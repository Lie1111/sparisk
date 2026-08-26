import { ClipboardCheck, Crown, Layers3, LayoutDashboard, MessageCircleWarning, RailSymbol, Settings, Settings2, Users, Activity, School, Watch, Wrench } from "lucide-react";

export default function NavRoutes() {
    return [
        {
            title: "Dashboard",
            url: "dashboard",
            icon: LayoutDashboard,
            isActive: route().current("dashboard")
        },
        {
            title: "Posture Settings",
            url: "/posture-settings",
            icon: Activity,
            isActive: route().current("posture-settings.index"),
        },
        {
            title: "Patients",
            url: "/patients",
            icon: Users,
            isActive: route().current("patients.index"),
        },
        {
            title: "Academies",
            url: "academies.index",
            icon: School,
            isActive: route().current("academies.index"),
        },
        // {
        //     title: "SS NFC",
        //     url: "smartsecure.index",
        //     icon: Layers3,
        //     isActive: route().current("smartsecure.index")
        // },


        {
            title: "Admin Controls",
            url: "#",
            icon: Settings2,
            permission: "can:view:control",
            isActive: route().current("users.index") || route().current("roles.index") || route().current("permissions.index"),
            items: [
                {
                    title: "Users",
                    url: "users.index",
                    permission: "can:view:user",
                },
                {
                    title: "Roles",
                    url: "roles.index",
                    permission: "can:view:role",
                },
                {
                    title: "Permissions",
                    url: "permissions.index",
                    permission: "can:view:permission",
                },
            ],
        },
    ]
}