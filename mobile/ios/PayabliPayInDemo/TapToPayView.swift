import PayabliSDKTapToPay
import SwiftUI

/// Demonstrates the Tap to Pay lifecycle: attest the device and prepare
/// the NFC reader with `ttp.initialize()`, then accept a contactless
/// card or wallet payment with `ttp.charge(type:paymentDetails:)`.
///
/// Tap to Pay only works on a physical iPhone XS or newer running iOS
/// 16.7+ with the Tap to Pay entitlement — see `README.md`. On the
/// Simulator, `initialize()` fails at the eligibility gate.
struct TapToPayView: View {
    @EnvironmentObject private var ttp: PayabliTTP

    @State private var lastResult = ""
    @State private var eventLog: [EventLogEntry] = []
    @State private var eventToken: PayabliTTPEventToken?
    @State private var isWorking = false

    var body: some View {
        NavigationView {
            List {
                Section("Lifecycle") {
                    Button("Initialize") { runInitialize() }
                        .disabled(isWorking || ttp.isReady)
                }

                Section("Charge") {
                    Button("Charge $\(Config.demoAmount)") { runCharge() }
                        .disabled(isWorking || !ttp.isReady)
                }

                Section("Last result") {
                    Text(lastResult.isEmpty ? "—" : lastResult)
                        .font(.footnote)
                        .foregroundColor(.secondary)
                }

                Section("Event log") {
                    if eventLog.isEmpty {
                        Text("No events yet")
                            .font(.footnote)
                            .foregroundColor(.secondary)
                    } else {
                        ForEach(eventLog) { entry in
                            Text(entry.label)
                                .font(.footnote.monospaced())
                                .foregroundColor(.secondary)
                        }
                    }
                }
            }
            .navigationTitle("Tap to Pay")
            .toolbar { sessionBadge }
            .onAppear(perform: subscribeToEvents)
            .onDisappear { eventToken?.cancel() }
        }
    }

    private var sessionBadge: some ToolbarContent {
        ToolbarItem(placement: .navigationBarTrailing) {
            Text(stateLabel(ttp.sessionState))
                .font(.caption.bold())
                .padding(.horizontal, 8)
                .padding(.vertical, 4)
                .background(stateColor(ttp.sessionState).opacity(0.2))
                .foregroundColor(stateColor(ttp.sessionState))
                .clipShape(Capsule())
        }
    }

    // MARK: - Actions

    private func runInitialize() {
        isWorking = true
        Task {
            defer { isWorking = false }
            do {
                try await ttp.initialize()
                lastResult = "✓ Initialized"
            } catch {
                lastResult = "✗ Initialize failed: \(error.localizedDescription)"
            }
        }
    }

    private func runCharge() {
        isWorking = true
        Task {
            defer { isWorking = false }
            do {
                let result = try await ttp.charge(
                    type: .sale,
                    paymentDetails: PayabliTTPPaymentDetails(amount: Decimal(Config.demoAmount))
                )
                lastResult = "✓ Charged · txn \(result.paymentTransId)"
            } catch {
                lastResult = "✗ Charge failed: \(error.localizedDescription)"
            }
        }
    }

    // MARK: - Event subscription

    private func subscribeToEvents() {
        guard eventToken == nil else { return }
        // The addEventListener token owns both the subscription and its
        // tear-down (cancelled in onDisappear) — a plain `events()` async
        // sequence has no handle SwiftUI could cancel from onDisappear.
        eventToken = ttp.addEventListener { code, payload in
            let detail = payload
                .map { "\($0.key): \($0.value)" }
                .sorted()
                .joined(separator: ", ")
            DispatchQueue.main.async {
                eventLog.insert(EventLogEntry(label: "\(code)" + (detail.isEmpty ? "" : " — \(detail)")), at: 0)
                if eventLog.count > 50 {
                    eventLog.removeLast(eventLog.count - 50)
                }
            }
        }
    }

    // MARK: - Cosmetics

    private func stateLabel(_ state: PayabliTTPSessionState) -> String {
        switch state {
        case .idle: return "idle"
        case .attestingDevice: return "attesting"
        case .fetchingConfig: return "config"
        case .initializingReader: return "reader"
        case .ready: return "ready"
        case .sessionExpired: return "expired"
        case .reinitializing: return "reinit"
        case .pendingActivation: return "pending"
        case .error: return "error"
        @unknown default: return "?"
        }
    }

    private func stateColor(_ state: PayabliTTPSessionState) -> Color {
        switch state {
        case .ready: return .green
        case .error, .sessionExpired: return .red
        case .pendingActivation: return .orange
        default: return .gray
        }
    }
}

private struct EventLogEntry: Identifiable {
    let id = UUID()
    let label: String
}
