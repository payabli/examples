import { type PayabliLogEntry } from './model';

export const PAYABLI_BRIDGE_CHANNEL = 'payabli-webview' as const;
export const PAYABLI_BRIDGE_PROTOCOL_VERSION = 1 as const;
export const PAYABLI_NATIVE_SOURCE = 'react-native-host' as const;
export const PAYABLI_EMBEDDED_SOURCE = 'payabli-embedded' as const;

type BridgeEnvelope<TType extends string, TPayload> = {
  channel: typeof PAYABLI_BRIDGE_CHANNEL;
  version: typeof PAYABLI_BRIDGE_PROTOCOL_VERSION;
  source: string;
  type: TType;
  payload: TPayload;
};

export type PaymentBridgeCommand = BridgeEnvelope<'submitPayment', null> & {
  source: typeof PAYABLI_NATIVE_SOURCE;
};

export type PaymentHeightMessage = BridgeEnvelope<'contentHeight', { height: number }> & {
  source: typeof PAYABLI_EMBEDDED_SOURCE;
};

export type PaymentReadyMessage = BridgeEnvelope<
  'ready',
  {
    raw: unknown;
    isReady: boolean;
  }
> & {
  source: typeof PAYABLI_EMBEDDED_SOURCE;
};

export type PaymentLifecycleMessage =
  | (BridgeEnvelope<'loaded', { status: string }> & {
      source: typeof PAYABLI_EMBEDDED_SOURCE;
    })
  | (BridgeEnvelope<'submit', { status: string }> & {
      source: typeof PAYABLI_EMBEDDED_SOURCE;
    });

export type PaymentOutcomeMessage =
  | (BridgeEnvelope<'success', unknown> & {
      source: typeof PAYABLI_EMBEDDED_SOURCE;
    })
  | (BridgeEnvelope<'error', unknown> & {
      source: typeof PAYABLI_EMBEDDED_SOURCE;
    });

export type PayabliEmbeddedMessage =
  | PaymentHeightMessage
  | PaymentReadyMessage
  | PaymentLifecycleMessage
  | PaymentOutcomeMessage;

const isRecord = (value: unknown): value is Record<string, unknown> => {
  return typeof value === 'object' && value !== null;
};

const isBridgeEnvelope = (value: unknown): value is Record<string, unknown> => {
  return (
    isRecord(value) &&
    value.channel === PAYABLI_BRIDGE_CHANNEL &&
    value.version === PAYABLI_BRIDGE_PROTOCOL_VERSION &&
    value.source === PAYABLI_EMBEDDED_SOURCE &&
    typeof value.type === 'string' &&
    'payload' in value
  );
};

const parseHeightMessage = (value: Record<string, unknown>): PaymentHeightMessage | null => {
  if (value.type !== 'contentHeight' || !isRecord(value.payload)) {
    return null;
  }

  const { height } = value.payload;
  if (typeof height !== 'number' || !Number.isFinite(height) || height <= 0) {
    return null;
  }

  return value as unknown as PaymentHeightMessage;
};

const parseReadyMessage = (value: Record<string, unknown>): PaymentReadyMessage | null => {
  if (value.type !== 'ready' || !isRecord(value.payload)) {
    return null;
  }

  const { isReady } = value.payload;
  if (typeof isReady !== 'boolean') {
    return null;
  }

  return value as unknown as PaymentReadyMessage;
};

const parseLifecycleMessage = (value: Record<string, unknown>): PaymentLifecycleMessage | null => {
  if ((value.type !== 'loaded' && value.type !== 'submit') || !isRecord(value.payload)) {
    return null;
  }

  if (typeof value.payload.status !== 'string') {
    return null;
  }

  return value as unknown as PaymentLifecycleMessage;
};

const parseOutcomeMessage = (value: Record<string, unknown>): PaymentOutcomeMessage | null => {
  if (value.type !== 'success' && value.type !== 'error') {
    return null;
  }

  return value as unknown as PaymentOutcomeMessage;
};

export const parseEmbeddedMessage = (raw: string): PayabliEmbeddedMessage | null => {
  try {
    const parsed = JSON.parse(raw) as unknown;

    if (!isBridgeEnvelope(parsed)) {
      return null;
    }

    return (
      parseHeightMessage(parsed) ??
      parseReadyMessage(parsed) ??
      parseLifecycleMessage(parsed) ??
      parseOutcomeMessage(parsed)
    );
  } catch {
    return null;
  }
};

export const createSubmitPaymentCommand = (): PaymentBridgeCommand => ({
  channel: PAYABLI_BRIDGE_CHANNEL,
  version: PAYABLI_BRIDGE_PROTOCOL_VERSION,
  source: PAYABLI_NATIVE_SOURCE,
  type: 'submitPayment',
  payload: null,
});

export const serializeNativeCommand = (command: PaymentBridgeCommand) => {
  return JSON.stringify(command);
};

export const serializeLogMessage = (message: PayabliEmbeddedMessage) => {
  return JSON.stringify(message, null, 2);
};

export const shouldLogMessage = (message: PayabliEmbeddedMessage) => {
  return message.type !== 'contentHeight';
};

export const isReadyMessage = (message: PayabliEmbeddedMessage) => {
  return message.type === 'ready' && message.payload.isReady;
};

export const getEmbeddedMessageHeight = (message: PayabliEmbeddedMessage) => {
  return message.type === 'contentHeight' ? message.payload.height : null;
};

export const appendUniqueLogEntry = (
  currentEntries: PayabliLogEntry[],
  nextEntry: PayabliLogEntry,
) => {
  const lastEntry = currentEntries[0];
  if (
    lastEntry &&
    lastEntry.type === nextEntry.type &&
    lastEntry.payloadText === nextEntry.payloadText
  ) {
    return currentEntries;
  }

  return [nextEntry, ...currentEntries].slice(0, 50);
};