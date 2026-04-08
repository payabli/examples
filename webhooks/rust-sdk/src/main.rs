use axum::{routing::{get, post}, Router};
use std::env;
use dotenv::dotenv;
use std::io::{self, Write};
use payabli_api::prelude::*;
use reqwest::Client as HttpClient;



/// Handles GET requests to / and returns a simple status message.
async fn index_handler() -> &'static str {
    "Payabli Webhook Test"
}

/// Handles incoming POST requests to /webhook.
async fn webhook_handler(body: axum::body::Bytes) {
    println!("\nReceived webhook payload:");
    println!("{}", String::from_utf8_lossy(&body));
}

/// Prompt the user for input with a message and return the input string.
fn prompt(message: &str) -> String {
    print!("{}", message);
    io::stdout().flush().unwrap();
    let mut input = String::new();
    io::stdin().read_line(&mut input).unwrap();
    input.trim().to_string()
}

/// Verify the ngrok tunnel is working by POSTing a test payload to the webhook URL.
async fn test_ngrok_tunnel(ngrok_url: &str) {
    let webhook_url = format!("{}/webhook", ngrok_url.trim_end_matches('/'));
    println!("\nTesting ngrok tunnel by POSTing to {}...", webhook_url);
    let client = HttpClient::new();
    match client
        .post(&webhook_url)
        .header("Content-Type", "application/json")
        .body(r#"{"test":"ping"}"}"#)
        .send()
        .await
    {
        Ok(resp) => println!("Tunnel test response: HTTP {}", resp.status()),
        Err(e) => eprintln!("Tunnel test FAILED - ngrok may not be running or URL is wrong: {e}"),
    }
}

/// Register a webhook notification with Payabli for ApprovedPayment events.
async fn create_webhook_notification(client: &ApiClient, ngrok_url: &str, entrypoint: &str, owner_id: i64) {
    let _ = entrypoint; // silence unused variable warning
    println!("\nRegistering webhook notification with Payabli...");
    let webhook_url = format!("{}/webhook", ngrok_url.trim_end_matches('/'));
        let request = AddNotificationRequest::NotificationStandardRequest(NotificationStandardRequest {
            content: Some(NotificationStandardRequestContent {
                event_type: Some(NotificationStandardRequestContentEventType::ApprovedPayment),
                internal_data: None,
                transaction_id: None,
                web_header_parameters: None,
            }),
            frequency: NotificationStandardRequestFrequency::Untilcancelled,
            method: NotificationStandardRequestMethod::Web,
            owner_id: Some(Ownerid(owner_id)), // Use owner_id from parameter
            owner_type: Ownertype(0), // 0 = Org, 2 = Paypoint (default: Paypoint)
            status: Some(Statusnotification(1)), // 1 = Active
            target: webhook_url.clone(),
        });
    println!("Notification request body: {:#?}", request);
    match client.notification.add_notification(&request, None).await {
        Ok(resp) => println!("Webhook registered: {:#?}", resp),
        Err(e) => {
            eprintln!("Failed to register webhook: {e:?}");
            // Print debug info for troubleshooting
            eprintln!("Request sent: {:#?}", request);
        }
    }
}

/// Trigger a test transaction to generate an ApprovedPayment webhook.
async fn trigger_transaction(client: &ApiClient, entrypoint: &str) {
    println!("\nTriggering a test transaction to generate webhook...");
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
    println!("Transaction request body: {:#?}", req);
    match client.money_in.getpaid(&req, None).await {
        Ok(resp) => println!("Transaction sent: {:#?}", resp),
        Err(e) => eprintln!("Failed to trigger transaction: {e:?}"),
    }
}

#[tokio::main]
async fn main() {
    dotenv().ok();
    let port = env::var("PORT").unwrap_or_else(|_| "3000".to_string());
    let api_key = env::var("PAYABLI_KEY").expect("PAYABLI_KEY missing in .env");
    let entrypoint = env::var("PAYABLI_ENTRY").expect("PAYABLI_ENTRY missing in .env");
        // Load OWNER_ID from .env
        let owner_id: u64 = env::var("OWNER_ID")
            .expect("OWNER_ID missing in .env")
            .parse()
            .expect("OWNER_ID must be a valid integer");

    // Start webhook server in background FIRST
    let server_port = port.clone();
    let server = tokio::spawn(async move {
        let app = Router::new().route("/", get(index_handler)).route("/webhook", post(webhook_handler));
        let addr: std::net::SocketAddr = format!("0.0.0.0:{}", server_port).parse().unwrap();
        let listener = tokio::net::TcpListener::bind(addr).await.expect("Failed to bind TcpListener");
        println!("\nWebhook server listening on http://localhost:{}/webhook", server_port);
        axum::serve(listener, app.into_make_service())
            .await
            .expect("Failed to start server");
    });

    // Prompt user to open Ngrok and paste URL
    println!("\nNow open a new terminal and run: ngrok http {}", port);
    let ngrok_url = prompt("Paste your public Ngrok URL (e.g. https://xxxx.ngrok.io): ");

    // Set up Payabli client
    let config = ClientConfig {
        api_key: Some(api_key.clone()),
        ..Default::default()
    };
    let client = ApiClient::new(config).expect("Failed to build Payabli client");


    // Test the ngrok tunnel first before registering with Payabli
    test_ngrok_tunnel(&ngrok_url).await;
    tokio::time::sleep(std::time::Duration::from_millis(500)).await;

    // Register webhook notification with owner_id
    create_webhook_notification(&client, &ngrok_url, &entrypoint, owner_id as i64).await;

    // Prompt to trigger transaction
    let do_tx = prompt("\nPress ENTER to trigger a test transaction and generate a webhook (or Ctrl+C to exit)...");
    let _ = do_tx; // just to pause
    trigger_transaction(&client, &entrypoint).await;

    println!("\nWaiting for webhook event. Check your terminal for output when received.");

    // Wait for the server task to finish (it won't, unless killed)
    let _ = server.await;

}
