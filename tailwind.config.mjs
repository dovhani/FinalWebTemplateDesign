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
                    50: '#f9f5f0',
                    100: '#f0e6d8',
                    200: '#dcc8ac',
                    300: '#c4a47c',
                    400: '#a87f50',
                    500: '#7a5a2e',
                    600: '#6b4d24',
                    700: '#5e3f1a',
                    800: '#543717',
                    900: '#3a2610',
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
