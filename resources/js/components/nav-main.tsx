import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem, SidebarMenuSub, SidebarMenuSubButton, SidebarMenuSubItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from './ui/collapsible';
import { cn } from '@/lib/utils';
import { ChevronRight } from 'lucide-react';

export function NavMain(props: any) {
    const page = usePage();

    const userHasPermission = (perm?: string) => {
        if (!perm) return true;
        return props.data.user.permissions.some((p: any) => p === perm);
    };

    return (
        <SidebarGroup>
            <SidebarGroupLabel>Platform</SidebarGroupLabel>
            <SidebarMenu>
                {props.data.navMain.map((item: any) => {
                    if (!userHasPermission(item.permission)) return null;

                    if (item.items) {
                        return (
                            <div key={item.title}>
                                <Collapsible
                                    asChild
                                    defaultOpen={item.isActive}
                                    className="group/collapsible"
                                >
                                    <SidebarMenuItem>
                                        <CollapsibleTrigger asChild>
                                            <SidebarMenuButton tooltip={item.title} className={item.isActive
                                                ? ` bg-muted `
                                                : ` text-muted-foreground `}>
                                                {item.icon && <item.icon />}
                                                <span>{item.title}</span>
                                                <ChevronRight className="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                                            </SidebarMenuButton>
                                        </CollapsibleTrigger>
                                        <CollapsibleContent>
                                            <SidebarMenuSub>
                                                {item.items?.map((subItem: any) =>
                                                    userHasPermission(subItem.permission) ? (
                                                        <SidebarMenuSubItem key={subItem.title}>
                                                            <SidebarMenuSubButton asChild>
                                                                <Link href={route(subItem.url)} className={route().current(subItem.url)
                                                                    ? ` bg-muted-foreground text-white `
                                                                    : ` text-muted-foreground `}>
                                                                    <span>{subItem.title}</span>
                                                                </Link>
                                                            </SidebarMenuSubButton>
                                                        </SidebarMenuSubItem>
                                                    ) : null
                                                )}
                                            </SidebarMenuSub>
                                        </CollapsibleContent>
                                    </SidebarMenuItem>
                                </Collapsible>
                            </div>
                        );
                    }

                    return (
                        <SidebarMenuItem key={item.title}>
                            <SidebarMenuButton
                                tooltip={item.title}
                                asChild
                                isActive={item.isActive}
                            >
                                <Link href={typeof item.url === 'string' && item.url.startsWith('/') ? item.url : route(item.url)} className={cn(
                                    "group/label text-sm",
                                    item.isActive && "font-medium text-primary"
                                )}>
                                    {item.icon && <item.icon />}
                                    <span>{item.title}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    );
                })}
            </SidebarMenu>
        </SidebarGroup>
    );
}
