import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from 'tailwindcss';
import postcss from 'postcss';
import autoprefixer from 'autoprefixer';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    css: {
        postcss: {
            plugins: [
                tailwindcss,
                autoprefixer({
                    // Browser support now comes from .browserslistrc file
                    grid: true,
                    flexbox: true
                }),
            ],
        },
        // Improve CSS processing
        devSourcemap: true,
        preprocessorOptions: {
            postcss: {
                javascriptEnabled: true,
            },
        },
    },
    server: {
        host: '0.0.0.0', // Allow external connections
        port: 5173,
        cors: true, // Enable CORS
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers': 'Content-Type, Authorization',
        },
        hmr: {
            host: 'localhost',
        },
    },
    build: {
        rollupOptions: {
            external: [],
        },
        // Generate manifest for better asset loading
        manifest: true,
        // Ensure assets have correct paths for external domains
        assetsDir: 'assets',
        outDir: 'public/build',
    },
    // Support for external tunnel URLs
    base: process.env.ASSET_URL ? `${process.env.ASSET_URL}/build/` : '/build/',
});