import AppLayout from '@/layouts/app-layout';
import { Head, router, useForm } from '@inertiajs/react';
import { useMemo, useState } from 'react';
import { toast } from 'sonner';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
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
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { type BreadcrumbItem } from '@/types';
import { cn } from '@/lib/utils';
import { ChevronsDownUp, ChevronsUpDown, Pencil, RotateCcw, Save, ShieldAlert } from 'lucide-react';

interface SettingData {
    id: number;
    view: string;
    section: string;
    label: string;
    age_group: string;
    sex: string | null;
    reference_value: string | null;
    normal_min: string | null;
    normal_max: string | null;
    reference_source: string | null;
    reference_level: string;
    sex_if_required: boolean;
    interpretation: string | null;
}

interface ReferenceLevelMeta {
    rank: number;
    label: string;
    approved: boolean;
}

interface SettingDraft {
    reference_level: string;
    reference_value: string;
    normal_min: string;
    normal_max: string;
    reference_source: string;
}

interface SeverityBandData {
    id: number;
    level: string;
    min: string | number;
    max: string | number | null;
    label: string;
    color: string;
}

interface BandDraft {
    min: string;
    max: string;
    label: string;
    color: string;
}

interface SectionRow {
    view: string;
    section: string;
    label: string;
    interpretation: string | null;
    sexIfRequired: boolean;
    cells: Record<string, SettingData[]>;
}

const BAND_COLORS = ['green', 'yellow', 'orange', 'red'] as const;

const BAND_DOT: Record<string, string> = {
    green: 'bg-green-500',
    yellow: 'bg-yellow-400',
    orange: 'bg-orange-500',
    red: 'bg-red-500',
};

const VIEW_META: Record<string, { label: string; description: string }> = {
    front: { label: 'Front View', description: 'Frontal plane alignment & symmetry (A1–A11)' },
    back: { label: 'Back View', description: 'Posterior plane alignment & symmetry (B1–B8)' },
    right_side: { label: 'Right Side View', description: 'Sagittal plane posture reference (C1–C7)' },
    left_side: { label: 'Left Side View', description: 'Sagittal plane posture reference (D1–D7)' },
};

const VIEW_ORDER = ['front', 'back', 'right_side', 'left_side'] as const;

/** Badge styling per reference level — approved levels read green/blue, gaps grey. */
const LEVEL_STYLES: Record<string, string> = {
    published: 'border-transparent bg-green-600 text-white',
    sata_validated: 'border-transparent bg-blue-600 text-white',
    individual_baseline: 'border-transparent bg-slate-400 text-white',
};

/** Dot colour per reference level, used in the compact per-parameter summary. */
const LEVEL_DOT: Record<string, string> = {
    published: 'bg-green-600',
    sata_validated: 'bg-blue-600',
    individual_baseline: 'bg-slate-400',
};

const sexLabel = (sex: string | null) => (sex === 'male' ? 'Boys / Men' : sex === 'female' ? 'Girls / Women' : null);

const BAND_BG: Record<string, string> = {
    green: 'bg-green-500',
    yellow: 'bg-yellow-400',
    orange: 'bg-orange-500',
    red: 'bg-red-500',
};

export default function Index({
    settings,
    ageGroups,
    severityBands,
    referenceLevels,
}: {
    settings: SettingData[];
    ageGroups: Record<string, { label: string; min: number; max: number | null }>;
    severityBands: SeverityBandData[];
    referenceLevels: Record<string, ReferenceLevelMeta>;
}) {
    const initial = useMemo(
        () =>
            Object.fromEntries(
                settings.map((s) => [
                    String(s.id),
                    {
                        reference_level: s.reference_level ?? 'individual_baseline',
                        reference_value: s.reference_value === null ? '' : String(s.reference_value),
                        normal_min: s.normal_min === null ? '' : String(s.normal_min),
                        normal_max: s.normal_max === null ? '' : String(s.normal_max),
                        reference_source: s.reference_source ?? '',
                    } satisfies SettingDraft,
                ]),
            ),
        [settings],
    );

    const { data, setData, post, processing, transform } = useForm<{ settings: Record<string, SettingDraft> }>({
        settings: initial,
    });

    transform((form) => ({
        settings: Object.entries(form.settings).map(([id, draft]) => ({
            id: Number(id),
            reference_level: draft.reference_level || null,
            reference_value: draft.reference_value === '' ? null : Number(draft.reference_value),
            normal_min: draft.normal_min === '' ? null : Number(draft.normal_min),
            normal_max: draft.normal_max === '' ? null : Number(draft.normal_max),
            reference_source: draft.reference_source || null,
        })),
    }));

    // --- Layout state: which view tab is open and which parameters are expanded ---
    const [activeView, setActiveView] = useState<string>('front');
    const [openSections, setOpenSections] = useState<Record<string, boolean>>({});

    // --- Severity bands editor state ---
    const [showBands, setShowBands] = useState(false);
    const [bandsOpen, setBandsOpen] = useState(false);
    const [bandDrafts, setBandDrafts] = useState<Record<string, BandDraft>>({});
    const [savingBands, setSavingBands] = useState(false);

    const openBandsEditor = () => {
        const drafts: Record<string, BandDraft> = {};
        severityBands.forEach((band) => {
            drafts[String(band.id)] = {
                min: String(band.min),
                max: band.max === null ? '' : String(band.max),
                label: band.label,
                color: band.color,
            };
        });
        setBandDrafts(drafts);
        setBandsOpen(true);
    };

    const updateBandDraft = (id: number, key: keyof BandDraft, value: string) => {
        setBandDrafts((prev) => ({
            ...prev,
            [String(id)]: { ...prev[String(id)], [key]: value },
        }));
    };

    const saveBands = () => {
        const payload = severityBands
            .map((band) => ({
                id: band.id,
                min: Number(bandDrafts[String(band.id)]?.min),
                max:
                    bandDrafts[String(band.id)]?.max === ''
                        ? null
                        : Number(bandDrafts[String(band.id)]?.max),
                label: bandDrafts[String(band.id)]?.label,
                color: bandDrafts[String(band.id)]?.color,
            }))
            .filter((band) => !Number.isNaN(band.min) && (band.max === null || !Number.isNaN(band.max)));

        setSavingBands(true);
        router.post(
            '/posture-settings/severity-bands',
            { bands: payload },
            {
                onSuccess: () => {
                    setBandsOpen(false);
                    toast('Severity bands updated');
                },
                onError: () => toast('Failed to update severity bands'),
                onFinish: () => setSavingBands(false),
            },
        );
    };

    // Group rows by view then section. Sex-dependent parameters (Pelvic Tilt,
    // Knee) carry a male and a female row for the same age group, so each cell
    // holds a small list of rows instead of one id per age group.
    const grouped = useMemo(() => {
        const sectionMap: Record<string, SectionRow> = {};
        settings.forEach((s) => {
            const key = `${s.view}:${s.section}`;
            if (!sectionMap[key]) {
                sectionMap[key] = {
                    view: s.view,
                    section: s.section,
                    label: s.label,
                    interpretation: s.interpretation,
                    sexIfRequired: s.sex_if_required,
                    cells: {},
                };
            }
            sectionMap[key].sexIfRequired = sectionMap[key].sexIfRequired || s.sex_if_required;
            sectionMap[key].cells[s.age_group] = [...(sectionMap[key].cells[s.age_group] ?? []), s];
        });

        const byView: Record<string, SectionRow[]> = { front: [], back: [], right_side: [], left_side: [] };
        Object.values(sectionMap).forEach((row) => {
            byView[row.view].push(row);
        });

        return byView;
    }, [settings]);

    const draftOf = (id: number): SettingDraft =>
        data.settings[String(id)] ?? {
            reference_level: 'individual_baseline',
            reference_value: '',
            normal_min: '',
            normal_max: '',
            reference_source: '',
        };

    const updateDraft = (id: number, patch: Partial<SettingDraft>) => {
        setData('settings', { ...data.settings, [String(id)]: { ...draftOf(id), ...patch } });
    };

    const changeLevel = (id: number, level: string) => {
        // An evidence gap stores no population reference — clear the fields so
        // the admin never leaves a stale value behind.
        if (level === 'individual_baseline') {
            updateDraft(id, {
                reference_level: level,
                reference_value: '',
                normal_min: '',
                normal_max: '',
                reference_source: '',
            });
            return;
        }
        updateDraft(id, { reference_level: level });
    };

    // Count the reference level of every age-group entry so each parameter card
    // can show a live summary of how it is configured.
    const levelCounts = (row: SectionRow) => {
        const counts: Record<string, number> = { published: 0, sata_validated: 0, individual_baseline: 0 };
        Object.values(row.cells)
            .flat()
            .forEach((s) => {
                const lv = draftOf(s.id).reference_level;
                counts[lv] = (counts[lv] ?? 0) + 1;
            });
        return counts;
    };

    // How many unsaved edits the admin currently has.
    const dirtyCount = useMemo(() => {
        let n = 0;
        for (const id of Object.keys(initial)) {
            const a = initial[id];
            const b = data.settings[id];
            if (!b) continue;
            if (
                a.reference_level !== b.reference_level ||
                a.reference_value !== b.reference_value ||
                a.normal_min !== b.normal_min ||
                a.normal_max !== b.normal_max ||
                a.reference_source !== b.reference_source
            ) {
                n++;
            }
        }
        return n;
    }, [initial, data.settings]);

    const globalStats = useMemo(() => {
        const counts: Record<string, number> = { published: 0, sata_validated: 0, individual_baseline: 0 };
        settings.forEach((s) => {
            const lv = data.settings[String(s.id)]?.reference_level ?? s.reference_level;
            counts[lv] = (counts[lv] ?? 0) + 1;
        });
        return counts;
    }, [settings, data.settings]);

    const handleSave = () => {
        post('/posture-settings/update', {
            onSuccess: () => toast('Posture settings updated'),
            onError: () => toast('Failed to update posture settings'),
        });
    };

    const resetAll = () => {
        setData('settings', initial);
        toast('Unsaved changes discarded');
    };

    const setViewSections = (view: string, open: boolean) => {
        const rows = grouped[view] ?? [];
        const patch: Record<string, boolean> = {};
        rows.forEach((row) => {
            patch[`${row.view}:${row.section}`] = open;
        });
        setOpenSections((prev) => ({ ...prev, ...patch }));
    };

    const breadcrumbs: BreadcrumbItem[] = [{ title: 'Posture Settings', href: '/posture-settings' }];

    const availableViews = VIEW_ORDER.filter((view) => (grouped[view]?.length ?? 0) > 0);
    const resolvedActiveView = availableViews.includes(activeView as (typeof VIEW_ORDER)[number])
        ? activeView
        : (availableViews[0] ?? 'front');

    const statCards = [
        { key: 'published', label: 'Level 1 · Published', value: globalStats.published },
        { key: 'sata_validated', label: 'Level 2 · SATA Validated', value: globalStats.sata_validated },
        { key: 'individual_baseline', label: 'Level 3 · Individual Baseline', value: globalStats.individual_baseline },
        { key: 'total', label: 'Total Entries', value: settings.length },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Posture Settings" />

            <main className="flex-1 items-start gap-4 p-4 pb-24 mt-4 sm:px-6 sm:py-0">
                {/* ==================== HEADER ==================== */}
                <div className="mb-4 flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold">SATA Posture Reference</h1>
                        <p className="mt-1 max-w-3xl text-sm text-muted-foreground">
                            Set the age-based reference for each posture parameter. The app always reports the{' '}
                            <strong>deviation angle</strong> from this reference — never the raw anatomical angle.
                        </p>
                    </div>
                    <div className="flex flex-wrap items-center gap-2 text-xs">
                        {Object.entries(referenceLevels).map(([key, meta]) => (
                            <Badge key={key} className={LEVEL_STYLES[key]}>
                                Level {meta.rank} · {meta.label}
                            </Badge>
                        ))}
                    </div>
                </div>

                {/* ==================== SUMMARY ==================== */}
                <div className="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-4">
                    {statCards.map((stat) => (
                        <div key={stat.key} className="rounded-lg border bg-card p-3">
                            <p className="text-xs text-muted-foreground">{stat.label}</p>
                            <p className="mt-1 text-xl font-semibold tabular-nums">{stat.value}</p>
                        </div>
                    ))}
                </div>

                {/* ==================== EVIDENCE GAP RULE ==================== */}
                <div className="mb-4 flex gap-3 rounded-lg border border-amber-300 bg-amber-50/60 p-3 text-sm text-amber-900">
                    <ShieldAlert className="mt-0.5 h-4 w-4 shrink-0" />
                    <p>
                        <strong>Evidence Gap rule.</strong> When no Level 1 / Level 2 reference exists, choose{' '}
                        <em>Individual Baseline</em> and leave the value empty. The first assessment becomes the
                        baseline and later assessments are reported as change from it. Do not invent a population
                        cut-off such as ±5° or ±10°.
                    </p>
                </div>

                {/* ==================== SEVERITY BANDS ==================== */}
                <Collapsible open={showBands} onOpenChange={setShowBands}>
                    <Card className="mb-4">
                        <CardHeader>
                            <div className="flex items-start justify-between gap-4">
                                <CollapsibleTrigger asChild>
                                    <button type="button" className="flex flex-1 items-start gap-2 text-left">
                                        <ChevronsUpDown className="mt-1 h-4 w-4 shrink-0 text-muted-foreground" />
                                        <div>
                                            <CardTitle className="text-base">SATA Fixed Severity Bands</CardTitle>
                                            <CardDescription>
                                                Deviation = ABS(Clinical Angle − Age Reference), mapped to a band below.
                                            </CardDescription>
                                        </div>
                                    </button>
                                </CollapsibleTrigger>
                                <Button size="sm" variant="outline" className="h-8 shrink-0 gap-1" onClick={openBandsEditor}>
                                    <Pencil className="h-3.5 w-3.5" />
                                    Edit
                                </Button>
                            </div>
                        </CardHeader>
                        <CollapsibleContent>
                            <CardContent>
                                <div className="flex flex-wrap gap-3">
                                    {severityBands.map((band) => (
                                        <div key={band.level} className="flex items-center gap-2 rounded-md border px-3 py-1.5 text-sm">
                                            <span className={cn('inline-block h-3 w-3 rounded-full', BAND_BG[band.color] ?? 'bg-slate-400')} />
                                            <span className="font-medium tabular-nums">
                                                {band.min}–{band.max ?? '∞'}°
                                            </span>
                                            <Badge variant="secondary">{band.label}</Badge>
                                        </div>
                                    ))}
                                </div>
                            </CardContent>
                        </CollapsibleContent>
                    </Card>
                </Collapsible>

                {/* ==================== REFERENCE TABLES (TABS PER VIEW) ==================== */}
                <Tabs value={resolvedActiveView} onValueChange={setActiveView}>
                    <TabsList className="mb-3 flex h-auto w-full flex-wrap justify-start gap-1 p-1">
                        {availableViews.map((view) => (
                            <TabsTrigger key={view} value={view} className="gap-2">
                                {VIEW_META[view].label}
                                <Badge variant="secondary" className="px-1.5 py-0 text-[10px]">
                                    {grouped[view]?.length ?? 0}
                                </Badge>
                            </TabsTrigger>
                        ))}
                    </TabsList>

                    {availableViews.map((view) => {
                        const rows = grouped[view] ?? [];
                        const meta = VIEW_META[view];

                        return (
                            <TabsContent key={view} value={view}>
                                <div className="mb-3 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                                    <div>
                                        <h2 className="text-base font-semibold">{meta.label}</h2>
                                        <p className="text-xs text-muted-foreground">{meta.description}</p>
                                    </div>
                                    <div className="flex gap-2">
                                        <Button size="sm" variant="ghost" className="h-8 gap-1" onClick={() => setViewSections(view, true)}>
                                            <ChevronsUpDown className="h-3.5 w-3.5" />
                                            Expand all
                                        </Button>
                                        <Button size="sm" variant="ghost" className="h-8 gap-1" onClick={() => setViewSections(view, false)}>
                                            <ChevronsDownUp className="h-3.5 w-3.5" />
                                            Collapse all
                                        </Button>
                                    </div>
                                </div>

                                <div className="flex flex-col gap-3">
                                    {rows.map((row) => {
                                        const key = `${row.view}:${row.section}`;
                                        const isOpen = !!openSections[key];
                                        const counts = levelCounts(row);

                                        return (
                                            <Collapsible
                                                key={key}
                                                open={isOpen}
                                                onOpenChange={(v) => setOpenSections((prev) => ({ ...prev, [key]: v }))}
                                            >
                                                <Card className="overflow-hidden">
                                                    <CollapsibleTrigger asChild>
                                                        <button
                                                            type="button"
                                                            className="flex w-full items-center gap-3 p-4 text-left hover:bg-muted/40"
                                                        >
                                                            <Badge variant="outline" className="shrink-0">
                                                                {row.section}
                                                            </Badge>
                                                            <div className="min-w-0 flex-1">
                                                                <div className="flex flex-wrap items-center gap-2">
                                                                    <span className="text-sm font-medium">{row.label}</span>
                                                                    {row.sexIfRequired && (
                                                                        <Badge variant="secondary" className="text-[10px]">
                                                                            Sex-dependent
                                                                        </Badge>
                                                                    )}
                                                                </div>
                                                                {row.interpretation && (
                                                                    <p className="truncate text-xs text-muted-foreground">
                                                                        {row.interpretation}
                                                                    </p>
                                                                )}
                                                            </div>
                                                            <div className="hidden items-center gap-3 sm:flex">
                                                                {(['published', 'sata_validated', 'individual_baseline'] as const).map((lv) =>
                                                                    counts[lv] ? (
                                                                        <span
                                                                            key={lv}
                                                                            className="inline-flex items-center gap-1 text-xs text-muted-foreground tabular-nums"
                                                                        >
                                                                            <span className={cn('h-2 w-2 rounded-full', LEVEL_DOT[lv])} />
                                                                            {counts[lv]}
                                                                        </span>
                                                                    ) : null,
                                                                )}
                                                            </div>
                                                            <ChevronsUpDown
                                                                className={cn(
                                                                    'h-4 w-4 shrink-0 text-muted-foreground transition-transform',
                                                                    isOpen && 'rotate-180',
                                                                )}
                                                            />
                                                        </button>
                                                    </CollapsibleTrigger>

                                                    <CollapsibleContent>
                                                        <div className="border-t bg-muted/20 p-4">
                                                            <div className="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                                                                {Object.keys(ageGroups).map((group) => {
                                                                    const cellRows = row.cells[group] ?? [];
                                                                    return (
                                                                        <div key={group} className="rounded-lg border bg-background p-3">
                                                                            <div className="mb-2 flex items-center justify-between">
                                                                                <span className="text-xs font-semibold">
                                                                                    {ageGroups[group].label}
                                                                                </span>
                                                                                {cellRows.length === 0 && (
                                                                                    <Badge variant="outline" className="text-[10px]">
                                                                                        Not set
                                                                                    </Badge>
                                                                                )}
                                                                            </div>

                                                                            {cellRows.length === 0 ? (
                                                                                <p className="text-xs text-muted-foreground">
                                                                                    No parameter defined for this age group.
                                                                                </p>
                                                                            ) : (
                                                                                <div className="flex flex-col gap-3">
                                                                                    {cellRows.map((setting) => {
                                                                                        const draft = draftOf(setting.id);
                                                                                        const isBaseline =
                                                                                            draft.reference_level === 'individual_baseline';
                                                                                        const sexText = sexLabel(setting.sex);
                                                                                        return (
                                                                                            <div key={setting.id} className="space-y-2">
                                                                                                {sexText && (
                                                                                                    <p className="text-[11px] font-medium text-muted-foreground">
                                                                                                        {sexText}
                                                                                                    </p>
                                                                                                )}

                                                                                                <Select
                                                                                                    value={draft.reference_level}
                                                                                                    onValueChange={(v) => changeLevel(setting.id, v)}
                                                                                                >
                                                                                                    <SelectTrigger className="h-8 w-full text-xs">
                                                                                                        <SelectValue />
                                                                                                    </SelectTrigger>
                                                                                                    <SelectContent>
                                                                                                        {Object.entries(referenceLevels).map(([lvKey, lv]) => (
                                                                                                            <SelectItem key={lvKey} value={lvKey} className="text-xs">
                                                                                                                {lv.label}
                                                                                                            </SelectItem>
                                                                                                        ))}
                                                                                                    </SelectContent>
                                                                                                </Select>

                                                                                                {isBaseline ? (
                                                                                                    <p className="text-[11px] text-muted-foreground">
                                                                                                        No population reference — tracked as change from the
                                                                                                        individual baseline.
                                                                                                    </p>
                                                                                                ) : (
                                                                                                    <div className="space-y-2">
                                                                                                        <div>
                                                                                                            <Label className="text-[11px] text-muted-foreground">
                                                                                                                Reference value (°)
                                                                                                            </Label>
                                                                                                            <Input
                                                                                                                type="number"
                                                                                                                step="0.1"
                                                                                                                className="h-8 text-xs"
                                                                                                                placeholder="e.g. 51.4"
                                                                                                                value={draft.reference_value}
                                                                                                                onChange={(e) =>
                                                                                                                    updateDraft(setting.id, {
                                                                                                                        reference_value: e.target.value,
                                                                                                                    })
                                                                                                                }
                                                                                                            />
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <Label className="text-[11px] text-muted-foreground">
                                                                                                                Published band (°)
                                                                                                            </Label>
                                                                                                            <div className="flex items-center gap-1">
                                                                                                                <Input
                                                                                                                    type="number"
                                                                                                                    step="0.1"
                                                                                                                    className="h-8 text-xs"
                                                                                                                    placeholder="min"
                                                                                                                    value={draft.normal_min}
                                                                                                                    onChange={(e) =>
                                                                                                                        updateDraft(setting.id, {
                                                                                                                            normal_min: e.target.value,
                                                                                                                        })
                                                                                                                    }
                                                                                                                />
                                                                                                                <span className="text-xs text-muted-foreground">–</span>
                                                                                                                <Input
                                                                                                                    type="number"
                                                                                                                    step="0.1"
                                                                                                                    className="h-8 text-xs"
                                                                                                                    placeholder="max"
                                                                                                                    value={draft.normal_max}
                                                                                                                    onChange={(e) =>
                                                                                                                        updateDraft(setting.id, {
                                                                                                                            normal_max: e.target.value,
                                                                                                                        })
                                                                                                                    }
                                                                                                                />
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <Label className="text-[11px] text-muted-foreground">
                                                                                                                Reference source
                                                                                                            </Label>
                                                                                                            <Input
                                                                                                                className="h-8 text-xs"
                                                                                                                placeholder="Citation / study"
                                                                                                                value={draft.reference_source}
                                                                                                                onChange={(e) =>
                                                                                                                    updateDraft(setting.id, {
                                                                                                                        reference_source: e.target.value,
                                                                                                                    })
                                                                                                                }
                                                                                                            />
                                                                                                        </div>
                                                                                                    </div>
                                                                                                )}
                                                                                            </div>
                                                                                        );
                                                                                    })}
                                                                                </div>
                                                                            )}
                                                                        </div>
                                                                    );
                                                                })}
                                                            </div>
                                                        </div>
                                                    </CollapsibleContent>
                                                </Card>
                                            </Collapsible>
                                        );
                                    })}
                                </div>
                            </TabsContent>
                        );
                    })}
                </Tabs>

                {/* ==================== STICKY SAVE BAR ==================== */}
                <div className="sticky bottom-0 z-20 mt-6 flex flex-wrap items-center justify-between gap-3 border-t bg-background/95 py-3 backdrop-blur">
                    <p className="text-xs text-muted-foreground">
                        {dirtyCount > 0 ? (
                            <span className="font-medium text-amber-600">{dirtyCount} unsaved change(s)</span>
                        ) : (
                            'All changes saved'
                        )}
                    </p>
                    <div className="flex gap-2">
                        <Button size="sm" variant="outline" className="h-8 gap-1" onClick={resetAll} disabled={dirtyCount === 0}>
                            <RotateCcw className="h-3.5 w-3.5" />
                            Discard
                        </Button>
                        <Button size="sm" className="h-8 gap-1" onClick={handleSave} disabled={processing || dirtyCount === 0}>
                            <Save className="h-3.5 w-3.5" />
                            {processing ? 'Saving…' : 'Save Changes'}
                        </Button>
                    </div>
                </div>

                {/* ==================== SEVERITY BANDS DIALOG ==================== */}
                <Dialog open={bandsOpen} onOpenChange={setBandsOpen}>
                    <DialogContent className="sm:max-w-xl">
                        <DialogHeader>
                            <DialogTitle>Edit Severity Bands</DialogTitle>
                            <DialogDescription>
                                Adjust the deviation ranges (degrees) for each band. Leave the last band&apos;s
                                max empty for no upper limit.
                            </DialogDescription>
                        </DialogHeader>
                        <div className="space-y-3">
                            {severityBands.map((band) => {
                                const draft = bandDrafts[String(band.id)];
                                return (
                                    <div key={band.id} className="flex items-center gap-3 rounded-md border p-3">
                                        <span className={cn('inline-block h-3 w-3 shrink-0 rounded-full', BAND_DOT[band.color] ?? 'bg-slate-400')} />
                                        <span className="w-24 text-sm font-medium capitalize">{band.level}</span>
                                        <div className="flex-1">
                                            <Label className="text-xs text-muted-foreground">Label</Label>
                                            <Input
                                                value={draft?.label ?? ''}
                                                onChange={(e) => updateBandDraft(band.id, 'label', e.target.value)}
                                            />
                                        </div>
                                        <div className="w-28">
                                            <Label className="text-xs text-muted-foreground">Colour</Label>
                                            <Select
                                                value={draft?.color ?? 'green'}
                                                onValueChange={(v) => updateBandDraft(band.id, 'color', v)}
                                            >
                                                <SelectTrigger>
                                                    <SelectValue />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    {BAND_COLORS.map((c) => (
                                                        <SelectItem key={c} value={c}>
                                                            <span className="flex items-center gap-2 capitalize">
                                                                <span className={cn('inline-block h-3 w-3 rounded-full', BAND_DOT[c])} />
                                                                {c}
                                                            </span>
                                                        </SelectItem>
                                                    ))}
                                                </SelectContent>
                                            </Select>
                                        </div>
                                        <div className="w-20">
                                            <Label className="text-xs text-muted-foreground">Min</Label>
                                            <Input
                                                type="number"
                                                step="0.1"
                                                value={draft?.min ?? ''}
                                                onChange={(e) => updateBandDraft(band.id, 'min', e.target.value)}
                                            />
                                        </div>
                                        <div className="w-20">
                                            <Label className="text-xs text-muted-foreground">Max</Label>
                                            <Input
                                                type="number"
                                                step="0.1"
                                                placeholder="∞"
                                                value={draft?.max ?? ''}
                                                onChange={(e) => updateBandDraft(band.id, 'max', e.target.value)}
                                            />
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                        <DialogFooter>
                            <Button variant="outline" onClick={() => setBandsOpen(false)}>
                                Cancel
                            </Button>
                            <Button onClick={saveBands} disabled={savingBands}>
                                <Save className="h-3.5 w-3.5" />
                                {savingBands ? 'Saving…' : 'Save Changes'}
                            </Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </main>
        </AppLayout>
    );
}
