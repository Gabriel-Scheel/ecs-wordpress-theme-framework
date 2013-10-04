// Avoid `console` errors in browsers that lack a console.
(function() {
                var method;
                var noop = function () {};
                var methods = [
                                'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
                                'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
                                'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
                                'timeStamp', 'trace', 'warn'
                ];
                var length = methods.length;
                var console = (window.console = window.console || {});

                while (length--) {
                                method = methods[length];

                                // Only stub undefined methods.
                                if (!console[method]) {
                                                console[method] = noop;
                                }
                }
}());

// Place any jQuery/helper plugins in here.

// debouncing function from John Hann
// http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
(function($,sr){

        // debouncing function from John Hann
        // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
        var debounce = function (func, threshold, execAsap) {
                var timeout;

                return function debounced () {
                        var obj = this, args = arguments;
                        function delayed () {
                                if (!execAsap)
                                        func.apply(obj, args);
                                timeout = null;
                        };

                        if (timeout)
                                clearTimeout(timeout);
                        else if (execAsap)
                                func.apply(obj, args);

                        timeout = setTimeout(delayed, threshold || 100);
                };
        }

        // smartresize 
        jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');

/*!
* FitVids 1.0
*
* Copyright 2011, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
* Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
* Released under the WTFPL license - http://sam.zoy.org/wtfpl/
*
* Date: Thu Sept 01 18:00:00 2011 -0500
*/
!function(a){"use strict";a.fn.fitVids=function(b){var c={customSelector:null};if(!document.getElementById("fit-vids-style")){var d=document.createElement("div"),e=document.getElementsByTagName("base")[0]||document.getElementsByTagName("script")[0];d.className="fit-vids-style",d.id="fit-vids-style",d.style.display="none",d.innerHTML="&shy;<style>                 .fluid-width-video-wrapper {                   width: 100%;                                position: relative;                         padding: 0;                              }                                                                                       .fluid-width-video-wrapper iframe,          .fluid-width-video-wrapper object,          .fluid-width-video-wrapper embed {             position: absolute;                         top: 0;                                     left: 0;                                    width: 100%;                                height: 100%;                            }                                         </style>",e.parentNode.insertBefore(d,e)}return b&&a.extend(c,b),this.each(function(){var b=["iframe[src*='player.vimeo.com']","iframe[src*='youtube.com']","iframe[src*='youtube-nocookie.com']","iframe[src*='kickstarter.com'][src*='video.html']","object","embed"];c.customSelector&&b.push(c.customSelector);var d=a(this).find(b.join(","));d=d.not("object object"),d.each(function(){var b=a(this);if(!("embed"===this.tagName.toLowerCase()&&b.parent("object").length||b.parent(".fluid-width-video-wrapper").length)){var c="object"===this.tagName.toLowerCase()||b.attr("height")&&!isNaN(parseInt(b.attr("height"),10))?parseInt(b.attr("height"),10):b.height(),d=isNaN(parseInt(b.attr("width"),10))?b.width():parseInt(b.attr("width"),10),e=c/d;if(!b.attr("id")){var f="fitvid"+Math.floor(999999*Math.random());b.attr("id",f)}b.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top",100*e+"%"),b.removeAttr("height").removeAttr("width")}})})}}(jQuery);


/*!
 * imagesLoaded PACKAGED v3.0.2
 * JavaScript is all like "You images are done yet or what?"
 */
(function(e){"use strict";function t(){}function n(e,t){if(r)return t.indexOf(e);for(var n=t.length;n--;)if(t[n]===e)return n;return-1}var i=t.prototype,r=Array.prototype.indexOf?!0:!1;i._getEvents=function(){return this._events||(this._events={})},i.getListeners=function(e){var t,n,i=this._getEvents();if("object"==typeof e){t={};for(n in i)i.hasOwnProperty(n)&&e.test(n)&&(t[n]=i[n])}else t=i[e]||(i[e]=[]);return t},i.getListenersAsObject=function(e){var t,n=this.getListeners(e);return n instanceof Array&&(t={},t[e]=n),t||n},i.addListener=function(e,t){var i,r=this.getListenersAsObject(e);for(i in r)r.hasOwnProperty(i)&&-1===n(t,r[i])&&r[i].push(t);return this},i.on=i.addListener,i.defineEvent=function(e){return this.getListeners(e),this},i.defineEvents=function(e){for(var t=0;e.length>t;t+=1)this.defineEvent(e[t]);return this},i.removeListener=function(e,t){var i,r,s=this.getListenersAsObject(e);for(r in s)s.hasOwnProperty(r)&&(i=n(t,s[r]),-1!==i&&s[r].splice(i,1));return this},i.off=i.removeListener,i.addListeners=function(e,t){return this.manipulateListeners(!1,e,t)},i.removeListeners=function(e,t){return this.manipulateListeners(!0,e,t)},i.manipulateListeners=function(e,t,n){var i,r,s=e?this.removeListener:this.addListener,o=e?this.removeListeners:this.addListeners;if("object"!=typeof t||t instanceof RegExp)for(i=n.length;i--;)s.call(this,t,n[i]);else for(i in t)t.hasOwnProperty(i)&&(r=t[i])&&("function"==typeof r?s.call(this,i,r):o.call(this,i,r));return this},i.removeEvent=function(e){var t,n=typeof e,i=this._getEvents();if("string"===n)delete i[e];else if("object"===n)for(t in i)i.hasOwnProperty(t)&&e.test(t)&&delete i[t];else delete this._events;return this},i.emitEvent=function(e,t){var n,i,r,s=this.getListenersAsObject(e);for(i in s)if(s.hasOwnProperty(i))for(n=s[i].length;n--;)r=t?s[i][n].apply(null,t):s[i][n](),r===!0&&this.removeListener(e,s[i][n]);return this},i.trigger=i.emitEvent,i.emit=function(e){var t=Array.prototype.slice.call(arguments,1);return this.emitEvent(e,t)},"function"==typeof define&&define.amd?define(function(){return t}):e.EventEmitter=t})(this),function(e){"use strict";var t=document.documentElement,n=function(){};t.addEventListener?n=function(e,t,n){e.addEventListener(t,n,!1)}:t.attachEvent&&(n=function(t,n,i){t[n+i]=i.handleEvent?function(){var t=e.event;t.target=t.target||t.srcElement,i.handleEvent.call(i,t)}:function(){var n=e.event;n.target=n.target||n.srcElement,i.call(t,n)},t.attachEvent("on"+n,t[n+i])});var i=function(){};t.removeEventListener?i=function(e,t,n){e.removeEventListener(t,n,!1)}:t.detachEvent&&(i=function(e,t,n){e.detachEvent("on"+t,e[t+n]);try{delete e[t+n]}catch(i){e[t+n]=void 0}});var r={bind:n,unbind:i};"function"==typeof define&&define.amd?define(r):e.eventie=r}(this),function(e){"use strict";function t(e,t){for(var n in t)e[n]=t[n];return e}function n(e){return"[object Array]"===a.call(e)}function i(e){var t=[];if(n(e))t=e;else if("number"==typeof e.length)for(var i=0,r=e.length;r>i;i++)t.push(e[i]);else t.push(e);return t}function r(e,n){function r(e,n,o){if(!(this instanceof r))return new r(e,n);"string"==typeof e&&(e=document.querySelectorAll(e)),this.elements=i(e),this.options=t({},this.options),"function"==typeof n?o=n:t(this.options,n),o&&this.on("always",o),this.getImages(),s&&(this.jqDeferred=new s.Deferred);var h=this;setTimeout(function(){h.check()})}function a(e){this.img=e}r.prototype=new e,r.prototype.options={},r.prototype.getImages=function(){this.images=[];for(var e=0,t=this.elements.length;t>e;e++){var n=this.elements[e];"IMG"===n.nodeName&&this.addImage(n);for(var i=n.querySelectorAll("img"),r=0,s=i.length;s>r;r++){var o=i[r];this.addImage(o)}}},r.prototype.addImage=function(e){var t=new a(e);this.images.push(t)},r.prototype.check=function(){function e(e,r){return t.options.debug&&h&&o.log("confirm",e,r),t.progress(e),n++,n===i&&t.complete(),!0}var t=this,n=0,i=this.images.length;if(this.hasAnyBroken=!1,!i)return this.complete(),void 0;for(var r=0;i>r;r++){var s=this.images[r];s.on("confirm",e),s.check()}},r.prototype.progress=function(e){this.hasAnyBroken=this.hasAnyBroken||!e.isLoaded,this.emit("progress",this,e),this.jqDeferred&&this.jqDeferred.notify(this,e)},r.prototype.complete=function(){var e=this.hasAnyBroken?"fail":"done";if(this.isComplete=!0,this.emit(e,this),this.emit("always",this),this.jqDeferred){var t=this.hasAnyBroken?"reject":"resolve";this.jqDeferred[t](this)}},s&&(s.fn.imagesLoaded=function(e,t){var n=new r(this,e,t);return n.jqDeferred.promise(s(this))});var f={};return a.prototype=new e,a.prototype.check=function(){var e=f[this.img.src];if(e)return this.useCached(e),void 0;if(f[this.img.src]=this,this.img.complete&&void 0!==this.img.naturalWidth)return this.confirm(0!==this.img.naturalWidth,"naturalWidth"),void 0;var t=this.proxyImage=new Image;n.bind(t,"load",this),n.bind(t,"error",this),t.src=this.img.src},a.prototype.useCached=function(e){if(e.isConfirmed)this.confirm(e.isLoaded,"cached was confirmed");else{var t=this;e.on("confirm",function(e){return t.confirm(e.isLoaded,"cache emitted confirmed"),!0})}},a.prototype.confirm=function(e,t){this.isConfirmed=!0,this.isLoaded=e,this.emit("confirm",this,t)},a.prototype.handleEvent=function(e){var t="on"+e.type;this[t]&&this[t](e)},a.prototype.onload=function(){this.confirm(!0,"onload"),this.unbindProxyEvents()},a.prototype.onerror=function(){this.confirm(!1,"onerror"),this.unbindProxyEvents()},a.prototype.unbindProxyEvents=function(){n.unbind(this.proxyImage,"load",this),n.unbind(this.proxyImage,"error",this)},r}var s=e.jQuery,o=e.console,h=o!==void 0,a=Object.prototype.toString;"function"==typeof define&&define.amd?define(["eventEmitter","eventie"],r):e.imagesLoaded=r(e.EventEmitter,e.eventie)}(window);


/*! Picturefill - Responsive Images that work today. (and mimic the proposed Picture element with span elements). Author: Scott Jehl, Filament Group, 2012 | License: MIT/GPLv2 */
!function(a){"use strict";a.picturefill=function(){for(var b=a.document.getElementsByTagName("span"),c=0,d=b.length;d>c;c++)if(null!==b[c].getAttribute("data-picture")){for(var e=b[c].getElementsByTagName("span"),f=[],g=0,h=e.length;h>g;g++){var i=e[g].getAttribute("data-media");(!i||a.matchMedia&&a.matchMedia(i).matches)&&f.push(e[g])}var j=b[c].getElementsByTagName("img")[0];if(f.length){var k=f.pop();j&&"NOSCRIPT"!==j.parentNode.nodeName||(j=a.document.createElement("img"),j.alt=b[c].getAttribute("data-alt")),j.src=k.getAttribute("data-src"),k.appendChild(j)}else j&&j.parentNode.removeChild(j)}},a.addEventListener?(a.addEventListener("resize",a.picturefill,!1),a.addEventListener("DOMContentLoaded",function(){a.picturefill(),a.removeEventListener("load",a.picturefill,!1)},!1),a.addEventListener("load",a.picturefill,!1)):a.attachEvent&&a.attachEvent("onload",a.picturefill)}(this);


// enquire.js v2.0.1 - Awesome Media Queries in JavaScript
// Copyright (c) 2013 Nick Williams - http://wicky.nillia.ms/enquire.js
// License: MIT (http://www.opensource.org/licenses/mit-license.php)
(function(t){"use strict";function i(t,i){var s,n=0,e=t.length;for(n;e>n&&(s=i(t[n],n),s!==!1);n++);}function s(t){return"[object Array]"===Object.prototype.toString.apply(t)}function n(t){return"function"==typeof t}function e(t){this.options=t,!t.deferSetup&&this.setup()}function o(t,i){this.query=t,this.isUnconditional=i,this.handlers=[],this.mql=h(t);var s=this;this.listener=function(t){s.mql=t,s.assess()},this.mql.addListener(this.listener)}function r(){if(!h)throw Error("matchMedia not present, legacy browsers require a polyfill");this.queries={},this.browserIsIncapable=!h("only all").matches}var h=t.matchMedia;e.prototype={setup:function(){this.options.setup&&this.options.setup(),this.initialised=!0},on:function(){!this.initialised&&this.setup(),this.options.match&&this.options.match()},off:function(){this.options.unmatch&&this.options.unmatch()},destroy:function(){this.options.destroy?this.options.destroy():this.off()},equals:function(t){return this.options===t||this.options.match===t}},o.prototype={addHandler:function(t){var i=new e(t);this.handlers.push(i),this.matches()&&i.on()},removeHandler:function(t){var s=this.handlers;i(s,function(i,n){return i.equals(t)?(i.destroy(),!s.splice(n,1)):void 0})},matches:function(){return this.mql.matches||this.isUnconditional},clear:function(){i(this.handlers,function(t){t.destroy()}),this.mql.removeListener(this.listener),this.handlers.length=0},assess:function(){var t=this.matches()?"on":"off";i(this.handlers,function(i){i[t]()})}},r.prototype={register:function(t,e,r){var h=this.queries,a=r&&this.browserIsIncapable;return h[t]||(h[t]=new o(t,a)),n(e)&&(e={match:e}),s(e)||(e=[e]),i(e,function(i){h[t].addHandler(i)}),this},unregister:function(t,i){var s=this.queries[t];return s&&(i?s.removeHandler(i):(s.clear(),delete this.queries[t])),this}},t.enquire=t.enquire||new r})(this);
