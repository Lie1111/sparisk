import { AppContent } from '@/components/app-content';
import { AppShell } from '@/components/app-shell';
import { AppSidebar } from '@/components/app-sidebar';
import { AppSidebarHeader } from '@/components/app-sidebar-header';
import { SharedData, type BreadcrumbItem } from '@/types';
import { type PropsWithChildren } from 'react';
import NavRoutes from './app-nav';
import { usePage } from '@inertiajs/react';

export default function AppSidebarLayout({ children, breadcrumbs = [] }: PropsWithChildren<{ breadcrumbs?: BreadcrumbItem[] }>) {
    const page = usePage<SharedData>();
    const { auth } = page.props;
    const data = {
        user: {
            name: auth.user.name,
            email: auth.user.email,
            permissions: auth.permissions
        },
        teams: [
            {
                name: "SmartSecure Authentic",
                logo: <img src='/icon.png' />,
            },
        ],
        navMain: NavRoutes()
    }

    return (
        <AppShell variant="sidebar">
            <AppSidebar data={data} />
            <AppContent variant="sidebar">
                <AppSidebarHeader breadcrumbs={breadcrumbs} />
                {children}
            </AppContent>
        </AppShell>
    );
}
