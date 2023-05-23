import axios from "axios";
import { ElementManager } from "../../core/classes/element";
 

export const insertModule = (target = null, module, options = {}, docu) => {
    if (!target || !module) {
        return;
    }
    return new Promise(async resolve => {
        await target.ownerDocument.defaultView.mw.module.insert(target, module, options, 'top', mw.liveEditState);
        
        mw.top().win.mw.app.liveEdit.handles.get('element').set(mw.top().win.mw.app.liveEdit.handles.get('element').getTarget())
        resolve();
    });
}