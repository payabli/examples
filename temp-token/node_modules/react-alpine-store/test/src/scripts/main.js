import { useState, useEffect, useCallback, useRef } from 'react';

const initializeAlpine = () => {
  return new Promise((resolve) => {
    if (window.Alpine) {
      resolve(window.Alpine);
    } else {
      document.addEventListener('alpine:init', () => {
        resolve(window.Alpine);
      });
    }
  });
};

const getStoreValue = (Alpine, path) => {
  const parts = path.split('.');
  if (parts.length === 1) {
    return Alpine.store(parts[0]);
  }
  
  let value = Alpine.store(parts[0]);
  for (let i = 1; i < parts.length; i++) {
    if (value === undefined) break;
    value = value[parts[i]];
  }
  
  return value;
};

const setStoreValue = (Alpine, path, newValue) => {
  const parts = path.split('.');
  if (parts.length === 1) {
    Alpine.store(parts[0], newValue);
    return;
  }
  
  let target = Alpine.store(parts[0]);
  for (let i = 1; i < parts.length - 1; i++) {
    if (!(parts[i] in target)) {
      target[parts[i]] = {};
    }
    target = target[parts[i]];
  }
  
  target[parts[parts.length - 1]] = newValue;
};

export function useAlpineStore(path) {
  const [value, setValue] = useState(undefined);
  const alpineRef = useRef(null);
  const pathRef = useRef(path);

  useEffect(() => {
    initializeAlpine().then((Alpine) => {
      alpineRef.current = Alpine;
      const initialValue = getStoreValue(Alpine, pathRef.current);
      setValue(initialValue);

      const cleanup = Alpine.effect(() => {
        const currentValue = getStoreValue(Alpine, pathRef.current);
        if (currentValue !== value) {
          setValue(currentValue);
        }
      });

      return cleanup;
    });
  }, []);

  const updateValue = useCallback((newValue) => {
    if (!alpineRef.current) return;

    const updatedValue = typeof newValue === 'function'
      ? newValue(getStoreValue(alpineRef.current, pathRef.current))
      : newValue;

    setStoreValue(alpineRef.current, pathRef.current, updatedValue);
    setValue(updatedValue);
  }, []);

  return [value, updateValue];
}
