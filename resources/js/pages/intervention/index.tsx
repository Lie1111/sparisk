import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { useMemo, useState } from 'react';
import { toast } from 'sonner';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { type BreadcrumbItem } from '@/types';
import { Dumbbell, Plus, Save } from 'lucide-react';
import { InterventionCard, InterventionForm, emptyInterventionForm } from './partials';
import type { Intervention } from './partials';

export default function Index({
    interventions,
    postureTypes,
    ageGroups,
    categories,
}: {
    interventions: Intervention[];
    postureTypes: Record<string, string>;
    ageGroups: Record<string, { label: string; min: number; max: number | null }>;
    categories: Record<string, string>;
}) {
    const [selectedPosture, setSelectedPosture] = useState<string>('normal_neutral');
    const [selectedAge, setSelectedAge] = useState<string>('13-18');
    const [dialogOpen, setDialogOpen] = useState(false);
    const [form, setForm] = useState(emptyInterventionForm('normal_neutral', '13-18'));
    const [saving, setSaving] = useState(false);

    const breadcrumbs: BreadcrumbItem[] = [{ title: 'CADANGAN INTERVENSI', href: '/interventions' }];

    const filtered = useMemo(
        () =>
            interventions
                .filter((i) => i.posture_type === selectedPosture && i.age_group === selectedAge)
                .sort((a, b) => a.order_index - b.order_index),
        [interventions, selectedPosture, selectedAge],
    );

    const openCreate = () => {
        setForm(emptyInterventionForm(selectedPosture, selectedAge));
        setDialogOpen(true);
    };

    const openEdit = (item: Intervention) => {
        setForm({
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
        setDialogOpen(true);
    };

    const update = (key: keyof typeof form, value: string) => {
        setForm((prev) => ({ ...prev, [key]: value }));
    };

    const submit = () => {
        if (!form.title.trim()) {
            toast('Please enter a title');
            return;
        }
        setSaving(true);
        const payload = {
            ...form,
            duration_minutes: form.duration_minutes === '' ? null : Number(form.duration_minutes),
        };

        router.post(form.id ? '/interventions/update' : '/interventions/store', payload, {
            onSuccess: () => {
                setDialogOpen(false);
                toast(form.id ? 'Intervention updated' : 'Intervention added');
            },
            onError: () => toast('Failed to save intervention'),
            onFinish: () => setSaving(false),
        });
    };

    const remove = (item: Intervention) => {
        if (!window.confirm(`Delete "${item.title}"?`)) return;
        router.post('/interventions/destroy', { id: item.id }, {
            onSuccess: () => toast('Intervention deleted'),
            onError: () => toast('Failed to delete intervention'),
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="CADANGAN INTERVENSI" />

            <main className="flex-1 items-start gap-4 p-4 mt-4 sm:px-6 sm:py-0 md:gap-8">
                <div className="mb-4">
                    <h1 className="text-2xl font-semibold">CADANGAN INTERVENSI</h1>
                    <p className="text-sm text-muted-foreground">
                        Configure exercises and interventions that are good for patients with specific
                        postural conditions. Defaults are provided for the adolescent (13–18 years) age group.
                    </p>
                </div>

                <Card className="mb-6">
                    <CardHeader>
                        <CardTitle className="text-base">Select Posture Type &amp; Age Group</CardTitle>
                    </CardHeader>
                    <CardContent className="flex flex-col gap-4">
                        <div className="flex flex-col gap-4 lg:flex-row">
                            <div className="flex-1">
                                <Label className="text-xs text-muted-foreground">Posture Type</Label>
                                <Select value={selectedPosture} onValueChange={setSelectedPosture}>
                                    <SelectTrigger className="mt-1">
                                        <SelectValue placeholder="Posture type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {Object.entries(postureTypes).map(([key, label]) => (
                                            <SelectItem key={key} value={key}>{label}</SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                            <div className="flex-1">
                                <Label className="text-xs text-muted-foreground">Age Group</Label>
                                <Select value={selectedAge} onValueChange={setSelectedAge}>
                                    <SelectTrigger className="mt-1">
                                        <SelectValue placeholder="Age group" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {Object.entries(ageGroups).map(([key, group]) => (
                                            <SelectItem key={key} value={key}>{group.label}</SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                        <div className="flex justify-end">
                            <Button onClick={openCreate} className="gap-2">
                                <Plus className="h-4 w-4" /> Add Intervention
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2 text-base">
                            <Dumbbell className="h-4 w-4" />
                            {postureTypes[selectedPosture]} — {ageGroups[selectedAge]?.label}
                            <Badge variant="secondary" className="ml-auto">{filtered.length} item(s)</Badge>
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        {filtered.length === 0 ? (
                            <p className="py-8 text-center text-sm text-muted-foreground">
                                No interventions for this posture type yet. Click "Add Intervention" to create one.
                            </p>
                        ) : (
                            <div className="space-y-2">
                                {filtered.map((item) => (
                                    <InterventionCard
                                        key={item.id}
                                        item={item}
                                        categories={categories}
                                        onEdit={openEdit}
                                        onDelete={remove}
                                    />
                                ))}
                            </div>
                        )}
                    </CardContent>
                </Card>

                <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
                    <DialogContent className="sm:max-w-lg">
                        <DialogHeader>
                            <DialogTitle>{form.id ? 'Edit Intervention' : 'Add Intervention'}</DialogTitle>
                            <DialogDescription>
                                Set up an exercise or intervention for this posture type and age group.
                            </DialogDescription>
                        </DialogHeader>
                        <InterventionForm
                            data={form}
                            setData={update}
                            categories={categories}
                        />
                        <DialogFooter>
                            <Button variant="outline" onClick={() => setDialogOpen(false)}>Cancel</Button>
                            <Button onClick={submit} disabled={saving}>
                                <Save className="h-3.5 w-3.5" />
                                {saving ? 'Saving…' : 'Save'}
                            </Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </main>
        </AppLayout>
    );
}
