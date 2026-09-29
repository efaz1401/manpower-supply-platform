import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                // Cairo renders both Arabic and Latin; Figtree is the Latin fallback.
                sans: ['Cairo', 'Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Cairo', 'Figtree', ...defaultTheme.fontFamily.sans],
            },

            /*
            | Semantic colors backed by CSS variables (see resources/css/app.css).
            | Each maps to `rgb(var(--x) / <alpha-value>)` so the SAME utility class
            | resolves to the right value in dark (default) and .light themes.
            */
            colors: {
                // Surfaces
                canvas: 'rgb(var(--ds-canvas) / <alpha-value>)',
                surface: 'rgb(var(--ds-surface) / <alpha-value>)',
                'surface-raised': 'rgb(var(--ds-surface-raised) / <alpha-value>)',
                'surface-sunken': 'rgb(var(--ds-surface-sunken) / <alpha-value>)',
                hairline: 'rgb(var(--ds-hairline) / <alpha-value>)',

                // Text
                ink: 'rgb(var(--ds-ink) / <alpha-value>)',
                'ink-muted': 'rgb(var(--ds-ink-muted) / <alpha-value>)',
                'ink-faint': 'rgb(var(--ds-ink-faint) / <alpha-value>)',

                // Brand / action (emerald preserved as primary CTA for continuity)
                accent: {
                    DEFAULT: 'rgb(var(--ds-accent) / <alpha-value>)',
                    soft: 'rgb(var(--ds-accent-soft) / <alpha-value>)',
                    contrast: 'rgb(var(--ds-accent-contrast) / <alpha-value>)',
                },
                teal: 'rgb(var(--ds-teal) / <alpha-value>)',
                gold: 'rgb(var(--ds-gold) / <alpha-value>)',

                // Status tones — mapped 1:1 to the existing status-pill semantics.
                success: 'rgb(var(--ds-success) / <alpha-value>)',
                warning: 'rgb(var(--ds-warning) / <alpha-value>)',
                danger: 'rgb(var(--ds-danger) / <alpha-value>)',
                info: 'rgb(var(--ds-info) / <alpha-value>)',
            },

            boxShadow: {
                glass: '0 1px 0 0 rgb(255 255 255 / 0.04) inset, 0 8px 30px -12px rgb(0 0 0 / 0.5)',
                'glow-accent': '0 0 0 1px rgb(var(--ds-accent) / 0.25), 0 8px 40px -8px rgb(var(--ds-accent) / 0.35)',
                'glow-gold': '0 0 40px -8px rgb(var(--ds-gold) / 0.45)',
            },

            backdropBlur: {
                xs: '2px',
            },

            keyframes: {
                'fade-in-up': {
                    '0%': { opacity: '0', transform: 'translateY(16px)', filter: 'blur(6px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)', filter: 'blur(0)' },
                },
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                shimmer: {
                    '100%': { transform: 'translateX(100%)' },
                },
                'pulse-ring': {
                    '0%': { boxShadow: '0 0 0 0 rgb(var(--ds-danger) / 0.5)' },
                    '70%': { boxShadow: '0 0 0 6px rgb(var(--ds-danger) / 0)' },
                    '100%': { boxShadow: '0 0 0 0 rgb(var(--ds-danger) / 0)' },
                },
                'radar-sweep': {
                    '100%': { transform: 'rotate(360deg)' },
                },
                'aurora-drift': {
                    '0%,100%': { transform: 'translate3d(0,0,0) scale(1)' },
                    '50%': { transform: 'translate3d(2%,-2%,0) scale(1.05)' },
                },
                'drawer-in': {
                    '0%': { opacity: '0', transform: 'translateX(var(--drawer-from, 24px))' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                'scale-in': {
                    '0%': { opacity: '0', transform: 'scale(0.96)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
            },

            animation: {
                'fade-in-up': 'fade-in-up 0.6s cubic-bezier(0.22,1,0.36,1) both',
                'fade-in': 'fade-in 0.4s ease-out both',
                shimmer: 'shimmer 1.5s infinite',
                'pulse-ring': 'pulse-ring 2s cubic-bezier(0.4,0,0.6,1) infinite',
                'radar-sweep': 'radar-sweep 4s linear infinite',
                'aurora-drift': 'aurora-drift 18s ease-in-out infinite',
                'drawer-in': 'drawer-in 0.35s cubic-bezier(0.22,1,0.36,1) both',
                'scale-in': 'scale-in 0.25s cubic-bezier(0.22,1,0.36,1) both',
            },
        },
    },

    plugins: [forms],
};
