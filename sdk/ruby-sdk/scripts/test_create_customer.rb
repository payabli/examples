# frozen_string_literal: true
require_relative '../config'
require 'payabli'

# Set your API key in config.rb or via ENV['PAYABLI_API_KEY']
client = Payabli::Client.new

begin
  customer = client.customer.create({
    first_name: 'Test',
    last_name: 'User',
    email: 'testuser@example.com',
    phone: '555-123-4567'
  })
  puts "Created customer: #{customer.id}"
rescue => e
  puts "Error creating customer: #{e.message}"
end
