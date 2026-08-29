import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import type { InterventionFormData } from './hooks';

export default function InterventionForm({
    data,
    setData,
    errors,
    categories,
}: {
    data: InterventionFormData;
    setData: (key: keyof InterventionFormData, value: any) => void;
    errors?: Record<string, string>;
    categories: Record<string, string>;
}) {
    const fieldError = (key: string) => (errors as any)?.[key];

    return (
        <div className="space-y-4">
            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <Label>Title</Label>
                    <Input
                        value={data.title}
                        onChange={(e) => setData('title', e.target.value)}
                        placeholder="e.g. Chin Tuck Float"
                    />
                    {fieldError('title') && <p className="text-xs text-red-500">{fieldError('title')}</p>}
                </div>
                <div>
                    <Label>Category</Label>
                    <Select value={data.category} onValueChange={(v) => setData('category', v)}>
                        <SelectTrigger>
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            {Object.entries(categories).map(([key, label]) => (
                                <SelectItem key={key} value={key}>{label}</SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                </div>
            </div>
            <div>
                <Label>Description</Label>
                <Textarea
                    value={data.description}
                    onChange={(e) => setData('description', e.target.value)}
                    rows={3}
                    placeholder="Instructions for the patient…"
                />
            </div>
            <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <Label>Sets / Reps</Label>
                    <Input
                        value={data.sets_reps}
                        onChange={(e) => setData('sets_reps', e.target.value)}
                        placeholder="3 x 10 reps"
                    />
                </div>
                <div>
                    <Label>Frequency</Label>
                    <Input
                        value={data.frequency}
                        onChange={(e) => setData('frequency', e.target.value)}
                        placeholder="Daily"
                    />
                </div>
                <div>
                    <Label>Duration (min)</Label>
                    <Input
                        type="number"
                        min={0}
                        value={data.duration_minutes}
                        onChange={(e) => setData('duration_minutes', e.target.value)}
                        placeholder="10"
                    />
                </div>
            </div>
        </div>
    );
}
