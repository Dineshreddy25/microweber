



mw.CSSParser = function(el){
    if(el && el.get) {
        el = el.get(0);
    }
    if(!el || !el.nodeName) {
        return false;
    }
    if(el.nodeName === '#text') {
        return false;
    }


    try {
        var css = window.getComputedStyle(el, null);
    } catch(error) {
        return;
    }


    var f = {};

    f.display = function(){
      return css.display;
    };

    f.is = function(){

        return {
          bold: parseFloat(css.fontWeight) >= 600 || css.fontWeight === 'bold' || css.fontWeight === 'bolder',
          italic: css.fontStyle === 'italic'||css.fontStyle === 'oblique',
          underlined: css.textDecoration.indexOf('underline') === 0,
          striked: css.textDecoration.indexOf('line-through') === 0,
        };
    };
    f.font = function(){
      if(css === null) return false;
      return {
        size:css.fontSize,
        weight:css.fontWeight,
        style:css.fontStyle,
        height:css.lineHeight,
        family:css.fontFamily,
        color:css.color
      };
    }

    f.letterSpacing  = function(){
        if(!!css){
            return css.letterSpacing || '';
        }
    }

    f.wordSpacing = function() {
        if (!!css) {
            return css.wordSpacing || '';
        }
    }

    f.alignNormalize = function(){
        if(!!css){
        return css.textAlign.contains('left')?'left':css.textAlign.contains('center')?'center':css.textAlign.contains('justify')?'justify':css.textAlign.contains('right')?'right':'left';
      }
    }
    f.border = function(parse){
        if(!parse){
          return {
              top:{width:css.borderTopWidth, style:css.borderTopStyle, color:css.borderTopColor},
              left:{width:css.borderLeftWidth, style:css.borderLeftStyle, color:css.borderLeftColor},
              right:{width:css.borderRightWidth, style:css.borderRightStyle, color:css.borderRightColor},
              bottom:{width:css.borderBottomWidth, style:css.borderBottomStyle, color:css.borderBottomColor}
          }
        }
        else{
          return {
              top:{width:parseFloat(css.borderTopWidth), style:css.borderTopStyle, color:css.borderTopColor},
              left:{width:parseFloat(css.borderLeftWidth), style:css.borderLeftStyle, color:css.borderLeftColor},
              right:{width:parseFloat(css.borderRightWidth), style:css.borderRightStyle, color:css.borderRightColor},
              bottom:{width:parseFloat(css.borderBottomWidth), style:css.borderBottomStyle, color:css.borderBottomColor}
          }
        }

    }
    f.width = function(){
        return css.width;
    }
    f.position = function(){
        return css.position;
    }
    f.textTransform  = function(){
        return css.textTransform ;
    }
    f.background = function(){
        return {
            image:css.backgroundImage,
            color:css.backgroundColor,
            position:css.backgroundPosition,
            clip:css.backgroundClip,
            repeat:css.backgroundRepeat
        }
    }
    f.margin = function(parse, actual){
        if(actual){
            var _parent = el.parentNode;
            var parentOff = mw.$(_parent).offset();
            var elOff = mw.$(el).offset();
            if(elOff.left > parentOff.left && css.marginLeft === css.marginRight && elOff.left - parentOff.left === parseInt(css.marginLeft, 10)){
                return {
                    top:css.marginTop,
                    left:'auto',
                    right:'auto',
                    bottom:css.marginBottom
                };
            }
      }
      if(!parse){
        return {
          top:css.marginTop,
          left:css.marginLeft,
          right:css.marginRight,
          bottom:css.marginBottom
        }
      }
      else{
        return {
          top:parseFloat(css.marginTop),
          left:parseFloat(css.marginLeft),
          right:parseFloat(css.marginRight),
          bottom:parseFloat(css.marginBottom)
        }
      }
    }
    f.padding = function(parse){
      if(!parse){
        return {
          top:css.paddingTop,
          left:css.paddingLeft,
          right:css.paddingRight,
          bottom:css.paddingBottom
        }
      }
      else{
         return {
          top:parseFloat(css.paddingTop),
          left:parseFloat(css.paddingLeft),
          right:parseFloat(css.paddingRight),
          bottom:parseFloat(css.paddingBottom)
        }
      }
    }
    f.opacity = function(){return css.opacity}

    f.radius = function(parse){
      if(!parse){
        return {
          tl:css.borderTopLeftRadius,
          tr:css.borderTopRightRadius,
          br:css.borderBottomRightRadius,
          bl:css.borderBottomLeftRadius
        }
      }
      else{
        return {
          tl:parseFloat(css.borderTopLeftRadius),
          tr:parseFloat(css.borderTopRightRadius),
          br:parseFloat(css.borderBottomRightRadius),
          bl:parseFloat(css.borderBottomLeftRadius)
        }
      }
    }

    f.transform = function(){
     var transform = mw.JSPrefix('transform');
     var transform = css[transform];
     if(transform==="" || transform==="none"){
       return [1, 0, 0, 1, 0, 0];
     }
     else{
       var transform = transform.substr(7, transform.length - 8).split(", ");
       return transform;
     }
    }
    f.boxShadow = function(){
        const VALUES_REG = /,(?![^\(]*\))/
        const PARTS_REG = /\s(?![^(]*\))/
        const LENGTH_REG = /^[0-9]+[a-zA-Z%]+?$/

        const parseValue = str => {
        const parts = str.split(PARTS_REG)
        const inset = parts.includes('inset')
        const last = parts.slice(-1)[0]
        const color = !isLength(last) ? last : undefined

        const nums = parts
            .filter(n => n !== 'inset')
            .filter(n => n !== color)
            .map(toNum)
        const [ offsetX, offsetY, blurRadius, spreadRadius ] = nums

            return {
                inset,
                offsetX,
                offsetY,
                blurRadius,
                spreadRadius,
                color
            }
        }

        const stringifyValue = obj => {
        const {
            inset,
            offsetX = 0,
            offsetY = 0,
            blurRadius = 0,
            spreadRadius,
            color
        } = obj || {}

        return [
            (inset ? 'inset' : null),
            offsetX,
            offsetY,
            blurRadius ,
            spreadRadius,
            color
        ].filter(v => v !== null && v !== undefined)
            .map(toPx)
            .map(s => ('' + s).trim())
            .join(' ')
        }

        const isLength = v => v === '0' || LENGTH_REG.test(v)
        const toNum = v => {
        if (!/px$/.test(v) && v !== '0') return v
        const n = parseFloat(v)
        return !isNaN(n) ? n : v
        }
        const toPx = n => typeof n === 'number' && n !== 0 ? (n + 'px') : n

        const parse = str => str.split(VALUES_REG).map(s => s.trim()).map(parseValue)
        const stringify = arr => arr.map(stringifyValue).join(', ');

        const shadow = (css['boxShadow'] || css['WebkitBoxShadow']).replace(/, /g, ",").split(" ");

        return parseValue(shadow);
    }
    f.shadow = function(){
      var shadow =  mw.JSPrefix('boxShadow');
      var shadow = css[shadow].replace(/, /g, ",").split(" ");
      return {
        color: shadow[0],
        left:shadow[1],
        top:shadow[2],
        strength:shadow[3]
      }
    }

    return {
        el:el,
        css:css,
        get:f
    }
}


