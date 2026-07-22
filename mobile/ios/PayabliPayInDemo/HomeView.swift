import SwiftUI

/// Landing screen: one tab per SDK feature covered by this example.
struct HomeView: View {
    var body: some View {
        TabView {
            PayInView()
                .tabItem {
                    Label("Pay In", systemImage: "creditcard")
                }

            TapToPayView()
                .tabItem {
                    Label("Tap to Pay", systemImage: "wave.3.right")
                }
        }
    }
}
