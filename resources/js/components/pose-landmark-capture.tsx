import { useEffect, useRef, useState, useCallback } from 'react'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Card, CardContent } from '@/components/ui/card'
import { Camera, RefreshCw, Save, Loader2, X } from 'lucide-react'
import { PoseLandmarker, FilesetResolver, type NormalizedLandmark } from '@mediapipe/tasks-vision'
import { router } from '@inertiajs/react'
import { toast } from 'sonner'

// MediaPipe pose landmark connections for skeleton drawing
const POSE_CONNECTIONS: [number, number][] = [
    [0, 1], [1, 2], [2, 3], [0, 4], [4, 5], [5, 6], [1, 7], [4, 8], [7, 9], [8, 10], [9, 10],
    [11, 12], [11, 13], [12, 14], [13, 15], [14, 16],
    [15, 17], [15, 19], [15, 21], [16, 18], [16, 20], [16, 22], [17, 19], [18, 20],
    [11, 23], [12, 24], [23, 24],
    [23, 25], [24, 26], [25, 27], [26, 28], [27, 29], [28, 30], [27, 31], [28, 32], [29, 31], [30, 32],
]

const LANDMARK_COLORS: Record<string, string> = {
    face: '#FF6B6B',
    torso: '#4ECDC4',
    leftArm: '#45B7D1',
    rightArm: '#96CEB4',
    leftLeg: '#FFEAA7',
    rightLeg: '#DDA0DD',
}

function getLandmarkColor(index: number): string {
    if (index <= 10) return LANDMARK_COLORS.face
    if (index >= 11 && index <= 12) return LANDMARK_COLORS.torso
    if (index >= 13 && index <= 22) return index % 2 === 1 ? LANDMARK_COLORS.leftArm : LANDMARK_COLORS.rightArm
    if (index >= 23 && index <= 24) return LANDMARK_COLORS.torso
    if (index >= 25) return index % 2 === 1 ? LANDMARK_COLORS.leftLeg : LANDMARK_COLORS.rightLeg
    return '#888'
}

function getConnectionColor(from: number, to: number): string {
    if (from <= 10 && to <= 10) return LANDMARK_COLORS.face
    if ((from >= 11 && from <= 22) || (to >= 11 && to <= 22)) {
        if (from % 2 === 1 || to % 2 === 1) return LANDMARK_COLORS.leftArm
        return LANDMARK_COLORS.rightArm
    }
    if (from === 11 && to === 12) return LANDMARK_COLORS.torso
    if (from === 23 && to === 24) return LANDMARK_COLORS.torso
    if (from === 11 && to === 23) return '#666'
    if (from === 12 && to === 24) return '#666'
    if (from >= 23 || to >= 23) {
        if (from % 2 === 1 || to % 2 === 1) return LANDMARK_COLORS.leftLeg
        return LANDMARK_COLORS.rightLeg
    }
    return '#aaa'
}

const LANDMARK_LABELS: Record<number, string> = {
    0: 'nose', 1: 'L eye in', 2: 'L eye', 3: 'L eye out',
    4: 'R eye in', 5: 'R eye', 6: 'R eye out', 7: 'L ear', 8: 'R ear',
    9: 'mouth L', 10: 'mouth R', 11: 'L shoulder', 12: 'R shoulder',
    13: 'L elbow', 14: 'R elbow', 15: 'L wrist', 16: 'R wrist',
    17: 'L pinky', 18: 'R pinky', 19: 'L index', 20: 'R index',
    21: 'L thumb', 22: 'R thumb', 23: 'L hip', 24: 'R hip',
    25: 'L knee', 26: 'R knee', 27: 'L ankle', 28: 'R ankle',
    29: 'L heel', 30: 'R heel', 31: 'L foot', 32: 'R foot',
}

interface LandmarkPoint {
    x: number
    y: number
    z: number
    visibility?: number
}

interface PoseLandmarkCaptureProps {
    assessmentId: number
    existingViews?: string[]
}

export default function PoseLandmarkCapture({ assessmentId, existingViews = [] }: PoseLandmarkCaptureProps) {
    const [mode, setMode] = useState<'camera' | 'captured' | 'saving'>('camera')
    const [landmarks, setLandmarks] = useState<LandmarkPoint[] | null>(null)
    const [selectedView, setSelectedView] = useState<string>(
        ['front', 'back', 'right_side', 'left_side'].find(v => !existingViews.includes(v)) || 'front'
    )
    const [mediapipeReady, setMediapipeReady] = useState(false)
    const [cameraError, setCameraError] = useState<string | null>(null)
    const [isDetecting, setIsDetecting] = useState(false)
    const [draggedIndex, setDraggedIndex] = useState<number | null>(null)
    const [hoveredIndex, setHoveredIndex] = useState<number | null>(null)

    const videoRef = useRef<HTMLVideoElement>(null)
    const canvasRef = useRef<HTMLCanvasElement>(null)
    const overlayCanvasRef = useRef<HTMLCanvasElement>(null)
    const containerRef = useRef<HTMLDivElement>(null)
    const streamRef = useRef<MediaStream | null>(null)
    const poseLandmarkerRef = useRef<PoseLandmarker | null>(null)
    const capturedImageDataRef = useRef<ImageData | null>(null)
    // Ref to always have the latest landmarks during drag without re-creating handlers
    const landmarksRef = useRef<LandmarkPoint[] | null>(null)
    // Keep landmarksRef in sync with state
    useEffect(() => { landmarksRef.current = landmarks }, [landmarks])

    // ========== Initialize MediaPipe ==========
    useEffect(() => {
        let cancelled = false
        async function init() {
            try {
                const vision = await FilesetResolver.forVisionTasks(
                    'https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.18/wasm'
                )
                if (cancelled) return
                const landmarker = await PoseLandmarker.createFromOptions(vision, {
                    baseOptions: {
                        modelAssetPath:
                            'https://storage.googleapis.com/mediapipe-models/pose_landmarker/pose_landmarker_lite/float16/latest/pose_landmarker_lite.task',
                    },
                    runningMode: 'IMAGE',
                    numPoses: 1,
                })
                if (!cancelled) {
                    poseLandmarkerRef.current = landmarker
                    setMediapipeReady(true)
                }
            } catch (e: any) {
                if (!cancelled) setCameraError('Failed to load pose detection model: ' + e.message)
            }
        }
        init()
        return () => { cancelled = true }
    }, [])

    // ========== Camera ==========
    const startCamera = useCallback(async () => {
        setCameraError(null)
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' },
            })
            streamRef.current = stream
            if (videoRef.current) {
                videoRef.current.srcObject = stream
            }
            setMode('camera')
            setLandmarks(null)
            capturedImageDataRef.current = null
        } catch (e: any) {
            setCameraError('Camera access denied or unavailable: ' + e.message)
        }
    }, [])

    const stopCamera = useCallback(() => {
        if (streamRef.current) {
            streamRef.current.getTracks().forEach(t => t.stop())
            streamRef.current = null
        }
    }, [])

    useEffect(() => {
        startCamera()
        return () => stopCamera()
    }, [startCamera, stopCamera])

    // ========== Capture image + detect pose ==========
    const captureImage = useCallback(async () => {
        if (!videoRef.current || !canvasRef.current) return
        const video = videoRef.current
        const canvas = canvasRef.current
        canvas.width = video.videoWidth
        canvas.height = video.videoHeight
        const ctx = canvas.getContext('2d')!
        ctx.drawImage(video, 0, 0)
        capturedImageDataRef.current = ctx.getImageData(0, 0, canvas.width, canvas.height)
        setMode('captured')
        setLandmarks(null)

        if (poseLandmarkerRef.current) {
            setIsDetecting(true)
            try {
                const result = poseLandmarkerRef.current.detect(canvas)
                if (result.landmarks && result.landmarks.length > 0) {
                    const rawLandmarks = result.landmarks[0]
                    const detected = rawLandmarks.map((lm: NormalizedLandmark) => ({
                        x: lm.x,
                        y: lm.y,
                        z: lm.z,
                        visibility: lm.visibility,
                    }))
                    setLandmarks(detected)
                } else {
                    toast.info('No pose detected. You can still save the image, or recapture with better lighting.')
                }
            } catch {
                toast.error('Pose detection failed.')
            } finally {
                setIsDetecting(false)
            }
        }
    }, [])

    // ========== Draw overlay (landmarks + skeleton) ==========
    useEffect(() => {
        if (mode !== 'captured' || !overlayCanvasRef.current || !canvasRef.current) return
        const overlay = overlayCanvasRef.current
        const imageCanvas = canvasRef.current
        overlay.width = imageCanvas.width
        overlay.height = imageCanvas.height
        const ctx = overlay.getContext('2d')!
        ctx.clearRect(0, 0, overlay.width, overlay.height)

        if (!landmarks || landmarks.length === 0) return

        const w = overlay.width
        const h = overlay.height

        // Draw connections
        for (const [i, j] of POSE_CONNECTIONS) {
            const a = landmarks[i]
            const b = landmarks[j]
            if (!a || !b) continue
            const minVis = Math.min(a.visibility ?? 1, b.visibility ?? 1)
            if (minVis < 0.3) continue
            ctx.beginPath()
            ctx.moveTo(a.x * w, a.y * h)
            ctx.lineTo(b.x * w, b.y * h)
            ctx.strokeStyle = getConnectionColor(i, j)
            ctx.lineWidth = 2
            ctx.globalAlpha = 0.6
            ctx.stroke()
        }

        // Draw landmarks
        for (let i = 0; i < landmarks.length; i++) {
            const lm = landmarks[i]
            if ((lm.visibility ?? 1) < 0.3) continue
            const cx = lm.x * w
            const cy = lm.y * h
            const isDragged = draggedIndex === i
            const isHovered = hoveredIndex === i
            const radius = isDragged ? 8 : isHovered ? 7 : 5

            // Glow ring for hovered/dragged
            if (isDragged || isHovered) {
                ctx.beginPath()
                ctx.arc(cx, cy, radius + 3, 0, 2 * Math.PI)
                ctx.fillStyle = 'rgba(255,255,255,0.3)'
                ctx.fill()
            }

            ctx.beginPath()
            ctx.arc(cx, cy, radius, 0, 2 * Math.PI)
            ctx.fillStyle = isDragged ? '#fff' : getLandmarkColor(i)
            ctx.globalAlpha = isDragged ? 1 : 0.9
            ctx.fill()
            ctx.strokeStyle = isDragged ? getLandmarkColor(i) : '#fff'
            ctx.lineWidth = isDragged ? 2.5 : 1.5
            ctx.stroke()
        }
    }, [landmarks, mode, draggedIndex, hoveredIndex])

    // ========== Drag handling ==========
    // Convert mouse event to normalized 0..1 canvas coordinates
    const mouseToNorm = useCallback((e: React.MouseEvent<HTMLCanvasElement> | MouseEvent): { normX: number; normY: number } | null => {
        const overlay = overlayCanvasRef.current
        if (!overlay) return null
        const rect = overlay.getBoundingClientRect()
        // The canvas internal resolution vs CSS display size
        const scaleX = overlay.width / rect.width
        const scaleY = overlay.height / rect.height
        const px = (e.clientX - rect.left) * scaleX
        const py = (e.clientY - rect.top) * scaleY
        return {
            normX: px / overlay.width,
            normY: py / overlay.height,
        }
    }, [])

    const findClosestLandmark = useCallback((normX: number, normY: number): number => {
        const lms = landmarksRef.current
        if (!lms) return -1
        const threshold = 0.04
        let closest = -1
        let minDist = Infinity
        for (let i = 0; i < lms.length; i++) {
            const dx = lms[i].x - normX
            const dy = lms[i].y - normY
            const dist = Math.sqrt(dx * dx + dy * dy)
            if (dist < threshold && dist < minDist) {
                minDist = dist
                closest = i
            }
        }
        return closest
    }, [])

    const handleMouseDown = useCallback((e: React.MouseEvent<HTMLCanvasElement>) => {
        const coords = mouseToNorm(e)
        if (!coords) return
        const closest = findClosestLandmark(coords.normX, coords.normY)
        if (closest >= 0) {
            e.preventDefault()
            setDraggedIndex(closest)
        }
    }, [mouseToNorm, findClosestLandmark])

    const handleMouseMove = useCallback((e: React.MouseEvent<HTMLCanvasElement>) => {
        const coords = mouseToNorm(e)
        if (!coords) return

        if (draggedIndex !== null) {
            // Update landmark position
            setLandmarks(prev => {
                if (!prev) return prev
                const updated = [...prev]
                updated[draggedIndex] = {
                    ...updated[draggedIndex],
                    x: Math.max(0, Math.min(1, coords.normX)),
                    y: Math.max(0, Math.min(1, coords.normY)),
                }
                return updated
            })
        } else {
            // Show hover effect
            const closest = findClosestLandmark(coords.normX, coords.normY)
            setHoveredIndex(closest)
        }
    }, [draggedIndex, mouseToNorm, findClosestLandmark])

    const handleMouseUp = useCallback(() => {
        setDraggedIndex(null)
    }, [])

    // Global mouseup listener to handle release outside canvas
    useEffect(() => {
        if (draggedIndex === null) return
        const onUp = () => setDraggedIndex(null)
        window.addEventListener('mouseup', onUp)
        return () => window.removeEventListener('mouseup', onUp)
    }, [draggedIndex])

    // ========== Touch support ==========
    const touchIdRef = useRef<number | null>(null)

    const handleTouchStart = useCallback((e: React.TouchEvent<HTMLCanvasElement>) => {
        if (e.touches.length !== 1) return
        const touch = e.touches[0]
        touchIdRef.current = touch.identifier
        // Convert touch to mouse-like event for coordinate mapping
        const fakeEvent = { clientX: touch.clientX, clientY: touch.clientY } as React.MouseEvent<HTMLCanvasElement>
        const coords = mouseToNorm(fakeEvent)
        if (!coords) return
        const closest = findClosestLandmark(coords.normX, coords.normY)
        if (closest >= 0) {
            e.preventDefault()
            setDraggedIndex(closest)
        }
    }, [mouseToNorm, findClosestLandmark])

    const handleTouchMove = useCallback((e: React.TouchEvent<HTMLCanvasElement>) => {
        if (draggedIndex === null) return
        const touch = Array.from(e.touches).find(t => t.identifier === touchIdRef.current)
        if (!touch) return
        e.preventDefault()
        const fakeEvent = { clientX: touch.clientX, clientY: touch.clientY } as React.MouseEvent<HTMLCanvasElement>
        const coords = mouseToNorm(fakeEvent)
        if (!coords) return
        setLandmarks(prev => {
            if (!prev) return prev
            const updated = [...prev]
            updated[draggedIndex] = {
                ...updated[draggedIndex],
                x: Math.max(0, Math.min(1, coords.normX)),
                y: Math.max(0, Math.min(1, coords.normY)),
            }
            return updated
        })
    }, [draggedIndex, mouseToNorm])

    const handleTouchEnd = useCallback(() => {
        setDraggedIndex(null)
        touchIdRef.current = null
    }, [])

    // ========== Save ==========
    const saveImage = useCallback(() => {
        if (!canvasRef.current) return
        setMode('saving')

        const canvas = canvasRef.current
        const mergedCanvas = document.createElement('canvas')
        mergedCanvas.width = canvas.width
        mergedCanvas.height = canvas.height
        const mergedCtx = mergedCanvas.getContext('2d')!

        if (capturedImageDataRef.current) {
            mergedCtx.putImageData(capturedImageDataRef.current, 0, 0)
        }
        if (overlayCanvasRef.current) {
            mergedCtx.drawImage(overlayCanvasRef.current, 0, 0)
        }

        const base64 = mergedCanvas.toDataURL('image/jpeg', 0.85)
        const currentLandmarks = landmarksRef.current

        router.post(
            `/assessments/${assessmentId}/capture`,
            {
                view: selectedView,
                image: base64,
                landmarks: currentLandmarks?.map(l => ({ x: l.x, y: l.y, z: l.z, visibility: l.visibility })) || [],
            },
            {
                onSuccess: () => {
                    toast.success('Image and landmarks saved.')
                },
                onError: (err: any) => {
                    setMode('captured')
                    toast.error('Failed to save: ' + (err?.message || 'Unknown error'))
                },
            }
        )
    }, [assessmentId, selectedView])

    const recapture = useCallback(() => {
        setMode('camera')
        setLandmarks(null)
        capturedImageDataRef.current = null
    }, [])

    const availableViews = ['front', 'back', 'right_side', 'left_side'].map(v => ({
        value: v,
        label: v.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()),
        disabled: existingViews.includes(v),
    }))

    return (
        <Card>
            <CardContent className="p-4 space-y-4">
                {/* Header */}
                <div className="flex items-center justify-between flex-wrap gap-2">
                    <div className="flex items-center gap-3">
                        <Camera className="h-5 w-5 text-primary" />
                        <span className="font-semibold">Pose Landmark Capture</span>
                        {!mediapipeReady && !cameraError && (
                            <Badge variant="outline" className="gap-1">
                                <Loader2 className="h-3 w-3 animate-spin" /> Loading model...
                            </Badge>
                        )}
                        {mediapipeReady && <Badge variant="default">Ready</Badge>}
                    </div>
                    <Select value={selectedView} onValueChange={setSelectedView}>
                        <SelectTrigger className="w-40">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            {availableViews.map(v => (
                                <SelectItem key={v.value} value={v.value} disabled={v.disabled}>
                                    {v.label} {v.disabled ? '(captured)' : ''}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                </div>

                {/* Camera error */}
                {cameraError && (
                    <div className="rounded-md bg-red-50 p-3 text-sm text-red-600 flex items-center gap-2">
                        <X className="h-4 w-4" />
                        {cameraError}
                        <Button variant="outline" size="sm" onClick={startCamera}>
                            Retry
                        </Button>
                    </div>
                )}

                {/* Capture area */}
                <div className="relative bg-black rounded-lg overflow-hidden" ref={containerRef}>
                    {/* Live video */}
                    <video
                        ref={videoRef}
                        autoPlay
                        playsInline
                        muted
                        className={`w-full max-h-[480px] object-contain ${mode === 'camera' ? 'block' : 'hidden'}`}
                    />

                    {/* Captured image canvas */}
                    <canvas
                        ref={canvasRef}
                        className={`w-full max-h-[480px] object-contain ${mode === 'captured' ? 'block' : 'hidden'}`}
                    />

                    {/* Landmark overlay canvas */}
                    {mode === 'captured' && (
                        <canvas
                            ref={overlayCanvasRef}
                            className={`absolute inset-0 w-full h-full ${draggedIndex !== null ? 'cursor-grabbing' : hoveredIndex >= 0 ? 'cursor-grab' : 'cursor-crosshair'}`}
                            style={{ touchAction: 'none' }}
                            onMouseDown={handleMouseDown}
                            onMouseMove={handleMouseMove}
                            onMouseUp={handleMouseUp}
                            onMouseLeave={handleMouseUp}
                            onTouchStart={handleTouchStart}
                            onTouchMove={handleTouchMove}
                            onTouchEnd={handleTouchEnd}
                        />
                    )}

                    {/* Detecting overlay */}
                    {isDetecting && (
                        <div className="absolute inset-0 flex items-center justify-center bg-black/40">
                            <div className="flex items-center gap-2 text-white">
                                <Loader2 className="h-5 w-5 animate-spin" />
                                Detecting pose...
                            </div>
                        </div>
                    )}

                    {/* No landmarks hint */}
                    {mode === 'captured' && !isDetecting && (!landmarks || landmarks.length === 0) && (
                        <div className="absolute bottom-3 left-3 right-3 rounded-md bg-black/60 px-3 py-2 text-xs text-white">
                            No pose detected. You can still save the image, or recapture with better lighting/positioning.
                        </div>
                    )}

                    {/* Hovered landmark label */}
                    {mode === 'captured' && hoveredIndex !== null && draggedIndex === null && landmarks && (
                        <div className="absolute top-3 left-3 rounded-md bg-black/70 px-2 py-1 text-xs text-white">
                            {LANDMARK_LABELS[hoveredIndex] || `#${hoveredIndex}`}
                        </div>
                    )}

                    {/* Drag hint */}
                    {mode === 'captured' && landmarks && landmarks.length > 0 && draggedIndex === null && (
                        <div className="absolute top-3 right-3 rounded-md bg-black/60 px-3 py-1.5 text-xs text-white">
                            Drag landmarks to adjust
                        </div>
                    )}
                </div>

                {/* Action buttons */}
                <div className="flex items-center gap-2 flex-wrap">
                    {mode === 'camera' && (
                        <Button onClick={captureImage} disabled={!mediapipeReady}>
                            <Camera className="mr-2 h-4 w-4" />
                            Capture
                        </Button>
                    )}
                    {mode === 'captured' && (
                        <>
                            <Button onClick={saveImage} disabled={isDetecting}>
                                <Save className="mr-2 h-4 w-4" />
                                Save
                            </Button>
                            <Button variant="outline" onClick={recapture}>
                                <RefreshCw className="mr-2 h-4 w-4" />
                                Recapture
                            </Button>
                        </>
                    )}
                    {mode === 'saving' && (
                        <Button disabled>
                            <Loader2 className="mr-2 h-4 w-4 animate-spin" />
                            Saving...
                        </Button>
                    )}
                </div>
            </CardContent>
        </Card>
    )
}
