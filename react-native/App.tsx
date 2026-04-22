import React, { useState } from 'react';
import { SafeAreaView, ScrollView, StyleSheet } from 'react-native';
import HomePage from './components/HomePage';
import PayabliCheckout from './components/PayabliCheckout';

type AppScreen = 'main' | 'checkout';

export default function App() {
  const [screen, setScreen] = useState<AppScreen>('main');

  return (
    <SafeAreaView style={styles.safeArea}>
      <ScrollView contentContainerStyle={styles.container}>
        {screen === 'main' ? (
          <HomePage onCheckout={() => setScreen('checkout')} />
        ) : (
          <PayabliCheckout onBackToHome={() => setScreen('main')} />
        )}
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  container: {
    flexGrow: 1,
    alignItems: 'center',
    justifyContent: 'center',
    padding: 16,
  },
});
