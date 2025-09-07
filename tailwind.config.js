import defaultTheme from 'tailwindcss/defaultTheme';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', 'Menlo', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', 'monospace'],
            },
            colors: {
                // Neural color palette (blues)
                neural: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                    950: '#082f49',
                },
                // Quantum color palette (greens)
                quantum: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                    950: '#052e16',
                },
                // Cyber color palette (pinks/purples)
                cyber: {
                    50: '#fdf4ff',
                    100: '#fae8ff',
                    200: '#f5d0fe',
                    300: '#f0abfc',
                    400: '#e879f9',
                    500: '#d946ef',
                    600: '#c026d3',
                    700: '#a21caf',
                    800: '#86198f',
                    900: '#701a75',
                    950: '#4a044e',
                },
            },
            animation: {
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'bounce-slow': 'bounce 2s infinite',
                'spin-slow': 'spin 3s linear infinite',
                'ping-slow': 'ping 2s cubic-bezier(0, 0, 0.2, 1) infinite',
                'float': 'float 6s ease-in-out infinite',
                'glow': 'glow 2s ease-in-out infinite alternate',
                'neural-flow': 'neural-flow 8s ease-in-out infinite',
                'fade-in': 'fadeIn 0.5s ease-in',
                'slide-up': 'slideUp 0.6s ease-out',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-20px)' },
                },
                glow: {
                    from: {
                        textShadow: '0 0 5px #fff, 0 0 10px #fff, 0 0 15px #0ea5e9, 0 0 20px #0ea5e9',
                    },
                    to: {
                        textShadow: '0 0 10px #fff, 0 0 20px #fff, 0 0 30px #0ea5e9, 0 0 40px #0ea5e9',
                    },
                },
                'neural-flow': {
                    '0%, 100%': { 
                        opacity: '0.5',
                        transform: 'scale(1) rotate(0deg)',
                    },
                    '50%': { 
                        opacity: '1',
                        transform: 'scale(1.05) rotate(180deg)',
                    },
                },
                fadeIn: {
                    from: { opacity: '0' },
                    to: { opacity: '1' },
                },
                slideUp: {
                    from: {
                        opacity: '0',
                        transform: 'translateY(30px)',
                    },
                    to: {
                        opacity: '1',
                        transform: 'translateY(0)',
                    },
                },
            },
            backdropBlur: {
                xs: '2px',
            },
            backgroundImage: {
                'gradient-neural': 'linear-gradient(135deg, #0ea5e9 0%, #3b82f6 50%, #8b5cf6 100%)',
                'gradient-quantum': 'linear-gradient(135deg, #22c55e 0%, #10b981 50%, #06b6d4 100%)',
                'gradient-cyber': 'linear-gradient(135deg, #d946ef 0%, #c026d3 50%, #9333ea 100%)',
                'gradient-dark': 'linear-gradient(135deg, #1e293b 0%, #0f172a 100%)',
            },
            boxShadow: {
                'neural': '0 0 30px rgba(14, 165, 233, 0.3)',
                'quantum': '0 0 30px rgba(34, 197, 94, 0.3)',
                'cyber': '0 0 30px rgba(217, 70, 239, 0.3)',
                'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.37)',
            },
        },
    },
    plugins: [],
};