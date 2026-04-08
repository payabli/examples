package main

import (
	"bufio"
	"context"
	"fmt"
	"io"
	"log"
	"net/http"
	"os"
	"strings"
	"time"

	"github.com/joho/godotenv"
	payabli "github.com/payabli/sdk-go"
	payabliclient "github.com/payabli/sdk-go/client"
	"github.com/payabli/sdk-go/option"
)

// indexHandler handles GET / and returns a simple status message.
func indexHandler(w http.ResponseWriter, r *http.Request) {
	fmt.Fprintln(w, "Payabli Webhook Test")
}

// webhookHandler handles POST /webhook and prints the raw request body.
// It returns HTTP 200 OK so Payabli knows the notification was received.
func webhookHandler(w http.ResponseWriter, r *http.Request) {
	body, err := io.ReadAll(r.Body)
	if err != nil {
		log.Printf("Error reading webhook body: %v", err)
		http.Error(w, "error reading body", http.StatusInternalServerError)
		return
	}
	defer r.Body.Close()

	fmt.Printf("\nReceived webhook payload:\n%s\n", string(body))
	w.WriteHeader(http.StatusOK)
}

// stdinReader is a single shared reader for stdin.
// Using a single bufio.Reader avoids the buffering issue that arises when
// multiple bufio.Scanners are created over the same file descriptor — each
// scanner pre-reads into its own internal buffer and the leftover bytes are
// lost when the scanner is discarded.
var stdinReader = bufio.NewReader(os.Stdin)

// prompt prints a message and reads a line from stdin.
func prompt(message string) string {
	fmt.Print(message)
	line, _ := stdinReader.ReadString('\n')
	return strings.TrimSpace(line)
}

// requestLogger is middleware that logs every incoming HTTP request before
// passing it to the next handler. This makes it easy to see if Payabli is
// delivering webhooks to a path other than /webhook.
func requestLogger(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		log.Printf("→ %s %s (Content-Type: %s, User-Agent: %s)",
			r.Method, r.URL.Path,
			r.Header.Get("Content-Type"),
			r.Header.Get("User-Agent"),
		)
		next.ServeHTTP(w, r)
	})
}

// testNgrokTunnel POSTs a small test payload to the webhook URL to confirm the tunnel is live.
func testNgrokTunnel(ngrokURL string) {
	webhookURL := strings.TrimRight(ngrokURL, "/") + "/webhook"
	fmt.Printf("\nTesting ngrok tunnel by POSTing to %s...\n", webhookURL)

	resp, err := http.Post(webhookURL, "application/json", strings.NewReader(`{"test":"ping"}`))
	if err != nil {
		log.Printf("Tunnel test FAILED - ngrok may not be running or URL is wrong: %v", err)
		return
	}
	defer resp.Body.Close()
	fmt.Printf("Tunnel test response: HTTP %d\n", resp.StatusCode)
}

// createWebhookNotification registers an ApprovedPayment webhook notification with Payabli,
// pointing at the ngrok-exposed /webhook endpoint.
func createWebhookNotification(c *payabliclient.Client, ngrokURL string, ownerID int) {
	webhookURL := strings.TrimRight(ngrokURL, "/") + "/webhook"

	fmt.Println("\nRegistering webhook notification with Payabli...")

	request := &payabli.AddNotificationRequest{
		NotificationStandardRequest: &payabli.NotificationStandardRequest{
			Content: &payabli.NotificationStandardRequestContent{
				// Fire this notification for every ApprovedPayment event.
				EventType: payabli.NotificationStandardRequestContentEventTypeApprovedPayment.Ptr(),
			},
			// Keep sending until manually cancelled.
			Frequency: payabli.NotificationStandardRequestFrequencyUntilcancelled,
			// Deliver via HTTP POST.
			Method: payabli.NotificationStandardRequestMethodWeb,
			// 0 = Org owner type.
			OwnerId:   payabli.Int(ownerID),
			OwnerType: 0,
			// 1 = Active.
			Status: payabli.Int(1),
			Target: webhookURL,
		},
	}

	fmt.Printf("Notification request body: %+v\n", request)

	resp, err := c.Notification.AddNotification(context.Background(), request)
	if err != nil {
		log.Printf("Failed to register webhook: %v", err)
		return
	}
	fmt.Printf("Webhook registered: %+v\n", resp)
}

// triggerTransaction sends a test $1.00 credit card transaction against the configured
// entrypoint to generate an ApprovedPayment event and trigger the webhook.
func triggerTransaction(c *payabliclient.Client, entrypoint string) {
	fmt.Println("\nTriggering a test transaction to generate webhook...")

	request := &payabli.RequestPayment{
		Body: &payabli.TransRequestBody{
			CustomerData: &payabli.PayorDataRequest{
				CustomerId: payabli.Int64(int64(4440)),
			},
			EntryPoint: payabli.String(entrypoint),
			Ipaddress:  payabli.String("255.255.255.255"),
			PaymentDetails: &payabli.PaymentDetail{
				ServiceFee:  payabli.Float64(0),
				TotalAmount: 1.00,
			},
			PaymentMethod: &payabli.PaymentMethod{
				PayMethodCredit: &payabli.PayMethodCredit{
					Cardcvv:    payabli.String("999"),
					Cardexp:    "02/27",
					CardHolder: payabli.String("Test User"),
					Cardnumber: "4111111111111111",
					Cardzip:    payabli.String("12345"),
					Initiator:  payabli.String("payor"),
				},
			},
		},
	}

	fmt.Printf("Transaction request body: %+v\n", request)

	resp, err := c.MoneyIn.Getpaid(context.Background(), request)
	if err != nil {
		log.Printf("Failed to trigger transaction: %v", err)
		return
	}
	fmt.Printf("Transaction sent: %+v\n", resp)
}

func main() {
	// Load environment variables from the .env file.
	if err := godotenv.Load(); err != nil {
		log.Fatal("Error loading .env file — copy .env.example to .env and fill in your values")
	}

	port := os.Getenv("PORT")
	if port == "" {
		port = "3000"
	}

	apiKey := os.Getenv("PAYABLI_KEY")
	if apiKey == "" {
		log.Fatal("PAYABLI_KEY missing in .env")
	}

	entrypoint := os.Getenv("PAYABLI_ENTRY")
	if entrypoint == "" {
		log.Fatal("PAYABLI_ENTRY missing in .env")
	}

	ownerIDStr := os.Getenv("OWNER_ID")
	if ownerIDStr == "" {
		log.Fatal("OWNER_ID missing in .env")
	}

	var ownerID int
	if _, err := fmt.Sscanf(ownerIDStr, "%d", &ownerID); err != nil {
		log.Fatalf("OWNER_ID must be a valid integer: %v", err)
	}

	// Register the webhook and index routes, then start the HTTP server in
	// a background goroutine so the main goroutine can continue with setup.
	mux := http.NewServeMux()
	mux.HandleFunc("/", indexHandler)
	mux.HandleFunc("/webhook", webhookHandler)

	server := &http.Server{
		Addr:    "0.0.0.0:" + port,
		Handler: requestLogger(mux),
	}

	go func() {
		fmt.Printf("\nWebhook server listening on http://localhost:%s/webhook\n", port)
		if err := server.ListenAndServe(); err != nil && err != http.ErrServerClosed {
			log.Fatalf("Failed to start server: %v", err)
		}
	}()

	// Give the server a moment to bind before we proceed.
	time.Sleep(100 * time.Millisecond)

	// Prompt the user to expose the local server through ngrok.
	fmt.Printf("\nNow open a new terminal and run: ngrok http %s\n", port)
	ngrokURL := prompt("Paste your public Ngrok URL (e.g. https://xxxx.ngrok.io): ")

	// Self-test the tunnel to confirm end-to-end connectivity before registering with Payabli.
	testNgrokTunnel(ngrokURL)
	time.Sleep(500 * time.Millisecond)

	// Build the Payabli SDK client using the API key from .env.
	c := payabliclient.NewClient(option.WithApiKey(apiKey))

	// Register the ApprovedPayment notification so Payabli knows where to POST webhooks.
	createWebhookNotification(c, ngrokURL, ownerID)

	// Wait for the user to confirm before firing a real test transaction.
	prompt("\nPress ENTER to trigger a test transaction and generate a webhook (or Ctrl+C to exit)...")
	triggerTransaction(c, entrypoint)

	fmt.Println("\nWaiting for webhook event. Check your terminal for output when received.")

	// Block until the process is interrupted (Ctrl+C).
	select {}
}
