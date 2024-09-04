<?php
	include "template/top3.php";
	include "include/date_lib.php";
	require "include/class.phpmailer.php";	
?>
   <div class="row span5">
<?php
	
	$id	=	$_REQUEST['id'];
	$task	=	$_REQUEST['task'];
	
	if ($task == md5('EntrySKKLHR'))
	{
		// $HRQRY	= odbc_exec($conn,"select * from tb_SKKLTransaction where SKKLID='$id'");

		odbc_execute($HRQRY = odbc_prepare($conn,"select * from tb_SKKLTransaction where SKKLID=?"),array($id));

		$nikLembur = odbc_result($HRQRY,'NIK');
		$beginTime = odbc_result($HRQRY,'BeginDate');
		$endTime = odbc_result($HRQRY,'EndDate');
		$assignDate= substr(odbc_result($HRQRY,'AssignmentDate'),8,2).substr(odbc_result($HRQRY,'AssignmentDate'),5,2).substr(odbc_result($HRQRY,'AssignmentDate'),0,4);
		$revNum = $_POST['uiTxtRevNum'];
		$approveTime = date('Y-m-d H:i:s');
		
		$revisiNo = str_replace(',','.', $revNum);
		
		
		if(empty($revNum))
		{
			// $update=odbc_exec($conn,"UPDATE  [tb_SKKLTransaction]
			// 					SET [HRNIK] = '$NIK', 
			// 					[HRApprovedTime] = '$approveTime'	WHERE SKKLID='$id'");

			odbc_execute($update = odbc_prepare($conn,"UPDATE  [tb_SKKLTransaction]
								SET [HRNIK] = ?, 
								[HRApprovedTime] = ?	WHERE SKKLID=?"),array($NIK, $approveTime, $id));
			
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
							//Retrieve export parameters
							$rows = saprfc_table_rows ($fce,"MESSTAB");
							//for ($i=1;$i<=$rows;$i++)
								//$MESSTAB[] = saprfc_table_read ($fce,"MESSTAB",$i);
							//Debug info
							saprfc_function_free($fce);
							
							if($SUBRC != 0)
							{
								// $update=odbc_exec($conn,"UPDATE  [tb_SKKLTransaction]
								// SET [HRNIK] = NULL,[Flag]='1',
								// [HRApprovedTime] = NULL	WHERE SKKLID='$id'");

								odbc_execute($update = odbc_prepare($conn,"UPDATE  [tb_SKKLTransaction]
								SET [HRNIK] = NULL,[Flag]='1',
								[HRApprovedTime] = NULL	WHERE SKKLID=?"),array($id));

								echo "<center><label class='alert alert-error'><h2>Approve failed</h2></label></center>";
							}
							else 
							{
								//save data
								echo  "<center><label class='alert alert-success'>Your data has been updated<br><h2>Thank You</h2></label></center>";
							}
		}else
		{
			//echo "UPDATE  [tb_SKKLTransaction]	SET [RevisionNum] = '$revisiNo', [HRNIK] = '$NIK', 	[HRApprovedTime] = '$approveTime'	WHERE SKKLID='$id'";
			// $update=odbc_exec($conn,"UPDATE  [tb_SKKLTransaction]
			// 					SET [RevisionNum] = '$revisiNo', [HRNIK] = '$NIK', 
			// 					[HRApprovedTime] = '$approveTime'	WHERE SKKLID='$id'");

			odbc_execute($update = odbc_prepare($conn,"UPDATE  [tb_SKKLTransaction]
								SET [RevisionNum] = ?, [HRNIK] = ?, 
								[HRApprovedTime] = ?	WHERE SKKLID=?"),array($revisiNo,$NIK,$approveTime,$id));

			//lakukan import ke sap
			//Discover interface for function module ZHRFM_CREATE_2007
			$fce = saprfc_function_discover($rfc,"ZHRFM_CREATE_2007");
			if (! $fce ) 
			{ 
				echo "Discovering interface of function module failed"; exit; 
			}
			
			//Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
			saprfc_import ($fce,"CHOIC_003",'2007');
			saprfc_import ($fce,"CHOIC_006",'ATTENDANCE QUOTAS');
			saprfc_import ($fce,"CTU","X");
			saprfc_import ($fce,"FI_BEGDA",$assignDate);
			saprfc_import ($fce,"FI_ENDDA",$assignDate);
			saprfc_import ($fce,"FI_JAMBEGDA",$beginTime);
			saprfc_import ($fce,"FI_JAMENDDA",$endTime);
			saprfc_import ($fce,"FI_LEMBUR",$revNum);
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
				echo ("Exception raised: ".saprfc_exception($fce)); 
				else echo (saprfc_error($fce)); exit; 
			}
			
			//Retrieve export parameters
			$SUBRC = saprfc_export ($fce,"SUBRC");
			$rows = saprfc_table_rows ($fce,"MESSTAB");
			for ($i=1;$i<=$rows;$i++)
				$MESSTAB[] = saprfc_table_read ($fce,"MESSTAB",$i);
			//Debug info
			//saprfc_function_debug_info($fce);
			saprfc_function_free($fce);
			//saprfc_close($rfc);
			}
	
			if($SUBRC != 0)
			{
				// $update=odbc_exec($conn,"UPDATE  [tb_SKKLTransaction]
				// 				SET [RevisionNum] = NULL, [HRNIK] = NULL, [Flag]='1',
				// 				[HRApprovedTime] = NULL	WHERE SKKLID='$id'");

				odbc_execute($update = odbc_prepare($conn,"UPDATE  [tb_SKKLTransaction]
								SET [RevisionNum] = NULL, [HRNIK] = NULL, [Flag]='1',
								[HRApprovedTime] = NULL	WHERE SKKLID=?"),array($id));

				echo "<center><label class='alert alert-error'><h2>Approve failed</h2></label></center>";
			}
			else 
			{
				//save data
				echo  "<center><label class='alert alert-success'>Your data has been updated<br><h2>Thank You</h2></label></center>";
			}
		}
		
?>
<center><input type="button" value="Close" onClick="self.parent.tb_remove(true);" /></center>
</div>

<?php
	include "template/bottom3.php";
?>