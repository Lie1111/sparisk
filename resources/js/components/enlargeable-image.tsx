"use client"

import * as React from "react"
import { X } from "lucide-react"

import { Button } from "@/components/ui/button"
import { Dialog, DialogContent, DialogTrigger } from "@/components/ui/dialog"

interface EnlargeableImageProps {
  src: string
  alt: string
  width: number
  height: number
  className?: string
}

export function EnlargeableImage({ src, alt, width, height, className }: EnlargeableImageProps) {
  const [open, setOpen] = React.useState(false)

  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogTrigger asChild>
        <div className="overflow-hidden">
          <img
            src={src || "/placeholder.svg"}
            alt={alt}
            width={width}
            height={height}
            className={`cursor-zoom-in object-cover transition-transform hover:scale-105 ${className ?? ""}`}
          />
        </div>
      </DialogTrigger>
      <DialogContent className="fixed left-[50%] top-[50%] max-h-[90vh] w-[90vw] max-w-[90vw] translate-x-[-50%] translate-y-[-50%] border-none bg-transparent p-0 md:w-[60vw] md:max-w-[60vw]">
        <div className="relative">
          <Button
            size="icon"
            variant="secondary"
            className="absolute right-2 top-2 z-50 h-8 w-8 rounded-full bg-white/80 backdrop-blur-sm"
            onClick={() => setOpen(false)}
          >
            <X className="h-4 w-4" />
            <span className="sr-only">Close</span>
          </Button>
          <div className="relative flex items-center justify-center">
            <img
              src={src || "/placeholder.svg"}
              alt={alt}
              className="h-auto max-h-[80vh] w-full rounded-lg object-contain"
            />
          </div>
        </div>
      </DialogContent>
    </Dialog>
  )
}

