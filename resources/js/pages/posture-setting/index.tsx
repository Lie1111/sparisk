import AppLayout from '@/layouts/app-layout';
import { Head, useForm } from '@inertiajs/react';
import { useMemo } from 'react';
import { toast } from 'sonner';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { type BreadcrumbItem } from '@/types';
import { Save } from 'lucide-react';

interface SettingData {
    id: number;
    view: string;
    section: string;
    label: string;
    age_group: string;
    reference_value: string;
    interpretation: string | null;
}

const VIEW_META: Record<string, { label: string; description: string }> = {
    front: { label: 'Front View', description: 'Frontal plane alignment & symmetry (A1–A11)' },
    back: { label: 'Back View', description: 'Posterior plane alignment & symmetry (B1–B8)' },
    right_side: { label: 'Right Side View', description: 'Sagittal plane posture reference (C1–C7)' },
    left_side: { label: 'Left Side View', description: 'Sagittal plane posture reference (D1–D7)' },
};

export default function Index({
    settings,
    ageGroups,
    severityBands,
}: {
    settings: SettingData[];
    ageGroups: Record<string, { label: string; min: number; max: number | null }>;
    severityBands: { min: number; max: number | null; level: string; label: string; color: string }[];
}) {
    const initial = useMemo(
        () => Object.fromEntries(settings.map((s) => [String(s.id), String(s.reference_value)])),
        [settings],
    );

    const { data, setData, post, processing, transform } = useForm<{ settings: Record<string, string> }>({
        settings: initial,
    });

    transform((form) => ({
        settings: Object.entries(form.settings).map(([id, value]) => ({
            id: Number(id),
            value: Number(value),
        })),
    }));

    // group rows by view then section, keeping the id per age group
    const grouped = useMemo(() => {
        const sectionMap: Record<string, any> = {};
        settings.forEach((s) => {
            const key = `${s.view}:${s.section}`;
            if (!sectionMap[key]) {
                sectionMap[key] = {
                    view: s.view,
                    section: s.section,
                    label: s.label,
                    interpretation: s.interpretation,
                    ids: {} as Record<string, number>,
                };
            }
            sectionMap[key].ids[s.age_group] = s.id;
        });

        const byView: Record<string, any[]> = { front: [], back: [], right_side: [], left_side: [] };
        Object.values(sectionMap).forEach((row: any) => {
            byView[row.view].push(row);
        });

        return byView;
    }, [settings]);

    const updateValue = (id: number, value: string) => {
        setData('settings', { ...data.settings, [String(id)]: value });
    };

    const handleSave = () => {
        post('/posture-settings/update', {
            onSuccess: () => toast('Posture settings updated'),
            onError: () => toast('Failed to update posture settings'),
        });
    };

    const breadcrumbs: BreadcrumbItem[] = [{ title: 'Posture Settings', href: '/posture-settings' }];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Posture Settings" />

            <main className="flex-1 items-start gap-4 p-4 mt-4 sm:px-6 sm:py-0 md:gap-8">
                <div className="mb-4 flex flex-col gap-2">
                    <div>
                        <h1 className="text-2xl font-semibold">Posture Settings by Age</h1>
                        <p className="text-sm text-muted-foreground">
                            SATA Age-Based Posture Reference v1.1 — configure the reference angle for each
                            parameter and age group. The app assesses patients against these values.
                        </p>
                    </div>
                </div>

                <Card className="mb-6">
                    <CardHeader>
                        <CardTitle className="text-base">SATA Fixed Severity Bands</CardTitle>
                        <CardDescription>
                            Deviation = ABS(Clinical Angle − Age Reference). The deviation is mapped to a
                            severity band below.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="flex flex-wrap gap-3">
                            {severityBands.map((band) => (
                                <div
                                    key={band.level}
                                    className={`flex items-center gap-2 rounded-md border px-3 py-1.5 text-sm`}
                                >
                                    <span
                                        className={`inline-block h-3 w-3 rounded-full ${
                                            band.color === 'green'
                                                ? 'bg-green-500'
                                                : band.color === 'yellow'
                                                  ? 'bg-yellow-400'
                                                  : band.color === 'orange'
                                                    ? 'bg-orange-500'
                                                    : 'bg-red-500'
                                        }`}
                                    />
                                    <span className="font-medium">
                                        {band.min}–{band.max ?? '∞'}°
                                    </span>
                                    <Badge variant="secondary">{band.label}</Badge>
                                </div>
                            ))}
                        </div>
                    </CardContent>
                </Card>

                <div className="flex justify-end mb-4">
                    <Button size="sm" className="h-8 gap-1" onClick={handleSave} disabled={processing}>
                        <Save className="h-3.5 w-3.5" />
                        {processing ? 'Saving…' : 'Save Changes'}
                    </Button>
                </div>

                {(['front', 'back', 'right_side', 'left_side'] as const).map((view) => {
                    const rows = grouped[view] ?? [];
                    if (rows.length === 0) return null;
                    const meta = VIEW_META[view];

                    return (
                        <Card key={view} className="mb-6">
                            <CardHeader>
                                <CardTitle className="text-base">{meta.label}</CardTitle>
                                <CardDescription>{meta.description}</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead className="w-[220px]">Parameter</TableHead>
                                            {Object.keys(ageGroups).map((group) => (
                                                <TableHead key={group} className="text-center">
                                                    {ageGroups[group].label}
                                                </TableHead>
                                            ))}
                                            <TableHead className="min-w-[240px]">Interpretation</TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        {rows.map((row: any) => (
                                            <TableRow key={`${row.view}:${row.section}`}>
                                                <TableCell>
                                                    <div className="flex items-center gap-2">
                                                        <Badge variant="outline">{row.section}</Badge>
                                                        <span className="text-sm font-medium">{row.label}</span>
                                                    </div>
                                                </TableCell>
                                                {Object.keys(ageGroups).map((group) => {
                                                    const id = row.ids[group];
                                                    return (
                                                        <TableCell key={group} className="text-center">
                                                            <Input
                                                                type="number"
                                                                step="0.1"
                                                                className="mx-auto w-20 text-center"
                                                                value={data.settings[String(id)] ?? ''}
                                                                onChange={(e) => updateValue(id, e.target.value)}
                                                            />
                                                        </TableCell>
                                                    );
                                                })}
                                                <TableCell>
                                                    <span className="text-xs text-muted-foreground">
                                                        {row.interpretation ?? '—'}
                                                    </span>
                                                </TableCell>
                                            </TableRow>
                                        ))}
                                    </TableBody>
                                </Table>
                            </CardContent>
                        </Card>
                    );
                })}
            </main>
        </AppLayout>
    );
}
