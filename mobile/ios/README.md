# iOS Example App

Demonstrates [Payabli's iOS SDK](https://github.com/payabli/sdk-ios): card/ACH payments with `PayabliPayInPaymentFlow`, and Tap to Pay on iPhone with `PayabliTTP`.

## What's here

- **Pay In tab** — hosted checkout form; submits a real payment.
- **Tap to Pay tab** — initialize the reader, then charge a contactless card or wallet.

## Requirements

- Xcode 15+
- A Payabli sandbox entrypoint
- A physical iPhone XS+ on iOS 16.7+ for Tap to Pay (Pay In works in the Simulator)

## Setup

```bash
./run.sh
```

Run it. First time, it creates `TokenServer/.env` and `Secrets.swift` and stops so you can fill them in. Run it again and it starts the token server, builds the app, and launches it in the Simulator.

For Tap to Pay, also set `appId` in `Secrets.swift` and authorize the app on your [entrypoint's device allowlist](https://github.com/payabli/sdk-ios#authorized-application-on-the-paypoint-allowlist), then run on a physical iPhone.

Never put a `clientSecret` in the app itself — a compiled app can be decompiled. That's why it lives in `TokenServer/.env` instead, and the app fetches a token from that local server.

### By hand, instead of `run.sh`

1. `cd TokenServer && cp .env.template .env` — fill in `CLIENT_ID`/`CLIENT_SECRET` (or `PAYABLI_ACCESS_TOKEN`), then `npm start`.
2. `cd ../PayabliPayInDemo && cp Secrets.swift.sample Secrets.swift` — fill in `entryPoint`.
3. Open `PayabliPayInDemo.xcodeproj` in Xcode, run the `PayabliPayInDemo` scheme.

### Regenerating the Xcode project

```bash
xcodegen generate
```

Run this after changing `project.yml` or adding/removing source files.

### Known issue

Linking both `PayabliSDKTapToPay` and `PayabliSDKPayInPaymentFlow` hits a Swift Package Manager limitation — they share a `PayabliSDKCore` dependency that Xcode can't reconcile between two products in one target. Already worked around in `project.yml` (`DISABLE_DIAMOND_PROBLEM_DIAGNOSTIC: YES`), so there's nothing to do — but worth flagging to the SDK team, since any app wanting both capabilities hits this.

## Layout

```
mobile/ios/
├── run.sh
├── PayabliPayInDemo.xcodeproj   # generated — see project.yml
├── project.yml
├── PayabliPayInDemo/
│   ├── PayabliPayInDemoApp.swift
│   ├── HomeView.swift
│   ├── PayInView.swift
│   ├── TapToPayView.swift
│   ├── Config.swift
│   └── Secrets.swift.sample
└── TokenServer/
```

More: [SDK docs](https://github.com/payabli/sdk-ios), [PayIn overview](https://github.com/payabli/sdk-ios/blob/main/Documentation/PayInPaymentFlowOverview.md).
