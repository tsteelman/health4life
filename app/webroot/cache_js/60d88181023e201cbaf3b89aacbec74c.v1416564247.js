!function(f,s){function t(a,b,k,c,h){var n=!1;return a.contents().detach().each(function(){var i=f(this);if("undefined"==typeof this||3==this.nodeType&&0==f.trim(this.data).length)return!0;if(i.is("script, .dotdotdot-keep"))a.append(i);else{if(n)return!0;a.append(i);h&&a[a.is("table, thead, tbody, tfoot, tr, col, colgroup, object, embed, param, ol, ul, dl, blockquote, select, optgroup, option, textarea, script, style")?"after":"append"](h);if(k.innerHeight()>c.maxHeight){var g;if(3==this.nodeType)if(g=
i[0]){var l=u(g),e=-1!==l.indexOf(" ")?" ":"\u3000",e="letter"==c.wrap?"":e,d=l.split(e),r=-1,o=-1,j=0,m=d.length-1;for(c.fallbackToLetter&&0==j&&0==m&&(e="",d=l.split(e),m=d.length-1);m>=j&&(0!=j||0!=m);){var v=Math.floor((j+m)/2);if(v==o)break;o=v;p(g,d.slice(0,o+1).join(e)+c.ellipsis);k.innerHeight()>c.maxHeight?(m=o,c.fallbackToLetter&&0==j&&0==m&&(e="",d=d[0].split(e),r=-1,o=-1,j=0,m=d.length-1)):(r=o,j=o)}-1==r||1==d.length&&0==d[0].length?(e=i.parent(),i.detach(),d=h&&h.closest(e).length?h.length:
0,e.contents().length>d?g=q(e.contents().eq(-1-d),b):(g=q(e,b,!0),d||e.detach()),g&&(l=w(u(g),c),p(g,l),d&&h&&f(g).parent().append(h))):(l=w(d.slice(0,r+1).join(e),c),p(g,l));g=!0}else g=!1;else g=t(i,b,k,c,h);(n=g)||(i.detach(),n=!0)}n||h&&h.detach()}}),n}function w(a,b){for(;-1<f.inArray(a.slice(-1),b.lastCharacter.remove);)a=a.slice(0,-1);return 0>f.inArray(a.slice(-1),b.lastCharacter.noEllipsis)&&(a+=b.ellipsis),a}function p(a,b){a.innerText?a.innerText=b:a.nodeValue?a.nodeValue=b:a.textContent&&
(a.textContent=b)}function u(a){return a.innerText?a.innerText:a.nodeValue?a.nodeValue:a.textContent?a.textContent:""}function x(a){do a=a.previousSibling;while(a&&1!==a.nodeType&&3!==a.nodeType);return a}function q(a,b,k){var c=a&&a[0];if(c){if(!k){if(3===c.nodeType)return c;if(f.trim(a.text()))return q(a.contents().last(),b)}for(k=x(c);!k;){if(a=a.parent(),a.is(b)||!a.length)return!1;k=x(a[0])}if(k)return q(f(k),b)}return!1}if(!f.fn.dotdotdot){f.fn.dotdotdot=function(a){if(0==this.length)return f.fn.dotdotdot.debug('No element found for "'+
this.selector+'".'),this;if(1<this.length)return this.each(function(){f(this).dotdotdot(a)});var b=this;b.data("dotdotdot")&&b.trigger("destroy.dot");b.data("dotdotdot-style",b.attr("style")||"");b.css("word-wrap","break-word");"nowrap"===b.css("white-space")&&b.css("white-space","normal");b.bind_events=function(){return b.bind("update.dot",function(a,d){a.preventDefault();a.stopPropagation();var j=c,m;if("number"==typeof c.height)m=c.height;else{m=b.innerHeight();for(var e=["paddingTop","paddingBottom"],
i=0,n=e.length;n>i;i++){var l=parseInt(b.css(e[i]),10);isNaN(l)&&(l=0);m-=l}}j.maxHeight=m;c.maxHeight+=c.tolerance;"undefined"!=typeof d&&(("string"==typeof d||d instanceof HTMLElement)&&(d=f("<div />").append(d).contents()),d instanceof f&&(k=d));g=b.wrapInner('<div class="dotdotdot" />').children();g.contents().detach().end().append(k.clone(!0)).find("br").replaceWith("  <br />  ").end().css({height:"auto",width:"auto",border:"none",padding:0,margin:0});j=e=!1;h.afterElement&&(e=h.afterElement.clone(!0),
e.show(),h.afterElement.detach());if(g.innerHeight()>c.maxHeight)if("children"==c.wrap){j=g;m=c;i=j.children();n=!1;j.empty();for(var l=0,q=i.length;q>l;l++){var p=i.eq(l);if(j.append(p),e&&j.append(e),j.innerHeight()>m.maxHeight){p.remove();n=!0;break}e&&e.detach()}j=n}else j=t(g,b,g,c,e);return g.replaceWith(g.contents()),g=null,f.isFunction(c.callback)&&c.callback.call(b[0],j,k),h.isTruncated=j,j}).bind("isTruncated.dot",function(a,c){return a.preventDefault(),a.stopPropagation(),"function"==typeof c&&
c.call(b[0],h.isTruncated),h.isTruncated}).bind("originalContent.dot",function(a,c){return a.preventDefault(),a.stopPropagation(),"function"==typeof c&&c.call(b[0],k),k}).bind("destroy.dot",function(a){a.preventDefault();a.stopPropagation();b.unwatch().unbind_events().contents().detach().end().append(k).attr("style",b.data("dotdotdot-style")||"").data("dotdotdot",!1)}),b};b.unbind_events=function(){return b.unbind(".dot"),b};b.watch=function(){if(b.unwatch(),"window"==c.watch){var a=f(window),d=a.width(),
e=a.height();a.bind("resize.dot"+h.dotId,function(){d==a.width()&&e==a.height()&&c.windowResizeFix||(d=a.width(),e=a.height(),i&&clearInterval(i),i=setTimeout(function(){b.trigger("update.dot")},100))})}else n={width:b.innerWidth(),height:b.innerHeight()},i=setInterval(function(){if(b.is(":visible")){var a={width:b.innerWidth(),height:b.innerHeight()};(n.width!=a.width||n.height!=a.height)&&(b.trigger("update.dot"),n=a)}},500);return b};b.unwatch=function(){return f(window).unbind("resize.dot"+h.dotId),
i&&clearInterval(i),b};var k=b.contents(),c=f.extend(!0,{},f.fn.dotdotdot.defaults,a),h={},n={},i=null,g=null;c.lastCharacter.remove instanceof Array||(c.lastCharacter.remove=f.fn.dotdotdot.defaultArrays.lastCharacter.remove);c.lastCharacter.noEllipsis instanceof Array||(c.lastCharacter.noEllipsis=f.fn.dotdotdot.defaultArrays.lastCharacter.noEllipsis);var l=h,e,d=c.after;e=d?"string"==typeof d?(d=f(d,b),d.length?d:!1):d.jquery?d:!1:!1;return l.afterElement=e,h.isTruncated=!1,h.dotId=y++,b.data("dotdotdot",
!0).bind_events().trigger("update.dot"),c.watch&&b.watch(),b};f.fn.dotdotdot.defaults={ellipsis:"... ",wrap:"word",fallbackToLetter:!0,lastCharacter:{},tolerance:0,callback:null,after:null,height:null,watch:!1,windowResizeFix:!0};f.fn.dotdotdot.defaultArrays={lastCharacter:{remove:" \u3000,;.!?".split(""),noEllipsis:[]}};f.fn.dotdotdot.debug=function(){};var y=1,z=f.fn.html;f.fn.html=function(a){return a!=s&&!f.isFunction(a)&&this.data("dotdotdot")?this.trigger("update",[a]):z.apply(this,arguments)};
var A=f.fn.text;f.fn.text=function(a){return a!=s&&!f.isFunction(a)&&this.data("dotdotdot")?(a=f("<div />").text(a).html(),this.trigger("update",[a])):A.apply(this,arguments)}}}(jQuery);