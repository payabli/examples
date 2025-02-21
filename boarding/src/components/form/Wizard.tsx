import React, { useState, type ReactNode, useRef } from "react"
import { motion, AnimatePresence } from "framer-motion"
import { ChevronLeft, ChevronRight, Check, Loader2Icon } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Card, CardContent } from "@/components/ui/card"

type WizardStepProps = {
  icon: ReactNode
  label: string
  children: ReactNode
}

export function WizardStep({ icon, label, children }: WizardStepProps) {
  return <div className="wizard-step">{children}</div>
}

type WizardProps = {
  currentPage: number
  setCurrentPage: (page: number) => void
  preChildren?: ReactNode
  postChildren?: ReactNode
  children: ReactNode
  onPageChange?: (newPage: number) => void
}

export function Wizard({
  currentPage,
  setCurrentPage,
  children,
  preChildren,
  postChildren,
  onPageChange,
}: WizardProps) {
  const steps = React.Children.toArray(children).filter(
    (child) => React.isValidElement(child) && child.props.className !== "wizard-step",
  ) as React.ReactElement<WizardStepProps>[]
  const totalPages = steps.length

  const nextPage = (event: React.MouseEvent<HTMLButtonElement, MouseEvent>) => {
    event.preventDefault()
    const newPage = Math.min(currentPage + 1, totalPages - 1)
    setCurrentPage(newPage)
    if (onPageChange) onPageChange(newPage)
    scrollToTop()
  }
  const prevPage = () => {
    const newPage = Math.max(currentPage - 1, 0)
    setCurrentPage(newPage)
    if (onPageChange) onPageChange(newPage)
    scrollToTop()
  }
  const goToPage = (page: number) => {
    const newPage = Math.max(0, Math.min(page, totalPages - 1))
    setCurrentPage(newPage)
    if (onPageChange) onPageChange(newPage)
    scrollToTop()
  }

  const wizardHeaderRef = useRef<HTMLDivElement>(null)

  const scrollToTop = () => {
    if (wizardHeaderRef.current) {
      wizardHeaderRef.current.scrollIntoView({ behavior: "smooth" })
    }
  }

  return (
    <>
      <div ref={wizardHeaderRef} />
      <Card className="relative mx-auto mb-12 w-full max-w-4xl">
        {preChildren}
        <CardContent className="p-6">
          <WizardStepIndicator
            steps={steps}
            currentPage={currentPage}
            setCurrentPage={setCurrentPage}
            totalPages={totalPages}
            goToPage={goToPage}
          />
          <ProgressBar currentPage={currentPage} totalPages={totalPages} />
          <div className="mt-6 min-h-[300px] w-full">
            <AnimatePresence mode="wait">
              <motion.div
                key={currentPage}
                initial={{ opacity: 0, x: -20 }}
                animate={{ opacity: 1, x: 0 }}
                exit={{ opacity: 0, x: 20 }}
                transition={{ duration: 0.3 }}
                className="w-full"
              >
                {steps[currentPage]}
              </motion.div>
            </AnimatePresence>
          </div>

          {postChildren}
          <WizardNavigation
            currentPage={currentPage}
            setCurrentPage={setCurrentPage}
            totalPages={totalPages}
            nextPage={nextPage}
            prevPage={prevPage}
          />
        </CardContent>
      </Card>
    </>
  )
}

type WizardStepIndicatorProps = {
  steps: React.ReactElement<WizardStepProps>[]
  currentPage: number
  setCurrentPage: (page: number) => void
  totalPages: number
  goToPage: (page: number) => void
}

function WizardStepIndicator({ steps, currentPage, setCurrentPage, totalPages, goToPage }: WizardStepIndicatorProps) {
  return (
    <div className="mb-6">
      <div className="hidden justify-between sm:flex">
        {steps.map((step, index) => (
          <div key={index} className="flex flex-1 cursor-pointer flex-col items-center" onClick={() => goToPage(index)}>
            <div
              className={`flex h-10 w-10 items-center justify-center rounded-full border-2 ${
                index === currentPage
                  ? "border-primary bg-primary text-primary-foreground"
                  : "border-muted bg-background text-muted-foreground"
              }`}
            >
              {step.props.icon}
            </div>
            <span
              className={`mt-2 text-sm ${index === currentPage ? "font-medium text-primary" : "text-muted-foreground"}`}
            >
              {step.props.label}
            </span>
          </div>
        ))}
      </div>
      <div className="flex flex-col items-center sm:hidden">
        <AnimatePresence mode="wait">
          <motion.div
            key={currentPage}
            initial={{ opacity: 0, x: -20 }}
            animate={{ opacity: 1, x: 0 }}
            exit={{ opacity: 0, x: 20 }}
            transition={{ duration: 0.3 }}
            className="flex flex-col items-center"
          >
            <div className="flex h-10 w-10 items-center justify-center rounded-full border-2 border-primary bg-primary text-primary-foreground">
              {steps[currentPage].props.icon}
            </div>
            <span className="mt-2 text-sm font-medium text-primary">{steps[currentPage].props.label}</span>
          </motion.div>
        </AnimatePresence>
        <div className="text-center sm:hidden">
          <span className="text-sm text-muted-foreground">
            Page {currentPage + 1} of {totalPages}
          </span>
        </div>
      </div>
    </div>
  )
}

type ProgressBarProps = {
  currentPage: number
  totalPages: number
}

function ProgressBar({ currentPage, totalPages }: ProgressBarProps) {
  const progress = ((currentPage + 1) / totalPages) * 100

  return (
    <div className="h-1.5 w-full overflow-hidden rounded-full bg-muted">
      <div className="h-full bg-primary transition-all duration-300 ease-in-out" style={{ width: `${progress}%` }} />
    </div>
  )
}

type WizardNavigationProps = {
  currentPage: number
  setCurrentPage: (page: number) => void
  totalPages: number
  nextPage: (event: React.MouseEvent<HTMLButtonElement, MouseEvent>) => void
  prevPage: () => void
}

function WizardNavigation({ currentPage, setCurrentPage, totalPages, nextPage, prevPage }: WizardNavigationProps) {
  const isLastPage = currentPage === totalPages - 1

  const [loading, setLoading] = useState(false)

  const handleClick = () => {
    setLoading(true)
    setTimeout(() => {
      setLoading(false)
    }, 4000)
  }

  return (
    <div className="relative mt-6 flex items-center justify-between">
      <Button type="button" onClick={prevPage} disabled={currentPage === 0} className="min-w-[100px]">
        <ChevronLeft className="mr-2 h-4 w-4" /> Previous
      </Button>
      <div className="absolute left-1/2 top-1/2 hidden -translate-x-1/2 -translate-y-1/2 transform text-sm text-muted-foreground sm:block">
        Page {currentPage + 1} of {totalPages}
      </div>
      {!isLastPage ? (
        <Button className="min-w-[100px]" onClick={nextPage} type="button">
          Next <ChevronRight className="ml-2 h-4 w-4" />
        </Button>
      ) : (
        <Button type="submit" className="min-w-[100px]" onClick={handleClick}>
          Confirm{" "}
          {!loading ? <Check className="ml-2 h-4 w-4" /> : <Loader2Icon className="ml-2 h-4 w-4 animate-spin" />}
        </Button>
      )}
    </div>
  )
}

