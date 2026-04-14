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

// Request logger middleware — logs every incoming HTTP request.
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
// and returns HTTP 200 OK.
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

Console.WriteLine($"\nExpose your local server publicly (e.g. ngrok http {port}, localhost.run, etc.)");
Console.Write("Paste your public tunnel URL (e.g. https://xxxx.ngrok-free.app): ");
var tunnelUrl = (Console.ReadLine() ?? "").Trim();

// Self-test the tunnel before registering with Payabli.
await TestTunnel(tunnelUrl);

// Build the Payabli SDK client using the API key from .env.
var client = new PayabliApiClient(apiKey);

// Register the ApprovedPayment notification so Payabli knows where to POST.
await CreateWebhookNotification(client, tunnelUrl, ownerId);

// Wait for the user to confirm before firing a live transaction.
Console.Write("\nPress ENTER to trigger a test transaction and generate a webhook (or Ctrl+C to exit)...");
Console.ReadLine();

await TriggerTransaction(client, entrypoint);

Console.WriteLine("\nWaiting for webhook event. Check your terminal for output when received.");

// Block until the process is shut down (Ctrl+C).
await app.WaitForShutdownAsync();


// ---- Helper methods ----

// TestTunnel POSTs a small test payload to the webhook URL to confirm
// the tunnel is reachable before registering with Payabli.
static async Task TestTunnel(string tunnelUrl)
{
    var webhookUrl = tunnelUrl.TrimEnd('/') + "/webhook";
    Console.WriteLine($"\nTesting tunnel by POSTing to {webhookUrl}...");

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
        Console.WriteLine($"Tunnel test FAILED - tunnel may not be running or URL is wrong: {ex.Message}");
    }
}

// CreateWebhookNotification registers an ApprovedPayment webhook notification
// with Payabli, pointing at the exposed /webhook endpoint.
static async Task CreateWebhookNotification(PayabliApiClient client, string tunnelUrl, int ownerId)
{
    var webhookUrl = tunnelUrl.TrimEnd('/') + "/webhook";
    Console.WriteLine("\nRegistering webhook notification with Payabli...");

    var request = new NotificationStandardRequest
    {
        Content = new NotificationStandardRequestContent
        {
            EventType = NotificationStandardRequestContentEventType.ApprovedPayment
        },
        Frequency = NotificationStandardRequestFrequency.Untilcancelled,
        Method = NotificationStandardRequestMethod.Web,
        OwnerId = ownerId,
        OwnerType = 0,
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

