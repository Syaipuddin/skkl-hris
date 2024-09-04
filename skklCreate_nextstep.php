<?php
	$pageTitle="SKKL";
	include "template/top2.php"; //Load template pembuka dan load css eksternal
?>
<div id="page-wrapper">
<?php
	//set variable
	$assNum = $_REQUEST['uiTxtAssignNum'];
	$txtTotalNumSpv = $_REQUEST['uiSlcSpv'];
	if(isset($_POST['uiTxtSpvGrp']))
	{
		$spv1 = $_POST['uiTxtSpvGrp'];
		$totalspv = count($spv1);
	}
?>
<form id="StepSkklOne" name="StepSkklOne" method="POST" autocomplete="off" action="skklCreateProses.php?task=<?php echo md5('addSKKL') ?>" onsubmit="return validateForm(this)">
                
<table border="0" cellpadding="0" cellspacing="1">
            <caption>
            <h1>Create SKKL</h1></caption>
            <tr>
                <td width="111">NIK</td>
                <td width="3">:</td>
                <td colspan="4">
                <?php echo $NIK ?>          
              </td>
            </tr>
            <tr>
              <td>Name</td>
              <td>:</td>
              <td colspan="4">
               <?php 
			// $getnama = odbc_exec($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = '$NIK'");

			odbc_execute($getnama = odbc_prepare($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = ?"), array($NIK));

   		 	echo $nama=odbc_result($getnama, "Nama");
	
				?>
              </td>
            </tr>
            <tr>
              <td>Assign To</td>
              <td>:</td>
              <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="6">
              
              <table width="100%" border="0">
                <tr>
                  <td width="30%">Assignment Number</td>
                  <td width="2%">:</td>
                  <td width="68%" colspan="4">
                  	<input name="uiTxtAssignNum" type="text" id="uiTxtAssignNum" size="3" maxlength="3" value="<?php echo $assNum ?>" readonly="readonly" />
                  
                  <!-- <button>Delete</button> -->
                  
                  </td>
                </tr>
                
                
                
                
                <tr>
                  <td colspan="6">
                  <table width="100%" border="0" id="tbl_assignment">
                  
                  <!-- looping array karyawan yang lembur -->
                  <?php
				  	
					$assNum = $_REQUEST['uiTxtAssignNum'];
					for($i=1;$i<=$assNum;$i++)
					{
						$nikLembur = $_POST['nik'.$i];
						$supervisor = $_POST['su'.$i];

	

						if($NIK == $nikLembur && $txtTotalNumSpv =='0')
						{
							//alert tanggal cuti
							echo "<script>alert('superior can not empty.');javascript:history.go(-1);</script>";
						}
						
						if($nikLembur == $supervisor)
						{
							echo "<script>alert('superior can not same with nik assignment.');javascript:history.go(-1);</script>";
						}
						//validasi nik karyawan lembur yang yang tidak sama dengan group 1 maka dia bisa save
						//call SAP Data for get group nik berhak lembur
						$fce = saprfc_function_discover($rfc,"ZHRFM_GETENTSTRUCTURE");
						if (! $fce )
						{
							echo "System Error. Please Try Again Later."; exit;
						}
							saprfc_import ($fce,"FI_PERNR",$nikLembur);
							saprfc_table_init ($fce,"FI_ENT");
							$rfc_rc = saprfc_call_and_receive ($fce);
							if ($rfc_rc != SAPRFC_OK)
							{
									if ($rfc == SAPRFC_EXCEPTION )
											echo "System Error. Please Try Again Later.";
									else
											echo "System Error. Please Try Again Later."; exit;
							}
							$rows = saprfc_table_rows($fce,"FI_ENT");
							if ($rows == 0)
							{
								$FI_ENT = '';
							}
							else
							{
								$FI_ENT = saprfc_table_read($fce,"FI_ENT",1);
							}
							
							//validasi group karyawan
							if ($FI_ENT['PERSKNAME'] != 'Group 1')
							{
								echo	"<script>alert('Nik= $nikLembur ini tidak diijinkan untuk lembur.');javascript:history.go(-1);</script>";
							}
							else
							{
								//next step
							}
						
						
						//  $getnama = odbc_exec($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = '$nikLembur'");

						 odbc_execute($getnama = odbc_prepare($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = ?"), array($nikLembur));

						 $nama_lembur=odbc_result($getnama, "Nama");
					
						?>
               		<tr class="row_to_clone">
                      <td>NIK</td>
                      <td>:</td>
                      <td><input name="uiTxtNIK2Grp[]" type="text" id="uiTxtNIK2Grp" onkeypress="return numbersonly(event)" size="6" maxlength="6" value='<?php echo $nikLembur ?>' readonly="readonly" /></td>
                      <td>Nama</td>
                      <td>:</td>
                      <td><input type="text" name="uiTxtNameGrp[]" id="uiTxtNameGrp" 
					  value='<?php echo $nama_lembur ?>' readonly="readonly" /></td>
                 	</tr>
					<?php
                    }
				  ?>
				 
                  </table></td>
                </tr>
                <tr>
                  <td>Date</td>
                  <td>:</td>
                  <td colspan="4"><input type="text" name="uiTxtSKKLDate" id="uiTxtSKKLDate"  />
                  </td>
                </tr>
                <tr>
                  <td>Time</td>
                  <td>:</td>
                  <td colspan="4"><select class="span1-half" name="uiDdlmulaiJam" id="uiDdlmulaiJam" onchange="checkTime()">
                    <option value=""></option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
		    <!-- <option value="24">24</option> -->
                    <option value="00">00</option>
                  </select>
                    :
                    <select name="uiDdlmulaiMenit" class="span1-half" id="uiDdlmulaiMenit" onchange="checkTime()">
                      <option value=""></option>
                      <option value="00">00</option>
                      <option value="15">15</option>
                      <option value="30">30</option>
                      <option value="45">45</option>
                  </select> 
                    To
                    <select name="uiDdlselesaiJam" class="span1-half" id="uiDdlselesaiJam" onchange="checkTime()">
					  <option value=""></option>
                      <option value="01">01</option>
                      <option value="02">02</option>
                      <option value="03">03</option>
                      <option value="04">04</option>
                      <option value="05">05</option>
                      <option value="06">06</option>
                      <option value="07">07</option>
                      <option value="08">08</option>
                      <option value="09">09</option>
                      <option value="10">10</option>
                      <option value="11">11</option>
                      <option value="12">12</option>
                      <option value="13">13</option>
                      <option value="14">14</option>
                      <option value="15">15</option>
                      <option value="16">16</option>
                      <option value="17">17</option>
                      <option value="18">18</option>
                      <option value="19">19</option>
                      <option value="20">20</option>
                      <option value="21">21</option>
                      <option value="22">22</option>
                      <option value="23">23</option>
		      <!-- <option value="24">24</option> -->
                      <option value="00">00</option>
                    </select>
                    :
                    <select name="uiDdlselesaiMenit" class="span1-half" id="uiDdlselesaiMenit" onchange="checkTime()">
                      <option value=""></option>
                      <option value="00">00</option>
                      <option value="15">15</option>
                      <option value="30">30</option>
                      <option value="45">45</option>
                    </select></td>
                </tr>
                <tr>
                  <td>Agenda</td>
                  <td>:</td>
                  <td colspan="4"><textarea name="uiTxtAgenda" id="uiTxtAgenda" cols="45" rows="3"><?php //echo $agenda ?></textarea></td>
                </tr>
                </table>
              
              </td>
            </tr>
            <tr>
              <td valign="top" width="30%">Superior to Approve</td>
              <td valign="top">&nbsp;</td>
              <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
              <td valign="top">Superior number</td>
              <td valign="top">:</td>
              <td colspan="4"><input name="uiTxtSpvNum" value="<?php echo $txtTotalNumSpv ?>" type="text" id="uiTxtSpvNum" size="3" maxlength="3" readonly="readonly" /></td>
            </tr>
            <tr>
              <td colspan="6"><table width="100%" border="0">
                <tr>
                  <td width="304%" colspan="6"><table width="100%" border="0" cellpadding="1" cellspacing="1">
                    <!-- looping superior -->
                   
                   
                    <?php
						
					$txtTotalNumSpv = $_POST['uiSlcSpv'];
					
						
						for($b=1;$b<=$txtTotalNumSpv;$b++)
						{
							$supervisor = $_POST['su'.$b];
							
							//validate nik login dengan supervisor
							if($NIK == $supervisor)
							{
								echo "<script>alert('You do not have permission to fill textbox with your own Personnel Number $supervisor');javascript:history.go(-1);</script>";
							}
							
							
							
							//jika $superior terisi dan $superior2, superior3 tidak terisi maka lakukan ini
							if($supervisor!='')
							{
								$fce = saprfc_function_discover($rfc,"ZHRFM_GETAUT");
								if (! $fce ) 
								{ 
									echo "System Error. Please Try Again Later."; exit;
								}
								else
								{
									saprfc_import ($fce,"FI_PERNR",$supervisor);
									$rfc_rc = saprfc_call_and_receive ($fce);
									$TEMPAT = saprfc_export ($fce,"TEMPAT");
									$TGLLAHIR = saprfc_export ($fce,"TGLLAHIR");
									if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) ; else $errorMessage = "An Error Occured, Please Try Again Later."; exit; }
									$rown = saprfc_table_rows ($fce,"FI_CV");
									saprfc_function_free($fce);
									if($TEMPAT=='' || $TGLLAHIR =='')
									{
										echo "<script>alert('Nik tidak terdaftar');javascript:history.go(-1);</script>";
									}
								}
							}
                    ?>
                    <tr>
                      <td width="23%">Superior</td>
                      <td width="2%">:</td>
                      <td width="54%"> &nbsp;<input name="uiTxtSpvGrp[]" type="text" onkeypress="return numbersonly(event)" id="uiTxtSpv1" size="6" maxlength="6" value="<?php echo $supervisor ?>" readonly="readonly" /></td>
                      <td width="7%">Nama</td>
                      <td width="7%">:</td>
                      <td width="7%">
                        
                        <?php
					// $getnama = odbc_exec($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = '$supervisor'");

					odbc_execute($getnama = odbc_prepare($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = ?"), array($supervisor));

    					$nama_supervisor=odbc_result($getnama, "Nama");
					  	
			?>

                         &nbsp;<input type="text" name="uiTxtSpvName[]" id="uiTxtSpvName" value="<?php echo $nama_supervisor; ?>" readonly="readonly" /></td>
                    </tr>
                    <?php
						}
					?>
                    
                    </table>
                    </td>
                  </tr>
                </table></td>
            </tr>
          </table>
<br></center>
                <center> 
             <button class="btn btn-primary"  type="submit"  value="Submit" id="btnSubmit" >Save</button>
             </center>
                  </form>
                  </div>
                  
<div id='loading'>
<h3>Loading page...</h3>
    <img src="img/loadingAnimation.gif" />
  <small><br>...please wait data on processing.</small>
</div>   

<?php
	include "template/bottom2.php"; //Load tenplate penutup dan load javascript eksternal
?>

<!-- Javascript dan jquery script dimulai dari sini-->
<script  type="text/javascript">
	$(document).ready(function(){
		$( "#uiTxtSKKLDate" ).datepicker({
			dateFormat: 'yy-mm-dd'
		});
	});
	
	$(function() {
	$("#StepSkklOne").validate(
		{
			rules: 	{
						uiTxtSKKLDate:{required: true,date: true},
						uiTxtAgenda:{required: true}
					}
		});

});

</script>


<script type="text/javascript">
			// validates that the field value string has one or more characters in it
			function isNotEmpty(elem) {
			  var str = elem.value;
				var re = /.+/;
				if(!str.match(re)) {
					alert("Please fill in the required field.");
					setTimeout("focusElement('" + elem.form.name + "', '" + elem.name + "')", 0);
					return false;
				} else {
					return true;
				}
			}

			// validate that the user made a selection other than default
			function isChosen(select) {
				if (select.selectedIndex == 0) {
					alert("Please make a choice from time list.");
					return false;
				} else {
					return true;
				}
			}
			
			
			function focusElement(formName, elemName) {
				var elem = document.forms[formName].elements[elemName];
				elem.focus();
				elem.select();
			}
			
			// batch validation router
			function validateForm(form) {  

						if (isChosen(form.uiDdlmulaiJam)) 
						{
							if (isChosen(form.uiDdlmulaiMenit)) 
							{
								if (isChosen(form.uiDdlselesaiJam)) 
								{
									if (isChosen(form.uiDdlselesaiMenit)) 
									{
										if (isNotEmpty(form.uiTxtAgenda)) 
										{
											return true;
										}
									}
								}
							}
						}            
				return false;
}
</script>
<script type="text/javascript">
	function numbersonly(e)
	{
		var unicode=e.charCode? e.charCode : e.keyCode
		if (unicode!=8)
		{ //if the key isn't the backspace key (which we should allow)
		if (unicode<48||unicode>57) //if not a number
		return false //disable key press
		}
	}
</script>
    	<script type="text/javascript">
	
	window.onload = function() {
		$('#StepSkklOne').submit(function(e)
							{ // <<< This selector needs to point to your form.
							mydropdown1 = $('#uiDdlmulaiJam option:selected');
							mydropdown2 = $('#uiDdlmulaiMenit option:selected');
							mydropdown3 = $('#uiDdlselesaiJam option:selected');
							mydropdown4 = $('#uiDdlselesaiMenit option:selected');
							if (mydropdown1.length == 0 || $(mydropdown1).val() == "" ||
								mydropdown2.length == 0 || $(mydropdown2).val() == "" ||
								mydropdown3.length == 0 || $(mydropdown3).val() == "" ||
								mydropdown4.length == 0 || $(mydropdown4).val() == "") {
								alert("Please select date");
								e.preventDefault();
								return false;
							}
							else
							{
								document.getElementById('page-wrapper').style.visibility='hidden';
		    					$('#loading').show();
							    return true;
							}
						});
						
	}
	</script>
