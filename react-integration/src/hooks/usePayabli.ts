// usePayabli.ts
import { useState, useEffect, useRef, useCallback } from "react";

const useScript = (src: string) => {
  const [isLoaded, setIsLoaded] = useState(false);

  useEffect(() => {
    const existingScript = document.querySelector(`script[src="${src}"]`);

    const onLoad = () => {
      setIsLoaded(true);
    };

    if (!existingScript) {
      const script = document.createElement("script");
      script.src = src;
      script.async = true;

    script.addEventListener("load", onLoad);

    document.body.appendChild(script);
    
    return () => {
      script.removeEventListener("load", onLoad);
      document.body.removeChild(script);
    };
        } else {
    if (existingScript.getAttribute("data-loaded") === "true") {
      setIsLoaded(true);
    } else {
      existingScript.addEventListener("load", onLoad);
    }
        }
  }, [src]);

  return isLoaded;
};

declare var PayabliComponent: any;

export const usePayabli = (
  options: any,
  method: string,
  parameters: any = null,
  production: boolean = false
) => {
  const [payOptions, setPayOptions] = useState(options);
  const [isInitialized, setIsInitialized] = useState(false);
  const payComponentRef = useRef<any>(null);
  const initCallbacks = useRef<(() => void)[]>([]); // Queue for functions waiting on initialization

  const scriptSrc = production ? "https://embedded-component.payabli.com/component.js" : "https://embedded-component-sandbox.payabli.com/component.js";
  
  const isScriptLoaded = useScript(scriptSrc);

  useEffect(() => {
    if (isScriptLoaded) {
      payComponentRef.current = new PayabliComponent(payOptions);
      setIsInitialized(true);
  
      // payabliExecute queued callbacks
      initCallbacks.current.forEach((cb) => cb());
      initCallbacks.current = []; // Clear the queue
    }
  }, [isScriptLoaded, payOptions]);

  useEffect(() => {
    if (isInitialized && payComponentRef.current) {
      payComponentRef.current.updateConfig(payOptions);
    }
  }, [isInitialized, payOptions]);

  const payabliReinit = useCallback(() => {
    if (isInitialized && payComponentRef.current) {
      payComponentRef.current.payabliExec("reinit");
    }
  }, [isInitialized]);

  const payabliExec = useCallback(() => {
    const payabliExecuteMethod = () => {
      if (payOptions.type === "methodEmbedded") {
        if (parameters != null) {
          payComponentRef.current.payabliExec(method, parameters);
        } else {
          payComponentRef.current.payabliExec(method);
        }
      } else if (payOptions.type === "methodLightbox" || payOptions.type === "vterminal") {
        payComponentRef.current.showModal()
      }
    };

    if (isInitialized && payComponentRef.current) {
      payabliExecuteMethod();
    } else {
      initCallbacks.current.push(payabliExecuteMethod); // Queue the payabliExec
    }
  }, [isInitialized, method, parameters]);

  return [payOptions, setPayOptions, payabliExec, payabliReinit];
};
