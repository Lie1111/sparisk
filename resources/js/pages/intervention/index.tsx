import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { useMemo, useState } from 'react';
import { toast } from 'sonner';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { cn } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import { CalendarDays, Clock, Plus, Save, Search } from 'lucide-react';
import {
    InterventionCard,
    InterventionForm,
    PROGRAM_ACCENT,
    PROGRAM_DOT,
    dayShort,
    emptyInterventionForm,
    formFromIntervention,
    programIcon,
} from './partials';
import type { Intervention, InterventionFormData } from './partials';

/** Shown under the tabs so the admin knows what each program is for. */
const PROGRAM_HINTS: Record<string, string> = {
    aquatic_exercise: 'Exercises performed in a therapy pool.',
    massage_therapy: 'Manual therapy plan, listed by body area.',
    general_exercise: 'Land based exercises and lifestyle habits.',
};

const FALLBACK_PROGRAMS: Record<string, string> = {
    aquatic_exercise: 'Aquatic Exercises',
    massage_therapy: 'Massage Therapy Plan',
    general_exercise: 'General Exercises',
};

const WEEKLY_TAB = 'weekly';
const GENERAL_SLUG = 'normal_neutral';

export default function Index({
    interventions,
    postureTypes,
    weekdays,
    ageGroups,
    categories,
    programs,
    levels,
}: {
    interventions: Intervention[];
    /** Muscle-map conditions plus the general plan, slug => name. */
    postureTypes: Record<string, string>;
    weekdays: string[];
    ageGroups: Record<string, { label: string; min: number; max: number | null }>;
    categories: Record<string, string>;
    programs?: Record<string, string>;
    levels?: Record<string, string>;
}) {
    const programList = programs ?? FALLBACK_PROGRAMS;
    const programKeys = Object.keys(programList);
    const postureKeys = Object.keys(postureTypes);

    const [selectedPosture, setSelectedPosture] = useState<string>(postureKeys[0] ?? GENERAL_SLUG);
    const [selectedAge, setSelectedAge] = useState<string>('13-18');
    const [activeTab, setActiveTab] = useState<string>(WEEKLY_TAB);
    const [search, setSearch] = useState('');
    const [dialogOpen, setDialogOpen] = useState(false);
    const [form, setForm] = useState<InterventionFormData>(
        emptyInterventionForm(selectedPosture, '13-18', programKeys[0] ?? 'aquatic_exercise'),
    );
    const [imageFile, setImageFile] = useState<File | null>(null);
    const [removeImage, setRemoveImage] = useState(false);
    const [errors, setErrors] = useState<Record<string, string>>({});
    const [saving, setSaving] = useState(false);

    const breadcrumbs: BreadcrumbItem[] = [{ title: 'Treatment Plans', href: '/interventions' }];

    const isWeekly = activeTab === WEEKLY_TAB;
    const currentProgram = programKeys.includes(activeTab) ? activeTab : programKeys[0];
    const ActiveIcon = programIcon(currentProgram);
    const programLabel = programList[currentProgram] ?? 'Intervention';

    const forAge = useMemo(
        () => interventions.filter((i) => i.age_group === selectedAge).sort((a, b) => a.order_index - b.order_index),
        [interventions, selectedAge],
    );

    const filtered = useMemo(() => forAge.filter((i) => i.posture_type === selectedPosture), [forAge, selectedPosture]);

    const countsFor = (slug: string) =>
        forAge
            .filter((i) => i.posture_type === slug)
            .reduce<Record<string, number>>((acc, item) => {
                acc[item.program] = (acc[item.program] ?? 0) + 1;
                return acc;
            }, {});

    const counts = countsFor(selectedPosture);
    const visible = filtered.filter((item) => item.program === currentProgram);

    // The week the patient will see for this condition.
    const week = useMemo(
        () =>
            weekdays.map((day) => {
                const items = filtered
                    .filter((i) => (i.days ?? []).includes(day))
                    .sort((a, b) => programKeys.indexOf(a.program) - programKeys.indexOf(b.program));
                return { day, items, minutes: items.reduce((sum, i) => sum + (i.duration_minutes ?? 0), 0) };
            }),
        // eslint-disable-next-line react-hooks/exhaustive-deps
        [filtered, weekdays],
    );
    const weekMinutes = week.reduce((sum, d) => sum + d.minutes, 0);
    const activeDays = week.filter((d) => d.items.length).length;

    const conditionList = postureKeys.filter((slug) =>
        postureTypes[slug].toLowerCase().includes(search.trim().toLowerCase()),
    );

    const openCreate = (program = currentProgram) => {
        setForm(emptyInterventionForm(selectedPosture, selectedAge, program));
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

    const update = (key: keyof InterventionFormData, value: unknown) => {
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

        // Multipart is required so the uploaded file reaches the controller.
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
        form.days.forEach((day) => payload.append('days[]', day));
        payload.append('duration_minutes', form.duration_minutes);
        payload.append('image_url', form.image_url);

        if (imageFile) payload.append('image_file', imageFile);
        if (removeImage) payload.append('remove_image', '1');

        router.post(form.id ? '/interventions/update' : '/interventions/store', payload, {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                setDialogOpen(false);
                toast(form.id ? 'Plan item updated' : 'Plan item added');
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
                preserveScroll: true,
                onSuccess: () => toast('Plan item deleted'),
                onError: () => toast('Failed to delete the item'),
            },
        );
    };

    const tabs = [WEEKLY_TAB, ...programKeys];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Treatment Plans" />

            <main className="mt-4 flex-1 p-4 sm:px-6">
                <div className="mb-5 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h1 className="text-2xl font-semibold">Treatment Plans</h1>
                        <p className="max-w-2xl text-sm text-muted-foreground">
                            Every condition the assessment detects gets its own aquatic exercises, massage therapy
                            plan and general exercises. Pick the days for each item and the app builds the
                            patient&apos;s weekly program from them.
                        </p>
                    </div>
                    <div className="flex flex-wrap rounded-lg border bg-card p-0.5">
                        {Object.entries(ageGroups).map(([key, group]) => (
                            <button
                                key={key}
                                type="button"
                                onClick={() => setSelectedAge(key)}
                                className={cn(
                                    'rounded-md px-3 py-1 text-xs font-semibold transition-colors',
                                    selectedAge === key ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:text-foreground',
                                )}
                            >
                                {group.label}
                            </button>
                        ))}
                    </div>
                </div>

                <div className="grid gap-5 lg:grid-cols-[260px_minmax(0,1fr)]">
                    {/* ---------- Conditions ---------- */}
                    <Card className="h-fit gap-3 py-4">
                        <CardHeader className="px-4">
                            <CardTitle className="text-base">Conditions</CardTitle>
                            <div className="relative mt-2">
                                <Search className="absolute top-2.5 left-2.5 h-3.5 w-3.5 text-muted-foreground" />
                                <Input
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    placeholder="Search"
                                    className="h-8 pl-8 text-sm"
                                />
                            </div>
                        </CardHeader>
                        <CardContent className="space-y-1 px-2">
                            {conditionList.map((slug) => {
                                const active = slug === selectedPosture;
                                const c = countsFor(slug);
                                const total = Object.values(c).reduce((a, b) => a + b, 0);
                                return (
                                    <button
                                        key={slug}
                                        type="button"
                                        onClick={() => setSelectedPosture(slug)}
                                        className={cn(
                                            'w-full rounded-lg px-3 py-2 text-left transition-colors',
                                            active ? 'bg-primary text-primary-foreground' : 'hover:bg-muted',
                                            slug === GENERAL_SLUG && 'mt-2 border-t pt-3',
                                        )}
                                    >
                                        <span className="block truncate text-sm font-medium">{postureTypes[slug]}</span>
                                        <span className="mt-1 flex items-center gap-2">
                                            {programKeys.map((p) => (
                                                <span key={p} className="inline-flex items-center gap-1 text-[11px] opacity-90">
                                                    <span className={cn('h-2 w-2 rounded-full', PROGRAM_DOT[p])} />
                                                    {c[p] ?? 0}
                                                </span>
                                            ))}
                                            {total === 0 && (
                                                <span className={cn('text-[10px]', active ? 'opacity-80' : 'text-amber-600')}>
                                                    No plan yet
                                                </span>
                                            )}
                                        </span>
                                    </button>
                                );
                            })}
                        </CardContent>
                    </Card>

                    {/* ---------- Plan ---------- */}
                    <Card className="gap-3 py-4">
                        <CardHeader className="px-5">
                            <div className="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <CardTitle className="text-lg">{postureTypes[selectedPosture] ?? selectedPosture}</CardTitle>
                                    <CardDescription>
                                        {ageGroups[selectedAge]?.label} ·{' '}
                                        {selectedPosture === GENERAL_SLUG
                                            ? 'Shown when the assessment detects no condition'
                                            : 'Shown when the assessment detects this condition'}
                                    </CardDescription>
                                </div>
                                <Button onClick={() => openCreate(isWeekly ? programKeys[0] : currentProgram)} className="gap-2">
                                    <Plus className="h-4 w-4" /> Add {isWeekly ? 'item' : programLabel}
                                </Button>
                            </div>

                            <div className="mt-3 flex flex-wrap gap-1 rounded-lg bg-muted p-1">
                                {tabs.map((key) => {
                                    const Icon = key === WEEKLY_TAB ? CalendarDays : programIcon(key);
                                    const label = key === WEEKLY_TAB ? 'Weekly Program' : programList[key];
                                    return (
                                        <button
                                            key={key}
                                            type="button"
                                            onClick={() => setActiveTab(key)}
                                            className={cn(
                                                'inline-flex items-center gap-2 rounded-md px-3 py-1.5 text-sm font-medium transition-colors',
                                                activeTab === key ? 'bg-background shadow-sm' : 'text-muted-foreground hover:text-foreground',
                                            )}
                                        >
                                            <Icon className="h-4 w-4" />
                                            {label}
                                            {key !== WEEKLY_TAB && (
                                                <span className="rounded-full bg-muted-foreground/10 px-1.5 text-[10px]">
                                                    {counts[key] ?? 0}
                                                </span>
                                            )}
                                        </button>
                                    );
                                })}
                            </div>
                        </CardHeader>

                        <CardContent className="px-5">
                            {isWeekly ? (
                                <>
                                    <div className="mb-3 flex flex-wrap items-center gap-4 text-xs text-muted-foreground">
                                        <span>
                                            <strong className="text-foreground">{activeDays}</strong> active days
                                        </span>
                                        <span>
                                            <strong className="text-foreground">{weekMinutes}</strong> min per week
                                        </span>
                                        <span className="ml-auto flex flex-wrap gap-3">
                                            {programKeys.map((p) => (
                                                <span key={p} className="inline-flex items-center gap-1.5">
                                                    <span className={cn('h-2.5 w-2.5 rounded-sm', PROGRAM_DOT[p])} />
                                                    {programList[p]}
                                                </span>
                                            ))}
                                        </span>
                                    </div>
                                    <div className="grid gap-2 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7">
                                        {week.map(({ day, items, minutes }) => (
                                            <div key={day} className="flex min-h-40 flex-col rounded-xl border bg-muted/30 p-2">
                                                <div className="mb-2 flex items-baseline justify-between px-1">
                                                    <span className="text-sm font-semibold">{dayShort(day)}</span>
                                                    {minutes > 0 && (
                                                        <span className="inline-flex items-center gap-0.5 text-[10px] text-muted-foreground">
                                                            <Clock className="h-3 w-3" /> {minutes}m
                                                        </span>
                                                    )}
                                                </div>
                                                {items.length === 0 ? (
                                                    <div className="flex flex-1 items-center justify-center rounded-lg border border-dashed text-[11px] text-muted-foreground">
                                                        Rest day
                                                    </div>
                                                ) : (
                                                    <div className="space-y-1.5">
                                                        {items.map((item) => (
                                                            <button
                                                                key={item.id}
                                                                type="button"
                                                                onClick={() => openEdit(item)}
                                                                className="flex w-full items-start gap-1.5 rounded-lg border bg-background p-1.5 text-left transition-shadow hover:shadow-sm"
                                                                title="Click to edit"
                                                            >
                                                                <span className={cn('mt-1 h-2 w-2 shrink-0 rounded-full', PROGRAM_DOT[item.program])} />
                                                                <span className="min-w-0">
                                                                    <span className="block text-[11px] leading-tight font-medium">{item.title}</span>
                                                                    {item.duration_minutes ? (
                                                                        <span className="text-[10px] text-muted-foreground">{item.duration_minutes} min</span>
                                                                    ) : null}
                                                                </span>
                                                            </button>
                                                        ))}
                                                    </div>
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                    <p className="mt-3 text-xs text-muted-foreground">
                                        Click an item to change its days. The app shows this week for the patient, combined
                                        across every condition their assessment detects.
                                    </p>
                                </>
                            ) : (
                                <>
                                    <p className="text-xs text-muted-foreground">{PROGRAM_HINTS[currentProgram]}</p>
                                    {visible.length === 0 ? (
                                        <div className="mt-4 flex flex-col items-center gap-3 rounded-lg border border-dashed py-12">
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
                                                    for {postureTypes[selectedPosture]} — {ageGroups[selectedAge]?.label}
                                                </p>
                                            </div>
                                            <Button variant="outline" size="sm" onClick={() => openCreate()} className="gap-2">
                                                <Plus className="h-3.5 w-3.5" /> Add {programLabel}
                                            </Button>
                                        </div>
                                    ) : (
                                        <div className="mt-4 space-y-3">
                                            {visible.map((item) => (
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
                                </>
                            )}
                        </CardContent>
                    </Card>
                </div>

                <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
                    <DialogContent className="max-h-[90vh] overflow-y-auto sm:max-w-2xl">
                        <DialogHeader>
                            <DialogTitle>{form.id ? 'Edit plan item' : 'Add plan item'}</DialogTitle>
                            <DialogDescription>
                                {form.posture_type in postureTypes
                                    ? `${postureTypes[form.posture_type]} — ${ageGroups[form.age_group]?.label}. `
                                    : ''}
                                Set the program, days, prescription and the image the patient will see.
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
