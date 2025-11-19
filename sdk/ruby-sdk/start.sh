#!/usr/bin/env bash
set -e

cp .env.template .env 2>/dev/null || true
bundle install
ruby app.rb