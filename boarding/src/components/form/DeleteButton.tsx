import { Trash2 } from 'lucide-react'
import { Button } from '@/components/ui/button'

export default function DeleteButton(
  props: React.ComponentProps<typeof Button>,
) {
  return (
    <div className="absolute right-0 top-0 z-50 flex min-h-fit items-center justify-center bg-background">
      <Button
        type="button"
        variant="ghost"
        size="icon"
        className="h-12 w-12 rounded-full bg-red-500 p-0 hover:bg-red-600 focus-visible:ring-red-700"
        {...props}
      >
        <span className="sr-only">Delete</span>
        <div className="absolute inset-0 flex items-center justify-center">
          <Trash2 className="h-6 w-6 text-background dark:text-background" />
        </div>
      </Button>
    </div>
  )
}
