import { useForm } from '@inertiajs/react';

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
            photo: null,
        });
    };

    const resetForm = () => form.reset();

    return { ...form, setFromPatient, resetForm };
}
