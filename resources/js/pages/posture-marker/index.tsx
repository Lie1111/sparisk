import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { useEffect, useMemo, useRef, useState } from 'react';
import { toast } from 'sonner';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
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
import { Textarea } from '@/components/ui/textarea';
import { cn } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import {
    Copy,
    FlipHorizontal2,
    ImageUp,
    MousePointerClick,
    Plus,
    RotateCcw,
    Search,
    Trash2,
    X,
} from 'lucide-react';

type Gender = 'male' | 'female';

interface Highlight {
    id: number;
    muscle_condition_id: number;
    gender: Gender;
    view: string;
    muscle: string;
    status: string;
    x: number;
    y: number;
    width: number;
    height: number;
    rotation: number;
    /** Muscle outline inside the box (-0.5..0.5 per axis); null draws an ellipse. */
    points: [number, number][] | null;
    order_index: number;
}

interface Condition {
    id: number;
    slug: string;
    name: string;
    description: string | null;
    effects: string | null;
    detection: { classifications?: string[]; sections?: { section: string; level?: string[]; direction?: string }[] } | null;
    is_active: boolean;
    highlights: Highlight[];
}

interface StatusMeta {
    label: string;
    hint: string;
    color: string;
}

interface ConditionDraft {
    id?: number;
    name: string;
    description: string;
    effects: string;
    classifications: string[];
    is_active: boolean;
}

const GENDER_LABELS: Record<Gender, string> = { male: 'Boy', female: 'Girl' };

const clamp = (value: number, min = 0, max = 1) => Math.min(max, Math.max(min, value));

/** An ellipse as an editable outline, used for new and older highlights. */
const ellipseOutline = (count = 10): [number, number][] =>
    Array.from({ length: count }, (_, i) => {
        const a = (2 * Math.PI * i) / count - Math.PI / 2;
        return [Math.round(Math.cos(a) * 50) / 100, Math.round(Math.sin(a) * 50) / 100];
    });

/**
 * A closed, smooth SVG path through the outline points (Catmull-Rom turned
 * into cubic Béziers), in the -0.5..0.5 box space. The app draws the same
 * curve, so the shape on the admin page is the shape the patient sees.
 */
const smoothPath = (points: [number, number][]) => {
    const n = points.length;
    if (n < 3) return '';
    const p = (i: number) => points[(i + n) % n];
    let d = `M ${p(0)[0]} ${p(0)[1]}`;
    for (let i = 0; i < n; i++) {
        const [p0, p1, p2, p3] = [p(i - 1), p(i), p(i + 1), p(i + 2)];
        const c1 = [p1[0] + (p2[0] - p0[0]) / 6, p1[1] + (p2[1] - p0[1]) / 6];
        const c2 = [p2[0] - (p3[0] - p1[0]) / 6, p2[1] - (p3[1] - p1[1]) / 6];
        d += ` C ${c1[0]} ${c1[1]} ${c2[0]} ${c2[1]} ${p2[0]} ${p2[1]}`;
    }
    return `${d} Z`;
};

const outlineOf = (h: Pick<Highlight, 'points'>) => (h.points && h.points.length >= 3 ? h.points : ellipseOutline());

const draftOf = (c?: Condition): ConditionDraft => ({
    id: c?.id,
    name: c?.name ?? '',
    description: c?.description ?? '',
    effects: c?.effects ?? '',
    classifications: c?.detection?.classifications ?? [],
    is_active: c?.is_active ?? true,
});

type Interaction =
    | { mode: 'move'; id: number; offsetX: number; offsetY: number }
    | { mode: 'resize'; id: number }
    | { mode: 'rotate'; id: number }
    | { mode: 'vertex'; id: number; index: number };

export default function Index({
    conditions,
    statuses,
    views,
    bodyImages,
    customImages,
    classificationOptions,
}: {
    conditions: Condition[];
    statuses: Record<string, StatusMeta>;
    views: Record<string, string>;
    bodyImages: Record<Gender, Record<string, string>>;
    customImages: string[];
    classificationOptions: Record<string, string>;
}) {
    const [conditionId, setConditionId] = useState<number | null>(conditions[0]?.id ?? null);
    const [gender, setGender] = useState<Gender>('male');
    const [view, setView] = useState<string>(Object.keys(views)[0] ?? 'front');
    const [paintStatus, setPaintStatus] = useState<string>('tight');
    const [selectedId, setSelectedId] = useState<number | null>(null);
    const [selectedPoint, setSelectedPoint] = useState<number | null>(null);
    const [search, setSearch] = useState('');

    const [local, setLocal] = useState<Highlight[]>([]);
    const localRef = useRef<Highlight[]>([]);
    const interactionRef = useRef<Interaction | null>(null);
    const movedRef = useRef(false);
    const interactedRef = useRef(false);
    const canvasRef = useRef<HTMLDivElement>(null);
    const fileRef = useRef<HTMLInputElement>(null);
    const selectNewestRef = useRef(false);

    const [conditionDialog, setConditionDialog] = useState(false);
    const [deleteDialog, setDeleteDialog] = useState(false);
    const [newName, setNewName] = useState('');
    const [draft, setDraft] = useState<ConditionDraft>(draftOf(conditions[0]));

    const condition = useMemo(() => conditions.find((c) => c.id === conditionId) ?? null, [conditions, conditionId]);

    // Server props are the source of truth; local copies only carry the shape
    // being dragged until the drop is saved.
    useEffect(() => {
        const all = conditions.flatMap((c) => c.highlights);
        setLocal(all);
        localRef.current = all;

        if (selectNewestRef.current && condition) {
            selectNewestRef.current = false;
            const newest = condition.highlights
                .filter((h) => h.gender === gender && h.view === view)
                .reduce<Highlight | null>((a, b) => (!a || b.id > a.id ? b : a), null);
            setSelectedId(newest?.id ?? null);
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [conditions]);

    useEffect(() => {
        if (!condition && conditions.length) setConditionId(conditions[conditions.length - 1].id);
    }, [condition, conditions]);

    useEffect(() => setDraft(draftOf(condition ?? undefined)), [condition]);
    useEffect(() => setSelectedId(null), [conditionId, gender, view]);
    useEffect(() => setSelectedPoint(null), [selectedId]);

    const visible = useMemo(
        () => local.filter((h) => h.muscle_condition_id === conditionId && h.gender === gender && h.view === view),
        [local, conditionId, gender, view],
    );
    const selected = visible.find((h) => h.id === selectedId) ?? null;

    const muscleSuggestions = useMemo(
        () => Array.from(new Set(local.map((h) => h.muscle))).sort((a, b) => a.localeCompare(b)),
        [local],
    );

    const filteredConditions = conditions.filter((c) => c.name.toLowerCase().includes(search.trim().toLowerCase()));

    const colorOf = (status: string) => statuses[status]?.color ?? '#64748b';
    const imageUrl = bodyImages[gender]?.[view] ?? '';
    const hasCustomImage = customImages.includes(`${gender}.${view}`);

    // ---- saving ----

    const post = (url: string, data: Record<string, unknown>, success?: string, onSuccess?: () => void) =>
        router.post(url, data as Parameters<typeof router.post>[1], {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                if (success) toast(success);
                onSuccess?.();
            },
            onError: (errors) => toast(Object.values(errors)[0] ?? 'Please check the fields'),
        });

    const saveHighlight = (h: Highlight) =>
        post('/posture-markers/highlights/update', { ...h, rotation: Math.round(h.rotation) });

    const patchLocal = (id: number, patch: Partial<Highlight>) => {
        const next = localRef.current.map((h) => (h.id === id ? { ...h, ...patch } : h));
        localRef.current = next;
        setLocal(next);
    };

    const patchAndSave = (id: number, patch: Partial<Highlight>) => {
        patchLocal(id, patch);
        const h = localRef.current.find((m) => m.id === id);
        if (h) saveHighlight(h);
    };

    const addHighlight = (x: number, y: number, extra: Partial<Highlight> = {}) => {
        if (!condition) return;
        selectNewestRef.current = true;
        post('/posture-markers/highlights/store', {
            muscle_condition_id: condition.id,
            gender,
            view,
            muscle: 'New muscle',
            status: paintStatus,
            x,
            y,
            width: 0.06,
            height: 0.08,
            rotation: 0,
            points: ellipseOutline(),
            order_index: visible.length,
            ...extra,
        });
    };

    const mirrorSelected = () => {
        if (!selected) return;
        addHighlight(clamp(1 - selected.x), selected.y, {
            muscle: selected.muscle,
            status: selected.status,
            width: selected.width,
            height: selected.height,
            rotation: -selected.rotation,
            points: outlineOf(selected).map(([px, py]) => [-px, py]),
        });
    };

    /** Adds a point halfway to the next one, after the selected point. */
    const addPoint = () => {
        if (!selected) return;
        const pts = outlineOf(selected);
        const i = selectedPoint ?? pts.length - 1;
        const [a, b] = [pts[i], pts[(i + 1) % pts.length]];
        const next = [...pts.slice(0, i + 1), [(a[0] + b[0]) / 2, (a[1] + b[1]) / 2] as [number, number], ...pts.slice(i + 1)];
        patchAndSave(selected.id, { points: next });
        setSelectedPoint(i + 1);
    };

    const removePoint = () => {
        if (!selected || selectedPoint === null) return;
        const pts = outlineOf(selected);
        if (pts.length <= 3) return;
        patchAndSave(selected.id, { points: pts.filter((_, i) => i !== selectedPoint) });
        setSelectedPoint(null);
    };

    const isSideView = view === 'right_side' || view === 'left_side';
    const otherSide = view === 'right_side' ? 'left_side' : 'right_side';

    const mirrorToOtherSide = () => {
        if (!condition || !isSideView) return;
        post(
            '/posture-markers/highlights/mirror-side',
            { muscle_condition_id: condition.id, gender, from: view },
            `Mirrored to the ${views[otherSide]}`,
        );
    };

    const deleteSelected = () => {
        if (!selected) return;
        post('/posture-markers/highlights/destroy', { id: selected.id }, 'Muscle highlight removed', () => setSelectedId(null));
    };

    // ---- canvas interaction ----

    const pointerToNormalized = (e: React.PointerEvent | React.MouseEvent) => {
        const rect = canvasRef.current!.getBoundingClientRect();
        return { x: (e.clientX - rect.left) / rect.width, y: (e.clientY - rect.top) / rect.height, rect };
    };

    const beginInteraction = (e: React.PointerEvent, interaction: Interaction) => {
        e.stopPropagation();
        e.preventDefault();
        interactionRef.current = interaction;
        movedRef.current = false;
        interactedRef.current = true;
        setSelectedId(interaction.id);
        canvasRef.current?.setPointerCapture?.(e.pointerId);
    };

    const handlePointerMove = (e: React.PointerEvent<HTMLDivElement>) => {
        const interaction = interactionRef.current;
        if (!interaction) return;
        const h = localRef.current.find((m) => m.id === interaction.id);
        if (!h) return;
        const { x, y, rect } = pointerToNormalized(e);
        movedRef.current = true;

        if (interaction.mode === 'move') {
            patchLocal(h.id, { x: clamp(x - interaction.offsetX), y: clamp(y - interaction.offsetY) });
            return;
        }

        // Resize and rotate work in pixels so a rotated shape keeps its form
        // on a non-square image.
        const dx = (x - h.x) * rect.width;
        const dy = (y - h.y) * rect.height;

        if (interaction.mode === 'rotate') {
            const angle = (Math.atan2(dy, dx) * 180) / Math.PI + 90;
            const normalized = ((((angle + 180) % 360) + 360) % 360) - 180;
            patchLocal(h.id, { rotation: Math.round(normalized) });
            return;
        }

        const theta = (-h.rotation * Math.PI) / 180;
        const lx = dx * Math.cos(theta) - dy * Math.sin(theta);
        const ly = dx * Math.sin(theta) + dy * Math.cos(theta);

        // A point is placed in the shape's own box, so it follows the box
        // when the shape is later moved, resized or rotated.
        if (interaction.mode === 'vertex') {
            const pts = [...outlineOf(h)];
            pts[interaction.index] = [
                Math.round(clamp(lx / (h.width * rect.width), -1, 1) * 1000) / 1000,
                Math.round(clamp(ly / (h.height * rect.height), -1, 1) * 1000) / 1000,
            ];
            patchLocal(h.id, { points: pts });
            return;
        }

        patchLocal(h.id, {
            width: clamp((Math.abs(lx) * 2) / rect.width, 0.015, 1),
            height: clamp((Math.abs(ly) * 2) / rect.height, 0.015, 1),
        });
    };

    const handlePointerUp = () => {
        const interaction = interactionRef.current;
        interactionRef.current = null;
        if (!interaction || !movedRef.current) return;
        const h = localRef.current.find((m) => m.id === interaction.id);
        if (h) saveHighlight(h);
    };

    const handleCanvasClick = (e: React.MouseEvent<HTMLDivElement>) => {
        // Pointer capture retargets the click of a shape press to the canvas,
        // so a press that started on a shape never adds a new one.
        if (interactedRef.current) {
            interactedRef.current = false;
            return;
        }
        if (selectedId !== null) {
            setSelectedId(null);
            return;
        }
        const { x, y } = pointerToNormalized(e);
        addHighlight(clamp(x), clamp(y));
    };

    // ---- conditions & images ----

    const saveCondition = () => {
        if (!draft.id) return;
        post(
            '/posture-markers/conditions/update',
            {
                id: draft.id,
                name: draft.name,
                description: draft.description,
                effects: draft.effects,
                detection: { classifications: draft.classifications },
                is_active: draft.is_active,
            },
            'Condition saved',
        );
    };

    const createCondition = () => {
        if (!newName.trim()) return;
        post('/posture-markers/conditions/store', { name: newName.trim(), is_active: true }, 'Condition created', () => {
            setConditionDialog(false);
            setNewName('');
            setConditionId(null);
        });
    };

    const deleteCondition = () => {
        if (!condition) return;
        post('/posture-markers/conditions/destroy', { id: condition.id }, 'Condition deleted', () => {
            setDeleteDialog(false);
            setConditionId(conditions.find((c) => c.id !== condition.id)?.id ?? null);
        });
    };

    const copyToOtherGender = () => {
        if (!condition) return;
        const to: Gender = gender === 'male' ? 'female' : 'male';
        post(
            '/posture-markers/highlights/copy',
            { muscle_condition_id: condition.id, from: gender, to },
            `Copied ${GENDER_LABELS[gender]} highlights to ${GENDER_LABELS[to]}`,
        );
    };

    const uploadImage = (file: File) =>
        router.post(
            '/posture-markers/body-image',
            { gender, view, image: file },
            {
                forceFormData: true,
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => toast('Body image updated'),
                onError: (errors) => toast(Object.values(errors)[0] ?? 'Upload failed'),
            },
        );

    const countsFor = (c: Condition, g: Gender) =>
        Object.keys(statuses).map((s) => ({
            status: s,
            count: new Set(c.highlights.filter((h) => h.gender === g && h.status === s).map((h) => h.muscle)).size,
        }));

    // Muscles on the current view, one row per name + status.
    const muscleRows = useMemo(() => {
        const rows = new Map<string, { muscle: string; status: string; ids: number[] }>();
        for (const h of visible) {
            const key = `${h.status}|${h.muscle}`;
            const row = rows.get(key) ?? { muscle: h.muscle, status: h.status, ids: [] };
            row.ids.push(h.id);
            rows.set(key, row);
        }
        return Array.from(rows.values());
    }, [visible]);

    const breadcrumbs: BreadcrumbItem[] = [{ title: 'Muscle Map', href: '/posture-markers' }];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Muscle Map" />

            <main className="mt-4 flex-1 p-4 sm:px-6">
                <div className="mb-5 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h1 className="text-2xl font-semibold">Muscle Map</h1>
                        <p className="max-w-2xl text-sm text-muted-foreground">
                            Pick a posture condition, then click a muscle on the body to highlight it. The app shows
                            the patient the conditions their assessment matches, with these muscles and the
                            problems written below.
                        </p>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        {Object.entries(statuses).map(([key, s]) => (
                            <span
                                key={key}
                                className="inline-flex items-center gap-2 rounded-full border bg-card px-3 py-1 text-xs font-medium"
                                title={s.hint}
                            >
                                <span className="h-3 w-3 rounded-full" style={{ backgroundColor: s.color }} />
                                {s.label}
                                <span className="hidden text-muted-foreground xl:inline">· {s.hint}</span>
                            </span>
                        ))}
                    </div>
                </div>

                <div className="grid gap-5 xl:grid-cols-[260px_minmax(0,1fr)_340px] lg:grid-cols-[240px_minmax(0,1fr)]">
                    {/* ---------- Conditions ---------- */}
                    <Card className="h-fit gap-3 py-4">
                        <CardHeader className="px-4">
                            <div className="flex items-center justify-between">
                                <CardTitle className="text-base">Conditions</CardTitle>
                                <Button size="sm" variant="outline" className="h-7 gap-1 px-2" onClick={() => setConditionDialog(true)}>
                                    <Plus className="h-3.5 w-3.5" /> New
                                </Button>
                            </div>
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
                            {filteredConditions.map((c) => {
                                const active = c.id === conditionId;
                                return (
                                    <button
                                        key={c.id}
                                        type="button"
                                        onClick={() => setConditionId(c.id)}
                                        className={cn(
                                            'w-full rounded-lg px-3 py-2 text-left transition-colors',
                                            active ? 'bg-primary text-primary-foreground' : 'hover:bg-muted',
                                        )}
                                    >
                                        <div className="flex items-center gap-2">
                                            <span className="flex-1 truncate text-sm font-medium">{c.name}</span>
                                            {!c.is_active && (
                                                <span className={cn('text-[10px] uppercase', active ? 'opacity-80' : 'text-muted-foreground')}>
                                                    Hidden
                                                </span>
                                            )}
                                        </div>
                                        <div className="mt-1 flex gap-2">
                                            {countsFor(c, gender).map(({ status, count }) => (
                                                <span key={status} className="inline-flex items-center gap-1 text-[11px] opacity-90">
                                                    <span className="h-2 w-2 rounded-full" style={{ backgroundColor: colorOf(status) }} />
                                                    {count}
                                                </span>
                                            ))}
                                        </div>
                                    </button>
                                );
                            })}
                            {filteredConditions.length === 0 && (
                                <p className="px-3 py-6 text-center text-sm text-muted-foreground">No conditions found.</p>
                            )}
                        </CardContent>
                    </Card>

                    {/* ---------- Body canvas ---------- */}
                    <Card className="gap-3 py-4">
                        <CardHeader className="px-4">
                            <div className="flex flex-wrap items-center gap-3">
                                <div>
                                    <CardTitle className="text-base">{condition?.name ?? 'No condition selected'}</CardTitle>
                                    <CardDescription>
                                        {selected ? 'Drag the muscle to move · dots to reshape · corner to resize · top dot to rotate' : 'Click on the body to add a muscle'}
                                    </CardDescription>
                                </div>
                                <div className="ml-auto flex rounded-lg border p-0.5">
                                    {(Object.keys(GENDER_LABELS) as Gender[]).map((g) => (
                                        <button
                                            key={g}
                                            type="button"
                                            onClick={() => setGender(g)}
                                            className={cn(
                                                'rounded-md px-3 py-1 text-xs font-semibold transition-colors',
                                                gender === g ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:text-foreground',
                                            )}
                                        >
                                            {GENDER_LABELS[g]}
                                        </button>
                                    ))}
                                </div>
                            </div>

                            <div className="mt-3 flex flex-wrap items-center gap-3">
                                <div className="flex rounded-lg bg-muted p-0.5">
                                    {Object.entries(views).map(([key, label]) => (
                                        <button
                                            key={key}
                                            type="button"
                                            onClick={() => setView(key)}
                                            className={cn(
                                                'rounded-md px-3 py-1 text-xs font-semibold transition-colors',
                                                view === key ? 'bg-background shadow-sm' : 'text-muted-foreground hover:text-foreground',
                                            )}
                                        >
                                            {label}
                                        </button>
                                    ))}
                                </div>
                                <div className="flex items-center gap-1.5">
                                    <MousePointerClick className="h-3.5 w-3.5 text-muted-foreground" />
                                    <span className="text-xs text-muted-foreground">New muscle as</span>
                                    {Object.entries(statuses).map(([key, s]) => (
                                        <button
                                            key={key}
                                            type="button"
                                            onClick={() => setPaintStatus(key)}
                                            className={cn(
                                                'rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-all',
                                                paintStatus === key ? 'text-white shadow-sm' : 'bg-background',
                                            )}
                                            style={
                                                paintStatus === key
                                                    ? { backgroundColor: s.color, borderColor: s.color }
                                                    : { color: s.color, borderColor: `${s.color}66` }
                                            }
                                        >
                                            {s.label}
                                        </button>
                                    ))}
                                </div>
                            </div>
                        </CardHeader>

                        <CardContent className="px-4">
                            <div className="rounded-xl bg-gradient-to-b from-slate-50 to-slate-100 p-4 dark:from-slate-900 dark:to-slate-950">
                                <div
                                    ref={canvasRef}
                                    onClick={condition ? handleCanvasClick : undefined}
                                    onPointerMove={handlePointerMove}
                                    onPointerUp={handlePointerUp}
                                    onPointerCancel={handlePointerUp}
                                    className={cn(
                                        'relative mx-auto w-full max-w-[380px] touch-none select-none',
                                        condition ? 'cursor-crosshair' : 'cursor-not-allowed opacity-60',
                                    )}
                                >
                                    {imageUrl ? (
                                        <img src={imageUrl} alt={`${GENDER_LABELS[gender]} ${views[view]}`} className="block h-auto w-full" draggable={false} />
                                    ) : (
                                        <div className="flex h-[520px] items-center justify-center rounded bg-muted text-sm text-muted-foreground">
                                            No body image
                                        </div>
                                    )}

                                    {visible.map((h) => {
                                        const isSelected = h.id === selectedId;
                                        const color = colorOf(h.status);
                                        const outline = outlineOf(h);
                                        return (
                                            <div
                                                key={h.id}
                                                className="pointer-events-none absolute"
                                                style={{
                                                    left: `${h.x * 100}%`,
                                                    top: `${h.y * 100}%`,
                                                    width: `${h.width * 100}%`,
                                                    height: `${h.height * 100}%`,
                                                    transform: `translate(-50%, -50%) rotate(${h.rotation}deg)`,
                                                    zIndex: isSelected ? 20 : 10,
                                                }}
                                            >
                                                {/* Only the muscle itself takes clicks, so a shape never blocks the body around it. */}
                                                <svg
                                                    viewBox="-0.5 -0.5 1 1"
                                                    preserveAspectRatio="none"
                                                    className="absolute inset-0 h-full w-full overflow-visible"
                                                    style={{ mixBlendMode: 'multiply' }}
                                                >
                                                    <path
                                                        d={smoothPath(outline)}
                                                        fill={color}
                                                        fillOpacity={0.62}
                                                        stroke={color}
                                                        strokeWidth={1}
                                                        vectorEffect="non-scaling-stroke"
                                                        className="pointer-events-auto cursor-move"
                                                        onPointerDown={(e) => {
                                                            const { x, y } = pointerToNormalized(e);
                                                            beginInteraction(e, { mode: 'move', id: h.id, offsetX: x - h.x, offsetY: y - h.y });
                                                        }}
                                                        onClick={(e) => e.stopPropagation()}
                                                    >
                                                        <title>{`${h.muscle} · ${statuses[h.status]?.label ?? h.status}`}</title>
                                                    </path>
                                                </svg>
                                                {isSelected && (
                                                    <>
                                                        <div className="absolute inset-0 border border-dashed border-sky-500/70" />
                                                        <span className="absolute -top-3 left-1/2 h-3 w-px -translate-x-1/2 bg-sky-500" />
                                                        <span
                                                            onPointerDown={(e) => beginInteraction(e, { mode: 'rotate', id: h.id })}
                                                            title="Drag to rotate"
                                                            className="pointer-events-auto absolute -top-5 left-1/2 h-3 w-3 -translate-x-1/2 cursor-grab rounded-full border-2 border-white bg-sky-500 shadow"
                                                        />
                                                        <span
                                                            onPointerDown={(e) => beginInteraction(e, { mode: 'resize', id: h.id })}
                                                            title="Drag to resize"
                                                            className="pointer-events-auto absolute -right-1.5 -bottom-1.5 h-3 w-3 cursor-nwse-resize rounded-sm border-2 border-white bg-sky-500 shadow"
                                                        />
                                                        {outline.map(([px, py], index) => (
                                                            <span
                                                                key={index}
                                                                onPointerDown={(e) => {
                                                                    setSelectedPoint(index);
                                                                    beginInteraction(e, { mode: 'vertex', id: h.id, index });
                                                                }}
                                                                onClick={(e) => e.stopPropagation()}
                                                                title="Drag to reshape"
                                                                className={cn(
                                                                    'pointer-events-auto absolute h-2.5 w-2.5 -translate-x-1/2 -translate-y-1/2 cursor-crosshair rounded-full border border-white shadow',
                                                                    selectedPoint === index ? 'bg-amber-400' : 'bg-sky-600',
                                                                )}
                                                                style={{ left: `${(px + 0.5) * 100}%`, top: `${(py + 0.5) * 100}%` }}
                                                            />
                                                        ))}
                                                    </>
                                                )}
                                            </div>
                                        );
                                    })}
                                </div>
                            </div>

                            <div className="mt-3 flex flex-wrap items-center gap-2">
                                <input
                                    ref={fileRef}
                                    type="file"
                                    accept="image/*"
                                    className="hidden"
                                    onChange={(e) => {
                                        const file = e.target.files?.[0];
                                        if (file) uploadImage(file);
                                        e.target.value = '';
                                    }}
                                />
                                <Button size="sm" variant="outline" className="h-8 gap-1.5" onClick={() => fileRef.current?.click()}>
                                    <ImageUp className="h-3.5 w-3.5" /> Upload {GENDER_LABELS[gender].toLowerCase()} {views[view]?.toLowerCase()} image
                                </Button>
                                {hasCustomImage && (
                                    <Button
                                        size="sm"
                                        variant="ghost"
                                        className="h-8 gap-1.5"
                                        onClick={() => post('/posture-markers/body-image/reset', { gender, view }, 'Body image reset')}
                                    >
                                        <RotateCcw className="h-3.5 w-3.5" /> Use default
                                    </Button>
                                )}
                                {isSideView && (
                                    <Button
                                        size="sm"
                                        variant="ghost"
                                        className="ml-auto h-8 gap-1.5"
                                        disabled={!condition}
                                        onClick={mirrorToOtherSide}
                                        title={`Replaces the ${views[otherSide]} highlights with a mirror of this side`}
                                    >
                                        <FlipHorizontal2 className="h-3.5 w-3.5" /> Mirror to {views[otherSide]}
                                    </Button>
                                )}
                                <Button
                                    size="sm"
                                    variant="ghost"
                                    className={cn('h-8 gap-1.5', !isSideView && 'ml-auto')}
                                    disabled={!condition}
                                    onClick={copyToOtherGender}
                                    title="Replaces the other gender's highlights for this condition"
                                >
                                    <Copy className="h-3.5 w-3.5" /> Copy to {GENDER_LABELS[gender === 'male' ? 'female' : 'male']}
                                </Button>
                            </div>
                        </CardContent>
                    </Card>

                    {/* ---------- Inspector ---------- */}
                    <div className="space-y-5 lg:col-span-2 xl:col-span-1">
                        {selected ? (
                            <Card className="gap-3 border-sky-200 py-4 dark:border-sky-900">
                                <CardHeader className="px-4">
                                    <div className="flex items-center justify-between">
                                        <CardTitle className="text-base">Muscle highlight</CardTitle>
                                        <Button size="icon" variant="ghost" className="h-7 w-7" onClick={() => setSelectedId(null)}>
                                            <X className="h-4 w-4" />
                                        </Button>
                                    </div>
                                </CardHeader>
                                <CardContent className="space-y-4 px-4">
                                    <div>
                                        <Label className="text-xs">Muscle name</Label>
                                        <Input
                                            key={selected.id}
                                            list="muscle-names"
                                            defaultValue={selected.muscle}
                                            autoFocus={selected.muscle === 'New muscle'}
                                            onFocus={(e) => selected.muscle === 'New muscle' && e.target.select()}
                                            onBlur={(e) => {
                                                const muscle = e.target.value.trim();
                                                if (muscle && muscle !== selected.muscle) patchAndSave(selected.id, { muscle });
                                            }}
                                            onKeyDown={(e) => e.key === 'Enter' && (e.target as HTMLInputElement).blur()}
                                        />
                                        <datalist id="muscle-names">
                                            {muscleSuggestions.map((m) => (
                                                <option key={m} value={m} />
                                            ))}
                                        </datalist>
                                    </div>

                                    <div>
                                        <Label className="text-xs">Status</Label>
                                        <div className="mt-1 grid grid-cols-3 gap-1.5">
                                            {Object.entries(statuses).map(([key, s]) => (
                                                <button
                                                    key={key}
                                                    type="button"
                                                    onClick={() => patchAndSave(selected.id, { status: key })}
                                                    className={cn(
                                                        'rounded-lg border-2 px-2 py-2 text-xs font-semibold transition-all',
                                                        selected.status === key ? 'text-white' : 'bg-background',
                                                    )}
                                                    style={
                                                        selected.status === key
                                                            ? { backgroundColor: s.color, borderColor: s.color }
                                                            : { color: s.color, borderColor: `${s.color}40` }
                                                    }
                                                >
                                                    {s.label}
                                                </button>
                                            ))}
                                        </div>
                                        <p className="mt-1 text-[11px] text-muted-foreground">{statuses[selected.status]?.hint}</p>
                                    </div>

                                    {(
                                        [
                                            ['width', 'Width', 0.015, 0.5, 0.005],
                                            ['height', 'Height', 0.015, 0.5, 0.005],
                                            ['rotation', 'Rotation', -180, 180, 1],
                                        ] as const
                                    ).map(([field, label, min, max, step]) => (
                                        <div key={field}>
                                            <div className="flex items-center justify-between">
                                                <Label className="text-xs">{label}</Label>
                                                <span className="text-xs text-muted-foreground tabular-nums">
                                                    {field === 'rotation' ? `${selected.rotation}°` : `${Math.round(selected[field] * 1000) / 10}%`}
                                                </span>
                                            </div>
                                            <input
                                                type="range"
                                                min={min}
                                                max={max}
                                                step={step}
                                                value={selected[field]}
                                                onChange={(e) => patchLocal(selected.id, { [field]: Number(e.target.value) })}
                                                onPointerUp={() => saveHighlight(localRef.current.find((h) => h.id === selected.id)!)}
                                                onKeyUp={() => saveHighlight(localRef.current.find((h) => h.id === selected.id)!)}
                                                className="mt-1 w-full accent-sky-500"
                                            />
                                        </div>
                                    ))}

                                    <div>
                                        <Label className="text-xs">Outline points</Label>
                                        <div className="mt-1 flex gap-2">
                                            <Button size="sm" variant="outline" className="flex-1 gap-1.5" onClick={addPoint}>
                                                <Plus className="h-3.5 w-3.5" /> Add point
                                            </Button>
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                className="flex-1 gap-1.5"
                                                disabled={selectedPoint === null || outlineOf(selected).length <= 3}
                                                onClick={removePoint}
                                            >
                                                <X className="h-3.5 w-3.5" /> Remove point
                                            </Button>
                                        </div>
                                        <p className="mt-1 text-[11px] text-muted-foreground">
                                            Drag the dots on the body to follow the muscle. Click a dot first to add a point after it or remove it.
                                        </p>
                                    </div>

                                    <div className="flex gap-2 pt-1">
                                        <Button size="sm" variant="outline" className="flex-1 gap-1.5" onClick={mirrorSelected}>
                                            <FlipHorizontal2 className="h-3.5 w-3.5" /> Copy mirrored
                                        </Button>
                                        <Button size="sm" variant="outline" className="gap-1.5 text-destructive" onClick={deleteSelected}>
                                            <Trash2 className="h-3.5 w-3.5" /> Delete
                                        </Button>
                                    </div>
                                </CardContent>
                            </Card>
                        ) : (
                            <Card className="gap-3 py-4">
                                <CardHeader className="px-4">
                                    <CardTitle className="text-base">
                                        Muscles · {GENDER_LABELS[gender]} {views[view]}
                                    </CardTitle>
                                    <CardDescription>Select a muscle to edit it on the body.</CardDescription>
                                </CardHeader>
                                <CardContent className="space-y-3 px-4">
                                    {muscleRows.length === 0 && (
                                        <p className="rounded-lg border border-dashed p-4 text-center text-sm text-muted-foreground">
                                            No muscles on this view yet.
                                        </p>
                                    )}
                                    {Object.entries(statuses).map(([status, s]) => {
                                        const rows = muscleRows.filter((r) => r.status === status);
                                        if (!rows.length) return null;
                                        return (
                                            <div key={status}>
                                                <p className="mb-1 text-[11px] font-semibold tracking-wide uppercase" style={{ color: s.color }}>
                                                    {s.label}
                                                </p>
                                                <div className="flex flex-wrap gap-1.5">
                                                    {rows.map((r) => (
                                                        <button
                                                            key={r.muscle}
                                                            type="button"
                                                            onClick={() => setSelectedId(r.ids[0])}
                                                            className="rounded-full border px-2.5 py-1 text-xs transition-colors hover:bg-muted"
                                                            style={{ borderColor: `${s.color}55` }}
                                                        >
                                                            {r.muscle}
                                                            {r.ids.length > 1 && <span className="ml-1 text-muted-foreground">×{r.ids.length}</span>}
                                                        </button>
                                                    ))}
                                                </div>
                                            </div>
                                        );
                                    })}
                                </CardContent>
                            </Card>
                        )}

                        {condition && (
                            <Card className="gap-3 py-4">
                                <CardHeader className="px-4">
                                    <CardTitle className="text-base">Patient wording</CardTitle>
                                    <CardDescription>Shown in the app when this condition is detected.</CardDescription>
                                </CardHeader>
                                <CardContent className="space-y-3 px-4">
                                    <div>
                                        <Label className="text-xs">Condition name</Label>
                                        <Input value={draft.name} onChange={(e) => setDraft({ ...draft, name: e.target.value })} />
                                    </div>
                                    <div>
                                        <Label className="text-xs">What is it?</Label>
                                        <Textarea
                                            rows={3}
                                            value={draft.description}
                                            onChange={(e) => setDraft({ ...draft, description: e.target.value })}
                                        />
                                    </div>
                                    <div>
                                        <Label className="text-xs">Problems the patient may notice (one per line)</Label>
                                        <Textarea
                                            rows={4}
                                            value={draft.effects}
                                            onChange={(e) => setDraft({ ...draft, effects: e.target.value })}
                                        />
                                    </div>
                                    <div>
                                        <Label className="text-xs">Detect when the assessment is classified as</Label>
                                        <div className="mt-1 flex flex-wrap gap-1.5">
                                            {Object.entries(classificationOptions).map(([key, label]) => {
                                                const on = draft.classifications.includes(key);
                                                return (
                                                    <button
                                                        key={key}
                                                        type="button"
                                                        onClick={() =>
                                                            setDraft({
                                                                ...draft,
                                                                classifications: on
                                                                    ? draft.classifications.filter((k) => k !== key)
                                                                    : [...draft.classifications, key],
                                                            })
                                                        }
                                                        className={cn(
                                                            'rounded-full border px-2.5 py-0.5 text-xs transition-colors',
                                                            on ? 'border-primary bg-primary text-primary-foreground' : 'hover:bg-muted',
                                                        )}
                                                    >
                                                        {label}
                                                    </button>
                                                );
                                            })}
                                        </div>
                                        {(condition.detection?.sections?.length ?? 0) > 0 && (
                                            <p className="mt-2 text-[11px] text-muted-foreground">
                                                Also detected from measurements:{' '}
                                                {condition.detection!.sections!.map((s) => s.section).join(', ')}
                                            </p>
                                        )}
                                        {draft.classifications.length === 0 && (condition.detection?.sections?.length ?? 0) === 0 && (
                                            <p className="mt-2 text-[11px] text-amber-600">
                                                No detection rule — the app will not show this condition yet.
                                            </p>
                                        )}
                                    </div>
                                    <label className="flex items-center gap-2 text-sm">
                                        <Checkbox
                                            checked={draft.is_active}
                                            onCheckedChange={(v) => setDraft({ ...draft, is_active: v === true })}
                                        />
                                        Show in the app
                                    </label>
                                    <div className="flex gap-2 pt-1">
                                        <Button size="sm" className="flex-1" onClick={saveCondition}>
                                            Save changes
                                        </Button>
                                        <Button size="sm" variant="ghost" className="text-destructive" onClick={() => setDeleteDialog(true)}>
                                            <Trash2 className="h-3.5 w-3.5" />
                                        </Button>
                                    </div>
                                    <div className="flex flex-wrap gap-1 pt-1">
                                        {countsFor(condition, gender).map(({ status, count }) => (
                                            <Badge key={status} variant="outline" className="gap-1 text-[10px]">
                                                <span className="h-2 w-2 rounded-full" style={{ backgroundColor: colorOf(status) }} />
                                                {count} {statuses[status].label}
                                            </Badge>
                                        ))}
                                    </div>
                                </CardContent>
                            </Card>
                        )}
                    </div>
                </div>

                <Dialog open={conditionDialog} onOpenChange={setConditionDialog}>
                    <DialogContent className="sm:max-w-sm">
                        <DialogHeader>
                            <DialogTitle>New condition</DialogTitle>
                            <DialogDescription>Add the wording and muscles after creating it.</DialogDescription>
                        </DialogHeader>
                        <Input
                            autoFocus
                            value={newName}
                            onChange={(e) => setNewName(e.target.value)}
                            onKeyDown={(e) => e.key === 'Enter' && createCondition()}
                            placeholder="e.g. Forward Head Posture"
                        />
                        <DialogFooter>
                            <Button variant="outline" onClick={() => setConditionDialog(false)}>
                                Cancel
                            </Button>
                            <Button onClick={createCondition}>Create</Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>

                <Dialog open={deleteDialog} onOpenChange={setDeleteDialog}>
                    <DialogContent className="sm:max-w-sm">
                        <DialogHeader>
                            <DialogTitle>Delete {condition?.name}?</DialogTitle>
                            <DialogDescription>
                                This removes the condition and all of its boy and girl muscle highlights.
                            </DialogDescription>
                        </DialogHeader>
                        <DialogFooter>
                            <Button variant="outline" onClick={() => setDeleteDialog(false)}>
                                Cancel
                            </Button>
                            <Button variant="destructive" onClick={deleteCondition}>
                                Delete
                            </Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </main>
        </AppLayout>
    );
}
