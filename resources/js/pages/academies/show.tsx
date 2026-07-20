import { Head, Link } from '@inertiajs/react'
import AppLayout from '@/layouts/app-layout'
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { BreadcrumbItem } from '@/types'
import { ArrowLeft, Users, GraduationCap, Mail, Phone, MapPin, School } from 'lucide-react'

export default function AcademyShow({ academy }: any) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: '/dashboard' },
        { title: 'Academies', href: '/academies' },
        { title: academy.name, href: '#' },
    ]

    const members = academy.members || []

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={academy.name} />
            <div className="flex flex-col gap-6 p-4">
                <Link href="/academies">
                    <Button variant="ghost" size="sm">
                        <ArrowLeft className="mr-2 h-4 w-4" />
                        Back to Academies
                    </Button>
                </Link>

                <Card>
                    <CardContent className="flex items-start gap-6 py-6">
                        <div className="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10">
                            <School className="h-8 w-8 text-primary" />
                        </div>
                        <div className="flex-1">
                            <h1 className="text-2xl font-bold">{academy.name}</h1>
                            <div className="mt-2 flex flex-wrap gap-2 text-sm text-muted-foreground">
                                {academy.state && <span className="flex items-center gap-1"><MapPin className="h-3 w-3" />{academy.state}</span>}
                                {academy.email && <span className="flex items-center gap-1"><Mail className="h-3 w-3" />{academy.email}</span>}
                                {academy.phone && <span className="flex items-center gap-1"><Phone className="h-3 w-3" />{academy.phone}</span>}
                            </div>
                        </div>
                        <Badge variant="outline" className="text-lg px-3 py-1">
                            <Users className="mr-1 h-4 w-4" />{members.length} members
                        </Badge>
                    </CardContent>
                </Card>

                {academy.address && (
                    <Card>
                        <CardHeader><CardTitle className="text-base">Address</CardTitle></CardHeader>
                        <CardContent><p className="text-muted-foreground">{academy.address}</p></CardContent>
                    </Card>
                )}

                <Card>
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2 text-base">
                            <GraduationCap className="h-4 w-4" />
                            Members ({members.length})
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        {members.length === 0 ? (
                            <p className="text-center text-muted-foreground py-4">No members yet</p>
                        ) : (
                            <div className="space-y-2">
                                {members.map((m: any) => (
                                    <Link key={m.id} href={`/patients/${m.patient?.id}`}>
                                        <div className="flex items-center gap-3 rounded-lg border p-3 hover:bg-muted/50 transition-colors">
                                            <div className="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10">
                                                <span className="text-xs font-bold text-primary">
                                                    {m.patient?.name?.[0]?.toUpperCase() || '?'}
                                                </span>
                                            </div>
                                            <div className="flex-1">
                                                <p className="text-sm font-medium">{m.patient?.name || 'Unknown'}</p>
                                            </div>
                                            <Badge variant="secondary" className="text-xs">{m.role}</Badge>
                                        </div>
                                    </Link>
                                ))}
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    )
}
