import { Head, Link } from '@inertiajs/react'
import AppLayout from '@/layouts/app-layout'
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { BreadcrumbItem } from '@/types'
import { ArrowLeft, Target, Dumbbell, CalendarDays, Star, Info, TrendingUp, Activity, Camera } from 'lucide-react'
import PoseLandmarkCapture from '@/components/pose-landmark-capture'

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Patients', href: '/patients' },
    { title: 'Assessment', href: '#' },
]

const viewLabels: Record<string, string> = {
    front: 'Front View (A)', back: 'Back View (B)',
    right_side: 'Right Side (C)', left_side: 'Left Side (D)',
}

// User-facing alignment wording. The backend sends `alignment_label`, but the
// map keeps stored measurements (which only carry the band) rendering the same
// wording as interpreted ones.
const alignmentLabels: Record<string, string> = {
    normal: 'On Point',
    mild: 'Slightly Off Point',
    moderate: 'Off Point',
    severe: 'Far Off Point',
    review: 'Check Measurement',
}

const alignmentStyles: Record<string, string> = {
    normal: 'text-green-600 bg-green-50',
    mild: 'text-yellow-600 bg-yellow-50',
    moderate: 'text-orange-600 bg-orange-50',
    severe: 'text-red-600 bg-red-50',
    review: 'text-slate-500 bg-slate-100',
}

const alignmentStatusOf = (m: any) => {
    if (m?.review_required) return 'review'
    const status = (m?.alignment_status || m?.severity || 'normal').toLowerCase()
    return alignmentLabels[status] ? status : 'normal'
}

const alignmentLabelOf = (m: any) =>
    m?.alignment_label || alignmentLabels[alignmentStatusOf(m)] || 'On Point'

const humanize = (value?: string) =>
    (value || '').replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase())

export default function AssessmentShow({ assessment, viewMeasurements, classification }: any) {
    const patient = assessment.patient
    // The classification summary carries the user-facing pattern wording, so its
    // enriched rows are preferred over the raw clinical keys on the model.
    const classificationInfo = classification || {}
    const classifications = classificationInfo.classifications?.length
        ? classificationInfo.classifications
        : (assessment.classifications || [])
    const exercises = assessment.exercise_recommendations || []
    const massages = assessment.massage_recommendations || []
    const programs = assessment.weekly_programs || []
    const score = assessment.overall_score || 0
    const scoreColor = score >= 80 ? 'text-green-600' : score >= 60 ? 'text-orange-600' : 'text-red-600'
    const scoreBg = score >= 80 ? 'bg-green-100' : score >= 60 ? 'bg-orange-100' : 'bg-red-100'

    const dayLabels: Record<string, string> = {
        monday: 'Mon', tuesday: 'Tue', wednesday: 'Wed',
        thursday: 'Thu', friday: 'Fri', saturday: 'Sat', sunday: 'Sun',
    }

    // An unclassified posture must never be shown as a diagnosis.
    const isUnclassified = classificationInfo.review_required === true
    const classificationDisplay = classificationInfo.display
        ? (isUnclassified ? classificationInfo.display : humanize(classificationInfo.display))
        : 'Not yet classified'

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Assessment ${assessment.time_mark}`} />
            <div className="flex flex-col gap-6 p-4">
                <div className="flex items-center gap-4">
                    <Link href={`/patients/${patient?.id}`}>
                        <Button variant="ghost" size="sm">
                            <ArrowLeft className="mr-2 h-4 w-4" />
                            Back to Patient
                        </Button>
                    </Link>
                    <h1 className="text-xl font-bold">
                        {patient?.name} — {assessment.time_mark}
                    </h1>
                </div>

                {/* Score & Meta */}
                <div className="grid gap-4 md:grid-cols-3">
                    <Card>
                        <CardContent className="flex items-center gap-4 py-4">
                            <div className={`flex h-16 w-16 items-center justify-center rounded-full ${scoreBg}`}>
                                <span className={`text-2xl font-bold ${scoreColor}`}>{score}</span>
                            </div>
                            <div>
                                <p className="text-sm text-muted-foreground">Posture Score</p>
                                <Badge>{assessment.overall_status || 'N/A'}</Badge>
                            </div>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardContent className="flex items-center gap-4 py-4">
                            <Target className="h-10 w-10 text-primary" />
                            <div>
                                <p className="text-sm text-muted-foreground">Classification</p>
                                <p className={`font-semibold ${isUnclassified ? 'text-orange-600' : ''}`}>
                                    {classificationDisplay}
                                </p>
                                {(classificationInfo.confidence_label || classificationInfo.confidence_level) && (
                                    <p className="text-xs text-muted-foreground">
                                        {classificationInfo.confidence_label || `${classificationInfo.confidence_level} confidence`}
                                    </p>
                                )}
                            </div>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardContent className="flex items-center gap-4 py-4">
                            <CalendarDays className="h-10 w-10 text-primary" />
                            <div>
                                <p className="text-sm text-muted-foreground">Date</p>
                                <p className="font-semibold">{assessment.assessment_date}</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <Tabs defaultValue="measurements">
                    <TabsList className="flex-wrap">
                        <TabsTrigger value="measurements"><Activity className="mr-2 h-4 w-4" />Measurements</TabsTrigger>
                        <TabsTrigger value="classification"><Target className="mr-2 h-4 w-4" />Classification</TabsTrigger>
                        <TabsTrigger value="exercises"><Dumbbell className="mr-2 h-4 w-4" />Exercises</TabsTrigger>
                        <TabsTrigger value="massage"><Star className="mr-2 h-4 w-4" />Massage</TabsTrigger>
                        <TabsTrigger value="program"><CalendarDays className="mr-2 h-4 w-4" />Weekly Program</TabsTrigger>
                        <TabsTrigger value="capture"><Camera className="mr-2 h-4 w-4" />Capture</TabsTrigger>
                    </TabsList>

                    <TabsContent value="measurements" className="mt-4">
                        <div className="grid gap-4 md:grid-cols-2">
                            {Object.entries(viewMeasurements).map(([view, measurements]: [string, any]) => (
                                <Card key={view}>
                                    <CardHeader>
                                        <CardTitle className="text-base">{viewLabels[view] || view}</CardTitle>
                                    </CardHeader>
                                    <CardContent className="space-y-2">
                                        {(measurements || []).length === 0 ? (
                                            <p className="text-sm text-muted-foreground">No measurements</p>
                                        ) : (
                                            (measurements || []).map((m: any) => {
                                                const status = alignmentStatusOf(m)
                                                const note = m.position_note || (status === 'review' ? m.interpretation : '')
                                                return (
                                                    <div key={m.id} className="border-b pb-1">
                                                        <div className="flex items-center justify-between">
                                                            <div className="flex items-center gap-3">
                                                                <span className="text-xs text-muted-foreground w-6">{m.section}</span>
                                                                <span className="text-sm">{m.label}</span>
                                                            </div>
                                                            <div className="flex items-center gap-2">
                                                                <span className="text-sm font-medium">{m.value?.toFixed(1)}°</span>
                                                                {status !== 'normal' && (
                                                                    <Badge className={`text-[10px] px-1.5 ${alignmentStyles[status]}`}>
                                                                        {alignmentLabelOf(m)}
                                                                    </Badge>
                                                                )}
                                                            </div>
                                                        </div>
                                                        {note && (
                                                            <p className="pl-9 text-xs text-muted-foreground">{note}</p>
                                                        )}
                                                    </div>
                                                )
                                            })
                                        )}
                                    </CardContent>
                                </Card>
                            ))}
                        </div>
                    </TabsContent>

                    <TabsContent value="classification" className="mt-4 space-y-4">
                        {isUnclassified && (
                            <Card className="border-orange-200 bg-orange-50/50">
                                <CardHeader>
                                    <CardTitle className="flex items-center gap-2 text-base text-orange-700">
                                        <Info className="h-4 w-4" />
                                        {classificationDisplay}
                                    </CardTitle>
                                </CardHeader>
                                <CardContent className="space-y-2 text-sm">
                                    <div className="grid gap-2 sm:grid-cols-2">
                                        <div className="flex justify-between gap-2">
                                            <span className="text-muted-foreground">Review Status</span>
                                            <span className="font-medium">Measurement Review Required</span>
                                        </div>
                                        {classificationInfo.suspected_pattern && (
                                            <div className="flex justify-between gap-2">
                                                <span className="text-muted-foreground">Main Finding</span>
                                                <span className="font-medium">
                                                    {classificationInfo.suspected_pattern_label
                                                        || `Possible ${humanize(classificationInfo.suspected_pattern)} Pattern`}
                                                </span>
                                            </div>
                                        )}
                                        {classificationInfo.secondary_pattern && (
                                            <div className="flex justify-between gap-2">
                                                <span className="text-muted-foreground">Secondary Finding</span>
                                                <span className="font-medium">
                                                    {classificationInfo.secondary_pattern_label
                                                        || humanize(classificationInfo.secondary_pattern)}
                                                </span>
                                            </div>
                                        )}
                                        <div className="flex justify-between gap-2">
                                            <span className="text-muted-foreground">Asymmetry</span>
                                            <span className="font-medium">{classificationInfo.asymmetry_flag ? 'Detected' : 'Not detected'}</span>
                                        </div>
                                        {(classificationInfo.confidence_label || classificationInfo.confidence_level) && (
                                            <div className="flex justify-between gap-2">
                                                <span className="text-muted-foreground">Confidence</span>
                                                <span className="font-medium">
                                                    {classificationInfo.confidence_label || classificationInfo.confidence_level}
                                                </span>
                                            </div>
                                        )}
                                    </div>
                                    <p className="text-muted-foreground">
                                        The measurements currently available are not sufficient to confirm a posture type.
                                        No Swayback, Lordosis or Kyphosis diagnosis has been made.
                                    </p>
                                </CardContent>
                            </Card>
                        )}

                        {classifications.length === 0 ? (
                            <Card><CardContent className="py-8 text-center text-muted-foreground">No classifications</CardContent></Card>
                        ) : (
                            classifications.map((c: any) => (
                                <Card key={c.id}>
                                    <CardContent className="flex items-center gap-4 py-4">
                                        <Badge variant={c.classification_type === 'primary' ? 'default' : 'secondary'}>
                                            {c.classification_type}
                                        </Badge>
                                        <div className="flex-1">
                                            <p className="font-semibold">
                                                {c.classification_label || humanize(c.classification_name)}
                                            </p>
                                            {c.description && <p className="text-sm text-muted-foreground">{c.description}</p>}
                                        </div>
                                        <div className="text-right">
                                            {(c.alignment_status || c.severity) && (
                                                <Badge className={alignmentStyles[alignmentStatusOf(c)]}>
                                                    {alignmentLabelOf(c)}
                                                </Badge>
                                            )}
                                            {c.confidence && (
                                                <p className="text-xs text-muted-foreground mt-1">{c.confidence}% confidence</p>
                                            )}
                                        </div>
                                    </CardContent>
                                </Card>
                            ))
                        )}

                        {assessment.clinical_summary && (
                            <Card>
                                <CardHeader>
                                    <CardTitle className="flex items-center gap-2 text-base">
                                        <Info className="h-4 w-4" />
                                        Clinical Interpretation
                                    </CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <p className="text-sm leading-relaxed">{assessment.clinical_summary}</p>
                                </CardContent>
                            </Card>
                        )}
                    </TabsContent>

                    <TabsContent value="exercises" className="mt-4 space-y-3">
                        {exercises.length === 0 ? (
                            <Card><CardContent className="py-8 text-center text-muted-foreground">No recommendations yet</CardContent></Card>
                        ) : (
                            exercises.map((e: any) => (
                                <Card key={e.id}>
                                    <CardContent className="flex items-start gap-4 py-4">
                                        <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100">
                                            <Dumbbell className="h-5 w-5 text-blue-600" />
                                        </div>
                                        <div className="flex-1">
                                            <p className="font-semibold">{e.exercise_name}</p>
                                            <p className="text-sm text-muted-foreground">
                                                {[e.program_level, e.difficulty, e.estimated_duration_minutes ? `${e.estimated_duration_minutes}min` : null]
                                                    .filter(Boolean).join(' · ')}
                                            </p>
                                            {e.instructions && (
                                                <p className="mt-2 text-sm text-muted-foreground">{e.instructions}</p>
                                            )}
                                        </div>
                                        {e.progression_stage && (
                                            <Badge variant="outline">Stage {e.progression_stage}</Badge>
                                        )}
                                    </CardContent>
                                </Card>
                            ))
                        )}
                    </TabsContent>

                    <TabsContent value="massage" className="mt-4 space-y-3">
                        {massages.length === 0 ? (
                            <Card><CardContent className="py-8 text-center text-muted-foreground">No recommendations yet</CardContent></Card>
                        ) : (
                            massages.map((m: any) => (
                                <Card key={m.id}>
                                    <CardContent className="flex items-center gap-4 py-4">
                                        <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100">
                                            <Star className="h-5 w-5 text-purple-600" />
                                        </div>
                                        <div className="flex-1">
                                            <p className="font-semibold">{m.body_area}</p>
                                            <p className="text-sm text-muted-foreground">
                                                {m.duration_minutes ? `${m.duration_minutes}min` : null} · {m.frequency}
                                            </p>
                                        </div>
                                        <div className="flex">
                                            {Array.from({ length: m.priority_stars || 1 }, (_, i) => (
                                                <Star key={i} className="h-4 w-4 fill-yellow-400 text-yellow-400" />
                                            ))}
                                        </div>
                                    </CardContent>
                                </Card>
                            ))
                        )}
                    </TabsContent>

                    <TabsContent value="program" className="mt-4 space-y-3">
                        {programs.length === 0 ? (
                            <Card><CardContent className="py-8 text-center text-muted-foreground">No weekly program generated</CardContent></Card>
                        ) : (
                            <Card>
                                <CardHeader>
                                    <CardTitle className="text-base">Weekly Program</CardTitle>
                                </CardHeader>
                                <CardContent className="space-y-2">
                                    {programs.map((p: any) => (
                                        <div key={p.id} className="flex items-center gap-4 border-b pb-2">
                                            <span className="w-12 text-sm font-medium text-primary">
                                                {dayLabels[p.day_of_week] || p.day_of_week}
                                            </span>
                                            <div className="flex-1">
                                                <p className="text-sm font-medium">{p.activity_title || p.activity_type?.replace(/_/g, ' ')}</p>
                                                {p.activity_details && (
                                                    <p className="text-xs text-muted-foreground">{p.activity_details}</p>
                                                )}
                                            </div>
                                            {p.duration_minutes > 0 && (
                                                <Badge variant="outline">{p.duration_minutes}min</Badge>
                                            )}
                                        </div>
                                    ))}
                                </CardContent>
                            </Card>
                        )}
                    </TabsContent>

                    <TabsContent value="capture" className="mt-4">
                        <PoseLandmarkCapture
                            assessmentId={assessment.id}
                            existingViews={(assessment.images || []).map((img: any) => img.view)}
                        />
                    </TabsContent>
                </Tabs>
            </div>
        </AppLayout>
    )
}
