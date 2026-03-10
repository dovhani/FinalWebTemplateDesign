/** @type {import('tailwindcss').Config} */
export default {
    content: ['./src/**/*.{astro,html,js,jsx,md,mdx,svelte,ts,tsx,vue}'],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                },
                gold: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#deb056',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#cb8f24',
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                },
                maroon: {
                    50: '#fef2f2',
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#ef4444',
                    600: '#dc2626',
                    700: '#a5180f',
                    800: '#961107',
                    900: '#7b0a02',
                }
            },
            fontFamily: {
                heading: ['var(--font-heading)'],
                display: ['var(--font-display)'],
                body: ['var(--font-body)'],
            }
        },
    },
    plugins: [],
}
