#!/usr/bin/env bash
# Starts the local token server and builds + launches the example app in the
# iOS Simulator. Tap to Pay itself still needs a physical iPhone XS+ (see
# README.md) — this gets you the Pay In tab running in one command.
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

SCHEME="PayabliPayInDemo"
BUNDLE_ID="com.payabli.examples.payinDemo"
DERIVED_DATA="$SCRIPT_DIR/.build"
APP_PATH="$DERIVED_DATA/Build/Products/Debug-iphonesimulator/$SCHEME.app"

# --- First run: create config files and stop -----------------------------

NEEDS_SETUP=0
if [[ ! -f TokenServer/.env ]]; then
  cp TokenServer/.env.template TokenServer/.env
  echo "Created TokenServer/.env — fill in CLIENT_ID/CLIENT_SECRET (or PAYABLI_ACCESS_TOKEN)."
  NEEDS_SETUP=1
fi
if [[ ! -f PayabliPayInDemo/Secrets.swift ]]; then
  cp PayabliPayInDemo/Secrets.swift.sample PayabliPayInDemo/Secrets.swift
  echo "Created PayabliPayInDemo/Secrets.swift — fill in entryPoint (and appId for Tap to Pay)."
  NEEDS_SETUP=1
fi
if [[ "$NEEDS_SETUP" == "1" ]]; then
  echo "Fill those in, then run ./run.sh again."
  exit 1
fi

# --- Token server -----------------------------------------------------

echo "Starting local token server..."
(cd TokenServer && exec node server.mjs) &
TOKEN_SERVER_PID=$!
trap 'echo "Stopping token server..."; kill "$TOKEN_SERVER_PID" 2>/dev/null || true' EXIT

for _ in $(seq 1 20); do
  curl -sf http://127.0.0.1:8787/health >/dev/null 2>&1 && break
  sleep 0.5
done
if ! curl -sf http://127.0.0.1:8787/health >/dev/null 2>&1; then
  echo "Token server didn't come up — check TokenServer/.env." >&2
  exit 1
fi
echo "Token server is up."

# --- Xcode project --------------------------------------------------------

if command -v xcodegen >/dev/null 2>&1; then
  echo "Regenerating Xcode project..."
  xcodegen generate >/dev/null
else
  echo "xcodegen not found (brew install xcodegen) — using the committed .xcodeproj as-is."
fi

# --- Simulator ------------------------------------------------------------

SIM_UDID=$(xcrun simctl list devices | grep -E "iPhone.*\(Booted\)" | head -1 \
  | grep -oE '[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}' || true)
if [[ -z "$SIM_UDID" ]]; then
  SIM_UDID=$(xcrun simctl list devices available | grep -E "iPhone" | head -1 \
    | grep -oE '[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}')
fi
if [[ -z "$SIM_UDID" ]]; then
  echo "No iPhone simulator found. Create one in Xcode > Settings > Platforms." >&2
  exit 1
fi

echo "Booting simulator $SIM_UDID (if needed)..."
xcrun simctl bootstatus "$SIM_UDID" -b >/dev/null
open -a Simulator --args -CurrentDeviceUDID "$SIM_UDID"

# --- Build, install, launch ------------------------------------------------

echo "Building $SCHEME (the first build can take a few minutes)..."
xcodebuild build \
  -project "$SCHEME.xcodeproj" \
  -scheme "$SCHEME" \
  -destination "platform=iOS Simulator,id=$SIM_UDID" \
  -derivedDataPath "$DERIVED_DATA" \
  CODE_SIGNING_ALLOWED=NO

echo "Installing and launching..."
xcrun simctl install "$SIM_UDID" "$APP_PATH"
xcrun simctl launch "$SIM_UDID" "$BUNDLE_ID"

cat <<EOF

$SCHEME is running in the Simulator.
- Pay In tab: fully testable here.
- Tap to Pay tab: needs a physical iPhone XS+ (see README.md).

Press Ctrl+C to stop the token server.
EOF

wait "$TOKEN_SERVER_PID"
