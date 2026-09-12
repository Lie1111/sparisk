import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { useEffect, useMemo, useRef, useState } from 'react';
import { toast } from 'sonner';

import { Badge } from '@/components/ui/badge';
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
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { type BreadcrumbItem } from '@/types';
import { Pencil, Plus, Trash2 } from 'lucide-react';

interface Marker {
    id: number;
    view: string;
    gender: string;
    severity: string;
    type: string;
    color: string | null;
    label: string | null;
    muscle: string | null;
    x: number;
    y: number;
    width: number;
    height: number;
    condition: string | null;
    order_index: number;
    is_active: boolean;
}

type MarkerDraft = Partial<Marker>;

const TYPE_DEFAULT_COLOR: Record<string, string> = {
    tight: '#fde047', // light yellow
    weak: '#16a34a',
    normal: '#94a3b8',
};

const markerColor = (m: { type?: string; color?: string | null }) =>
    m.color && m.color.trim() ? m.color : (TYPE_DEFAULT_COLOR[m.type ?? 'tight'] ?? '#94a3b8');

const VIEW_LABELS: Record<string, string> = {
    front: 'Front View',
    back: 'Back View',
    right_side: 'Right Side',
    left_side: 'Left Side',
};

const clamp = (value: number, min = 0, max = 1) => Math.min(max, Math.max(min, value));

export default function Index({
    markers,
    views,
    genders,
    types,
    severities,
    conditions,
    characterImages,
}: {
    markers: Marker[];
    views: string[];
    genders: string[];
    types: string[];
    severities: string[];
    conditions: Record<string, string>;
    characterImages: Record<'male' | 'female', Record<string, string>>;
}) {
    const [view, setView] = useState<string>('front');
    const [imageGender, setImageGender] = useState<'male' | 'female'>('male');
    const [severity, setSeverity] = useState<string>('normal');
    const [condition, setCondition] = useState<string>('');
    const [local, setLocal] = useState<Marker[]>(markers);

    const [dialogOpen, setDialogOpen] = useState(false);
    const [isEdit, setIsEdit] = useState(false);
    const [draft, setDraft] = useState<MarkerDraft>({});

    const containerRef = useRef<HTMLDivElement>(null);
    const dragIdRef = useRef<number | null>(null);
    const resizeIdRef = useRef<number | null>(null);
    const localRef = useRef<Marker[]>(markers);

    useEffect(() => setLocal(markers), [markers]);
    useEffect(() => {
        localRef.current = local;
    }, [local]);

    // Male and female markers are independent, and each severity result has
    // its own marker set, so only the matching markers are shown/edited.
    const visible = useMemo(
        () =>
            local.filter((m) => {
                if (m.view !== view || m.gender !== imageGender) return false;
                // Condition sets (SATA figures) are separate from severity sets.
                if (condition) return m.condition === condition;
                return !m.condition && m.severity === severity;
            }),
        [local, view, imageGender, severity, condition],
    );

    const imageUrl = characterImages[imageGender]?.[view] ?? '';

    const openAdd = (x: number, y: number) => {
        setDraft({
            view,
            gender: imageGender,
            severity,
            condition: condition || null,
            type: 'tight',
            color: TYPE_DEFAULT_COLOR['tight'],
            label: '',
            muscle: '',
            x,
            y,
            width: 0.12,
            height: 0.12,
            order_index: 0,
            is_active: true,
        });
        setIsEdit(false);
        setDialogOpen(true);
    };

    const openEdit = (m: Marker) => {
        setDraft({ ...m });
        setIsEdit(true);
        setDialogOpen(true);
    };

    const submit = () => {
        const url = isEdit ? '/posture-markers/update' : '/posture-markers/store';
        router.post(url, draft as any, {
            preserveScroll: true,
            onSuccess: () => {
                setDialogOpen(false);
                toast(isEdit ? 'Marker updated' : 'Marker created');
            },
            onError: () => toast('Please check the marker fields'),
        });
    };

    const remove = (id: number) => {
        router.post('/posture-markers/destroy', { id }, { preserveScroll: true, onSuccess: () => toast('Marker deleted') });
    };

    const handleContainerClick = (e: React.MouseEvent<HTMLDivElement>) => {
        if (dragIdRef.current !== null || resizeIdRef.current !== null) return;
        const rect = containerRef.current?.getBoundingClientRect();
        if (!rect) return;
        openAdd(clamp((e.clientX - rect.left) / rect.width), clamp((e.clientY - rect.top) / rect.height));
    };

    const handlePointerMove = (e: React.PointerEvent<HTMLDivElement>) => {
        const rect = containerRef.current?.getBoundingClientRect();
        if (!rect) return;

        // Resizing: keep the marker center fixed and grow/shrink symmetrically.
        if (resizeIdRef.current !== null) {
            const id = resizeIdRef.current;
            const marker = localRef.current.find((m) => m.id === id);
            if (!marker) return;
            const px = (e.clientX - rect.left) / rect.width;
            const py = (e.clientY - rect.top) / rect.height;
            const width = clamp(Math.abs(px - marker.x) * 2, 0.02, 1);
            const height = clamp(Math.abs(py - marker.y) * 2, 0.02, 1);
            setLocal((prev) => prev.map((m) => (m.id === id ? { ...m, width, height } : m)));
            return;
        }

        // Moving.
        if (dragIdRef.current !== null) {
            const x = clamp((e.clientX - rect.left) / rect.width);
            const y = clamp((e.clientY - rect.top) / rect.height);
            setLocal((prev) => prev.map((m) => (m.id === dragIdRef.current ? { ...m, x, y } : m)));
        }
    };

    const handlePointerUp = () => {
        const id = resizeIdRef.current ?? dragIdRef.current;
        const wasResize = resizeIdRef.current !== null;
        resizeIdRef.current = null;
        dragIdRef.current = null;
        if (id === null) return;
        const marker = localRef.current.find((m) => m.id === id);
        if (marker) {
            router.post('/posture-markers/update', marker as any, {
                preserveScroll: true,
                onSuccess: () => wasResize && toast('Marker resized'),
            });
        }
    };

    const breadcrumbs: BreadcrumbItem[] = [{ title: 'Posture Markers', href: '/posture-markers' }];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Posture Markers" />

            <main className="mt-4 flex-1 p-4 sm:px-6">
                <div className="mb-4">
                    <h1 className="text-2xl font-semibold">Posture Markers</h1>
                    <p className="text-sm text-muted-foreground">
                        Place the red (tight) and green (weak) markers on the character images. Click the
                        image to add a marker, drag a marker to move it. The mobile app uses these to point
                        at the patient&apos;s problem areas.
                    </p>
                </div>

                <div className="grid gap-6 lg:grid-cols-[minmax(0,360px)_1fr]">
                    {/* Character image editor */}
                    <Card>
                        <CardHeader>
                            <CardTitle className="text-base">Character Image</CardTitle>
                            <CardDescription>Click to add · drag to reposition</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="mb-3 flex items-center gap-2">
                                <Select value={imageGender} onValueChange={(v) => setImageGender(v as 'male' | 'female')}>
                                    <SelectTrigger className="h-8 w-32">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="male">Male</SelectItem>
                                        <SelectItem value="female">Female</SelectItem>
                                    </SelectContent>
                                </Select>
                                <div className="flex flex-1 gap-1">
                                    {views.map((v) => (
                                        <Button
                                            key={v}
                                            size="sm"
                                            variant={view === v ? 'default' : 'outline'}
                                            className="h-8 flex-1 px-1 text-[10px]"
                                            onClick={() => setView(v)}
                                        >
                                            {VIEW_LABELS[v] ?? v}
                                        </Button>
                                    ))}
                                </div>
                            </div>

                            <div className="mb-3 flex items-center gap-2">
                                <span className="w-16 shrink-0 text-[10px] font-semibold text-muted-foreground uppercase">
                                    Set
                                </span>
                                <Select
                                    value={condition || 'severity'}
                                    onValueChange={(v) => setCondition(v === 'severity' ? '' : v)}
                                >
                                    <SelectTrigger className="h-8 flex-1">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="severity">Severity sets (Normal / Moderate / Severe)</SelectItem>
                                        {Object.entries(conditions).map(([slug, label]) => (
                                            <SelectItem key={slug} value={slug}>
                                                {label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>

                            {!condition && (
                                <div className="mb-3 flex items-center gap-2">
                                    <span className="w-16 shrink-0 text-[10px] font-semibold text-muted-foreground uppercase">
                                        Severity
                                    </span>
                                    <div className="flex flex-1 gap-1">
                                        {severities.map((s) => (
                                            <Button
                                                key={s}
                                                size="sm"
                                                variant={severity === s ? 'default' : 'outline'}
                                                className="h-8 flex-1 px-1 text-[10px] capitalize"
                                                onClick={() => setSeverity(s)}
                                            >
                                                {s}
                                            </Button>
                                        ))}
                                    </div>
                                </div>
                            )}

                            <div
                                ref={containerRef}
                                onClick={handleContainerClick}
                                onPointerMove={handlePointerMove}
                                onPointerUp={handlePointerUp}
                                onPointerLeave={handlePointerUp}
                                className="relative mx-auto w-full max-w-[300px] cursor-crosshair touch-none select-none"
                            >
                                {imageUrl ? (
                                    <img src={imageUrl} alt={view} className="block h-auto w-full" draggable={false} />
                                ) : (
                                    <div className="flex h-[420px] items-center justify-center rounded bg-muted text-sm text-muted-foreground">
                                        No image
                                    </div>
                                )}

                                {visible.map((m) => (
                                    <div
                                        key={m.id}
                                        onPointerDown={(e) => {
                                            e.stopPropagation();
                                            dragIdRef.current = m.id;
                                            (e.target as HTMLElement).setPointerCapture?.(e.pointerId);
                                        }}
                                        onClick={(e) => e.stopPropagation()}
                                        title={m.muscle ?? m.label ?? ''}
                                        className="absolute cursor-move rounded border-2"
                                        style={{
                                            left: `${m.x * 100}%`,
                                            top: `${m.y * 100}%`,
                                            width: `${m.width * 100}%`,
                                            height: `${m.height * 100}%`,
                                            transform: 'translate(-50%, -50%)',
                                            backgroundColor: `${markerColor(m)}99`,
                                            borderColor: markerColor(m),
                                        }}
                                    >
                                        <span className="pointer-events-none absolute -top-4 left-1/2 -translate-x-1/2 whitespace-nowrap rounded bg-black/70 px-1 text-[9px] text-white">
                                            {m.label || m.muscle}
                                        </span>
                                        {/* Resize handle (bottom-right) */}
                                        <span
                                            onPointerDown={(e) => {
                                                e.stopPropagation();
                                                resizeIdRef.current = m.id;
                                                (e.target as HTMLElement).setPointerCapture?.(e.pointerId);
                                            }}
                                            onClick={(e) => e.stopPropagation()}
                                            title="Drag to resize"
                                            className="absolute -right-1.5 -bottom-1.5 h-3 w-3 cursor-nwse-resize rounded-sm border border-white bg-slate-700"
                                        />
                                    </div>
                                ))}
                            </div>
                        </CardContent>
                    </Card>

                    {/* Marker list */}
                    <Card>
                        <CardHeader>
                            <div className="flex items-center justify-between">
                                <div>
                                    <CardTitle className="text-base">
                                        Markers — {VIEW_LABELS[view] ?? view} ({visible.length})
                                    </CardTitle>
                                    <CardDescription className="capitalize">
                                        {imageGender} · {condition ? (conditions[condition] ?? condition) : `${severity} (severity set)`}
                                    </CardDescription>
                                </div>
                                <Button size="sm" className="h-8 gap-1" onClick={() => openAdd(0.5, 0.5)}>
                                    <Plus className="h-3.5 w-3.5" /> Add
                                </Button>
                            </div>
                        </CardHeader>
                        <CardContent>
                            {visible.length === 0 ? (
                                <p className="text-sm text-muted-foreground">No markers for this view yet.</p>
                            ) : (
                                <div className="space-y-2">
                                    {visible.map((m) => (
                                        <div
                                            key={m.id}
                                            className="flex items-center gap-3 rounded-lg border p-2.5"
                                        >
                                            <span
                                                className="inline-block h-4 w-4 shrink-0 rounded border-2"
                                                style={{
                                                    backgroundColor: `${markerColor(m)}99`,
                                                    borderColor: markerColor(m),
                                                }}
                                            />
                                            <div className="min-w-0 flex-1">
                                                <div className="flex items-center gap-2">
                                                    <span className="truncate text-sm font-medium">
                                                        {m.label || m.muscle || 'Marker'}
                                                    </span>
                                                    <Badge variant="outline" className="text-[10px] capitalize">
                                                        {m.type}
                                                    </Badge>
                                                    <Badge variant="secondary" className="text-[10px]">
                                                        {m.gender}
                                                    </Badge>
                                                </div>
                                                <p className="truncate text-xs text-muted-foreground">
                                                    {m.muscle}
                                                </p>
                                            </div>
                                            <Button size="icon" variant="ghost" className="h-8 w-8" onClick={() => openEdit(m)}>
                                                <Pencil className="h-3.5 w-3.5" />
                                            </Button>
                                            <Button
                                                size="icon"
                                                variant="ghost"
                                                className="h-8 w-8 text-destructive"
                                                onClick={() => remove(m.id)}
                                            >
                                                <Trash2 className="h-3.5 w-3.5" />
                                            </Button>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </CardContent>
                    </Card>
                </div>

                <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
                    <DialogContent className="sm:max-w-md">
                        <DialogHeader>
                            <DialogTitle>{isEdit ? 'Edit Marker' : 'Add Marker'}</DialogTitle>
                            <DialogDescription>
                                Normalized position ({draft.x?.toFixed(3)}, {draft.y?.toFixed(3)}).
                            </DialogDescription>
                        </DialogHeader>
                        <div className="grid grid-cols-2 gap-3">
                            <div className="col-span-2">
                                <Label className="text-xs">Muscle / Region</Label>
                                <Input
                                    value={draft.muscle ?? ''}
                                    onChange={(e) => setDraft({ ...draft, muscle: e.target.value })}
                                    placeholder="e.g. Upper Trapezius"
                                />
                            </div>
                            <div className="col-span-2">
                                <Label className="text-xs">Label</Label>
                                <Input
                                    value={draft.label ?? ''}
                                    onChange={(e) => setDraft({ ...draft, label: e.target.value })}
                                    placeholder="Short label shown on the image"
                                />
                            </div>
                            <div>
                                <Label className="text-xs">Type</Label>
                                <Select value={draft.type} onValueChange={(v) => setDraft({ ...draft, type: v })}>
                                    <SelectTrigger>
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {types.map((t) => (
                                            <SelectItem key={t} value={t} className="capitalize">
                                                {t}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                            <div>
                                <Label className="text-xs">Colour</Label>
                                <div className="flex items-center gap-2">
                                    <input
                                        type="color"
                                        value={markerColor(draft)}
                                        onChange={(e) => setDraft({ ...draft, color: e.target.value })}
                                        className="h-9 w-12 shrink-0 cursor-pointer rounded border"
                                        title="Marker colour"
                                    />
                                    <Input
                                        value={draft.color ?? ''}
                                        onChange={(e) => setDraft({ ...draft, color: e.target.value })}
                                        placeholder={TYPE_DEFAULT_COLOR[draft.type ?? 'tight']}
                                    />
                                </div>
                            </div>
                            <div>
                                <Label className="text-xs">Gender</Label>
                                <Select value={draft.gender} onValueChange={(v) => setDraft({ ...draft, gender: v })}>
                                    <SelectTrigger>
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {genders.map((g) => (
                                            <SelectItem key={g} value={g} className="capitalize">
                                                {g}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                            <div>
                                <Label className="text-xs">Severity</Label>
                                <Select
                                    value={draft.severity ?? 'normal'}
                                    onValueChange={(v) => setDraft({ ...draft, severity: v })}
                                >
                                    <SelectTrigger>
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {severities.map((s) => (
                                            <SelectItem key={s} value={s} className="capitalize">
                                                {s}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                            <div>
                                <Label className="text-xs">X (0–1)</Label>
                                <Input
                                    type="number"
                                    step="0.001"
                                    value={draft.x ?? 0}
                                    onChange={(e) => setDraft({ ...draft, x: Number(e.target.value) })}
                                />
                            </div>
                            <div>
                                <Label className="text-xs">Y (0–1)</Label>
                                <Input
                                    type="number"
                                    step="0.001"
                                    value={draft.y ?? 0}
                                    onChange={(e) => setDraft({ ...draft, y: Number(e.target.value) })}
                                />
                            </div>
                            <div>
                                <Label className="text-xs">Width</Label>
                                <Input
                                    type="number"
                                    step="0.01"
                                    value={draft.width ?? 0.12}
                                    onChange={(e) => setDraft({ ...draft, width: Number(e.target.value) })}
                                />
                            </div>
                            <div>
                                <Label className="text-xs">Height</Label>
                                <Input
                                    type="number"
                                    step="0.01"
                                    value={draft.height ?? 0.12}
                                    onChange={(e) => setDraft({ ...draft, height: Number(e.target.value) })}
                                />
                            </div>
                        </div>
                        <DialogFooter>
                            <Button variant="outline" onClick={() => setDialogOpen(false)}>
                                Cancel
                            </Button>
                            <Button onClick={submit}>{isEdit ? 'Save Changes' : 'Create Marker'}</Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </main>
        </AppLayout>
    );
}
