import AppLayout from '@/layouts/app-layout';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { type BreadcrumbItem } from '@/types';
import { ArrowLeft, Pencil, Trash2, Activity, ClipboardList, FileText, Eye } from 'lucide-react';
import { ConfirmDialog } from '@/components/confirm-dialog';
import { FormDialog } from '@/components/form-dialog';
import { useState } from 'react';
import { toast } from 'sonner';
import { PatientForm, usePatientForm } from './partials';

type PatientDetail = {
    id: number;
    name: string;
    photo: string | null;
    age: number | null;
    gender: string | null;
    height: number | null;
    weight: number | null;
    state: string | null;
    diagnosis: string | null;
    emergency_contact_name: string | null;
    emergency_contact_phone: string | null;
    special_needs_type: string | null;
    posture_assessments: any[];
    health_screenings: any[];
    updated_at: string;
    created_at: string;
};

export default function Show({ patient }: { patient: PatientDetail }) {
    const [openDeleteDialog, setOpenDeleteDialog] = useState(false);
    const [openEditDialog, setOpenEditDialog] = useState(false);
    const { data, setData, post, processing } = useForm<{ id: number }>({ id: patient.id });
    const patientForm = usePatientForm();

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Patients', href: '/patients' },
        { title: patient.name, href: `/patients/${patient.id}` },
    ];

    const handleDelete = () => {
        setData('id', patient.id);
        setOpenDeleteDialog(true);
    };

    const handleConfirmDelete = () => {
        post('/patients/destroy', {
            onSuccess: () => {
                setOpenDeleteDialog(false);
                toast('Patient deleted');
                router.get('/patients');
            },
        });
    };

    const handleEdit = () => {
        patientForm.setFromPatient(patient as any);
        setOpenEditDialog(true);
    };

    const handleConfirmUpdate = (e: any) => {
        e.preventDefault();
        if (!patientForm.data.name.trim()) {
            toast('Please enter patient name');
            return;
        }
        patientForm.post('/patients/update', {
            forceFormData: true,
            onSuccess: () => {
                setOpenEditDialog(false);
                toast('Patient updated');
                router.reload({ only: ['patient'] });
            },
        });
    };

    const assessments = patient.posture_assessments || [];
    const screenings = patient.health_screenings || [];

    const needsLabel = (type: string | null) => {
        if (!type || type === 'none') return null;
        return type.replace(/_/g, ' ');
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Patient - ${patient.name}`} />

            <main className="flex-1 items-start gap-4 p-4 mt-4 sm:px-6 sm:py-0 md:gap-8">
                <div className="mb-4 flex items-center justify-between">
                    <Link href="/patients" className="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-foreground">
                        <ArrowLeft className="h-4 w-4" />
                        Back to Patients
                    </Link>
                    <div className="flex items-center gap-2">
                        <Button variant="outline" onClick={handleEdit} className="gap-2">
                            <Pencil className="h-4 w-4" /> Edit
                        </Button>
                        <Button variant="destructive" onClick={handleDelete} className="gap-2" disabled={processing}>
                            <Trash2 className="h-4 w-4" /> Delete
                        </Button>
                    </div>
                </div>

                <Card>
                    <CardContent className="py-6">
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <div className="text-xs text-muted-foreground">Name</div>
                                <div className="font-medium text-lg">{patient.name}</div>
                            </div>
                            <div>
                                <div className="text-xs text-muted-foreground">Age / Gender</div>
                                <div className="font-medium">
                                    {[patient.age ? `${patient.age}y` : null, patient.gender].filter(Boolean).join(' · ') || '-'}
                                </div>
                            </div>
                            <div>
                                <div className="text-xs text-muted-foreground">Height</div>
                                <div className="font-medium">{patient.height ? `${patient.height} cm` : '-'}</div>
                            </div>
                            <div>
                                <div className="text-xs text-muted-foreground">Weight</div>
                                <div className="font-medium">{patient.weight ? `${patient.weight} kg` : '-'}</div>
                            </div>
                            <div>
                                <div className="text-xs text-muted-foreground">State</div>
                                <div className="font-medium">{patient.state || '-'}</div>
                            </div>
                            <div>
                                <div className="text-xs text-muted-foreground">Diagnosis</div>
                                <div className="font-medium">{patient.diagnosis || '-'}</div>
                            </div>
                            <div>
                                <div className="text-xs text-muted-foreground">Special Needs</div>
                                <div className="font-medium">
                                    {needsLabel(patient.special_needs_type) ? (
                                        <Badge variant="secondary">{needsLabel(patient.special_needs_type)}</Badge>
                                    ) : '-'}
                                </div>
                            </div>
                            <div>
                                <div className="text-xs text-muted-foreground">Emergency Contact</div>
                                <div className="font-medium">
                                    {patient.emergency_contact_name || patient.emergency_contact_phone
                                        ? `${patient.emergency_contact_name || ''} ${patient.emergency_contact_phone || ''}`
                                        : '-'}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card className="mt-4">
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2 text-base">
                            <Activity className="h-4 w-4" />
                            Assessments ({assessments.length})
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        {assessments.length === 0 ? (
                            <p className="text-sm text-muted-foreground">No assessments yet. Use the mobile app to perform posture assessments.</p>
                        ) : (
                            <div className="space-y-3">
                                {assessments.map((a: any) => {
                                    return (
                                        <div key={a.id} className="rounded-lg border overflow-hidden">
                                            <div className="flex items-center justify-between gap-3 p-3">
                                                <Link href={`/assessments/${a.id}`} className="flex items-center gap-3 flex-1 min-w-0 hover:bg-transparent">
                                                    <div className={`flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-bold ${
                                                        (a.overall_score || 0) >= 80 ? 'bg-green-100 text-green-700' :
                                                        (a.overall_score || 0) >= 60 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700'
                                                    }`}>
                                                        {a.overall_score ?? '-'}
                                                    </div>
                                                    <div className="min-w-0">
                                                        <span className="font-medium">{a.time_mark}</span>
                                                        <span className="text-sm text-muted-foreground ml-2">{a.assessment_date}</span>
                                                        <div className="mt-1 flex flex-wrap items-center gap-2">
                                                            {a.posture_classification && (
                                                                <Badge variant="outline" className="text-xs">{a.posture_classification}</Badge>
                                                            )}
                                                            {a.overall_progress && (
                                                                <Badge variant="secondary" className="text-xs">{a.overall_progress.replace(/_/g, ' ')}</Badge>
                                                            )}
                                                        </div>
                                                    </div>
                                                </Link>
                                                <div className="flex shrink-0 items-center gap-2">
                                                    <a
                                                        href={`/assessments/${a.id}/word`}
                                                        className="inline-flex items-center gap-1.5 rounded-md border px-2.5 py-1.5 text-xs font-medium hover:bg-muted/50"
                                                    >
                                                        <FileText className="h-3.5 w-3.5" />
                                                        Word
                                                    </a>
                                                    <Link
                                                        href={`/patients/${patient.id}/assessments/${a.id}`}
                                                        className="inline-flex items-center gap-1.5 rounded-md border px-2.5 py-1.5 text-xs font-medium hover:bg-muted/50"
                                                    >
                                                        <Eye className="h-3.5 w-3.5" />
                                                        Details
                                                    </Link>
                                                </div>
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        )}
                    </CardContent>
                </Card>

                <Card className="mt-4">
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2 text-base">
                            <ClipboardList className="h-4 w-4" />
                            Health Screenings ({screenings.length})
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        {screenings.length === 0 ? (
                            <p className="text-sm text-muted-foreground">No health screenings recorded.</p>
                        ) : (
                            <div className="space-y-2">
                                {screenings.map((s: any) => (
                                    <div key={s.id} className="rounded-lg border p-3">
                                        <div className="flex items-center gap-2">
                                            <Badge variant={
                                                s.safety_level === 'low' ? 'destructive' :
                                                s.safety_level === 'medium' ? 'secondary' : 'default'
                                            }>
                                                {s.safety_level?.toUpperCase()}
                                            </Badge>
                                            <span className="text-xs text-muted-foreground">{new Date(s.created_at).toLocaleDateString('en-GB')}</span>
                                        </div>
                                        <div className="mt-2 flex flex-wrap gap-1">
                                            {s.fear_of_water && <Badge variant="outline" className="text-[10px]">Fear of Water</Badge>}
                                            {s.history_of_seizure && <Badge variant="outline" className="text-[10px]">Seizure</Badge>}
                                            {s.heart_disease && <Badge variant="outline" className="text-[10px]">Heart</Badge>}
                                            {s.asthma && <Badge variant="outline" className="text-[10px]">Asthma</Badge>}
                                            {s.neck_pain && <Badge variant="outline" className="text-[10px]">Neck Pain</Badge>}
                                            {s.back_pain && <Badge variant="outline" className="text-[10px]">Back Pain</Badge>}
                                            {s.hip_pain && <Badge variant="outline" className="text-[10px]">Hip Pain</Badge>}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </CardContent>
                </Card>

                {openDeleteDialog && (
                    <ConfirmDialog open={openDeleteDialog} setConfirm={handleConfirmDelete} title="Confirm to delete patient?" setOpen={setOpenDeleteDialog} />
                )}

                {openEditDialog && (
                    <FormDialog
                        setOpenForm={setOpenEditDialog}
                        openDialog={openEditDialog}
                        setConfirmForm={handleConfirmUpdate}
                        title="Update Patient"
                        confirmLabel="Update"
                        forms={<PatientForm data={patientForm.data} setData={patientForm.setData as any} errors={patientForm.errors as any} />}
                        processing={patientForm.processing}
                    />
                )}
            </main>
        </AppLayout>
    );
}
