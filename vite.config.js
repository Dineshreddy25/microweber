import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import istanbul from 'vite-plugin-istanbul';
import sass from 'sass';
export default defineConfig({
    build: {
        open: true,
        port: 3000,
        assetsInlineLimit: 0,
        outDir: './public/build',
        manifest: "manifest.json",
        rollupOptions: {
            output: {
                globals: {
                    jquery: 'window.jQuery',
                    $: 'window.$',
                    mw: 'window.mw',
                }
            }
        },

        target: 'esnext'


    },
    css: {
        preprocessorOptions: {
            scss: {
                implementation: sass,
            }
        }
    },
    plugins: [

        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        laravel({
            input: [
               // 'resources/css/app.css',
              // 'resources/js/app.js',
              //  'userfiles/modules/microweber/api/live-edit-app/index.js',
               // 'userfiles/modules/microweber/api/live-edit-app/app.js',
                'src/MicroweberPackages/LiveEdit/resources/js/api-core/core/css/scss/liveedit.scss',
                'src/MicroweberPackages/LiveEdit/resources/js/ui/live-edit-app.js',

                'src/MicroweberPackages/LiveEdit/resources/js/ui/live-edit-page-scripts.js',


                'src/MicroweberPackages/LiveEdit/resources/js/ui/admin-app.js',
                // 'src/MicroweberPackages/LiveEdit/resources/js/ui/admin-filament-app.js',
                'src/MicroweberPackages/LiveEdit/resources/front-end/js/admin/admin-filament-libs.js',
                'src/MicroweberPackages/LiveEdit/resources/front-end/js/admin/admin-filament-app.js',

                'src/MicroweberPackages/LiveEdit/resources/js/ui/css/admin-filament.scss',
                'src/MicroweberPackages/Filament/resources/js/tiny-editor.js',


                'src/MicroweberPackages/LiveEdit/resources/js/ui/apps/ElementStyleEditor/element-style-editor-app.js',
                'src/MicroweberPackages/Multilanguage/resources/js/filament-translatable.js',

                'resources/css/filament/admin/theme.css',
            ],
            publicDirectory: "public",
            refresh: true,
        }),


        // istanbul({
        //     include: 'src/MicroweberPackages/LiveEdit/*',
        //     exclude: ['node_modules', 'tests/'],
        //     extension: [ '.js', '.ts', '.vue' ],
        //     forceBuildInstrument: true,
        //     requireEnv: true,
        //     //requireEnv: false,
        // })

    ]
});
