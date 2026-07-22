/// <reference types="astro/client" />

// react-syntax-highlighter@16 ships no bundled types, and @types/react-syntax-highlighter
// (DefinitelyTyped) hasn't published a matching major version yet. This declares only the
// exports this project actually imports.
declare module "react-syntax-highlighter" {
  import type { ComponentType, HTMLAttributes, CSSProperties } from "react"

  export interface SyntaxHighlighterProps extends HTMLAttributes<HTMLElement> {
    language?: string
    style?: Record<string, CSSProperties>
    customStyle?: CSSProperties
    codeTagProps?: HTMLAttributes<HTMLElement>
    showLineNumbers?: boolean
    wrapLongLines?: boolean
    children?: string
  }

  export const Prism: ComponentType<SyntaxHighlighterProps>
}

declare module "react-syntax-highlighter/dist/esm/styles/prism" {
  export const vscDarkPlus: Record<string, import("react").CSSProperties>
}
