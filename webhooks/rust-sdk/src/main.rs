use axum::{Router, extract::State, routing::{get, post}};
use axum::body::Bytes;
use dotenv::dotenv;
use payabli_api::prelude::*;
use reqwest::Client as HttpClient;
use std::env;
use std::io::{self, Write};
use tokio::sync::mpsc;
use tokio::time::{Duration, timeout};

// ── Shared state passed into the axum handler ──────────────────────────────
#[derive(Clone)]
struct AppState {
    tx: mpsc::UnboundedSender<String>,
}

async fn index_handler() -> &'static str {
    "Payabli Webhook Test"
}

async fn webhook_handler(State(state): State<AppState>, body: Bytes) {
    let payload = String::from_utf8_lossy(&body).to_string();
    println!(
        "[webhook handler] received {} bytes, pushing to queue",
        payload.len()
    );
    let _ = state.tx.send(payload);
}

fn prompt(message: &str) -> String {
    print!("{}", message);
    io::stdout().flush().unwrap();
    let mut input = String::new();
    io::stdin().read_line(&mut input).unwrap();
    input.trim().to_string()
}

/// POST a small test payload to the tunnel to confirm it is live.
async fn test_tunnel(webhook_url: &str) {
    println!("\nTesting tunnel by POSTing to {}...", webhook_url);
    let client = HttpClient::new();
    match client
        .post(webhook_url)
        .header("Content-Type", "application/json")
        .body(r#"{"test":"ping"}"#)
        .send()
        .await
    {
        Ok(resp) => println!("Tunnel test response: HTTP {}", resp.status()),
        Err(e) => eprintln!("Tunnel test FAILED – tunnel may not be running or URL is wrong: {e}"),
    }
}

/// Register an ApprovedPayment notification pointing at the exposed /webhook endpoint.
async fn create_webhook_notification(
    client: &ApiClient,
    base_url: &str,
    owner_id: i64,
) {
    let webhook_url = format!("{}/webhook", base_url);
    println!("\nRegistering webhook notification with Payabli...");
    println!(
        "Notification request: Target={}, OwnerId={}",
        webhook_url, owner_id
    );

    let request =
        AddNotificationRequest::NotificationStandardRequest(NotificationStandardRequest {
            content: Some(NotificationStandardRequestContent {
                event_type: Some(
                    NotificationStandardRequestContentEventType::ApprovedPayment,
                ),
                internal_data: None,
                transaction_id: None,
                web_header_parameters: None,
            }),
            frequency: NotificationStandardRequestFrequency::Untilcancelled,
            method: NotificationStandardRequestMethod::Web,
            owner_id: Some(Ownerid(owner_id)),
            owner_type: Ownertype(0),
            status: Some(Statusnotification(1)),
            target: webhook_url.clone(),
        });

    if let Ok(json) = serde_json::to_string(&request) {
        println!("Notification request body: {}", json);
    }

    match client.notification.add_notification(&request, None).await {
        Ok(resp) => {
            println!(
                "Webhook registered: IsSuccess={:?}, ResponseCode={:?}, NotificationId={:?}",
                resp.is_success, resp.response_code, resp.response_data
            );
            if !resp.is_success.map(|s| s.0).unwrap_or(false) {
                eprintln!(
                    "WARNING: Notification registration failed – ResponseText: {:?}",
                    resp.response_text
                );
                eprintln!(
                    "No webhook will be delivered. Check your PAYABLI_KEY, OWNER_ID, and PAYABLI_ENTRY."
                );
            }
        }
        Err(e) => eprintln!("Failed to register webhook: {e:?}"),
    }
}

/// Send a test $1.00 credit-card transaction against the configured entrypoint.
async fn trigger_transaction(client: &ApiClient, entrypoint: &str) {
    println!("\nTriggering a test transaction to generate webhook...");
    println!(
        "Transaction request: EntryPoint={}, Amount=1.00",
        entrypoint
    );
    let req = GetpaidRequest {
        body: TransRequestBody {
            account_id: None,
            customer_data: Some(PayorDataRequest {
                customer_id: Some(CustomerId(4440)),
                additional_data: None,
                billing_address_1: None,
                billing_address_2: None,
                billing_city: None,
                billing_country: None,
                billing_email: None,
                billing_phone: None,
                billing_state: None,
                billing_zip: None,
                company: None,
                customer_number: None,
                first_name: None,
                identifier_fields: None,
                last_name: None,
                shipping_address_1: None,
                shipping_address_2: None,
                shipping_city: None,
                shipping_country: None,
                shipping_state: None,
                shipping_zip: None,
            }),
            entry_point: Some(Entrypointfield(entrypoint.to_string())),
            invoice_data: None,
            ipaddress: Some(IpAddress("255.255.255.255".to_string())),
            order_description: None,
            order_id: None,
            payment_details: PaymentDetail {
                categories: None,
                check_image: None,
                check_number: None,
                currency: None,
                service_fee: Some(0.0),
                split_funding: None,
                check_unique_id: None,
                total_amount: 1.00,
            },
            payment_method: PaymentMethod::PayMethodCredit(PayMethodCredit {
                cardcvv: Some(Cardcvv("999".to_string())),
                cardexp: Cardexp("02/27".to_string()),
                card_holder: Some(Cardholder("Test User".to_string())),
                cardnumber: Cardnumber("4111111111111111".to_string()),
                cardzip: Some(Cardzip("12345".to_string())),
                initiator: Some(Initiator("payor".to_string())),
                method: "card".to_string(),
                save_if_success: None,
            }),
            source: None,
            subdomain: None,
            subscription_id: None,
        },
        ach_validation: None,
        force_customer_creation: None,
        include_details: None,
    };
    match client.money_in.getpaid(&req, None).await {
        Ok(resp) => println!("Transaction response: {:#?}", resp),
        Err(e) => eprintln!("Failed to trigger transaction: {e:?}"),
    }
}

// ── Main ───────────────────────────────────────────────────────────────────

#[tokio::main]
async fn main() {
    dotenv().ok();

    let api_key = env::var("PAYABLI_KEY").expect("PAYABLI_KEY missing in .env");
    let entrypoint = env::var("PAYABLI_ENTRY").expect("PAYABLI_ENTRY missing in .env");
    let owner_id: i64 = env::var("OWNER_ID")
        .expect("OWNER_ID missing in .env")
        .parse()
        .expect("OWNER_ID must be a valid integer");
    let port: u16 = env::var("PORT")
        .unwrap_or_else(|_| "3000".to_string())
        .parse()
        .expect("PORT must be a valid number");

    // ── Channel: handler → main ────────────────────────────────────────────
    let (tx, mut rx) = mpsc::unbounded_channel::<String>();
    let state = AppState { tx };

    // ── Start the HTTP server ──────────────────────────────────────────────
    let app = Router::new()
        .route("/", get(index_handler))
        .route("/webhook", post(webhook_handler))
        .with_state(state);

    let addr: std::net::SocketAddr = format!("0.0.0.0:{}", port).parse().unwrap();
    let listener = tokio::net::TcpListener::bind(addr)
        .await
        .expect("Failed to bind TcpListener");
    println!("\nWebhook server listening on http://localhost:{}/webhook", port);

    tokio::spawn(async move {
        axum::serve(listener, app.into_make_service())
            .await
            .expect("Server error");
    });

    // ── Prompt for tunnel URL ──────────────────────────────────────────────
    println!("\nExpose your local server publicly (e.g. ngrok http {}, localhost.run, etc.)", port);
    let raw_url = prompt("Paste your public tunnel URL (e.g. https://xxxx.ngrok-free.app): ");
    let mut base_url = raw_url.trim_end_matches('/').to_string();
    if base_url.ends_with("/webhook") {
        base_url.truncate(base_url.len() - 8);
    }

    // ── Build Payabli client ───────────────────────────────────────────────
    let config = ClientConfig {
        api_key: Some(api_key.clone()),
        ..Default::default()
    };
    let client = ApiClient::new(config).expect("Failed to build Payabli client");

    // ── Verify the tunnel is reachable ─────────────────────────────────────
    test_tunnel(&format!("{}/webhook", base_url)).await;

    // ── Register the ApprovedPayment webhook ───────────────────────────────
    create_webhook_notification(&client, &base_url, owner_id).await;

    // ── Wait for confirmation before sending a live transaction ───────────
    prompt("\nPress ENTER to trigger a test transaction and generate a webhook (or Ctrl+C to exit)...");

    // Drain any payloads that arrived before the transaction (e.g. tunnel test ping).
    while rx.try_recv().is_ok() {}

    // ── Fire a $1.00 test transaction ──────────────────────────────────────
    trigger_transaction(&client, &entrypoint).await;

    // ── Self-test: POST directly to localhost /webhook ─────────────────────
    // Confirms the server+channel chain works independently.
    // If this appears in output but Payabli's delivery does not, the issue
    // is 100% on the notification registration side.
    println!("\n[self-test] POSTing directly to localhost to verify queue...");
    let self_test_url = format!("http://localhost:{}/webhook", port);
    let http = HttpClient::new();
    match http
        .post(&self_test_url)
        .header("Content-Type", "application/json")
        .body(r#"{"self-test":true}"#)
        .send()
        .await
    {
        Ok(resp) => println!("[self-test] POST to localhost /webhook: HTTP {}", resp.status()),
        Err(e) => eprintln!("[self-test] FAILED: {e}"),
    }

    // Drain the self-test ping before waiting for Payabli.
    match timeout(Duration::from_secs(3), rx.recv()).await {
        Ok(Some(_)) => println!("[self-test] self-test ping drained from queue."),
        _ => {}
    }

    println!("\nWaiting up to 30 seconds for Payabli webhook delivery...");
    println!("(Watch for '-> POST /webhook' in the server logs above - if it never appears, Payabli is not delivering to your tunnel URL)");

    // ── Wait for the real webhook ──────────────────────────────────────────
    match timeout(Duration::from_secs(30), rx.recv()).await {
        Ok(Some(payload)) => {
            println!(
                "\nReceived webhook payload:\n{}",
                if payload.is_empty() {
                    "(empty body)".to_string()
                } else {
                    payload
                }
            );

            // Keep printing any further deliveries for up to 60 s each.
            loop {
                match timeout(Duration::from_secs(60), rx.recv()).await {
                    Ok(Some(p)) => println!("\nReceived webhook payload:\n{}", p),
                    _ => break,
                }
            }
        }
        _ => {
            println!("\nNo webhook received within 30 seconds.");
            println!("Possible causes:");
            println!("  1. The notification was not registered successfully - check the output above.");
            println!("  2. The URL you pasted already included '/webhook' - target would be '.../webhook/webhook'.");
            println!("  3. Payabli is delivering to a previously-registered notification's dead URL.");
            println!("  4. The tunnel expired before Payabli made the delivery.");
        }
    }
}
