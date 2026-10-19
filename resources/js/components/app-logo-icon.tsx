import { SVGAttributes } from 'react';

export default function AppLogoIcon(props: SVGAttributes<SVGElement>) {
    return (
        <svg {...props} viewBox="0 0 40 42" xmlns="http://www.w3.org/2000/svg">
            {/* alignment axis */}
            <path d="M8.5 5v32" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeDasharray="0.5 5" fill="none" opacity="0.35" />
            {/* head */}
            <circle cx="25" cy="9.5" r="4.5" fill="currentColor" />
            {/* spine */}
            <path d="M25 15.5c0 5.5-6.5 6.5-6.5 13 0 3.5 4 4.5 4 8" stroke="currentColor" strokeWidth="3.5" strokeLinecap="round" fill="none" />
            {/* shoulders */}
            <path d="M19 17.5h10.5" stroke="currentColor" strokeWidth="3" strokeLinecap="round" fill="none" opacity="0.6" />
        </svg>
    );
}
