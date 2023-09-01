import  MicroweberBaseClass  from "../../containers/base-class.js";


export class LiveEditCanvas extends MicroweberBaseClass {

    constructor() {
        super();
    }

    #canvas = null;

    go(url) {
        if(this.#canvas && this.#canvas.ownerDocument && this.#canvas.contentWindow) {
            this.#canvas.src = url;
        }
    }

    refresh() {
        if(this.#canvas && this.#canvas.ownerDocument && this.#canvas.contentWindow) {
            this.#canvas.contentWindow.location.reload();
        }
    }

    getFrame(){
        if(this.#canvas && this.#canvas.ownerDocument) {
            return this.#canvas;
        }
    }

    getWindow(){
        if(this.#canvas && this.#canvas.ownerDocument) {
            return this.#canvas.contentWindow;
        }
    }
    getDocument() {
        if(this.#canvas && this.#canvas.ownerDocument) {
            return this.#canvas.contentWindow.document;
        }
    }

    getLiveEditData() {
        var liveEditIframe = this.getWindow();
        if (liveEditIframe
            && typeof liveEditIframe.mw !== 'undefined'
            && typeof liveEditIframe.mw.liveEditIframeData !== 'undefined'
            && liveEditIframe.mw.liveEditIframeData

        ) {
            return liveEditIframe.mw.liveEditIframeData;
        }
        return false;
    }

    mount(target) {

        this.dispatch('liveEditBeforeLoaded');


        mw.spinner({
            element: target,
            size: 52,
            decorate: true
        });

        const liveEditIframe = document.createElement('iframe');
        let url = mw.settings.site_url;
        const qurl = new URLSearchParams(window.top.location.search).get('url');

        if (qurl) {
            url = decodeURIComponent(qurl)
        }

        url = new URL(url);

        url.searchParams.set('editmode', 'iframe');


        if(url.host !== top.location.host) {
            url = `${mw.settings.site_url}?editmode=iframe`;
        }
        liveEditIframe.src = url.toString();

        liveEditIframe.frameBorder = 0;
        liveEditIframe.id="live-editor-frame";
        liveEditIframe.referrerPolicy = "no-referrer";
        liveEditIframe.loading = "lazy";

        this.#canvas = liveEditIframe;
        target.innerHTML = '';
        target.appendChild(liveEditIframe);


        window.onbeforeunload = function () {
            if(liveEditIframe && liveEditIframe.contentWindow && liveEditIframe.contentWindow.mw
           && liveEditIframe.contentWindow.mw.askusertostay){
               // prevent user from leaving the page
               return true;
           }
         };





        liveEditIframe.addEventListener('load', e => {

            mw.spinner({element: target, decorate: true}).remove();


            // liveEditIframe.contentWindow.addEventListener('beforeunload', event => {
            //     mw.spinner({element: target, decorate: true, size: 52}).show()
            //
            // });

            liveEditIframe.contentWindow.document.body.addEventListener('click', (event) => {

                this.dispatch('canvasDocumentClick', event)
                 

            });


            liveEditIframe.contentWindow.document.addEventListener('keydown', (event) => {

                this.dispatch('canvasDocumentKeydown', event)
                 

            });

            


            liveEditIframe.contentWindow.document.body.addEventListener('dblclick', (event) => {
                this.dispatch('canvasDocumentDoubleClick',  event)
                 
            });


            this.dispatch('liveEditCanvasLoaded', {frame: liveEditIframe, frameWindow: liveEditIframe.contentWindow, frameDocument: liveEditIframe.contentWindow.document});
            mw.spinner({
                element: target,
            }).remove()
        });
    }
}
