"use client"

import { Button } from "@/components/ui/button"
import { Checkbox } from "@/components/ui/checkbox"
import {
  Dialog,
  DialogContent,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog"
import { Input } from "@/components/ui/input"
import { ScrollArea } from "@/components/ui/scroll-area"
import { Separator } from "@/components/ui/separator"
import { useEffect, useState } from "react"
import { Search } from "lucide-react"

export default function RolePermissionDialog(props: {
  openPermission: boolean
  setOpenPermission: (open: boolean) => void
  permissions: any[]
  setPermissions: (permissions: any) => void
  allPermissions: any[]
  title: string
  setConfirmPermission: (confirm: boolean) => void
  // handlePermission: () => void
}) {
  const [open, setOpen] = useState(props.openPermission)
  const [data, setData] = useState(props.permissions)
  const [searchTerm, setSearchTerm] = useState("")

  useEffect(() => {
    props.setOpenPermission(open)
  }, [open, props])

  function handleSubmit(event: React.FormEvent) {
    event.preventDefault()
    props.setPermissions(data)
    props.setConfirmPermission(true)
    // props.handlePermission
    setOpen(false)
  }

  function handleCancel() {
    props.setOpenPermission(false)
    setOpen(false)
  }

  function handleChecked(checked: boolean, id: string) {
    setData(prevData => {
      if (!checked) {
        return prevData.filter((d: any) => d.id !== id)
      } else {
        const newPermission = props.allPermissions.find((d: any) => d.id === id)
        return [...prevData, newPermission]
      }
    })
  }

  function groupPermissions(permissions: any[]) {
    return permissions.reduce((groups: any, permission: any) => {
      const prefix = permission.name.split(':')[2]
      if (!groups[prefix]) {
        groups[prefix] = []
      }
      groups[prefix].push(permission)

      return groups
    }, {})
  }

  function handleGroupSelect(prefix: string, checked: boolean) {
    setData(prevData => {
      const groupPermissions = props.allPermissions.filter((p: any) => p.name.split(':')[2] === prefix)

      if (checked) {
        return [...new Set([...prevData, ...groupPermissions])]
      } else {
        return prevData.filter((d: any) =>  d.name.split(':')[2] !== prefix)
      }
    })
  }

  const groupedPermissions = groupPermissions(props.allPermissions)

  const filteredGroups = Object.entries(groupedPermissions).filter(([prefix, permissions]: [string, any]) => {
    return prefix.toLowerCase().includes(searchTerm.toLowerCase()) ||
      permissions.some((p: any) => p.name.toLowerCase().includes(searchTerm.toLowerCase()))
  })

  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogContent className="sm:max-w-[700px]">
        <DialogHeader>
          <DialogTitle>{props.title}</DialogTitle>
        </DialogHeader>
        <div className="relative mb-4">
          <Search className="absolute left-2 top-2.5 h-4 w-4 text-muted-foreground" />
          <Input
            placeholder="Search permissions..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="pl-8"
          />
        </div>
        <ScrollArea className="h-[400px] pr-4">
          <div className="space-y-6">
            {filteredGroups.map(([prefix, permissions]: [string, any], index: number) => (
              <div key={prefix}>
                <div className="space-y-2 mb-4">
                  <div className="flex items-center space-x-2">
                    <Checkbox
                      id={`group-${prefix}`}
                      checked={permissions.every((p: any) => data.some((d: any) => d.id === p.id))}
                      onCheckedChange={(checked) => handleGroupSelect(prefix, checked as boolean)}
                    />
                    <label
                      htmlFor={`group-${prefix}`}
                      className="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                    >
                      {prefix}
                    </label>
                  </div>
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-2 ml-6">
                    {permissions.map((p: any) => (
                      <div className="flex items-center space-x-2" key={p.id}>
                        <Checkbox
                          checked={data.some((perm: any) => perm.id === p.id)}
                          onCheckedChange={(checked) => {
                            handleChecked(checked as boolean, p.id)
                          }}
                          id={`cb-${p.id}`}
                        />
                        <label
                          htmlFor={`cb-${p.id}`}
                          className="text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                        >
                          {p.name.split(':').slice(1).join(' ')}
                        </label>
                      </div>
                    ))}
                  </div>
                </div>
                {index < filteredGroups.length - 1 && <Separator className="my-4" />}
              </div>
            ))}
          </div>
        </ScrollArea>
        <DialogFooter>
          <Button onClick={handleCancel} variant="outline">
            Cancel
          </Button>
          <Button onClick={handleSubmit} type="submit">
            Confirm
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  )
}