!function(a){"use strict";var b=function(b,c){this.$element=a(b);this.options=a.extend({},a.fn.typeahead.defaults,c);this.matcher=this.options.matcher||this.matcher;this.sorter=this.options.sorter||this.sorter;this.highlighter=this.options.highlighter||this.highlighter;this.$menu=a(this.options.menu).appendTo("body");this.source=this.options.source;this.shown=false;this.listen()};b.prototype={constructor:b,select:function(){var a=this.$menu.find(".active").attr("data-value");this.$element.val(a);return this.hide()},show:function(){var b=a.extend({},this.$element.offset(),{height:this.$element[0].offsetHeight});this.$menu.css({top:b.top+b.height,left:b.left});this.$menu.show();this.shown=true;return this},hide:function(){this.$menu.hide();this.shown=false;return this},lookup:function(b){var c=this,d,e;this.query=this.$element.val();if(!this.query){return this.shown?this.hide():this}d=a.grep(this.source,function(a){if(c.matcher(a))return a});d=this.sorter(d);if(!d.length){return this.shown?this.hide():this}return this.render(d.slice(0,this.options.items)).show()},matcher:function(a){return~a.toLowerCase().indexOf(this.query.toLowerCase())},sorter:function(a){var b=[],c=[],d=[],e;while(e=a.shift()){if(!e.toLowerCase().indexOf(this.query.toLowerCase()))b.push(e);else if(~e.indexOf(this.query))c.push(e);else d.push(e)}return b.concat(c,d)},highlighter:function(a){return a.replace(new RegExp("("+this.query+")","ig"),function(a,b){return"<strong>"+b+"</strong>"})},render:function(b){var c=this;b=a(b).map(function(b,d){b=a(c.options.item).attr("data-value",d);b.find("a").html(c.highlighter(d));return b[0]});b.first().addClass("active");this.$menu.html(b);return this},next:function(b){var c=this.$menu.find(".active").removeClass("active"),d=c.next();if(!d.length){d=a(this.$menu.find("li")[0])}d.addClass("active")},prev:function(a){var b=this.$menu.find(".active").removeClass("active"),c=b.prev();if(!c.length){c=this.$menu.find("li").last()}c.addClass("active")},listen:function(){this.$element.on("blur",a.proxy(this.blur,this)).on("keypress",a.proxy(this.keypress,this)).on("keyup",a.proxy(this.keyup,this));if(a.browser.webkit||a.browser.msie){this.$element.on("keydown",a.proxy(this.keypress,this))}this.$menu.on("click",a.proxy(this.click,this)).on("mouseenter","li",a.proxy(this.mouseenter,this))},keyup:function(a){a.stopPropagation();a.preventDefault();switch(a.keyCode){case 40:case 38:break;case 9:case 13:if(!this.shown)return;this.select();break;case 27:this.hide();break;default:this.lookup()}},keypress:function(a){a.stopPropagation();if(!this.shown)return;switch(a.keyCode){case 9:case 13:case 27:a.preventDefault();break;case 38:a.preventDefault();this.prev();break;case 40:a.preventDefault();this.next();break}},blur:function(a){var b=this;a.stopPropagation();a.preventDefault();setTimeout(function(){b.hide()},150)},click:function(a){a.stopPropagation();a.preventDefault();this.select()},mouseenter:function(b){this.$menu.find(".active").removeClass("active");a(b.currentTarget).addClass("active")}};a.fn.typeahead=function(c){return this.each(function(){var d=a(this),e=d.data("typeahead"),f=typeof c=="object"&&c;if(!e)d.data("typeahead",e=new b(this,f));if(typeof c=="string")e[c]()})};a.fn.typeahead.defaults={source:[],items:8,menu:'<ul class="typeahead dropdown-menu"></ul>',item:'<li><a href="#"></a></li>'};a.fn.typeahead.Constructor=b;a(function(){a("body").on("focus.typeahead.data-api",'[data-provide="typeahead"]',function(b){var c=a(this);if(c.data("typeahead"))return;b.preventDefault();c.typeahead(c.data())})})}(window.jQuery)