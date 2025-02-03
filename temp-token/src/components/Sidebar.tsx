import React, { useState, useRef, useEffect, useMemo, useCallback } from "react"
import { ChevronLeft, Book, Copy, Check, X, ChevronDown } from "lucide-react"
import { motion, AnimatePresence } from "framer-motion"
import { useAlpineStore } from "../utils/react-alpine-store"

interface Libraries {
  ReactMarkdown: typeof import("react-markdown").default
  remarkGfm: typeof import("remark-gfm").default
  SyntaxHighlighter: typeof import("react-syntax-highlighter").Prism
  vscDarkPlus: typeof import("react-syntax-highlighter/dist/esm/styles/prism").vscDarkPlus
}

const useClientSideLibraries = (): Libraries | null => {
  const [libraries, setLibraries] = useState<Libraries | null>(null)

  useEffect(() => {
    const loadLibraries = async () => {
      const [ReactMarkdown, { default: remarkGfm }, { Prism: SyntaxHighlighter }, { vscDarkPlus }] = await Promise.all([
        import("react-markdown"),
        import("remark-gfm"),
        import("react-syntax-highlighter"),
        import("react-syntax-highlighter/dist/esm/styles/prism"),
      ])

      setLibraries({
        ReactMarkdown: ReactMarkdown.default,
        remarkGfm,
        SyntaxHighlighter,
        vscDarkPlus,
      })
    }

    loadLibraries()
  }, [])

  return libraries
}

interface CodeBlockProps {
  language: string
  value: string
  SyntaxHighlighter: Libraries["SyntaxHighlighter"]
  vscDarkPlus: Libraries["vscDarkPlus"]
}

const CodeBlock: React.FC<CodeBlockProps> = React.memo(({ language, value, SyntaxHighlighter, vscDarkPlus }) => {
  const [copied, setCopied] = useState(false)

  const handleCopy = useCallback(() => {
    navigator.clipboard.writeText(value)
    setCopied(true)
    setTimeout(() => setCopied(false), 2000)
  }, [value])

  if (!SyntaxHighlighter) {
    return <pre>{value}</pre>
  }

  return (
    <div className="relative">
      <SyntaxHighlighter
        language={language}
        style={vscDarkPlus}
        customStyle={{
          margin: 0,
          padding: 0,
          backgroundColor: "transparent",
          overflow: "visible",
        }}
        codeTagProps={{
          style: {
            backgroundColor: "transparent",
            padding: "1rem",
            paddingLeft: "0",
            display: "block",
          },
        }}
      >
        {value}
      </SyntaxHighlighter>
      <button
        className="text-gray-500 hover:text-gray-200 dark:text-gray-400 dark:hover:text-gray-100 transition-all absolute w-8 h-8 -top-1 -right-2 flex justify-center items-center"
        onClick={handleCopy}
      >
        {!copied ? <Copy size={20} /> : <Check size={20} />}
      </button>
    </div>
  )
})

CodeBlock.displayName = "CodeBlock"

interface MessageProps {
  message: string
  isNew: boolean
  libraries: Libraries | null
}

const Message: React.FC<MessageProps> = React.memo(({ message, isNew, libraries }) => {
  if (!libraries) {
    return <div className="animate-pulse bg-gray-200 dark:bg-gray-700 h-20 rounded mb-4"></div>
  }

  const { ReactMarkdown, remarkGfm, SyntaxHighlighter, vscDarkPlus } = libraries

  return (
    <motion.div
      initial={isNew ? { opacity: 0, y: 20 } : { opacity: 1, y: 0 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.3 }}
      className="message-item mb-4 overflow-hidden"
    >
      <div className="rounded-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow overflow-hidden">
        <ReactMarkdown
          remarkPlugins={[remarkGfm]}
          components={{
          h1: ({ node, ...props }) => <h1 className="text-3xl font-bold my-4" {...props} />,
          h2: ({ node, ...props }) => <h2 className="text-2xl font-bold my-3" {...props} />,
          h3: ({ node, ...props }) => <h3 className="text-xl font-bold my-2" {...props} />,
          h4: ({ node, ...props }) => <h4 className="text-lg font-bold my-2" {...props} />,
          h5: ({ node, ...props }) => <h5 className="text-base font-bold my-1" {...props} />,
          h6: ({ node, ...props }) => <h6 className="text-sm font-bold my-1" {...props} />,           
          code({ node, inline, className, children, ...props }) {
              const match = /language-(\w+)/.exec(className || "")
              return !inline && match ? (
                <CodeBlock
                  language={match[1]}
                  value={String(children).replace(/\n$/, "")}
                  SyntaxHighlighter={SyntaxHighlighter}
                  vscDarkPlus={vscDarkPlus}
                  {...props}
                />
              ) : (
                <code className={className} {...props}>
                  {children}
                </code>
              )
            },
          }}
          className="prose prose-sm dark:prose-invert max-w-none overflow-hidden"
        >
          {message}
        </ReactMarkdown>
      </div>
    </motion.div>
  )
})

Message.displayName = "Message"

interface ClientSideContentProps {
  messages: string[]
  libraries: Libraries | null
}

const ClientSideContent: React.FC<ClientSideContentProps> = ({ messages, libraries }) => {
  const [renderedMessages, setRenderedMessages] = useState<Array<{ content: string; isNew: boolean }>>([])

  useEffect(() => {
    if (messages && messages.length > renderedMessages.length) {
      const newMessages = messages.slice(renderedMessages.length)
      setRenderedMessages((prev) => [...prev, ...newMessages.map((msg) => ({ content: msg, isNew: true }))])
    }
  }, [messages, renderedMessages.length])

  return (
    <>
      {renderedMessages.map((message, index) => (
        <Message key={index} message={message.content} isNew={message.isNew} libraries={libraries} />
      ))}
    </>
  )
}

const useMediaQuery = (query: string): boolean => {
  const [matches, setMatches] = useState(false)

  useEffect(() => {
    const media = window.matchMedia(query)
    if (media.matches !== matches) {
      setMatches(media.matches)
    }
    const listener = () => setMatches(media.matches)
    media.addListener(listener)
    return () => media.removeListener(listener)
  }, [matches, query])

  return matches
}

export default function SidebarChat() {
  const [messages] = useAlpineStore("sidebar.messages")
  const [isExpanded, setIsExpanded] = useState(false)
  const [unreadCount, setUnreadCount] = useState(0)
  const messagesEndRef = useRef<HTMLDivElement>(null)
  const prevMessagesLengthRef = useRef(0)
  const lastOpenedRef = useRef(Date.now())
  const isMobile = useMediaQuery("(max-width: 768px)")
  const libraries = useClientSideLibraries()
  const [isCtrlKHeld, setIsCtrlKHeld] = useState(false) //Added as per update 1

  const toggleSidebar = useCallback(() => {
    setIsExpanded((prev) => {
      if (!prev) {
        lastOpenedRef.current = Date.now()
        setUnreadCount(0)
      }
      return !prev
    })
  }, [])

  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.ctrlKey && e.key === "k") {
        setIsCtrlKHeld(true) //Added as per update 2
        e.preventDefault()
        toggleSidebar()
      }
    }

    const handleKeyUp = (e: KeyboardEvent) => {
      //Added as per update 2
      if (e.key === "Control" || e.key === "k") {
        setIsCtrlKHeld(false) //Added as per update 2
      }
    }

    window.addEventListener("keydown", handleKeyDown) //Added as per update 2
    window.addEventListener("keyup", handleKeyUp) //Added as per update 2

    return () => {
      window.removeEventListener("keydown", handleKeyDown) //Added as per update 2
      window.removeEventListener("keyup", handleKeyUp) //Added as per update 2
    }
  }, [toggleSidebar])

  useEffect(() => {
    if (messages) {
      const newMessagesCount = messages.length - prevMessagesLengthRef.current
      if (newMessagesCount > 0) {
        if (!isExpanded) {
          setUnreadCount((prev) => prev + newMessagesCount)
        } else {
          const newMessages = messages.slice(-newMessagesCount)
          const hasUnreadMessages = newMessages.some((msg) => new Date(msg.timestamp).getTime() > lastOpenedRef.current)
          if (hasUnreadMessages) {
            setUnreadCount((prev) => prev + newMessagesCount)
          }
        }
        messagesEndRef.current?.scrollIntoView({ behavior: "smooth" })
      }
      prevMessagesLengthRef.current = messages.length
    }
  }, [messages, isExpanded])

  const sidebarVariants = useMemo(
    () => ({
      open: isMobile ? { y: 0 } : { x: 0 },
      closed: isMobile ? { y: "100%" } : { x: "-100%" },
    }),
    [isMobile],
  )

  const buttonVariants = useMemo(
    () => ({
      open: isMobile ? { x: "150%" } : { x: 384 },
      closed: { x: 0 },
    }),
    [isMobile],
  )

  const sidebarTransition = useMemo(
    () => ({
      type: isMobile ? "tween" : "spring",
      duration: isMobile ? 0.2 : undefined,
      ease: isMobile ? [0.25, 0.1, 0.25, 1] : undefined,
      stiffness: isMobile ? undefined : 500,
      damping: isMobile ? undefined : 30,
    }),
    [isMobile],
  )

  const buttonTransition = useMemo(
    () => ({
      type: isMobile ? "tween" : "spring",
      duration: isMobile ? 0.2 : undefined,
      ease: isMobile ? [0.25, 0.1, 0.25, 1] : undefined,
      stiffness: isMobile ? undefined : 500,
      damping: isMobile ? undefined : 30,
    }),
    [isMobile],
  )

  return (
    <>
      <AnimatePresence>
        {isExpanded && (
          <motion.div
            className="fixed md:w-96 md:top-0 md:left-0 md:h-full bottom-0 left-0 right-0 h-[80vh] bg-gray-100 dark:bg-gray-900 border-t-2 md:border-t-0 md:border-r-2 border-indigo-500/50 shadow-2xl z-50 flex flex-col"
            initial="closed"
            animate="open"
            exit="closed"
            variants={sidebarVariants}
            transition={sidebarTransition}
          >
            <div className="flex justify-between items-center p-4 border-b border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800">
              <h1 className="text-2xl md:text-4xl font-bold text-gray-900 dark:text-white">Guide</h1>
              <button
                onClick={toggleSidebar}
                className="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
              >
                <X size={24} />
              </button>
            </div>
            <div className="flex-1 overflow-y-auto p-4 overflow-hidden">
              <ClientSideContent messages={messages} libraries={libraries} />
              <div ref={messagesEndRef} className="h-24" />
            </div>
          </motion.div>
        )}
      </AnimatePresence>
      <div className="relative">
        <motion.button
          onClick={toggleSidebar}
          className="fixed md:bottom-3 md:left-3 bottom-3 right-3 z-50 md:w-12 md:h-12 border-2 border-indigo-500/50 flex h-12 w-12 rounded-md items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 transition-all hover:bg-gray-300 dark:hover:bg-gray-700 hover:shadow-lg"
          whileHover={{ scale: 1.1 }}
          whileTap={{ scale: 0.9 }}
        >
          <AnimatePresence mode="wait" initial={false}>
            {isExpanded ? (
              <motion.div
                key="chevron"
                initial={{ opacity: 0, scale: 0.8 }}
                animate={{ opacity: 1, scale: 1 }}
                exit={{ opacity: 0, scale: 0.8 }}
                transition={{ duration: 0.1 }}
              >
                <ChevronLeft size={32} className="text-indigo-500 dark:text-indigo-400 md:block hidden" />
                <ChevronDown size={32} className="text-indigo-500 dark:text-indigo-400 md:hidden block" />
              </motion.div>
            ) : (
              <motion.div
                key="book"
                initial={{ opacity: 0, scale: 0.8 }}
                animate={{ opacity: 1, scale: 1 }}
                exit={{ opacity: 0, scale: 0.8 }}
                transition={{ duration: 0.1 }}
              >
                <Book size={24} className="text-indigo-500 dark:text-indigo-400" />
              </motion.div>
            )}
          </AnimatePresence>
          <AnimatePresence>
            {unreadCount > 0 && !isExpanded && (
              <>
                <motion.span
                  key="unread-badge"
                  className="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white z-50"
                  initial={{ scale: 0 }}
                  animate={{ scale: 1 }}
                  exit={{ scale: 0 }}
                  transition={{ duration: 0.2 }}
                >
                  {unreadCount}
                </motion.span>
                <span className="absolute -right-2 -top-2 flex h-5 w-5 justify-center rounded-full bg-red-500 animate-[ping_1.5s_ease-in-out_infinite] z-40"></span>
              </>
            )}
          </AnimatePresence>
          <kbd
            className={`pointer-events-none absolute -right-28 border-2 border-gray-400/50 bg-gray-800 text-white font-mono z-50 py-1 px-2 rounded-lg ${
              isCtrlKHeld ? "ring-2 ring-sky-400" : ""
            }`}
          >
            Ctrl + K
          </kbd>{" "}
          {/*Added as per update 3*/}
        </motion.button>
      </div>
    </>
  )
}

