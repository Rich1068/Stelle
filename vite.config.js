import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/blueprint.css',  // Include your Blueprint CSS
                'resources/js/polotno.bundle.js',  // Include Polotno bundle
                'resources/js/editor.jsx',
                'resources/js/calendar.js',  // Include your renamed JSX file
            ],
            refresh: true,
        }),
        react(),
    ],
});