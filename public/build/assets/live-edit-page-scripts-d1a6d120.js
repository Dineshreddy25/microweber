if(window.self!==window.top){mw.require("liveedit.css"),mw.liveEditSaveService={grammarlyFix:function(e){return mw.$("grammarly-btn",e).remove(),mw.$("grammarly-card",e).remove(),mw.$("g.gr_",e).each(function(){mw.$(this).replaceWith(this.innerHTML)}),mw.$("[data-gramm_id]",e).removeAttr("data-gramm_id"),mw.$("[data-gramm]",e).removeAttr("data-gramm"),mw.$("[data-gramm_id]",e).removeAttr("data-gramm_id"),mw.$("grammarly-card",e).remove(),mw.$("grammarly-inline-cards",e).remove(),mw.$("grammarly-popups",e).remove(),mw.$("grammarly-extension",e).remove(),e},saving:!1,coreSave:function(e){if(!e)return!1;$.each(e,function(){var t=mw.tools.parseHtml(this.html).body;mw.liveEditSaveService.grammarlyFix(t),mw.liveEditSaveService.animationsClearFix(t),this.html=t.innerHTML}),mw.liveEditSaveService.saving=!0,e=JSON.stringify(e),e=btoa(encodeURIComponent(e).replace(/%([0-9A-F]{2})/g,function(i,r){return String.fromCharCode("0x"+r)})),e={data_base64:e};var a=mw.ajax({type:"POST",url:mw.settings.api_url+"save_edit",data:e,dataType:"json",success:function(t){t&&t.new_page_url&&!mw.liveEditSaveService.DraftSaving&&(window.mw.parent().askusertostay=!1,window.mw.askusertostay=!1,window.location.href=t.new_page_url)}});return a.always(function(){mw.liveEditSaveService.saving=!1}),a},parseContent:function(e){e=e||document.body;var a=mw.tools.parseHtml(e.innerHTML);mw.$(".element-current",a).removeClass("element-current"),mw.$(".element-active",a).removeClass("element-active"),mw.$(".disable-resize",a).removeClass("disable-resize"),mw.$(".mw-module-drag-clone",a).removeClass("mw-module-drag-clone"),mw.$(".ui-draggable",a).removeClass("ui-draggable"),mw.$(".ui-draggable-handle",a).removeClass("ui-draggable-handle"),mw.$(".mt-ready",a).removeClass("mt-ready"),mw.$(".mw-webkit-drag-hover-binded",a).removeClass("mw-webkit-drag-hover-binded"),mw.$(".module-cat-toggle-Modules",a).removeClass("module-cat-toggle-Modules"),mw.$(".mw-module-drag-clone",a).removeClass("mw-module-drag-clone"),mw.$("-module",a).removeClass("-module"),mw.$(".empty-element",a).remove(),mw.$(".empty-element",a).remove(),mw.$(".edit .ui-resizable-handle",a).remove(),mw.$("script",a).remove(),mw.tools.classNamespaceDelete("all","ui-",a,"starts"),mw.$("[contenteditable]",a).removeAttr("contenteditable");for(var t=a.querySelectorAll("[contenteditable]"),i=t.length,r=0;r<i;r++)t[r].removeAttribute("contenteditable");for(var t=a.querySelectorAll(".module"),i=t.length,r=0;r<i;r++)t[r].querySelector(".edit")===null&&(t[r].innerHTML="");return a},htmlAttrValidate:function(e){var a=[];return $.each(e,function(){var t=this.outerHTML;t=t.replace(/url\(&quot;/g,"url('"),t=t.replace(/jpg&quot;/g,"jpg'"),t=t.replace(/jpeg&quot;/g,"jpeg'"),t=t.replace(/png&quot;/g,"png'"),t=t.replace(/gif&quot;/g,"gif'"),a.push($(t)[0])}),a},cleanUnwantedTags:function(e){return mw.$(".mw-skip-and-remove,script",e).remove(),e},animationsClearFix:function(e){return mw.$('[class*="animate__"]').each(function(){mw.tools.classNamespaceDelete(this,"animate__")}),e},collectData:function(e){mw.$(e).each(function(){mw.$("meta",this).remove(),$(".mw-le-spacer",this).empty().removeAttr("data-resizable").removeAttr("style")}),e=this.htmlAttrValidate(e);var a=e.length,t=0,i={},r={};if(a>0)for(;t<a;t++){i.item=e[t];var o=mw.tools.mwattr(i.item,"rel");o||(mw.$(i.item).removeClass("changed"),mw.tools.foreachParents(i.item,function(d){var u=this.className,g=mw.tools.mwattr(this,"rel");mw.tools.hasClass(u,"edit")&&mw.tools.hasClass(u,"changed")&&g&&(i.item=this,mw.tools.stopLoop(d))}));var o=mw.tools.mwattr(i.item,"rel");if(!o){var c=i.item.id?"#"+i.item.id:"";console.warn("Skipped save: .edit"+c+" element does not have rel attribute.");continue}mw.$(i.item).removeClass("changed orig_changed"),mw.$(i.item).removeClass("module-over"),mw.$(".mw-le-ghost-layout",i.item).remove(),mw.$("#mw-non-existing-temp-element-holder",i.item).remove(),mw.$(".module-over",i.item).each(function(){mw.$(this).removeClass("module-over")}),mw.$(".element[data-mwplaceholder]",i.item).each(function(){var d=!this.innerHTML.trim();d||mw.$(this).removeAttr("data-mwplaceholder")}),mw.$(".element.lipsum",i.item).each(function(){mw.$(this).removeClass("lipsum")}),mw.$("[data-mw-live-edithover]",i.item).each(function(){mw.$(this).removeAttr("data-mw-live-edithover")}),mw.$("[data-mw-temp-option-save]",i.item).each(function(){mw.$(this).removeAttr("data-mw-temp-option-save")}),mw.$("[class]",i.item).each(function(){var d=this.getAttribute("class");typeof d=="string"&&(d=d.trim()),d||this.removeAttribute("class")});var s=mw.liveEditSaveService.cleanUnwantedTags(i.item).innerHTML,n={},m=i.item.attributes;if(m.length>0)for(var l=0,w=m.length;l<w;l++)n[m[l].nodeName]=m[l].nodeValue;var p={attributes:n,html:s},f="field_data_"+t;r[f]=p}return r},getData:function(e){var a=mw.liveEditSaveService.parseContent(e).body,t=a.querySelectorAll(".edit.changed");return mw.liveEditSaveService.collectData(t)},saveDisabled:!1,draftDisabled:!1,save:function(e,a,t){if(mw.trigger("beforeSaveStart",e),mw.top().app&&mw.top().app&&mw.top().app.cssEditor&&mw.top().app.cssEditor.publishIfChanged(),mw.top().app&&mw.top().app&&mw.top().options&&mw.top().options.publishTempOptions(document),mw.liveEditSaveService.saveDisabled)return!1;if(!e){var i=mw.liveEditSaveService.parseContent().body,r=i.querySelectorAll(".edit.changed");e=mw.liveEditSaveService.collectData(r)}var o=(mw.__pageAnimations||[]).filter(function(n){return n.animation!=="none"});if(o&&o.length>0){var c={group:"template",key:"animations-global",value:JSON.stringify(o)};mw.top().options.saveOption(c)}if(mw.tools.isEmptyObject(e))return a&&a.call({}),!1;mw._liveeditData=e,mw.trigger("saveStart",mw._liveeditData);var s=mw.liveEditSaveService.coreSave(mw._liveeditData);return s.error(function(n){s.status==403&&(mw.dialog({id:"save_content_error_iframe_modal",html:"<iframe id='save_content_error_iframe' style='overflow-x:hidden;overflow-y:auto;' class='mw-modal-frame' ></iframe>",width:$(window).width()-90,height:$(window).height()-90}),mw.askusertostay=!1,mw.$("#save_content_error_iframe").ready(function(){var m=document.getElementById("save_content_error_iframe").contentWindow.document;m.open(),m.write(s.responseText),m.close();var l=0;m=document.getElementById("save_content_error_iframe").contentWindow.document,mw.$("#save_content_error_iframe").load(function(){var w=mw.$(".challenge-form",m).length;l++,w&&l==2&&setTimeout(function(){mw.askusertostay=!1,mw.$("#save_content_error_iframe_modal").remove()},150)})})),t&&t.call(n)}),s.success(function(n){mw.$(".edit.changed").removeClass("changed"),mw.$(".orig_changed").removeClass("orig_changed"),document.querySelector(".edit.changed")!==null?mw.liveEditSaveService.save():(mw.askusertostay=!1,mw.trigger("saveEnd",n)),a&&a.call(n)}),s.fail(function(n,m,l){mw.trigger("saveFailed",m,l),t&&t.call(sdata)}),s}},mw.saveLiveEdit=async()=>new Promise(e=>{mw.liveEditSaveService.save(void 0,()=>e(!0),()=>e(!1))}),mw.top().app.save=async()=>await mw.saveLiveEdit(),addEventListener("load",()=>{window.addEventListener("keydown",function(a){mw.top().app.canvas.dispatch("iframeKeyDown",{event:a})}),function(){function a(t){const i=!t.innerHTML.trim();i&&t.innerHTML.trim()===t.textContent.trim()?mw.element(t).append(`<p class="element" data-mwplaceholder="${mw.lang("This is sample text for your page")}"></p>`):t.classList[i?"add":"remove"]("mw-le-empty-element")}document.querySelectorAll(".edit").forEach(function(t){t.__$$_handleEmptyEditFields||(t.__$$_handleEmptyEditFields=!0,a(t),t.addEventListener("input",function(){a(this)}))}),mw.top().app.on("editChanged",t=>{setTimeout(()=>a(t))})}()});let v=null;mw.top().app.isNavigating=()=>!!v&&v.returnValue&&v.defaultPrevented===!0,self.onbeforeunload=function(e){mw.top().app.canvas.dispatch("liveEditCanvasBeforeUnload"),v=e,console.log(e);var a=mw.top().app.canvas.getWindow();if(a&&a.mw&&a.mw.askusertostay)return a.mw.isNavigating=!0,setTimeout(function(t){t&&t.mw&&(t.mw.isNavigating=!1)},300),!0;a.mw.isNavigating=!0,mw.top().spinner({element:mw.top().app.canvas.getFrame().parentElement,decorate:!0,size:52}).show()},mw.drag=mw.drag||{},mw.drag.save=function(){return mw.liveEditSaveService.save()},mw.drag.fix_placeholders=function(e,a){a=a||".edit .row";var t="div.col-md",i=mw.top().app.templateSettings.helperClasses.external_grids_col_classes,r;for(r=i.length-1;r>=0;--r)t+=",div."+i[r];mw.$(a).each(function(){var o=mw.$(this);o.children(t).each(function(){var c=mw.$(this).children("*");if(c.size()==0){mw.$(this).append('<div class="element" id="mw-element-'+mw.random()+'"></div>');var c=mw.$(this).children("div.element")}})})},mw.drag.module_settings=function(){var e=mw.top().app.liveEdit.moduleHandle.getTarget();return mw.top().app.editor.dispatch("onModuleSettingsRequest",e)},document.addEventListener("keydown",function(e){if(e.ctrlKey&&e.key==="s")return mw.top().app.editor.dispatch("Ctrl+S",e)})}self===top&&window.addEventListener("load",v=>{if(window.mwLiveEditIframeBackUrl){var e=document.createElement("a");e.id="back-to-live-sticky-button",e.textContent="Live Edit",e.href=window.mwLiveEditIframeBackUrl,e.classList.add("sticky"),document.body.appendChild(e),e.classList.add("sticky");var a=document.createElement("style");a.textContent=`
                #back-to-live-sticky-button {
                    position: absolute;
                    left: 50%;
                    transform: translateX(-50%);
                    z-index: 999;
                    transition: top 0.3s;
                    background: #000;
                    padding: 10px 20px;
                    border-radius: 5px;
                    border-top-left-radius: 0;
                    border-top-right-radius: 0;
                }
                 a#back-to-live-sticky-button {
                 color: #fff;
                 }
                #back-to-live-sticky-button.sticky {
                    top: 0;
                }
            `,document.head.appendChild(a)}});
