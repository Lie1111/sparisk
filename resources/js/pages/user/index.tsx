import React, { useState, useEffect } from 'react'
import { Head, useForm } from '@inertiajs/react'
import AuthLayout from '@/layouts/auth-layout'
import { Button } from "@/components/ui/button"
import { toast } from 'sonner'
// import { UserDialog } from "./UserDialog"
import { ConfirmDialog } from '@/components/confirm-dialog'
import { router } from '@inertiajs/react'
import { ArrowUpDown } from 'lucide-react'
import { FormDialog } from '@/components/form-dialog'
import Form from './form'
import TableWithPagination from '@/components/table-with-pagination'
import AppLayout from '@/layouts/app-layout'
import { BreadcrumbItem } from '@/types'

export default function Index({ users, roles }: any) {
    const [openUser, setOpenUser] = useState(false)
    const [openDeleteDialog, setOpenDeleteDialog] = useState(false)
    const [formType, setFormType] = useState<'create' | 'update'>('create')
    const [sortDirection, setSortDirection] = useState<'asc' | 'desc'>('asc')
    const [sortColumn, setSortColumn] = useState<string>('variant_id')
    const [openAudit, setOpenAudit] = useState(false);
    const [auditData, setAuditData] = useState(null);

    const { data, setData, post, processing, errors, reset } = useForm({
        id: '',
        email: '',
        name: '',
        role: '',
    })

    useEffect(() => {
        if (!openUser) {
            reset()
        }
    }, [openUser])

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
            key: 'email',
            label: <Button
                variant="ghost"
                onClick={() => handleSort('email')}
            >
                Email
                <ArrowUpDown className="ml-2 h-4 w-4" />
            </Button>,
            hidden: false,
            render: (item: any) => (
                item.email
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
            permission: 'can:view:audit',
            label: 'Audit',
            onClick: (item: any) => {
                setOpenAudit(true);
                setAuditData(item)
            },
        },
        {
            permission: 'can:update:user',
            label: 'Update',
            onClick: (item: any) => {
                setData(item)
                setOpenUser(true)
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
        router.get('/users', { q: query }, { preserveState: true })
    }

    const handlePageChange = (url: string) => {
        router.get(url, {}, { preserveState: true })
    }

    const handleAdd = (e: React.MouseEvent) => {
        reset()
        setOpenUser(true)
        setFormType('create')
    }

    const handleConfirmDelete = () => {
        post(`/users/destroy`, {
            onSuccess: () => {
                setOpenDeleteDialog(false)
                toast('User Deleted')
            },
        })
    }

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault()
        const url = formType === 'create' ? '/users/create' : '/users/update'

        post(url, {
            onSuccess: (data: any) => {
                setOpenUser(false)
                toast(`User ${formType === 'create' ? 'Created' : 'Updated'}`)
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
            '/users',
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
            title: 'User',
            href: '/users',
        },
    ];

    return (
        <AppLayout
            breadcrumbs={breadcrumbs}
        >
            <Head title="Users" />

            <main className="flex-1 items-start gap-4 p-4 mt-4 sm:px-6 sm:py-0 md:gap-8">
                <TableWithPagination
                    title="Users"
                    description="Manage users and view their data."
                    columns={columns}
                    data={users.data}
                    pagination={{
                        currentPage: users.current_page,
                        perPage: users.per_page,
                        total: users.total,
                        from: users.from,
                        to: users.to,
                        nextUrl: users.next_page_url,
                        prevUrl: users.prev_page_url,
                    }}
                    actions={actions}
                    onSearch={handleSearch}
                    onPageChange={handlePageChange}
                    onAdd={handleAdd}
                    addPermission="can:create:user"
                />

                {openUser && (

                    <FormDialog
                        setOpenForm={setOpenUser}
                        openDialog={openUser}
                        setConfirmForm={handleSubmit}
                        title={`${formType === 'create' ? 'Create' : 'Update'} User`}
                        forms={<Form setData={setData} data={data} formType={formType} errors={errors} allRoles={roles} />}
                        processing={processing}
                    />
                )}

                {openDeleteDialog && (
                    <ConfirmDialog
                        open={openDeleteDialog}
                        setConfirm={handleConfirmDelete}
                        title="Confirm to delete user?"
                        setOpen={setOpenDeleteDialog}
                    />
                )}

                {/* {openAudit && (
                    <AuditDialog
                        openAudit={openAudit}
                        title="User Audits"
                        setOpenAudit={setOpenAudit}
                        auditData={auditData}
                    />
                )} */}
            </main>

        </AppLayout>
    )
}