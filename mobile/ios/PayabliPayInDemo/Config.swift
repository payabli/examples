import Foundation

/// Non-secret example configuration shared by both demo tabs. Values that
/// vary per developer live in `Secrets.swift` instead (copy it from
/// `Secrets.swift.sample`; it's gitignored).
enum Config {
    /// Demo payment amount charged by both the Pay In and Tap to Pay tabs.
    static let demoAmount: Double = 9.99

    /// Prints redacted PayIn request/response diagnostics to the Xcode
    /// console — invaluable when a capture fails without much detail in
    /// the on-screen error. Turn off to quiet the console.
    static let payInDiagnosticsEnabled = true
}
