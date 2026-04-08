# frozen_string_literal: true

require 'dotenv'
require 'net/http'
require 'json'
require 'payabli'
require 'sinatra/base'
require 'thread'

# Load .env from the same directory as this script, regardless of cwd.
Dotenv.load(File.join(__dir__, '.env'))

# ─── Configuration ────────────────────────────────────────────────────────────

PORT       = Integer(ENV.fetch('PORT', '3000'))
API_KEY    = ENV['PAYABLI_KEY']   or abort('PAYABLI_KEY missing in .env')
ENTRYPOINT = ENV['PAYABLI_ENTRY'] or abort('PAYABLI_ENTRY missing in .env')
OWNER_ID   = Integer(ENV.fetch('OWNER_ID', '0').tap { |v| abort('OWNER_ID missing in .env') if v == '0' })

# Thread-safe queue: the Sinatra handler pushes received payloads here,
# the main thread pops and prints them.
WEBHOOK_QUEUE = Queue.new

# ─── Sinatra webhook server ────────────────────────────────────────────────────

class WebhookApp < Sinatra::Base
  set :logging, false
  set :host_authorization, { permitted_hosts: [] }

  before do
    puts "→ #{request.request_method} #{request.path_info} " \
         "(Content-Type: #{request.content_type}, " \
         "User-Agent: #{request.user_agent})"
    $stdout.flush
  end

  get '/' do
    'Payabli Webhook Test'
  end

  post '/webhook' do
    body = request.body.read
    WEBHOOK_QUEUE.push(body)
    status 200
    ''
  end
end

# ─── Helper functions ─────────────────────────────────────────────────────────

# POST a small test payload to the webhook URL to confirm the tunnel is live.
def test_tunnel(tunnel_url)
  webhook_url = tunnel_url.chomp('/') + '/webhook'
  puts "\nTesting tunnel by POSTing to #{webhook_url}..."

  uri = URI(webhook_url)
  http = Net::HTTP.new(uri.host, uri.port)
  http.use_ssl = uri.scheme == 'https'
  http.open_timeout = 10
  http.read_timeout = 10

  req = Net::HTTP::Post.new(uri.path.empty? ? '/' : uri.path)
  req['Content-Type'] = 'application/json'
  req.body = '{"test":"ping"}'

  resp = http.request(req)
  puts "Tunnel test response: HTTP #{resp.code}"
rescue => e
  puts "Tunnel test FAILED - tunnel may not be running or URL is wrong: #{e.message}"
end

# Register an ApprovedPayment webhook notification with Payabli.
def create_webhook_notification(api_key, tunnel_url, owner_id)
  webhook_url = tunnel_url.chomp('/') + '/webhook'
  puts "\nRegistering webhook notification with Payabli..."
  puts "Notification request: Target=#{webhook_url}, OwnerId=#{owner_id}"

  body = Payabli::Types::NotificationStandardRequest.new(
    content: Payabli::Types::NotificationStandardRequestContent.new(
      event_type: 'ApprovedPayment'
    ),
    frequency: Payabli::Types::NotificationStandardRequestFrequency::UNTILCANCELLED,
    method_: Payabli::Types::NotificationStandardRequestMethod::WEB,
    owner_id: owner_id,
    owner_type: 0,
    status: 1,
    target: webhook_url
  ).to_h

  uri = URI('https://api-sandbox.payabli.com/api/Notification')
  http = Net::HTTP.new(uri.host, uri.port)
  http.use_ssl = true

  req = Net::HTTP::Post.new(uri.path)
  req['Content-Type'] = 'application/json'
  req['requestToken'] = api_key
  req.body = body.to_json

  resp = http.request(req)
  result = JSON.parse(resp.body)
  if result['isSuccess']
    puts "Webhook registered: IsSuccess=#{result['isSuccess']}, " \
         "ResponseCode=#{result['responseCode']}, " \
         "NotificationId=#{result['responseData']}"
  else
    puts "Failed to register webhook: #{resp.body}"
  end
rescue => e
  puts "Failed to register webhook: #{e.message}"
end

# Send a test $1.00 credit card transaction to generate an ApprovedPayment event.
def trigger_transaction(client, entrypoint)
  puts "\nTriggering a test transaction to generate webhook..."
  puts "Transaction request: EntryPoint=#{entrypoint}, Amount=1.00"

  response = client.money_in.getpaid(
    customer_data: { customer_id: 4440 },
    entry_point: entrypoint,
    ipaddress: '255.255.255.255',
    payment_details: { total_amount: 1.00, service_fee: 0 },
    payment_method: {
      cardcvv:     '999',
      cardexp:     '02/27',
      card_holder: 'Test User',
      cardnumber:  '4111111111111111',
      cardzip:     '12345',
      initiator:   'payor',
      method_:     'card'
    }
  )
  puts "Transaction sent: IsSuccess=#{response.is_success}, " \
       "ResponseText=#{response.response_text}"
rescue => e
  puts "Failed to trigger transaction: #{e.message}"
end

# ─── Main ─────────────────────────────────────────────────────────────────────

# Start the Sinatra server in a background thread.
server_thread = Thread.new do
  WebhookApp.run!(port: PORT, bind: '0.0.0.0')
end

# Give the server a moment to bind before prompting.
sleep 0.5
puts "\nWebhook server listening on http://localhost:#{PORT}/webhook"

puts "\nExpose your local server publicly (e.g. ngrok http #{PORT}, localhost.run, etc.)"
print 'Paste your public tunnel URL (e.g. https://xxxx.ngrok-free.app): '
tunnel_url = $stdin.gets.to_s.strip

test_tunnel(tunnel_url)

# Build the Payabli SDK client.
client = Payabli::Client.new(
  api_key: API_KEY,
  base_url: Payabli::Environment::SANDBOX
)

# Register the ApprovedPayment notification so Payabli knows where to POST.
create_webhook_notification(API_KEY, tunnel_url, OWNER_ID)

# Wait for the user to confirm before firing a live transaction.
print "\nPress ENTER to trigger a test transaction and generate a webhook (or Ctrl+C to exit)..."
$stdin.gets

# Drain the queue of anything that arrived before the transaction
# (e.g. the tunnel test ping) so only real webhook payloads are printed.
WEBHOOK_QUEUE.clear

trigger_transaction(client, ENTRYPOINT)

puts "\nWaiting for webhook event. Check your terminal for output when received."

# Block, printing any webhook payloads as they arrive.
loop do
  payload = WEBHOOK_QUEUE.pop
  puts "\nReceived webhook payload:\n#{payload.empty? ? '(empty body)' : payload}\n"
  $stdout.flush
end
