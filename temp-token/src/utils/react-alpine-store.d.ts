// react-alpine-store.js is a plain untyped bridge to an Alpine.js store: the value's
// actual shape depends entirely on what's stored at the given path at runtime, so it
// can't be inferred from the implementation (a bare array literal return, which
// TypeScript would otherwise collapse into an incorrect concrete union type).
export function useAlpineStore<T = any>(
  path: string,
): [T | undefined, (value: T | ((prev: T) => T)) => void]
