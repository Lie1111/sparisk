import React, { useState, useEffect } from 'react'
import { Button } from "@/components/ui/button"
import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog"
import UploadExcel from "./upload-excel"
import StickerForm from "./sticker-form"

export function StickerDialog({
    openExcel,
    setOpenExcel,
    title,
    formType,
    sticker,
    setSticker,
    onConfirm,
    errors,
    users
}: any) {
    const [stickerData, setStickerData] = useState([])
    const [userSelected, setUserSelected] = useState({})

    useEffect(() => {
        if (!openExcel) {
            setStickerData([])
            setUserSelected({})
        }
    }, [openExcel])

    function handleSubmit(event: React.FormEvent) {
        event.preventDefault()
        onConfirm(formType === 'create' ? stickerData : sticker, userSelected)
    }

    return (
        <Dialog open={openExcel} onOpenChange={setOpenExcel}>
            <form>
                <DialogContent
                    className={formType === "create" ? "sm:max-w-screen-xl" : "sm:max-w-[425px]"}
                    onInteractOutside={(e) => {
                        e.preventDefault()
                    }}
                >
                    <DialogHeader>
                        <DialogTitle>{title}</DialogTitle>
                    </DialogHeader>
                    {formType === "create" && (
                        <UploadExcel
                            data={stickerData}
                            setData={setStickerData}
                            users={users}
                            setUserSelected={setUserSelected}
                        />
                    )}
                    {formType === "update" && (
                        <StickerForm
                            data={sticker}
                            setData={setSticker}
                            errors={errors}
                        />
                    )}
                    <DialogFooter>
                        <Button onClick={handleSubmit}>Confirm</Button>
                        <Button type="button" variant="outline" onClick={() => setOpenExcel(false)}>Cancel</Button>
                    </DialogFooter>
                </DialogContent>
            </form>
        </Dialog>
    )
}

