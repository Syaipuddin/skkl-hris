<?php
$jumlah_nik = $_GET['a'];
$input_nik='';

$i=1;
while($i <= $jumlah_nik) {
 $input_nik.='	
 				<script type="text/javascript">
							function numbersonly(e)
							{
								var unicode=e.charCode? e.charCode : e.keyCode
								if (unicode!=8)
								{ 
								if (unicode<48||unicode>57) //if not a number
								return false //disable key press
								}
							}
						</script>
	 			<table>
						<div class="row">
						<div class="span2">NIK - '.$i.'  :</div>
						<div class="span3"><input type="text" data-num="'.$i.'" maxlength="6" minlength="6" onkeypress="return numbersonly(event)" class="required nik"   name="nik'.$i.'" id="nik'.$i.'" onKeyUp="javascript:checkNumber(skklReqForm1.su'.$i.');"></div>
						<div class="span6"><input type="text" readonly="readonly" id="nama_'.$i.'"></div>
						</div>				
					</table>';
	$i++;
}
?>



</head>

<body>
<input name="x" type="text" value="x" style="visibility:hidden" /><br />
<?php echo $input_nik;?><br/>

</body>
</html>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.nik').change(function(event) {
			/* Act on the event */
			var num = $(this).data('num');
			var nik = $(this).val();
			if (nik.length == 6) {
				$.ajax({
					url: 'Sub_ajax1.php',
					type: 'GET',
					data: {nik: nik},
				})
				.done(function(respond) {
					$('#nama_'+num).val(respond);
					console.log("success");
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
				
			};
			
		});
	});
</script>