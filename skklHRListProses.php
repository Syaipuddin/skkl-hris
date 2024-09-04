<?php
	$pageTitle="SKKL Request";
	include "template/top2.php"; //Load template pembuka dan load css eksternal
	/*include "include/date_lib.php";
	require "include/class.phpmailer.php";*/
	echo "<meta http-equiv='refresh' content='3;url=skklHRList.php'>";
?>
<h2>SKKL HR List Proses</h2><br>
                <!-- Content For Code -->
<?php

//jika button approve yang diklik maka lakukkan
if ($_POST['btnApprove'])
{
	$idSkkl = $_POST['uiChkSKKLGrp'];
	$groupSKKL = count($idSkkl);
	$approveTime = date('Y-m-d H:i:s');
	
	if($NIK=="")
	{
		echo "<center><label class='alert alert-error'><h2>Approve failed HRNIK is empty </h2></label></center>";
	}
	else
	{
		$StatusSuperiorQry=odbc_exec($conn,"select SKKLID, NIK, BeginDate, EndDate, AssignmentDate from tb_SKKLTransaction 
												where SKKLID in(" . implode(',', $idSkkl) . ")");

		$ArrSukses = array();
		$ArrGagal = array();

		//looping by checklist id
		while($StatusSuperiorArr = odbc_fetch_array($StatusSuperiorQry))
		{  
			   
			/*$StatusSuperiorQry=odbc_exec($conn,"select NIK, BeginDate, EndDate, AssignmentDate from tb_SKKLTransaction 
												where SKKLID='$idSkkl[$i]'");*/
			/*$nikLembur = odbc_result($StatusSuperiorQry,"NIK");
			$beginDate = odbc_result($StatusSuperiorQry,"BeginDate");
			$endDate = odbc_result($StatusSuperiorQry,"EndDate");
			$assignDate= substr(odbc_result($StatusSuperiorQry,'AssignmentDate'),8,2).substr(odbc_result($StatusSuperiorQry,'AssignmentDate'),5,2).substr(odbc_result($StatusSuperiorQry,'AssignmentDate'),0,4);*/
			$idSkklQry = $StatusSuperiorArr["SKKLID"];
			$nikLembur = $StatusSuperiorArr["NIK"];
			$beginDate = $StatusSuperiorArr["BeginDate"];
			$endDate = $StatusSuperiorArr["EndDate"];
			$assignDate= substr($StatusSuperiorArr['AssignmentDate'],8,2).substr($StatusSuperiorArr['AssignmentDate'],5,2).substr($StatusSuperiorArr['AssignmentDate'],0,4);
		   
			//insert to sap with attendance quota
			//lakukan import ke sap
			$fce = saprfc_function_discover($rfc,"ZHRFM_CREATE_2007_NONUMBER");
			if (! $fce ) { echo "System Error. Please Try Again Later."; exit; }
		//Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
			saprfc_import ($fce,"CHOIC_003",'2007');
			saprfc_import ($fce,"CHOIC_006",'ATTENDANCE QUOTAS');
			saprfc_import ($fce,"CTU","X");
			saprfc_import ($fce,"FI_BEGDA",$assignDate);
			saprfc_import ($fce,"FI_ENDDA",$assignDate);
			saprfc_import ($fce,"FI_JAMBEGDA",$beginDate);
			saprfc_import ($fce,"FI_JAMENDDA",$endDate);
			saprfc_import ($fce,"FI_PERNR",$nikLembur);
			saprfc_import ($fce,"FI_TYPE","01");
			saprfc_import ($fce,"GROUP","");
			saprfc_import ($fce,"HOLDDATE","");
			saprfc_import ($fce,"KEEP","");
			saprfc_import ($fce,"MODE","N");
			saprfc_import ($fce,"NODATA","/");
			saprfc_import ($fce,"TIMR6_002","X");
			saprfc_import ($fce,"TIMR6_005","X");
			saprfc_import ($fce,"UPDATE","L");
			saprfc_import ($fce,"USER","");
			//Fill internal tables
			saprfc_table_init ($fce,"MESSTAB");
			//Do RFC call of function ZHRFM_CREATE_2007, for handling exceptions use saprfc_exception()
			$rfc_rc = saprfc_call_and_receive ($fce);
			if ($rfc_rc != SAPRFC_OK) 
			{ 
				if ($rfc == SAPRFC_EXCEPTION ) 
				echo "System Error. Please Try Again Later."; 
				else 
				echo "System Error. Please Try Again Later.";  exit;
			}
			
			$SUBRC = saprfc_export ($fce,"SUBRC");
			//Retrieve export parameters
			$rows = saprfc_table_rows ($fce,"MESSTAB");
			
			if($SUBRC != 0)
			{
				array_push($ArrGagal, $idSkklQry);
				/*$update=odbc_exec($conn,"UPDATE  [tb_SKKLTransaction] SET 
									[HRNIK] = NULL, [HRApprovedTime] = NULL,[Flag]='1'	
									WHERE SKKLID='$idSkkl[$i]'");   */
				// echo "<center><label class='alert alert-error'><h2>Approve failed</h2></label></center>";
			}
			else 
			{
				array_push($ArrSukses, $idSkklQry);
				//save data
				/*$update=odbc_exec($conn,"UPDATE  [tb_SKKLTransaction] SET 
									[HRNIK] = '$NIK', [HRApprovedTime] = '$approveTime'	
									WHERE SKKLID='$idSkkl[$i]'"); 
				echo  "<center><label class='alert alert-success'>Your data has been updated<br><h2>Thank You</h2></label></center>";*/
			}
			saprfc_function_free($fce);
		}

		if (count($ArrGagal)>0) {
			$update=odbc_exec($conn,"UPDATE  [tb_SKKLTransaction] SET 
									[HRNIK] = NULL, [HRApprovedTime] = NULL,[Flag]='1'	
									WHERE SKKLID IN(" . implode(',', $ArrGagal) . ")");   
			echo "<center><label class='alert alert-error'><h2>There is Failed Approve</h2></label></center>";
		}
		
		if (count($ArrSukses)>0) {
			$update=odbc_exec($conn,"UPDATE  [tb_SKKLTransaction] SET 
									[HRNIK] = '$NIK', [HRApprovedTime] = '$approveTime'	
									WHERE SKKLID IN(" . implode(',', $ArrSukses) . ")");
			echo  "<center><label class='alert alert-success'>Your data has been updated<br><h2>Thank You</h2></label></center>";
		}
	}

}
?>
<div></div>

<?php
	include "template/bottom2.php"; //Load tenplate penutup dan load javascript eksternal
?>
