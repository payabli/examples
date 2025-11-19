# frozen_string_literal: true
require_relative '../config'
require 'payabli'

client = Payabli::Client.new

begin
  customers = client.customer.list({ limit: 5 })
  puts "Listing customers:"
  customers.each do |c|
    puts "- #{c.id}: #{c.first_name} #{c.last_name}"
  end
rescue => e
  puts "Error listing customers: #{e.message}"
end
