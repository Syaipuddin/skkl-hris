<script type="text/javascript">
// Charles
$(document).ready(function(){

   
$(function() {
        $("#start_period").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });
		$("#end_period").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });
		
		var kotak_kiri = $("#kotak_kiri").html();
		var kotak_kanan = $("#kotak_kanan").html();
		
		$("#print").click(function(){
			$("#kotak_kanan").show();
			$("#kotak_kiri,#kotak_kanan").html("");
			$("#kotak_kiri").html(kotak_kanan);
			$("#kotak_kanan").html(kotak_kiri);
			var dataserialize = $("#id_search").serialize();
			$.ajax({
				type : 'POST',
				url : "<?php echo base_url(); ?>tagihan/ajax", 
				data : dataserialize
			}).done(function(msg){
					$("#div1").html(msg);
				});
		});
	});
});


</script>