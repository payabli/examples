import Alpine from "alpinejs";
import Prism from "prismjs";
import { xCopyPaste } from "./copypaste.js";

document.addEventListener("alpine:init", () => {
  // INIT
  Prism.highlightAll()

  xCopyPaste(Alpine);

  // STORE
  Alpine.store("sidebar", {
    messages: [
      "## Intro\nWelcome to the [Payabli](https://payabli.com/) Temp Token Flow Example App! As you progress through the transaction, the application will update the sidebar with the *current state* of the transaction.\n\nTo learn more about using the temporary token flow with Payabli's embedded components, visit the [docs](https://docs.payabli.com/developer-guides/tokenization-temporary-flow).",
      "## Setup\n1. Enable temporary tokens in the config:\n```javascript\ntemporaryToken: true\n```\n2. Set the component's action to `method`:\n```javascript\nfunction executeAction() {\n  paycomponent0.payabliExec('method', {\n   // paymentDetails\n  })\n}\n```\n3. Call the `executeAction` function when the user clicks the button to start the transaction.\n```javascript\ndocument\n.getElementById('btnx')\n.addEventListener('click', executeAction)\n```",
    ],
  });
  Alpine.store("checkout", {
    loading: false,
  });
});
