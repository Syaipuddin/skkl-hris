$(function(){
	$(".fg-button").hover(function(){
		$(this).removeClass("ui-state-default").addClass("ui-state-focus")
	},function(){
		$(this).removeClass("ui-state-focus").addClass("ui-state-default")
	});

	$("#hierarchybreadcrumb").fgmenu({
		content:$("#hierarchybreadcrumb").next().html(),backLink:false,width:250,maxHeight:350,positionOpts:{posX:"left",posY:"bottom",offsetX:0,offsetY:0,directionH:"right",directionV:"down",detectH:false,detectV:false,linkToFront:false}});

});