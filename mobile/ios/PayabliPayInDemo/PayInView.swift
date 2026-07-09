import PayabliSDKPayInPaymentFlow
import SwiftUI

/// Demonstrates `PayabliPayInPaymentFlow` configured for `.capture`: the
/// hosted SwiftUI form collects card or ACH details and submits a v2
/// MoneyIn payment directly. Clear PAN data never reaches this view —
/// the SDK owns the sensitive fields and the network request.
struct PayInView: View {
    @EnvironmentObject private var paymentFlow: PayabliPayInPaymentFlow

    @State private var resultText = ""

    var body: some View {
        NavigationView {
            ScrollView {
                VStack(alignment: .leading, spacing: 16) {
                    PayabliPayInPaymentFlowView(
                        component: paymentFlow,
                        configuration: configuration,
                        onCompleted: handleCompleted,
                        onError: handleError
                    )
                    .payabliPayInPaymentFlowStyle(style)

                    Text(resultText.isEmpty ? "No payment yet" : resultText)
                        .font(.footnote)
                        .foregroundColor(.secondary)
                        .frame(maxWidth: .infinity, alignment: .leading)
                        .padding(12)
                        .background(Color(.secondarySystemBackground))
                        .clipShape(RoundedRectangle(cornerRadius: 8))
                }
                .padding(16)
            }
            .navigationTitle("Pay In")
        }
    }

    private var configuration: PayabliPayInPaymentFlowFormConfiguration {
        PayabliPayInPaymentFlowFormConfiguration(
            allowedMethods: [.card, .ach],
            defaultMethod: .card,
            // .capture submits a MoneyIn payment, which requires customer
            // info (name/email) in addition to the payment method — without
            // it the API rejects the request with "Error in customer data".
            cardFieldOrder: [
                .cardholderName,
                .cardNumber,
                .cardExpiration,
                .cardCvv,
                .cardZip,
                .firstName,
                .lastName,
                .billingEmail
            ],
            achFieldOrder: [
                .achHolder,
                .achRouting,
                .achAccount,
                .achAccountType,
                .firstName,
                .lastName,
                .billingEmail
            ],
            cardSections: [
                PayabliPayInPaymentFlowFieldSection(
                    title: "Card Information",
                    fields: [.cardholderName, .cardNumber, .cardExpiration, .cardCvv, .cardZip]
                ),
                PayabliPayInPaymentFlowFieldSection(
                    title: "Customer Information",
                    fields: [.firstName, .lastName, .billingEmail]
                )
            ],
            achSections: [
                PayabliPayInPaymentFlowFieldSection(
                    title: "Bank Information",
                    fields: [.achHolder, .achRouting, .achAccount, .achAccountType]
                ),
                PayabliPayInPaymentFlowFieldSection(
                    title: "Customer Information",
                    fields: [.firstName, .lastName, .billingEmail]
                )
            ],
            hiddenValues: PayabliPayInPaymentFlowHiddenValues(
                achHolderType: .personal,
                achSecCode: .web,
                methodDescription: "Payabli iOS example payment"
            ),
            labels: PayabliPayInPaymentFlowLabels(
                title: "Checkout",
                subtitle: "Pay $\(Config.demoAmount) with a card or bank account.",
                submitButton: "Pay $\(Config.demoAmount)"
            ),
            labelLayout: .external,
            formatting: PayabliPayInPaymentFlowFormatting(
                insertsCardNumberSpaces: true,
                masksACHAccountEntry: true
            )
        )
    }

    private var style: PayabliPayInPaymentFlowStyle {
        PayabliPayInPaymentFlowStyle(
            input: PayabliPayInPaymentFlowInputStyle(
                backgroundColor: Color(.systemBackground),
                borderColor: Color(.separator).opacity(0.6),
                cornerRadius: 8
            ),
            submitButton: PayabliPayInPaymentFlowSubmitButtonStyle(cornerRadius: 8)
        )
    }

    private func handleCompleted(_ result: PayabliPayInPaymentFlowResult) {
        resultText = [
            "Status: \(result.code)",
            "Payment trans ID: \(result.transaction?.paymentTransId ?? "-")",
            "Method: \(result.transaction?.method ?? "-")"
        ].joined(separator: "\n")
    }

    private func handleError(_ error: Error) {
        resultText = "Payment failed: \(error.localizedDescription)"
    }
}
