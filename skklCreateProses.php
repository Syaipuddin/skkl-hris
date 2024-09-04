<?php
	$pageTitle="SKKL Request";
	include "template/top2.php"; //Load template pembuka dan load css eksternal
	include "include/date_lib.php";
    	include_once "include/send_notification.php";

	//redirect page
    echo "<meta http-equiv='refresh' content='3;url=skklStatusAtasan.php'>";
    skkl_declaration_function($NIK,$conn);

    	function skkl_save_and_notif_function($NIK,$conn,$save,$nikSpv1,$nikSpv2,$startTime,$endTime,$startMinute,$endMinute)
    	{
    					if (extension_loaded('newrelic')) { // Ensure PHP agent is available
					        $new_relic = newrelic_add_custom_tracer("skkl_save_and_notif_function");
					        $change_name = newrelic_name_transaction("/skklCreateProses.php/skkl_save_and_notif_function");
					        newrelic_end_transaction(); // stop recording the current transaction
			        		newrelic_start_transaction(ini_get("newrelic.appname"));
					    }

			            if($save)
						{
							
							//get variable		
							$assignNum=$_POST['uiTxtAssignNum'];
							$grpNikLembur=$_POST['uiTxtNIK2Grp'];
							$namaNikLembur = $_POST['uiTxtNameGrp'];
							$totalNikLembur= count($grpNikLembur);
							if(isset($_POST['uiTxtSpvGrp']))
							{
								$nikSpvGrp=$_POST['uiTxtSpvGrp'];
								$namaSpvGrp =$_POST['uiTxtSpvName'];
							}
							$totalSpv= count($nikSpvGrp);
							$assignmentDate=$_POST['uiTxtSKKLDate'];
							$startSKKLTime = $startTime.":".$startMinute;
							$endSKKLTime = $endTime.":".$endMinute;
							
							
							if ($totalSpv ==0)
							{	
									// DICOMMENT DLU BIAR GA BERAT
									//send SMS dan email to bawahan
									/*$sendSMStoIsi = odbc_exec($conn,"exec spIsiSMSUpdateSKKLOK  '$NIK', '$assignmentDate'");
									//send sms
									$sendSMStoSkkl = odbc_exec($conn,"exec spToSMSSKKLOK '$NIK', '$assignmentDate'");	*/
									

								echo "<center><label class='alert alert-success'>Your request has been sent<br> 
									click <a href='skklStatusAtasan.php'> here </a>
									if your browser doesn't redirect to home page<br><h2>Thank You</h2></label></center>";
							

							}
							elseif ($totalSpv ==1)
							{

								//get SKKLID
								// $getSKKLID = odbc_exec($conn,"select top 1 SKKLID  from dbo.tb_SKKLTransaction where AssignmentDate='$assignmentDate' and SuperiorNIK='$NIK' order by SKKLID desc");

								odbc_execute($getSKKLID = odbc_prepare($conn,"select top 1 SKKLID from tb_SKKLTransaction where AssignmentDate=? and SuperiorNIK=? order by SKKLID desc"),array($assignmentDate,$NIK));
								$skklID = odbc_result($getSKKLID,'SKKLID');
								
								// DICOMMENT DLU BIAR GA BERAT
								//kirim SMS
								// $sendSMStoIsi = odbc_exec($conn,"exec spIsiSMSSKKL '$NIK', '$assignmentDate'");
								
								//get mail from tr_login		
								// $getEmailSpv1 = odbc_exec($conn,"select email from tr_login where userLogin='$nikSpv1'");

								odbc_execute($getEmailSpv1 = odbc_prepare($conn,"select email from tr_login where userLogin=?"),array($nikSpv1));

								$emailSpv1 = odbc_result($getEmailSpv1,'email');
								
								//get pesan 
								// $getQryMessage = odbc_exec($conn,"select Ket from  Temp_SMSSKKL where SKKLID=$skklID");

								odbc_execute($getQryMessage = odbc_prepare($conn,"select Ket from  Temp_SMSSKKL where SKKLID=?"),array($skklID));

								$MessageOvertime = substr(odbc_result($getQryMessage,'Ket'),2);
								
								// DICOMMENT DLU BIAR GA BERAT
								//configuration mail
								/*$return = send_email(EMAIL_HOST_1, EMAIL_PORT_1, EMAIL_SECURITY_1, EMAIL_USER_1, EMAIL_PASSWORD_1,EMAIL_FROM_1,EMAIL_FROM2_1,$emailSpv1,"[HRPortal] Overtime Request Information",$MessageOvertime);

							        if ($return['status']) {
							        }
							        else{
							          $return = send_email(EMAIL_HOST_2, EMAIL_PORT_2, true, EMAIL_USER_2, EMAIL_PASSWORD_2,EMAIL_FROM_2,EMAIL_FROM2_2,$emailSpv1,"[HRPortal] Overtime Request Information",$MessageOvertime);

							          if ($return['status']) {
							          }
							          else{
							            $errorMessage = $return['msg'];
							            echo "Error sending: ".$errorMessage;
							          }
							        }*/
								
								// DICOMMENT DLU BIAR GA BERAT
								// $sendSMStoSkkl = odbc_exec($conn,"exec spToSMSSKKL '$NIK', '$assignmentDate'");	
								//berhasil
								echo "<center><label class='alert alert-success'>Your request has been sent<br> 
									click <a href='skklStatusAtasan.php'> here </a>
									if your browser doesn't redirect to home page<br><h2>Thank You</h2></label></center></div>";
							}
							elseif($totalSpv==2)
							{
								//get SKKLID
								// $getSKKLID = odbc_exec($conn,"select top 1 SKKLID  from dbo.tb_SKKLTransaction where 
								// 	AssignmentDate='$assignmentDate' and SuperiorNIK='$NIK' order by SKKLID desc");

								odbc_execute($getSKKLID = odbc_prepare($conn,"select top 1 SKKLID  from dbo.tb_SKKLTransaction where 
									AssignmentDate=? and SuperiorNIK=? order by SKKLID desc"),array($assignmentDate,$NIK));

								$skklID = odbc_result($getSKKLID,'SKKLID');
							
								//kirim SMS
								// DICOMMENT DLU BIAR GA BERAT
								// $sendSMStoIsi = odbc_exec($conn,"exec spIsiSMSSKKL '$NIK', '$assignmentDate'");
								
								//get mail from tr_login		
								// $getEmailSpv1 = odbc_exec($conn,"select email from tr_login where userLogin='$nikSpv1'");
								odbc_execute($getEmailSpv1 = odbc_prepare($conn,"select email from tr_login where userLogin=?"),array($nikSpv1));
								$emailSpv1 = odbc_result($getEmailSpv1,'email');
								
								// $getEmailSpv2 = odbc_exec($conn,"select email from tr_login where userLogin='$nikSpv2'");
								odbc_execute($getEmailSpv2 = odbc_prepare($conn,"select email from tr_login where userLogin=?"),array($nikSpv2));

								$emailSpv2 = odbc_result($getEmailSpv2,'email');
								
								//get pesan 
								//echo "select * from  Temp_SMSSKKL where SKKLID=$skklID";
								// $getQryMessage = odbc_exec($conn,"select Ket from  Temp_SMSSKKL where SKKLID=$skklID");

								odbc_execute($getQryMessage = odbc_prepare($conn,"select Ket from  Temp_SMSSKKL where SKKLID=?"),array($skklID));

								$MessageOvertime = substr(odbc_result($getQryMessage,'Ket'),2);
								
								//configuration mail
								
								
								$email = array($emailSpv1, $emailSpv2);
								// DICOMMENT DLU BIAR GA BERAT
								/*$return = send_email(EMAIL_HOST_1, EMAIL_PORT_1, EMAIL_SECURITY_1, EMAIL_USER_1, EMAIL_PASSWORD_1,EMAIL_FROM_1,EMAIL_FROM2_1,$email,"[HRPortal] Overtime Request Information",$MessageOvertime);

							        if ($return['status']) {
							        }
							        else{
							          $return = send_email(EMAIL_HOST_2, EMAIL_PORT_2, true, EMAIL_USER_2, EMAIL_PASSWORD_2,EMAIL_FROM_2,EMAIL_FROM2_2,$email,"[HRPortal] Overtime Request Information",$MessageOvertime);

							          if ($return['status']) {
							          }
							          else{
							            $errorMessage = $return['msg'];
							            echo "Error sending: ".$errorMessage;
							          }
							        }
								
								$sendSMStoSkkl = odbc_exec($conn,"exec spToSMSSKKL '$NIK', '$assignmentDate'");*/
								
								//berhasil
								echo "<center><label class='alert alert-success'>Your request has been sent<br> 
									click <a href='skklStatusAtasan.php'> here </a>
									if your browser doesn't redirect to home page<br><h2>Thank You</h2>
									</label></center></div>";
							}
			            }
						else
						{
			             	echo("<center><label class='alert alert-error'>Input data failed</label></center>");
			           	}
		}

		function skkl_insert_and_email_function($NIK,$conn,$totalNikLembur, $grpNikLembur,$namaNikLembur,$inputDate,$assignmentDate,$startSKKLTime,$endSKKLTime,$agendaSKKL,$namaSuperior,$totalSpv,$nikSpv1,$namaSpv1,$nikSpv2,$namaSpv2,$startTime,$endTime,$startMinute,$endMinute){

						if (extension_loaded('newrelic')) { // Ensure PHP agent is available
					        $new_relic = newrelic_add_custom_tracer("skkl_insert_and_email_function");
					        $change_name = newrelic_name_transaction("/skklCreateProses.php/skkl_insert_and_email_function");
					        newrelic_end_transaction(); // stop recording the current transaction
			        		newrelic_start_transaction(ini_get("newrelic.appname"));
					    }

						$a=0;
						//looping berdasarkan nik lembur
						for($a=0;$a<$totalNikLembur;$a++)
						{
							//kondisi jika supervisor kosong
							if ($totalSpv == 0)
							{
								
								//insert to table tanpa isi supervisor nik 1 dan 2
								//  $save=odbc_exec($conn,"INSERT INTO 	[PORTAL].[dbo].[tb_SKKLTransaction]([NIK],[Nama],[InputDate],
								//  	[AssignmentDate],[BeginDate],[EndDate],[Agenda],[SuperiorNIK],[NamaSuperior],[isApproved],[FirstApproverNIK],[NamaFirstApprove],[SecondApproverNIK],[NamaSecondApprove]) VALUES
								// ('$grpNikLembur[$a]','$namaNikLembur[$a]','$inputDate','$assignmentDate','$startSKKLTime',
								// 		'$endSKKLTime','$agendaSKKL','$NIK','$namaSuperior','True'," . ($nikSpv1 ? "'$nikSpv1'": 'NULL') . "," . ($namaSpv1 ? "'$namaSpv1'": 'NULL') . "," 
								// 		. ($nikSpv2 ? "'$nikSpv2'": 'NULL') . "," . ($namaSpv2 ? "'$namaSpv2'": 'NULL') . ")");

								odbc_execute($save = odbc_prepare($conn,"INSERT INTO 
												[PORTAL].[dbo].[tb_SKKLTransaction]([NIK],[Nama],[InputDate],[AssignmentDate]
												,[BeginDate],[EndDate],[Agenda],[SuperiorNIK],
												[NamaSuperior],[FirstApproverNIK],[NamaFirstApprove],[SecondApproverNIK],[NamaSecondApprove])
												VALUES
													(?,?,?,?,?,?,?,?,?,?,?,?,?)"),array($grpNikLembur[$a],$namaNikLembur[$a],$inputDate,$assignmentDate,$startSKKLTime,
												$endSKKLTime,$agendaSKKL,$NIK,$namaSuperior,($nikSpv1 ? "$nikSpv1": NULL),
												($namaSpv1 ? "$namaSpv1": NULL),($nikSpv2 ? "$nikSpv2": NULL),($namaSpv2 ? "$namaSpv2": NULL)));
								
									//get mail nik from tr_login		
									// $getMailNik = odbc_exec($conn,"select email from tr_login where userLogin='$grpNikLembur[$a]'");

									odbc_execute($getMailNik = odbc_prepare($conn,"select email from tr_login where userLogin=?"),array($grpNikLembur[$a]));

									$emailNik = odbc_result($getMailNik,'email');
									
												
									//get pesan bawahan
									$MessageOvertimeBawahan = "HR PORTAL KG: $namaSuperior ($NIK) has assigned you to work overtime on 
									$assignmentDate-$startSKKLTime to $endSKKLTime For detail information, please check https://hr.kompasgramedia.com";
									
									//send mail ke bawahan
									//configuration mail
									// DICOMMENT DLU BIAR GA BERAT
									/*$return = send_email(EMAIL_HOST_1, EMAIL_PORT_1, EMAIL_SECURITY_1, EMAIL_USER_1, EMAIL_PASSWORD_1,EMAIL_FROM_1,EMAIL_FROM2_1,$emailNik,"[HRPortal] Overtime Request Information",$MessageOvertimeBawahan);

								        if ($return['status']) {
								        }
								        else{
								          $return = send_email(EMAIL_HOST_2, EMAIL_PORT_2, true, EMAIL_USER_2, EMAIL_PASSWORD_2,EMAIL_FROM_2,EMAIL_FROM2_2,$emailNik,"[HRPortal] Overtime Request Information",$MessageOvertimeBawahan);

								          if ($return['status']) {
								          }
								          else{
								            $errorMessage = $return['msg'];
								            echo "Error sending: ".$errorMessage;
								          }
								        }*/
							}
							elseif($totalSpv == 1)
							{
								
								
								//insert to table isi supervisor nik 1
								// $save=odbc_exec($conn,"INSERT INTO 
								//  				[PORTAL].[dbo].[tb_SKKLTransaction]([NIK],[Nama],[InputDate],[AssignmentDate]
								// 				,[BeginDate],[EndDate],[Agenda],[SuperiorNIK],
								// 				[NamaSuperior],[FirstApproverNIK],[NamaFirstApprove]) VALUES
					           	// 				('$grpNikLembur[$a]','$namaNikLembur[$a]','$inputDate','$assignmentDate','$startSKKLTime',
								// 				'$endSKKLTime','$agendaSKKL','$NIK','$namaSuperior'," . ($nikSpv1 ? "'$nikSpv1'": 'NULL') . "," .
								// 				($namaSpv1 ? "'$namaSpv1'": 'NULL') . ")");

								$save = odbc_prepare($conn,"INSERT INTO 
												[PORTAL].[dbo].[tb_SKKLTransaction] ([NIK],[Nama],[InputDate],[AssignmentDate],[BeginDate],[EndDate],[Agenda],[SuperiorNIK],[NamaSuperior],[FirstApproverNIK],[NamaFirstApprove])
												VALUES
													(?,?,?,?,?,?,?,?,?,?,?)");

								odbc_execute($save,array($grpNikLembur[$a],$namaNikLembur[$a],$inputDate,$assignmentDate,$startSKKLTime,
												$endSKKLTime,$agendaSKKL,$NIK,$namaSuperior,($nikSpv1 ? $nikSpv1: NULL),
												($namaSpv1 ? $namaSpv1: NULL)));
			
							}
							elseif($totalSpv == 2)
							{
									
								//insert to table isi supervisor nik 1 dan 2
								// $save=odbc_exec($conn,"INSERT INTO 
								//  				[PORTAL].[dbo].[tb_SKKLTransaction]([NIK],[Nama],[InputDate],[AssignmentDate]
								// 				,[BeginDate],[EndDate],[Agenda],[SuperiorNIK],
								// 				[NamaSuperior],[FirstApproverNIK],[NamaFirstApprove],[SecondApproverNIK],[NamaSecondApprove])
								// 				VALUES
			           			// 				('$grpNikLembur[$a]','$namaNikLembur[$a]','$inputDate','$assignmentDate','$startSKKLTime',
								// 				'$endSKKLTime','$agendaSKKL','$NIK','$namaSuperior'," . ($nikSpv1 ? "'$nikSpv1'": 'NULL'). "," .
								// 				($namaSpv1 ? "'$namaSpv1'": 'NULL') . "," 
								// 				. ($nikSpv2 ? "'$nikSpv2'": 'NULL') . "," . ($namaSpv2 ? "'$namaSpv2'": 'NULL') . ")");
								
								odbc_execute($save = odbc_prepare($conn,"INSERT INTO 
													[PORTAL].[dbo].[tb_SKKLTransaction]([NIK],[Nama],[InputDate],[AssignmentDate]
													,[BeginDate],[EndDate],[Agenda],[SuperiorNIK],
													[NamaSuperior],[FirstApproverNIK],[NamaFirstApprove],[SecondApproverNIK],[NamaSecondApprove])
													VALUES
														(?,?,?,?,?,?,?,?,?,?,?,?,?)"),array($grpNikLembur[$a],$namaNikLembur[$a],$inputDate,$assignmentDate,$startSKKLTime,
													$endSKKLTime,$agendaSKKL,$NIK,$namaSuperior,($nikSpv1 ? "$nikSpv1": NULL),
													($namaSpv1 ? "$namaSpv1": NULL),($nikSpv2 ? "$nikSpv2": NULL),($namaSpv2 ? "$namaSpv2": NULL)));
							}
						}

					    // call skkl_save_and_notif_function
					    skkl_save_and_notif_function($NIK,$conn,$save,$nikSpv1,$nikSpv2,$startTime,$endTime,$startMinute,$endMinute);
		}

    	function skkl_insert_and_save($NIK,$conn,$flag,$totalNikLembur, $grpNikLembur,$namaNikLembur,$inputDate,$assignmentDate,$startSKKLTime,$endSKKLTime,$agendaSKKL,$namaSuperior,$totalSpv,$nikSpv1,$namaSpv1,$nikSpv2,$namaSpv2,$startTime,$endTime,$startMinute,$endMinute)
		    {

		    	if (extension_loaded('newrelic')) { // Ensure PHP agent is available
			        $new_relic = newrelic_add_custom_tracer("skkl_insert_and_save");
			        $change_name = newrelic_name_transaction("/skklCreateProses.php/skkl_insert_and_save");
			        newrelic_end_transaction(); // stop recording the current transaction
			        newrelic_start_transaction(ini_get("newrelic.appname"));
			    }

				if($flag != 1)
				{
					// call skkl_save_and_notif_function
					skkl_insert_and_email_function($NIK,$conn,$totalNikLembur, $grpNikLembur,$namaNikLembur,$inputDate,$assignmentDate,$startSKKLTime,$endSKKLTime,$agendaSKKL,$namaSuperior,$totalSpv,$nikSpv1,$namaSpv1,$nikSpv2,$namaSpv2,$startTime,$endTime,$startMinute,$endMinute);
				}
				else
				{
					echo "<script>alert('SKKL Failed!');javascript:history.go(-1);</script>";	
					
				}
		}
		
		function skkl_declaration_function($NIK,$conn){

			if (extension_loaded('newrelic')) { // Ensure PHP agent is available
		        $new_relic = newrelic_add_custom_tracer("skkl_declaration_function");
		        $change_name = newrelic_name_transaction("/skklCreateProses.php/skkl_declaration_function");
		        newrelic_end_transaction(); // stop recording the current transaction
		        newrelic_start_transaction(ini_get("newrelic.appname"));
		    }

			//	$nik	=	$_REQUEST['NIK'];
			$task	=	$_REQUEST['task'];
			//get nama from SAP
			// $getnama = odbc_exec($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = '$NIK'");

			odbc_execute($getnama = odbc_prepare($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = ?"),array($NIK));

	        $namaSuperior=odbc_result($getnama, "Nama");
			
			if ($task == md5('addSKKL'))
			{
				//get variable		
				$assignNum=$_POST['uiTxtAssignNum'];
				$grpNikLembur=$_POST['uiTxtNIK2Grp'];
				$namaNikLembur = $_POST['uiTxtNameGrp'];
				$totalNikLembur= count($grpNikLembur);
				$inputDate=date('Y-m-d H:i:s');
				$assignmentDate=$_POST['uiTxtSKKLDate'];
		            	$startTime=$_POST['uiDdlmulaiJam'];
		   		$startMinute=$_POST['uiDdlmulaiMenit'];
		   		$endTime=$_POST['uiDdlselesaiJam'];
		   		$endMinute=$_POST['uiDdlselesaiMenit'];
				$startSKKLTime = $startTime.":".$startMinute;
		   		$endSKKLTime = $endTime.":".$endMinute;
				$agendaSKKL=$_POST['uiTxtAgenda'];
				if(isset($_POST['uiTxtSpvGrp'])!='')
				{
					$nikSpvGrp=$_POST['uiTxtSpvGrp'];
					$namaSpvGrp =$_POST['uiTxtSpvName'];
					$totalSpv= count($nikSpvGrp);
				}
				
				$nikSpv1 = $nikSpvGrp[0];
				$namaSpv1 = $namaSpvGrp[0];

				if(isset($nikSpvGrp[1])!='')
				{
					$nikSpv2 = $nikSpvGrp[1];
					$namaSpv2 = $namaSpvGrp[1];
				}
				

				//parse assignment Date
				$tahunAssign=substr($assignmentDate, 0,4);
				$bulanAssign=substr($assignmentDate, 5,2);
				$tanggalAssign=substr($assignmentDate, 8,2);

				$flag=0;
				//check validate if flag 1 gagal update
				if (checkDateOvertimeReq($tahunAssign,$bulanAssign,$tanggalAssign))
				{
				   //valid
				   //echo 'valid';
				}
				else
				{
					echo "<label class='alert alert-error'>Harap mengajukan lembur susulan melalui HR Unit.</label>";
				    $flag =1;
				}

			// call skkl_insert_and_save
		    skkl_insert_and_save($NIK,$conn,$flag,$totalNikLembur, $grpNikLembur,$namaNikLembur,$inputDate,$assignmentDate,$startSKKLTime,$endSKKLTime,$agendaSKKL,$namaSuperior,$totalSpv,$nikSpv1,$namaSpv1,$nikSpv2,$namaSpv2,$startTime,$endTime,$startMinute,$endMinute);

		}
		}
        ?>
  
          
            </div>
        </div>
    </div>
</div>
</div>

<?php
	include "template/bottom2.php"; //Load tenplate penutup dan load javascript eksternal
?>
