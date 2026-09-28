import AppLayout from '@/layouts/app-layout';
import { Head, Link } from '@inertiajs/react';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { type BreadcrumbItem } from '@/types';
import {
    ArrowLeft,
    Activity,
    Info,
    Dumbbell,
    Star,
    CalendarDays,
    ImageIcon,
    Target,
    FileText,
    Download,
} from 'lucide-react';

type Props = {
    patient: { id: number; name: string };
    assessment: any;
    viewMeasurements: Record<string, any[]>;
    classification?: any;
};

const viewLabels: Record<string, string> = {
    front: 'Front (A)',
    back: 'Back (B)',
    right_side: 'Right (C)',
    left_side: 'Left (D)',
};

const alignmentLabels: Record<string, string> = {
    normal: 'Aligned',
    mild: 'Slightly Misaligned',
    moderate: 'Misaligned',
    severe: 'Clearly Misaligned',
    review: 'Check Measurement',
};

const alignmentStyles: Record<string, string> = {
    normal: 'text-green-600 bg-green-50',
    mild: 'text-yellow-600 bg-yellow-50',
    moderate: 'text-orange-600 bg-orange-50',
    severe: 'text-red-600 bg-red-50',
    review: 'text-slate-500 bg-slate-100',
};

const alignmentStatusOf = (m: any) => {
    if (m?.review_required) return 'review';
    const status = (m?.alignment_status || m?.severity || 'normal').toLowerCase();
    return alignmentLabels[status] ? status : 'normal';
};

const alignmentLabelOf = (m: any) =>
    m?.alignment_label || alignmentLabels[alignmentStatusOf(m)] || 'Aligned';

const humanize = (value?: string) =>
    (value || '').replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase());

const fmtAngle = (v: any) => {
    if (v == null || v === '') return '—';
    const n = typeof v === 'number' ? v : parseFloat(v);
    return isNaN(n) ? String(v) : n.toFixed(1);
};

const dayLabels: Record<string, string> = {
    monday: 'Mon', tuesday: 'Tue', wednesday: 'Wed',
    thursday: 'Thu', friday: 'Fri', saturday: 'Sat', sunday: 'Sun',
};

/**
 * Renders a captured posture image with its muscle highlight zones overlaid.
 * Highlight rectangles are stored in normalized (0..1) coordinates relative to
 * the drawing box, so they map directly to percentage positions.
 */
const HighlightedImageView = ({ img, storageUrl }: { img: any; storageUrl: (p: string) => string }) => {
    const highlights: any[] = Array.isArray(img.highlights) ? img.highlights : [];
    return (
        <div className="relative h-48 w-full overflow-hidden rounded-md border">
            <img
                src={storageUrl(img.image_path)}
                alt={viewLabels[img.view] || img.view}
                className="absolute inset-0 h-full w-full object-cover"
            />
            {highlights.map((h: any, i: number) => {
                const left = Number(h.left) * 100;
                const top = Number(h.top) * 100;
                const width = Number(h.width) * 100;
                const height = Number(h.height) * 100;
                const weak = h.type === 'weak';
                return (
                    <div
                        key={i}
                        className={`absolute pointer-events-none rounded-md ${weak ? 'bg-green-500/50' : 'bg-red-500/50'}`}
                        style={{
                            left: `${left}%`,
                            top: `${top}%`,
                            width: `${width}%`,
                            height: `${height}%`,
                            boxShadow: weak ? 'inset 0 0 0 2px rgba(34,197,94,0.8)' : 'inset 0 0 0 2px rgba(239,68,68,0.8)',
                        }}
                    />
                );
            })}
        </div>
    );
};

export default function AssessmentDetail({ patient, assessment, viewMeasurements, classification }: Props) {
    const storageUrl = (path: string) => '/storage/' + path.replace(/^\//, '');
    const measurements = assessment.measurements || [];
    // The classification summary carries the user-facing pattern wording, so its
    // enriched rows are preferred over the raw clinical keys on the model.
    const classificationInfo = classification || {};
    const classifications = classificationInfo.classifications?.length
        ? classificationInfo.classifications
        : (assessment.classifications || []);
    const exercises = assessment.exercise_recommendations || [];
    const massages = assessment.massage_recommendations || [];
    const programs = assessment.weekly_programs || [];
    const images = assessment.images || [];
    const score = assessment.overall_score || 0;
    const scoreColor = score >= 80 ? 'text-green-600' : score >= 60 ? 'text-orange-600' : 'text-red-600';
    const scoreBg = score >= 80 ? 'bg-green-100' : score >= 60 ? 'bg-orange-100' : 'bg-red-100';

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Patients', href: '/patients' },
        { title: patient.name, href: `/patients/${patient.id}` },
        { title: assessment.time_mark, href: '#' },
    ];

    // An unclassified posture must never be shown as a diagnosis.
    const isUnclassified = classificationInfo.review_required === true;
    const classificationDisplay = classificationInfo.display
        ? (isUnclassified ? classificationInfo.display : humanize(classificationInfo.display))
        : 'Not yet classified';

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Assessment - ${patient.name}`} />

            <main className="flex-1 items-start gap-4 p-4 mt-4 sm:px-6 sm:py-0 md:gap-8">
                <div className="mb-4 flex items-center justify-between">
                    <Link href={`/patients/${patient.id}`} className="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-foreground">
                        <ArrowLeft className="h-4 w-4" />
                        Back to {patient.name}
                    </Link>
                    <div className="flex items-center gap-2">
                        <a
                            href={`/assessments/${assessment.id}/pdf`}
                            className="inline-flex items-center gap-1.5 rounded-md border px-3 py-1.5 text-sm font-medium hover:bg-muted/50"
                        >
                            <Download className="h-4 w-4" />
                            Download PDF
                        </a>
                        <a
                            href={`/assessments/${assessment.id}/word`}
                            className="inline-flex items-center gap-1.5 rounded-md border px-3 py-1.5 text-sm font-medium hover:bg-muted/50"
                        >
                            <FileText className="h-4 w-4" />
                            Download Word
                        </a>
                    </div>
                </div>

                {/* Score & meta */}
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
                                <p className="font-semibold">{assessment.assessment_date} · {assessment.time_mark}</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <div className="mt-4 space-y-5">
                    {/* Images */}
                    {images.length > 0 && (
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2 text-base">
                                    <ImageIcon className="h-4 w-4" /> Posture Images
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="grid grid-cols-2 gap-3 md:grid-cols-4">
                                    {images.map((img: any) => (
                                        <div key={img.id}>
                                            <HighlightedImageView img={img} storageUrl={storageUrl} />
                                            <p className="mt-1 text-xs text-muted-foreground">{viewLabels[img.view] || img.view}</p>
                                        </div>
                                    ))}
                                </div>
                                {images.some((img: any) => Array.isArray(img.highlights) && img.highlights.length) && (
                                    <div className="mt-3 flex items-center gap-4 text-xs">
                                        <span className="inline-flex items-center gap-1.5 text-muted-foreground">
                                            <span className="h-3 w-3 rounded-sm bg-red-500/60" /> Tight muscle
                                        </span>
                                        <span className="inline-flex items-center gap-1.5 text-muted-foreground">
                                            <span className="h-3 w-3 rounded-sm bg-green-500/60" /> Weak muscle
                                        </span>
                                    </div>
                                )}
                            </CardContent>
                        </Card>
                    )}

                    {/* Measurements */}
                    {measurements.length > 0 && (
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2 text-base">
                                    <Activity className="h-4 w-4" /> Measurements
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="grid gap-4 md:grid-cols-2">
                                    {Object.entries(viewMeasurements).map(([view, items]: [string, any[]]) => (
                                        <div key={view}>
                                            <p className="text-xs font-medium text-muted-foreground">{viewLabels[view] || view}</p>
                                            {items.length === 0 ? (
                                                <p className="text-xs text-muted-foreground">No measurements</p>
                                            ) : (
                                                <div className="mt-1 space-y-1">
                                                    {items.map((m: any) => {
                                                        const status = alignmentStatusOf(m);
                                                        const note = m.position_note || (status === 'review' ? m.interpretation : '');
                                                        return (
                                                            <div key={m.id} className="border-b pb-1 text-sm">
                                                                <div className="flex items-center justify-between">
                                                                    <span className="text-xs text-muted-foreground w-6">{m.section}</span>
                                                                    <span className="flex-1">{m.label}</span>
                                                                    <span className="font-medium">{fmtAngle(m.value)}°</span>
                                                                    {status !== 'normal' && (
                                                                        <Badge className={`ml-1 text-[10px] px-1.5 ${alignmentStyles[status]}`}>
                                                                            {alignmentLabelOf(m)}
                                                                        </Badge>
                                                                    )}
                                                                </div>
                                                                {note && (
                                                                    <p className="pl-6 text-xs text-muted-foreground">{note}</p>
                                                                )}
                                                            </div>
                                                        );
                                                    })}
                                                </div>
                                            )}
                                        </div>
                                    ))}
                                </div>
                            </CardContent>
                        </Card>
                    )}

                    {/* Classification */}
                    {(classifications.length > 0 || isUnclassified) && (
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2 text-base">
                                    <Info className="h-4 w-4" /> Classification
                                </CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-2">
                                {isUnclassified && (
                                    <div className="rounded-md border border-orange-200 bg-orange-50/50 p-3 text-sm">
                                        <p className="font-medium text-orange-700">{classificationDisplay}</p>
                                        <div className="mt-2 space-y-1">
                                            {classificationInfo.suspected_pattern && (
                                                <p>
                                                    <span className="text-muted-foreground">Main finding:</span>{' '}
                                                    {classificationInfo.suspected_pattern_label
                                                        || `Possible ${humanize(classificationInfo.suspected_pattern)} pattern`}
                                                </p>
                                            )}
                                            {classificationInfo.secondary_pattern && (
                                                <p>
                                                    <span className="text-muted-foreground">Secondary finding:</span>{' '}
                                                    {classificationInfo.secondary_pattern_label
                                                        || humanize(classificationInfo.secondary_pattern)}
                                                </p>
                                            )}
                                            <p><span className="text-muted-foreground">Asymmetry:</span> {classificationInfo.asymmetry_flag ? 'Detected' : 'Not detected'}</p>
                                            {(classificationInfo.confidence_label || classificationInfo.confidence_level) && (
                                                <p>
                                                    <span className="text-muted-foreground">Confidence:</span>{' '}
                                                    {classificationInfo.confidence_label || classificationInfo.confidence_level}
                                                </p>
                                            )}
                                        </div>
                                        <p className="mt-2 text-muted-foreground">
                                            The measurements currently available are not sufficient to confirm a posture type.
                                            No Swayback, Lordosis or Kyphosis diagnosis has been made.
                                        </p>
                                    </div>
                                )}
                                {classifications.map((c: any) => (
                                    <div key={c.id} className="flex items-center gap-3 rounded-md border p-2 text-sm">
                                        <Badge variant={c.classification_type === 'primary' ? 'default' : 'secondary'} className="text-[10px]">
                                            {c.classification_type}
                                        </Badge>
                                        <span className="flex-1 font-medium">
                                            {c.classification_label || humanize(c.classification_name)}
                                        </span>
                                        {(c.alignment_status || c.severity) && (
                                            <Badge className={alignmentStyles[alignmentStatusOf(c)]}>
                                                {alignmentLabelOf(c)}
                                            </Badge>
                                        )}
                                    </div>
                                ))}
                            </CardContent>
                        </Card>
                    )}

                    {/* Clinical summary */}
                    {assessment.clinical_summary && (
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2 text-base">
                                    <Info className="h-4 w-4" /> Clinical Interpretation
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p className="text-sm text-muted-foreground">{assessment.clinical_summary}</p>
                            </CardContent>
                        </Card>
                    )}

                    {/* Exercises */}
                    {exercises.length > 0 && (
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2 text-base">
                                    <Dumbbell className="h-4 w-4" /> Exercises ({exercises.length})
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="space-y-2">
                                    {exercises.map((e: any) => (
                                        <div key={e.id} className="rounded-md border p-2 text-sm">
                                            <div className="flex items-center justify-between">
                                                <span className="font-medium">{e.exercise_name}</span>
                                                <span className="text-xs text-muted-foreground">
                                                    {[e.program_level, e.difficulty, e.estimated_duration_minutes ? `${e.estimated_duration_minutes}min` : null].filter(Boolean).join(' · ')}
                                                </span>
                                            </div>
                                            {e.instructions && <p className="mt-1 text-xs text-muted-foreground">{e.instructions}</p>}
                                        </div>
                                    ))}
                                </div>
                            </CardContent>
                        </Card>
                    )}

                    {/* Massage */}
                    {massages.length > 0 && (
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2 text-base">
                                    <Star className="h-4 w-4" /> Massage ({massages.length})
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="space-y-1">
                                    {massages.map((m: any) => (
                                        <div key={m.id} className="flex items-center gap-3 text-sm">
                                            <span className="flex-1">{m.body_area}</span>
                                            <span className="text-xs text-muted-foreground">
                                                {m.duration_minutes ? `${m.duration_minutes}min` : null} · {m.frequency}
                                            </span>
                                            <span className="flex">
                                                {Array.from({ length: m.priority_stars || 1 }, (_, i) => (
                                                    <Star key={i} className="h-3 w-3 fill-yellow-400 text-yellow-400" />
                                                ))}
                                            </span>
                                        </div>
                                    ))}
                                </div>
                            </CardContent>
                        </Card>
                    )}

                    {/* Weekly program */}
                    {programs.length > 0 && (
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2 text-base">
                                    <CalendarDays className="h-4 w-4" /> Weekly Program
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="space-y-1">
                                    {programs.map((p: any) => (
                                        <div key={p.id} className="flex items-center gap-3 text-sm">
                                            <span className="w-10 font-medium text-primary">{dayLabels[p.day_of_week] || p.day_of_week}</span>
                                            <span className="flex-1">{p.activity_title || p.activity_type?.replace(/_/g, ' ')}</span>
                                            {p.duration_minutes > 0 && <Badge variant="outline">{p.duration_minutes}min</Badge>}
                                        </div>
                                    ))}
                                </div>
                            </CardContent>
                        </Card>
                    )}
                </div>
            </main>
        </AppLayout>
    );
}
