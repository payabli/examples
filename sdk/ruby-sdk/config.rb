# frozen_string_literal: true

require 'dotenv/load'

module ExampleConfig
  def self.client_id
    ENV['PAYABLI_CLIENT_ID']
  end

  def self.client_secret
    ENV['PAYABLI_CLIENT_SECRET']
  end

  def self.entrypoint
    ENV['PAYABLI_ENTRY']
  end

  def self.require_config!
    unless client_id && client_id != 'your_client_id_here'
      puts "Missing PAYABLI_CLIENT_ID in .env - please set it from .env.template"
      exit 1
    end
    unless client_secret && client_secret != 'your_client_secret_here'
      puts "Missing PAYABLI_CLIENT_SECRET in .env - please set it from .env.template"
      exit 1
    end
    unless entrypoint && entrypoint != 'your_entrypoint_id_here'
      puts "Missing PAYABLI_ENTRY in .env - please set it from .env.template"
      exit 1
    end
  end
end