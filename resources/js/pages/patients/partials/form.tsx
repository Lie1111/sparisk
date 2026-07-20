import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import type { PatientFormData } from './hooks';

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
}: {
    data: PatientFormData;
    setData: (key: keyof PatientFormData, value: any) => void;
    errors: Record<string, string>;
}) {
    const fieldError = (key: string) => (errors as any)[key];

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

            <div className="grid gap-2">
                <Label htmlFor="state">State</Label>
                <Input id="state" value={data.state} onChange={(e) => setData('state', e.target.value)} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="diagnosis">Diagnosis</Label>
                <Input id="diagnosis" value={data.diagnosis} onChange={(e) => setData('diagnosis', e.target.value)} />
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
