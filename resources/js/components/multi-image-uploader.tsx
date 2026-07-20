"use client"

import { useCallback, useState, useEffect } from "react"
import { FileImage, Upload, X } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Label } from "@/components/ui/label"
import { cn } from "@/lib/utils"

interface ImageFile {
  id: string
  file: File
  preview: string
}

interface MultiImageUploaderProps {
  onImagesChange: (files: File[]) => void
  onDefaultImageRemove?: (removedUrl: string) => void // Add this prop
  className?: string
  maxFiles?: number
  defaultImages?: string[]
  title?: string
}

export function MultiImageUploader({
  onImagesChange,
  onDefaultImageRemove, // Add onDefaultImageRemove to props
  className,
  maxFiles = 5,
  defaultImages = [], // Initialize with empty array
  title = "Upload Images",
}: MultiImageUploaderProps) {
  const [images, setImages] = useState<ImageFile[]>([])
  const [dragActive, setDragActive] = useState(false)

  // Initialize with default images
  useEffect(() => {
    const loadDefaultImages = async () => {
      try {
        const defaultImageFiles = await Promise.all(
          defaultImages.map(async (url) => {
            const response = await fetch(url)
            const blob = await response.blob()
            const file = new File([blob], `image-${Math.random().toString(36).substring(7)}.jpg`, {
              type: blob.type,
            })
            return {
              id: Math.random().toString(36).substring(7),
              file,
              preview: url,
            }
          }),
        )
        setImages((prevImages) => {
          // Only set default images if there are no images yet
          if (prevImages.length === 0) {
            return defaultImageFiles
          }
          return prevImages
        })
      } catch (error) {
        console.error("Error loading default images:", error)
      }
    }

    if (defaultImages.length > 0 && images.length === 0) {
      loadDefaultImages()
    }
  }, [defaultImages]) // Remove images.length dependency

  useEffect(() => {
    onImagesChange(images.map((img) => img.file))
  }, [images, onImagesChange])

  const handleDrag = useCallback((e: React.DragEvent) => {
    e.preventDefault()
    e.stopPropagation()
    if (e.type === "dragenter" || e.type === "dragover") {
      setDragActive(true)
    } else if (e.type === "dragleave") {
      setDragActive(false)
    }
  }, [])

  const handleDrop = useCallback((e: React.DragEvent) => {
    e.preventDefault()
    e.stopPropagation()
    setDragActive(false)

    const droppedFiles = Array.from(e.dataTransfer.files).filter((file) => file.type.startsWith("image/"))
    handleFiles(droppedFiles)
  }, [])

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files?.length) {
      const selectedFiles = Array.from(e.target.files)
      handleFiles(selectedFiles)
    }
  }

  const handleFiles = (files: File[]) => {
    // Limit the number of files that can be added
    const remainingSlots = maxFiles - images.length
    const filesToAdd = files.slice(0, remainingSlots)

    const newImages = filesToAdd.map((file) => ({
      id: Math.random().toString(36).substring(7),
      file,
      preview: URL.createObjectURL(file),
    }))

    const updatedImages = [...images, ...newImages]
    setImages(updatedImages)
  }

  const handleRemove = (id: string) => {
    setImages((prevImages) => {
      const imageToRemove = prevImages.find((img) => img.id === id)
      if (imageToRemove) {
        // If it's a default image, notify parent to clear it
        if (defaultImages.includes(imageToRemove.preview) && onDefaultImageRemove) {
          onDefaultImageRemove(imageToRemove.preview)
        } else if (!defaultImages.includes(imageToRemove.preview)) {
          URL.revokeObjectURL(imageToRemove.preview)
        }
      }
      const updatedImages = prevImages.filter((img) => img.id !== id)
      onImagesChange(updatedImages.map((img) => img.file))
      return updatedImages
    })
  }

  // Cleanup previews on unmount
  useEffect(() => {
    return () => {
      // Clean up the preview URLs to avoid memory leaks
      images.forEach((image) => {
        // Only revoke URLs that we created, not the default image URLs
        if (!defaultImages.includes(image.preview)) {
          URL.revokeObjectURL(image.preview)
        }
      })
    }
  }, [images, defaultImages])

  return (
    <div className={cn("space-y-2", className)}>
      <Label>
        {/* {title} ({images.length}/{maxFiles}) */}
        {title}
      </Label>

      {/* Image Grid */}
      {images.length > 0 && maxFiles > 1 && (
        <div className="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
          {images.map((image) => (
            <div key={image.id} className="relative aspect-square rounded-lg overflow-hidden border">
              <img
                src={image.preview || "/placeholder.svg"}
                alt={`Preview ${image.file.name}`}
                className="object-cover w-full h-full"
              />
              <Button
                size="icon"
                variant="destructive"
                className="absolute right-2 top-2"
                onClick={() => handleRemove(image.id)}
                type="button"
              >
                <X className="h-4 w-4" />
                <span className="sr-only">Remove image</span>
              </Button>
            </div>
          ))}
        </div>
      )}

      {/* Image Grid */}
      {images.length > 0 && maxFiles === 1 && (
        images.map((image) => (
          <div key={image.id} className="relative aspect-square rounded-lg overflow-hidden border">
            <img
              src={image.preview || "/placeholder.svg"}
              alt={`Preview ${image.file.name}`}
              className="object-cover w-full h-full"
            />
            <Button
              size="icon"
              variant="destructive"
              className="absolute right-2 top-2"
              onClick={() => handleRemove(image.id)}
              type="button"
            >
              <X className="h-4 w-4" />
              <span className="sr-only">Remove image</span>
            </Button>
          </div>
        ))
      )}

      {/* Upload Area */}
      {images.length < maxFiles && (
        <div
          className={cn(
            "relative flex flex-col items-center justify-center rounded-lg border border-dashed p-6 transition-colors",
            dragActive ? "border-primary bg-primary/5" : "border-muted-foreground/25",
          )}
          onDragEnter={handleDrag}
          onDragLeave={handleDrag}
          onDragOver={handleDrag}
          onDrop={handleDrop}
        >
          <input
            type="file"
            multiple
            className="absolute h-full w-full opacity-0 cursor-pointer"
            accept="image/*"
            onChange={handleChange}
            aria-label="Upload images"
          />

          <div className="flex flex-col items-center gap-2 text-muted-foreground">
            <Upload className="h-8 w-8" />
            <div className="text-center">
              <p>Drag & drop images here, or click to select</p>
              <p className="text-xs">Supports: JPG, PNG, GIF (Max {maxFiles} images)</p>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}

