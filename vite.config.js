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
                'resources/js/calendar.js',
                'resources/js/event_editor.jsx',
                'resources/js/sendButton.jsx',
                'resources/js/TemplateNameModal.jsx',
                'resources/js/UploadSection.jsx'
            ],
            refresh: true,
        }),
        react(),
    ],
});