# frozen_string_literal: true

require 'dotenv/load'

module ExampleConfig
  def self.api_key
    ENV['PAYABLI_KEY']
  end

  def self.entrypoint
    ENV['PAYABLI_ENTRY']
  end

  def self.require_config!
    unless api_key && api_key != 'your_api_key_here'
      puts "Missing PAYABLI_KEY in .env - please set it from .env.template"
      exit 1
    end
    unless entrypoint && entrypoint != 'your_entrypoint_id_here'
      puts "Missing PAYABLI_ENTRY in .env - please set it from .env.template"
      exit 1
    end
  end
end