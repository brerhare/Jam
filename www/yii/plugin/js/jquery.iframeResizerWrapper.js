/*! iFrame Resizer (jquery.iframeSizer.min.js ) - v1.4.4 - 2014-02-27
 *  Desc: Force cross domain iframes to size to content.
 *  Requires: iframeSizer.contentWindow.min.js to be loaded into the target frame.
 *  Copyright: (c) 2014 David J. Bradshaw - dave@bradshaw.net
 *  License: MIT
 */

!function(a){function b(){var a,b=["moz","webkit","o","ms"];for(a=0;a<b.length&&!window.requestAnimationFrame;a+=1)window.requestAnimationFrame=window[b[a]+"RequestAnimationFrame"];window.requestAnimationFrame||(c(" RequestAnimationFrame not supported"),window.requestAnimationFrame=function(a){a()})}function c(a){g.log&&"console"in window&&console.log(e+"[Host page]"+a)}var d=0,e="[iFrameSizer]",f=e.length,g={},h={autoResize:!0,contentWindowBodyMargin:8,doHeight:!0,doWidth:!1,enablePublicMethods:!1,interval:0,log:!1,scrolling:!1,callback:function(){}};b(),a(window).bind("message",function(b){function d(b){function d(){function a(a){window.requestAnimationFrame(function(){i.iframe.style[a]=i[a]+"px",c(" "+i.iframe.id+" "+a+" set to "+i[a]+"px")})}g.doHeight&&a("height"),g.doWidth&&a("width")}function h(){var e=b.substr(f).split(":");i={iframe:document.getElementById(e[0]),height:e[1],width:e[2],type:e[3]},"close"===i.type?(c("iFrame "+i.iframe.id+" removed."),a(i.iframe).remove()):d()}var i={};e===""+b.substr(0,f)&&(h(),g.callback(i))}d(b.originalEvent.data)}),a.fn.iFrameResize=a.fn.iFrameSizer=function(b){return g=a.extend({},h,b),this.filter("iframe").each(function(){function b(){c("IFrame scrolling "+(g.scrolling?"enabled":"disabled")),j.style.overflow=!1===g.scrolling?"hidden":"auto",j.scrolling=!1===g.scrolling?"no":"yes"}function f(){""===j.id&&(j.id="iFrameSizer"+d++,c(" Added missing iframe ID: "+j.id))}function h(a){var b=j.id+":"+g.contentWindowBodyMargin+":"+g.doWidth+":"+g.log+":"+g.interval+":"+g.enablePublicMethods+":"+g.autoResize;c("["+a+"] Sending init msg to iframe ("+b+")"),j.contentWindow.postMessage(e+b,"*")}function i(){a(j).bind("load",function(){h("iFrame.onload")}),h("init")}var j=this;f(),b(),i()})}}(window.jQuery);
//# sourceMappingURL=../src/jquery.iframeResizer.map

        jQuery('iframe').iFrameSizer({
            log                    : true,  // For development
            autoResize             : true,  // Trigering resize on events in iFrame
            contentWindowBodyMargin: 8,     // Set the default browser body margin style (in px)
            doHeight               : true,  // Calculates dynamic height
            doWidth                : false, // Calculates dynamic width 
            enablePublicMethods    : true,  // Enable methods within iframe hosted page 
            interval               : 0,     // interval in ms to recalculate body height, 0 to disable refreshing
            scrolling              : false, // Enable the scrollbars in the iFrame
            callback               : function(messageData){ // Callback fn when message is received
                $('p#callback').html(
                    '<b>Frame ID:</b> '    + messageData.iframe.id + 
                    ' <b>Height:</b> '     + messageData.height +
                    ' <b>Width:</b> '      + messageData.width + 
                    ' <b>Event type:</b> ' + messageData.type
                );
            }
        }); 
