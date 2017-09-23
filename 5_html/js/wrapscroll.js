/* 
 * wrapScroll
 * Copyright 2007 youmos <youmos.com@gmail.com>
 * http://youmos.com/project/wrapscroll
 * 
 * The wrapScroll is licensed under a 
 * Creative Commons Attribution-NonCommercial-NoDerivs License.
 *
 * http://creativecommons.org/licenses/by-nc-nd/3.0/
 *
 * It is provided "as is" and without warranty of any kind. If you wish to continue to use it,
 * free for personal or non-commercial use, $30 for commercial use,
 * or transform and build your script upon this work to distribute.
 * The permission is valid for all future versions of wrapScroll.
*/

/*--- wrapscroll.js ---*/
var wrapScroll=function(){this.initialize.apply(this,arguments);};wrapScroll.prototype={Version:'0.9.5',Interval:500,Duration:60,marginTop:0,marginBottom:0,_timer:0,_obj:null,initialize:function(e,p,r){this.attach(window,'load',this.bind(this.prepare,arguments));},prepare:function(e,p,r){if(o=document.getElementById(e)){this._obj={'e':o,'p':(p)?document.getElementById(p):o.parentNode,'r':(r)?document.getElementById(r):null};o.style.position='absolute';if((c=o.offsetTop)!=(v=((o.style.top=o.offsetTop+'px')!='')?o.offsetTop:0)){o.style.marginTop=0;o.style.top=c+(v-c)+'px';}this.callback();}},callback:function(){f=this.event.update(this,this._obj);setTimeout(this.bind(this.callback),(f)?10:this.Interval);},bind:function(method,arg){var _this=this;var _arg=(arg)?arg:[];return function(){method.apply(_this,_arg);}},attach:function(e,name,func,cap){if(e.addEventListener){e.addEventListener(name,func,cap);}else if(e.attachEvent){e.attachEvent('on'+name,func);}},setTween:function(f,d){if(f){this.tween=f;}if(d){this.Duration=d;}},tween:function(t,b,c,d){return c*(t/=d)*t+b;}};wrapScroll.prototype.event={update:function(t,o){var tpos=Math.max(this.scroll(),(o.r)?this.offset(o.r)+this.height(o.r):this.offset(o.p));var bpos=(this.offset(o.p)+this.height(o.p))-(this.height(o.e)+t.marginBottom);var s=this.offset(o.e);var e=Math.min(bpos,tpos+t.marginTop);if((t._prev!=e)||(t._timer>t.Duration)){t._timer=0;}if((t._prev=e)!=s){o.e.style.top=t.tween(t._timer++,s,e-s,t.Duration,o.e.style)+'px';return true;}return false;},scroll:function(){return document.body.scrollTop||document.documentElement.scrollTop;},height:function(o){return o.offsetHeight||document.body.scrollHeight||document.documentElement.scrollHeight;},offset:function(o){y=o.offsetTop;if(o.style.position=='absolute')return y;while(o=o.offsetParent){y+=o.offsetTop;}return y;}};