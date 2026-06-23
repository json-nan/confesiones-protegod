import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/robsontenorio/mary/src/View/Components/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                app: 'var(--bg)',
                panel: {
                    DEFAULT: 'var(--panel)',
                    elevated: 'var(--panel-elevated)',
                    muted: 'var(--panel-muted)',
                },
                line: {
                    DEFAULT: 'var(--line)',
                    strong: 'var(--line-strong)',
                },
                ink: {
                    DEFAULT: 'var(--ink)',
                    muted: 'var(--ink-muted)',
                    faint: 'var(--ink-faint)',
                },
                brand: {
                    DEFAULT: 'rgb(var(--brand-rgb) / <alpha-value>)',
                    hover: 'rgb(var(--brand-hover-rgb) / <alpha-value>)',
                    active: 'rgb(var(--brand-active-rgb) / <alpha-value>)',
                    soft: 'rgb(var(--brand-soft-rgb) / <alpha-value>)',
                    tint: 'var(--brand-tint)',
                },
                success: 'rgb(var(--success-rgb) / <alpha-value>)',
                warning: 'rgb(var(--warning-rgb) / <alpha-value>)',
                error: 'rgb(var(--error-rgb) / <alpha-value>)',
                info: 'rgb(var(--info-rgb) / <alpha-value>)',
            },
            boxShadow: {
                panel: '0 1px 2px rgba(0,0,0,0.3), 0 10px 30px -14px rgba(0,0,0,0.55)',
                'panel-sm': '0 1px 2px rgba(0,0,0,0.3)',
                glow: '0 0 0 1px rgba(124,58,237,0.35), 0 8px 30px -10px rgba(124,58,237,0.45)',
            },
            borderRadius: {
                xl: '0.875rem',
                '2xl': '1.25rem',
            },
            keyframes: {
                'fade-up': {
                    '0%': { opacity: '0', transform: 'translateY(6px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
            animation: {
                'fade-up': 'fade-up 0.25s ease-out both',
            },
        },
    },

    plugins: [forms, require('daisyui')],

    daisyui: {
        themes: [
            {
                protegod: {
                    'primary': '#7c3aed',
                    'primary-content': '#ffffff',
                    'secondary': '#8b5cf6',
                    'secondary-content': '#0c0a14',
                    'accent': '#38bdf8',
                    'accent-content': '#0c0a14',
                    'neutral': '#1c1630',
                    'neutral-content': '#a89fc4',
                    'base-100': '#0c0a14',
                    'base-200': '#15111f',
                    'base-300': '#1c1630',
                    'base-content': '#f4f1fb',
                    'info': '#38bdf8',
                    'success': '#22c55e',
                    'warning': '#f59e0b',
                    'error': '#ef4444',
                    '--rounded-box': '0.875rem',
                    '--rounded-btn': '0.5rem',
                    '--rounded-badge': '9999px',
                    '--border-btn': '1px',
                },
            },
        ],
        darkTheme: 'protegod',
    },
};
