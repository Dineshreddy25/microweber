const entry = {
   'admin-filament-libs': './resources/assets/js/admin-filament-libs.js',

   'admin': './resources/assets/js/admin.js',
   'live-edit-page-scripts': './resources/assets/live-edit/live-edit-page-scripts.js',
   core: './resources/assets/js/core.js',
   admin: './resources/assets/js/admin.js',
   admincss: './resources/assets/css/admin.scss',
   imageeditor: './node_modules/filerobot-image-editor/lib/index.js',
};

const outputJS = `./resources/dist/js`;
const outputCSS = `../css`; // relative to outputJS


export const config = {
    entry, outputJS, outputCSS
}
