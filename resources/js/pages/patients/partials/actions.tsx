import type { Patient } from './hooks';

export function buildActions({
    onView,
    onEdit,
    onDelete,
}: {
    onView: (patient: Patient) => void;
    onEdit: (patient: Patient) => void;
    onDelete: (patient: Patient) => void;
}) {
    return [
        {
            label: 'View',
            onClick: (patient: Patient) => onView(patient),
        },
        {
            label: 'Edit',
            onClick: (patient: Patient) => onEdit(patient),
        },
        {
            label: 'Delete',
            onClick: (patient: Patient) => onDelete(patient),
        },
    ];
}
