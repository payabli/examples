import { useAlpineStore } from "../scripts/main.js"

export default function CounterReact(props) {
  const [count, setCount] = useAlpineStore(props.index)

  return (
    <div>
      <p>Count: {count}</p>
      <button onClick={() => setCount(count + 1)}>Increment</button>
    </div>
  )
}
