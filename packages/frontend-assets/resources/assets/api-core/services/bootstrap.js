import './index.js'

import {MWUniversalContainer} from "./containers/container.js";


import {Commands} from "./services/commands.js";
import {Modules} from "./services/modules.js";
import {Layouts} from "./services/layouts.js";
import {License} from "./services/license.js";
import {KeyboardEvents} from "./services/keyboard-events.js";
import {IconPicker} from "./services/icon-picker";
import {LinkPicker} from "./services/link-picker";
import {ColorPicker} from "./services/color-picker";

import {normalizeBase64Images, normalizeBase64Image} from "../../tools/base64-images.js";
//import '@nextapps-be/livewire-sortablejs';

// other libs
//import Alpine from 'alpinejs';
//import focus from '@alpinejs/focus';
import {DynamicTargetMenus} from './services/dynamic-target-menus.js';
import {LiveEditCanvas} from "./components/live-edit-canvas/live-edit-canvas";
import {SingleFilePickerComponent} from './services/single-file-picker-component.js';
import {MWBroadcast} from './services/broadcast.js';
import {MWDocumentFocus} from './services/document.focus.service.js';
import {MWPageAlreadyOpened} from './components/live-edit/page-already-opened.service.js';

if (!mw.app) {
    mw.app = new MWUniversalContainer();
}



const canvasSetUrlInterceptor = async (url) => {
    return new Promise((resolve) => {
        if (mw.top().app.documentFocus.isActive()) {

        } else {

        }
    });
};


const canvas = new LiveEditCanvas({
    onSetUrl: canvasSetUrlInterceptor
});


mw.app.register('documentFocus', MWDocumentFocus);
mw.app.register('broadcast', MWBroadcast);
mw.app.register('canvas', canvas);
mw.app.register('commands', Commands);
mw.app.register('modules', Modules);

mw.app.register('layouts', Layouts);
mw.app.register('license', License);
mw.app.register('keyboard', KeyboardEvents);
mw.app.register('iconPicker', IconPicker);
mw.app.register('linkPicker', LinkPicker);
mw.app.register('colorPicker', ColorPicker);
mw.app.register('dynamicTargetMenus', DynamicTargetMenus);
mw.app.register('pageAlreadyOpened', MWPageAlreadyOpened);


mw.app.normalizeBase64Image = normalizeBase64Image;
mw.app.normalizeBase64Images = normalizeBase64Images;

mw.app.singleFilePickerComponent = options => {
    return new SingleFilePickerComponent(options)
};


let sameUrlDialog = false;


const handleSameUrl = async () => {
    let url = mw.top().app.canvas.getUrl();

    const isLiveEdit = !!mw.top().app.canvas && mw.top().app.canvas.getWindow() && (self === top || self.frameElement.id === 'live-editor-frame');


    if (isLiveEdit && sameUrlDialog) {
        if (mw.top().app.canvas.getWindow() && mw.top().app.canvas.isUrlOpened(url) && mw.top().app.canvas.isUrlSame(url)) {


            const action = await mw.app.pageAlreadyOpened.handle(url);

            if (action) {

            } else {
                mw.top().win.location.href = mw.top().settings.adminUrl;
            }

        }
    }
}


mw.top().app.canvas.on('liveEditCanvasLoaded', async function () {
    await handleSameUrl();
});

mw.top().storage.change('mw-broadcast-data', async function () {

    await handleSameUrl();


});


//mw.app.register('commands', Commands);

// mw.app.domTreeSelect = function(node) {
//     // deprecated
//     // if(!mw.top().app._liveEditDomTree) {
//     //     return;
//     // }
//     //
//     // mw.top().app._liveEditDomTree.select(node)
// }


//}, 300);

// init other libs
// window.Alpine = Alpine;
// Alpine.plugin(focus);

//Alpine.start();
