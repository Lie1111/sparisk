import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Loader2 } from 'lucide-react';
import { useBmiPreview, type PatientFormData } from './hooks';

export type NeuroOption = { value: string; label: string };

const genders = [
    { value: 'male', label: 'Male' },
    { value: 'female', label: 'Female' },
    { value: 'other', label: 'Other' },
];

const specialNeeds = [
    { value: 'none', label: 'None' },
    { value: 'autism', label: 'Autism' },
    { value: 'adhd', label: 'ADHD' },
    { value: 'cerebral_palsy', label: 'Cerebral Palsy' },
    { value: 'down_syndrome', label: 'Down Syndrome' },
    { value: 'developmental_delay', label: 'Developmental Delay' },
    { value: 'other', label: 'Other' },
];

export default function PatientForm({
    data,
    setData,
    errors,
    neuroProfiles = [],
    neuroConditions = [],
}: {
    data: PatientFormData;
    setData: (key: keyof PatientFormData, value: any) => void;
    errors: Record<string, string>;
    neuroProfiles?: NeuroOption[];
    neuroConditions?: NeuroOption[];
}) {
    const fieldError = (key: string) => (errors as any)[key];

    const isNeurodivergent = data.neuro_profile === 'neurodivergent';

    const toggleCondition = (value: string) => {
        const next = isNeurodivergent
            ? data.neuro_conditions.includes(value)
                ? data.neuro_conditions.filter((c) => c !== value)
                : [...data.neuro_conditions, value]
            : [];
        setData('neuro_conditions', next);
    };

    const { result: bmi, loading: bmiLoading } = useBmiPreview({
        age: data.age ?? '',
        gender: data.gender,
        height: data.height,
        weight: data.weight,
    });

    const bmiError = bmi && !bmi.valid ? Object.values(bmi.errors)[0] : null;

    return (
        <div className="grid gap-4 py-4">
            <div className="grid gap-2">
                <Label htmlFor="name">Full Name *</Label>
                <Input id="name" value={data.name} onChange={(e) => setData('name', e.target.value)} />
                {fieldError('name') && <p className="text-xs text-red-500">{fieldError('name')}</p>}
            </div>

            <div className="grid grid-cols-2 gap-4">
                <div className="grid gap-2">
                    <Label htmlFor="age">Age</Label>
                    <Input id="age" type="number" value={data.age ?? ''} onChange={(e) => setData('age', e.target.value ? Number(e.target.value) : '')} />
                </div>
                <div className="grid gap-2">
                    <Label htmlFor="gender">Gender</Label>
                    <Select value={data.gender || 'none'} onValueChange={(v) => setData('gender', v === 'none' ? '' : v)}>
                        <SelectTrigger><SelectValue placeholder="Select" /></SelectTrigger>
                        <SelectContent>
                            <SelectItem value="none">-</SelectItem>
                            {genders.map(g => <SelectItem key={g.value} value={g.value}>{g.label}</SelectItem>)}
                        </SelectContent>
                    </Select>
                </div>
            </div>

            <div className="grid grid-cols-2 gap-4">
                <div className="grid gap-2">
                    <Label htmlFor="height">Height (cm)</Label>
                    <Input id="height" type="number" step="0.1" value={data.height ?? ''} onChange={(e) => setData('height', e.target.value ? Number(e.target.value) : '')} />
                </div>
                <div className="grid gap-2">
                    <Label htmlFor="weight">Weight (kg)</Label>
                    <Input id="weight" type="number" step="0.1" value={data.weight ?? ''} onChange={(e) => setData('weight', e.target.value ? Number(e.target.value) : '')} />
                </div>
            </div>

            {bmi || bmiLoading ? (
                <div className="rounded-md border bg-muted/40 px-3 py-2 text-sm">
                    <div className="flex items-center gap-2">
                        <span className="font-medium">BMI</span>
                        {bmiLoading && <Loader2 className="h-3.5 w-3.5 animate-spin text-muted-foreground" />}
                    </div>
                    {bmi?.valid ? (
                        <div className="mt-1 space-y-0.5">
                            <div className="font-medium">BMI: {bmi.bmi_display}</div>
                            <div className="font-medium">Category: {bmi.category}</div>
                            <div className="text-xs text-muted-foreground">
                                {[bmi.age_group_label, bmi.reference_label].filter(Boolean).join(' • ')}
                            </div>
                            {bmi.source && <div className="text-xs text-muted-foreground">Reference: {bmi.source}</div>}
                        </div>
                    ) : (
                        <div className="mt-1 text-xs text-muted-foreground">{bmiError ?? 'Enter age, height and weight to calculate BMI.'}</div>
                    )}
                </div>
            ) : null}

            <div className="grid gap-2">
                <Label htmlFor="state">State</Label>
                <Input id="state" value={data.state} onChange={(e) => setData('state', e.target.value)} />
            </div>

            <div className="grid gap-2">
                <Label>Neurodevelopmental Profile</Label>
                <p className="text-xs text-muted-foreground">
                    This information personalises instructions and support recommendations. It does not create or confirm a clinical
                    diagnosis.
                </p>
                <div className="grid grid-cols-2 gap-4">
                    {neuroProfiles.map((option) => (
                        <label
                            key={option.value}
                            htmlFor={`neuro_profile_${option.value}`}
                            className="flex cursor-pointer items-center gap-2 text-sm font-medium"
                        >
                            <Checkbox
                                id={`neuro_profile_${option.value}`}
                                checked={data.neuro_profile === option.value}
                                onCheckedChange={() =>
                                    setData('neuro_profile', data.neuro_profile === option.value ? '' : option.value)
                                }
                            />
                            {option.label}
                        </label>
                    ))}
                </div>

                {isNeurodivergent && (
                    <div className="mt-2 grid gap-2 rounded-md border bg-muted/40 p-3">
                        <Label>Select all that apply</Label>
                        <div className="grid grid-cols-2 gap-2">
                            {neuroConditions.map((option) => (
                                <label
                                    key={option.value}
                                    htmlFor={`neuro_condition_${option.value}`}
                                    className="flex cursor-pointer items-center gap-2 text-sm"
                                >
                                    <Checkbox
                                        id={`neuro_condition_${option.value}`}
                                        checked={data.neuro_conditions.includes(option.value)}
                                        onCheckedChange={() => toggleCondition(option.value)}
                                    />
                                    {option.label}
                                </label>
                            ))}
                        </div>
                        {data.neuro_conditions.includes('other') && (
                            <Input
                                placeholder="Please specify"
                                value={data.neuro_conditions_other}
                                onChange={(e) => setData('neuro_conditions_other', e.target.value)}
                            />
                        )}
                    </div>
                )}
            </div>

            <div className="grid gap-2">
                <Label htmlFor="special_needs">Special Needs</Label>
                <Select value={data.special_needs_type || 'none'} onValueChange={(v) => setData('special_needs_type', v)}>
                    <SelectTrigger><SelectValue placeholder="Select type" /></SelectTrigger>
                    <SelectContent>
                        {specialNeeds.map(n => <SelectItem key={n.value} value={n.value}>{n.label}</SelectItem>)}
                    </SelectContent>
                </Select>
            </div>

            <div className="grid gap-2">
                <Label htmlFor="emergency_contact_name">Emergency Contact Name</Label>
                <Input id="emergency_contact_name" value={data.emergency_contact_name} onChange={(e) => setData('emergency_contact_name', e.target.value)} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="emergency_contact_phone">Emergency Contact Phone</Label>
                <Input id="emergency_contact_phone" value={data.emergency_contact_phone} onChange={(e) => setData('emergency_contact_phone', e.target.value)} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="photo">Photo</Label>
                <Input id="photo" type="file" accept="image/*" onChange={(e) => {
                    const file = (e.target as HTMLInputElement).files?.[0];
                    if (file) setData('photo', file as any);
                }} />
            </div>
        </div>
    );
}
