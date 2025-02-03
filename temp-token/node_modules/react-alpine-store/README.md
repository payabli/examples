# [react-alpine-store](https://www.npmjs.com/package/react-alpine-store)

Allows for syncing between reactive state across React and Alpine's `$store`.
See more information about Alpine's `$store` [here](https://alpinejs.dev/magics/store).


Useful for projects that require both frameworks, such as an Alpine application within Astro that needs to drop in some React components.

## Usage

```js
// main.js
document.addEventListener("alpine:init", () => {
  Alpine.store("customThing", {
    count: 0;
  });
});
```

```js
// Counter.jsx
import { useAlpineStore } from "react-alpine-store"

export default function Counter() {
  const [ count, setCount ] = useAlpineStore("customThing.count");

  return (
    <div>
      <p>React Value: {count}</p>
      <br/>
      <button onClick={() => setCount(count + 1)}>React Increment</button>
      <br/>
      <p>Alpine Value: <span x-text="$store.customThing.count"></span></p>
      <br/>
      <button x-on:click="$store.customThing.count++">Alpine Increment</button>
    </div>
  )
}
```
