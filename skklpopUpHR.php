<?php
	$pageTitle="SKKL";
	include "template/top3.php";
	include "include/date_lib.php"; 
	$task	=	$_REQUEST['task'];
	$task_user = '';
			
	//for edit
	if ($task == md5('EntrySKKLHR'))
	{
		$task_user = 'EntrySKKLHR';
		$id = $_REQUEST['SKKLid'];
		// $SKKLQRY	= odbc_exec($conn,"select * from tb_SKKLTransaction where SKKLID='$id'");

		odbc_execute($SKKLQRY = odbc_prepare($conn,"select * from tb_SKKLTransaction where SKKLID=?"),array($id));

		//get total data nik lembur
		$agenda = odbc_result($SKKLQRY,'Agenda');
		$beginTime = odbc_result($SKKLQRY,'BeginDate');
		$endTime = odbc_result($SKKLQRY,'EndDate');
		$assignDate= odbc_result($SKKLQRY,'AssignmentDate');
		$superior = odbc_result($SKKLQRY,'SuperiorNIK');
		$namaSuperior = odbc_result($SKKLQRY,'NamaSuperior');
		$firstApprove =odbc_result($SKKLQRY,14);
		$namaFirstApprove =odbc_result($SKKLQRY,15);
		$secondApprove =odbc_result($SKKLQRY,16);
		$namaSecondApprove =odbc_result($SKKLQRY,17);
	}
?>
<h2>SKKL HR</h2><br />
<div id="poppage-wrap">
    <div class="well span9">
            <form autocomplete="off" name="confSKKL" id="confSKKL" action="skklpopUpHRProses.php?id=<?php echo $id ?>&task=<?php echo md5($task_user) ?>" method="POST" onsubmit="return validateForm(this)">
        <div class="row">
    		<div class="span2">NIK :</div>
    		<div class="span2">
                    <?php
                        echo odbc_result($SKKLQRY,"NIK");
                    ?>
                    </div>
			</div>
        <div class="row">
    		<div class="span2">Name :</div>
    		<div class="span3">
					<?php 
                      echo odbc_result($SKKLQRY,"Nama");					
                    
                    ?>
			</div>
		</div>
        <div class="row">
    		<div class="span2">SKKL</div>
    		<div class="span2">&nbsp;</div>
                </div>
			<div class="row">
                <div class="span1">
                  <table class="table table-striped table-bordered table-condensed">                
			<div class="row">
                <div class="span5">Superior :</div>
                </div>
				<div class="row">
                <div class="span8"><?php
                        
                        //jika superior ada, firstsuperior tidak ada, secondsuperior tidak ada muncul ini
                        
                        if($firstApprove ==NULL && $secondApprove == NULL)
                        {
                            echo $superior.'-'.$namaSuperior;
                        }
                        elseif($firstApprove !=NULL && $secondApprove == NULL)
                        {
                                //jika superior ada, firstsuperior ada, secondsuperior tidak ada muncul ini
                                
                                echo $superior.'-'.$namaSuperior.'<br>'.$firstApprove.'-'.$namaFirstApprove;
                        }
                        elseif($firstApprove !=NULL && $secondApprove != NULL)
                        {
                                //jika superior ada, firstsuperior ada, secondsuperior ada muncul ini
                                
                                echo $superior.'-'.$namaSuperior.'<br>'.$firstApprove.'-'.$namaFirstApprove.
                                '<br>'.$secondApprove.'-'.$namaSecondApprove;
                        }
                         
                      ?></div>
                    </div>
                  </table></div>
                </div>
				<div class="row">
                <div class="span2">Date :</div>
                <div class="span5"><?php echo convertDateAdis($assignDate) ?></div>
                </div>
                <div class="row">
                <div class="span2">Time :</div>
                <div class="span5"><?php echo $beginTime ?>:<?php echo $endTime ?></div>
                </div>
				<div class="row">
                <div class="span2">Agenda :</div>
                <div class="span5"><?php echo $agenda ?></div>
                </div>
               	<div class="row">
                <div class="span2">Time Event :</div>
                <div class="span5">
                  <?php
                  
                    //variable time
                    $timeEvent= substr($assignDate,0,4).substr($assignDate,5,2).substr($assignDate,8,2);
                    
                    $fce = saprfc_function_discover($rfc,"ZHRFM_LIST_TIME");
                    if (! $fce )
                    {
                        echo "System Error. Please Try Again Later."; exit;
                    }
                    saprfc_import ($fce,"FI_AKHIR",$timeEvent);
                    saprfc_import ($fce,"FI_AWAL",$timeEvent);
                    saprfc_import ($fce,"FI_PERNR",odbc_result($SKKLQRY,"NIK"));
                    saprfc_table_init ($fce,"FI_ABSEN");
                    $rfc_rc = saprfc_call_and_receive ($fce);
                    if ($rfc_rc != SAPRFC_OK)
                    {
                        if ($rfc == SAPRFC_EXCEPTION )
                            echo "System Error. Please Try Again Later.";
                        else
                            echo "System Error. Please Try Again Later."; exit;
                    }
                    $FI_RTEXT = saprfc_export ($fce,"FI_RTEXT");
                    $rows = saprfc_table_rows ($fce,"FI_ABSEN");
                    $FI_ABSEN = saprfc_table_read ($fce,"FI_ABSEN",$rows);
                    
                    echo $FI_ABSEN['JAMMASUK'].'&nbsp;To&nbsp;'.$FI_ABSEN['JAMPULANG']; 
                    
                    ?>
                    </div>
                </div>
                        <div class="row">
                <div class="span2">HR Revision Number :</div>
                <div class="span5">
                  <input name="uiTxtRevNum" type="text" onBlur="checkNumeric(this,-5,5000,',','-');" id="uiTxtRevNum" size="5" maxlength="5" class="required span1" /></div>
                </div>
            
            <center>
			<button class="btn btn-primary thickbox" type="Submit" value="Submit" />Submit</button></center>
          </form>
    </div>
</div>

<div id="poploading">
	  <h3>Loading page...</h3>
        <img src="img/loadingAnimation.gif" alt="loader">
        <small><br>...please wait data on processing.</small>
</div>

            
<?php
  include "template/bottom3.php"
?>


<SCRIPT LANGUAGE="JavaScript">
$('#confSKKL').submit(function(e)
{ 
	if(document.getElementById("uiTxtRevNum").value == "")
	{
		alert("Please fill Revision Number");
		e.preventDefault();
		return false;
	}
	else
	{
		document.getElementById('poploading').style.display='block';
		document.getElementById('poppage-wrap').style.visibility='hidden'
		return true;
	}
});
				
$(document).ready(

  function() 
  { 
    $("#confSKKL").validate(
    {
      rules:  {
            uiTxtRevNum: {required: true}
          }
    });     
  });   

function checkNumeric(objName,minval, maxval,comma,period,hyphen)
{
	var numberfield = objName;
	if (chkNumeric(objName,minval,maxval,comma,period,hyphen) == false)
	{
		numberfield.select();
		numberfield.focus();
		return false;
	}
	else
	{
		return true;
	}
}

function chkNumeric(objName,minval,maxval,comma,period,hyphen)
{
	var checkOK = "0123456789" + comma + period + hyphen;
	var checkStr = objName;
	var allValid = true;
	var decPoints = 0;
	var allNum = "";

	for (i = 0;  i < checkStr.value.length;  i++)
	{
	ch = checkStr.value.charAt(i);
	for (j = 0;  j < checkOK.length;  j++)
	if (ch == checkOK.charAt(j))
	break;
	if (j == checkOK.length)
	{
	allValid = false;
	break;
	}
	if (ch != ",")
	allNum += ch;
	}
	if (!allValid)
	{	
	alertsay = "Please enter only these values \""
	alertsay = alertsay + checkOK + "\" in the \"" + checkStr.name + "\" field."
	alert(alertsay);
	return (false);
	}

	// set the minimum and maximum
	var chkVal = allNum;
	var prsVal = parseInt(allNum);
	if (chkVal != "" && !(prsVal >= minval && prsVal <= maxval))
	{
	alertsay = "Please enter a value greater than or "
	alertsay = alertsay + "equal to \"" + minval + "\" and less than or "
	alertsay = alertsay + "equal to \"" + maxval + "\" in the \"" + checkStr.name + "\" field."
	alert(alertsay);
	return (false);
	}
	}
	//  End -->
</script>
