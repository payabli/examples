use axum::{
    extract::{Path, State},
    http::StatusCode,
    response::{Html, IntoResponse, Response},
    routing::{delete, get, post},
    Form, Router,
};
use askama::Template;
use payabli_api::prelude::*;
use serde::Deserialize;
use std::sync::Arc;
use tower_http::{services::ServeDir, trace::TraceLayer};
use tracing_subscriber::{layer::SubscriberExt, util::SubscriberInitExt};

// Application state
struct EnvConfig {
    entry_point: String,
    public_token: String,
    api_key: String,
}

struct AppState {
    payabli_client: ApiClient,
    env_config: Arc<EnvConfig>,
}

// Templates
#[derive(Template)]
#[template(path = "base.html")]
struct BaseTemplate<'a> {
    title: &'a str,
    content: &'a str,
}

#[derive(Template)]
#[template(path = "create.html")]
struct CreateTemplate;

#[derive(Template)]
#[template(path = "list.html")]
struct ListTemplate;

#[derive(Template)]
#[template(path = "transaction.html")]
struct TransactionTemplate {
    public_token: String,
    entry_point: String,
}

// Form structures
#[derive(Deserialize)]
struct CreateCustomerForm {
    firstname: String,
    lastname: String,
    email: String,
    #[serde(rename = "timeZone")]
    time_zone: String,
    address: String,
    city: String,
    state: String,
    zip: String,
    country: String,
    hvac: Option<String>,
    electrical: Option<String>,
    terms: String,
}

#[tokio::main]
async fn main() {
    // Initialize tracing
    tracing_subscriber::registry()
        .with(
            tracing_subscriber::EnvFilter::try_from_default_env()
                .unwrap_or_else(|_| "rust_sdk_example=debug,tower_http=debug".into()),
        )
        .with(tracing_subscriber::fmt::layer())
        .init();

    // Load environment variables
    dotenvy::dotenv().ok();

    let api_key = std::env::var("PAYABLI_KEY").expect("PAYABLI_KEY must be set");
    let env_config = Arc::new(EnvConfig {
        entry_point: std::env::var("PAYABLI_ENTRY").expect("PAYABLI_ENTRY must be set"),
        public_token: std::env::var("PAYABLI_PUBLIC_TOKEN").expect("PAYABLI_PUBLIC_TOKEN must be set"),
        api_key: std::env::var("PAYABLI_KEY").expect("PAYABLI_KEY must be set"),
    });

    // Initialize Payabli client
    let config = ClientConfig {
        api_key: Some(env_config.api_key.clone()),
        ..Default::default()
    };
    let payabli_client = ApiClient::new(config).expect("Failed to build client");

    let state = Arc::new(AppState {
        payabli_client,
        env_config,
    });

    // Build router
    let app = Router::new()
        .route("/", get(create_customer_page))
        .route("/list", get(list_customers_page))
        .route("/transaction", get(make_transaction_page))
        .route("/api/create", post(create_customer))
        .route("/api/list", get(list_customers))
        .route("/api/delete/:customer_id", delete(delete_customer))
        .route("/api/transaction/:token", post(process_transaction))
        .nest_service("/static", ServeDir::new("static"))
        .layer(TraceLayer::new_for_http())
        .with_state(state);

    let listener = tokio::net::TcpListener::bind("0.0.0.0:3000").await.unwrap();
    tracing::info!("listening on {}", listener.local_addr().unwrap());
    axum::serve(listener, app).await.unwrap();
}

async fn create_customer_page() -> impl IntoResponse {
    HtmlTemplate(CreateTemplate)
}

async fn list_customers_page() -> impl IntoResponse {
    HtmlTemplate(ListTemplate)
}

async fn make_transaction_page(State(state): State<Arc<AppState>>) -> impl IntoResponse {
    HtmlTemplate(TransactionTemplate {
        public_token: state.env_config.public_token.to_string(),
        entry_point: state.env_config.entry_point.to_string(),
    })
}

async fn create_customer(
    State(state): State<Arc<AppState>>,
    Form(form): Form<CreateCustomerForm>,
) -> Response {
    let time_zone = form.time_zone.clone();

    let mut additional_fields = std::collections::HashMap::new();
    if let Some(hvac) = form.hvac {
        additional_fields.insert("hvac".to_string(), Some(hvac));
    }
    if let Some(electrical) = form.electrical {
        additional_fields.insert("electrical".to_string(), Some(electrical));
    }

    let result = state
        .payabli_client
        .customer
        .add_customer(&Entrypointfield(state.env_config.entry_point.clone()), &AddCustomerRequest {
            body: CustomerData {
                customer_number: None,
                customer_username: None,
                customer_psw: None,
                customer_status: None,
                company: None,
                firstname: Some(form.firstname),
                lastname: Some(form.lastname),
                phone: None,
                email: Some(Email(form.email)),
                address: Some(form.address),
                address_1: None,
                city: Some(form.city),
                state: Some(form.state),
                zip: Some(form.zip),
                country: Some(form.country),
                shipping_address: None,
                shipping_address_1: None,
                shipping_city: None,
                shipping_state: None,
                shipping_zip: None,
                shipping_country: None,
                balance: None,
                time_zone: Some(Timezone(time_zone.parse::<i64>().unwrap())),
                additional_fields: if !additional_fields.is_empty() {
                    Some(additional_fields)
                } else {
                    None
                },
                identifier_fields: Some(Identifierfields(vec![Some("email".to_string())])),
                created_at: None,
            },
            replace_existing: Some(0),
            force_customer_creation: Some(false),
        }, None)
        .await;

    match result {
        Ok(_) => Html(
            r#"<input type="text" name="valid" value="Success!" aria-invalid="false" id="form-result" readonly>"#,
        )
        .into_response(),
        Err(e) => {
            tracing::error!("Error creating customer: {:?}", e);
            Html(
                r#"<input type="text" name="invalid" value="Error!" aria-invalid="true" id="form-result" readonly>"#,
            )
            .into_response()
        }
    }
}

async fn list_customers(State(state): State<Arc<AppState>>) -> Response {
    let result = state
        .payabli_client
        .query
        .list_customers(
            &Entry(state.env_config.entry_point.to_string()),
            &ListCustomersQueryRequest {
                export_format: None,
                parameters: None,
                from_record: None,
                limit_record: None,
                sort_by: None,
            },
            None,
        )
        .await;

    match result {
        Ok(response) => {
            let mut table_rows = String::new();

            if let Some(records) = response.records {
                for record in records {
                    let firstname = record.firstname.unwrap_or_default();
                    let lastname = record.lastname.unwrap_or_default();
                    let email = record.email.map(|e| e.0).unwrap_or_default();
                    let address = record.address.unwrap_or_default();
                    let city = record.city.unwrap_or_default();
                    let state = record.state.unwrap_or_default();
                    let zip = record.zip.unwrap_or(String::from("N/A"));
                    let time_zone = record.time_zone.map(|t| format!("{:?}", t)).unwrap_or_default();
                    let customer_id = record.customer_id.unwrap();

                    table_rows.push_str(&format!(
                        r#"
                        <tr>
                          <td>{}</td>
                          <td>{}</td>
                          <td>{}</td>
                          <td>{}</td>
                          <td>{}</td>
                          <td>{}</td>
                          <td>{}</td>
                          <td>{}</td>
                          <td>
                            <button id="delete" class="outline"
                              hx-delete="/api/delete/{}"
                              hx-swap="innerHTML"
                              hx-target="closest tr"
                              hx-on="htmx:beforeRequest: this.setAttribute('aria-busy', 'true'), htmx:afterRequest: this.removeAttribute('aria-busy')"
                            >
                              ❌
                            </button>
                          </td>
                        </tr>
                        "#,
                        firstname, lastname, email, address, city, state, zip, time_zone, customer_id.0
                    ));
                }
            } else {
                table_rows = r#"
                    <tr>
                      <td colspan="9" style="text-align: center; color: #666;">
                        No customers found. Create a customer to get started!
                      </td>
                    </tr>
                "#
                .to_string();
            }

            let table = format!(
                r#"
                <table class="striped" style="layout: fixed">
                  <thead>
                    <tr>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Email</th>
                      <th>Address</th>
                      <th>City</th>
                      <th>State</th>
                      <th>Zip</th>
                      <th>Time Zone</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    {}
                  </tbody>
                </table>
                "#,
                table_rows
            );

            Html(table).into_response()
        }
        Err(e) => {
            tracing::error!("Error listing customers: {:?}", e);
            Html("<p>Error loading customers. Please check your API credentials and try again.</p>")
                .into_response()
        }
    }
}

async fn delete_customer(
    State(state): State<Arc<AppState>>,
    Path(customer_id): Path<String>,
) -> Response {
    let customer_id_int = match customer_id.parse::<i64>() {
        Ok(id) => id,
        Err(_) => {
            return Html(r#"<td colspan="9">Invalid customer ID</td>"#).into_response();
        }
    };

    let result = state
        .payabli_client
        .customer
        .delete_customer(customer_id_int, None)
        .await;

    match result {
        Ok(_) => (StatusCode::OK, Html("")).into_response(),
        Err(e) => {
            tracing::error!("Error deleting customer: {:?}", e);
            Html(format!(r#"<td colspan="9">Error deleting customer: {}</td>"#, e))
                .into_response()
        }
    }
}

async fn process_transaction(
    State(state): State<Arc<AppState>>,
    Path(token): Path<String>,
) -> Response {
    // Step 1: Convert temporary token to permanent
    let token_result = state
        .payabli_client
        .token_storage
        .add_method(&AddMethodRequest {
            body: RequestTokenStorage {
                customer_data: Some(PayorDataRequest {
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
                    customer_id: Some(CustomerId(4440)),
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
                entry_point: Some(Entrypointfield(state.env_config.entry_point.to_string())),
                fallback_auth: Some(true),
                fallback_auth_amount: None,
                method_description: Some("Main card".to_string()),
                payment_method: Some(RequestTokenStoragePaymentMethod::ConvertToken(
                    ConvertToken {
                        method: "card".to_string(),
                        token_id: token, // Replace with actual token ID
                    },
                )),
                vendor_data: None,
                source: Some(Source("web".to_string())),
                subdomain: None,
            },
            ..Default::default()
        }, None)
        .await;

    let stored_method_id = match token_result {
        Ok(response) => response.response_data.unwrap().reference_id,
        Err(e) => {
            tracing::error!("Error storing token: {:?}", e);
            return Html(
                r#"<input type="text" name="invalid" value="Error processing payment" aria-invalid="true" readonly>"#,
            )
            .into_response();
        }
    };

    // Step 2: Process payment using stored method
    let payment_result = state
        .payabli_client
        .money_in
        .getpaid(&GetpaidRequest {
            ach_validation: None,
            force_customer_creation: None,
            include_details: None,
            body: TransRequestBody {
                account_id: None,
                order_description: None,
                invoice_data: None,
                source: None,
                order_id: None,
                subscription_id: None,
                subdomain: None,
                customer_data: Some(PayorDataRequest {
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
                    customer_id: Some(CustomerId(4440)),
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
                entry_point: Some(Entrypointfield(state.env_config.entry_point.to_string())),
                ipaddress: Some(IpAddress("255.255.255.255".to_string())),
                payment_details: PaymentDetail {
                    service_fee: Some(0.0),
                    total_amount: 100.0,
                    categories: None,
                    check_image: None,
                    check_number: None,
                    currency: None,
                    split_funding: None,
                    ..Default::default()
                },
                payment_method: PaymentMethod::PayMethodStoredMethod(PayMethodStoredMethod {
                    initiator: Some(Initiator("payor".to_string())),
                    method: PayMethodStoredMethodMethod::Card,
                    stored_method_id: Some(Storedmethodid(stored_method_id.unwrap().0)),
                    stored_method_usage_type: Some(StoredMethodUsageType("unscheduled".to_string())),
                }),
            },
        }, None)
        .await;

    match payment_result {
        Ok(_) => Html(
            r#"<input type="text" name="valid" value="Payment processed successfully!" aria-invalid="false" readonly>"#,
        )
        .into_response(),
        Err(e) => {
            tracing::error!("Error processing payment: {:?}", e);
            Html(format!(
                r#"<input type="text" name="invalid" value="Payment failed: {}" aria-invalid="true" readonly>"#,
                e
            ))
            .into_response()
        }
    }
}

// Helper to render templates
struct HtmlTemplate<T>(T);

impl<T> IntoResponse for HtmlTemplate<T>
where
    T: Template,
{
    fn into_response(self) -> Response {
        match self.0.render() {
            Ok(html) => Html(html).into_response(),
            Err(err) => (
                StatusCode::INTERNAL_SERVER_ERROR,
                format!("Failed to render template. Error: {}", err),
            )
                .into_response(),
        }
    }
}
