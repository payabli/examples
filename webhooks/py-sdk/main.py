import os
import threading
import time
import requests
from dotenv import load_dotenv
from flask import Flask, request

import payabli
from payabli.types import (
    NotificationStandardRequest,
    NotificationStandardRequestContent,
    PayMethodCredit,
    PaymentDetail,
    PayorDataRequest,
)

# Load environment variables from .env file.
load_dotenv()

PORT = int(os.environ.get("PORT", "3000"))
API_KEY = os.environ.get("PAYABLI_KEY") or ""
ENTRYPOINT = os.environ.get("PAYABLI_ENTRY") or ""
OWNER_ID = int(os.environ.get("OWNER_ID", "0"))

if not API_KEY:
    raise SystemExit("PAYABLI_KEY missing in .env")
if not ENTRYPOINT:
    raise SystemExit("PAYABLI_ENTRY missing in .env")
if not OWNER_ID:
    raise SystemExit("OWNER_ID missing in .env")

# ----- Flask server -----

app = Flask(__name__)

# Silence Flask's default request logger so only our own print() calls appear.
import logging
log = logging.getLogger("werkzeug")
log.setLevel(logging.ERROR)


@app.before_request
def log_request():
    """Log every incoming HTTP request so it is easy to see if Payabli delivers
    to a path other than /webhook."""
    print(
        f"→ {request.method} {request.path} "
        f"(Content-Type: {request.content_type}, "
        f"User-Agent: {request.headers.get('User-Agent', '')})",
        flush=True,
    )


@app.get("/")
def index():
    return "Payabli Webhook Test"


@app.post("/webhook")
def webhook():
    """Receive a Payabli notification, print the raw body, and return 200 OK."""
    body = request.data.decode("utf-8", errors="replace")
    print(f"\nReceived webhook payload:\n{body if body else '(empty body)'}\n", flush=True)
    return "", 200


# ----- Helper functions -----

def test_ngrok_tunnel(ngrok_url: str) -> None:
    """POST a small test payload to the webhook URL to confirm the tunnel is live."""
    webhook_url = ngrok_url.rstrip("/") + "/webhook"
    print(f"\nTesting ngrok tunnel by POSTing to {webhook_url}...")
    try:
        resp = requests.post(
            webhook_url,
            json={"test": "ping"},
            timeout=10,
        )
        print(f"Tunnel test response: HTTP {resp.status_code}")
    except Exception as e:
        print(f"Tunnel test FAILED - ngrok may not be running or URL is wrong: {e}")


def create_webhook_notification(client: payabli.payabli, ngrok_url: str, owner_id: int) -> None:
    """Register an ApprovedPayment webhook notification with Payabli."""
    webhook_url = ngrok_url.rstrip("/") + "/webhook"
    print("\nRegistering webhook notification with Payabli...")
    print(f"Notification request: Target={webhook_url}, OwnerId={owner_id}")

    try:
        response = client.notification.add_notification(
            request=NotificationStandardRequest(
                content=NotificationStandardRequestContent(
                    # Fire this notification for every ApprovedPayment event.
                    event_type="ApprovedPayment",
                ),
                # Keep sending until manually cancelled.
                frequency="untilcancelled",
                # Deliver via HTTP POST.
                method="web",
                # 0 = Org owner type.
                owner_id=owner_id,
                owner_type=0,
                # 1 = Active.
                status=1,
                target=webhook_url,
            )
        )
        # ResponseData holds the new notification ID (an int).
        print(
            f"Webhook registered: IsSuccess={response.is_success}, "
            f"ResponseCode={response.response_code}, "
            f"NotificationId={response.response_data}"
        )
    except Exception as e:
        print(f"Failed to register webhook: {e}")


def trigger_transaction(client: payabli.payabli, entrypoint: str) -> None:
    """Send a test $1.00 credit card transaction to generate an ApprovedPayment event."""
    print("\nTriggering a test transaction to generate webhook...")
    print(f"Transaction request: EntryPoint={entrypoint}, Amount=1.00")

    try:
        response = client.money_in.getpaid(
            payment_details=PaymentDetail(total_amount=1.00, service_fee=0),
            payment_method=PayMethodCredit(
                cardcvv="999",
                cardexp="02/27",
                card_holder="Test User",
                cardnumber="4111111111111111",
                cardzip="12345",
                initiator="payor",
            ),
            customer_data=PayorDataRequest(customer_id=4440),
            entry_point=entrypoint,
            ipaddress="255.255.255.255",
        )
        print(
            f"Transaction sent: IsSuccess={response.is_success}, "
            f"ResponseText={response.response_text}"
        )
    except Exception as e:
        print(f"Failed to trigger transaction: {e}")


# ----- Main -----

def main() -> None:
    # Start the Flask server in a background thread.
    server_thread = threading.Thread(
        target=lambda: app.run(host="0.0.0.0", port=PORT, use_reloader=False, threaded=True),
        daemon=True,
    )
    server_thread.start()

    # Give the server a moment to bind before prompting.
    time.sleep(0.5)
    print(f"\nWebhook server listening on http://localhost:{PORT}/webhook")

    # Prompt the user to expose the local server through ngrok.
    print(f"\nNow open a new terminal and run: ngrok http {PORT}")
    ngrok_url = input("Paste your public Ngrok URL (e.g. https://xxxx.ngrok.io): ").strip()

    test_ngrok_tunnel(ngrok_url)

    # Build the Payabli SDK client.
    client = payabli.payabli(api_key=API_KEY)

    create_webhook_notification(client, ngrok_url, OWNER_ID)

    input("\nPress ENTER to trigger a test transaction and generate a webhook (or Ctrl+C to exit)...")
    trigger_transaction(client, ENTRYPOINT)

    print("\nWaiting for webhook event. Check your terminal for output when received.")

    # Block until interrupted (Ctrl+C).
    try:
        while True:
            time.sleep(1)
    except KeyboardInterrupt:
        print("\nShutting down.")


if __name__ == "__main__":
    main()
