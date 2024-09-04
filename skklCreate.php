<?php
	$pageTitle="SKKL";
	include "template/top2.php"; //Load template pembuka dan load css eksternal
?>
<h2>Create SKKL</h2><br>
<form id="CreateSKKL" name="CreateSKKL" method="post" action="skklCreate_nextstep.php?task=<?php echo 'step1' ?>">
	<div class="row">
	<div class="span1">NIK:</div>
	<div class="span2"><?php echo $NIK;?></div>
                   <div class="span1">Nama:</div>
                <div class="span2">
				   <?php 
				$getnama = odbc_exec($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = '$NIK'");
                echo $nama=odbc_result($getnama, "Nama");	
				?></div>
                </div>
                	<div class="row">
                <div class="span2">Assign to:</div><div class="span3">&nbsp;</div></div>
                
              	<div class="row">
	<div class="span2">Assign number:</div><div class="span3">
<input name="uiTxtAssignNum" type="text" id="uiTxtAssignNum" size="3" maxlength="3"  onkeypress="return numbersonly(event)"  class="required"/>
                </div>
                </div>
              
                
                <div id="niklembur">
				</div>
                <div class="row">
				<div class="span2">Superior to Approve:</div>
                <div class="span3">&nbsp;</div>
                </div>
                
				<div class="row">
				<div class="span2">
                Superior number:</div>
                <div class="span3">
               
                 <?php
					$jumlah_superior=2;
					$select_superior='';

					$i=1;
					while($i <= $jumlah_superior) {
					 $select_superior.='<option value="'.$i.'">'.$i.'</option>';
						$i++;
					}
				?>
                <select name="uiSlcSpv" id="uiSlcSpv"  >
               <option value="0" selected="selected" ></option>
					<?php echo $select_superior;?>
					</select>
                </div>
                </div>
                
                
                <div id="superior">
				</div>
                
                <div class="row">
				<div class="span3">               
                <a href="skklPopUpSearchPimpinanKG.php?superior=JAKOB+OETAMA&keepThis=true&TB_iframe=true&height=430&width=750" title="Search Superior" class="thickbox" >Search Personnel Number</a>
                </div><br>
                </div>
                <div class="row">
                <div class="span2"></div><div class="span3">    
                <button class="btn btn-primary"  type="Submit" value="Next">Next</button></div>
                </div>
                  </form>



<?php
	include "template/bottom2.php"; //Load tenplate penutup dan load javascript eksternal
	include "include/disableThickboxRefresh.php"; 
?>

<script type="text/javascript">
$(function() {

	$("#CreateSKKL").submit(function( event ) {
		var total_nik_lembur = $("#uiTxtAssignNum").val();
		if(total_nik_lembur > 10)
		{
			alert('Maximum pengajuan 10 nik untuk 1 transaksi');
			return false;
		}else{
			return true;
		}
  		event.preventDefault();	
	});

	$("#uiTxtAssignNum").keyup(function(event, data) {
		$("#niklembur").html('<p><img src="css/spinner_bar.gif" width="16" height="16"  /></p>');
		$("#niklembur").load('skklRequestPilihNIK.php?a='+$(this).val());
	});

	$("#uiSlcSpv").change(function() {
		$("#superior").html('<p><img src="css/spinner_bar.gif" width="16" height="16"  /></p>');
		$("#superior").load('skklRequestPilihSuperior.php?j='+$(this).val());
	});


	$("#CreateSKKL").validate(
		{
			rules: 	{
						uiTxtAssignNum:{required: true,number: true, max:10},
						nik: {required: true,number: true}
					}
		});

});

</script>
