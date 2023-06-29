mw.require("options.js");mw.liveEditSaveService={grammarlyFix:function(e){return mw.$("grammarly-btn",e).remove(),mw.$("grammarly-card",e).remove(),mw.$("g.gr_",e).each(function(){mw.$(this).replaceWith(this.innerHTML)}),mw.$("[data-gramm_id]",e).removeAttr("data-gramm_id"),mw.$("[data-gramm]",e).removeAttr("data-gramm"),mw.$("[data-gramm_id]",e).removeAttr("data-gramm_id"),mw.$("grammarly-card",e).remove(),mw.$("grammarly-inline-cards",e).remove(),mw.$("grammarly-popups",e).remove(),mw.$("grammarly-extension",e).remove(),e},saving:!1,coreSave:function(e){if(!e)return!1;$.each(e,function(){var r=mw.tools.parseHtml(this.html).body;mw.liveEditSaveService.grammarlyFix(r),mw.liveEditSaveService.animationsClearFix(r),this.html=r.innerHTML}),mw.liveEditSaveService.saving=!0,e=JSON.stringify(e),e=btoa(encodeURIComponent(e).replace(/%([0-9A-F]{2})/g,function(a,i){return String.fromCharCode("0x"+i)})),e={data_base64:e};var t=mw.ajax({type:"POST",url:mw.settings.api_url+"save_edit",data:e,dataType:"json",success:function(r){r&&r.new_page_url&&!mw.liveEditSaveService.DraftSaving&&(window.mw.parent().askusertostay=!1,window.mw.askusertostay=!1,window.location.href=r.new_page_url)}});return t.always(function(){mw.liveEditSaveService.saving=!1}),t},parseContent:function(e){e=e||document.body;var t=mw.tools.parseHtml(e.innerHTML);mw.$(".element-current",t).removeClass("element-current"),mw.$(".element-active",t).removeClass("element-active"),mw.$(".disable-resize",t).removeClass("disable-resize"),mw.$(".mw-module-drag-clone",t).removeClass("mw-module-drag-clone"),mw.$(".ui-draggable",t).removeClass("ui-draggable"),mw.$(".ui-draggable-handle",t).removeClass("ui-draggable-handle"),mw.$(".mt-ready",t).removeClass("mt-ready"),mw.$(".mw-webkit-drag-hover-binded",t).removeClass("mw-webkit-drag-hover-binded"),mw.$(".module-cat-toggle-Modules",t).removeClass("module-cat-toggle-Modules"),mw.$(".mw-module-drag-clone",t).removeClass("mw-module-drag-clone"),mw.$("-module",t).removeClass("-module"),mw.$(".empty-element",t).remove(),mw.$(".empty-element",t).remove(),mw.$(".edit .ui-resizable-handle",t).remove(),mw.$("script",t).remove(),mw.tools.classNamespaceDelete("all","ui-",t,"starts"),mw.$("[contenteditable]",t).removeAttr("contenteditable");for(var r=t.querySelectorAll("[contenteditable]"),a=r.length,i=0;i<a;i++)r[i].removeAttribute("contenteditable");for(var r=t.querySelectorAll(".module"),a=r.length,i=0;i<a;i++)r[i].querySelector(".edit")===null&&(r[i].innerHTML="");return t},htmlAttrValidate:function(e){var t=[];return $.each(e,function(){var r=this.outerHTML;r=r.replace(/url\(&quot;/g,"url('"),r=r.replace(/jpg&quot;/g,"jpg'"),r=r.replace(/jpeg&quot;/g,"jpeg'"),r=r.replace(/png&quot;/g,"png'"),r=r.replace(/gif&quot;/g,"gif'"),t.push($(r)[0])}),t},cleanUnwantedTags:function(e){return mw.$(".mw-skip-and-remove,script",e).remove(),e},animationsClearFix:function(e){return mw.$('[class*="animate__"]').each(function(){mw.tools.classNamespaceDelete(this,"animate__")}),e},collectData:function(e){mw.$(e).each(function(){mw.$("meta",this).remove(),$(".mw-le-spacer",this).empty().removeAttr("data-resizable").removeAttr("style")}),e=this.htmlAttrValidate(e);var t=e.length,r=0,a={},i={};if(t>0)for(;r<t;r++){a.item=e[r];var v=mw.tools.mwattr(a.item,"rel");v||(mw.$(a.item).removeClass("changed"),mw.tools.foreachParents(a.item,function(s){var w=this.className,f=mw.tools.mwattr(this,"rel");mw.tools.hasClass(w,"edit")&&mw.tools.hasClass(w,"changed")&&f&&(a.item=this,mw.tools.stopLoop(s))}));var v=mw.tools.mwattr(a.item,"rel");if(!v){var c=a.item.id?"#"+a.item.id:"";console.warn("Skipped save: .edit"+c+" element does not have rel attribute.");continue}mw.$(a.item).removeClass("changed orig_changed"),mw.$(a.item).removeClass("module-over"),mw.$(".module-over",a.item).each(function(){mw.$(this).removeClass("module-over")}),mw.$("[class]",a.item).each(function(){var s=this.getAttribute("class");typeof s=="string"&&(s=s.trim()),s||this.removeAttribute("class")});var l=mw.liveEditSaveService.cleanUnwantedTags(a.item).innerHTML,m={},o=a.item.attributes;if(o.length>0)for(var n=0,d=o.length;n<d;n++)m[o[n].nodeName]=o[n].nodeValue;var u={attributes:m,html:l},g="field_data_"+r;i[g]=u}return i},getData:function(e){var t=mw.liveEditSaveService.parseContent(e).body,r=t.querySelectorAll(".edit.changed");return mw.liveEditSaveService.collectData(r)},saveDisabled:!1,draftDisabled:!1,save:async function(e,t,r){if(mw.trigger("beforeSaveStart",e),mw.liveedit&&mw.liveedit.cssEditor&&mw.liveedit.cssEditor.publishIfChanged(),mw.liveEditSaveService.saveDisabled)return!1;if(!e){var a=mw.liveEditSaveService.parseContent().body,i=a.querySelectorAll(".edit.changed");e=mw.liveEditSaveService.collectData(i)}var v=(mw.__pageAnimations||[]).filter(function(m){return m.animation!=="none"}),c={group:"template",key:"animations-global",value:JSON.stringify(v)};if(await new Promise(m=>{mw.options.saveOption(c,function(){m()})}),mw.tools.isEmptyObject(e))return t&&t.call({}),!1;mw._liveeditData=e,mw.trigger("saveStart",mw._liveeditData);var l=mw.liveEditSaveService.coreSave(mw._liveeditData);return l.error(function(m){l.status==403&&(mw.dialog({id:"save_content_error_iframe_modal",html:"<iframe id='save_content_error_iframe' style='overflow-x:hidden;overflow-y:auto;' class='mw-modal-frame' ></iframe>",width:$(window).width()-90,height:$(window).height()-90}),mw.askusertostay=!1,mw.$("#save_content_error_iframe").ready(function(){var o=document.getElementById("save_content_error_iframe").contentWindow.document;o.open(),o.write(l.responseText),o.close();var n=0;o=document.getElementById("save_content_error_iframe").contentWindow.document,mw.$("#save_content_error_iframe").load(function(){var d=mw.$(".challenge-form",o).length;n++,d&&n==2&&setTimeout(function(){mw.askusertostay=!1,mw.$("#save_content_error_iframe_modal").remove()},150)})})),r&&r.call(m)}),l.success(function(m){mw.$(".edit.changed").removeClass("changed"),mw.$(".orig_changed").removeClass("orig_changed"),document.querySelector(".edit.changed")!==null?mw.liveEditSaveService.save():(mw.askusertostay=!1,mw.trigger("saveEnd",m)),t&&t.call(m)}),l.fail(function(m,o,n){mw.trigger("saveFailed",o,n),r&&r.call(sdata)}),l}};addEventListener("load",()=>{const e=async()=>new Promise(t=>{mw.liveEditSaveService.save(void 0,()=>t(!0),()=>t(!1))});mw.top().app.save=async()=>await e(),window.addEventListener("keydown",function(t){mw.top().app.canvas.dispatch("iframeKeyDown",{event:t})})});
