using DotNetEnv;
using PayabliApi;
using System.Text;

// Load environment variables from .env file.
Env.Load();

var port = Environment.GetEnvironmentVariable("PORT") ?? "3000";

var apiKey = Environment.GetEnvironmentVariable("PAYABLI_KEY")
    ?? throw new InvalidOperationException("PAYABLI_KEY missing in .env");

var entrypoint = Environment.GetEnvironmentVariable("PAYABLI_ENTRY")
    ?? throw new InvalidOperationException("PAYABLI_ENTRY missing in .env");

var ownerIdStr = Environment.GetEnvironmentVariable("OWNER_ID")
    ?? throw new InvalidOperationException("OWNER_ID missing in .env");

if (!int.TryParse(ownerIdStr, out var ownerId))
    throw new InvalidOperationException("OWNER_ID must be a valid integer");

// Build the web application. Suppress the default ASP.NET banner and
// verbose host logging so the output stays focused on webhook events.
var builder = WebApplication.CreateSlimBuilder(args);
builder.WebHost.UseUrls($"http://0.0.0.0:{port}");
builder.Logging.ClearProviders();

var app = builder.Build();

// Request logger middleware — logs every incoming HTTP request so it is easy
// to see if Payabli delivers to a path other than /webhook.
app.Use(async (ctx, next) =>
{
    Console.WriteLine(
        $"→ {ctx.Request.Method} {ctx.Request.Path} " +
        $"(Content-Type: {ctx.Request.ContentType}, " +
        $"User-Agent: {ctx.Request.Headers.UserAgent})");
    await next(ctx);
});

// GET / — simple status page.
app.MapGet("/", () => "Payabli Webhook Test");

// POST /webhook — receives the Payabli notification, prints the raw body,
// and returns HTTP 200 OK so Payabli knows the delivery succeeded.
app.MapPost("/webhook", async (HttpContext ctx) =>
{
    using var reader = new StreamReader(ctx.Request.Body);
    var body = await reader.ReadToEndAsync();
    Console.WriteLine($"\nReceived webhook payload:\n{body}\n");
    return Results.Ok();
});

// Start the server in the background so the main thread can continue
// with the interactive setup flow.
await app.StartAsync();
Console.WriteLine($"\nWebhook server listening on http://localhost:{port}/webhook");

// --- Interactive setup ---

Console.WriteLine($"\nNow open a new terminal and run: ngrok http {port}");
Console.Write("Paste your public Ngrok URL (e.g. https://xxxx.ngrok.io): ");
var ngrokUrl = (Console.ReadLine() ?? "").Trim();

// Self-test the tunnel before registering with Payabli.
await TestNgrokTunnel(ngrokUrl);

// Build the Payabli SDK client using the API key from .env.
var client = new PayabliApiClient(apiKey);

// Register the ApprovedPayment notification so Payabli knows where to POST.
await CreateWebhookNotification(client, ngrokUrl, ownerId);

// Wait for the user to confirm before firing a live transaction.
Console.Write("\nPress ENTER to trigger a test transaction and generate a webhook (or Ctrl+C to exit)...");
Console.ReadLine();

await TriggerTransaction(client, entrypoint);

// After the transaction fires, poll Payabli's notification delivery logs every
// 15 seconds. This shows whether Payabli is attempting delivery (and the result)
// or never attempting at all.
_ = Task.Run(() => PollNotificationLogsAsync(client, ownerId));

Console.WriteLine("\nWaiting for webhook event. Check your terminal for output when received.");

// Block until the process is shut down (Ctrl+C).
await app.WaitForShutdownAsync();


// ---- Helper methods ----

// TestNgrokTunnel POSTs a small test payload to the webhook URL to confirm
// the ngrok tunnel is reachable before registering with Payabli.
static async Task TestNgrokTunnel(string ngrokUrl)
{
    var webhookUrl = ngrokUrl.TrimEnd('/') + "/webhook";
    Console.WriteLine($"\nTesting ngrok tunnel by POSTing to {webhookUrl}...");

    using var http = new HttpClient();
    try
    {
        var response = await http.PostAsync(
            webhookUrl,
            new StringContent("{\"test\":\"ping\"}", Encoding.UTF8, "application/json"));
        Console.WriteLine($"Tunnel test response: HTTP {(int)response.StatusCode}");
    }
    catch (Exception ex)
    {
        Console.WriteLine($"Tunnel test FAILED - ngrok may not be running or URL is wrong: {ex.Message}");
    }
}

// CreateWebhookNotification registers an ApprovedPayment webhook notification
// with Payabli, pointing at the ngrok-exposed /webhook endpoint.
static async Task CreateWebhookNotification(PayabliApiClient client, string ngrokUrl, int ownerId)
{
    var webhookUrl = ngrokUrl.TrimEnd('/') + "/webhook";
    Console.WriteLine("\nRegistering webhook notification with Payabli...");

    var request = new NotificationStandardRequest
    {
        Content = new NotificationStandardRequestContent
        {
            // Fire this notification for every ApprovedPayment event.
            EventType = NotificationStandardRequestContentEventType.ApprovedPayment
        },
        // Keep sending until manually cancelled.
        Frequency = NotificationStandardRequestFrequency.Untilcancelled,
        // Deliver via HTTP POST.
        Method = NotificationStandardRequestMethod.Web,
        // 0 = Org owner type.
        OwnerId = ownerId,
        OwnerType = 0,
        // 1 = Active.
        Status = 1,
        Target = webhookUrl
    };

    Console.WriteLine($"Notification request: Target={request.Target}, OwnerId={request.OwnerId}");

    try
    {
        var response = await client.Notification.AddNotificationAsync(request);
        // ResponseData is Nullable<OneOf<int, string>> — read it safely to avoid
        // the OneOf serialization exception thrown by JsonSerializer.
        string notifId = "null";
        if (response.ResponseData.HasValue)
        {
            var data = response.ResponseData.Value;
            notifId = data.IsT0 ? data.AsT0.ToString() : data.AsT1;
        }
        Console.WriteLine(
            $"Webhook registered: IsSuccess={response.IsSuccess}, " +
            $"ResponseCode={response.ResponseCode}, NotificationId={notifId}");
    }
    catch (Exception ex)
    {
        Console.WriteLine($"Failed to register webhook: {ex.Message}");
    }
}

// TriggerTransaction sends a test $1.00 credit card transaction against the
// configured entrypoint to generate an ApprovedPayment event and trigger the webhook.
static async Task TriggerTransaction(PayabliApiClient client, string entrypoint)
{
    Console.WriteLine("\nTriggering a test transaction to generate webhook...");

    var request = new RequestPayment
    {
        Body = new TransRequestBody
        {
            CustomerData = new PayorDataRequest { CustomerId = 4440 },
            EntryPoint = entrypoint,
            Ipaddress = "255.255.255.255",
            PaymentDetails = new PaymentDetail
            {
                ServiceFee = 0,
                TotalAmount = 1.00
            },
            PaymentMethod = new PayMethodCredit
            {
                Cardcvv = "999",
                Cardexp = "02/27",
                CardHolder = "Test User",
                Cardnumber = "4111111111111111",
                Cardzip = "12345",
                Initiator = "payor"
            }
        }
    };

    Console.WriteLine(
        $"Transaction request: EntryPoint={request.Body?.EntryPoint}, " +
        $"Amount={request.Body?.PaymentDetails?.TotalAmount}");

    try
    {
        var response = await client.MoneyIn.GetpaidAsync(request);
        Console.WriteLine(
            $"Transaction sent: IsSuccess={response.IsSuccess}, " +
            $"ResponseText={response.ResponseText}");
    }
    catch (Exception ex)
    {
        Console.WriteLine($"Failed to trigger transaction: {ex.Message}");
    }
}

// PollNotificationLogsAsync queries Payabli's notification delivery log every
// 15 seconds after the transaction fires. This shows whether Payabli is
// attempting webhook delivery, the target URL it is posting to, and whether
// it succeeded or failed.
static async Task PollNotificationLogsAsync(PayabliApiClient client, int ownerId)
{
    Console.WriteLine("\nPolling notification delivery logs every 15s (first check in 10s)...");
    await Task.Delay(TimeSpan.FromSeconds(10));

    using var timer = new PeriodicTimer(TimeSpan.FromSeconds(15));
    do
    {
        var now = DateTime.UtcNow;
        var start = now.AddMinutes(-10);

        var request = new SearchNotificationLogsRequest
        {
            PageSize = 10,
            Body = new NotificationLogSearchRequest
            {
                StartDate = start,
                EndDate = now,
                OrgId = (long)ownerId,
                NotificationEvent = "ApprovedPayment"
            }
        };

        try
        {
            var logs = (await client.Notificationlogs.SearchNotificationLogsAsync(request)).ToList();
            if (logs.Count == 0)
                Console.WriteLine("[Notification logs] No ApprovedPayment delivery attempts found in the last 10 minutes.");
            else
                foreach (var entry in logs)
                    Console.WriteLine(
                        $"[Notification logs] Delivery attempt: " +
                        $"Id={entry.Id}, Target={entry.Target}, " +
                        $"Event={entry.NotificationEvent}, Success={entry.Success}, " +
                        $"ResponseStatus={entry.ResponseStatus}, " +
                        $"CreatedDate={entry.CreatedDate:O}");
        }
        catch (Exception ex)
        {
            Console.WriteLine($"[Notification logs] Query error: {ex.Message}");
        }
    } while (await timer.WaitForNextTickAsync());
}
