import { Head, Link } from '@inertiajs/react'
import AppLayout from '@/layouts/app-layout'
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { BreadcrumbItem } from '@/types'
import { School, Users, MapPin, ChevronRight, GraduationCap } from 'lucide-react'

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Academies', href: '/academies' },
]

export default function AcademiesIndex({ academies }: any) {
    const data = academies.data || []

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Academies" />
            <div className="flex flex-col gap-6 p-4">
                <div>
                    <h1 className="text-2xl font-bold tracking-tight">SPARISK Academies</h1>
                    <p className="text-muted-foreground">Manage SPARISK Academy locations and members.</p>
                </div>

                <div className="grid gap-4 md:grid-cols-2">
                    {data.length === 0 ? (
                        <Card className="md:col-span-2">
                            <CardContent className="flex flex-col items-center justify-center py-12">
                                <School className="h-12 w-12 text-muted-foreground/50" />
                                <p className="mt-4 font-medium">No academies registered</p>
                                <p className="text-sm text-muted-foreground">Academies will appear here once registered.</p>
                            </CardContent>
                        </Card>
                    ) : (
                        data.map((academy: any) => (
                            <Link key={academy.id} href={`/academies/${academy.id}`}>
                                <Card className="cursor-pointer transition-shadow hover:shadow-md">
                                    <CardContent className="flex items-center gap-4 py-6">
                                        <div className="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10">
                                            <School className="h-6 w-6 text-primary" />
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <p className="font-semibold truncate">{academy.name}</p>
                                            <div className="mt-1 flex flex-wrap items-center gap-2 text-sm text-muted-foreground">
                                                {academy.state && (
                                                    <span className="flex items-center gap-1">
                                                        <MapPin className="h-3 w-3" />{academy.state}
                                                    </span>
                                                )}
                                                <span className="flex items-center gap-1">
                                                    <Users className="h-3 w-3" />{academy.members_count || 0} members
                                                </span>
                                            </div>
                                        </div>
                                        <ChevronRight className="h-5 w-5 text-muted-foreground" />
                                    </CardContent>
                                </Card>
                            </Link>
                        ))
                    )}
                </div>

                {academies.links && (
                    <div className="flex items-center justify-center gap-2">
                        {academies.links.map((link: any, i: number) => (
                            <Button key={i}
                                variant={link.active ? 'default' : 'outline'}
                                size="sm"
                                disabled={!link.url}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ))}
                    </div>
                )}
            </div>
        </AppLayout>
    )
}
