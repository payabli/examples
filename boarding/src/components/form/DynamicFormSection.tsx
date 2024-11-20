import React, { useState, useEffect, useRef, ReactElement } from 'react'
import { Button } from '@/components/ui/button'
import { Separator } from '@/components/ui/separator'
import DeleteButton from './DeleteButton'
import { Plus } from 'lucide-react'
import { AnimatePresence, motion } from 'framer-motion'

interface DynamicFormSectionProps {
  title: string
  items: any[]
  addItem: () => void
  removeItem: (index: number) => void
  addButtonText: string
  children: ReactElement | ReactElement[]
}

export function DynamicFormSection({
  title,
  items,
  addItem,
  removeItem,
  addButtonText,
  children,
}: DynamicFormSectionProps) {
  const [animatedItems, setAnimatedItems] = useState<number[]>([])
  const scrollRef = useRef<HTMLDivElement>(null)

  useEffect(() => {
    if (items.length > animatedItems.length) {
      setAnimatedItems((prev) => [...prev, items.length - 1])
    } else if (items.length < animatedItems.length) {
      setAnimatedItems((prev) => prev.slice(0, items.length))
    }
  }, [items.length])
  const handleRemoveItem = (index: number) => {
    removeItem(index)
    setAnimatedItems(
      animatedItems
        .filter((i) => i !== index)
        .map((i) => (i > index ? i - 1 : i)),
    )

    // Scroll to the previous entry
    if (scrollRef.current && index > 0) {
      const previousEntry = scrollRef.current.children[
        index - 1
      ] as HTMLDivElement
      previousEntry.scrollIntoView({ behavior: 'smooth' })
    }
  }

  return (
    <div className="mb-16 space-y-4">
      <h3 className="text-lg font-semibold">{title}</h3>
      <AnimatePresence initial={false}>
        {items.map((_, index) => (
          <motion.div
            key={index}
            initial={{ opacity: 0, height: 0, scale: 0 }}
            animate={{ opacity: 1, height: 'auto', scale: 1 }}
            exit={{ opacity: 0, height: 0, scale: 0 }}
            transition={{ duration: 0.3, ease: 'easeInOut' }}
          >
            {index > 0 && <Separator className="mb-2" />}
            <div
              className="relative items-end gap-4 rounded p-4 md:grid md:grid-cols-2"
              ref={index === items.length - 1 ? scrollRef : null}
            >
              {index > 0 && (
                <DeleteButton
                  onClick={() => removeItem(index)}
                  aria-label={`Remove ${title.toLowerCase()}`}
                />
              )}
              {React.Children.map(children, (child) => {
                if (React.isValidElement<any>(child)) {
                  return React.cloneElement(child, {
                    name: child.props.name.replace('[]', `[${index}]`),
                    key: `${child.props.name}-${index}`,
                  })
                }
                return child
              })}
            </div>
          </motion.div>
        ))}
      </AnimatePresence>
      <Button onClick={addItem} className="w-full" type="button">
        <Plus className="mr-2 h-4 w-4" /> {addButtonText}
      </Button>
    </div>
  )
}
