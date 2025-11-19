# frozen_string_literal: true
require_relative '../config'
require 'payabli'

client = Payabli::Client.new

begin
  # Example ACH data (use test values)
  ach_data = Payabli::TokenStorage::Types::TokenizeAch.new(
    account_number: '123456789',
    routing_number: '021000021',
    account_type: 'checking',
    billing_zip: '12345'
  )

  request = Payabli::TokenStorage::Types::RequestTokenStorage.new(
    payment_method: ach_data
  )

  token = client.token_storage.request_token_storage(request)
  puts "Tokenized ACH: #{token.id}"
rescue => e
  puts "Error tokenizing ACH: #{e.message}"
end
