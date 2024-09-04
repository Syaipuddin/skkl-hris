<?php
$jumlah_superior = $_GET['j'];
$input_superior='';

$i=1;
while($i <= $jumlah_superior) {
 $input_superior.='	
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
						<div class="span2">Superior - '.$i.'  :  </div>
						<div class="span3"><input type="text" data-num="'.$i.'" maxlength="6" minlength="6" onkeypress="return numbersonly(event)"  class="required nik_su"  name="su'.$i.'" id="su'.$i.'" onKeyUp="javascript:checkNumber(skklReqForm1.su'.$i.');"></div>
						<div class="span6"><input type="text" readonly="readonly" id="nama_su_'.$i.'"></div>
						</div>			
					</table>';
	$i++;
}
?>

</head>

<body>
<input name="x" type="text" value="x" style="visibility:hidden" /><br />
<?php echo $input_superior;?><br/>
               
</body>
</html>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.nik_su').change(function(event) {
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
					$('#nama_su_'+num).val(respond);
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