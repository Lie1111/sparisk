import { useForm } from '@inertiajs/react';

export type Intervention = {
    id: number;
    posture_type: string;
    posture_label: string;
    age_group: string;
    category: string;
    title: string;
    description: string | null;
    sets_reps: string | null;
    frequency: string | null;
    duration_minutes: number | null;
    order_index: number;
};

export type InterventionFormData = {
    id: number | null;
    posture_type: string;
    age_group: string;
    category: string;
    title: string;
    description: string;
    sets_reps: string;
    frequency: string;
    duration_minutes: string;
};

export const emptyInterventionForm = (posture_type: string, age_group: string): InterventionFormData => ({
    id: null,
    posture_type,
    age_group,
    category: 'exercise',
    title: '',
    description: '',
    sets_reps: '',
    frequency: '',
    duration_minutes: '',
});

export function useInterventionForm() {
    const form = useForm<InterventionFormData>(emptyInterventionForm('normal_neutral', '13-18'));

    const setFromIntervention = (item: Intervention) => {
        form.setData({
            id: item.id,
            posture_type: item.posture_type,
            age_group: item.age_group,
            category: item.category,
            title: item.title,
            description: item.description ?? '',
            sets_reps: item.sets_reps ?? '',
            frequency: item.frequency ?? '',
            duration_minutes: item.duration_minutes !== null ? String(item.duration_minutes) : '',
        });
    };

    return { ...form, setFromIntervention };
}
