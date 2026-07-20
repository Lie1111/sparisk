import React, { useState, useEffect } from 'react'
import { Head, useForm, usePage } from '@inertiajs/react'
import AppLayout from '@/layouts/app-layout'
import { Button } from "@/components/ui/button"
import { toast } from 'sonner'
import { ConfirmDialog } from '@/components/confirm-dialog'
import { router } from '@inertiajs/react'
import { ArrowUpDown, PlusCircle } from 'lucide-react'
import { StickerDialog } from './sticker-dialog'
import TableWithPagination from '@/components/table-with-pagination'
import { Badge } from "@/components/ui/badge"
import { BreadcrumbItem, SharedData } from '@/types'

export default function StickersIndex({ stickers, query, users }: any) {
    const [openStickerDialog, setOpenStickerDialog] = useState(false)
    const [openDeleteDialog, setOpenDeleteDialog] = useState(false)
    const [formType, setFormType] = useState<'create' | 'update'>('create')
    const [sortDirection, setSortDirection] = useState<'asc' | 'desc'>('asc')
    const [sortColumn, setSortColumn] = useState<string>('tag')
    const permissions = usePage<SharedData>().props.auth.permissions
    const role = usePage<SharedData>().props.auth.role

    const { data, setData, post, processing, errors, reset } = useForm({
        id: '',
        tag: '',
        label: '',
        enc: '',
        status: '',
    })

    useEffect(() => {
        if (!openStickerDialog) {
            reset()
        }
    }, [openStickerDialog])

    const columns = [
        {
            key: 'tag',
            label: <Button variant="ghost" onClick={() => handleSort('tag')}>
                Tag
                <ArrowUpDown className="ml-2 h-4 w-4" />
            </Button>,
            hidden: false,
        },
        {
            key: 'label',
            label: 'Label',
            hidden: false,
            style: 'hidden md:table-cell',
        },
        {
            key: 'enc',
            label: 'Encryption',
            hidden: role !== "Super Admin",
            style: 'hidden md:table-cell',
        },
        {
            key: 'status',
            label: 'Status',
            hidden: false,
            render: (item: any) => (
                <Badge variant={item.status === "1" ? 'default' : 'outline'}>
                    {item.status === "1" ? 'Active' : 'Inactive'}
                </Badge>
            ),
        },
        {
            key: 'updated_at',
            label: <Button variant="ghost" onClick={() => handleSort('updated_at')}>
                Updated At
                <ArrowUpDown className="ml-2 h-4 w-4" />
            </Button>,
            hidden: false,
            style: 'hidden md:table-cell',
            render: (item: any) => new Date(item.updated_at).toLocaleDateString("en-GB"),
        },
    ]

    const actions = [
        {
            permission: 'can:update:sticker',
            label: 'Update',
            onClick: (item: any) => {
                setData(item)
                setOpenStickerDialog(true)
                setFormType('update')
            },
        },
        {
            permission: 'can:delete:sticker',
            label: 'Delete',
            onClick: (item: any) => {
                setData(item)
                setOpenDeleteDialog(true)
            },
        },
    ]

    const handleSearch = (query: string) => {
        router.get('/smartsecure', { q: query }, { preserveState: true })
    }

    const handlePageChange = (url: string) => {
        router.get(url, {}, { preserveState: true })
    }

    const handleAdd = (e: React.MouseEvent) => {
        reset()
        setOpenStickerDialog(true)
        setFormType('create')
    }

    const handleConfirmDelete = () => {
        post(`/smartsecure/sticker/destroy`, {
            onSuccess: () => {
                setOpenDeleteDialog(false)
                toast("Sticker Deleted")
            },
        })
    }

    const handleSubmit = (stickerData: any, userSelected: any) => {
        const url = formType === 'create' ? '/smartsecure/sticker/create' : '/smartsecure/sticker/update'
        const postData = formType === 'create' ? { data: stickerData, user: userSelected } : stickerData

        router.post(url, postData, {
            onSuccess: () => {
                setOpenStickerDialog(false)
                toast(`Sticker ${formType === 'create' ? 'Created' : 'Updated'}`)
            },
            onError: console.error,
        })
    }

    const handleSort = (column: string) => {
        const newDirection = column === sortColumn && sortDirection === 'asc' ? 'desc' : 'asc'
        setSortDirection(newDirection)
        setSortColumn(column)
        router.get(
            '/smartsecure',
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
            title: 'SmartSecure NFC',
            href: '/stickers',
        },
    ];

    return (
        <AppLayout
            breadcrumbs={breadcrumbs}
        >
            <Head title="SmartSecure" />

            <main className="flex-1 items-start gap-4 p-4 mt-4 sm:px-6 sm:py-0 md:gap-8">
                <TableWithPagination
                    title="SS NFC"
                    description="Manages SS NFC and view their data."
                    columns={columns}
                    data={stickers.data}
                    pagination={{
                        currentPage: stickers.current_page,
                        perPage: stickers.per_page,
                        total: stickers.total,
                        from: stickers.from,
                        to: stickers.to,
                        nextUrl: stickers.next_page_url,
                        prevUrl: stickers.prev_page_url,
                    }}
                    actions={actions}
                    onSearch={handleSearch}
                    onPageChange={handlePageChange}
                    onAdd={handleAdd}
                    addPermission="can:create:sticker"
                />

                {openStickerDialog && (
                    <StickerDialog
                        openExcel={openStickerDialog}
                        setOpenExcel={setOpenStickerDialog}
                        title={`${formType === 'create' ? 'Upload' : 'Edit'} SS NFC`}
                        formType={formType}
                        sticker={data}
                        setSticker={setData}
                        onConfirm={handleSubmit}
                        errors={errors}
                        users={users}
                    />
                )}

                {openDeleteDialog && (
                    <ConfirmDialog
                        open={openDeleteDialog}
                        setConfirm={handleConfirmDelete}
                        title="Confirm to delete SS NFC?"
                        setOpen={setOpenDeleteDialog}
                    />
                )}
            </main>
        </AppLayout>
    )
}
