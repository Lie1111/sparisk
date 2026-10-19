import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Tooltip, TooltipContent, TooltipTrigger } from '@/components/ui/tooltip';
import { useInitials } from '@/hooks/use-initials';
import AppLayout from '@/layouts/app-layout';
import { cn } from '@/lib/utils';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import {
    ArrowRight,
    ArrowUpRight,
    ChartLine,
    CircleAlert,
    ClipboardCheck,
    Crosshair,
    Dumbbell,
    Gauge,
    Minus,
    Ruler,
    Users,
    type LucideIcon,
} from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface TrendPoint {
    month: string;
    label: string;
    assessments: number;
    average_score: number | null;
}

interface StatusBucket {
    key: string;
    label: string;
    tone: string;
    count: number;
    percentage: number;
}

interface PatientRef {
    id: number;
    name: string;
    photo: string | null;
}

interface RecentAssessment {
    id: number;
    patient: PatientRef | null;
    date: string | null;
    score: number | null;
    status: string | null;
    status_label: string;
    status_tone: string;
    pattern: string | null;
    needs_review: boolean;
    url: string;
}

interface AttentionItem {
    patient: PatientRef & { age: number | null };
    score: number | null;
    date: string | null;
    reasons: { label: string; tone: string }[];
    url: string;
}

interface ProblemArea {
    area: string;
    count: number;
    severity: string | null;
    tone: string;
    percentage: number;
}

interface DashboardProps {
    stats: {
        patients: { total: number; new_this_month: number };
        assessments: {
            total: number;
            this_month: number;
            average_score: number;
            average_score_this_month: number;
            score_delta: number | null;
        };
        review: { outstanding: number; measurements: number };
        library: { interventions: number };
    };
    trend: TrendPoint[];
    status_mix: { total: number; buckets: StatusBucket[] };
    recent_assessments: RecentAssessment[];
    needs_attention: AttentionItem[];
    problem_areas: ProblemArea[];
}

const TONES: Record<string, { fill: string; text: string; chip: string; dot: string }> = {
    emerald: {
        fill: 'bg-emerald-500',
        text: 'text-emerald-600 dark:text-emerald-400',
        chip: 'border-emerald-500/30 bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
        dot: 'bg-emerald-500',
    },
    amber: {
        fill: 'bg-amber-500',
        text: 'text-amber-600 dark:text-amber-400',
        chip: 'border-amber-500/30 bg-amber-500/10 text-amber-700 dark:text-amber-400',
        dot: 'bg-amber-500',
    },
    rose: {
        fill: 'bg-rose-500',
        text: 'text-rose-600 dark:text-rose-400',
        chip: 'border-rose-500/30 bg-rose-500/10 text-rose-700 dark:text-rose-400',
        dot: 'bg-rose-500',
    },
    sky: {
        fill: 'bg-sky-500',
        text: 'text-sky-600 dark:text-sky-400',
        chip: 'border-sky-500/30 bg-sky-500/10 text-sky-700 dark:text-sky-400',
        dot: 'bg-sky-500',
    },
    zinc: {
        fill: 'bg-zinc-400 dark:bg-zinc-500',
        text: 'text-muted-foreground',
        chip: 'border-border bg-muted text-muted-foreground',
        dot: 'bg-zinc-400 dark:bg-zinc-500',
    },
};

const tone = (key: string | null | undefined) => TONES[key ?? 'zinc'] ?? TONES.zinc;

const scoreTone = (score: number | null) => {
    if (score === null) return 'zinc';
    if (score >= 80) return 'emerald';
    if (score >= 60) return 'amber';
    return 'rose';
};

const formatDate = (value: string | null) => {
    if (!value) return 'No date';

    return new Intl.DateTimeFormat('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(value));
};

const greetingFor = (hour: number) => (hour < 12 ? 'Good morning' : hour < 18 ? 'Good afternoon' : 'Good evening');

function EmptyState({
    icon: Icon,
    title,
    description,
    href,
    action,
}: {
    icon: LucideIcon;
    title: string;
    description: string;
    href?: string;
    action?: string;
}) {
    return (
        <div className="flex flex-col items-center justify-center gap-2 rounded-lg border border-dashed px-6 py-10 text-center">
            <Icon className="text-muted-foreground size-6" />
            <p className="text-sm font-medium">{title}</p>
            <p className="text-muted-foreground max-w-sm text-xs">{description}</p>
            {href && action && (
                <Button asChild variant="outline" size="sm" className="mt-2">
                    <Link href={href}>{action}</Link>
                </Button>
            )}
        </div>
    );
}

function StatCard({
    icon: Icon,
    label,
    value,
    suffix,
    hint,
    chip,
    footer,
}: {
    icon: LucideIcon;
    label: string;
    value: string | number;
    suffix?: string;
    hint?: string;
    chip?: { label: string; tone: string; icon?: LucideIcon };
    footer?: React.ReactNode;
}) {
    return (
        <Card className="gap-4 py-5">
            <CardContent className="flex flex-col gap-4 px-5">
                <div className="flex items-start justify-between">
                    <span className="bg-muted text-muted-foreground flex size-9 items-center justify-center rounded-lg">
                        <Icon className="size-4" />
                    </span>
                    {chip && (
                        <span
                            className={cn('inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-xs font-medium', tone(chip.tone).chip)}
                        >
                            {chip.icon && <chip.icon className="size-3" />}
                            {chip.label}
                        </span>
                    )}
                </div>
                <div>
                    <p className="text-muted-foreground text-xs font-medium tracking-wide uppercase">{label}</p>
                    <p className="mt-1 flex items-baseline gap-1 text-3xl font-semibold tracking-tight tabular-nums">
                        {value}
                        {suffix && <span className="text-muted-foreground text-base font-normal">{suffix}</span>}
                    </p>
                    {hint && <p className="text-muted-foreground mt-1 text-xs">{hint}</p>}
                </div>
                {footer}
            </CardContent>
        </Card>
    );
}

function TrendChart({ trend }: { trend: TrendPoint[] }) {
    const peak = Math.max(...trend.map((point) => point.assessments), 1);
    const withScores = trend.map((point, index) => ({ ...point, index })).filter((point) => point.average_score !== null);
    const columns = trend.length;

    const points = withScores.map((point) => `${point.index + 0.5},${100 - (point.average_score as number)}`).join(' ');

    return (
        <div className="flex flex-col gap-3">
            <div className="text-muted-foreground flex items-center justify-between text-xs">
                <span>
                    {peak} assessment{peak === 1 ? '' : 's'} at peak
                </span>
                <span className="flex items-center gap-1.5">
                    <span className="bg-foreground/40 h-px w-4 rounded-full" /> average score
                </span>
            </div>

            <div className="relative h-40">
                <div className="absolute inset-0 flex flex-col justify-between">
                    {[0, 1, 2, 3].map((line) => (
                        <span key={line} className="border-border/60 border-t border-dashed" />
                    ))}
                </div>

                {withScores.length > 1 && (
                    <svg className="absolute inset-0 size-full" viewBox={`0 0 ${columns} 100`} preserveAspectRatio="none" aria-hidden>
                        <polyline
                            points={points}
                            fill="none"
                            stroke="currentColor"
                            strokeWidth={2}
                            strokeLinejoin="round"
                            strokeLinecap="round"
                            vectorEffect="non-scaling-stroke"
                            className="text-foreground/40"
                        />
                    </svg>
                )}

                <div className="absolute inset-0 flex">
                    {trend.map((point) => (
                        <Tooltip key={point.month}>
                            <TooltipTrigger asChild>
                                <div className="flex flex-1 flex-col justify-end px-1">
                                    <div
                                        className={cn(
                                            'w-full rounded-t-md bg-gradient-to-t from-sky-500/80 to-emerald-400/80 transition-all',
                                            point.assessments === 0 && 'bg-muted from-muted to-muted',
                                        )}
                                        style={{ height: point.assessments === 0 ? '2px' : `${Math.max((point.assessments / peak) * 100, 4)}%` }}
                                    />
                                </div>
                            </TooltipTrigger>
                            <TooltipContent>
                                {point.label}: {point.assessments} assessment{point.assessments === 1 ? '' : 's'}
                                {point.average_score !== null ? ` · avg ${point.average_score}/100` : ' · no score'}
                            </TooltipContent>
                        </Tooltip>
                    ))}
                </div>

                {withScores.map((point) => (
                    <span
                        key={`dot-${point.month}`}
                        className="bg-background border-foreground/40 pointer-events-none absolute size-2 -translate-x-1/2 -translate-y-1/2 rounded-full border-2"
                        style={{ left: `${((point.index + 0.5) / columns) * 100}%`, top: `${100 - (point.average_score as number)}%` }}
                    />
                ))}
            </div>

            <div className="text-muted-foreground flex text-xs">
                {trend.map((point) => (
                    <span key={point.month} className="flex-1 px-1 text-center">
                        {point.label}
                    </span>
                ))}
            </div>
        </div>
    );
}

function StatusMix({ mix }: { mix: DashboardProps['status_mix'] }) {
    return (
        <div className="flex flex-col gap-5">
            <div className="bg-muted flex h-3 w-full overflow-hidden rounded-full">
                {mix.buckets
                    .filter((bucket) => bucket.count > 0)
                    .map((bucket) => (
                        <span key={bucket.key} className={cn('h-full', tone(bucket.tone).fill)} style={{ width: `${bucket.percentage}%` }} />
                    ))}
            </div>

            <ul className="flex flex-col gap-3">
                {mix.buckets.map((bucket) => (
                    <li key={bucket.key} className="flex items-center gap-3 text-sm">
                        <span className={cn('size-2.5 shrink-0 rounded-full', tone(bucket.tone).dot)} />
                        <span className="flex-1 truncate">{bucket.label}</span>
                        <span className="text-muted-foreground text-xs tabular-nums">{bucket.count}</span>
                        <span className="w-10 text-right text-xs font-medium tabular-nums">{bucket.percentage}%</span>
                    </li>
                ))}
            </ul>
        </div>
    );
}

function RecentAssessments({ assessments }: { assessments: RecentAssessment[] }) {
    const getInitials = useInitials();

    if (assessments.length === 0) {
        return (
            <EmptyState
                icon={ClipboardCheck}
                title="No assessments yet"
                description="Screenings captured in the mobile app will appear here as soon as they are submitted."
                href="/patients"
                action="Open participants"
            />
        );
    }

    return (
        <ul className="flex flex-col">
            {assessments.map((assessment) => (
                <li key={assessment.id}>
                    <Link href={assessment.url} className="hover:bg-muted/60 -mx-2 flex items-center gap-3 rounded-lg px-2 py-2.5 transition-colors">
                        <Avatar className="size-9">
                            <AvatarImage src={assessment.patient?.photo ?? undefined} alt={assessment.patient?.name ?? 'Participant'} />
                            <AvatarFallback className="text-xs">{getInitials(assessment.patient?.name ?? '?')}</AvatarFallback>
                        </Avatar>

                        <div className="min-w-0 flex-1">
                            <p className="truncate text-sm font-medium">{assessment.patient?.name ?? 'Unknown participant'}</p>
                            <p className="text-muted-foreground truncate text-xs">
                                {assessment.pattern ? `${assessment.pattern} · ` : ''}
                                {formatDate(assessment.date)}
                            </p>
                        </div>

                        {assessment.needs_review && (
                            <Badge variant="outline" className={cn('hidden sm:inline-flex', tone('amber').chip)}>
                                Review
                            </Badge>
                        )}

                        <span className={cn('w-9 text-right text-sm font-semibold tabular-nums', tone(scoreTone(assessment.score)).text)}>
                            {assessment.score ?? '—'}
                        </span>

                        <Badge variant="outline" className={cn('hidden md:inline-flex', tone(assessment.status_tone).chip)}>
                            {assessment.status_label}
                        </Badge>
                    </Link>
                </li>
            ))}
        </ul>
    );
}

function NeedsAttention({ items }: { items: AttentionItem[] }) {
    const getInitials = useInitials();

    if (items.length === 0) {
        return <EmptyState icon={Gauge} title="Everyone is on track" description="No participant needs a follow-up review right now." />;
    }

    return (
        <ul className="flex flex-col">
            {items.map((item) => (
                <li key={item.patient.id}>
                    <Link href={item.url} className="group hover:bg-muted/60 -mx-2 flex items-center gap-3 rounded-lg px-2 py-2.5 transition-colors">
                        <Avatar className="size-9">
                            <AvatarImage src={item.patient.photo ?? undefined} alt={item.patient.name} />
                            <AvatarFallback className="text-xs">{getInitials(item.patient.name)}</AvatarFallback>
                        </Avatar>

                        <div className="min-w-0 flex-1">
                            <p className="truncate text-sm font-medium">
                                {item.patient.name}
                                {item.patient.age !== null && <span className="text-muted-foreground font-normal"> · {item.patient.age}y</span>}
                                <span className="text-muted-foreground text-xs font-normal"> · {formatDate(item.date)}</span>
                            </p>
                            <span className="mt-1 flex flex-wrap gap-1">
                                {item.reasons.map((reason) => (
                                    <span
                                        key={reason.label}
                                        className={cn('rounded-full border px-1.5 py-0.5 text-[11px] font-medium', tone(reason.tone).chip)}
                                    >
                                        {reason.label}
                                    </span>
                                ))}
                            </span>
                        </div>

                        <span className={cn('text-sm font-semibold tabular-nums', tone(scoreTone(item.score)).text)}>{item.score ?? '—'}</span>
                        <ArrowRight className="text-muted-foreground size-4 opacity-0 transition-opacity group-hover:opacity-100" />
                    </Link>
                </li>
            ))}
        </ul>
    );
}

function ProblemAreas({ areas }: { areas: ProblemArea[] }) {
    if (areas.length === 0) {
        return <EmptyState icon={Ruler} title="No problem areas recorded" description="Findings from stored assessments will be ranked here." />;
    }

    return (
        <ul className="flex flex-col gap-4">
            {areas.map((area) => (
                <li key={area.area} className="flex flex-col gap-1.5">
                    <div className="flex items-center justify-between gap-3 text-sm">
                        <span className="truncate font-medium">{area.area}</span>
                        <span className="flex items-center gap-2">
                            {area.severity && (
                                <span className={cn('rounded-full border px-2 py-0.5 text-[11px] font-medium capitalize', tone(area.tone).chip)}>
                                    {area.severity}
                                </span>
                            )}
                            <span className="text-muted-foreground text-xs tabular-nums">{area.count}×</span>
                        </span>
                    </div>
                    <div className="bg-muted h-1.5 w-full overflow-hidden rounded-full">
                        <div className={cn('h-full rounded-full', tone(area.tone).fill)} style={{ width: `${area.percentage}%` }} />
                    </div>
                </li>
            ))}
        </ul>
    );
}

function ScoreDelta({ delta }: { delta: number | null }) {
    if (delta === null || delta === 0) {
        return (
            <span className="text-muted-foreground inline-flex items-center gap-1">
                <Minus className="size-3" />
                {delta === null ? 'No previous month to compare' : 'Unchanged from last month'}
            </span>
        );
    }

    const improving = delta > 0;

    return (
        <span className={cn('inline-flex items-center gap-1', tone(improving ? 'emerald' : 'rose').text)}>
            <ArrowUpRight className={cn('size-3', !improving && 'rotate-90')} />
            {improving ? '+' : ''}
            {delta} vs last month
        </span>
    );
}

export default function Dashboard() {
    const { auth, stats, trend, status_mix, recent_assessments, needs_attention, problem_areas } = usePage<SharedData & DashboardProps>().props;

    const firstName = auth.user?.name?.split(' ')[0] ?? 'there';
    const today = new Intl.DateTimeFormat('en-GB', { weekday: 'long', day: 'numeric', month: 'long' }).format(new Date());
    const reviewTarget = recent_assessments.find((assessment) => assessment.needs_review);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />

            <div className="flex flex-1 flex-col gap-4 p-4">
                <section className="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p className="text-muted-foreground text-xs font-medium tracking-[0.18em] uppercase">Overview</p>
                        <h1 className="mt-1 text-2xl font-semibold tracking-tight">
                            {greetingFor(new Date().getHours())}, {firstName}
                        </h1>
                        <p className="text-muted-foreground mt-1 text-sm">
                            {today} · {stats.patients.total} participant{stats.patients.total === 1 ? '' : 's'} · {stats.assessments.this_month}{' '}
                            assessment
                            {stats.assessments.this_month === 1 ? '' : 's'} this month
                        </p>
                    </div>

                    <div className="flex flex-wrap gap-2">
                        <Button asChild variant="outline">
                            <Link href={route('interventions.index')}>
                                <Dumbbell className="size-4" />
                                Interventions
                            </Link>
                        </Button>
                        <Button asChild>
                            <Link href="/patients">
                                <Users className="size-4" />
                                Participants
                            </Link>
                        </Button>
                    </div>
                </section>

                <section className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <StatCard
                        icon={Users}
                        label="Participants"
                        value={stats.patients.total}
                        hint={
                            stats.patients.new_this_month > 0 ? `${stats.patients.new_this_month} new this month` : 'No new participants this month'
                        }
                    />
                    <StatCard
                        icon={ClipboardCheck}
                        label="Assessments logged"
                        value={stats.assessments.total}
                        hint={`${stats.assessments.this_month} captured this month`}
                        footer={
                            stats.assessments.total > 0 ? (
                                <div className="bg-muted h-1.5 w-full overflow-hidden rounded-full">
                                    <div
                                        className="h-full rounded-full bg-gradient-to-r from-sky-500 to-emerald-400"
                                        style={{
                                            width: `${Math.min((stats.assessments.this_month / Math.max(stats.assessments.total, 1)) * 100, 100)}%`,
                                        }}
                                    />
                                </div>
                            ) : undefined
                        }
                    />
                    <StatCard
                        icon={Gauge}
                        label="Average posture score"
                        value={stats.assessments.average_score || '—'}
                        suffix={stats.assessments.average_score ? '/ 100' : undefined}
                        hint="Across every stored assessment"
                        footer={
                            <p className="text-xs">
                                <ScoreDelta delta={stats.assessments.score_delta} />
                            </p>
                        }
                    />
                    <StatCard
                        icon={CircleAlert}
                        label="Measurements to review"
                        value={stats.review.outstanding}
                        hint={`of ${stats.review.measurements} captured measurements`}
                        chip={
                            stats.review.outstanding > 0
                                ? { label: 'Action needed', tone: 'amber', icon: CircleAlert }
                                : { label: 'All clear', tone: 'emerald' }
                        }
                        footer={
                            reviewTarget ? (
                                <Button asChild variant="outline" size="sm" className="w-full">
                                    <Link href={reviewTarget.url}>Open latest review</Link>
                                </Button>
                            ) : undefined
                        }
                    />
                </section>

                <section className="grid gap-4 lg:grid-cols-3">
                    <Card className="lg:col-span-2">
                        <CardHeader className="flex-row items-start justify-between gap-4">
                            <div>
                                <CardTitle className="flex items-center gap-2">
                                    <ChartLine className="size-4" />
                                    Screening activity
                                </CardTitle>
                                <CardDescription>Assessments logged and average score over the last six months</CardDescription>
                            </div>
                            <Badge variant="outline" className={cn('shrink-0', tone('sky').chip)}>
                                {stats.assessments.this_month} this month
                            </Badge>
                        </CardHeader>
                        <CardContent>
                            {stats.assessments.total === 0 ? (
                                <EmptyState
                                    icon={ClipboardCheck}
                                    title="Nothing to chart yet"
                                    description="Once screenings are stored, monthly volume and score trends appear here."
                                    href="/patients"
                                    action="Open participants"
                                />
                            ) : (
                                <TrendChart trend={trend} />
                            )}
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2">
                                <Crosshair className="size-4" />
                                Posture status mix
                            </CardTitle>
                            <CardDescription>
                                {stats.assessments.total > 0
                                    ? `How the ${stats.assessments.total} stored assessments are classified`
                                    : 'No assessments classified yet'}
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <StatusMix mix={status_mix} />
                        </CardContent>
                    </Card>
                </section>

                <section className="grid gap-4 lg:grid-cols-3">
                    <Card className="lg:col-span-2">
                        <CardHeader className="flex-row items-start justify-between gap-4">
                            <div>
                                <CardTitle>Recent assessments</CardTitle>
                                <CardDescription>Newest screenings across all participants</CardDescription>
                            </div>
                            <Button asChild variant="ghost" size="sm" className="shrink-0">
                                <Link href="/patients">
                                    View all
                                    <ArrowRight className="size-4" />
                                </Link>
                            </Button>
                        </CardHeader>
                        <CardContent>
                            <RecentAssessments assessments={recent_assessments} />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Needs attention</CardTitle>
                            <CardDescription>Latest screening is off track or awaiting review</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <NeedsAttention items={needs_attention} />
                        </CardContent>
                    </Card>
                </section>

                <section className="grid gap-4 lg:grid-cols-3">
                    <Card className="lg:col-span-2">
                        <CardHeader>
                            <CardTitle>Problem areas</CardTitle>
                            <CardDescription>Most frequently flagged areas across recent findings</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <ProblemAreas areas={problem_areas} />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Reference library</CardTitle>
                            <CardDescription>Thresholds and programs the engines read from</CardDescription>
                        </CardHeader>
                        <CardContent className="flex flex-col gap-3">
                            <Link
                                href={route('interventions.index')}
                                className="hover:bg-muted/60 flex items-center gap-3 rounded-lg border p-3 transition-colors"
                            >
                                <span className="bg-muted flex size-9 items-center justify-center rounded-lg">
                                    <Dumbbell className="size-4" />
                                </span>
                                <span className="flex-1">
                                    <span className="block text-sm font-medium">Interventions</span>
                                    <span className="text-muted-foreground block text-xs">{stats.library.interventions} programs available</span>
                                </span>
                                <ArrowRight className="text-muted-foreground size-4" />
                            </Link>

                            <Link
                                href={route('posture-settings.index')}
                                className="hover:bg-muted/60 flex items-center gap-3 rounded-lg border p-3 transition-colors"
                            >
                                <span className="bg-muted flex size-9 items-center justify-center rounded-lg">
                                    <Ruler className="size-4" />
                                </span>
                                <span className="flex-1">
                                    <span className="block text-sm font-medium">Posture settings</span>
                                    <span className="text-muted-foreground block text-xs">Severity bands and reference angles</span>
                                </span>
                                <ArrowRight className="text-muted-foreground size-4" />
                            </Link>

                            <Link
                                href={route('posture-markers.index')}
                                className="hover:bg-muted/60 flex items-center gap-3 rounded-lg border p-3 transition-colors"
                            >
                                <span className="bg-muted flex size-9 items-center justify-center rounded-lg">
                                    <Crosshair className="size-4" />
                                </span>
                                <span className="flex-1">
                                    <span className="block text-sm font-medium">Posture markers</span>
                                    <span className="text-muted-foreground block text-xs">Landmarks captured during screening</span>
                                </span>
                                <ArrowRight className="text-muted-foreground size-4" />
                            </Link>
                        </CardContent>
                    </Card>
                </section>
            </div>
        </AppLayout>
    );
}
