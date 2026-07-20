import { useCallback } from "react"
import { useDropzone } from "react-dropzone"
import readXlsxFile from "read-excel-file"
import { Table, TableHeader, TableBody, TableCell, TableRow, TableHead } from "@/components/ui/table"
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger, DropdownMenuLabel, DropdownMenuItem } from "@/components/ui/dropdown-menu"
import { Button } from "@/components/ui/button"
import { MoreHorizontal } from 'lucide-react'
import { toast } from "sonner"


export default function UploadExcel({ data, setData }: any) {
    const onDrop = useCallback((acceptedFiles: File[]) => {
        if (acceptedFiles.length > 1) {
            return toast("Oops, only 1 excel file is allowed!")
        }
        readXlsxFile(acceptedFiles[0]).then((rows) => {
            if (rows.length > 1) {
                setData(rows.slice(1))
            }
        })
    }, [setData])

    const { getRootProps, getInputProps, isDragActive } = useDropzone({
        onDrop,
        accept: {
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet": [".xlsx"],
            "application/vnd.ms-excel": [".xls"],
        },
    })

    return (
        <div>
            <div
                className="flex h-[calc(100vh_-_250px)] w-full rounded-lg border border-dashed shadow-sm"
                {...getRootProps()}
            >
                <input {...getInputProps()} />
                {data.length === 0 ? (
                    <p className="m-auto">
                        Drag 'n' drop excel here, or click to select files (Max 1)
                    </p>
                ) : (
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>No.</TableHead>
                                <TableHead>Tag</TableHead>
                                <TableHead>Label</TableHead>
                                <TableHead className="hidden md:table-cell">Encryption</TableHead>
                                <TableHead><span className="sr-only">Actions</span></TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {data.map((d: any, i: number) => (
                                <TableRow key={i}>
                                    <TableCell className="font-medium">{d[0]}</TableCell>
                                    <TableCell className="font-medium">{d[1]}</TableCell>
                                    <TableCell className="font-medium">{d[2].slice(0, 28)}</TableCell>
                                    <TableCell className="hidden md:table-cell">{d[2].slice(28, 52)}</TableCell>
                                    <TableCell>
                                        <DropdownMenu>
                                            <DropdownMenuTrigger asChild>
                                                <Button aria-haspopup="true" size="icon" variant="ghost">
                                                    <MoreHorizontal className="h-4 w-4" />
                                                    <span className="sr-only">Toggle menu</span>
                                                </Button>
                                            </DropdownMenuTrigger>
                                            <DropdownMenuContent align="end">
                                                <DropdownMenuLabel>Actions</DropdownMenuLabel>
                                                <DropdownMenuItem onClick={() => {
                                                    const newData = data.filter((_: any, index: any) => index !== i)
                                                    setData(newData)
                                                }}>
                                                    Delete
                                                </DropdownMenuItem>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                )}
            </div>
        </div>
    )
}

