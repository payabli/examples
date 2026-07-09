import PayabliSDKCore
import PayabliSDKPayInPaymentFlow
import PayabliSDKTapToPay
import SwiftUI

/// Entry point of the demo app.
///
/// Owns one `PayabliTTP` instance for Tap to Pay and one
/// `PayabliPayInPaymentFlow` instance configured to capture a card/ACH
/// payment, and hands both to `HomeView` as environment objects.
@main
struct PayabliPayInDemoApp: App {
    // Just satisfies PayabliTTP's synchronous `accessToken:` parameter at
    // launch — the SDK replaces it via `tokenProvider` on the first 401,
    // so this never needs to be a real token.
    private static let placeholderAccessToken = "placeholder-token"

    @StateObject private var ttp = PayabliTTP(
        accessToken: placeholderAccessToken,
        tokenProvider: { try await Secrets.fetchAccessToken() },
        entryPoint: Secrets.entryPoint,
        appId: Secrets.appId,
        environment: .sandbox
    )

    @StateObject private var payInPaymentFlow = PayabliPayInPaymentFlow(
        entryPoint: Secrets.entryPoint,
        environment: .sandbox,
        accessTokenProvider: { try await Secrets.fetchAccessToken() },
        diagnostics: Self.diagnostics,
        operation: .capture,
        requestConfiguration: PayabliPayInPaymentFlowRequestConfiguration(
            paymentDetails: PayabliPayInPaymentFlowPaymentDetails(
                totalAmount: Config.demoAmount,
                currency: "USD"
            ),
            // Sandbox paypoints configure which field(s) identify a
            // customer under Settings > Custom Fields > Custom
            // Identifiers — this one requires customerNumber. That's
            // paypoint-specific, not part of the hosted form's visible
            // fields, so it's supplied here; it merges with whatever
            // the form collects (form values win where both are set).
            customerData: PayabliPayInPaymentFlowCustomerData(
                customerNumber: "ios-example-customer"
            ),
            orderDescription: "Payabli iOS example payment",
            source: "ios-example-app"
        )
    )

    var body: some Scene {
        WindowGroup {
            HomeView()
                .environmentObject(ttp)
                .environmentObject(payInPaymentFlow)
        }
    }

    /// Prints the raw (redacted) request/response for every PayIn call —
    /// check Xcode's console when a capture fails with a generic message.
    private static var diagnostics: PayabliPayInPaymentFlowDiagnostics {
        guard Config.payInDiagnosticsEnabled else { return .disabled }
        return .enabled { entry in
            var lines = [
                "[PayabliPayInPaymentFlowDiagnostics] \(entry.phase.rawValue.uppercased()) \(entry.method) \(entry.url)"
            ]
            if let statusCode = entry.statusCode { lines.append("statusCode=\(statusCode)") }
            if let durationMilliseconds = entry.durationMilliseconds {
                lines.append("durationMilliseconds=\(durationMilliseconds)")
            }
            lines.append("headers=\(entry.headers)")
            if let body = entry.body { lines.append("body=\(body)") }
            if let errorDescription = entry.errorDescription { lines.append("error=\(errorDescription)") }
            print(lines.joined(separator: "\n"))
        }
    }
}
