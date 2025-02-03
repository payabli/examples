import { useState, useEffect, useCallback, useRef } from "react"

const initializeAlpine = () => {
  return new Promise((resolve) => {
    if (window.Alpine) {
      resolve(window.Alpine)
    } else {
      document.addEventListener("alpine:init", () => {
        resolve(window.Alpine)
      })
    }
  })
}

const getStoreValue = (Alpine, path) => {
  const parts = path.split(".")
  let value = Alpine.store(parts[0])
  for (let i = 1; i < parts.length; i++) {
    if (value === undefined) break
    value = value[parts[i]]
  }
  return value
}

const setStoreValue = (Alpine, path, newValue) => {
  const parts = path.split(".")
  const storeName = parts[0]
  const store = Alpine.store(storeName)

  if (parts.length === 1) {
    Alpine.store(storeName, newValue)
  } else {
    let target = store
    for (let i = 1; i < parts.length - 1; i++) {
      if (!(parts[i] in target)) {
        target[parts[i]] = {}
      }
      target = target[parts[i]]
    }
    target[parts[parts.length - 1]] = newValue
  }

  // Trigger Alpine's reactivity
  Alpine.store(storeName, { ...store })
}

const unwrapProxy = (value) => {
  if (Array.isArray(value)) {
    return value.map(unwrapProxy)
  } else if (typeof value === "object" && value !== null) {
    return Object.fromEntries(Object.entries(value).map(([k, v]) => [k, unwrapProxy(v)]))
  }
  return value
}

export function useAlpineStore(path) {
  const [value, setValue] = useState(undefined)
  const alpineRef = useRef(null)
  const pathRef = useRef(path)

  useEffect(() => {
    let unsubscribe = null

    initializeAlpine().then((Alpine) => {
      alpineRef.current = Alpine
      const initialValue = getStoreValue(Alpine, pathRef.current)
      setValue(unwrapProxy(initialValue))

      unsubscribe = Alpine.effect(() => {
        const currentValue = getStoreValue(Alpine, pathRef.current)
        setValue(unwrapProxy(currentValue))
      })
    })

    return () => {
      if (unsubscribe) {
        unsubscribe()
      }
    }
  }, [])

  useEffect(() => {
    pathRef.current = path
    if (alpineRef.current) {
      const currentValue = getStoreValue(alpineRef.current, path)
      setValue(unwrapProxy(currentValue))
    }
  }, [path])

  const updateValue = useCallback((newValue) => {
    if (!alpineRef.current) return

    const updatedValue =
      typeof newValue === "function"
        ? newValue(unwrapProxy(getStoreValue(alpineRef.current, pathRef.current)))
        : newValue

    setStoreValue(alpineRef.current, pathRef.current, updatedValue)
  }, [])

  return [value, updateValue]
}

