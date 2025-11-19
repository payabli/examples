# frozen_string_literal: true

require 'sinatra'
require 'json'
require 'erb'
require 'rack/mime'
require 'time'
require 'logger'
require_relative 'config'
require 'payabli'

set :public_folder, File.dirname(__FILE__) + '/assets'

ExampleConfig.require_config!

# Initialize client
client = Payabli::Client.new(
  api_key: ExampleConfig.api_key,
  base_url: Payabli::Environment::SANDBOX
)

LOGGER = Logger.new($stdout)
LOGGER.level = Logger::INFO

LOGGER.info("Payabli client initialized. Entrypoint=#{ExampleConfig.entrypoint}")

['INT', 'TERM'].each do |sig|
  trap(sig) do
    LOGGER.info("Received #{sig}, shutting down Sinatra app")
    exit
  end
end

helpers do
  def render_template(name)
    ERB.new(File.read(File.join(File.dirname(__FILE__), 'views', name))).result(binding)
  end
end

get '/' do
  redirect '/create'
end

get '/create' do
  content_type 'text/html'
  erb :create, layout: :'base'
end

post '/api/create' do
  # Returns an HTML snippet (same as Python example) for htmx
  begin
    # map fields like Python example
    payload = {
      entry: ExampleConfig.entrypoint,
      firstname: params['firstname'],
      lastname: params['lastname'],
      email: params['email'],
      time_zone: params['timeZone'] ? params['timeZone'].to_i : nil,
      address: params['address'],
      city: params['city'],
      state: params['state'],
      zip: params['zip'],
      country: params['country'],
      identifier_fields: ['email']
    }

  LOGGER.info("POST /api/create payload: #{payload.to_json}")
  result = client.customer.add_customer(**payload)
  LOGGER.info("customer.add_customer response: #{result.inspect}")
    '<input type="text" name="valid" value="Success!" aria-invalid="false" readonly>'
  rescue Payabli::Errors::ApiError => e
  LOGGER.error("customer.add_customer ApiError: #{e.class} #{e.message}")
  LOGGER.debug("body: #{e.body}") if e.respond_to?(:body)
    '<input type="text" name="invalid" value="Error!" aria-invalid="true" readonly>'
  rescue => e
  LOGGER.error("customer.add_customer unexpected error: #{e.class} #{e.message}")
  LOGGER.debug(e.backtrace.join("\n"))
    '<input type="text" name="invalid" value="Error!" aria-invalid="true" readonly>'
  end
end

get '/api/list' do
  content_type 'text/html'
  begin
    result = client.query.list_customers(entry: ExampleConfig.entrypoint)

    # Prefer SDK typed records. If none, use empty list. Assume SDK now returns correctly-cased fields.
    records = result.respond_to?(:records) && result.records ? result.records : []

    LOGGER.info("GET /api/list returned #{records.length} records")

    rows = ''

    if records.any?
      records.each_with_index do |record, i|
        firstname = record.firstname
        lastname = record.lastname
        email = record.email
        address = record.address
        city = record.city
        state = record.state
        zip_code = record.zip
        time_zone = record.time_zone
        customer_id = record.customer_id
        # We rely on SDK typed accessors; the page rendering is unchanged.

        rows += <<~ROW
        <tr>
          <td>#{firstname}</td>
          <td>#{lastname}</td>
          <td>#{email}</td>
          <td>#{address}</td>
          <td>#{city}</td>
          <td>#{state}</td>
          <td>#{zip_code}</td>
          <td>#{time_zone}</td>
          <td>
            <form method="post" action="/api/delete/#{customer_id}" hx-swap="outerHTML">
              <button type="submit">❌</button>
            </form>
          </td>
        </tr>
        ROW
      end
    else
      rows = <<~ROW
      <tr>
        <td colspan="9" style="text-align:center;color:#666;">No customers found. Create a customer to get started!</td>
      </tr>
      ROW
    end

    table = <<~TABLE
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
        #{rows}
      </tbody>
    </table>
    TABLE

    table
  rescue Payabli::Errors::ResponseError => e
    LOGGER.error("API ResponseError in /api/list: #{e.class} #{e.message}")
    LOGGER.debug("Response body: #{e.body}") if e.respond_to?(:body)
    LOGGER.debug(e.backtrace.join("\n"))
    status 500
    '<p>Error loading customers (API error). Please check your API credentials and try again.</p>'
  rescue => e
    LOGGER.error("Error in /api/list: #{e.class} #{e.message}")
    LOGGER.debug(e.backtrace.join("\n"))
    status 500
    '<p>Error loading customers. Please check your API credentials and try again.</p>'
  end
end

# Render the list page which uses HTMX to fetch /api/list
get '/list' do
  content_type 'text/html'
  erb :list, layout: :'base'
end

post '/api/delete/:customer_id' do
  content_type 'text/html'
  begin
  LOGGER.info("POST /api/delete/#{params[:customer_id]} - deleting customer")
  client.customer.delete_customer(customerId: params[:customer_id])
  LOGGER.info("customer.delete_customer called for #{params[:customer_id]}")
    '<input type="text" name="valid" value="Deleted" aria-invalid="false" readonly>'
  rescue => e
  LOGGER.error("customer.delete_customer error: #{e.class} #{e.message}")
  LOGGER.debug(e.backtrace.join("\n"))
    '<input type="text" name="invalid" value="Error" aria-invalid="true" readonly>'
  end
end

get '/transaction' do
  content_type 'text/html'
  erb :transaction, layout: :'base'
end

# Endpoint that the embedded component posts to in the Python example: /api/transaction/<referenceId>
post '/api/transaction/:reference_id' do
  content_type 'text/html'
  reference_id = params[:reference_id]
  begin
  LOGGER.info("POST /api/transaction - received reference_id: #{reference_id}")

  token_result = client.token_storage.add_method(
    create_anonymous: true,
    temporary: false,
    customer_data: {
      customer_id: 4440 
    },
    entry_point: ExampleConfig.entrypoint,
    payment_method: {
      method_: 'card',
      token_id: reference_id
    }
  )

  LOGGER.info("token_storage.add_method response: #{token_result.inspect}")

    stored_method_id = token_result.response_data.reference_id
  LOGGER.info("Stored method id: #{stored_method_id}")

    payment_payload = {
      customer_data: {
        customer_id: 4440
      },
      entry_point: ExampleConfig.entrypoint,
      payment_details: {
        service_fee: 0,
        total_amount: 10000
      },
      payment_method: {
        initiator: 'payor',
        method_: 'card',
        stored_method_id: stored_method_id,
        stored_method_usage_type: 'unscheduled'
      }
    }
  LOGGER.info("Calling money_in.getpaid with: #{payment_payload.to_json}")
  payment_result = client.money_in.getpaid(**payment_payload)
  LOGGER.info("money_in.getpaid response: #{payment_result.inspect}")

    '<input type="text" name="valid" value="Payment processed successfully!" aria-invalid="false" readonly>'
  rescue Payabli::Errors::ApiError => e
  LOGGER.error("ApiError: #{e.class} #{e.message}")
  LOGGER.debug("body: #{e.body}") if e.respond_to?(:body)
    "<input type=\"text\" name=\"invalid\" value=\"Payment failed: #{e.message}\" aria-invalid=\"true\" readonly>"
  rescue => e
  LOGGER.error("transaction processing error: #{e.class} #{e.message}")
  LOGGER.debug(e.backtrace.join("\n"))
    "<input type=\"text\" name=\"invalid\" value=\"Error processing payment\" aria-invalid=\"true\" readonly>"
  end
end

# Serve the pico css from assets
get '/static/pico-important.css' do
  content_type 'text/css'
  File.read(File.join(settings.public_folder, 'pico-important.css'))
end

__END__