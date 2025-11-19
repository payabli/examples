# frozen_string_literal: true
require_relative '../config'
require 'payabli'

client = Payabli::Client.new

begin
  # Example card data (use test values)
  card_data = Payabli::TokenStorage::Types::TokenizeCard.new(
    card_number: '4111111111111111',
    exp_month: '12',
    exp_year: '2027',
    cvv: '123',
    billing_zip: '12345'
  )

  request = Payabli::TokenStorage::Types::RequestTokenStorage.new(
    payment_method: card_data
  )

  token = client.token_storage.request_token_storage(request)
  puts "Tokenized card: #{token.id}"
rescue => e
  puts "Error tokenizing card: #{e.message}"
end
