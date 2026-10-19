import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Activity } from 'lucide-react';

export type SkillsCategory = {
    key: string;
    label: string;
    achieved: number;
    total: number;
    percentage: number;
};

export type SkillsSession = {
    id: number;
    label: string;
    index: number;
    date: string;
    score: number;
    max: number;
    percentage: number;
    category_scores: SkillsCategory[];
};

type SkillsFigure = {
    label: string;
    score: number;
    max: number;
    percentage: number;
};

export type SkillsProgress = {
    patient: { id: number; name: string };
    catalogue: { version: string; categories: Record<string, string>; item_count: number };
    sessions: SkillsSession[];
    categories: SkillsCategory[];
    current: null | {
        id: number;
        label: string;
        date: string;
        score: number;
        max: number;
        percentage: number;
        achieved: number;
        missed: number;
    };
    improvement: {
        has_comparison: boolean;
        items: number | null;
        percentage_points: number | null;
        baseline: SkillsFigure | null;
        latest: SkillsFigure | null;
    };
};

const BRAND = '#0E7C86';
const GRID = '#E2E8F0';
const MUTED = '#64748B';

/**
 * Overall progress, one point per sitting, drawn as a hand-written SVG line
 * chart. A single sitting is a lone marker: there is no line to draw and no
 * movement to imply.
 */
function OverallProgressChart({ sessions }: { sessions: SkillsSession[] }) {
    const width = 640;
    const height = 220;
    const left = 42;
    const right = 34;
    const top = 24;
    const bottom = 44;
    const plotWidth = width - left - right;
    const plotHeight = height - top - bottom;
    const ceiling = Math.max(...sessions.map((session) => session.max), 1);

    const x = (index: number) =>
        sessions.length === 1 ? left + plotWidth / 2 : left + (index / (sessions.length - 1)) * plotWidth;
    const y = (score: number) => top + (1 - score / ceiling) * plotHeight;

    // Keep the axis readable when a patient has a long history.
    const labelEvery = Math.max(1, Math.ceil(sessions.length / 6));

    return (
        <svg
            viewBox={`0 0 ${width} ${height}`}
            role="img"
            aria-label="Overall skills progress across sittings"
            className="h-auto w-full"
        >
            {[0, 0.5, 1].map((fraction) => (
                <g key={fraction}>
                    <line
                        x1={left}
                        y1={y(ceiling * fraction)}
                        x2={width - right}
                        y2={y(ceiling * fraction)}
                        stroke={GRID}
                        strokeWidth={1}
                        strokeDasharray={fraction === 0 ? undefined : '4 3'}
                    />
                    <text
                        x={left - 8}
                        y={y(ceiling * fraction) + 3}
                        textAnchor="end"
                        fontSize={10}
                        fill={MUTED}
                    >
                        {Math.round(ceiling * fraction)}
                    </text>
                </g>
            ))}

            {sessions.length > 1 && (
                <polyline
                    points={sessions.map((session, index) => `${x(index)},${y(session.score)}`).join(' ')}
                    fill="none"
                    stroke={BRAND}
                    strokeWidth={2.5}
                    strokeLinecap="round"
                    strokeLinejoin="round"
                />
            )}

            {sessions.map((session, index) => {
                const px = x(index);
                const py = y(session.score);

                return (
                    <g key={session.id}>
                        <circle cx={px} cy={py} r={4.5} fill="#FFFFFF" stroke={BRAND} strokeWidth={2.5} />
                        {sessions.length <= 6 && (
                            <text x={px} y={py - 10} textAnchor="middle" fontSize={10} fill="#0A3C50">
                                {session.score}/{session.max}
                            </text>
                        )}
                        {(index % labelEvery === 0 || index === sessions.length - 1) && (
                            <>
                                <text x={px} y={height - 20} textAnchor="middle" fontSize={10} fill={MUTED}>
                                    {session.label}
                                </text>
                                <text x={px} y={height - 8} textAnchor="middle" fontSize={9} fill="#94A3B8">
                                    {session.date}
                                </text>
                            </>
                        )}
                    </g>
                );
            })}
        </svg>
    );
}

/**
 * The four categories as percentages of their own item counts. Raw counts
 * would always favour the longest category (11 advanced items against 6
 * control items), so every bar is measured out of 100.
 */
function CategoryBarChart({ categories }: { categories: SkillsCategory[] }) {
    return (
        <div>
            <div className="flex h-40 items-end gap-3 border-b border-border">
                {categories.map((category) => (
                    <div key={category.key} className="h-full flex-1" title={category.label}>
                        <div className="flex h-full flex-col justify-end">
                            <div
                                className="w-full rounded-t"
                                style={{
                                    height: `${Math.max(category.percentage, 1.5)}%`,
                                    backgroundColor: BRAND,
                                }}
                            />
                        </div>
                    </div>
                ))}
            </div>
            <div className="mt-2 flex gap-3">
                {categories.map((category) => (
                    <div key={category.key} className="flex-1 text-center">
                        <div className="text-xs font-semibold" style={{ color: BRAND }}>
                            {category.percentage}%
                        </div>
                        <div className="text-[10px] leading-tight text-muted-foreground">{category.label}</div>
                        <div className="text-[10px] text-muted-foreground">
                            {category.achieved}/{category.total}
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}

function improvementNote(improvement: SkillsProgress['improvement']): string {
    if (!improvement.has_comparison) {
        return 'First sitting recorded — a comparison appears after the next sitting.';
    }

    const items = improvement.items ?? 0;
    const points = improvement.percentage_points ?? 0;
    const since = improvement.baseline?.label ?? 'the baseline';

    if (items === 0 && points === 0) {
        return `No change since ${since}.`;
    }

    return `${items > 0 ? '+' : ''}${items} items · ${points > 0 ? '+' : ''}${points} percentage points since ${since}`;
}

/**
 * The latest sitting as a headline percentage, with the achieved / not
 * achieved split and a progress bar.
 */
function CurrentScore({
    current,
    improvement,
}: {
    current: NonNullable<SkillsProgress['current']>;
    improvement: SkillsProgress['improvement'];
}) {
    const points = improvement.percentage_points ?? 0;
    const showDelta = improvement.has_comparison && points !== 0;

    return (
        <div className="flex h-full flex-col">
            <div className="flex items-baseline gap-2">
                <span className="text-4xl font-bold tabular-nums">{current.percentage}%</span>
                {showDelta && (
                    <span className={`text-sm font-medium ${points > 0 ? 'text-green-700' : 'text-red-600'}`}>
                        {points > 0 ? '+' : ''}
                        {points} pp
                    </span>
                )}
            </div>
            <div className="mt-1 text-xs text-muted-foreground">
                {current.label} · {current.date}
            </div>

            <div className="mt-4 h-2.5 w-full overflow-hidden rounded-full bg-muted">
                <div className="h-full rounded-full" style={{ width: `${current.percentage}%`, backgroundColor: BRAND }} />
            </div>

            <div className="mt-2 flex items-center justify-between text-xs">
                <span>
                    <span className="font-semibold">{current.achieved}</span> of {current.max} items achieved
                </span>
                <span className="text-muted-foreground">{current.missed} not achieved</span>
            </div>

            <p className="mt-3 text-xs text-muted-foreground">{improvementNote(improvement)}</p>
        </div>
    );
}

export default function SkillsProgressDashboard({ progress }: { progress: SkillsProgress }) {
    const sessions = progress.sessions ?? [];
    const categories = progress.categories ?? [];

    return (
        <Card className="mt-4">
            <CardHeader>
                <CardTitle className="flex items-center gap-2 text-base">
                    <Activity className="h-4 w-4" />
                    Student Progress Dashboard
                </CardTitle>
                <p className="text-xs text-muted-foreground">
                    Aquatic skills checklist · {progress.catalogue.item_count} items · {progress.catalogue.version}
                </p>
            </CardHeader>
            <CardContent>
                {sessions.length === 0 || progress.current === null ? (
                    <p className="text-sm text-muted-foreground">
                        No aquatic skills sittings recorded yet. The first sitting becomes the baseline every later
                        sitting is measured against.
                    </p>
                ) : (
                    <div className="space-y-6">
                        <section>
                            <h3 className="text-sm font-semibold">Overall progress</h3>
                            <p className="mb-2 text-xs text-muted-foreground">
                                Score out of {progress.current.max} at each sitting, oldest first.
                            </p>
                            <OverallProgressChart sessions={sessions} />
                        </section>

                        <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            <section>
                                <h3 className="text-sm font-semibold">Progress by Assessment Category</h3>
                                <p className="mb-3 text-xs text-muted-foreground">
                                    Percentage of each category&apos;s own items achieved at the latest sitting.
                                </p>
                                <CategoryBarChart categories={categories} />
                            </section>

                            <section>
                                <h3 className="text-sm font-semibold">Overall score as a percentage</h3>
                                <p className="mb-3 text-xs text-muted-foreground">
                                    Items achieved against the full checklist.
                                </p>
                                <CurrentScore current={progress.current} improvement={progress.improvement} />
                            </section>
                        </div>
                    </div>
                )}
            </CardContent>
        </Card>
    );
}
