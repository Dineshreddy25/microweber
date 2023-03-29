import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
export default defineConfig({
    build: {
        assetsInlineLimit: 0,
        outDir: './public/build'
    },
    plugins: [
        vue(),
        laravel({
            input: [
               // 'resources/css/app.css',
              // 'resources/js/app.js',
              //  'userfiles/modules/microweber/api/live-edit-app/index.js',
                 'userfiles/modules/microweber/api/live-edit-app/app.js',
                'resources/css/microweber-admin-filament.css',
            ],
            publicDirectory: "public",
            refresh: true,
        })
    ]
});
