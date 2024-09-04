<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	$pageTitle="SKKL Request";
	include "template/top2.php"; //Load template pembuka dan load css eksternal
	include "include/date_lib.php";
	// require "include/class.phpmailer.php";
    include_once "include/send_notification.php"; 
	 //redirect page
    echo "<meta http-equiv='refresh' content='3;url=skklStatusBawahan.php'>";

	//jika button approve yang diklik maka lakukkan
	if(isset($_POST['btnApprove'])&& $_POST['btnApprove'])
	{
		$skklID = $_POST['uiChkSKKLGrp'];
		$groupSKKL = count($skklID);
		//looping by checklist id
		for($i=0;$i<$groupSKKL;$i++)
		   {
			    $StatusSuperiorQry=odbc_exec($conn,"select * from tb_SKKLTransaction where SKKLID='$skklID[$i]'");
				$firstSuperior =  odbc_result($StatusSuperiorQry,14);
				$secondSuperior =  odbc_result($StatusSuperiorQry,16);
				$superiorNik = odbc_result($StatusSuperiorQry,"SuperiorNIK");
				$nikLembur = odbc_result($StatusSuperiorQry,"NIK");
				$updatetime = date("Y-m-d h:i:s");
				$assignmentDate = odbc_result($StatusSuperiorQry,"AssignmentDate");
				
				
			 	if($firstSuperior == $NIK && $secondSuperior == NULL)
				{
					//validate backdate
					//parse assignment Date
					$tahunAssign=substr($assignmentDate, 0,4);
					$bulanAssign=substr($assignmentDate, 5,2);
					$tanggalAssign=substr($assignmentDate, 8,2);
			
			
					//check validate if flag 1 gagal update
					if (checkDateOvertimeAgree($tahunAssign,$bulanAssign,$tanggalAssign))
					{
					
					   $update=odbc_exec($conn,"UPDATE tb_SKKLTransaction SET isFirstApproved = 'true', 
												 isApproved = 'true', FirstApprovedTime='$updatetime' 
												 where SKKLID='$skklID[$i]'");
						
					
						//send SMS dan email to superior
						//echo "exec spIsiSMSUpdateSKKL  '$skklID[$i]'";
						$sendSMStoIsi = odbc_exec($conn,"exec spIsiSMSUpdateSKKL  '$skklID[$i]', 1, 0");
						
						//get mail superior from tr_login		
						$getMailSuperior = odbc_exec($conn,"select email from tr_login where 
															userLogin='$superiorNik'");
						$emailSuperior = odbc_result($getMailSuperior,'email');
						
						//get mail nik from tr_login		
						$getMailNik = odbc_exec($conn,"select email from tr_login where userLogin='$nikLembur'");
						$emailNik = odbc_result($getMailNik,'email');
						
						//get pesan bawahan
						$getQryMessageBawahan = odbc_exec($conn,"select top 1 * from  Temp_SMS_UpdateSKKL where SKKLID='$skklID[$i]' order by Ket asc");
						$MessageOvertimeBawahan = substr(odbc_result($getQryMessageBawahan,'Ket'),2);
						
						//get pesan Superior
						$getQryMessageSuperior = odbc_exec($conn,"select top 1 * from  Temp_SMS_UpdateSKKL where SKKLID='$skklID[$i]' order by Ket desc");
						$MessageOvertimeSuperior = substr(odbc_result($getQryMessageSuperior,'Ket'),2);
						
						if($MessageOvertimeBawahan != '' or $MessageOvertimeSuperior !='')
						{
							//send mail ke bawahan
							//configuration mail							

							$return = send_email(EMAIL_HOST_1, EMAIL_PORT_1, EMAIL_SECURITY_1, EMAIL_USER_1, EMAIL_PASSWORD_1,EMAIL_FROM_1,EMAIL_FROM2_1,$emailNik,"[HRPortal] Overtime Request Information",$MessageOvertimeBawahan);

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
					        }
							
							//send mail ke Superior
							//configuration mail

							$return = send_email(EMAIL_HOST_1, EMAIL_PORT_1, EMAIL_SECURITY_1, EMAIL_USER_1, EMAIL_PASSWORD_1,EMAIL_FROM_1,EMAIL_FROM2_1,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertimeSuperior);

					        if ($return['status']) {
					        }
					        else{
					          $return = send_email(EMAIL_HOST_2, EMAIL_PORT_2, true, EMAIL_USER_2, EMAIL_PASSWORD_2,EMAIL_FROM_2,EMAIL_FROM2_2,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertimeSuperior);

					          if ($return['status']) {
					          }
					          else{
					            $errorMessage = $return['msg'];
					            echo "Error sending: ".$errorMessage;
					          }
					        }
						}
							
						
						if($update)
						{
							echo "<center><label class='alert alert-success'>Your data id=$skklID[$i] has been updated<br><h2>Thank You</h2></label></center>";			
						}else{
							echo("<center><label class='alert alert-error'>Input data failed</label></center>");
						}
					}
					else
					{
						
						$update=odbc_exec($conn,"UPDATE tb_SKKLTransaction SET isFirstApproved = 'false', 
						isApproved = 'false',FirstApprovedTime='$updatetime' where SKKLID='$skklID[$i]'");
								
						//send SMS dan email to superior
						//$sendSMStoIsi = odbc_exec($conn,"exec spIsiSMSCancelSKKL  '$skklID[$i]'");
						
						//get mail from tr_login		
						$getMailSuperior = odbc_exec($conn,"select email from tr_login where userLogin='$superiorNik'");
						$emailSuperior = odbc_result($getMailSuperior,'email');
						
						//get pesan 
						//$getQryMessage = odbc_exec($conn,"select * from  Temp_SMS_CancelSKKL where SKKLID='$skklID[$i]'");
						//$MessageOvertime = substr(odbc_result($getQryMessage,'Ket'),2);
						
						$getQryMessage = odbc_exec($conn,"select * from  tb_SKKLTransaction where SKKLID='$skklID[$i]'");
						$nikkaryawan = odbc_result($getQryMessage,'NIK');
						$namakaryawan = odbc_result($getQryMessage,'Nama');
						$namaatasan = odbc_result($getQryMessage,'NamaFirstApprove');
						$MessageOvertime = "HR PORTAL KG: For some reason, your overtime assignment for $namakaryawan($nikkaryawan) has been rejected by $namaatasan($firstSuperior)";
		
						if($MessageOvertime != '')
						{
							//send mail
							//configuration mail

							$return = send_email(EMAIL_HOST_1, EMAIL_PORT_1, EMAIL_SECURITY_1, EMAIL_USER_1, EMAIL_PASSWORD_1,EMAIL_FROM_1,EMAIL_FROM2_1,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertime);

					        if ($return['status']) {
					        }
					        else{
					          $return = send_email(EMAIL_HOST_2, EMAIL_PORT_2, true, EMAIL_USER_2, EMAIL_PASSWORD_2,EMAIL_FROM_2,EMAIL_FROM2_2,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertime);

					          if ($return['status']) {
					          }
					          else{
					            $errorMessage = $return['msg'];
					            echo "Error sending: ".$errorMessage;
					          }
					        }
						}
						
						if($update)
						{
							echo "<center><label class='alert alert-error'>Anda terlambat melakukan approval, proses approve SKKLID=$skklID[$i] gagal.<br>Harap hubungi HR unit untuk mengajukan lembur susulan.</label></center>";

						}else{
							echo("<center><label class='alert alert-error'>Input data failed</label></center>");
						}
					}
				}
				elseif($firstSuperior == $NIK or $secondSuperior == $NIK && $secondSuperior != NULL )
				{
					//validate backdate
					//parse assignment Date
					$tahunAssign=substr($assignmentDate, 0,4);
					$bulanAssign=substr($assignmentDate, 5,2);
					$tanggalAssign=substr($assignmentDate, 8,2);
					
					if ($secondSuperior == $NIK)
					{
						//cek di tb_SKKLTransaction apabila firstsuperior blm approve maka lakukan ini
						if (odbc_result($StatusSuperiorQry,18) == NULL)
						{			
							//check validate if flag 1 gagal update
							if (checkDateOvertimeAgree($tahunAssign,$bulanAssign,$tanggalAssign))
							{
								//echo "UPDATE tb_SKKLTransaction SET isSecondApproved = 'true', SecondApprovedTime='$updatetime' where SKKLID='$skklID[$i]'";
								$update=odbc_exec($conn,"UPDATE tb_SKKLTransaction SET isSecondApproved = 'true', SecondApprovedTime='$updatetime' where SKKLID='$skklID[$i]'");
								
								//send SMS dan email to superior
								$sendSMStoIsi = odbc_exec($conn,"exec spIsiSMSUpdateSKKL  '$skklID[$i]',0,1");
								
								//get mail from tr_login		
								$getMailSuperior = odbc_exec($conn,"select email from tr_login where userLogin='$superiorNik'");
								$emailSuperior = odbc_result($getMailSuperior,'email');
								
								//get pesan 
								$getQryMessage = odbc_exec($conn,"select * from  Temp_SMS_UpdateSKKL where SKKLID='$skklID[$i]'");
								$MessageOvertime = substr(odbc_result($getQryMessage,'Ket'),2);
				
								if($MessageOvertime !='')
								{
									//send mail
									//configuration mail

									$return = send_email(EMAIL_HOST_1, EMAIL_PORT_1, EMAIL_SECURITY_1, EMAIL_USER_1, EMAIL_PASSWORD_1,EMAIL_FROM_1,EMAIL_FROM2_1,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertime);

							        if ($return['status']) {
							        }
							        else{
							          $return = send_email(EMAIL_HOST_2, EMAIL_PORT_2, true, EMAIL_USER_2, EMAIL_PASSWORD_2,EMAIL_FROM_2,EMAIL_FROM2_2,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertime);

							          if ($return['status']) {
							          }
							          else{
							            $errorMessage = $return['msg'];
							            echo "Error sending: ".$errorMessage;
							          }
							        }
									
									//send SMS
									//$sendSMStoSkkl = odbc_exec($conn,"exec spToSMSUpdateSKKL");	
								}
								if($update)
								{
									echo "<center><label class='alert alert-success'>Your data id=$skklID[$i] has been updated<br><h2>Thank You</h2></label></center>";			
								}else{
									echo("<center><label class='alert alert-error'>Input data failed</label></center>");
								}		
							}
							else
							{
															
								$update=odbc_exec($conn,"UPDATE tb_SKKLTransaction SET isFirstApproved = 'false', 
								isApproved = 'false',FirstApprovedTime='$updatetime' where SKKLID='$skklID[$i]'");
								
								//send SMS dan email to superior
								//$sendSMStoIsi = odbc_exec($conn,"exec spIsiSMSCancelSKKL  '$skklID[$i]'");
						
								//get mail from tr_login		
								$getMailSuperior = odbc_exec($conn,"select email from tr_login where userLogin='$superiorNik'");
								$emailSuperior = odbc_result($getMailSuperior,'email');
						
								//get pesan 
								$getQryMessage = odbc_exec($conn,"select * from  tb_SKKLTransaction where SKKLID='$skklID[$i]'");
								$nikkaryawan = odbc_result($getQryMessage,'NIK');
								$namakaryawan = odbc_result($getQryMessage,'Nama');
								$namaatasan = odbc_result($getQryMessage,'NamaSecondApprove');
								$MessageOvertime = "HR PORTAL KG: For some reason, your overtime assignment for $namakaryawan($nikkaryawan) has been rejected by $namaatasan($secondSuperior)";
		
								if($MessageOvertime != '')
								{
									//send mail
									//configuration mail

									$return = send_email(EMAIL_HOST_1, EMAIL_PORT_1, EMAIL_SECURITY_1, EMAIL_USER_1, EMAIL_PASSWORD_1,EMAIL_FROM_1,EMAIL_FROM2_1,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertime);

							        if ($return['status']) {
							        }
							        else{
							          $return = send_email(EMAIL_HOST_2, EMAIL_PORT_2, true, EMAIL_USER_2, EMAIL_PASSWORD_2,EMAIL_FROM_2,EMAIL_FROM2_2,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertime);

							          if ($return['status']) {
							          }
							          else{
							            $errorMessage = $return['msg'];
							            echo "Error sending: ".$errorMessage;
							          }
							        }
								}
						
								if($update)
								{
									echo "<center><label class='alert alert-error'>Anda terlambat melakukan approval, proses approve SKKLID=$skklID[$i] gagal.<br>Harap hubungi HR unit untuk mengajukan lembur susulan.</label></center>";
									
								}else{
									echo("<center><label class='alert alert-error'>Input data failed</label></center>");
								}
							}
						
						}
						else
						{
							//check validate if flag 1 gagal update
							if (checkDateOvertimeAgree($tahunAssign,$bulanAssign,$tanggalAssign))
							{
								$update=odbc_exec($conn,"UPDATE tb_SKKLTransaction SET isSecondApproved = 'true',  isApproved = 'true', SecondApprovedTime='$updatetime' where SKKLID='$skklID[$i]'");
								//send SMS dan email to superior
								$sendSMStoIsi = odbc_exec($conn,"exec spIsiSMSUpdateSKKL  '$skklID[$i]',0,1");
							
								//get mail from tr_login		
								$getMailSuperior = odbc_exec($conn,"select email from tr_login where userLogin='$superiorNik'");
								$emailSuperior = odbc_result($getMailSuperior,'email');								
								//get pesan 
								$getQryMessage = odbc_exec($conn,"select * from  Temp_SMS_UpdateSKKL where SKKLID='$skklID[$i]'");
								$MessageOvertime = substr(odbc_result($getQryMessage,'Ket'),2);
				
								if($MessageOvertime !='')
								{
									//send mail
									//configuration mail

									$return = send_email(EMAIL_HOST_1, EMAIL_PORT_1, EMAIL_SECURITY_1, EMAIL_USER_1, EMAIL_PASSWORD_1,EMAIL_FROM_1,EMAIL_FROM2_1,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertime);

							        if ($return['status']) {
							        }
							        else{
							          $return = send_email(EMAIL_HOST_2, EMAIL_PORT_2, true, EMAIL_USER_2, EMAIL_PASSWORD_2,EMAIL_FROM_2,EMAIL_FROM2_2,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertime);

							          if ($return['status']) {
							          }
							          else{
							            $errorMessage = $return['msg'];
							            echo "Error sending: ".$errorMessage;
							          }
							        }
									
									//send SMS
									//$sendSMStoSkkl = odbc_exec($conn,"exec spToSMSUpdateSKKL");	
								}
							
								if($update)
								{
									echo "<center><label class='alert alert-success'>Your data id=$skklID[$i] has been updated<br><h2>Thank You</h2></label></center>";			
								}else{
									echo("<center><label class='alert alert-error'>Input data failed</label></center>");
								}							

							}
							else
							{
							
								/*echo "UPDATE tb_SKKLTransaction SET isFirstApproved = 'false', 
								isApproved = 'false',FirstApprovedTime='$updatetime' where SKKLID='$skklID[$i]'";*/
								
								$update=odbc_exec($conn,"UPDATE tb_SKKLTransaction SET isFirstApproved = 'false', 
								isApproved = 'false',FirstApprovedTime='$updatetime' where SKKLID='$skklID[$i]'");
									
								//echo "exec spIsiSMSCancelSKKL  '$skklID[$i]'";	
								//send SMS dan email to superior
								//$sendSMStoIsi = odbc_exec($conn,"exec spIsiSMSCancelSKKL  '$skklID[$i]'");
							
								//get mail from tr_login		
								$getMailSuperior = odbc_exec($conn,"select email from tr_login where userLogin='$superiorNik'");
								$emailSuperior = odbc_result($getMailSuperior,'email');
							
								//get pesan 
								//$getQryMessage = odbc_exec($conn,"select * from  Temp_SMS_CancelSKKL where SKKLID='$skklID[$i]'");
								//$MessageOvertime = substr(odbc_result($getQryMessage,'Ket'),2);
								
								$getQryMessage = odbc_exec($conn,"select * from  tb_SKKLTransaction where SKKLID='$skklID[$i]'");
								$nikkaryawan = odbc_result($getQryMessage,'NIK');
								$namakaryawan = odbc_result($getQryMessage,'Nama');
								$namaatasan = odbc_result($getQryMessage,'NamaSecondApprove');
								$MessageOvertime = "HR PORTAL KG: For some reason, your overtime assignment for $namakaryawan($nikkaryawan) has been rejected by $namaatasan($secondSuperior)";
			
								if($MessageOvertime != '')
								{
									//send mail
									//configuration mail

									$return = send_email(EMAIL_HOST_1, EMAIL_PORT_1, EMAIL_SECURITY_1, EMAIL_USER_1, EMAIL_PASSWORD_1,EMAIL_FROM_1,EMAIL_FROM2_1,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertime);

							        if ($return['status']) {
							        }
							        else{
							          $return = send_email(EMAIL_HOST_2, EMAIL_PORT_2, true, EMAIL_USER_2, EMAIL_PASSWORD_2,EMAIL_FROM_2,EMAIL_FROM2_2,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertime);

							          if ($return['status']) {
							          }
							          else{
							            $errorMessage = $return['msg'];
							            echo "Error sending: ".$errorMessage;
							          }
							        }
								}
								
							
								if($update)
								{
									echo "<center><label class='alert alert-error'>Anda terlambat melakukan approval, proses approve SKKLID=$skklID[$i] gagal.<br>Harap hubungi HR unit untuk mengajukan lembur susulan.</label></center>";
								}else{
									echo("<center><label class='alert alert-error'>Input data failed</label></center>");
								}
							}
						}
					}
					else
					{
						if (odbc_result($StatusSuperiorQry,19) == NULL)
						{
							//check validate if flag 1 gagal update
							if (checkDateOvertimeAgree($tahunAssign,$bulanAssign,$tanggalAssign))
							{
								$update=odbc_exec($conn,"UPDATE tb_SKKLTransaction SET isFirstApproved = 'true', FirstApprovedTime='$updatetime' where SKKLID='$skklID[$i]'");
							
								//send SMS dan email to superior
								$sendSMStoIsi = odbc_exec($conn,"exec spIsiSMSUpdateSKKL  '$skklID[$i]',1,0");
							
								//get mail from tr_login		
								$getMailSuperior = odbc_exec($conn,"select email from tr_login where userLogin='$superiorNik'");
								$emailSuperior = odbc_result($getMailSuperior,'email');
							
								//get pesan 
								$getQryMessage = odbc_exec($conn,"select * from  Temp_SMS_UpdateSKKL where SKKLID='$skklID[$i]'");
								$MessageOvertime = substr(odbc_result($getQryMessage,'Ket'),2);
			
								if($MessageOvertime !='')
								{
									//send mail
									//configuration mail

									$return = send_email(EMAIL_HOST_1, EMAIL_PORT_1, EMAIL_SECURITY_1, EMAIL_USER_1, EMAIL_PASSWORD_1,EMAIL_FROM_1,EMAIL_FROM2_1,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertime);

							        if ($return['status']) {
							        }
							        else{
							          $return = send_email(EMAIL_HOST_2, EMAIL_PORT_2, true, EMAIL_USER_2, EMAIL_PASSWORD_2,EMAIL_FROM_2,EMAIL_FROM2_2,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertime);

							          if ($return['status']) {
							          }
							          else{
							            $errorMessage = $return['msg'];
							            echo "Error sending: ".$errorMessage;
							          }
							        }
									
									//send SMS
									//$sendSMStoSkkl = odbc_exec($conn,"exec spToSMSUpdateSKKL");	
								}
								
								if($update)
								{
									echo "<center><label class='alert alert-success'>Your data id=$skklID[$i] has been updated<br><h2>Thank You</h2></label></center>";			
								}else{
									echo("<center><label class='alert alert-error'>Input data failed</label></center>");
								}							
							}
							else
							{
							
								$update=odbc_exec($conn,"UPDATE tb_SKKLTransaction SET isFirstApproved = 'false', 
								isApproved = 'false',FirstApprovedTime='$updatetime' where SKKLID='$skklID[$i]'");
										
								//send SMS dan email to superior
								$sendSMStoIsi = odbc_exec($conn,"exec spIsiSMSCancelSKKL  '$skklID[$i]'");
								
								//get mail from tr_login		
								$getMailSuperior = odbc_exec($conn,"select email from tr_login where userLogin='$superiorNik'");
								$emailSuperior = odbc_result($getMailSuperior,'email');
								
								//get pesan 
								$getQryMessage = odbc_exec($conn,"select * from  Temp_SMS_CancelSKKL where SKKLID='$skklID[$i]'");
								$MessageOvertime = substr(odbc_result($getQryMessage,'Ket'),2);
				
								if($MessageOvertime != '')
								{
									//send mail
									//configuration mail

									$return = send_email(EMAIL_HOST_1, EMAIL_PORT_1, EMAIL_SECURITY_1, EMAIL_USER_1, EMAIL_PASSWORD_1,EMAIL_FROM_1,EMAIL_FROM2_1,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertime);

							        if ($return['status']) {
							        }
							        else{
							          $return = send_email(EMAIL_HOST_2, EMAIL_PORT_2, true, EMAIL_USER_2, EMAIL_PASSWORD_2,EMAIL_FROM_2,EMAIL_FROM2_2,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertime);

							          if ($return['status']) {
							          }
							          else{
							            $errorMessage = $return['msg'];
							            echo "Error sending: ".$errorMessage;
							          }
							        }
								}
								
								if($update)
								{
									echo "<center><label class='alert alert-error'>Anda terlambat melakukan approval, proses approve SKKLID=$skklID[$i] gagal.<br>Harap hubungi HR unit untuk mengajukan lembur susulan.</label></center>";
								}else{
									echo("<center><label class='alert alert-error'>Input data failed</label></center>");
								}
							}
						}
						else
						{//check validate if flag 1 gagal update
							if (checkDateOvertimeAgree($tahunAssign,$bulanAssign,$tanggalAssign))
							{
								$update=odbc_exec($conn,"UPDATE tb_SKKLTransaction SET isFirstApproved = 'true', isApproved = 'true', FirstApprovedTime='$updatetime' where SKKLID='$skklID[$i]'");
							
								//send SMS dan email to superior
								$sendSMStoIsi = odbc_exec($conn,"exec spIsiSMSUpdateSKKL  '$skklID[$i]',1,1");
							
								//get mail from tr_login		
								$getMailSuperior = odbc_exec($conn,"select email from tr_login where 
																userLogin='$superiorNik'");
								$emailSuperior = odbc_result($getMailSuperior,'email');
							
								//get pesan 
								$getQryMessage = odbc_exec($conn,"select * from  Temp_SMS_UpdateSKKL where SKKLID='$skklID[$i]'");
								$MessageOvertime = substr(odbc_result($getQryMessage,'Ket'),2);
			
								if($MessageOvertime !='')
								{
									//send mail
									//configuration mail

									$return = send_email(EMAIL_HOST_1, EMAIL_PORT_1, EMAIL_SECURITY_1, EMAIL_USER_1, EMAIL_PASSWORD_1,EMAIL_FROM_1,EMAIL_FROM2_1,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertime);

							        if ($return['status']) {
							        }
							        else{
							          $return = send_email(EMAIL_HOST_2, EMAIL_PORT_2, true, EMAIL_USER_2, EMAIL_PASSWORD_2,EMAIL_FROM_2,EMAIL_FROM2_2,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertime);

							          if ($return['status']) {
							          }
							          else{
							            $errorMessage = $return['msg'];
							            echo "Error sending: ".$errorMessage;
							          }
							        }
									
									//send SMS
								}
								
								if($update)
								{
									echo "<center><label class='alert alert-success'>Your data id=$skklID[$i] has been updated<br><h2>Thank You</h2></label></center>";			
								}else{
									echo("<center><label class='alert alert-error'>Input data failed</label></center>");
								}							
							}
							else
							{
						
								$update=odbc_exec($conn,"UPDATE tb_SKKLTransaction SET isFirstApproved = 'false', 
								isApproved = 'false',FirstApprovedTime='$updatetime' where SKKLID='$skklID[$i]'");
										
								//send SMS dan email to superior
								$sendSMStoIsi = odbc_exec($conn,"exec spIsiSMSCancelSKKL  '$skklID[$i]'");
								
								//get mail from tr_login		
								$getMailSuperior = odbc_exec($conn,"select email from tr_login where userLogin='$superiorNik'");
								$emailSuperior = odbc_result($getMailSuperior,'email');
								
								//get pesan 
								$getQryMessage = odbc_exec($conn,"select * from  Temp_SMS_CancelSKKL where SKKLID='$skklID[$i]'");
								$MessageOvertime = substr(odbc_result($getQryMessage,'Ket'),2);
				
								if($MessageOvertime != '')
								{
									//send mail
									//configuration mail

									$return = send_email(EMAIL_HOST_1, EMAIL_PORT_1, EMAIL_SECURITY_1, EMAIL_USER_1, EMAIL_PASSWORD_1,EMAIL_FROM_1,EMAIL_FROM2_1,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertime);

							        if ($return['status']) {
							        }
							        else{
							          $return = send_email(EMAIL_HOST_2, EMAIL_PORT_2, true, EMAIL_USER_2, EMAIL_PASSWORD_2,EMAIL_FROM_2,EMAIL_FROM2_2,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertime);

							          if ($return['status']) {
							          }
							          else{
							            $errorMessage = $return['msg'];
							            echo "Error sending: ".$errorMessage;
							          }
							        }
								}
								
								if($update)
								{
									echo "<center><label class='alert alert-error'>Anda terlambat melakukan approval, proses approve SKKLID=$skklID[$i] gagal.<br>Harap hubungi HR unit untuk mengajukan lembur susulan.</label></center>";	
								}else{
									echo("<center><label class='alert alert-error'>Input data failed</label></center>");
								}
							}
						}
					}
				
				}
				
		   
		   }
		   
			$SMSQuerySuperior=odbc_exec($conn,"select * from tb_SKKLTransaction where SKKLID='$skklID[0]'");
			$nikFirstSuperior =  odbc_result($SMSQuerySuperior,14);
			$nikSecondSuperior =  odbc_result($SMSQuerySuperior,16);
			$nikSuperiorNik = odbc_result($SMSQuerySuperior,"SuperiorNIK");
			$smsAssignmentDate = odbc_result($SMSQuerySuperior,"AssignmentDate");
				
			if($nikFirstSuperior == $NIK && $nikSecondSuperior == NULL)
			{
				//echo "exec spToSMSUpdateSKKL $nikSuperiorNik, $smsAssignmentDate";
				$sendSMStoSkkl = odbc_exec($conn,"exec spToSMSUpdateSKKL '$nikSuperiorNik', '$smsAssignmentDate',1,0");
			}
			elseif($nikFirstSuperior == $NIK or $nikSecondSuperior == $NIK && $nikSecondSuperior != NULL )
			{
				if ($secondSuperior == $NIK)
				{
					if (odbc_result($SMSQuerySuperior,19) == NULL)
					{
						$sendSMStoSkkl = odbc_exec($conn,"exec spToSMSUpdateSKKL '$nikSuperiorNik', '$smsAssignmentDate',0,1");
					}
					else
					{
						$sendSMStoSkkl = odbc_exec($conn,"exec spToSMSUpdateSKKL '$nikSuperiorNik', '$smsAssignmentDate',1,1");
					}
				}
				else
				{
					if (odbc_result($SMSQuerySuperior,19) == NULL)
					{
						$sendSMStoSkkl = odbc_exec($conn,"exec spToSMSUpdateSKKL '$nikSuperiorNik', '$smsAssignmentDate',1,0");
					}
					else
					{
						$sendSMStoSkkl = odbc_exec($conn,"exec spToSMSUpdateSKKL '$nikSuperiorNik', '$smsAssignmentDate',1,1");
					}
				}
			} 
			
	}
	else
	{
		$skklID = $_POST['uiChkSKKLGrp'];
		$groupSKKL = count($skklID);
		
		//looping by checklist id
		for($i=0;$i<$groupSKKL;$i++)
		   {
			    $StatusSuperiorQry=odbc_exec($conn,"select * from tb_SKKLTransaction where SKKLID='$skklID[$i]'");
				$firstSuperior =  odbc_result($StatusSuperiorQry,14);
				$secondSuperior =  odbc_result($StatusSuperiorQry,16);
				$superiorNik = odbc_result($StatusSuperiorQry,"SuperiorNIK");
				$nikLembur = odbc_result($StatusSuperiorQry,"NIK");
				$updatetime = date("Y-m-d h:i:s");
				
				//reject
				if($firstSuperior == $NIK && $secondSuperior == NULL)
				{
					
						$update=odbc_exec($conn,"UPDATE tb_SKKLTransaction SET isFirstApproved = 'false', 
						isApproved = 'false',FirstApprovedTime='$updatetime' where SKKLID='$skklID[$i]'");
								
						//send SMS dan email to superior
						$sendSMStoIsi = odbc_exec($conn,"exec spIsiSMSCancelSKKL  '$skklID[$i]'");
						
						//get mail from tr_login		
						$getMailSuperior = odbc_exec($conn,"select email from tr_login where userLogin='$superiorNik'");
						$emailSuperior = odbc_result($getMailSuperior,'email');
						
						//get pesan 
						$getQryMessage = odbc_exec($conn,"select * from  Temp_SMS_CancelSKKL where SKKLID='$skklID[$i]'");
						$MessageOvertimeCancel = substr(odbc_result($getQryMessage,'Ket'),2);
		
						if($MessageOvertimeCancel != '')
						{
							//send mail
							//configuration mail

							$return = send_email(EMAIL_HOST_1, EMAIL_PORT_1, EMAIL_SECURITY_1, EMAIL_USER_1, EMAIL_PASSWORD_1,EMAIL_FROM_1,EMAIL_FROM2_1,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertimeCancel);

					        if ($return['status']) {
					        }
					        else{
					          $return = send_email(EMAIL_HOST_2, EMAIL_PORT_2, true, EMAIL_USER_2, EMAIL_PASSWORD_2,EMAIL_FROM_2,EMAIL_FROM2_2,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertimeCancel);

					          if ($return['status']) {
					          }
					          else{
					            $errorMessage = $return['msg'];
					            echo "Error sending: ".$errorMessage;
					          }
					        }
						}
						
						if($update)
						{
							echo "<center><label class='alert alert-success'>Your data id=$skklID[$i] has been updated<br><h2>Thank You</h2></label></center>";			
						}else{
							echo("<center><label class='alert alert-error'>Input data failed</label></center>");
						}							

				}
				elseif($firstSuperior == $NIK or $secondSuperior == $NIK && $secondSuperior != NULL )
				{
					if ($secondSuperior == $NIK)
					{
						$update=odbc_exec($conn,"UPDATE tb_SKKLTransaction SET isSecondApproved = 'false', isApproved = 'false', 
								SecondApprovedTime='$updatetime' where SKKLID='$skklID[$i]'");
						
						//send SMS dan email to superior
						$sendSMStoIsi = odbc_exec($conn,"exec spIsiSMSCancelSKKL  '$skklID[$i]'");
						
						//get mail from tr_login		
						$getMailSuperior = odbc_exec($conn,"select email from tr_login where userLogin='$superiorNik'");
						$emailSuperior = odbc_result($getMailSuperior,'email');
						
						//get pesan 
						$getQryMessage = odbc_exec($conn,"select * from  Temp_SMS_CancelSKKL where SKKLID='$skklID[$i]'");
						$MessageOvertimeCancel = substr(odbc_result($getQryMessage,'Ket'),2);
		
			
						if($MessageOvertimeCancel != '')
						{
							//send mail
							//configuration mail

							$return = send_email(EMAIL_HOST_1, EMAIL_PORT_1, EMAIL_SECURITY_1, EMAIL_USER_1, EMAIL_PASSWORD_1,EMAIL_FROM_1,EMAIL_FROM2_1,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertimeCancel);

					        if ($return['status']) {
					        }
					        else{
					          $return = send_email(EMAIL_HOST_2, EMAIL_PORT_2, true, EMAIL_USER_2, EMAIL_PASSWORD_2,EMAIL_FROM_2,EMAIL_FROM2_2,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertimeCancel);

					          if ($return['status']) {
					          }
					          else{
					            $errorMessage = $return['msg'];
					            echo "Error sending: ".$errorMessage;
					          }
					        }

						}
						//send sms
						//$sendSMStoSkkl = odbc_exec($conn,"exec spToSMSCancelSKKL");	
						
						if($update)
						{
							echo "<center><label class='alert alert-success'>Your data id=$skklID[$i] has been updated<br><h2>Thank You</h2></label></center>";			
						}else{
							echo("<center><label class='alert alert-error'>Input data failed</label></center>");
						}	
						
					}
					else
					{
						$update=odbc_exec($conn,"UPDATE tb_SKKLTransaction SET isFirstApproved = 'false',  isApproved = 'false', 
								FirstApprovedTime='$updatetime' where SKKLID='$skklID[$i]'");
						
						//send SMS dan email to superior
						$sendSMStoIsi = odbc_exec($conn,"exec spIsiSMSCancelSKKL  '$skklID[$i]'");
						
						//get mail from tr_login		
						$getMailSuperior = odbc_exec($conn,"select email from tr_login where userLogin='$superiorNik'");
						$emailSuperior = odbc_result($getMailSuperior,'email');
						
						//get pesan 
						$getQryMessage = odbc_exec($conn,"select * from  Temp_SMS_CancelSKKL where SKKLID='$skklID[$i]'");
						$MessageOvertimeCancel = substr(odbc_result($getQryMessage,'Ket'),2);
		
						if ($MessageOvertimeCancel != '')
						{
							//send mail
							//configuration mail

							$return = send_email(EMAIL_HOST_1, EMAIL_PORT_1, EMAIL_SECURITY_1, EMAIL_USER_1, EMAIL_PASSWORD_1,EMAIL_FROM_1,EMAIL_FROM2_1,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertimeCancel);

					        if ($return['status']) {
					        }
					        else{
					          $return = send_email(EMAIL_HOST_2, EMAIL_PORT_2, true, EMAIL_USER_2, EMAIL_PASSWORD_2,EMAIL_FROM_2,EMAIL_FROM2_2,$emailSuperior,"[HRPortal] Overtime Request Information",$MessageOvertimeCancel);

					          if ($return['status']) {
					          }
					          else{
					            $errorMessage = $return['msg'];
					            echo "Error sending: ".$errorMessage;
					          }
					        }
						}
						
						//send sms
						//$sendSMStoSkkl = odbc_exec($conn,"exec spToSMSCancelSKKL");
						
						if($update)
						{
							echo "<center><label class='alert alert-success'>Your data id=$skklID[$i] has been updated<br><h2>Thank You</h2></label></center>";			
						}else{
							echo("<center><label class='alert alert-error'>Input data failed</label></center>");
						}	
						
					}
					
				}

		   
		   }
			
			//free all result
			//odbc_result_all();	
			//close odbc after execute
			odbc_close($conn);
	
		   	$SMSCancelQuerySuperior=odbc_exec($conn,"select * from tb_SKKLTransaction where SKKLID='$skklID[0]'");
			$nikFirstSuperior =  odbc_result($SMSCancelQuerySuperior,14);
			$nikSecondSuperior =  odbc_result($SMSCancelQuerySuperior,16);
			$nikSuperiorNik = odbc_result($SMSCancelQuerySuperior,"SuperiorNIK");
			$smsAssignmentDate = odbc_result($SMSCancelQuerySuperior,"AssignmentDate");
				
			if($nikFirstSuperior == $NIK && $nikSecondSuperior == NULL)
			{
				$sendSMStoSkkl = odbc_exec($conn,"exec spToSMSCancelSKKL '$nikSuperiorNik', '$smsAssignmentDate'");
			}
			else
			{
				$sendSMStoSkkl = odbc_exec($conn,"exec spToSMSCancelSKKL '$nikSuperiorNik', '$smsAssignmentDate'");
			} 

	}
?>


<?php
	include "template/bottom2.php"; //Load tenplate penutup dan load javascript eksternal
?>
