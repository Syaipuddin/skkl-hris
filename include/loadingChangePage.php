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
	var box2;
    console.log('stesst');
    // $(document).ready(function() {
    // $(document).unload(function(event){
    window.addEventListener('beforeunload', function(event) {
        box2 = new ajaxLoader($("body"), {classOveride: 'blue-loader', bgColor: '#000', opacity: '0.5'});
    		$("body").css('overflowY', 'hidden');
        console.log('box2 : '+box2);
    });

    window.addEventListener('onload', function(event) {
        box2 = new ajaxLoader($("body"), {classOveride: 'blue-loader', bgColor: '#000', opacity: '0.5'});
        $("body").css('overflowY', 'hidden');
        console.log('box2 : '+box2);
    });

    window.addEventListener('load', function(event) {
        box2.remove();
    });
});
</script>