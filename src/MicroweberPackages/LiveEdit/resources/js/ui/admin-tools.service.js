import {DomHelpers} from "../../../../../../userfiles/modules/microweber/js/tools/domhelpers.js";

export class AdminTools {
    constructor(app) {
        this._app = app;
        this.extend(DomHelpers)
    }

    index(el, parent, selector) {
        el = mw.$(el)[0];
        selector = selector || el.tagName.toLowerCase();
        parent = parent || el.parentNode;
        var all;
        if (parent.constructor === [].constructor) {
            all = parent;
        }
        else {
            all = mw.$(selector, parent)
        }
        var i = 0, l = all.length;
        for (; i < l; i++) {
            if (el === all[i]) return i;
        }
    }

    extend(methods = {}) {
        for (let i in methods) {
            this[i] = methods[i];
        }
    }

}
