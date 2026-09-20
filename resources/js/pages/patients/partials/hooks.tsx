import { useForm } from '@inertiajs/react';
import { useEffect, useState } from 'react';

export type Patient = {
    id: number;
    user_id: number | null;
    name: string;
    photo: string | null;
    age: number | null;
    gender: string | null;
    height: number | string | null;
    weight: number | string | null;
    state: string | null;
    diagnosis: string | null;
    emergency_contact_name: string | null;
    emergency_contact_phone: string | null;
    special_needs_type: string | null;
    neuro_profile?: string | null;
    neuro_profile_label?: string | null;
    neuro_conditions?: string[] | null;
    neuro_condition_labels?: string[] | null;
    neuro_conditions_other?: string | null;
    assessment_count: number;
    updated_at: string;
};

export type PatientFormData = {
    id: number | '';
    name: string;
    age: number | '' | null;
    gender: string;
    height: number | '' | string | null;
    weight: number | '' | string | null;
    state: string;
    diagnosis: string;
    emergency_contact_name: string;
    emergency_contact_phone: string;
    special_needs_type: string;
    neuro_profile: string;
    neuro_conditions: string[];
    neuro_conditions_other: string;
    photo: File | null;
    patients?: number;
};

const emptyForm: PatientFormData = {
    id: '',
    name: '',
    age: '',
    gender: '',
    height: '',
    weight: '',
    state: '',
    diagnosis: '',
    emergency_contact_name: '',
    emergency_contact_phone: '',
    special_needs_type: 'none',
    neuro_profile: '',
    neuro_conditions: [],
    neuro_conditions_other: '',
    photo: null,
};

export function usePatientForm() {
    const form = useForm<PatientFormData>(emptyForm);

    const setFromPatient = (patient: Patient) => {
        form.setData({
            id: patient.id,
            name: patient.name ?? '',
            age: patient.age ?? '',
            gender: patient.gender ?? '',
            height: patient.height ?? '',
            weight: patient.weight ?? '',
            state: patient.state ?? '',
            diagnosis: patient.diagnosis ?? '',
            emergency_contact_name: patient.emergency_contact_name ?? '',
            emergency_contact_phone: patient.emergency_contact_phone ?? '',
            special_needs_type: patient.special_needs_type ?? 'none',
            neuro_profile: patient.neuro_profile ?? '',
            neuro_conditions: patient.neuro_conditions ?? [],
            neuro_conditions_other: patient.neuro_conditions_other ?? '',
            photo: null,
        });
    };

    const resetForm = () => form.reset();

    return { ...form, setFromPatient, resetForm };
}

export type BmiResult = {
    valid: boolean;
    errors: Record<string, string>;
    bmi: number | null;
    bmi_display: string | null;
    category: string | null;
    age_group: string | null;
    age_group_label: string | null;
    reference_label: string | null;
    source: string | null;
    is_verified: boolean;
};

function xsrfToken(): string {
    const match = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);

    return match ? decodeURIComponent(match[1]) : '';
}

/**
 * Live BMI preview for the participant form.
 *
 * The BMI value and its category are always classified by the backend
 * SpariskBmiEngine, so the web form, the mobile app and the API never disagree.
 */
export function useBmiPreview({
    age,
    gender,
    height,
    weight,
}: {
    age: number | '' | null;
    gender: string;
    height: number | '' | string | null;
    weight: number | '' | string | null;
}) {
    const [result, setResult] = useState<BmiResult | null>(null);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        const numeric = (value: number | string | null | undefined) =>
            value === '' || value === null || value === undefined ? null : Number(value);

        const payload = {
            age: numeric(age),
            gender: gender || null,
            height: numeric(height),
            weight: numeric(weight),
        };

        if (payload.age === null && payload.height === null && payload.weight === null) {
            setResult(null);
            return;
        }

        const controller = new AbortController();
        const timer = setTimeout(async () => {
            setLoading(true);
            try {
                const response = await fetch('/patients/bmi-preview', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-XSRF-TOKEN': xsrfToken(),
                    },
                    body: JSON.stringify(payload),
                    signal: controller.signal,
                });
                setResult(response.ok ? await response.json() : null);
            } catch {
                if (!controller.signal.aborted) setResult(null);
            } finally {
                if (!controller.signal.aborted) setLoading(false);
            }
        }, 400);

        return () => {
            controller.abort();
            clearTimeout(timer);
        };
    }, [age, gender, height, weight]);

    return { result, loading };
}
