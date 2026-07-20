import React, { useState, useEffect } from 'react'
import { Head, useForm } from '@inertiajs/react'
import AppLayout from '@/layouts/app-layout'
import { Button } from "@/components/ui/button"
import { toast } from 'sonner'
import { ConfirmDialog } from '@/components/confirm-dialog'
import { router } from '@inertiajs/react'
import { ArrowUpDown } from 'lucide-react'
import { FormDialog } from '@/components/form-dialog'
import Form from './form'
import TableWithPagination from '@/components/table-with-pagination'
import { BreadcrumbItem } from '@/types'

export default function Index({ permissions }: any) {
    const [openPermission, setOpenPermission] = useState(false)
    const [openDeleteDialog, setOpenDeleteDialog] = useState(false)
    const [formType, setFormType] = useState<'create' | 'update'>('create')
    const [sortDirection, setSortDirection] = useState<'asc' | 'desc'>('asc')
    const [sortColumn, setSortColumn] = useState<string>('variant_id')

    const { data, setData, post, processing, errors, reset } = useForm({
        id: '',
        name: ''
    })

    useEffect(() => {
        if (!openPermission) {
            reset()
        }
    }, [openPermission])

    const columns = [
        {
            key: 'name',
            label: <Button
                variant="ghost"
                onClick={() => handleSort('name')}
            >
                Name
                < ArrowUpDown className="ml-2 h-4 w-4" />
            </Button>,
            hidden: false,
            render: (item: any) => (
                item.name
            )
        },
        {
            key: 'updated_at',
            style: 'hidden md:table-cell',
            label: <Button
                variant="ghost"
                onClick={() => handleSort('updated_at')}
            >
                Updated At
                <ArrowUpDown className="ml-2 h-4 w-4" />
            </Button>,
            hidden: false,
            render: (item: any) => (
                new Date(item.updated_at).toLocaleDateString("en-GB")
            )
        },
    ]

    const actions = [
        {
            permission: 'can:update:permission',
            label: 'Update',
            onClick: (item: any) => {
                setData(item)
                setOpenPermission(true)
                setFormType('update')
            },
        },
        {
            permission: 'can:delete:user',
            label: 'Delete',
            onClick: (item: any) => {
                setData(item)
                setOpenDeleteDialog(true)
            },
        },
    ]

    const handleSearch = (query: string) => {
        router.get('/permissions', { q: query }, { preserveState: true })
    }

    const handlePageChange = (url: string) => {
        router.get(url, {}, { preserveState: true })
    }

    const handleAdd = (e: React.MouseEvent) => {
        reset()
        setOpenPermission(true)
        setFormType('create')
    }

    const handleConfirmDelete = () => {
        post(`/permissions/destroy`, {
            onSuccess: () => {
                setOpenDeleteDialog(false)
                toast("Permission Deleted")
            },
        })
    }

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault()
        const url = formType === 'create' ? '/permissions/create' : '/permissions/update'

        post(url, {
            onSuccess: (data: any) => {
                setOpenPermission(false)
                toast(`Permission ${formType === 'create' ? 'Created' : 'Updated'}`)
            },
            onError: (err: any) => {
                console.log(err)
            }
        })
    }

    const handleSort = (column: string) => {
        const newDirection = column === sortColumn && sortDirection === 'asc' ? 'desc' : 'asc'
        setSortDirection(newDirection)
        setSortColumn(column)
        router.get(
            '/permissions',
            {
                sort: column,
                order: newDirection
            },
            {
                replace: true,
                preserveState: true,
            }
        )
    }

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Permission',
            href: '/permissions',
        },
    ];

    return (
        <AppLayout
            breadcrumbs={breadcrumbs}
        >
            <Head title="Permissions" />

            <main className="flex-1 items-start gap-4 p-4 mt-4 sm:px-6 sm:py-0 md:gap-8">
                <TableWithPagination
                    title="Permissions"
                    description="Manage permissions and view their data."
                    columns={columns}
                    data={permissions.data}
                    pagination={{
                        currentPage: permissions.current_page,
                        perPage: permissions.per_page,
                        total: permissions.total,
                        from: permissions.from,
                        to: permissions.to,
                        nextUrl: permissions.next_page_url,
                        prevUrl: permissions.prev_page_url,
                    }}
                    actions={actions}
                    onSearch={handleSearch}
                    onPageChange={handlePageChange}
                    onAdd={handleAdd}
                    addPermission="can:create:permission"
                />

                {openPermission && (

                    <FormDialog
                        setOpenForm={setOpenPermission}
                        openDialog={openPermission}
                        setConfirmForm={handleSubmit}
                        title={`${formType === 'create' ? 'Create' : 'Update'} Permission`}
                        forms={<Form setData={setData} data={data} formType={formType} errors={errors} />}
                        processing={processing}
                    />
                )}

                {openDeleteDialog && (
                    <ConfirmDialog
                        open={openDeleteDialog}
                        setConfirm={handleConfirmDelete}
                        title="Confirm to delete Permission?"
                        setOpen={setOpenDeleteDialog}
                    />
                )}
            </main>
        </AppLayout>
    )
}