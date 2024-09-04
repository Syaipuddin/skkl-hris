<?php
      $pageTitle = 'Employee Identity Update';
      include "template/top5.php";
      include "language/Home_library_word.php";
  		include "language/medical_reimburst_constant.php";
      
      $ms_niktelp = odbc_prepare($conn, "SELECT * FROM ms_niktelp WHERE NIK = ?");
      odbc_execute($ms_niktelp, array($NIK));
      $nama = odbc_result($ms_niktelp, "Nama");


      $fce = saprfc_function_discover($rfc,"ZHRFM_GET0185");
          if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
          saprfc_import ($fce,"FI_KEYDATE",date("Ymd"));
          saprfc_import ($fce,"FI_PERNR",$NIK);
          saprfc_import ($fce,"FI_SUBTYPE","01");

          $rfc_rc = saprfc_call_and_receive ($fce);
          if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }

          $FE_ID = saprfc_export ($fce,"FE_ID");
          $FE_TAXID = saprfc_export ($fce, "FE_TAXID");
          $FE_TAXNAME = saprfc_export ($fce, "FE_TAXNAME");

          // GET FEMALE AND NIKAH
      		$fce2 = saprfc_function_discover($rfc,"ZHRFM_CV");
          if (! $fce2 ) { echo "Discovering interface of function module failed"; exit; }
          saprfc_import ($fce2,"FI_PERNR",$NIK);
          saprfc_import ($fce2,"FI_PERNR_DIAKSES",$NIK);
          saprfc_table_init ($fce2,"FI_CV");
          $rfc_rc = saprfc_call_and_receive ($fce2);
          if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce2)); else echo (saprfc_error($fce2)); exit; }
          $rows = saprfc_table_rows ($fce2,"FI_CV");

          if($rows)
					{
						$FI_CV = saprfc_table_read ($fce2,"FI_CV",1);
					}
      ?>
      <style type="text/css">
      	.container-npwp {
      			font-size: 14px;
				    display: flex;
				    flex-direction: column;
				    align-items: center; /* Center the container horizontally */
				    text-align: left; /* Text inside the container is left-aligned */
				}
				#body-container .row {
				    margin: 0 auto;
				    text-align: left; /* Text inside each row is left-aligned */
				}

				.title-container, .body-container {
				    max-width: 600px; /* Set a maximum width for the title and body sections */
				    margin: 0 auto; /* Center the divs horizontally */
				    text-align: justify-all; /* Left-align the text inside the divs */
				}
				
      </style>
      <div class="container-npwp">
      	<div class="row title-container">
      		<div class="span6">
      			<h2>Konfirmasi Pemadanan NIK-NPWP</h2>
      		</div>
					<div class="span12">
					    <img src="img/banner_home/new_djp_banner_1.png" width="450px" style="text-align: center;">
				    	<br>
				      <br>
					</div>
				</div>
				<div class="row body-container">
					<div class="span12">
				      	<div class="row">
				          <div class="span6" style="font-weight: normal;">Apakah anda sudah melakukan pemadanan data NIK-NPWP di Direktorat Jenderal Pajak (DJP) Online?</div>
				        </div>
				        <br>
				        <div class="row">
				        	<div class="span12">
							      <div class="radio span2">
							        <label title="sudah" id="question1" class="question"><input id="radio1" type="radio" name="optradio" value="1">Sudah</label> 
							      </div>
							      <div class="radio span2">
							        <label title="belum" id="question2" class="question"><input id="radio2" type="radio" name="optradio" value="0">Belum</label>
							      </div>
				          </div>
				        </div>
				        <br>
				        <div id="link" style="display: none;">
				        		<div class="row">
				        			<div class="span6" style="font-weight: normal;">Silahkan melakukan pemadanan NIK-NPWP di website DJP :<br>
											<a href="https://djponline.pajak.go.id">https://djponline.pajak.go.id</a>
				        			<br>
				        			<br>
				        			Selanjutnya lakukan konfirmasi Pemadanan NIK-NPWP di HR Portal dengan memilih "Sudah"
				        			<br>
				        			<br>
											Panduan lengkap pengisian dapat anda simak melalui link berikut :
											<a href="https://kognisi.mykg.id/learning#/detail_list_video_player/2302">https://kognisi.mykg.id/learning#/detail_list_video_player/2302</a>
											</div>
						        </div>
				        </div>

				        <form method="post" style="display: none;" action="home_update_nik_npwp_djp_process.php" enctype="multipart/form-data" name="form-identity" id="form-identity">
					      	<div class="row">
						          <div class="span6" style="font-weight: normal;">Terimakasih sudah melakukan konfirmasi pengisian pemadanan NIK-NPWP.
						          	<br><br>Sebagai keterangan tambahan, mohon untuk mengkonfirmasi atau melengkapi data-data berikut :</div>
						     	</div>
						     	<br>
					      	<div class="row" style="display: none;">
					          <div class="span2" style="font-weight: normal;">NIK</div>
					            <div class="span10">
					              <input type="text" name="nik" id="nik" value="<?=$NIK?>" style="width: 35%" class="required_fields" readonly>
					            </div>
					        </div>
					        <div class="row" style="display: none;">
					          <div class="span2" style="font-weight: normal;">Nama</div>
					            <div class="span10">
					              <input type="text" name="nama" id="nama" value="<?=$nama?>" style="width: 35%" class="required_fields" readonly>
					            </div>
					        </div>
					        <div class="row" style=" display: none;">
					          <div class="span2" style="font-weight: normal;">No. KTP</div>
					            <div class="span10">
					              <input type="text" name="old_ktp" placeholder="example: 32010XXXXXXXXXXX" style="width: 35%" id="old_ktp" value="<?=$FE_ID?>" readonly class="required_fields">
					            </div>
					        </div>
					        <br>
					        <!-- status milik pasangan hanya boleh karyawan yang perempuan dan menikah -->
					      	<div class="row">
					          <div class="span12" style="font-weight: normal;">NIK - NPWP milik siapakah yang anda gunakan?</div>
					        </div>
					        <br>
					        <div class="row">
					        	<div class="span12">
								      <div class="radio span2">
								        <label title="self" id="self" class="question2"><input id="self" type="radio" name="status_npwp" value="self">NPWP Sendiri</label>
								      </div>
								      <!-- status milik pasangan hanya boleh karyawan yang perempuan dan menikah -->
					        		<?php if ($FI_CV['JENISKELAMIN'] == 'Female' && $FI_CV['STATUSPERKAWINAN'] == 'Nikah'): ?>
								      <div class="radio span2">
								        <label title="spouse" id="spouse" class="question2"><input id="spouse" type="radio" name="status_npwp" value="spouse">NPWP Suami</label>
								      </div>
								      <?php endif ?>
					          </div>
					          <br>
					        	<br>
					        </div>
					        
					        <div id="form_npwp" style="display: none;">
					        	<div id="div_npwp" style="display: none;">
					        		<br>
						        	<div class="row">
							          <div class="span2" style="font-weight: normal;">Konfirmasi NIK - NPWP 16 Digit</div>
							            <div class="span10">
							              <input type="text" minlength="16" maxlength="16" name="npwp" placeholder="example: 32010XXXXXXXXXXX" style="width: 35%" id="npwp" value="" class="required_fields">
							              <span style="color: red; vertical-align: top; vertical-align: top;"> **</span>
							            </div>
							        </div>
							        <br>
							        <div class="row">
								        <div class="span12">
							        		<input type="checkbox" id="checkboxaddrSAMAKAN1" /> Samakan dengan No. KTP tersimpan
							        	</div>
							        </div>
							        <br>
							        <br>
					        	</div>
						        <div class="row" id="div_nama_pasangan" style="display: none;">
						          <div class="span2" style="font-weight: normal;">Nama Lengkap Pasangan</div>
						            <div class="span10">
						              <input type="text" name="nama_pasangan" id="nama_pasangan" style="width: 35%" class="required_fields">
						              <span style="color: red; vertical-align: top; vertical-align: top;"> *</span>
						            </div>
						        </div>
						        <br>
						        <div id="div_self_ktp" style="display: none;">
							        <div class="row">
							          <div class="span2" style="font-weight: normal;">Konfirmasi No. KTP Milik Sendiri</div>
							            <div class="span10">
							              <input type="text" minlength="16" maxlength="16" name="self_ktp" placeholder="example: 32010XXXXXXXXXXX" style="width: 35%" id="self_ktp" value="" class="required_fields">
							              <span style="color: red; vertical-align: top; vertical-align: top;"> **</span>
							            </div>
							        </div>
							        <br>
							        <div class="row">
							        	<div class="span12">
						        			<input type="checkbox" id="checkboxaddrSAMAKAN2" /> Samakan dengan No. KTP tersimpan
						        		</div>
						        	</div>
						        </div>
						        <br>
						        <div class="row" style="font-size: 12px;">
						          <div class="span12"><span style="color:red;">* Wajib diisi</span><br></div>
						          <br>
						          <div class="span12">							
													<span style="color:red;">
							          		** NPWP / No. KTP wajib berupa angka dengan panjang 16 karakter.
					                </span>
					              </div>
						        </div>
						        <br>
						        <div class="row">
						          <div class="span6">
						            <button class="btn button-submit btn-primary" style="text-align: right;" type="submit" value="Submit" id="btnSubmit">Submit</button>
						          </div>
						        </div>
					        </div>

					      </form>
				</div>

				<div id="dialog-form" title="Konfirmasi">
		            <fieldset>
		                <h3>Apakah anda yakin ingin submit data?</h3>
		                <br>Mohon pastikan data anda sudah yakin benar.
		                <input type="text" name="status_dialog" id="status_dialog" style="display: none;">
		            </fieldset>
		        </div>
      </div>
  <?php
    include "template/bottom5.php"; //Load tenplate penutup dan load javascript eksternal
  ?>
  <script src='js/multifile/jquery.form.js' type="text/javascript"></script>
	<script src='js/multifile/jquery.MetaData.js' type="text/javascript"></script>
	<script src='js/multifile/jquery.MultiFile.js' type="text/javascript"></script>
  	<script type="text/javascript" src="js/jquery.validate.min.js"></script>
  	<script type="text/javascript">

  		jQuery.validator.addMethod("npwp",function(value,element){
			    return this.optional(element) || /^\d*[0-9+](|.\d*[0-9]|,\d*[0-9])?$/i.test(value);
			    },"NPWP hanya boleh menggunakan angka");

  		jQuery.validator.addMethod("identic_validation",function(value,element){
			    return this.optional(element) || /^(?!((\d)\2{5,})\d+$)\d+$/.test(value);
			    },"NPWP atau No KTP tidak boleh 9999999999999999 atau dengan angka identik lainnya");

  		jQuery.validator.addMethod("validation123",function(value,element){
			    return this.optional(element) || /^(?!1234567890123456$|1234567890987654$|0123456789012345$|0123456789098765$|0123456789123456$|0123456789987654$|9876543210123456|9876543210987654|0987654321234567|0987654321012345)\d{16}$/.test(value);
			    },"NPWP atau No KTP tidak boleh 1234567890 atau 0987654321 atau sejenisnya");

  		jQuery.validator.addMethod("self_ktp",function(value,element){
			    return this.optional(element) || /^\d*[0-9+](|.\d*[0-9]|,\d*[0-9])?$/i.test(value);
			    },"No. KTP hanya boleh menggunakan angka");

  		jQuery.validator.addMethod("notEqual", function(value, element, param) {
				  return this.optional(element) || value != $(param).val();
				}, "KTP Sendiri tidak boleh sama dengan NIK - NPWP Pasangan");

  		$("#checkboxaddrSAMAKAN1").click(function(){
				if ($(this).prop("checked") == true) {
			    $("#npwp").val($("#old_ktp").val());
				}
				else{
					$("#npwp").val('');
				}
			});

			$("#checkboxaddrSAMAKAN2").click(function(){
				if ($(this).prop("checked") == true) {
			    $("#self_ktp").val($("#old_ktp").val());
				}
				else{
					$("#self_ktp").val('');
				}
			});

  		$('input[type=radio][name=optradio]').change(function() {
  			var radio = $('input[name=optradio]:checked').val();
  				if (radio == 0) {
  					$('#link').show();
  					$('#form-identity').hide();
  				}
  				else{
  					$('#link').hide();
  					$('#form-identity').show();
  				}
  		});

  		$('input[type=radio][name=status_npwp]').change(function() {
  			var status_npwp = $('input[name=status_npwp]:checked').val();
  				if (status_npwp == 'spouse') {
  					$('#form_npwp').show();
  					$('#div_npwp').show();
  					$('#div_nama_pasangan').show();
  					$('#div_self_ktp').show();
  				}
  				else if(status_npwp == "self"){
  					$('#form_npwp').show();
  					$('#div_npwp').show();
  					$('#div_nama_pasangan').hide();
  					$('#div_self_ktp').hide();
  				}
  				else{
  					$('#form_npwp').hide();
  					$('#div_npwp').hide();
  					$('#div_nama_pasangan').hide();
  					$('#div_self_ktp').hide();
  				}
  		});

  		$(document).ready(function() 
		{ 
			jumlah = 0;
			$('#ui_file_upl').MultiFile({
                  max_size: 1024,
                  accept: 'jpg|jpeg',
                    afterFileSelect: function(element, value, master_element){
                      jumlah = jumlah+1;
                      	$('#file').val(value);
                    },
                    afterFileRemove: function(element, value, master_element){
                      jumlah = jumlah-1;
                      if (jumlah<1) {
                        $('#file').val('');
                      }
                    }
                  });

				$("#form-identity").validate(
				{
					submitHandler: function(form) {
				    	if ($('#status_dialog').val()==1) {
				    		return true;
				    	}
				    	else{
				    		$('#dialog-form').dialog('open');
				    		return false;
				    	}
				    },
					rules: 	{
								nik: {required: true,number:true},
								nama: {required: true},
								nama_pasangan: {required: true},
								status_npwp: {required: true},
								npwp: {required: true, npwp: true, identic_validation: true, validation123: true},
								self_ktp: {required: true, notEqual: "#npwp", self_ktp: true, identic_validation: true, validation123: true},
					}
				});

			$( "#dialog-form" ).dialog({
		      autoOpen: false,
		      height: 200,
		      width: 450,
		      modal: true,
		      buttons: {
		      	Tidak: function() {
		          $(this).dialog( "close" );
		        },
		        Ya: function(){
		        	$('#form-identity').validate();
		        	$('#dialog-form').dialog('close');
		        	$('#status_dialog').val(1);
		        	$('#form-identity').submit();
		        }
		      }
		    })
		});
  	</script>