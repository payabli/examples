# frozen_string_literal: true
require_relative '../config'
require 'payabli'

client = Payabli::Client.new

begin
  # Replace with a valid customer_id and payment_method_id for your environment
  customer_id = ENV['PAYABLI_TEST_CUSTOMER_ID'] || 'cus_test_id'
  payment_method_id = ENV['PAYABLI_TEST_PAYMENT_METHOD_ID'] || 'pm_test_id'

  transaction = client.transaction.create({
    amount: 1000, # $10.00
    currency: 'USD',
    customer_id: customer_id,
    payment_method_id: payment_method_id,
    description: 'Test transaction'
  })
  puts "Created transaction: #{transaction.id}"
rescue => e
  puts "Error creating transaction: #{e.message}"
end
