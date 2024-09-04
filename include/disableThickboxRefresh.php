<script>
function tb_remove(){
    $("#TB_imageOff").unbind("click");$("#TB_closeWindowButton").unbind("click");$("#TB_window").fadeOut("fast",function(){$("#TB_window,#TB_overlay,#TB_HideSelect").trigger("unload").unbind().remove()});$("#TB_load").remove();if(typeof document.body.style.maxHeight=="undefined"){$("body","html").css({height:"auto",width:"auto"});$("html").css("overflow","")}document.onkeydown="";document.onkeyup="";return false
}
</script>