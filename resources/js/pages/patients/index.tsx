import AppLayout from '@/layouts/app-layout';
import { Head, router, useForm } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { toast } from 'sonner';

import { Button } from '@/components/ui/button';
import { ConfirmDialog } from '@/components/confirm-dialog';
import { FormDialog } from '@/components/form-dialog';
import TableWithPagination from '@/components/table-with-pagination';
import { type BreadcrumbItem } from '@/types';
import { PlusCircle } from 'lucide-react';

import { buildActions, buildColumns, PatientForm, usePatientForm, type Patient } from './partials';

export default function Index({ patients, query }: { patients: any; query: string }) {
    const [openForm, setOpenForm] = useState(false);
    const [openDeleteDialog, setOpenDeleteDialog] = useState(false);
    const [formType, setFormType] = useState<'create' | 'update'>('create');
    const [editingPatient, setEditingPatient] = useState<Patient | null>(null);
    const [sortDirection, setSortDirection] = useState<'asc' | 'desc'>('desc');
    const [sortColumn, setSortColumn] = useState<string>('updated_at');

    const { data, setData, post, processing, errors, resetForm, setFromPatient } = usePatientForm();
    const deleteForm = useForm<{ id: number | ''; patients?: number }>({ id: '', patients: patients.current_page });

    useEffect(() => {
        if (!openForm) {
            setEditingPatient(null);
            resetForm();
        }
    }, [openForm, resetForm]);

    const handleSearch = (q: string) => {
        router.get('/patients', { q }, { preserveState: true });
    };

    const handlePageChange = (url: string) => {
        router.get(url, {}, { preserveState: true });
    };

    const handleSort = (column: string) => {
        const newDirection = column === sortColumn && sortDirection === 'asc' ? 'desc' : 'asc';
        setSortDirection(newDirection);
        setSortColumn(column);
        router.get('/patients', { q: query ?? '', sort: column, order: newDirection }, { replace: true, preserveState: true });
    };

    const handleAdd = () => {
        setEditingPatient(null);
        resetForm();
        setData('patients', patients.current_page);
        setFormType('create');
        setOpenForm(true);
    };

    const handleEdit = (patient: Patient) => {
        setEditingPatient(patient);
        setFromPatient(patient);
        setData('patients', patients.current_page);
        setFormType('update');
        setOpenForm(true);
    };

    const handleDelete = (patient: Patient) => {
        deleteForm.setData('id', patient.id);
        deleteForm.setData('patients', patients.current_page);
        setOpenDeleteDialog(true);
    };

    const handleConfirmDelete = () => {
        deleteForm.post('/patients/destroy', {
            onSuccess: () => {
                setOpenDeleteDialog(false);
                toast('Patient deleted');
            },
            onError: () => {
                toast('Failed to delete patient');
            },
        });
    };

    const handleConfirm = (e: any) => {
        e.preventDefault();
        if (!data.name.trim()) {
            toast('Please enter patient name');
            return;
        }
        const url = formType === 'create' ? '/patients/create' : '/patients/update';
        post(url, {
            forceFormData: true,
            onSuccess: () => {
                setOpenForm(false);
                toast(`Patient ${formType === 'create' ? 'created' : 'updated'}`);
            },
        });
    };

    const handleView = (patient: Patient) => {
        router.get(`/patients/${patient.id}`);
    };

    const columns = buildColumns({ onSort: handleSort, onView: handleView });
    const actions = buildActions({ onView: handleView, onEdit: handleEdit, onDelete: handleDelete });

    const breadcrumbs: BreadcrumbItem[] = [{ title: 'Patients', href: '/patients' }];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Patients" />

            <main className="flex-1 items-start gap-4 p-4 mt-4 sm:px-6 sm:py-0 md:gap-8">
                <div className="mb-4 flex items-center justify-end">
                    <Button size="sm" className="h-8 gap-1" onClick={handleAdd}>
                        <PlusCircle className="h-3.5 w-3.5" />
                        <span className="sm:whitespace-nowrap">Register Patient</span>
                    </Button>
                </div>

                <TableWithPagination
                    title="Patients"
                    description="Create, view, update, and delete patients."
                    columns={columns as any}
                    data={patients.data}
                    pagination={{
                        currentPage: patients.current_page,
                        perPage: patients.per_page,
                        total: patients.total,
                        from: patients.from,
                        to: patients.to,
                        nextUrl: patients.next_page_url,
                        prevUrl: patients.prev_page_url,
                    }}
                    actions={actions as any}
                    onSearch={handleSearch}
                    onPageChange={handlePageChange}
                />

                {openForm && (
                    <FormDialog
                        setOpenForm={setOpenForm}
                        openDialog={openForm}
                        setConfirmForm={handleConfirm}
                        title={`${formType === 'create' ? 'Register' : 'Update'} Patient`}
                        confirmLabel={formType === 'create' ? 'Create' : 'Update'}
                        forms={<PatientForm data={data} setData={setData as any} errors={errors as any} />}
                        processing={processing}
                    />
                )}

                {openDeleteDialog && (
                    <ConfirmDialog
                        open={openDeleteDialog}
                        setConfirm={handleConfirmDelete}
                        title="Confirm to delete patient?"
                        setOpen={setOpenDeleteDialog}
                    />
                )}
            </main>
        </AppLayout>
    );
}
