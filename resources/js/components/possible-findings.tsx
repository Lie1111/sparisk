export type PossibleFinding = { label: string; percent: number };

/** Red from 75%, amber from 50%, blue below: how strongly a pattern is shown. */
const tone = (percent: number) => {
    if (percent >= 75) return { text: 'text-red-600 dark:text-red-400', bar: 'bg-red-500', track: 'bg-red-100 dark:bg-red-950' };
    if (percent >= 50) return { text: 'text-amber-600 dark:text-amber-400', bar: 'bg-amber-500', track: 'bg-amber-100 dark:bg-amber-950' };
    return { text: 'text-blue-600 dark:text-blue-400', bar: 'bg-blue-500', track: 'bg-blue-100 dark:bg-blue-950' };
};

/**
 * Posture patterns with their match score, as the mobile app shows them: how
 * strongly the measurements show each pattern, weighing how directly and how
 * far off each reading is.
 */
export function PossibleFindings({ findings, title = 'Possible Findings' }: { findings: PossibleFinding[]; title?: string }) {
    if (findings.length === 0) return null;

    return (
        <div className="space-y-3">
            <div>
                <p className="text-sm font-medium">{title}</p>
                <p className="text-xs text-muted-foreground">How closely the measurements match each posture pattern.</p>
            </div>
            {findings.map((f) => {
                const t = tone(f.percent);
                return (
                    <div key={f.label} className="space-y-1">
                        <div className="flex items-baseline justify-between gap-2 text-sm">
                            <span className="font-medium">{f.label}</span>
                            <span className={`font-semibold tabular-nums ${t.text}`}>{f.percent}%</span>
                        </div>
                        <div className={`h-1.5 overflow-hidden rounded-full ${t.track}`}>
                            <div className={`h-full rounded-full ${t.bar}`} style={{ width: `${Math.min(100, Math.max(0, f.percent))}%` }} />
                        </div>
                    </div>
                );
            })}
        </div>
    );
}

/** The percent a classification row shows: its match score, else the stored confidence. */
export const classificationPercent = (c: { match?: number | null; confidence?: string | number | null }) => {
    if (c.match != null) return c.match;
    const n = Number(c.confidence);
    return Number.isFinite(n) && c.confidence != null ? Math.round(n) : null;
};
