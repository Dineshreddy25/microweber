


//import {MWUniversalContainer} from '../../../input/front-end/js/containers/container.js';
// input/front-endjs/containers/container.js

if(!window.mw ) {
    window.mw  = {};
}


// window.mw.container = new MWUniversalContainer();


mw.required = [] ;
mw.require = function(url, inHead, key, defered) {
    if(!url) return;
    var defer;
    if(defered) {
        defer = ' defer ';
    } else {
        defer = '   '
    }
    if(typeof inHead === 'boolean' || typeof inHead === 'undefined'){
        inHead = inHead || false;
    }
    var keyString;
    if(typeof inHead === 'string'){
        keyString = ''+inHead;
        inHead = key || false;
    }
    if(typeof key === 'string'){
        keyString = key;
    }
    var toPush = url, urlModified = false;
    if (!!keyString) {
        toPush = keyString;
        urlModified = true
    }
    var t = url.split('.').pop();
    url = url.includes('//') ? url : (t !== "css" ? mw.settings.includes_url + "api/" + url  :  mw.settings.includes_url + "css/" + url);
    if(!urlModified) toPush = url;
    if (!~mw.required.indexOf(toPush)) {

      mw.required.push(toPush);
      url = url.includes("?") ?  url + '&mwv=' + mw.version : url + "?mwv=" + mw.version;
      if(document.querySelector('link[href="'+url+'"],script[src="'+url+'"]') !== null){
          return
      }

      var cssRel = " rel='stylesheet' ";

      if(defered){
        cssRel = " rel='preload' as='style' onload='this.onload=null;this.rel=\'stylesheet\'' ";
      }


      var string = t !== "css" ? "<script "+defer+"  src='" + url + "'></script>" : "<link "+cssRel+" href='" + url + "' />";

          if(typeof $.fn === 'object'){
              $(mw.head).append(string);
          }
          else{
              var el;
              if( t !== "css")  {
                  el = document.createElement('script');
                  el.src = url;
                  el.defer = !!defer;
                  el.setAttribute('type', 'text/javascript');
                  mw.head.appendChild(el);
              }
              else{

                 el = document.createElement('link');
                 if(defered) {
                    el.as='style';
                    el.rel='preload';
                    el.addEventListener('load', e => el.rel='stylesheet');
                 } else {
                    el.rel='stylesheet';
                 }


                 el.href = url;
                 mw.head.appendChild(el);
              }
          }

    }
  };



