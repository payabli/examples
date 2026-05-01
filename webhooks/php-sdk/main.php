<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Payabli\PayabliClient;
use Payabli\Types\NotificationStandardRequest;
use Payabli\Types\NotificationStandardRequestContent;
use Payabli\MoneyIn\Requests\RequestPaymentV2;
use Payabli\MoneyIn\Types\TransRequestBody;
use Payabli\Types\PaymentDetail;
use Payabli\Types\PayMethodCredit;
use Payabli\Types\PayorDataRequest;
use Dotenv\Dotenv;

// ─── Shared log file (IPC between cli-server request handlers and the CLI process)
// The path is derived from the server port so both modes agree without env-var passing.
function webhookLogFile(string $port): string
{
    return sys_get_temp_dir() . '/payabli_webhook_' . $port . '.log';
}

function webhookLog(string $msg, string $port): void
{
    file_put_contents(webhookLogFile($port), $msg, FILE_APPEND | LOCK_EX);
}

// ─── Web handler (invoked by `php -S`) ────────────────────────────────────────
// When the built-in server routes a request to this file, PHP_SAPI is 'cli-server'.
if (PHP_SAPI !== 'cli') {
    $method  = $_SERVER['REQUEST_METHOD'];
    $path    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $ct      = $_SERVER['HTTP_CONTENT_TYPE'] ?? $_SERVER['CONTENT_TYPE'] ?? '';
    $ua      = $_SERVER['HTTP_USER_AGENT'] ?? '';

    $port = (string)($_SERVER['SERVER_PORT'] ?? '3000');

    // Log every request so it is easy to see if Payabli delivers to a path
    // other than /webhook.
    webhookLog(sprintf("→ %s %s (Content-Type: %s, User-Agent: %s)\n", $method, $path, $ct, $ua), $port);

    if ($path === '/' && $method === 'GET') {
        echo 'Payabli Webhook Test';
        exit;
    }

    if ($path === '/webhook' && $method === 'POST') {
        // Read and print the raw request body, then return 200 OK so Payabli
        // knows the delivery succeeded.
        $body = file_get_contents('php://input');
        webhookLog(sprintf("\nReceived webhook payload:\n%s\n\n", $body !== '' ? $body : '(empty body)'), $port);
        http_response_code(200);
        exit;
    }

    http_response_code(404);
    echo 'Not found';
    exit;
}

// ─── CLI helpers ──────────────────────────────────────────────────────────────

/** Print a prompt and read a line from stdin. */
function prompt(string $message): string
{
    fwrite(STDOUT, $message);
    return trim((string)(fgets(STDIN) ?: ''));
}

// ─── Payabli API helpers ──────────────────────────────────────────────────────

/** POST a small test payload to the webhook URL to confirm the tunnel is live. */
function testTunnel(string $tunnelUrl): void
{
    $webhookUrl = rtrim($tunnelUrl, '/') . '/webhook';
    echo "\nTesting tunnel by POSTing to {$webhookUrl}...\n";

    $ch = curl_init($webhookUrl);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => '{"test":"ping"}',
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
    ]);

    $result = curl_exec($ch);
    if ($result === false) {
        $err = curl_error($ch);
        curl_close($ch);
        echo "Tunnel test FAILED - tunnel may not be running or URL is wrong: {$err}\n";
        return;
    }
    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "Tunnel test response: HTTP {$httpCode}\n";
}

/**
 * Register an ApprovedPayment webhook notification with Payabli, pointing at
 * the exposed /webhook endpoint.
 */
function createWebhookNotification(PayabliClient $client, string $tunnelUrl, int $ownerId): void
{
    $webhookUrl = rtrim($tunnelUrl, '/') . '/webhook';
    echo "\nRegistering webhook notification with Payabli...\n";
    echo "Notification request: Target={$webhookUrl}, OwnerId={$ownerId}\n";

    try {
        $response = $client->notification->addNotification(
            new NotificationStandardRequest([
                'content'   => new NotificationStandardRequestContent([
                    'eventType' => 'ApprovedPayment',
                ]),
                'frequency' => 'untilcancelled',
                'method'    => 'web',
                'ownerId'   => (string)$ownerId,
                'ownerType' => 0,
                'status'    => 1,
                'target'    => $webhookUrl,
            ])
        );
        $notifId = $response->responseData ?? 'null';
        echo "Webhook registered: IsSuccess={$response->isSuccess}, "
            . "ResponseCode={$response->responseCode}, "
            . "NotificationId={$notifId}\n";
    } catch (Exception $e) {
        echo "Failed to register webhook: {$e->getMessage()}\n";
    }
}

/**
 * Send a test $1.00 credit card transaction against the configured entrypoint
 * through the GetPaid v2 endpoint to generate an ApprovedPayment event and
 * trigger the webhook.
 */
function triggerTransaction(PayabliClient $client, string $entrypoint): void
{
    echo "\nTriggering a test transaction to generate webhook...\n";
    echo "Transaction request: EntryPoint={$entrypoint}, Amount=1.00\n";

    try {
        $response = $client->moneyIn->getpaidv2(
            new RequestPaymentV2([
                'body' => new TransRequestBody([
                    'customerData' => new PayorDataRequest([
                        'customerId' => 4440,
                    ]),
                    'entryPoint'     => $entrypoint,
                    'ipaddress'      => '255.255.255.255',
                    'paymentDetails' => new PaymentDetail([
                        'totalAmount' => 1.00,
                        'serviceFee'  => 0.0,
                    ]),
                    'paymentMethod' => new PayMethodCredit([
                        'cardcvv'    => '999',
                        'cardexp'    => '02/27',
                        'cardHolder' => 'Test User',
                        'cardnumber' => '4111111111111111',
                        'cardzip'    => '12345',
                        'initiator'  => 'payor',
                        'method'     => 'card',
                    ]),
                ]),
            ])
        );
        echo "Transaction sent (v2 response):\n";
        print_r($response);
    } catch (Exception $e) {
        echo "Failed to trigger transaction: {$e->getMessage()}\n";
    }
}

// ─── Main ─────────────────────────────────────────────────────────────────────

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$port       = $_ENV['PORT'] ?? '3000';
$apiKey     = $_ENV['PAYABLI_KEY'] ?? '';
$entrypoint = $_ENV['PAYABLI_ENTRY'] ?? '';
$ownerIdStr = $_ENV['OWNER_ID'] ?? '';

if ($apiKey === '')     { die("PAYABLI_KEY missing in .env\n"); }
if ($entrypoint === '') { die("PAYABLI_ENTRY missing in .env\n"); }
if ($ownerIdStr === '') { die("OWNER_ID missing in .env\n"); }

$ownerId = (int)$ownerIdStr;

// Clear any leftover log file from a previous run.
$logFile = webhookLogFile($port);
@unlink($logFile);

// Start the built-in PHP server in a background subprocess, pointing to this
// same file. In non-CLI mode the file handles HTTP requests (see top of file).
$serverProcess = proc_open(
    PHP_BINARY . ' -S 0.0.0.0:' . $port . ' ' . __FILE__,
    [
        0 => ['file', '/dev/null', 'r'],
        1 => STDOUT,
        2 => STDERR,
    ],
    $pipes
);

if (!is_resource($serverProcess)) {
    die("Failed to start PHP built-in server\n");
}

// Terminate the server subprocess and clean up the log file on exit (including Ctrl+C).
register_shutdown_function(function () use ($serverProcess, $logFile): void {
    proc_terminate($serverProcess);
    proc_close($serverProcess);
    @unlink($logFile);
});

// Give the server a moment to bind before prompting.
usleep(500_000);

echo "\nWebhook server listening on http://localhost:{$port}/webhook\n";

echo "\nExpose your local server publicly (e.g. ngrok http {$port}, localhost.run, etc.)\n";
$tunnelUrl = prompt("Paste your public tunnel URL (e.g. https://xxxx.ngrok-free.app): ");

// Self-test the tunnel to confirm end-to-end connectivity before registering.
testTunnel($tunnelUrl);

// Build the Payabli SDK client using the API key from .env.
$client = new PayabliClient($apiKey);

// Register the ApprovedPayment notification so Payabli knows where to POST.
createWebhookNotification($client, $tunnelUrl, $ownerId);

// Wait for the user to confirm before firing a real test transaction.
prompt("\nPress ENTER to trigger a test transaction and generate a webhook (or Ctrl+C to exit)...");
triggerTransaction($client, $entrypoint);

echo "\nWaiting for webhook event. Check your terminal for output when received.\n";

// Skip anything already in the log (tunnel test ping, etc.) — only print
// entries that arrive after the transaction is triggered.
$logOffset = file_exists($logFile) ? (int)filesize($logFile) : 0;
while (true) {
    usleep(100_000); // poll every 100 ms
    if (file_exists($logFile)) {
        $content = file_get_contents($logFile);
        if ($content !== false && strlen($content) > $logOffset) {
            echo substr($content, $logOffset);
            $logOffset = strlen($content);
        }
    }
}
