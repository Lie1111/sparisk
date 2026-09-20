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
import { Tabs, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { type BreadcrumbItem } from '@/types';
import { ArrowLeft, Plus, Save } from 'lucide-react';
import {
    InterventionCard,
    InterventionForm,
    PROGRAM_ACCENT,
    emptyInterventionForm,
    formFromIntervention,
    programIcon,
} from './partials';
import type { Intervention, InterventionFormData } from './partials';

const FALLBACK_PROGRAMS: Record<string, string> = {
    aquatic_exercise: 'Aquatic Exercises',
    massage_therapy: 'Massage Therapy Plan',
    general_exercise: 'General Exercises',
};

function currentPostureLabel(postureTypes: Record<string, string>, posture: string) {
    return postureTypes[posture] || posture;
}

export default function Show({
    interventions,
    postureTypes,
    ageGroups,
    categories,
    programs,
    levels,
    posture,
    age,
}: {
    interventions: Intervention[];
    postureTypes: Record<string, string>;
    ageGroups: Record<string, { label: string; min: number; max: number | null }>;
    categories: Record<string, string>;
    programs?: Record<string, string>;
    levels?: Record<string, string>;
    posture: string;
    age: string;
}) {
    const programList = programs ?? FALLBACK_PROGRAMS;
    const programKeys = Object.keys(programList);

    const [activeProgram, setActiveProgram] = useState<string>(programKeys[0] ?? 'aquatic_exercise');
    const [dialogOpen, setDialogOpen] = useState(false);
    const [form, setForm] = useState<InterventionFormData>(
        emptyInterventionForm(posture, age, programKeys[0] ?? 'aquatic_exercise'),
    );
    const [imageFile, setImageFile] = useState<File | null>(null);
    const [removeImage, setRemoveImage] = useState(false);
    const [errors, setErrors] = useState<Record<string, string>>({});
    const [saving, setSaving] = useState(false);

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Intervention Recommendations', href: '/interventions' },
        { title: currentPostureLabel(postureTypes, posture), href: `/interventions/${posture}/${age}` },
    ];

    const currentProgram = programKeys.includes(activeProgram) ? activeProgram : programKeys[0];
    const ActiveIcon = programIcon(currentProgram);
    const programLabel = programList[currentProgram] ?? 'Intervention';

    const counts = useMemo(
        () =>
            interventions.reduce<Record<string, number>>((acc, item) => {
                acc[item.program] = (acc[item.program] ?? 0) + 1;
                return acc;
            }, {}),
        [interventions],
    );

    /** Rows of the active program, grouped by category for a tidy list. */
    const grouped = useMemo(() => {
        const map: Record<string, Intervention[]> = {};
        interventions
            .filter((item) => item.program === currentProgram)
            .sort((a, b) => a.order_index - b.order_index)
            .forEach((item) => {
                if (!map[item.category]) map[item.category] = [];
                map[item.category].push(item);
            });
        return map;
    }, [interventions, currentProgram]);

    const visibleCount = useMemo(
        () => Object.values(grouped).reduce((total, items) => total + items.length, 0),
        [grouped],
    );

    const navigate = (postureType: string, ageGroup: string) => {
        router.get(`/interventions/${postureType}/${ageGroup}`);
    };

    const openCreate = () => {
        setForm(emptyInterventionForm(posture, age, currentProgram));
        setImageFile(null);
        setRemoveImage(false);
        setErrors({});
        setDialogOpen(true);
    };

    const openEdit = (item: Intervention) => {
        setForm(formFromIntervention(item));
        setImageFile(null);
        setRemoveImage(false);
        setErrors({});
        setDialogOpen(true);
    };

    const update = (key: keyof InterventionFormData, value: any) => {
        setForm((prev) => ({ ...prev, [key]: value }));
    };

    const removeCurrentImage = () => {
        setForm((prev) => ({ ...prev, image_url: '' }));
        setRemoveImage(true);
    };

    const submit = () => {
        if (!form.title.trim()) {
            toast(form.program === 'massage_therapy' ? 'Please enter a body area' : 'Please enter a title');
            return;
        }

        setSaving(true);

        const payload = new FormData();

        if (form.id) payload.append('id', String(form.id));
        payload.append('posture_type', form.posture_type);
        payload.append('age_group', form.age_group);
        payload.append('category', form.category);
        payload.append('program', form.program);
        payload.append('level', form.level);
        payload.append('title', form.title);
        payload.append('description', form.description);
        payload.append('sets_reps', form.sets_reps);
        payload.append('frequency', form.frequency);
        payload.append('duration_minutes', form.duration_minutes);
        payload.append('image_url', form.image_url);

        if (imageFile) payload.append('image_file', imageFile);
        if (removeImage) payload.append('remove_image', '1');

        router.post(form.id ? '/interventions/update' : '/interventions/store', payload, {
            forceFormData: true,
            onSuccess: () => {
                setDialogOpen(false);
                toast(form.id ? 'Intervention updated' : 'Intervention added');
            },
            onError: (validationErrors) => {
                setErrors(validationErrors as Record<string, string>);
                toast('Please check the highlighted fields');
            },
            onFinish: () => setSaving(false),
        });
    };

    const remove = (item: Intervention) => {
        if (!window.confirm(`Delete "${item.title}"?`)) return;
        router.post(
            '/interventions/destroy',
            { id: item.id },
            {
                onSuccess: () => toast('Intervention deleted'),
                onError: () => toast('Failed to delete intervention'),
            },
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`${currentPostureLabel(postureTypes, posture)} - Interventions`} />

            <main className="mt-4 flex-1 items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8">
                <div className="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <button
                        type="button"
                        onClick={() => router.get('/interventions')}
                        className="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-foreground"
                    >
                        <ArrowLeft className="h-4 w-4" />
                        Back to Interventions
                    </button>
                    <Button onClick={openCreate} className="gap-2">
                        <Plus className="h-4 w-4" /> Add {programLabel}
                    </Button>
                </div>

                <Card className="mb-6">
                    <CardHeader className="pb-3">
                        <CardTitle className="flex flex-wrap items-center gap-2 text-base">
                            {currentPostureLabel(postureTypes, posture)} — {ageGroups[age]?.label}
                            <Badge variant="secondary" className="ml-auto">
                                {interventions.length} item(s)
                            </Badge>
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="flex flex-col gap-4 lg:flex-row">
                            <div className="flex-1">
                                <Label className="text-xs text-muted-foreground">Posture Type</Label>
                                <Select value={posture} onValueChange={(v) => navigate(v, age)}>
                                    <SelectTrigger className="mt-1">
                                        <SelectValue placeholder="Posture type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {Object.entries(postureTypes).map(([key, label]) => (
                                            <SelectItem key={key} value={key}>
                                                {label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                            <div className="flex-1">
                                <Label className="text-xs text-muted-foreground">Age Group</Label>
                                <Select value={age} onValueChange={(v) => navigate(posture, v)}>
                                    <SelectTrigger className="mt-1">
                                        <SelectValue placeholder="Age group" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {Object.entries(ageGroups).map(([key, group]) => (
                                            <SelectItem key={key} value={key}>
                                                {group.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Tabs value={currentProgram} onValueChange={setActiveProgram}>
                    <TabsList className="h-auto w-full flex-wrap justify-start gap-1 p-1">
                        {programKeys.map((key) => {
                            const Icon = programIcon(key);
                            return (
                                <TabsTrigger key={key} value={key} className="gap-2">
                                    <Icon className="h-4 w-4" />
                                    {programList[key]}
                                    <span className="rounded-full bg-muted px-1.5 text-[10px] text-muted-foreground">
                                        {counts[key] ?? 0}
                                    </span>
                                </TabsTrigger>
                            );
                        })}
                    </TabsList>
                </Tabs>

                {visibleCount === 0 ? (
                    <Card className="mt-4">
                        <CardContent>
                            <div className="flex flex-col items-center gap-3 py-12">
                                <div
                                    className={`flex h-12 w-12 items-center justify-center rounded-full ${
                                        PROGRAM_ACCENT[currentProgram] ?? 'bg-muted text-muted-foreground'
                                    }`}
                                >
                                    <ActiveIcon className="h-5 w-5" />
                                </div>
                                <div className="text-center">
                                    <p className="text-sm font-medium">No {programLabel.toLowerCase()} yet</p>
                                    <p className="text-xs text-muted-foreground">
                                        for {currentPostureLabel(postureTypes, posture)} — {ageGroups[age]?.label}
                                    </p>
                                </div>
                                <Button variant="outline" size="sm" onClick={openCreate} className="gap-2">
                                    <Plus className="h-3.5 w-3.5" /> Add {programLabel}
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                ) : (
                    Object.entries(grouped).map(([categoryKey, items]) => (
                        <Card key={categoryKey} className="mt-4">
                            <CardHeader className="pb-3">
                                <CardTitle className="text-base">
                                    {categories[categoryKey] || categoryKey}
                                </CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-3">
                                {items.map((item) => (
                                    <InterventionCard
                                        key={item.id}
                                        item={item}
                                        categories={categories}
                                        onEdit={openEdit}
                                        onDelete={remove}
                                    />
                                ))}
                            </CardContent>
                        </Card>
                    ))
                )}

                <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
                    <DialogContent className="max-h-[90vh] overflow-y-auto sm:max-w-2xl">
                        <DialogHeader>
                            <DialogTitle>{form.id ? 'Edit Intervention' : 'Add Intervention'}</DialogTitle>
                            <DialogDescription>
                                {currentPostureLabel(postureTypes, form.posture_type)} — {ageGroups[form.age_group]?.label}.
                                Set the program, prescription and the image the patient will see.
                            </DialogDescription>
                        </DialogHeader>
                        <InterventionForm
                            data={form}
                            setData={update}
                            errors={errors}
                            categories={categories}
                            programs={programList}
                            levels={levels}
                            imageFile={imageFile}
                            onImageFileChange={setImageFile}
                            onRemoveImage={removeCurrentImage}
                            currentImage={form.id ? (interventions.find((i) => i.id === form.id)?.image_src ?? null) : null}
                        />
                        <DialogFooter>
                            <Button variant="outline" onClick={() => setDialogOpen(false)}>
                                Cancel
                            </Button>
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
