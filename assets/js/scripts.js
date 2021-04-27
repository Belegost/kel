!function(t,e,i,s){function n(e,i){this.settings=null,this.options=t.extend({},n.Defaults,i),this.$element=t(e),this._handlers={},this._plugins={},this._supress={},this._current=null,this._speed=null,this._coordinates=[],this._breakpoint=null,this._width=null,this._items=[],this._clones=[],this._mergers=[],this._widths=[],this._invalidated={},this._pipe=[],this._drag={time:null,target:null,pointer:null,stage:{start:null,current:null},direction:null},this._states={current:{},tags:{initializing:["busy"],animating:["busy"],dragging:["interacting"]}},t.each(["onResize","onThrottledResize"],t.proxy(function(e,i){this._handlers[i]=t.proxy(this[i],this)},this)),t.each(n.Plugins,t.proxy(function(t,e){this._plugins[t.charAt(0).toLowerCase()+t.slice(1)]=new e(this)},this)),t.each(n.Workers,t.proxy(function(e,i){this._pipe.push({filter:i.filter,run:t.proxy(i.run,this)})},this)),this.setup(),this.initialize()}n.Defaults={items:3,loop:!1,center:!1,rewind:!1,mouseDrag:!0,touchDrag:!0,pullDrag:!0,freeDrag:!1,margin:0,stagePadding:0,merge:!1,mergeFit:!0,autoWidth:!1,startPosition:0,rtl:!1,smartSpeed:250,fluidSpeed:!1,dragEndSpeed:!1,responsive:{},responsiveRefreshRate:200,responsiveBaseElement:e,fallbackEasing:"swing",info:!1,nestedItemSelector:!1,itemElement:"div",stageElement:"div",refreshClass:"owl-refresh",loadedClass:"owl-loaded",loadingClass:"owl-loading",rtlClass:"owl-rtl",responsiveClass:"owl-responsive",dragClass:"owl-drag",itemClass:"owl-item",stageClass:"owl-stage",stageOuterClass:"owl-stage-outer",grabClass:"owl-grab"},n.Width={Default:"default",Inner:"inner",Outer:"outer"},n.Type={Event:"event",State:"state"},n.Plugins={},n.Workers=[{filter:["width","settings"],run:function(){this._width=this.$element.width()}},{filter:["width","items","settings"],run:function(t){t.current=this._items&&this._items[this.relative(this._current)]}},{filter:["items","settings"],run:function(){this.$stage.children(".cloned").remove()}},{filter:["width","items","settings"],run:function(t){var e=this.settings.margin||"",i=!this.settings.autoWidth,s=this.settings.rtl,n={width:"auto","margin-left":s?e:"","margin-right":s?"":e};!i&&this.$stage.children().css(n),t.css=n}},{filter:["width","items","settings"],run:function(t){var e=(this.width()/this.settings.items).toFixed(3)-this.settings.margin,i=null,s=this._items.length,n=!this.settings.autoWidth,o=[];for(t.items={merge:!1,width:e};s--;)i=this._mergers[s],i=this.settings.mergeFit&&Math.min(i,this.settings.items)||i,t.items.merge=i>1||t.items.merge,o[s]=n?e*i:this._items[s].width();this._widths=o}},{filter:["items","settings"],run:function(){var e=[],i=this._items,s=this.settings,n=Math.max(2*s.items,4),o=2*Math.ceil(i.length/2),r=s.loop&&i.length?s.rewind?n:Math.max(n,o):0,a="",h="";for(r/=2;r--;)e.push(this.normalize(e.length/2,!0)),a+=i[e[e.length-1]][0].outerHTML,e.push(this.normalize(i.length-1-(e.length-1)/2,!0)),h=i[e[e.length-1]][0].outerHTML+h;this._clones=e,t(a).addClass("cloned").appendTo(this.$stage),t(h).addClass("cloned").prependTo(this.$stage)}},{filter:["width","items","settings"],run:function(){for(var t=this.settings.rtl?1:-1,e=this._clones.length+this._items.length,i=-1,s=0,n=0,o=[];++i<e;)s=o[i-1]||0,n=this._widths[this.relative(i)]+this.settings.margin,o.push(s+n*t);this._coordinates=o}},{filter:["width","items","settings"],run:function(){var t=this.settings.stagePadding,e=this._coordinates,i={width:Math.ceil(Math.abs(e[e.length-1]))+2*t,"padding-left":t||"","padding-right":t||""};this.$stage.css(i)}},{filter:["width","items","settings"],run:function(t){var e=this._coordinates.length,i=!this.settings.autoWidth,s=this.$stage.children();if(i&&t.items.merge)for(;e--;)t.css.width=this._widths[this.relative(e)],s.eq(e).css(t.css);else i&&(t.css.width=t.items.width,s.css(t.css))}},{filter:["items"],run:function(){this._coordinates.length<1&&this.$stage.removeAttr("style")}},{filter:["width","items","settings"],run:function(t){t.current=t.current?this.$stage.children().index(t.current):0,t.current=Math.max(this.minimum(),Math.min(this.maximum(),t.current)),this.reset(t.current)}},{filter:["position"],run:function(){this.animate(this.coordinates(this._current))}},{filter:["width","position","items","settings"],run:function(){var t,e,i,s,n=this.settings.rtl?1:-1,o=2*this.settings.stagePadding,r=this.coordinates(this.current())+o,a=r+this.width()*n,h=[];for(i=0,s=this._coordinates.length;i<s;i++)t=this._coordinates[i-1]||0,e=Math.abs(this._coordinates[i])+o*n,(this.op(t,"<=",r)&&this.op(t,">",a)||this.op(e,"<",r)&&this.op(e,">",a))&&h.push(i);this.$stage.children(".active").removeClass("active"),this.$stage.children(":eq("+h.join("), :eq(")+")").addClass("active"),this.settings.center&&(this.$stage.children(".center").removeClass("center"),this.$stage.children().eq(this.current()).addClass("center"))}}],n.prototype.initialize=function(){var e,i,n;(this.enter("initializing"),this.trigger("initialize"),this.$element.toggleClass(this.settings.rtlClass,this.settings.rtl),this.settings.autoWidth&&!this.is("pre-loading"))&&(e=this.$element.find("img"),i=this.settings.nestedItemSelector?"."+this.settings.nestedItemSelector:s,n=this.$element.children(i).width(),e.length&&n<=0&&this.preloadAutoWidthImages(e));this.$element.addClass(this.options.loadingClass),this.$stage=t("<"+this.settings.stageElement+' class="'+this.settings.stageClass+'"/>').wrap('<div class="'+this.settings.stageOuterClass+'"/>'),this.$element.append(this.$stage.parent()),this.replace(this.$element.children().not(this.$stage.parent())),this.$element.is(":visible")?this.refresh():this.invalidate("width"),this.$element.removeClass(this.options.loadingClass).addClass(this.options.loadedClass),this.registerEventHandlers(),this.leave("initializing"),this.trigger("initialized")},n.prototype.setup=function(){var e=this.viewport(),i=this.options.responsive,s=-1,n=null;i?(t.each(i,function(t){t<=e&&t>s&&(s=Number(t))}),"function"==typeof(n=t.extend({},this.options,i[s])).stagePadding&&(n.stagePadding=n.stagePadding()),delete n.responsive,n.responsiveClass&&this.$element.attr("class",this.$element.attr("class").replace(new RegExp("("+this.options.responsiveClass+"-)\\S+\\s","g"),"$1"+s))):n=t.extend({},this.options),this.trigger("change",{property:{name:"settings",value:n}}),this._breakpoint=s,this.settings=n,this.invalidate("settings"),this.trigger("changed",{property:{name:"settings",value:this.settings}})},n.prototype.optionsLogic=function(){this.settings.autoWidth&&(this.settings.stagePadding=!1,this.settings.merge=!1)},n.prototype.prepare=function(e){var i=this.trigger("prepare",{content:e});return i.data||(i.data=t("<"+this.settings.itemElement+"/>").addClass(this.options.itemClass).append(e)),this.trigger("prepared",{content:i.data}),i.data},n.prototype.update=function(){for(var e=0,i=this._pipe.length,s=t.proxy(function(t){return this[t]},this._invalidated),n={};e<i;)(this._invalidated.all||t.grep(this._pipe[e].filter,s).length>0)&&this._pipe[e].run(n),e++;this._invalidated={},!this.is("valid")&&this.enter("valid")},n.prototype.width=function(t){switch(t=t||n.Width.Default){case n.Width.Inner:case n.Width.Outer:return this._width;default:return this._width-2*this.settings.stagePadding+this.settings.margin}},n.prototype.refresh=function(){this.enter("refreshing"),this.trigger("refresh"),this.setup(),this.optionsLogic(),this.$element.addClass(this.options.refreshClass),this.update(),this.$element.removeClass(this.options.refreshClass),this.leave("refreshing"),this.trigger("refreshed")},n.prototype.onThrottledResize=function(){e.clearTimeout(this.resizeTimer),this.resizeTimer=e.setTimeout(this._handlers.onResize,this.settings.responsiveRefreshRate)},n.prototype.onResize=function(){return!!this._items.length&&(this._width!==this.$element.width()&&(!!this.$element.is(":visible")&&(this.enter("resizing"),this.trigger("resize").isDefaultPrevented()?(this.leave("resizing"),!1):(this.invalidate("width"),this.refresh(),this.leave("resizing"),void this.trigger("resized")))))},n.prototype.registerEventHandlers=function(){t.support.transition&&this.$stage.on(t.support.transition.end+".owl.core",t.proxy(this.onTransitionEnd,this)),!1!==this.settings.responsive&&this.on(e,"resize",this._handlers.onThrottledResize),this.settings.mouseDrag&&(this.$element.addClass(this.options.dragClass),this.$stage.on("mousedown.owl.core",t.proxy(this.onDragStart,this)),this.$stage.on("dragstart.owl.core selectstart.owl.core",function(){return!1})),this.settings.touchDrag&&(this.$stage.on("touchstart.owl.core",t.proxy(this.onDragStart,this)),this.$stage.on("touchcancel.owl.core",t.proxy(this.onDragEnd,this)))},n.prototype.onDragStart=function(e){var s=null;3!==e.which&&(t.support.transform?s={x:(s=this.$stage.css("transform").replace(/.*\(|\)| /g,"").split(","))[16===s.length?12:4],y:s[16===s.length?13:5]}:(s=this.$stage.position(),s={x:this.settings.rtl?s.left+this.$stage.width()-this.width()+this.settings.margin:s.left,y:s.top}),this.is("animating")&&(t.support.transform?this.animate(s.x):this.$stage.stop(),this.invalidate("position")),this.$element.toggleClass(this.options.grabClass,"mousedown"===e.type),this.speed(0),this._drag.time=(new Date).getTime(),this._drag.target=t(e.target),this._drag.stage.start=s,this._drag.stage.current=s,this._drag.pointer=this.pointer(e),t(i).on("mouseup.owl.core touchend.owl.core",t.proxy(this.onDragEnd,this)),t(i).one("mousemove.owl.core touchmove.owl.core",t.proxy(function(e){var s=this.difference(this._drag.pointer,this.pointer(e));t(i).on("mousemove.owl.core touchmove.owl.core",t.proxy(this.onDragMove,this)),Math.abs(s.x)<Math.abs(s.y)&&this.is("valid")||(e.preventDefault(),this.enter("dragging"),this.trigger("drag"))},this)))},n.prototype.onDragMove=function(t){var e=null,i=null,s=null,n=this.difference(this._drag.pointer,this.pointer(t)),o=this.difference(this._drag.stage.start,n);this.is("dragging")&&(t.preventDefault(),this.settings.loop?(e=this.coordinates(this.minimum()),i=this.coordinates(this.maximum()+1)-e,o.x=((o.x-e)%i+i)%i+e):(e=this.settings.rtl?this.coordinates(this.maximum()):this.coordinates(this.minimum()),i=this.settings.rtl?this.coordinates(this.minimum()):this.coordinates(this.maximum()),s=this.settings.pullDrag?-1*n.x/5:0,o.x=Math.max(Math.min(o.x,e+s),i+s)),this._drag.stage.current=o,this.animate(o.x))},n.prototype.onDragEnd=function(e){var s=this.difference(this._drag.pointer,this.pointer(e)),n=this._drag.stage.current,o=s.x>0^this.settings.rtl?"left":"right";t(i).off(".owl.core"),this.$element.removeClass(this.options.grabClass),(0!==s.x&&this.is("dragging")||!this.is("valid"))&&(this.speed(this.settings.dragEndSpeed||this.settings.smartSpeed),this.current(this.closest(n.x,0!==s.x?o:this._drag.direction)),this.invalidate("position"),this.update(),this._drag.direction=o,(Math.abs(s.x)>3||(new Date).getTime()-this._drag.time>300)&&this._drag.target.one("click.owl.core",function(){return!1})),this.is("dragging")&&(this.leave("dragging"),this.trigger("dragged"))},n.prototype.closest=function(e,i){var s=-1,n=this.width(),o=this.coordinates();return this.settings.freeDrag||t.each(o,t.proxy(function(t,r){return"left"===i&&e>r-30&&e<r+30?s=t:"right"===i&&e>r-n-30&&e<r-n+30?s=t+1:this.op(e,"<",r)&&this.op(e,">",o[t+1]||r-n)&&(s="left"===i?t+1:t),-1===s},this)),this.settings.loop||(this.op(e,">",o[this.minimum()])?s=e=this.minimum():this.op(e,"<",o[this.maximum()])&&(s=e=this.maximum())),s},n.prototype.animate=function(e){var i=this.speed()>0;this.is("animating")&&this.onTransitionEnd(),i&&(this.enter("animating"),this.trigger("translate")),t.support.transform3d&&t.support.transition?this.$stage.css({transform:"translate3d("+e+"px,0px,0px)",transition:this.speed()/1e3+"s"}):i?this.$stage.animate({left:e+"px"},this.speed(),this.settings.fallbackEasing,t.proxy(this.onTransitionEnd,this)):this.$stage.css({left:e+"px"})},n.prototype.is=function(t){return this._states.current[t]&&this._states.current[t]>0},n.prototype.current=function(t){if(t===s)return this._current;if(0===this._items.length)return s;if(t=this.normalize(t),this._current!==t){var e=this.trigger("change",{property:{name:"position",value:t}});e.data!==s&&(t=this.normalize(e.data)),this._current=t,this.invalidate("position"),this.trigger("changed",{property:{name:"position",value:this._current}})}return this._current},n.prototype.invalidate=function(e){return"string"===t.type(e)&&(this._invalidated[e]=!0,this.is("valid")&&this.leave("valid")),t.map(this._invalidated,function(t,e){return e})},n.prototype.reset=function(t){(t=this.normalize(t))!==s&&(this._speed=0,this._current=t,this.suppress(["translate","translated"]),this.animate(this.coordinates(t)),this.release(["translate","translated"]))},n.prototype.normalize=function(t,e){var i=this._items.length,n=e?0:this._clones.length;return!this.isNumeric(t)||i<1?t=s:(t<0||t>=i+n)&&(t=((t-n/2)%i+i)%i+n/2),t},n.prototype.relative=function(t){return t-=this._clones.length/2,this.normalize(t,!0)},n.prototype.maximum=function(t){var e,i,s,n=this.settings,o=this._coordinates.length;if(n.loop)o=this._clones.length/2+this._items.length-1;else if(n.autoWidth||n.merge){for(e=this._items.length,i=this._items[--e].width(),s=this.$element.width();e--&&!((i+=this._items[e].width()+this.settings.margin)>s););o=e+1}else o=n.center?this._items.length-1:this._items.length-n.items;return t&&(o-=this._clones.length/2),Math.max(o,0)},n.prototype.minimum=function(t){return t?0:this._clones.length/2},n.prototype.items=function(t){return t===s?this._items.slice():(t=this.normalize(t,!0),this._items[t])},n.prototype.mergers=function(t){return t===s?this._mergers.slice():(t=this.normalize(t,!0),this._mergers[t])},n.prototype.clones=function(e){var i=this._clones.length/2,n=i+this._items.length,o=function(t){return t%2==0?n+t/2:i-(t+1)/2};return e===s?t.map(this._clones,function(t,e){return o(e)}):t.map(this._clones,function(t,i){return t===e?o(i):null})},n.prototype.speed=function(t){return t!==s&&(this._speed=t),this._speed},n.prototype.coordinates=function(e){var i,n=1,o=e-1;return e===s?t.map(this._coordinates,t.proxy(function(t,e){return this.coordinates(e)},this)):(this.settings.center?(this.settings.rtl&&(n=-1,o=e+1),i=this._coordinates[e],i+=(this.width()-i+(this._coordinates[o]||0))/2*n):i=this._coordinates[o]||0,i=Math.ceil(i))},n.prototype.duration=function(t,e,i){return 0===i?0:Math.min(Math.max(Math.abs(e-t),1),6)*Math.abs(i||this.settings.smartSpeed)},n.prototype.to=function(t,e){var i=this.current(),s=null,n=t-this.relative(i),o=(n>0)-(n<0),r=this._items.length,a=this.minimum(),h=this.maximum();this.settings.loop?(!this.settings.rewind&&Math.abs(n)>r/2&&(n+=-1*o*r),(s=(((t=i+n)-a)%r+r)%r+a)!==t&&s-n<=h&&s-n>0&&(i=s-n,t=s,this.reset(i))):t=this.settings.rewind?(t%(h+=1)+h)%h:Math.max(a,Math.min(h,t)),this.speed(this.duration(i,t,e)),this.current(t),this.$element.is(":visible")&&this.update()},n.prototype.next=function(t){t=t||!1,this.to(this.relative(this.current())+1,t)},n.prototype.prev=function(t){t=t||!1,this.to(this.relative(this.current())-1,t)},n.prototype.onTransitionEnd=function(t){if(t!==s&&(t.stopPropagation(),(t.target||t.srcElement||t.originalTarget)!==this.$stage.get(0)))return!1;this.leave("animating"),this.trigger("translated")},n.prototype.viewport=function(){var s;return this.options.responsiveBaseElement!==e?s=t(this.options.responsiveBaseElement).width():e.innerWidth?s=e.innerWidth:i.documentElement&&i.documentElement.clientWidth?s=i.documentElement.clientWidth:console.warn("Can not detect viewport width."),s},n.prototype.replace=function(e){this.$stage.empty(),this._items=[],e&&(e=e instanceof jQuery?e:t(e)),this.settings.nestedItemSelector&&(e=e.find("."+this.settings.nestedItemSelector)),e.filter(function(){return 1===this.nodeType}).each(t.proxy(function(t,e){e=this.prepare(e),this.$stage.append(e),this._items.push(e),this._mergers.push(1*e.find("[data-merge]").addBack("[data-merge]").attr("data-merge")||1)},this)),this.reset(this.isNumeric(this.settings.startPosition)?this.settings.startPosition:0),this.invalidate("items")},n.prototype.add=function(e,i){var n=this.relative(this._current);i=i===s?this._items.length:this.normalize(i,!0),e=e instanceof jQuery?e:t(e),this.trigger("add",{content:e,position:i}),e=this.prepare(e),0===this._items.length||i===this._items.length?(0===this._items.length&&this.$stage.append(e),0!==this._items.length&&this._items[i-1].after(e),this._items.push(e),this._mergers.push(1*e.find("[data-merge]").addBack("[data-merge]").attr("data-merge")||1)):(this._items[i].before(e),this._items.splice(i,0,e),this._mergers.splice(i,0,1*e.find("[data-merge]").addBack("[data-merge]").attr("data-merge")||1)),this._items[n]&&this.reset(this._items[n].index()),this.invalidate("items"),this.trigger("added",{content:e,position:i})},n.prototype.remove=function(t){(t=this.normalize(t,!0))!==s&&(this.trigger("remove",{content:this._items[t],position:t}),this._items[t].remove(),this._items.splice(t,1),this._mergers.splice(t,1),this.invalidate("items"),this.trigger("removed",{content:null,position:t}))},n.prototype.preloadAutoWidthImages=function(e){e.each(t.proxy(function(e,i){this.enter("pre-loading"),i=t(i),t(new Image).one("load",t.proxy(function(t){i.attr("src",t.target.src),i.css("opacity",1),this.leave("pre-loading"),!this.is("pre-loading")&&!this.is("initializing")&&this.refresh()},this)).attr("src",i.attr("src")||i.attr("data-src")||i.attr("data-src-retina"))},this))},n.prototype.destroy=function(){this.$element.off(".owl.core"),this.$stage.off(".owl.core"),t(i).off(".owl.core"),!1!==this.settings.responsive&&(e.clearTimeout(this.resizeTimer),this.off(e,"resize",this._handlers.onThrottledResize));for(var s in this._plugins)this._plugins[s].destroy();this.$stage.children(".cloned").remove(),this.$stage.unwrap(),this.$stage.children().contents().unwrap(),this.$stage.children().unwrap(),this.$element.removeClass(this.options.refreshClass).removeClass(this.options.loadingClass).removeClass(this.options.loadedClass).removeClass(this.options.rtlClass).removeClass(this.options.dragClass).removeClass(this.options.grabClass).attr("class",this.$element.attr("class").replace(new RegExp(this.options.responsiveClass+"-\\S+\\s","g"),"")).removeData("owl.carousel")},n.prototype.op=function(t,e,i){var s=this.settings.rtl;switch(e){case"<":return s?t>i:t<i;case">":return s?t<i:t>i;case">=":return s?t<=i:t>=i;case"<=":return s?t>=i:t<=i}},n.prototype.on=function(t,e,i,s){t.addEventListener?t.addEventListener(e,i,s):t.attachEvent&&t.attachEvent("on"+e,i)},n.prototype.off=function(t,e,i,s){t.removeEventListener?t.removeEventListener(e,i,s):t.detachEvent&&t.detachEvent("on"+e,i)},n.prototype.trigger=function(e,i,s,o,r){var a={item:{count:this._items.length,index:this.current()}},h=t.camelCase(t.grep(["on",e,s],function(t){return t}).join("-").toLowerCase()),l=t.Event([e,"owl",s||"carousel"].join(".").toLowerCase(),t.extend({relatedTarget:this},a,i));return this._supress[e]||(t.each(this._plugins,function(t,e){e.onTrigger&&e.onTrigger(l)}),this.register({type:n.Type.Event,name:e}),this.$element.trigger(l),this.settings&&"function"==typeof this.settings[h]&&this.settings[h].call(this,l)),l},n.prototype.enter=function(e){t.each([e].concat(this._states.tags[e]||[]),t.proxy(function(t,e){this._states.current[e]===s&&(this._states.current[e]=0),this._states.current[e]++},this))},n.prototype.leave=function(e){t.each([e].concat(this._states.tags[e]||[]),t.proxy(function(t,e){this._states.current[e]--},this))},n.prototype.register=function(e){if(e.type===n.Type.Event){if(t.event.special[e.name]||(t.event.special[e.name]={}),!t.event.special[e.name].owl){var i=t.event.special[e.name]._default;t.event.special[e.name]._default=function(t){return!i||!i.apply||t.namespace&&-1!==t.namespace.indexOf("owl")?t.namespace&&t.namespace.indexOf("owl")>-1:i.apply(this,arguments)},t.event.special[e.name].owl=!0}}else e.type===n.Type.State&&(this._states.tags[e.name]?this._states.tags[e.name]=this._states.tags[e.name].concat(e.tags):this._states.tags[e.name]=e.tags,this._states.tags[e.name]=t.grep(this._states.tags[e.name],t.proxy(function(i,s){return t.inArray(i,this._states.tags[e.name])===s},this)))},n.prototype.suppress=function(e){t.each(e,t.proxy(function(t,e){this._supress[e]=!0},this))},n.prototype.release=function(e){t.each(e,t.proxy(function(t,e){delete this._supress[e]},this))},n.prototype.pointer=function(t){var i={x:null,y:null};return(t=(t=t.originalEvent||t||e.event).touches&&t.touches.length?t.touches[0]:t.changedTouches&&t.changedTouches.length?t.changedTouches[0]:t).pageX?(i.x=t.pageX,i.y=t.pageY):(i.x=t.clientX,i.y=t.clientY),i},n.prototype.isNumeric=function(t){return!isNaN(parseFloat(t))},n.prototype.difference=function(t,e){return{x:t.x-e.x,y:t.y-e.y}},t.fn.owlCarousel=function(e){var i=Array.prototype.slice.call(arguments,1);return this.each(function(){var s=t(this),o=s.data("owl.carousel");o||(o=new n(this,"object"==typeof e&&e),s.data("owl.carousel",o),t.each(["next","prev","to","destroy","refresh","replace","add","remove"],function(e,i){o.register({type:n.Type.Event,name:i}),o.$element.on(i+".owl.carousel.core",t.proxy(function(t){t.namespace&&t.relatedTarget!==this&&(this.suppress([i]),o[i].apply(this,[].slice.call(arguments,1)),this.release([i]))},o))})),"string"==typeof e&&"_"!==e.charAt(0)&&o[e].apply(o,i)})},t.fn.owlCarousel.Constructor=n}(window.Zepto||window.jQuery,window,document),function(t,e,i,s){var n=function(e){this._core=e,this._interval=null,this._visible=null,this._handlers={"initialized.owl.carousel":t.proxy(function(t){t.namespace&&this._core.settings.autoRefresh&&this.watch()},this)},this._core.options=t.extend({},n.Defaults,this._core.options),this._core.$element.on(this._handlers)};n.Defaults={autoRefresh:!0,autoRefreshInterval:500},n.prototype.watch=function(){this._interval||(this._visible=this._core.$element.is(":visible"),this._interval=e.setInterval(t.proxy(this.refresh,this),this._core.settings.autoRefreshInterval))},n.prototype.refresh=function(){this._core.$element.is(":visible")!==this._visible&&(this._visible=!this._visible,this._core.$element.toggleClass("owl-hidden",!this._visible),this._visible&&this._core.invalidate("width")&&this._core.refresh())},n.prototype.destroy=function(){var t,i;e.clearInterval(this._interval);for(t in this._handlers)this._core.$element.off(t,this._handlers[t]);for(i in Object.getOwnPropertyNames(this))"function"!=typeof this[i]&&(this[i]=null)},t.fn.owlCarousel.Constructor.Plugins.AutoRefresh=n}(window.Zepto||window.jQuery,window,document),function(t,e,i,s){var n=function(e){this._core=e,this._loaded=[],this._handlers={"initialized.owl.carousel change.owl.carousel resized.owl.carousel":t.proxy(function(e){if(e.namespace&&this._core.settings&&this._core.settings.lazyLoad&&(e.property&&"position"==e.property.name||"initialized"==e.type))for(var i=this._core.settings,s=i.center&&Math.ceil(i.items/2)||i.items,n=i.center&&-1*s||0,o=(e.property&&void 0!==e.property.value?e.property.value:this._core.current())+n,r=this._core.clones().length,a=t.proxy(function(t,e){this.load(e)},this);n++<s;)this.load(r/2+this._core.relative(o)),r&&t.each(this._core.clones(this._core.relative(o)),a),o++},this)},this._core.options=t.extend({},n.Defaults,this._core.options),this._core.$element.on(this._handlers)};n.Defaults={lazyLoad:!1},n.prototype.load=function(i){var s=this._core.$stage.children().eq(i),n=s&&s.find(".owl-lazy");!n||t.inArray(s.get(0),this._loaded)>-1||(n.each(t.proxy(function(i,s){var n,o=t(s),r=e.devicePixelRatio>1&&o.attr("data-src-retina")||o.attr("data-src");this._core.trigger("load",{element:o,url:r},"lazy"),o.is("img")?o.one("load.owl.lazy",t.proxy(function(){o.css("opacity",1),this._core.trigger("loaded",{element:o,url:r},"lazy")},this)).attr("src",r):((n=new Image).onload=t.proxy(function(){o.css({"background-image":'url("'+r+'")',opacity:"1"}),this._core.trigger("loaded",{element:o,url:r},"lazy")},this),n.src=r)},this)),this._loaded.push(s.get(0)))},n.prototype.destroy=function(){var t,e;for(t in this.handlers)this._core.$element.off(t,this.handlers[t]);for(e in Object.getOwnPropertyNames(this))"function"!=typeof this[e]&&(this[e]=null)},t.fn.owlCarousel.Constructor.Plugins.Lazy=n}(window.Zepto||window.jQuery,window,document),function(t,e,i,s){var n=function(e){this._core=e,this._handlers={"initialized.owl.carousel refreshed.owl.carousel":t.proxy(function(t){t.namespace&&this._core.settings.autoHeight&&this.update()},this),"changed.owl.carousel":t.proxy(function(t){t.namespace&&this._core.settings.autoHeight&&"position"==t.property.name&&this.update()},this),"loaded.owl.lazy":t.proxy(function(t){t.namespace&&this._core.settings.autoHeight&&t.element.closest("."+this._core.settings.itemClass).index()===this._core.current()&&this.update()},this)},this._core.options=t.extend({},n.Defaults,this._core.options),this._core.$element.on(this._handlers)};n.Defaults={autoHeight:!1,autoHeightClass:"owl-height"},n.prototype.update=function(){var e,i=this._core._current,s=i+this._core.settings.items,n=this._core.$stage.children().toArray().slice(i,s),o=[];t.each(n,function(e,i){o.push(t(i).height())}),e=Math.max.apply(null,o),this._core.$stage.parent().height(e).addClass(this._core.settings.autoHeightClass)},n.prototype.destroy=function(){var t,e;for(t in this._handlers)this._core.$element.off(t,this._handlers[t]);for(e in Object.getOwnPropertyNames(this))"function"!=typeof this[e]&&(this[e]=null)},t.fn.owlCarousel.Constructor.Plugins.AutoHeight=n}(window.Zepto||window.jQuery,window,document),function(t,e,i,s){var n=function(e){this._core=e,this._videos={},this._playing=null,this._handlers={"initialized.owl.carousel":t.proxy(function(t){t.namespace&&this._core.register({type:"state",name:"playing",tags:["interacting"]})},this),"resize.owl.carousel":t.proxy(function(t){t.namespace&&this._core.settings.video&&this.isInFullScreen()&&t.preventDefault()},this),"refreshed.owl.carousel":t.proxy(function(t){t.namespace&&this._core.is("resizing")&&this._core.$stage.find(".cloned .owl-video-frame").remove()},this),"changed.owl.carousel":t.proxy(function(t){t.namespace&&"position"===t.property.name&&this._playing&&this.stop()},this),"prepared.owl.carousel":t.proxy(function(e){if(e.namespace){var i=t(e.content).find(".owl-video");i.length&&(i.css("display","none"),this.fetch(i,t(e.content)))}},this)},this._core.options=t.extend({},n.Defaults,this._core.options),this._core.$element.on(this._handlers),this._core.$element.on("click.owl.video",".owl-video-play-icon",t.proxy(function(t){this.play(t)},this))};n.Defaults={video:!1,videoHeight:!1,videoWidth:!1},n.prototype.fetch=function(t,e){var i=t.attr("data-vimeo-id")?"vimeo":t.attr("data-vzaar-id")?"vzaar":"youtube",s=t.attr("data-vimeo-id")||t.attr("data-youtube-id")||t.attr("data-vzaar-id"),n=t.attr("data-width")||this._core.settings.videoWidth,o=t.attr("data-height")||this._core.settings.videoHeight,r=t.attr("href");if(!r)throw new Error("Missing video URL.");if((s=r.match(/(http:|https:|)\/\/(player.|www.|app.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com)|vzaar\.com)\/(video\/|videos\/|embed\/|channels\/.+\/|groups\/.+\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/))[3].indexOf("youtu")>-1)i="youtube";else if(s[3].indexOf("vimeo")>-1)i="vimeo";else{if(!(s[3].indexOf("vzaar")>-1))throw new Error("Video URL not supported.");i="vzaar"}s=s[6],this._videos[r]={type:i,id:s,width:n,height:o},e.attr("data-video",r),this.thumbnail(t,this._videos[r])},n.prototype.thumbnail=function(e,i){var s,n,o,r=i.width&&i.height?'style="width:'+i.width+"px;height:"+i.height+'px;"':"",a=e.find("img"),h="src",l="",c=this._core.settings,p=function(t){n='<div class="owl-video-play-icon"></div>',s=c.lazyLoad?'<div class="owl-video-tn '+l+'" '+h+'="'+t+'"></div>':'<div class="owl-video-tn" style="opacity:1;background-image:url('+t+')"></div>',e.after(s),e.after(n)};if(e.wrap('<div class="owl-video-wrapper"'+r+"></div>"),this._core.settings.lazyLoad&&(h="data-src",l="owl-lazy"),a.length)return p(a.attr(h)),a.remove(),!1;"youtube"===i.type?(o="//img.youtube.com/vi/"+i.id+"/hqdefault.jpg",p(o)):"vimeo"===i.type?t.ajax({type:"GET",url:"//vimeo.com/api/v2/video/"+i.id+".json",jsonp:"callback",dataType:"jsonp",success:function(t){o=t[0].thumbnail_large,p(o)}}):"vzaar"===i.type&&t.ajax({type:"GET",url:"//vzaar.com/api/videos/"+i.id+".json",jsonp:"callback",dataType:"jsonp",success:function(t){o=t.framegrab_url,p(o)}})},n.prototype.stop=function(){this._core.trigger("stop",null,"video"),this._playing.find(".owl-video-frame").remove(),this._playing.removeClass("owl-video-playing"),this._playing=null,this._core.leave("playing"),this._core.trigger("stopped",null,"video")},n.prototype.play=function(e){var i,s=t(e.target).closest("."+this._core.settings.itemClass),n=this._videos[s.attr("data-video")],o=n.width||"100%",r=n.height||this._core.$stage.height();this._playing||(this._core.enter("playing"),this._core.trigger("play",null,"video"),s=this._core.items(this._core.relative(s.index())),this._core.reset(s.index()),"youtube"===n.type?i='<iframe width="'+o+'" height="'+r+'" src="//www.youtube.com/embed/'+n.id+"?autoplay=1&rel=0&v="+n.id+'" frameborder="0" allowfullscreen></iframe>':"vimeo"===n.type?i='<iframe src="//player.vimeo.com/video/'+n.id+'?autoplay=1" width="'+o+'" height="'+r+'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>':"vzaar"===n.type&&(i='<iframe frameborder="0"height="'+r+'"width="'+o+'" allowfullscreen mozallowfullscreen webkitAllowFullScreen src="//view.vzaar.com/'+n.id+'/player?autoplay=true"></iframe>'),t('<div class="owl-video-frame">'+i+"</div>").insertAfter(s.find(".owl-video")),this._playing=s.addClass("owl-video-playing"))},n.prototype.isInFullScreen=function(){var e=i.fullscreenElement||i.mozFullScreenElement||i.webkitFullscreenElement;return e&&t(e).parent().hasClass("owl-video-frame")},n.prototype.destroy=function(){var t,e;this._core.$element.off("click.owl.video");for(t in this._handlers)this._core.$element.off(t,this._handlers[t]);for(e in Object.getOwnPropertyNames(this))"function"!=typeof this[e]&&(this[e]=null)},t.fn.owlCarousel.Constructor.Plugins.Video=n}(window.Zepto||window.jQuery,window,document),function(t,e,i,s){var n=function(e){this.core=e,this.core.options=t.extend({},n.Defaults,this.core.options),this.swapping=!0,this.previous=void 0,this.next=void 0,this.handlers={"change.owl.carousel":t.proxy(function(t){t.namespace&&"position"==t.property.name&&(this.previous=this.core.current(),this.next=t.property.value)},this),"drag.owl.carousel dragged.owl.carousel translated.owl.carousel":t.proxy(function(t){t.namespace&&(this.swapping="translated"==t.type)},this),"translate.owl.carousel":t.proxy(function(t){t.namespace&&this.swapping&&(this.core.options.animateOut||this.core.options.animateIn)&&this.swap()},this)},this.core.$element.on(this.handlers)};n.Defaults={animateOut:!1,animateIn:!1},n.prototype.swap=function(){if(1===this.core.settings.items&&t.support.animation&&t.support.transition){this.core.speed(0);var e,i=t.proxy(this.clear,this),s=this.core.$stage.children().eq(this.previous),n=this.core.$stage.children().eq(this.next),o=this.core.settings.animateIn,r=this.core.settings.animateOut;this.core.current()!==this.previous&&(r&&(e=this.core.coordinates(this.previous)-this.core.coordinates(this.next),s.one(t.support.animation.end,i).css({left:e+"px"}).addClass("animated owl-animated-out").addClass(r)),o&&n.one(t.support.animation.end,i).addClass("animated owl-animated-in").addClass(o))}},n.prototype.clear=function(e){t(e.target).css({left:""}).removeClass("animated owl-animated-out owl-animated-in").removeClass(this.core.settings.animateIn).removeClass(this.core.settings.animateOut),this.core.onTransitionEnd()},n.prototype.destroy=function(){var t,e;for(t in this.handlers)this.core.$element.off(t,this.handlers[t]);for(e in Object.getOwnPropertyNames(this))"function"!=typeof this[e]&&(this[e]=null)},t.fn.owlCarousel.Constructor.Plugins.Animate=n}(window.Zepto||window.jQuery,window,document),function(t,e,i,s){var n=function(e){this._core=e,this._timeout=null,this._paused=!1,this._handlers={"changed.owl.carousel":t.proxy(function(t){t.namespace&&"settings"===t.property.name?this._core.settings.autoplay?this.play():this.stop():t.namespace&&"position"===t.property.name&&this._core.settings.autoplay&&this._setAutoPlayInterval()},this),"initialized.owl.carousel":t.proxy(function(t){t.namespace&&this._core.settings.autoplay&&this.play()},this),"play.owl.autoplay":t.proxy(function(t,e,i){t.namespace&&this.play(e,i)},this),"stop.owl.autoplay":t.proxy(function(t){t.namespace&&this.stop()},this),"mouseover.owl.autoplay":t.proxy(function(){this._core.settings.autoplayHoverPause&&this._core.is("rotating")&&this.pause()},this),"mouseleave.owl.autoplay":t.proxy(function(){this._core.settings.autoplayHoverPause&&this._core.is("rotating")&&this.play()},this),"touchstart.owl.core":t.proxy(function(){this._core.settings.autoplayHoverPause&&this._core.is("rotating")&&this.pause()},this),"touchend.owl.core":t.proxy(function(){this._core.settings.autoplayHoverPause&&this.play()},this)},this._core.$element.on(this._handlers),this._core.options=t.extend({},n.Defaults,this._core.options)};n.Defaults={autoplay:!1,autoplayTimeout:5e3,autoplayHoverPause:!1,autoplaySpeed:!1},n.prototype.play=function(t,e){this._paused=!1,this._core.is("rotating")||(this._core.enter("rotating"),this._setAutoPlayInterval())},n.prototype._getNextTimeout=function(s,n){return this._timeout&&e.clearTimeout(this._timeout),e.setTimeout(t.proxy(function(){this._paused||this._core.is("busy")||this._core.is("interacting")||i.hidden||this._core.next(n||this._core.settings.autoplaySpeed)},this),s||this._core.settings.autoplayTimeout)},n.prototype._setAutoPlayInterval=function(){this._timeout=this._getNextTimeout()},n.prototype.stop=function(){this._core.is("rotating")&&(e.clearTimeout(this._timeout),this._core.leave("rotating"))},n.prototype.pause=function(){this._core.is("rotating")&&(this._paused=!0)},n.prototype.destroy=function(){var t,e;this.stop();for(t in this._handlers)this._core.$element.off(t,this._handlers[t]);for(e in Object.getOwnPropertyNames(this))"function"!=typeof this[e]&&(this[e]=null)},t.fn.owlCarousel.Constructor.Plugins.autoplay=n}(window.Zepto||window.jQuery,window,document),function(t,e,i,s){"use strict";var n=function(e){this._core=e,this._initialized=!1,this._pages=[],this._controls={},this._templates=[],this.$element=this._core.$element,this._overrides={next:this._core.next,prev:this._core.prev,to:this._core.to},this._handlers={"prepared.owl.carousel":t.proxy(function(e){e.namespace&&this._core.settings.dotsData&&this._templates.push('<div class="'+this._core.settings.dotClass+'">'+t(e.content).find("[data-dot]").addBack("[data-dot]").attr("data-dot")+"</div>")},this),"added.owl.carousel":t.proxy(function(t){t.namespace&&this._core.settings.dotsData&&this._templates.splice(t.position,0,this._templates.pop())},this),"remove.owl.carousel":t.proxy(function(t){t.namespace&&this._core.settings.dotsData&&this._templates.splice(t.position,1)},this),"changed.owl.carousel":t.proxy(function(t){t.namespace&&"position"==t.property.name&&this.draw()},this),"initialized.owl.carousel":t.proxy(function(t){t.namespace&&!this._initialized&&(this._core.trigger("initialize",null,"navigation"),this.initialize(),this.update(),this.draw(),this._initialized=!0,this._core.trigger("initialized",null,"navigation"))},this),"refreshed.owl.carousel":t.proxy(function(t){t.namespace&&this._initialized&&(this._core.trigger("refresh",null,"navigation"),this.update(),this.draw(),this._core.trigger("refreshed",null,"navigation"))},this)},this._core.options=t.extend({},n.Defaults,this._core.options),this.$element.on(this._handlers)};n.Defaults={nav:!1,navText:["prev","next"],navSpeed:!1,navElement:"div",navContainer:!1,navContainerClass:"owl-nav",navClass:["owl-prev","owl-next"],slideBy:1,dotClass:"owl-dot",dotsClass:"owl-dots",dots:!0,dotsEach:!1,dotsData:!1,dotsSpeed:!1,dotsContainer:!1},n.prototype.initialize=function(){var e,i=this._core.settings;this._controls.$relative=(i.navContainer?t(i.navContainer):t("<div>").addClass(i.navContainerClass).appendTo(this.$element)).addClass("disabled"),this._controls.$previous=t("<"+i.navElement+">").addClass(i.navClass[0]).html(i.navText[0]).prependTo(this._controls.$relative).on("click",t.proxy(function(t){this.prev(i.navSpeed)},this)),this._controls.$next=t("<"+i.navElement+">").addClass(i.navClass[1]).html(i.navText[1]).appendTo(this._controls.$relative).on("click",t.proxy(function(t){this.next(i.navSpeed)},this)),i.dotsData||(this._templates=[t("<div>").addClass(i.dotClass).append(t("<span>")).prop("outerHTML")]),this._controls.$absolute=(i.dotsContainer?t(i.dotsContainer):t("<div>").addClass(i.dotsClass).appendTo(this.$element)).addClass("disabled"),this._controls.$absolute.on("click","div",t.proxy(function(e){var s=t(e.target).parent().is(this._controls.$absolute)?t(e.target).index():t(e.target).parent().index();e.preventDefault(),this.to(s,i.dotsSpeed)},this));for(e in this._overrides)this._core[e]=t.proxy(this[e],this)},n.prototype.destroy=function(){var t,e,i,s;for(t in this._handlers)this.$element.off(t,this._handlers[t]);for(e in this._controls)this._controls[e].remove();for(s in this.overides)this._core[s]=this._overrides[s];for(i in Object.getOwnPropertyNames(this))"function"!=typeof this[i]&&(this[i]=null)},n.prototype.update=function(){var t,e,i=this._core.clones().length/2,s=i+this._core.items().length,n=this._core.maximum(!0),o=this._core.settings,r=o.center||o.autoWidth||o.dotsData?1:o.dotsEach||o.items;if("page"!==o.slideBy&&(o.slideBy=Math.min(o.slideBy,o.items)),o.dots||"page"==o.slideBy)for(this._pages=[],t=i,e=0,0;t<s;t++){if(e>=r||0===e){if(this._pages.push({start:Math.min(n,t-i),end:t-i+r-1}),Math.min(n,t-i)===n)break;e=0,0}e+=this._core.mergers(this._core.relative(t))}},n.prototype.draw=function(){var e,i=this._core.settings,s=this._core.items().length<=i.items,n=this._core.relative(this._core.current()),o=i.loop||i.rewind;this._controls.$relative.toggleClass("disabled",!i.nav||s),i.nav&&(this._controls.$previous.toggleClass("disabled",!o&&n<=this._core.minimum(!0)),this._controls.$next.toggleClass("disabled",!o&&n>=this._core.maximum(!0))),this._controls.$absolute.toggleClass("disabled",!i.dots||s),i.dots&&(e=this._pages.length-this._controls.$absolute.children().length,i.dotsData&&0!==e?this._controls.$absolute.html(this._templates.join("")):e>0?this._controls.$absolute.append(new Array(e+1).join(this._templates[0])):e<0&&this._controls.$absolute.children().slice(e).remove(),this._controls.$absolute.find(".active").removeClass("active"),this._controls.$absolute.children().eq(t.inArray(this.current(),this._pages)).addClass("active"))},n.prototype.onTrigger=function(e){var i=this._core.settings;e.page={index:t.inArray(this.current(),this._pages),count:this._pages.length,size:i&&(i.center||i.autoWidth||i.dotsData?1:i.dotsEach||i.items)}},n.prototype.current=function(){var e=this._core.relative(this._core.current());return t.grep(this._pages,t.proxy(function(t,i){return t.start<=e&&t.end>=e},this)).pop()},n.prototype.getPosition=function(e){var i,s,n=this._core.settings;return"page"==n.slideBy?(i=t.inArray(this.current(),this._pages),s=this._pages.length,e?++i:--i,i=this._pages[(i%s+s)%s].start):(i=this._core.relative(this._core.current()),s=this._core.items().length,e?i+=n.slideBy:i-=n.slideBy),i},n.prototype.next=function(e){t.proxy(this._overrides.to,this._core)(this.getPosition(!0),e)},n.prototype.prev=function(e){t.proxy(this._overrides.to,this._core)(this.getPosition(!1),e)},n.prototype.to=function(e,i,s){var n;!s&&this._pages.length?(n=this._pages.length,t.proxy(this._overrides.to,this._core)(this._pages[(e%n+n)%n].start,i)):t.proxy(this._overrides.to,this._core)(e,i)},t.fn.owlCarousel.Constructor.Plugins.Navigation=n}(window.Zepto||window.jQuery,window,document),function(t,e,i,s){"use strict";var n=function(i){this._core=i,this._hashes={},this.$element=this._core.$element,this._handlers={"initialized.owl.carousel":t.proxy(function(i){i.namespace&&"URLHash"===this._core.settings.startPosition&&t(e).trigger("hashchange.owl.navigation")},this),"prepared.owl.carousel":t.proxy(function(e){if(e.namespace){var i=t(e.content).find("[data-hash]").addBack("[data-hash]").attr("data-hash");if(!i)return;this._hashes[i]=e.content}},this),"changed.owl.carousel":t.proxy(function(i){if(i.namespace&&"position"===i.property.name){var s=this._core.items(this._core.relative(this._core.current())),n=t.map(this._hashes,function(t,e){return t===s?e:null}).join();if(!n||e.location.hash.slice(1)===n)return;e.location.hash=n}},this)},this._core.options=t.extend({},n.Defaults,this._core.options),this.$element.on(this._handlers),t(e).on("hashchange.owl.navigation",t.proxy(function(t){var i=e.location.hash.substring(1),s=this._core.$stage.children(),n=this._hashes[i]&&s.index(this._hashes[i]);void 0!==n&&n!==this._core.current()&&this._core.to(this._core.relative(n),!1,!0)},this))};n.Defaults={URLhashListener:!1},n.prototype.destroy=function(){var i,s;t(e).off("hashchange.owl.navigation");for(i in this._handlers)this._core.$element.off(i,this._handlers[i]);for(s in Object.getOwnPropertyNames(this))"function"!=typeof this[s]&&(this[s]=null)},t.fn.owlCarousel.Constructor.Plugins.Hash=n}(window.Zepto||window.jQuery,window,document),function(t,e,i,s){var n=t("<support>").get(0).style,o="Webkit Moz O ms".split(" "),r={transition:{end:{WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd",transition:"transitionend"}},animation:{end:{WebkitAnimation:"webkitAnimationEnd",MozAnimation:"animationend",OAnimation:"oAnimationEnd",animation:"animationend"}}},a=function(){return!!c("transform")},h=function(){return!!c("perspective")},l=function(){return!!c("animation")};function c(e,i){var r=!1,a=e.charAt(0).toUpperCase()+e.slice(1);return t.each((e+" "+o.join(a+" ")+a).split(" "),function(t,e){if(n[e]!==s)return r=!i||e,!1}),r}function p(t){return c(t,!0)}(function(){return!!c("transition")})()&&(t.support.transition=new String(p("transition")),t.support.transition.end=r.transition.end[t.support.transition]),l()&&(t.support.animation=new String(p("animation")),t.support.animation.end=r.animation.end[t.support.animation]),a()&&(t.support.transform=new String(p("transform")),t.support.transform3d=h())}(window.Zepto||window.jQuery,window,document);
/**
 * [Chart.PieceLabel.js]{@link https://github.com/emn178/Chart.PieceLabel.js}
 *
 * @version 0.9.0
 * @author Chen, Yi-Cyuan [emn178@gmail.com]
 * @copyright Chen, Yi-Cyuan 2017
 * @license MIT
 */
(function(){function c(){this.drawDataset=this.drawDataset.bind(this)}"undefined"===typeof Chart?console.warn("Can not find Chart object."):(c.prototype.beforeDatasetsUpdate=function(a){if(this.parseOptions(a)&&"outside"===this.position){var b=1.5*this.fontSize+2;a.chartArea.top+=b;a.chartArea.bottom-=b}},c.prototype.afterDatasetsDraw=function(a){this.parseOptions(a)&&(this.labelBounds=[],a.config.data.datasets.forEach(this.drawDataset))},c.prototype.drawDataset=function(a){for(var b=this.ctx,p=this.chartInstance,
l=a._meta[Object.keys(a._meta)[0]],h=0,f=0;f<l.data.length;f++){var g=l.data[f],d=g._view;if(0!==d.circumference||this.showZero){switch(this.render){case "value":var e=a.data[f];this.format&&(e=this.format(e));e=e.toString();break;case "label":e=p.config.data.labels[f];break;case "image":e=this.images[f]?this.loadImage(this.images[f]):"";break;default:var q=d.circumference/this.options.circumference*100;q=parseFloat(q.toFixed(this.precision));this.showActualPercentages||(h+=q,100<h&&(q-=h-100,q=parseFloat(q.toFixed(this.precision))));
e=q+"%"}"function"===typeof this.render&&(e=this.render({label:p.config.data.labels[f],value:a.data[f],percentage:q,dataset:a,index:f}),"object"===typeof e&&(e=this.loadImage(e)));if(!e)break;b.save();b.beginPath();b.font=Chart.helpers.fontString(this.fontSize,this.fontStyle,this.fontFamily);if("outside"===this.position||"border"===this.position&&"pie"===p.config.type){var k=d.outerRadius/2;var c,m=this.fontSize+2;var n=d.startAngle+(d.endAngle-d.startAngle)/2;"border"===this.position?c=(d.outerRadius-
k)/2+k:"outside"===this.position&&(c=d.outerRadius-k+k+m);n={x:d.x+Math.cos(n)*c,y:d.y+Math.sin(n)*c};if("outside"===this.position){n.x=n.x<d.x?n.x-m:n.x+m;var r=d.outerRadius+m}}else k=d.innerRadius,n=g.tooltipPosition();m=this.fontColor;"function"===typeof m?m=m({label:p.config.data.labels[f],value:a.data[f],percentage:q,text:e,backgroundColor:a.backgroundColor[f],dataset:a,index:f}):"string"!==typeof m&&(m=m[f]||this.options.defaultFontColor);if(this.arc)r||(r=(k+d.outerRadius)/2),b.fillStyle=
m,b.textBaseline="middle",this.drawArcText(e,r,d,this.overlap);else{k=this.measureText(e);d=n.x-k.width/2;k=n.x+k.width/2;var t=n.y-this.fontSize/2,u=n.y+this.fontSize/2;(this.overlap||("outside"===this.position?this.checkTextBound(d,k,t,u):g.inRange(d,t)&&g.inRange(d,u)&&g.inRange(k,t)&&g.inRange(k,u)))&&this.fillText(e,n,m)}b.restore()}}},c.prototype.parseOptions=function(a){var b=a.options.pieceLabel;return b?(this.chartInstance=a,this.ctx=a.chart.ctx,this.options=a.config.options,this.render=
b.render||b.mode,this.position=b.position||"default",this.arc=b.arc,this.format=b.format,this.precision=b.precision||0,this.fontSize=b.fontSize||this.options.defaultFontSize,this.fontColor=b.fontColor||this.options.defaultFontColor,this.fontStyle=b.fontStyle||this.options.defaultFontStyle,this.fontFamily=b.fontFamily||this.options.defaultFontFamily,this.hasTooltip=a.tooltip._active&&a.tooltip._active.length,this.showZero=b.showZero,this.overlap=b.overlap,this.images=b.images||[],this.showActualPercentages=
b.showActualPercentages||!1,!0):!1},c.prototype.checkTextBound=function(a,b,p,l){for(var h=this.labelBounds,f=0;f<h.length;++f){for(var g=h[f],d=[[a,p],[a,l],[b,p],[b,l]],e=0;e<d.length;++e){var c=d[e][0],k=d[e][1];if(c>=g.left&&c<=g.right&&k>=g.top&&k<=g.bottom)return!1}d=[[g.left,g.top],[g.left,g.bottom],[g.right,g.top],[g.right,g.bottom]];for(e=0;e<d.length;++e)if(c=d[e][0],k=d[e][1],c>=a&&c<=b&&k>=p&&k<=l)return!1}h.push({left:a,right:b,top:p,bottom:l});return!0},c.prototype.measureText=function(a){return"object"===
typeof a?{width:a.width,height:a.height}:this.ctx.measureText(a)},c.prototype.fillText=function(a,b,p){var c=this.ctx;"object"===typeof a?c.drawImage(a,b.x-a.width/2,b.y-a.height/2,a.width,a.height):(c.fillStyle=p,c.textBaseline="top",c.textAlign="center",c.fillText(a,b.x,b.y-this.fontSize/2))},c.prototype.loadImage=function(a){var b=new Image;b.src=a.src;b.width=a.width;b.height=a.height;return b},c.prototype.drawArcText=function(a,b,c,l){var h=this.ctx,f=c.x,g=c.y,d=c.startAngle;c=c.endAngle;h.save();
h.translate(f,g);g=c-d;d+=Math.PI/2;c+=Math.PI/2;var e=d;f=this.measureText(a);d+=(c-(f.width/b+d))/2;if(l||!(c-d>g))if("string"===typeof a)for(h.rotate(d),l=0;l<a.length;l++)d=a.charAt(l),f=h.measureText(d),h.save(),h.translate(0,-1*b),h.fillText(d,0,0),h.restore(),h.rotate(f.width/b);else h.rotate((e+c)/2),h.translate(0,-1*b),this.fillText(a,{x:0,y:0});h.restore()},Chart.pluginService.register({beforeInit:function(a){a.pieceLabel=new c},beforeDatasetsUpdate:function(a){a.pieceLabel.beforeDatasetsUpdate(a)},
afterDatasetsDraw:function(a){a.pieceLabel.afterDatasetsDraw(a)}}))})();

!function(t,e,r,s,n){for(var a,i,o,c,p=r.createElement("div").style,l="Transform",f=["O"+l,"ms"+l,"Webkit"+l,"Moz"+l],u=f.length,g=("Float32Array"in e),m=/Matrix([^)]*)/,x=/^\s*matrix\(\s*1\s*,\s*0\s*,\s*0\s*,\s*1\s*(?:,\s*0(?:px)?\s*){2}\)\s*$/,d="transform",k="transformOrigin",h="translate",y="rotate",b="scale",I="skew",M="matrix";u--;)f[u]in p&&(t.support[d]=a=f[u],t.support[k]=a+"Origin");function O(e){e=e.split(")");var r,n,a,i=t.trim,o=-1,c=e.length-1,p=g?new Float32Array(6):[],l=g?new Float32Array(6):[],f=g?new Float32Array(6):[1,0,0,1,0,0];for(p[0]=p[3]=f[0]=f[3]=1,p[1]=p[2]=p[4]=p[5]=0;++o<c;){switch(n=i((r=e[o].split("("))[0]),a=r[1],l[0]=l[3]=1,l[1]=l[2]=l[4]=l[5]=0,n){case h+"X":l[4]=parseInt(a,10);break;case h+"Y":l[5]=parseInt(a,10);break;case h:a=a.split(","),l[4]=parseInt(a[0],10),l[5]=parseInt(a[1]||0,10);break;case y:a=j(a),l[0]=s.cos(a),l[1]=s.sin(a),l[2]=-s.sin(a),l[3]=s.cos(a);break;case b+"X":l[0]=+a;break;case b+"Y":l[3]=a;break;case b:a=a.split(","),l[0]=a[0],l[3]=a.length>1?a[1]:a[0];break;case I+"X":l[2]=s.tan(j(a));break;case I+"Y":l[1]=s.tan(j(a));break;case M:a=a.split(","),l[0]=a[0],l[1]=a[1],l[2]=a[2],l[3]=a[3],l[4]=parseInt(a[4],10),l[5]=parseInt(a[5],10)}f[0]=p[0]*l[0]+p[2]*l[1],f[1]=p[1]*l[0]+p[3]*l[1],f[2]=p[0]*l[2]+p[2]*l[3],f[3]=p[1]*l[2]+p[3]*l[3],f[4]=p[0]*l[4]+p[2]*l[5]+p[4],f[5]=p[1]*l[4]+p[3]*l[5]+p[5],p=[f[0],f[1],f[2],f[3],f[4],f[5]]}return f}function v(t){var e,r,n,a=t[0],i=t[1],o=t[2],c=t[3];return a*c-i*o?(o-=(a/=e=s.sqrt(a*a+i*i))*(n=a*o+(i/=e)*c),c-=i*n,n/=r=s.sqrt(o*o+c*c),a*(c/=r)<i*(o/=r)&&(a=-a,i=-i,n=-n,e=-e)):e=r=n=0,[[h,[+t[4],+t[5]]],[y,s.atan2(i,a)],[I+"X",s.atan(n)],[b,[e,r]]]}function X(t,e){var r,s=+!t.indexOf(b),n=t.replace(/e[XY]/,"e");switch(t){case h+"Y":case b+"Y":e=[s,e?parseFloat(e):s];break;case h+"X":case h:case b+"X":r=1;case b:e=e?(e=e.split(","))&&[parseFloat(e[0]),parseFloat(e.length>1?e[1]:t==b?r||e[0]:s+"")]:[s,s];break;case I+"X":case I+"Y":case y:e=e?j(e):0;break;case M:return v(e?H(e):[1,0,0,1,0,0])}return[[n,e]]}function w(t){return x.test(t)}function F(t){return t.replace(/(?:\([^)]*\))|\s/g,"")}function Y(t,e,r){for(;r=e.shift();)t.push(r)}function j(t){return~t.indexOf("deg")?parseInt(t,10)*(2*s.PI/360):~t.indexOf("grad")?parseInt(t,10)*(s.PI/200):parseFloat(t)}function H(t){return[(t=/([^,]*),([^,]*),([^,]*),([^,]*),([^,p]*)(?:px)?,([^)p]*)(?:px)?/.exec(t))[1],t[2],t[3],t[4],t[5],t[6]]}a||(t.support.matrixFilter=i=""===p.filter),t.cssNumber[d]=t.cssNumber[k]=!0,a&&a!=d?(t.cssProps[d]=a,t.cssProps[k]=a+"Origin",a=="Moz"+l?o={get:function(e,r){return r?t.css(e,a).split("px").join(""):e.style[a]},set:function(t,e){t.style[a]=/matrix\([^)p]*\)/.test(e)?e.replace(/matrix((?:[^,]*,){4})([^,]*),([^)]*)/,M+"$1$2px,$3px"):e}}:/^1\.[0-5](?:\.|$)/.test(t.fn.jquery)&&(o={get:function(e,r){return r?t.css(e,a.replace(/^ms/,"Ms")):e.style[a]}})):i&&(o={get:function(e,r,s){var n,a,i=r&&e.currentStyle?e.currentStyle:e.style;return n=i&&m.test(i.filter)?[(n=RegExp.$1.split(","))[0].split("=")[1],n[2].split("=")[1],n[1].split("=")[1],n[3].split("=")[1]]:[1,0,0,1],t.cssHooks[k]?(a=t._data(e,"transformTranslate",void 0),n[4]=a?a[0]:0,n[5]=a?a[1]:0):(n[4]=i&&parseInt(i.left,10)||0,n[5]=i&&parseInt(i.top,10)||0),s?n:M+"("+n+")"},set:function(e,r,s){var n,a,i,o,c=e.style;s||(c.zoom=1),a=["Matrix(M11="+(r=O(r))[0],"M12="+r[2],"M21="+r[1],"M22="+r[3],"SizingMethod='auto expand'"].join(),i=(n=e.currentStyle)&&n.filter||c.filter||"",c.filter=m.test(i)?i.replace(m,a):i+" progid:DXImageTransform.Microsoft."+a+")",t.cssHooks[k]?t.cssHooks[k].set(e,r):((o=t.transform.centerOrigin)&&(c["margin"==o?"marginLeft":"left"]=-e.offsetWidth/2+e.clientWidth/2+"px",c["margin"==o?"marginTop":"top"]=-e.offsetHeight/2+e.clientHeight/2+"px"),c.left=r[4]+"px",c.top=r[5]+"px")}}),o&&(t.cssHooks[d]=o),c=o&&o.get||t.css,t.fx.step.transform=function(e){var r,n,p,l,f=e.elem,u=e.start,g=e.end,m=e.pos,x="";for(u&&"string"!=typeof u||(u||(u=c(f,a)),i&&(f.style.zoom=1),g=g.split("+=").join(u),t.extend(e,function(e,r){var s,n,a,i,o={start:[],end:[]},c=-1;("none"==e||w(e))&&(e=""),("none"==r||w(r))&&(r=""),e&&r&&!r.indexOf("matrix")&&H(e).join()==H(r.split(")")[0]).join()&&(o.origin=e,e="",r=r.slice(r.indexOf(")")+1));if(!e&&!r)return;if(e&&r&&F(e)!=F(r))o.start=v(O(e)),o.end=v(O(r));else for(e&&(e=e.split(")"))&&(s=e.length),r&&(r=r.split(")"))&&(s=r.length);++c<s-1;)e[c]&&(n=e[c].split("(")),r[c]&&(a=r[c].split("(")),i=t.trim((n||a)[0]),Y(o.start,X(i,n?n[1]:0)),Y(o.end,X(i,a?a[1]:0));return o}(u,g)),u=e.start,g=e.end),r=u.length;r--;)switch(n=u[r],p=g[r],l=0,n[0]){case h:l="px";case b:l||(l=""),x=n[0]+"("+s.round(1e5*(n[1][0]+(p[1][0]-n[1][0])*m))/1e5+l+","+s.round(1e5*(n[1][1]+(p[1][1]-n[1][1])*m))/1e5+l+")"+x;break;case I+"X":case I+"Y":case y:x=n[0]+"("+s.round(1e5*(n[1]+(p[1]-n[1])*m))/1e5+"rad)"+x}e.origin&&(x=e.origin+x),o&&o.set?o.set(f,x,1):f.style[a]=x},t.transform={centerOrigin:"margin"}}(jQuery,window,document,Math);
!function(e){e.extend(e.easing,{spincrementEasing:function(e,t,n,a,i){return t==i?n+a:a*(1-Math.pow(2,-10*t/i))+n}}),e.fn.spincrement=function(t){var n=e.extend({from:0,to:!1,decimalPlaces:0,decimalPoint:".",thousandSeparator:",",duration:1e3,leeway:50,easing:"spincrementEasing",fade:!0},t),a=new RegExp(/^(-?[0-9]+)([0-9]{3})/);function i(e){if(e=e.toFixed(n.decimalPlaces),n.decimalPlaces>0&&"."!=n.decimalPoint&&(e=e.replace(".",n.decimalPoint)),n.thousandSeparator)for(;a.test(e);)e=e.replace(a,"$1"+n.thousandSeparator+"$2");return e}return this.each(function(){var t=e(this),a=n.from,o=0!=n.to?n.to:parseFloat(t.html()),r=n.duration;n.leeway&&(r+=Math.round(n.duration*((2*Math.random()-1)*n.leeway/100))),t.css("counter",a),n.fade&&t.css("opacity",0),t.animate({counter:o,opacity:1},{easing:n.easing,duration:r,step:function(e){t.css("visibility","visible"),t.html(i(e*o))},complete:function(){t.css("counter",null),t.html(i(o))}})})}}(jQuery);
/*!
 * jquery.customSelect() - v0.5.1
 * http://adam.co/lab/jquery/customselect/
 */
(function(a){a.fn.extend({customSelect:function(c){if(typeof document.body.style.maxHeight==="undefined"){return this}var e={customClass:"customSelect",mapClass:true,mapStyle:true},c=a.extend(e,c),d=c.customClass,f=function(h,k){var g=h.find(":selected"),j=k.children(":first"),i=g.html()||"&nbsp;";j.html(i);if(g.attr("disabled")){k.addClass(b("DisabledOption"))}else{k.removeClass(b("DisabledOption"))}setTimeout(function(){k.removeClass(b("Open"));a(document).off("mouseup.customSelect")},60)},b=function(g){return d+g};return this.each(function(){var g=a(this),i=a("<span />").addClass(b("Inner")),h=a("<span />");g.after(h.append(i));h.addClass(d);if(c.mapClass){h.addClass(g.attr("class"))}if(c.mapStyle){h.attr("style",g.attr("style"))}g.addClass("hasCustomSelect").on("render.customSelect",function(){f(g,h);g.css("width","");var k=parseInt(g.outerWidth(),10)-(parseInt(h.outerWidth(),10)-parseInt(h.width(),10));h.css({display:"inline-block"});var j=h.outerHeight();if(g.attr("disabled")){h.addClass(b("Disabled"))}else{h.removeClass(b("Disabled"))}i.css({width:k,display:"inline-block"});g.css({"-webkit-appearance":"menulist-button",width:h.outerWidth(),position:"absolute",opacity:0,height:j,fontSize:h.css("font-size")})}).on("change.customSelect",function(){h.addClass(b("Changed"));f(g,h)}).on("keyup.customSelect",function(j){if(!h.hasClass(b("Open"))){g.trigger("blur.customSelect");g.trigger("focus.customSelect")}else{if(j.which==13||j.which==27){f(g,h)}}}).on("mousedown.customSelect",function(){h.removeClass(b("Changed"))}).on("mouseup.customSelect",function(j){if(!h.hasClass(b("Open"))){if(a("."+b("Open")).not(h).length>0&&typeof InstallTrigger!=="undefined"){g.trigger("focus.customSelect")}else{h.addClass(b("Open"));j.stopPropagation();a(document).one("mouseup.customSelect",function(k){if(k.target!=g.get(0)&&a.inArray(k.target,g.find("*").get())<0){g.trigger("blur.customSelect")}else{f(g,h)}})}}}).on("focus.customSelect",function(){h.removeClass(b("Changed")).addClass(b("Focus"))}).on("blur.customSelect",function(){h.removeClass(b("Focus")+" "+b("Open"))}).on("mouseenter.customSelect",function(){h.addClass(b("Hover"))}).on("mouseleave.customSelect",function(){h.removeClass(b("Hover"))}).trigger("render.customSelect")})}})})(jQuery);
/*
    radialIndicator.js v 1.3.1
    Author: Sudhanshu Yadav
    Copyright (c) 2015 Sudhanshu Yadav - ignitersworld.com , released under the MIT license.
    Demo on: ignitersworld.com/lab/radialIndicator.html
*/
!function(t){var e=Function("return this")()||(42,eval)("this");"function"==typeof define&&define.amd?define(["jquery"],function(n){return e.radialIndicator=t(n,e)}):"object"==typeof module&&module.exports?module.exports=e.document?t(require("jquery"),e):function(e){if(!e.document)throw new Error("radialIndiactor requires a window with a document");return t(require("jquery")(e),e)}:e.radialIndicator=t(e.jQuery,e)}(function(t,e,n){function r(t){var e=/^#?([a-f\d])([a-f\d])([a-f\d])$/i;t=t.replace(e,function(t,e,n,r){return e+e+n+n+r+r});var n=/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(t);return n?[parseInt(n[1],16),parseInt(n[2],16),parseInt(n[3],16)]:null}function i(t,e,n,r){return Math.round(n+(r-n)*t/e)}function a(t,e,n,a,o){var u=-1!=o.indexOf("#")?r(o):o.match(/\d+/g),l=-1!=a.indexOf("#")?r(a):a.match(/\d+/g),s=n-e,c=t-e;return u&&l?"rgb("+i(c,s,l[0],u[0])+","+i(c,s,l[1],u[1])+","+i(c,s,l[2],u[2])+")":null}function o(){for(var t=arguments,e=t[0],n=1,r=t.length;r>n;n++){var i=t[n];for(var a in i)i.hasOwnProperty(a)&&(e[a]=i[a])}return e}function u(t){return function(e){if(!t)return e.toString();e=e||0;for(var n=e.toString().split("").reverse(),r=t.split("").reverse(),i=0,a=0,o=r.length;o>i&&n.length;i++)"#"==r[i]&&(a=i,r[i]=n.shift());return r.splice(a+1,r.lastIndexOf("#")-a,n.reverse().join("")),r.reverse().join("")}}function l(t,e){function n(t){if(e.interaction){t.preventDefault();var n=-Math.max(-1,Math.min(1,t.wheelDelta||-t.detail)),i=null!=e.precision?e.precision:0,a=Math.pow(10,i),o=e.maxValue-e.minValue,u=r.current_value+Math.round(a*n*o/Math.min(o,100))/a;return r.value(u),!1}}var r=this;e=e||{},e=o({},s.defaults,e),this.indOption=e,"string"==typeof t&&(t=c.querySelector(t)),t.length&&(t=t[0]),this.container=t;var i=c.createElement("canvas");t.appendChild(i),this.canElm=i,this.ctx=i.getContext("2d"),this.current_value=e.initValue||e.minValue||0;var a=function(t){if(e.interaction){var n="touchstart"==t.type?"touchmove":"mousemove",a="touchstart"==t.type?"touchend":"mouseup",o=i.getBoundingClientRect(),u=o.top+i.offsetHeight/2,l=o.left+i.offsetWidth/2,s=function(t){t.preventDefault();var n=t.clientX||t.touches[0].clientX,i=t.clientY||t.touches[0].clientY,a=(h+d+Math.atan2(i-u,n-l))%(h+.0175),o=e.radius-1+e.barWidth/2,s=h*o,c=null!=e.precision?e.precision:0,f=Math.pow(10,c),v=Math.round(f*a*o*(e.maxValue-e.minValue)/s)/f;r.value(v)},f=function(){c.removeEventListener(n,s,!1),c.removeEventListener(a,f,!1)};c.addEventListener(n,s,!1),c.addEventListener(a,f,!1)}};i.addEventListener("touchstart",a,!1),i.addEventListener("mousedown",a,!1),i.addEventListener("mousewheel",n,!1),i.addEventListener("DOMMouseScroll",n,!1)}function s(t,e){var n=new l(t,e);return n._init(),n}var c=e.document,h=2*Math.PI,d=Math.PI/2,f=function(){var t=c.createElement("canvas").getContext("2d"),n=e.devicePixelRatio||1,r=t.webkitBackingStorePixelRatio||t.mozBackingStorePixelRatio||t.msBackingStorePixelRatio||t.oBackingStorePixelRatio||t.backingStorePixelRatio||1,i=n/r;return function(t,e,n){var r=n||c.createElement("canvas");return r.width=t*i,r.height=e*i,r.style.width=t+"px",r.style.height=e+"px",r.getContext("2d").setTransform(i,0,0,i,0,0),r}}();return l.prototype={constructor:s,_init:function(){var t=this.indOption,e=this.canElm,n=(this.ctx,2*(t.radius+t.barWidth));return this.formatter="function"==typeof t.format?t.format:u(t.format),this.maxLength=t.percentage?4:this.formatter(t.maxValue).length,f(n,n,e),this._drawBarBg(),this.value(this.current_value),this},_drawBarBg:function(){var t=this.indOption,e=this.ctx,n=2*(t.radius+t.barWidth),r=n/2;e.strokeStyle=t.barBgColor,e.lineWidth=t.barWidth,"transparent"!=t.barBgColor&&(e.beginPath(),e.arc(r,r,t.radius-1+t.barWidth/2,0,2*Math.PI),e.stroke())},value:function(t){if(t===n||isNaN(t))return this.current_value;t=parseFloat(t);var e=this.ctx,r=this.indOption,i=r.barColor,o=2*(r.radius+r.barWidth),u=r.minValue,l=r.maxValue,s=o/2;t=u>t?u:t>l?l:t;var c=null!=r.precision?r.precision:0,f=Math.pow(10,c),v=Math.round((t-u)*f/(l-u)*100)/f,m=r.percentage?v+"%":this.formatter(t);if(this.current_value=t,e.clearRect(0,0,o,o),this._drawBarBg(),"object"==typeof i)for(var p=Object.keys(i),g=1,x=p.length;x>g;g++){var b=p[g-1],y=p[g],C=i[b],M=i[y],w=t==b?C:t==y?M:t>b&&y>t?r.interpolate?a(t,b,y,C,M):M:!1;if(0!=w){i=w;break}}if(e.strokeStyle=i,r.roundCorner&&(e.lineCap="round"),e.beginPath(),e.arc(s,s,r.radius-1+r.barWidth/2,-d,h*v/100-d,!1),e.stroke(),r.displayNumber){var B=e.font.split(" "),I=r.fontWeight,V=r.fontSize||o/(this.maxLength-(Math.floor(1.4*this.maxLength/4)-1));B=r.fontFamily||B[B.length-1],e.fillStyle=r.fontColor||i,e.font=I+" "+V+"px "+B,e.textAlign="center",e.textBaseline=r.textBaseline,e.fillText(m,s,s)}return r.onChange.call(this.container,t),this},animate:function(t){var e=this.indOption,n=this.current_value||e.minValue,r=this,i=e.minValue,a=e.maxValue,o=e.frameNum||(e.percentage?100:500),u=null!=e.precision?e.precision:Math.ceil(Math.log(a-i/o)),l=Math.pow(10,u),s=Math.round((a-i)*l/o)/l;t=i>t?i:t>a?a:t;var c=n>t;return this.intvFunc&&clearInterval(this.intvFunc),this.intvFunc=setInterval(function(){if(!c&&n>=t||c&&t>=n){if(r.current_value==n)return clearInterval(r.intvFunc),void(e.onAnimationComplete&&e.onAnimationComplete(r.current_value));n=t}r.value(n),n!=t&&(n+=c?-s:s)},e.frameTime),this},option:function(t,e){return e===n?this.option[t]:(-1!=["radius","barWidth","barBgColor","format","maxValue","percentage"].indexOf(t)&&(this.indOption[t]=e,this._init().value(this.current_value)),void(this.indOption[t]=e))}},s.defaults={radius:50,barWidth:5,barBgColor:"#eeeeee",barColor:"#99CC33",format:null,frameTime:10,frameNum:null,fontColor:null,fontFamily:null,fontWeight:"bold",fontSize:null,textBaseline:"middle",interpolate:!0,percentage:!1,precision:null,displayNumber:!0,roundCorner:!1,minValue:0,maxValue:100,initValue:0,interaction:!1,onChange:function(){}},e.radialIndicator=s,t&&(t.fn.radialIndicator=function(e){return this.each(function(){var n=s(this,e);t.data(this,"radialIndicator",n)})}),s});

'use strict';

var windowHeight = document.documentElement.clientHeight;
var windowWidth = document.documentElement.clientWidth;

// Запускаем счетчик цифр, если он не был запущен до этого
var incrementDigits = function() {
    var selectors = [];
    return {
        init: function(selector, options) {
            if(selectors.indexOf(selector) == -1) {
                var options = options || false;
                var defaultOptions = { thousandSeparator: "", duration: 2000 };
                if(options){ for(var opt in defaultOptions)if(!options[opt])options[opt]=defaultOptions[opt] }
                else { options = defaultOptions; }
                $(selector).spincrement(options);
                selectors.push(selector);
            }
        }
    }
}();

function animatedBlocksParam(selector) {
    var section = document.querySelector(selector);
    return {
        section: section,
        sectionBg: section?document.querySelector(selector+'-bg'):false,
        sectionOffset: section?section.offsetTop:section
    }
}
function histogrammMenu(obj){
    var activeBorder = obj.querySelector('.active-border');
    obj.addEventListener('click', function(e) {
        if (e.target.tagName == 'SPAN') {
            activeBorder.style.marginLeft = e.target.offsetLeft+'px';
            activeBorder.style.width = e.target.offsetWidth+'px';
            obj.querySelector('.current').classList.remove('current');
            e.target.className = 'current';
        }
    });
}
function showVisible() {
    var elems = document.querySelectorAll('.animate-visible');
    for (var i = 0; i < elems.length; i++) {
        var elem = elems[i];
        if (isVisible(elem)) {
            elem.classList.remove('animate-visible')
        }
    }
}
function isVisible(elem) {
    var coords = elem.getBoundingClientRect();
    var topVisible = coords.top > 0 && coords.top < windowHeight;
    var bottomVisible = coords.bottom < windowHeight && coords.bottom > 0;
    return topVisible || bottomVisible;
}

$('input[type=text],input[type=password],input[type=tel],input[type=email],input[type=time],input[type=date],input[type=url], textarea').on({
        focus: function () {
            var fldst = $(this).closest('fieldset');
            fldst.removeClass('has_error');
            fldst.find('label, .field-border').addClass('focused');
        },
        blur: function () {
            var fldst = $(this).closest('fieldset');
            if(this.value=="")fldst.find('label, .field-border').removeClass('focused');
        }
    }
);
$('input[type=checkbox]').on({
        change: function () {
            $(this).closest('label').toggleClass('checked');
        }
    }
);
$('input[type=radio]').on({
        change: function () {
            var cname = this.name;
            var cbox = $(this);
            $('input[name='+cname+']').each(function(){
                $(this).prop('checked', false);
                $(this).closest('label').removeClass('checked');
            });
            cbox.prop('checked', true);
            $(this).closest('label').addClass('checked');
        }
    }
);
$('.inputfile' ).each( function() {
    var $input = $(this),
        $label = $input.next('label'),
        labelVal = $label.html();

    $input.on('change', function (e) {
        var fileName = '';

        if (this.files && this.files.length > 1)
            fileName = ( this.getAttribute('data-multiple-caption') || '' ).replace('{count}', this.files.length);
        else if (e.target.value)
            fileName = e.target.value.split('\\').pop();

        if (fileName)
            $label.find('span').html(fileName);
        else
            $label.html(labelVal);
    });

    // Firefox bug fix
    $input
        .on('focus', function () {
            $input.addClass('has-focus');
        })
        .on('blur', function () {
            $input.removeClass('has-focus');
        });
});
$('fieldset input').each(function(e) {
    var field = $(this).parent();
    if($(this).val() == '') {
        field.find('label, .field-border').removeClass('focused');
    } else {
        field.find('label, .field-border').addClass('focused');
    }
});
$('fieldset input[type=checkbox], fieldset input[type=radio]').each(function(e) {
    var label = $(this).parent();
    if($(this).prop('checked')) {
        label.addClass('checked');
    } else {
        label.removeClass('checked');
    }
});
$('fieldset select').customSelect();
$('.popup-toggle').on('click', function(e) {
    e.preventDefault();
    var popupShadow = $('.popup-shadow');
    var id = $(this).attr('data-target');

    $('.popup').removeClass('visible');
    popupShadow.fadeIn(300, function() {
        $('.popup.'+id).addClass('visible');
    });
});
$('.close-wrap').on('click', function() {
    var popupShadow = $('.popup-shadow');
    var id = $(this).attr('data-target');

    $('.popup').removeClass('visible');
    $('.popup').removeClass('visible');
    popupShadow.find('div').hide();
    popupShadow.fadeOut(300);
});

function displayPopupMsg(msg) {
    $('.popup-msg .popup-msg-info').html(msg);
    var popupShadow = $('.popup-shadow');
    $('.popup').removeClass('visible');
    popupShadow.fadeIn(300, function() {
        $('.popup.popup-msg').addClass('visible');
    });
}


window.addEventListener('load', function() {
    $('.mobile-burger, .switch-sidebar-menu').on(
        'click',
        function() {
            $('body').toggleClass('minified');
        }
    );
    $('.mainwrap-overlay').on('click', function(e) {
        $('body').removeClass('minified');
    });
    if(windowWidth>1139) {
        $('body:not(".minified") header nav a, body:not(".minified") .sticky-header nav a').on(
            'click',
            function(e) {
                e.preventDefault();
                var mClass = $(this).hasClass('menu-crypto')?'crypto':$(this).hasClass('menu-products')?'products':'market';
                $('.smenu > div').hide();
                switch(mClass) {
                    case 'crypto':
                        $('.smenu > div.smenu-crypto').show();
                        break;
                    case 'products':
                        $('.smenu > div.smenu-products').show();
                        break;
                    case 'market':
                        $('.smenu > div.smenu-market').show();
                        break;
                }
                $('body').addClass('minified');
            }
        )
    }
    showVisible();
});
var popupBuyBtn = {};
$('.popup-product_buy-info').on('click', '.btn', function() {
    var btn = $(this);
    btn.prop('disabled', true);
    popupBuyBtn.qty = btn.data('qty');
    $.ajax({
        url: btn.data('link'),
        type: 'POST',
        data: popupBuyBtn,
        dataType: 'json',
        success: function (response) {
            if (response.status == 'success') {
                displayPopupMsg('<h4>Product Added!</h4>');
                setTimeout(function() {
                    window.location.href='/myproducts'
                }, 2000);
                btn.prop('disabled', false);
            } else {
                displayPopupMsg(response.message);
            }
        },
        complete: function () {
            btn.prop('disabled', false);
        }
    });
});
$('.popup-product_buy-qty input').on('change', function(){
    var cost = $('.popup-product_buy-cost').text();
        cost = cost.replace(/[\s\$,]/g, '');
    var qty = $(this).val();
    $('.popup-product_buy-total').text('$ '+(1*qty*cost));
    $('.popup-product_buy-info button').data('qty', qty);
});
var pageHeaderBg = document.querySelector('body:not(.homepage) .page-header-bg');
var sticky = $('.sticky-header');
var sticky_is_visible = 'hidden';
sticky.removeClass('visible');
var lastScrollTop = 0;
window.addEventListener('scroll', function () {
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;
    if (pageHeaderBg&&windowWidth > 767) {
        pageHeaderBg.style.transform = "translateY(" + 0.25 * scrolled + "px)";
    }
    showVisible();
    var current_sticky = false;
    if(scrolled>250&&lastScrollTop>scrolled) current_sticky = true;
    if(current_sticky!=sticky_is_visible) {
        if(current_sticky) {
            sticky.addClass('visible');
        } else {
            $('.sticky-header .header-user-menu').slideUp(200);
            sticky.removeClass('visible');
        }
    }
    sticky_is_visible = current_sticky;
    lastScrollTop = scrolled;
});
$(document.forms.sign_in).on('submit', function (e) {
    e.stopPropagation();
    var formSignInData = new FormData(this);

    var username = $('#sign_in_username').val();
    var password = $('#sign_in_password').val();
    var keepsigned = $('#sign_in_keepsigned').prop('checked')?1:0;
    var btn = $(this).find('.btn');
    // if(username.length<3) {
    //     $('.popup-login-username .field-error').html('The username should be more than 3 symbols');
    //     $('.popup-login-username .field-border').addClass('focused');
    //     $('.popup-login-username').addClass('has_error');
    // } else if(password.length<8) {
    //     $('.popup-login-password .field-error').html('The length of the password should be more than 8 chars');
    //     $('.popup-login-password').addClass('has_error');
    // } else {

        $.ajax({
            url: "/signin",
            type: 'POST',
            data: formSignInData,
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == 'success') {
                    if (response.data.google2FAAttempt) {
                        document.location.reload();
                    } else {
                        location.href = '/dashboard';
                    }
                } else {
                    $('.popup-login-errors').html(response.message).removeClass('dn');
                }
            },
            complete: function() {
                btn.prop('disabled', false);
                username.val('');
                password.val('');
            }
        });
    // }

    return false;
});

$(document.forms.google2_fa).on('submit', function (e) {
    e.stopPropagation();

    let btn = $(this).find('.btn');
    let google2FAForm = new FormData(this);
    let verificationCode = $('#google2_fa_verificationCode');

    if (verificationCode.val().length !== 6) {
        $('.popup-google2FA-verificationCode .field-error').
            html('Verification Code must be at 6 characters long');
        $('.popup-google2FA-verificationCode .field-border').
            addClass('focused');
        $('.popup-google2FA-verificationCode').addClass('has_error');
    } else {
        btn.prop('disabled', true);
        $.ajax({
            url: '/google2FA',
            type: 'POST',
            data: google2FAForm,
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == 'success') {
                    location.href = '/dashboard';
                } else {
                    btn.prop('disabled', false);

                    $('.popup-google2FA-errors').
                        html(response.message).
                        removeClass('dn');
                }
            },
            complete: function() {
                verificationCode.val('');
            }
        });
    }

    return false;
});

$(document.forms.reset_password).on('submit', function (e) {
    e.stopPropagation();
    var formSignInData = new FormData(this);
    var btn = $('.popup-forgot .btn');
    btn.prop('disabled', true);
    $.ajax({
        url: "/password/reset",
        type: 'POST',
        data: formSignInData,
        cache: false,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status == 'success') {
                $('div.popup-forgot').removeClass('visible');
                $('div.popup-msg-info').html(response.message);
                $('div.popup-msg').addClass('visible');
            } else {
                $('.popup-forgot-errors').html(response.message).removeClass('dn');
            }
        },
        complete: function () {
            btn.prop('disabled', false);
            $('#reset_password_for_username').val('');
        }
    });

    return false;
});
$(document).on('click', function(event) {
    if(!$(event.target).closest('.login').length) {
        if($(event.target).closest('.header-user-menu').css('display')!='none') {
            $('.header-user-menu').slideUp(300);
        }
    }
});
$('.show-user-menu').on('click', function() {
    $(this).parent().find('.header-user-menu').slideToggle(300);
});
$('.header-user-menu-switcher').on('click', function() {
    $(this).toggleClass('active');
});
var supportBlock = (function(){
    return animatedBlocksParam('.support-block');
})();

window.addEventListener('scroll', function () {
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;
    if(supportBlock.section&&windowWidth>767) {
        supportBlock.sectionBg.style.transform = "translateY(" + 0.25 * (scrolled - supportBlock.sectionOffset) + "px)";
    }
});
