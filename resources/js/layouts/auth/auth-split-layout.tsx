import AppLogoIcon from '@/components/app-logo-icon';
import AppearanceToggleDropdown from '@/components/appearance-dropdown';
import { type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { ClipboardCheck, Ruler, TrendingUp } from 'lucide-react';
import { type PropsWithChildren } from 'react';

interface AuthLayoutProps {
    title?: string;
    description?: string;
}

const highlights = [
    {
        icon: Ruler,
        title: 'Standardised screening',
        description: 'Capture posture markers the same way on every assessment.',
    },
    {
        icon: ClipboardCheck,
        title: 'Assessment records',
        description: 'Keep each screening, score and report linked to its patient.',
    },
    {
        icon: TrendingUp,
        title: 'Follow-up that works',
        description: 'Turn results into intervention plans and track the progress.',
    },
];

export default function AuthSplitLayout({ children, title, description }: PropsWithChildren<AuthLayoutProps>) {
    const { name } = usePage<SharedData>().props;
    const appName = name || 'SPARISK';
    const gridMask = 'radial-gradient(circle at 25% 15%, black, transparent 72%)';

    return (
        <div className="bg-background grid min-h-svh lg:grid-cols-[1.05fr_1fr]">
            <aside className="relative hidden flex-col overflow-hidden bg-zinc-950 p-12 lg:flex xl:p-16">
                <div className="absolute inset-0 bg-gradient-to-br from-sky-500/25 via-zinc-950 to-emerald-500/20" aria-hidden />
                <div className="absolute -top-32 -left-24 size-[28rem] rounded-full bg-sky-500/20 blur-3xl" aria-hidden />
                <div className="absolute -right-32 -bottom-32 size-[26rem] rounded-full bg-emerald-500/15 blur-3xl" aria-hidden />
                <div
                    className="absolute inset-0 opacity-20"
                    aria-hidden
                    style={{
                        backgroundImage:
                            'linear-gradient(to right, rgba(255,255,255,0.35) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.35) 1px, transparent 1px)',
                        backgroundSize: '48px 48px',
                        maskImage: gridMask,
                        WebkitMaskImage: gridMask,
                    }}
                />

                <Link href={route('home')} className="relative z-10 flex items-center gap-3 text-white">
                    <span className="flex size-11 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/20 backdrop-blur-sm">
                        <AppLogoIcon className="size-6 text-white" />
                    </span>
                    <span className="text-lg font-semibold tracking-tight">{appName}</span>
                </Link>

                <div className="relative z-10 my-auto max-w-md py-10">
                    <p className="text-[0.7rem] font-semibold tracking-[0.22em] text-white/45 uppercase">Posture risk assessment</p>
                    <h2 className="mt-5 text-3xl leading-[1.15] font-semibold tracking-tight text-balance text-white xl:text-4xl">
                        Objective screening, consistent scoring, clearer follow-up.
                    </h2>
                    <p className="mt-5 text-sm leading-relaxed text-white/55">
                        {appName} brings posture markers, patient assessments and intervention plans into a single clinical workspace.
                    </p>

                    <ul className="mt-10 space-y-5">
                        {highlights.map(({ icon: Icon, title: highlightTitle, description: highlightDescription }) => (
                            <li key={highlightTitle} className="flex gap-4">
                                <span className="mt-0.5 flex size-9 shrink-0 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/10">
                                    <Icon className="size-4 text-white/85" />
                                </span>
                                <div>
                                    <p className="text-sm font-medium text-white/90">{highlightTitle}</p>
                                    <p className="mt-0.5 text-xs leading-relaxed text-white/50">{highlightDescription}</p>
                                </div>
                            </li>
                        ))}
                    </ul>
                </div>

                <p className="relative z-10 text-xs text-white/35">
                    © {new Date().getFullYear()} {appName}. All rights reserved.
                </p>
            </aside>

            <main className="relative flex flex-col justify-center px-6 py-12 sm:px-10">
                <div className="absolute top-4 right-4">
                    <AppearanceToggleDropdown />
                </div>

                <div className="mx-auto w-full max-w-sm">
                    <Link href={route('home')} className="mb-10 flex items-center gap-3 lg:hidden">
                        <span className="bg-primary text-primary-foreground flex size-10 items-center justify-center rounded-xl">
                            <AppLogoIcon className="size-5" />
                        </span>
                        <span className="text-base font-semibold tracking-tight">{appName}</span>
                    </Link>

                    <div className="space-y-2">
                        <h1 className="text-2xl font-semibold tracking-tight">{title}</h1>
                        {description && <p className="text-muted-foreground text-sm">{description}</p>}
                    </div>

                    <div className="mt-8">{children}</div>
                </div>
            </main>
        </div>
    );
}
