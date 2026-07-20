import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { ArrowUpDown } from 'lucide-react';
import type { Patient } from './hooks';

const needsLabel = (type: string | null) => {
    if (!type || type === 'none') return null;
    return type.replace(/_/g, ' ');
};

export function buildColumns({ onSort, onView }: { onSort: (column: string) => void; onView: (patient: Patient) => void }) {
    return [
        {
            key: 'name',
            label: (
                <Button variant="ghost" onClick={() => onSort('name')}>
                    Name
                    <ArrowUpDown className="ml-2 h-4 w-4" />
                </Button>
            ),
            hidden: false,
            render: (patient: Patient) => (
                <button type="button" className="flex w-full items-center gap-3 text-left" onClick={() => onView(patient)}>
                    <div className="flex h-10 w-10 items-center justify-center rounded-md bg-muted">
                        <span className="font-bold text-primary">{patient.name?.[0]?.toUpperCase() || '?'}</span>
                    </div>
                    <div className="min-w-0">
                        <div className="truncate font-medium">{patient.name}</div>
                        <div className="truncate text-xs text-muted-foreground">
                            {[patient.age ? `${patient.age}y` : null, patient.gender, patient.diagnosis]
                                .filter(Boolean).join(' · ')}
                        </div>
                    </div>
                </button>
            ),
        },
        {
            key: 'state',
            label: 'State',
            hidden: false,
            render: (patient: Patient) => patient.state || '-',
        },
        {
            key: 'special_needs',
            label: 'Special Needs',
            hidden: false,
            render: (patient: Patient) => {
                const label = needsLabel(patient.special_needs_type);
                return label ? <Badge variant="secondary" className="text-xs">{label}</Badge> : <span className="text-xs text-muted-foreground">-</span>;
            },
        },
        {
            key: 'assessments',
            label: 'Assessments',
            hidden: false,
            render: (patient: Patient) => (
                <Badge variant="outline">{patient.assessment_count || 0}</Badge>
            ),
        },
        {
            key: 'updated_at',
            style: 'hidden md:table-cell',
            label: (
                <Button variant="ghost" onClick={() => onSort('updated_at')}>
                    Updated
                    <ArrowUpDown className="ml-2 h-4 w-4" />
                </Button>
            ),
            hidden: false,
            render: (patient: Patient) => new Date(patient.updated_at).toLocaleDateString('en-GB'),
        },
    ];
}
