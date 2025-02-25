import { defineConfig } from 'vite';
import { resolve } from 'path';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
//import ViteAliases from 'vite-aliases';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
          host: '127.0.0.1', // Use 127.0.0.1 for HMR
        },
    },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        //ViteAliases(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
        },
    },
});
