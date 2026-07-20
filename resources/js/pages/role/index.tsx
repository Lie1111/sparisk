import React, { useState, useEffect } from 'react'
import { Head, useForm, usePage } from '@inertiajs/react'
import AuthLayout from '@/layouts/auth-layout'
import { Button } from "@/components/ui/button"
import { toast } from 'sonner'
import { ConfirmDialog } from '@/components/confirm-dialog'
import { router } from '@inertiajs/react'
import { ArrowUpDown } from 'lucide-react'
import { FormDialog } from '@/components/form-dialog'
import Form from './form'
import TableWithPagination from '@/components/table-with-pagination'
import RolePermissionDialog from './role-permission-dialog'
import AppLayout from '@/layouts/app-layout'
import { BreadcrumbItem } from '@/types'

export default function Index({ roles, allPermissions }: any) {
    const [openRole, setOpenRole] = useState(false)
    const [openDeleteDialog, setOpenDeleteDialog] = useState(false)
    const [formType, setFormType] = useState<'create' | 'update'>('create')
    const [sortDirection, setSortDirection] = useState<'asc' | 'desc'>('asc')
    const [sortColumn, setSortColumn] = useState<string>('variant_id')
    const [openPermission, setOpenPermission] = useState(false);
    const [permissions, setPermissions] = useState([]);
    const [confirmPermission, setConfirmPermission] = useState(false);

    const { data, setData, post, processing, errors, reset } = useForm({
        id: '',
        name: ''
    })

    useEffect(() => {
        if (!openRole) {
            reset()
        }
    }, [openRole])

    useEffect(() => {
        if (confirmPermission) {
            router.post(`/roles/update?type=setPermissions`, {
                id: data.id,
                permissions: permissions.map((p: any) => p.name),
                pages: roles.current_page,
            }, {
                onSuccess: () => {
                    setConfirmPermission(false);
                    setOpenPermission(false);
                    toast("Permissions Updated");
                },
                onError: (errors) => console.error(errors),
            });
        }
    }, [confirmPermission]);

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
            permission: 'can:update:role',
            label: 'Permissions',
            onClick: (item: any) => {
                setOpenPermission(true);
                // setUserID(d.id);
                setData(item)
                setPermissions(item.permissions);
            },
        },
        {
            permission: 'can:update:role',
            label: 'Update',
            onClick: (item: any) => {
                setData(item)
                setOpenRole(true)
                setFormType('update')
            },
        },
        {
            permission: 'can:delete:permission',
            label: 'Delete',
            onClick: (item: any) => {
                setData(item)
                setOpenDeleteDialog(true)
            },
        },
    ]

    const handleSearch = (query: string) => {
        router.get('/roles', { q: query }, { preserveState: true })
    }

    const handlePageChange = (url: string) => {
        router.get(url, {}, { preserveState: true })
    }

    const handleAdd = (e: React.MouseEvent) => {
        reset()
        setOpenRole(true)
        setFormType('create')
    }

    const handleConfirmDelete = () => {
        post(`/roles/destroy`, {
            onSuccess: () => {
                setOpenDeleteDialog(false)
                toast("Role Deleted")
            },
        })
    }

    const handlePermission = (e: React.FormEvent) => {
        e.preventDefault()
        post(`/roles/update?type=setPermissions`, {
            onSuccess: (data: any) => {
                setOpenPermission(false)
                toast(`Permissions Updated`)
            },
            onError: (err: any) => {
                console.log(err)
            }
        })
    }

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault()
        const url = formType === 'create' ? '/roles/create' : '/roles/update'

        post(url, {
            onSuccess: (data: any) => {
                setOpenRole(false)
                toast(`Role ${formType === 'create' ? 'Created' : 'Updated'}`)
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
            '/roles',
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
            title: 'Role',
            href: '/Roles',
        },
    ];

    return (
        <AppLayout
            breadcrumbs={breadcrumbs}
        >
            <Head title="Roles" />

            <main className="flex-1 items-start gap-4 p-4 mt-4 sm:px-6 sm:py-0 md:gap-8">
                <TableWithPagination
                    title="Roles"
                    description="Manage roles and view their data."
                    columns={columns}
                    data={roles.data}
                    pagination={{
                        currentPage: roles.current_page,
                        perPage: roles.per_page,
                        total: roles.total,
                        from: roles.from,
                        to: roles.to,
                        nextUrl: roles.next_page_url,
                        prevUrl: roles.prev_page_url,
                    }}
                    actions={actions}
                    onSearch={handleSearch}
                    onPageChange={handlePageChange}
                    onAdd={handleAdd}
                    addPermission="can:create:role"
                />

                {openRole && (

                    <FormDialog
                        setOpenForm={setOpenRole}
                        openDialog={openRole}
                        setConfirmForm={handleSubmit}
                        title={`${formType === 'create' ? 'Create' : 'Update'} Role`}
                        forms={<Form setData={setData} data={data} formType={formType} errors={errors} />}
                        processing={processing}
                    />
                )}

                {openDeleteDialog && (
                    <ConfirmDialog
                        open={openDeleteDialog}
                        setConfirm={handleConfirmDelete}
                        title="Confirm to delete Role?"
                        setOpen={setOpenDeleteDialog}
                    />
                )}
                {openPermission && (
                    <RolePermissionDialog
                        openPermission={openPermission}
                        setConfirmPermission={setConfirmPermission}
                        title={"Set Permission"}
                        setOpenPermission={setOpenPermission}
                        allPermissions={allPermissions}
                        permissions={permissions}
                        setPermissions={setPermissions}
                    // handlePermission={handlePermission}
                    />
                )}
            </main>
        </AppLayout>
    )
}