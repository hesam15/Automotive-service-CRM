import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/DatePickerManager.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        sourcemap: true,
        outDir: 'public/build',
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['alpinejs'],
                },
                chunkFileNames: 'assets/js/[name]-[hash].js',
                entryFileNames: 'assets/js/[name]-[hash].js',
                assetFileNames: 'assets/[ext]/[name]-[hash].[ext]',
            },
        },
        chunkSizeWarningLimit: 1000,
    },
    server: {
        hmr: {
            host: 'localhost'
        },
        watch: {
            usePolling: true,
        },
        headers: {
            'Access-Control-Allow-Origin': '*'
        }
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '~': path.resolve(__dirname, 'node_modules'),
            '@components': path.resolve(__dirname, 'resources/js/components'),
            '@managers': path.resolve(__dirname, 'resources/js/managers'),
            '@utils': path.resolve(__dirname, 'resources/js/utils'),
            '$': 'jquery',
            'jQuery': 'jquery'
        },
    },
    optimizeDeps: {
        include: ['alpinejs', 'jquery'],
    }
});
