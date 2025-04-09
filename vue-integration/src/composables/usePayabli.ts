// usePayabli.ts
import { ref, reactive, onMounted, watchEffect } from 'vue';

const loadedScripts = new Set<string>();

const useScript = (src: string) => {
  const isLoaded = ref(false);

  onMounted(() => {
    if (loadedScripts.has(src)) {
      isLoaded.value = true;
      return;
    }

    const existingScript = document.querySelector(`script[src="${src}"]`);

    const handleLoad = () => {
      loadedScripts.add(src);
      isLoaded.value = true;
      script.setAttribute("data-loaded", "true");
    };

    let script: HTMLScriptElement;

    if (!existingScript) {
      script = document.createElement("script");
      script.src = src;
      script.async = true;
      script.addEventListener("load", handleLoad);
      document.body.appendChild(script);
    } else {
      if (existingScript.getAttribute("data-loaded") === "true") {
        isLoaded.value = true;
      } else {
        existingScript.addEventListener("load", handleLoad);
      }
    }
  });

  return isLoaded;
};

declare var PayabliComponent: any;

export const usePayabli = (
  options: any,
  method: string,
  parameters: any = null,
  production = false
) => {
  const payOptions = reactive({ ...options });
  const isInitialized = ref(false);
  const payComponentRef = ref<any>(null);
  const initCallbacks: (() => void)[] = [];

  const scriptSrc = production
    ? "https://embedded-component.payabli.com/component.js"
    : "https://embedded-component-sandbox.payabli.com/component.js";

  const isScriptLoaded = useScript(scriptSrc);

  const initPayabli = () => {
    if (!isScriptLoaded.value || isInitialized.value) return;

    payComponentRef.value = new PayabliComponent(payOptions);
    isInitialized.value = true;

    initCallbacks.splice(0).forEach(cb => cb());
  };

  watchEffect(() => {
    if (isScriptLoaded.value) {
      initPayabli();
    }
  });

  watchEffect(() => {
    if (isInitialized.value && payComponentRef.value) {
      payComponentRef.value.updateConfig(payOptions);
    }
  });

  const payabliReinit = () => {
    if (isInitialized.value && payComponentRef.value) {
      payComponentRef.value.payabliExec("reinit");
    }
  };

  const payabliExec = () => {
    const exec = () => {
      if (!payComponentRef.value) return;

      if (payOptions.type === "methodEmbedded") {
        if (parameters != null) {
          payComponentRef.value.payabliExec(method, parameters);
        } else {
          payComponentRef.value.payabliExec(method);
        }
      } else if (
        payOptions.type === "methodLightbox" ||
        payOptions.type === "vterminal"
      ) {
        payComponentRef.value.showModal();
      }
    };

    if (isInitialized.value) {
      exec();
    } else {
      initCallbacks.push(exec);
    }
  };

  return [payOptions, payabliExec, payabliReinit] as const;
};
