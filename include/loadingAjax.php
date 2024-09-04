<style>
.ajax_loader {background: url("js/ajax-loader/spinner_squares_circle.gif") no-repeat center center transparent;width:100%;height:100%;}
.blue-loader .ajax_loader {background: url("js/ajax-loader/ajax-loader_blue.gif") no-repeat center center transparent;}

body {
  position:relative;
}
</style>
<script id="loader" src="js/ajax-loader/script.js" type="text/javascript"></script>
<script>
$(function(){
	var box1;                

    $(document).ajaxStart(function() {
        box1 = new ajaxLoader($("body"), {classOveride: 'blue-loader', bgColor: '#000', opacity: '0.5'});
    		$("body").css('overflowY', 'hidden');
    }).ajaxStop(function(){
        box1.remove();
        $("body").css('overflowY', 'scroll');
    });  
});
</script>