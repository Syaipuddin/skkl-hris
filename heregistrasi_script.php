<?php
// var_dump('huhu: ',$GLOBALS['heregistrasi_popup']);
if ($GLOBALS['heregistrasi_popup']) {
	$heregistrasi_popup = $GLOBALS['heregistrasi_popup'];

?>
	<!-- remodal buat survey dobloo -->
	<script src="js/remodal/remodal.js"></script>
	<script>
		$(document).ready(function(){
			// console.log($('[data-remodal-id=modal_survey_dobloo]').length);
			if ($('[data-remodal-id=modal_heregistrasi]').length != 0) {
				var inst_heregistrasi = $('[data-remodal-id=modal_heregistrasi]').remodal();
			}

			if(inst_heregistrasi && typeof inst_heregistrasi != 'undefined' && typeof inst_heregistrasi != null){
				// script utk countdown timer
				/*var timeoutDuration = 180000; // Set the timeout duration in milliseconds
		        var startTime = Date.now();
		        var timerElement = document.getElementById('time_interval');*/

		        /*function updateTimer() {
		            var elapsedTime = Date.now() - startTime;
		            var timeRemaining = Math.max(0, timeoutDuration - elapsedTime);
		            var secondsRemaining = Math.ceil(timeRemaining / 1000);
		            timerElement.textContent = secondsRemaining + ' seconds';

		            if (timeRemaining > 0) {
		                setTimeout(updateTimer, 1000);
		            }
		            else{
		            	window.location.reload();
		            }
		        }*/

			    /*$(document).on('closing', '.remodal', function () {
				    $.ajax({ url: 'survey_dobloo_popup.php?skipValidation=1'});
				});*/

				/*$(document).on('opening', '.remodal', function (e) {
					if (e.currentTarget.attributes[0].value == 'modal_survey_dobloo') {
						updateTimer();
					}
				});*/

			    var flag = "<?php echo $heregistrasi_popup['flag']; ?>";
			    /*var path = "<?php echo $survey_dobloo['path']; ?>";*/

			    // sementara skip_flag dipake buat spy popup ga muncul
			    /*var skip_flag = "<?php echo $survey_dobloo['skip_flag']; ?>";*/

			    /*var host_path = "<?php echo $survey_dobloo['host_path']; ?>";*/

			    // if (jml_skip == 3) {
			    	
			    // }
			    /*console.log('flag dobloo:',flag);
			    console.log(skip_flag);*/
			    // sementara skip_flag dipake buat spy popup ga muncul
			    // if((flag === "0" || flag == 2) && skip_flag != 3){
			    console.log('masuk sini ga: ',flag);
			    if(flag === "0"){
			    	// console.log('test_masuk '+skip_flag);
			    	inst_heregistrasi.settings = {
			            closeOnCancel: false,
			            closeOnEscape: false,
			            closeOnOutsideClick: false
			        }

			    	/**
					 * Opens the modal window
					 */
					inst_heregistrasi.open();
			    }
			    else if (flag === "2"){
			    	// klo flag 2 -> berarti popupnya bs diclose (tdk wajib)
			    	// untuk klo family, education, training, work exp blm, tp flag lain udh
			    	inst_heregistrasi.settings = {
			            closeOnCancel: true,
			            closeOnEscape: true,
			            closeOnOutsideClick: true
			        }
			        /**
					 * Opens the modal window
					 */
					inst_heregistrasi.open();
			    }
			}
		});
	</script>
<?php } ?>