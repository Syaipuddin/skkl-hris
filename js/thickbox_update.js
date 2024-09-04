function tb_detectMacXFF(){
	var a=navigator.userAgent.toLowerCase();
	if(a.indexOf("mac")!=-1&&a.indexOf("firefox")!=-1){
		return true
	}
}

function tb_getPageSize(){
	var a=document.documentElement;
	var b=window.innerWidth||self.innerWidth||a&&a.clientWidth||document.body.clientWidth;
	var c=window.innerHeight||self.innerHeight||a&&a.clientHeight||document.body.clientHeight;
	arrayPageSize=[b,c];
	return arrayPageSize
}

function tb_parseQuery(a){
	var b={};
	if(!a){
		return b
	}
	var c=a.split(/[;&]/);
	for(var d=0;d<c.length;d++){
		var e=c[d].split("=");
		if(!e||e.length!=2){
			continue
		}
		var f=unescape(e[0]);
		var g=unescape(e[1]);
		g=g.replace(/\+/g," ");
		b[f]=g
	}
	return b
}

function tb_position(){
	$("#TB_window").css({marginLeft:"-"+parseInt(TB_WIDTH/2,10)+"px",width:TB_WIDTH+"px"});
	if(!(jQuery.browser.msie&&jQuery.browser.version<7)){
		$("#TB_window").css({marginTop:"-"+parseInt(TB_HEIGHT/2,10)+"px"})
	}
}

function tb_remove(refreshParentWindow) { 

refreshParentWindow = typeof refreshParentWindow !== 'undefined' ? refreshParentWindow : false; //defaults to false
if(refreshParentWindow){
        parent.location.reload(1);
}
$("#TB_imageOff").unbind("click");
$("#TB_closeWindowButton").unbind("click");
$("#TB_window").fadeOut("fast",function(){$('#TB_window,#TB_overlay,
#TB_HideSelect').trigger("unload").unbind().remove();});
$("#TB_load").remove();
if (typeof document.body.style.maxHeight == "undefined") {//if IE 6
$("body","html").css({height: "auto", width: "auto"});
$("html").css("overflow","");
}
document.onkeydown = "";
document.onkeyup = "";
return false;
}


function tb_showIframe(){
	$("#TB_load").remove();
	$("#TB_window").css({display:"block"})
}

function tb_show(a,b,c){
	try{
		if(typeof document.body.style.maxHeight==="undefined"){
			$("body","html").css({height:"100%",width:"100%"});
			$("html").css("overflow","hidden");
			if(document.getElementById("TB_HideSelect")===null){
				$("body").append("<iframe id='TB_HideSelect'></iframe><div id='TB_overlay'></div><div id='TB_window'></div>")
			}
		}
		else{
			if(document.getElementById("TB_overlay")===null){
				$("body").append("<div id='TB_overlay'></div><div id='TB_window'></div>")
			}
		}
		if(tb_detectMacXFF()){
			$("#TB_overlay").addClass("TB_overlayMacFFBGHack")
		}
		else{
			$("#TB_overlay").addClass("TB_overlayBG")
		}
		if(a===null){
			a=""
		}
		$("body").append("<div id='TB_load'><img src='"+imgLoader.src+"' /></div>");
		$("#TB_load").show();
		var d;
		if(b.indexOf("?")!==-1){
			d=b.substr(0,b.indexOf("?"))
		}
		else{
			d=b
		}
		var e=/\.jpg$|\.jpeg$|\.png$|\.gif$|\.bmp$/;
		var f=d.toLowerCase().match(e);
		if(f==".jpg"||f==".jpeg"||f==".png"||f==".gif"||f==".bmp"){
			TB_PrevCaption="";
			TB_PrevURL="";
			TB_PrevHTML="";
			TB_NextCaption="";
			TB_NextURL="";
			TB_NextHTML="";
			TB_imageCount="";
			TB_FoundURL=false;
			if(c){
				TB_TempArray=$("a[@rel="+c+"]").get();
				for(TB_Counter=0;TB_Counter<TB_TempArray.length&&TB_NextHTML==="";TB_Counter++){
					var g=TB_TempArray[TB_Counter].href.toLowerCase().match(e);
					if(!(TB_TempArray[TB_Counter].href==b)){
						if(TB_FoundURL){
							TB_NextCaption=TB_TempArray[TB_Counter].title;
							TB_NextURL=TB_TempArray[TB_Counter].href;
							TB_NextHTML="<span id='TB_next'>  <a href='#'>Next ></a></span>"
						}
						else{
							TB_PrevCaption=TB_TempArray[TB_Counter].title;
							TB_PrevURL=TB_TempArray[TB_Counter].href;
							TB_PrevHTML="<span id='TB_prev'>  <a href='#'>< Prev</a></span>"
						}
					}
					else{
						TB_FoundURL=true;
						TB_imageCount="Image "+(TB_Counter+1)+" of "+TB_TempArray.length
					}
				}
			}
			imgPreloader=new Image;
			imgPreloader.onload=function(){
				imgPreloader.onload=null;
				var d=tb_getPageSize();
				var e=d[0]-150;
				var f=d[1]-150;
				var g=imgPreloader.width;
				var h=imgPreloader.height;
				if(g>e){
					h=h*(e/g);
					g=e;
					if(h>f){
						g=g*(f/h);
						h=f
					}
				}
				else if(h>f){
					g=g*(f/h);
					h=f;
					if(g>e){
						h=h*(e/g);g=e
					}
				}
				TB_WIDTH=g+30;
				TB_HEIGHT=h+60;
				$("#TB_window").append("<a href='' id='TB_ImageOff' title='Close'><img id='TB_Image' src='"+b+"' width='"+g+"' height='"+h+"' alt='"+a+"'/></a>"+"<div id='TB_caption'>"+a+"<div id='TB_secondLine'>"+TB_imageCount+TB_PrevHTML+TB_NextHTML+"</div></div><div id='TB_closeWindow'><a href='#' id='TB_closeWindowButton'  title='Close' style='color:#F00;font-weight:bold;'>close</a> or Esc Key</div>");
				$("#TB_closeWindowButton").click(tb_remove);
				if(!(TB_PrevHTML==="")){
					function i(){
						if($(document).unbind("click",i)){
							$(document).unbind("click",i)
						}
						$("#TB_window").remove();
						$("body").append("<div id='TB_window'></div>");
						tb_show(TB_PrevCaption,TB_PrevURL,c);
						return false
					}
					$("#TB_prev").click(i)
				}
				if(!(TB_NextHTML==="")){
					function j(){
						$("#TB_window").remove();
						$("body").append("<div id='TB_window'></div>");
						tb_show(TB_NextCaption,TB_NextURL,c);
						return false
					}
					$("#TB_next").click(j)
				}
				document.onkeydown=function(a){
					if(a==null){
						keycode=event.keyCode
					}
					else{
						keycode=a.which
					}
					if(keycode==27){
						tb_remove()
					}
					else if(keycode==190){
						if(!(TB_NextHTML=="")){
							document.onkeydown="";
							j()
						}
					}
					else if(keycode==188){
						if(!(TB_PrevHTML=="")){
							document.onkeydown="";i()
						}
					}
				};
				tb_position();
				$("#TB_load").remove();
				$("#TB_ImageOff").click(tb_remove);
				$("#TB_window").css({display:"block"})
			};
			imgPreloader.src=b
		}
		else{
			var h=b.replace(/^[^\?]+\??/,"");
			var i=tb_parseQuery(h);
			TB_WIDTH=i["width"]*1+30||630;
			TB_HEIGHT=i["height"]*1+40||440;
			ajaxContentW=TB_WIDTH-30;
			ajaxContentH=TB_HEIGHT-45;
			if(b.indexOf("TB_iframe")!=-1){
				urlNoQuery=b.split("TB_");
				$("#TB_iframeContent").remove();
				if(i["modal"]!="true"){
					$("#TB_window").append("<div id='TB_title'><div id='TB_ajaxWindowTitle'>"+a+"</div><div id='TB_closeAjaxWindow'><a href='#' id='TB_closeWindowButton' title='Close' style='color:#F00;font-weight:bold;'>close</a> or Esc Key</div></div><iframe frameborder='0' hspace='0' src='"+urlNoQuery[0]+"' id='TB_iframeContent' name='TB_iframeContent"+Math.round(Math.random()*1e3)+"' onload='tb_showIframe()' style='width:"+(ajaxContentW+29)+"px;height:"+(ajaxContentH+17)+"px;' > </iframe>")
				}
				else{
					$("#TB_overlay").unbind();
					$("#TB_window").append("<iframe frameborder='0' hspace='0' src='"+urlNoQuery[0]+"' id='TB_iframeContent' name='TB_iframeContent"+Math.round(Math.random()*1e3)+"' onload='tb_showIframe()' style='width:"+(ajaxContentW+29)+"px;height:"+(ajaxContentH+17)+"px;'> </iframe>")
				}
			}
			else{
				if($("#TB_window").css("display")!="block"){
					if(i["modal"]!="true"){
						$("#TB_window").append("<div id='TB_title'><div id='TB_ajaxWindowTitle'>"+a+"</div><div id='TB_closeAjaxWindow'><a href='#' id='TB_closeWindowButton' style='color:#F00;font-weight:bold;'>close</a> or Esc Key</div></div><div id='TB_ajaxContent' style='width:"+ajaxContentW+"px;height:"+ajaxContentH+"px'></div>")
					}
					else{
						$("#TB_overlay").unbind();
						$("#TB_window").append("<div id='TB_ajaxContent' class='TB_modal' style='width:"+ajaxContentW+"px;height:"+ajaxContentH+"px;'></div>")
					}
				}
				else{
					$("#TB_ajaxContent")[0].style.width=ajaxContentW+"px";
					$("#TB_ajaxContent")[0].style.height=ajaxContentH+"px";
					$("#TB_ajaxContent")[0].scrollTop=0;
					$("#TB_ajaxWindowTitle").html(a)
				}
			}
			$("#TB_closeWindowButton").click(tb_remove);
			if(b.indexOf("TB_inline")!=-1){
				$("#TB_ajaxContent").append($("#"+i["inlineId"]).children());
				$("#TB_window").unload(function(){
					$("#"+i["inlineId"]).append($("#TB_ajaxContent").children())
				});
				tb_position();
				$("#TB_load").remove();
				$("#TB_window").css({display:"block"})
			}
			else if(b.indexOf("TB_iframe")!=-1){
				tb_position();
				if($.browser.safari){
					$("#TB_load").remove();
					$("#TB_window").css({display:"block"})
				}
			}
			else{
				$("#TB_ajaxContent").load(b+="&random="+(new Date).getTime(),function(){
					tb_position();
					$("#TB_load").remove();
					tb_init("#TB_ajaxContent a.thickbox");
					$("#TB_window").css({display:"block"})
				})
			}
		}
		if(!i["modal"]){
			document.onkeyup=function(a){
				if(a==null){
					keycode=event.keyCode
				}
				else{
					keycode=a.which
				}
				if(keycode==27){
					tb_remove()
				}
			}
		}
	}
	catch(j){}
}

function tb_init(a){
	$(a).click(function(){
		var a=this.title||this.name||null;
		var b=this.href||this.alt;
		var c=this.rel||false;
		tb_show(a,b,c);
		this.blur();
		return false
	})
}

var tb_pathToImage="img/loadingAnimation.gif";
$(document).ready(function(){
	tb_init("a.thickbox, area.thickbox, input.thickbox");
	imgLoader=new Image;
	imgLoader.src=tb_pathToImage
})
