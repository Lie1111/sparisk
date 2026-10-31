import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { ArrowUpDown } from 'lucide-react';
import type { Patient } from './hooks';

const needsLabel = (type: string | null) => {
    if (!type || type === 'none') return null;
    return type.replace(/_/g, ' ');
};

const dash = <span className="text-xs text-muted-foreground">-</span>;

/** Green for good posture, amber for needs improvement, red for poor. */
const scoreTone = (status: string | null) => {
    const s = (status ?? '').toUpperCase();
    if (/GOOD|EXCELLENT|NORMAL/.test(s)) return 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-950 dark:text-emerald-300 dark:ring-emerald-900';
    if (/POOR|SEVERE|HIGH/.test(s)) return 'bg-red-50 text-red-700 ring-red-200 dark:bg-red-950 dark:text-red-300 dark:ring-red-900';
    return 'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-950 dark:text-amber-300 dark:ring-amber-900';
};

const titleCase = (s: string) => s.toLowerCase().replace(/\b\w/g, (c) => c.toUpperCase());

const formatDate = (date: string) => new Date(date).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });

const sortable = (label: string, column: string, onSort: (column: string) => void) => (
    <Button variant="ghost" className="-ml-3" onClick={() => onSort(column)}>
        {label}
        <ArrowUpDown className="ml-2 h-4 w-4" />
    </Button>
);

export function buildColumns({ onSort }: { onSort: (column: string) => void }) {
    return [
        {
            key: 'name',
            label: sortable('Patient', 'name', onSort),
            hidden: false,
            render: (patient: Patient) => {
                const needs = needsLabel(patient.special_needs_type);
                return (
                    <div className="flex items-center gap-3">
                        {patient.photo ? (
                            <img src={patient.photo} alt="" className="h-10 w-10 rounded-md object-cover" />
                        ) : (
                            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-muted">
                                <span className="font-bold text-primary">{patient.name?.[0]?.toUpperCase() || '?'}</span>
                            </div>
                        )}
                        <div className="min-w-0">
                            <div className="truncate font-medium capitalize">{patient.name}</div>
                            <div className="flex items-center gap-1.5 text-xs text-muted-foreground">
                                <span className="capitalize">
                                    {[patient.age ? `${patient.age}y` : null, patient.gender].filter(Boolean).join(' · ')}
                                </span>
                                {needs && <Badge variant="secondary" className="h-4 px-1.5 text-[10px] capitalize">{needs}</Badge>}
                            </div>
                        </div>
                    </div>
                );
            },
        },
        {
            key: 'problem',
            label: 'Main Problem',
            hidden: false,
            render: (patient: Patient) => {
                const latest = patient.latest_assessment;
                if (!latest) return <span className="text-xs text-muted-foreground">Not assessed yet</span>;
                const [main, ...rest] = latest.problems;
                if (!main) return <span className="text-xs text-emerald-600 dark:text-emerald-400">No problem found</span>;
                return (
                    <div className="flex flex-wrap items-center gap-1.5">
                        <span className="font-medium">{main.name}</span>
                        {main.certainty === 'possible' && <span className="text-xs text-muted-foreground">(possible)</span>}
                        {rest.length > 0 && (
                            <Badge variant="outline" className="text-xs" title={rest.map((p) => p.name).join(', ')}>
                                +{rest.length} more
                            </Badge>
                        )}
                    </div>
                );
            },
        },
        {
            key: 'score',
            label: 'Posture Score',
            hidden: false,
            render: (patient: Patient) => {
                const latest = patient.latest_assessment;
                if (latest?.score == null) return dash;
                return (
                    <div className="flex items-center gap-2">
                        <span className="text-base font-semibold tabular-nums">{latest.score}</span>
                        {latest.status && (
                            <span className={`rounded-full px-2 py-0.5 text-[11px] font-medium ring-1 ring-inset ${scoreTone(latest.status)}`}>
                                {titleCase(latest.status)}
                            </span>
                        )}
                    </div>
                );
            },
        },
        {
            key: 'bmi',
            label: 'BMI',
            style: 'hidden lg:table-cell',
            hidden: false,
            render: (patient: Patient) => {
                const bmi = patient.bmi;
                if (!bmi?.valid || bmi.bmi == null) return dash;
                return (
                    <div>
                        <div className="tabular-nums">{bmi.bmi}</div>
                        {bmi.category && <div className="text-xs text-muted-foreground">{bmi.category}</div>}
                    </div>
                );
            },
        },
        {
            key: 'updated_at',
            style: 'hidden md:table-cell',
            label: 'Last Assessment',
            hidden: false,
            render: (patient: Patient) => {
                const latest = patient.latest_assessment;
                const count = patient.assessment_count || 0;
                return (
                    <div>
                        <div>{latest?.date ? formatDate(latest.date) : '-'}</div>
                        <div className="text-xs text-muted-foreground">
                            {count} assessment{count === 1 ? '' : 's'}
                        </div>
                    </div>
                );
            },
        },
    ];
}
