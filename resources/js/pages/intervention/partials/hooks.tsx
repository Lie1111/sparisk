import { useForm } from '@inertiajs/react';
import { Dumbbell, Flower2, Waves, type LucideIcon } from 'lucide-react';

export type Intervention = {
    id: number;
    posture_type: string;
    posture_label: string;
    age_group: string;
    category: string;
    program: string;
    level: string | null;
    title: string;
    description: string | null;
    sets_reps: string | null;
    frequency: string | null;
    duration_minutes: number | null;
    image_path: string | null;
    image_url: string | null;
    /** Resolved image to display: the external link, else the uploaded file. */
    image_src: string | null;
    order_index: number;
};

export type InterventionFormData = {
    id: number | null;
    posture_type: string;
    age_group: string;
    category: string;
    program: string;
    level: string;
    title: string;
    description: string;
    sets_reps: string;
    frequency: string;
    duration_minutes: string;
    image_url: string;
};

/** Icon per program, used on the tabs, the cards and the form. */
export const PROGRAM_ICONS: Record<string, LucideIcon> = {
    aquatic_exercise: Waves,
    massage_therapy: Flower2,
    general_exercise: Dumbbell,
};

export const programIcon = (program: string): LucideIcon => PROGRAM_ICONS[program] ?? Dumbbell;

/** Tailwind classes for the roomy program header inside the card. */
export const PROGRAM_ACCENT: Record<string, string> = {
    aquatic_exercise: 'bg-sky-50 text-sky-600',
    massage_therapy: 'bg-amber-50 text-amber-600',
    general_exercise: 'bg-violet-50 text-violet-600',
};

export const LEVEL_ACCENT: Record<string, string> = {
    beginner: 'bg-emerald-50 text-emerald-700 border-emerald-200',
    intermediate: 'bg-amber-50 text-amber-700 border-amber-200',
    advanced: 'bg-rose-50 text-rose-700 border-rose-200',
};

export const emptyInterventionForm = (
    posture_type: string,
    age_group: string,
    program = 'general_exercise',
): InterventionFormData => ({
    id: null,
    posture_type,
    age_group,
    category: 'exercise',
    program,
    level: '',
    title: '',
    description: '',
    sets_reps: '',
    frequency: '',
    duration_minutes: '',
    image_url: '',
});

export const formFromIntervention = (item: Intervention): InterventionFormData => ({
    id: item.id,
    posture_type: item.posture_type,
    age_group: item.age_group,
    category: item.category,
    program: item.program ?? 'general_exercise',
    level: item.level ?? '',
    title: item.title,
    description: item.description ?? '',
    sets_reps: item.sets_reps ?? '',
    frequency: item.frequency ?? '',
    duration_minutes: item.duration_minutes !== null ? String(item.duration_minutes) : '',
    image_url: item.image_url ?? '',
});

export function useInterventionForm() {
    const form = useForm<InterventionFormData>(emptyInterventionForm('normal_neutral', '13-18'));

    const setFromIntervention = (item: Intervention) => {
        form.setData(formFromIntervention(item));
    };

    return { ...form, setFromIntervention };
}
