import React from 'react';
import { Button, Image, Linking, StyleSheet, Text, View } from 'react-native';

type HomePageProps = {
  onCheckout: () => void;
};

const payabliLogo = require('../assets/payabli-logo.png');
const reactLogo = require('../assets/react-logo.png');

const HomePage = ({ onCheckout }: HomePageProps) => {
  const handleOpenPayabliDocs = () => {
    void Linking.openURL('https://docs.payabli.com/home');
  };

  const handleOpenReactNativeDocs = () => {
    void Linking.openURL('https://reactnative.dev/');
  };

  const handleOpenExpoDocs = () => {
    void Linking.openURL('https://expo.dev/');
  };

  return (
    <View style={styles.container}>
      <View style={styles.logoRow}>
        <Image source={payabliLogo} style={styles.logoImage} resizeMode="contain" />
        <Text style={styles.logoPlus}>+</Text>
        <Image source={reactLogo} style={styles.logoImage} resizeMode="contain" />
      </View>
      <Text style={styles.eyebrow}>Payabli Example App</Text>
      <Text style={styles.title}>Home</Text>
      <Text style={styles.subtitle}>
        This is a Payabli demo built with <Text onPress={handleOpenReactNativeDocs} style={styles.inlineLink}>React Native</Text> and <Text onPress={handleOpenExpoDocs} style={styles.inlineLink}>Expo</Text>. Click the button below to go to the checkout page which is powered by Payabli's Embedded Method UI.
      </Text>
      <View style={styles.heroCard}>
        <Text style={styles.heroTitle}>Checkout Demo</Text>
        <Text style={styles.heroDescription}>
          Made by <Text onPress={handleOpenPayabliDocs} style={styles.heroLink}>Payabli</Text> with ❤️.
        </Text>
      </View>
      <Button title="Checkout" onPress={onCheckout} />
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    width: '100%',
    minHeight: 520,
    flex: 1,
    justifyContent: 'center',
  },
  logoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: 14,
    marginBottom: 18,
  },
  logoImage: {
    width: 56,
    height: 56,
  },
  logoPlus: {
    color: '#111827',
    fontSize: 24,
    fontWeight: '700',
  },
  eyebrow: {
    color: '#2563eb',
    fontSize: 12,
    fontWeight: '700',
    textTransform: 'uppercase',
    letterSpacing: 0.8,
    marginBottom: 8,
  },
  title: {
    fontSize: 32,
    lineHeight: 38,
    fontWeight: '700',
    color: '#111827',
    marginBottom: 10,
  },
  subtitle: {
    color: '#4b5563',
    fontSize: 15,
    lineHeight: 22,
    marginBottom: 20,
  },
  heroCard: {
    backgroundColor: '#111827',
    borderRadius: 24,
    padding: 24,
    marginBottom: 20,
  },
  heroTitle: {
    color: '#f9fafb',
    fontSize: 24,
    fontWeight: '700',
    marginBottom: 8,
  },
  heroDescription: {
    color: '#d1d5db',
    fontSize: 15,
    lineHeight: 22,
  },
  heroLink: {
    color: '#93c5fd',
    textDecorationLine: 'underline',
  },
  inlineLink: {
    color: '#2563eb',
    textDecorationLine: 'underline',
  },
});

export default HomePage;