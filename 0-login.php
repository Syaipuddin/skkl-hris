<?php
	session_start(); 
	require_once "include/formvalidator.php";
	include "include/sapconnect.php";
	include "include/connection.php";
	include "include/system_lib.php";

	// var_dump($_SESSION);
	/*function check_survey($rfc,$conn_eSurvey,$nik)
	{
		$fce_s = saprfc_function_discover($rfc,"ZHRFM_GETPOSORG_OM");
		if (! $fce_s ){
			$errorMessage = "An Error Occured, Please Try Again Later.";
			echo "<script>alert('".$errorMessage."');</script>";
											// echo "<script>window.location='index.php'</script>";
		} else {
			saprfc_import ($fce_s,"KEYDATE",date('Ymd'));
			saprfc_import ($fce_s,"OBJID",$nik);
			$rfc_rc = saprfc_call_and_receive ($fce_s);
			$post = saprfc_table_rows ($fce_s,"FI_OUT");
			if ($post == 1){
				$FI_POST = saprfc_table_read ($fce_s,"FI_OUT",1);
				$position_id = $FI_POST['OBJECT_ID'];
				saprfc_function_free($fce_s);
				
				$fce_p = saprfc_function_discover($rfc,"ZHRFM_GETORGPOSDETAIL");
				if (! $fce_p ){
					$errorMessage = "An Error Occured, Please Try Again Later.";
					echo "<script>alert('".$errorMessage."');</script>";
													// echo "<script>window.location='index.php'</script>";
				} 

				saprfc_import ($fce_p,"KEYDATE",date('Ymd'));
				saprfc_import ($fce_p,"OBJID",$position_id);
				saprfc_import ($fce_p,"DEPTH",0);
				$rfc_rc = saprfc_call_and_receive ($fce_p);

				$om_count = saprfc_table_rows ($fce_p,"T_OBJECTSDATA");
				if ($om_count>0) {
					$unit_id 			= '';
					$div_id 			= '';
					for ($i=1; $i <= $om_count ; $i++) { 
						$om = saprfc_table_read ($fce_p,"T_OBJECTSDATA",$i);	
						if(substr($om['SHORT_TEXT'], 0,1)=='1'){
							$unit_id 		= $om['OBJECT_ID'];
						} else if(substr($om['SHORT_TEXT'], 0,1)=='2') {
							$div_id 		= $om['OBJECT_ID'];
						}
					}
					if ($unit_id == '') {
						$unit_id = $div_id;
					}*/
					/*$array_prepare = array(1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $nik, $position_id, 1);
					$query = "SELECT COUNT(*) as val 
						FROM GES_M_kuesioner 
						WHERE is_active = ? 
							AND start_time <= ? 
							AND end_time >= ? 
							AND kuesioner_id NOT IN 
							(SELECT kuesioner_id 
								FROM GES_T_responden 
								WHERE nik = ? 
									AND position_id = ? 
									AND is_submitted = ?)";
						if ($unit_id != '') {
							$query .= " AND 
								(
									(method = ?
									AND is_all = ?
									AND kuesioner_id IN 
										(SELECT kuesioner_id 
											FROM GES_M_responden_unit 
											WHERE  org_id = ?)
									)
								OR 
									(method = ?
									AND kuesioner_id IN
										(SELECT kuesioner_id 
									FROM GES_T_responden 
									WHERE nik = ?
										AND position_id = ?
										AND is_submitted = ?)
									)
								)";

								$array_prepare = array(1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $nik, $position_id, 1, 0, 0, $unit_id, 1, $nik, $position_id, 0);
						}*/

						/*$query = "SELECT COUNT(*) as val 
						FROM GES_M_kuesioner 
						WHERE is_active = 1 
							AND start_time <= GETDATE() 
							AND end_time >= GETDATE() 
							AND kuesioner_id NOT IN 
							(SELECT kuesioner_id 
								FROM GES_T_responden 
								WHERE nik = '$nik' 
									AND position_id = '$position_id' 
									AND is_submitted = 1)";
						if ($unit_id != '') {
							$query .= " AND 
								(
									(method = 0 
									AND is_all = 0 
									AND kuesioner_id IN 
										(SELECT kuesioner_id 
											FROM GES_M_responden_unit 
											WHERE  org_id = $unit_id)
									)
								OR 
									(method = 1 
									AND kuesioner_id IN
										(SELECT kuesioner_id 
									FROM GES_T_responden 
									WHERE nik = '$nik' 
										AND position_id = '$position_id' 
										AND is_submitted = 0)
									)
								)";
						}

						$count_k = odbc_result(odbc_exec($conn_eSurvey, $query), 'val');*/
						/*$count_k_sql = odbc_prepare($conn_eSurvey, $query);
						odbc_execute($count_k_sql, $array_prepare);
						$count_k = odbc_result($count_k_sql, 'val');*/
						// $count_k = odbc_result(odbc_execute(odbc_prepare($conn_eSurvey, $query), $array_prepare), 'val');
						/*if ($count_k == 0) {
							return FALSE;
						} else {
							return TRUE;
						}
				} else {
					return TRUE;

				}

			} else {
				return TRUE;
			}
		}

	}*/

	$_SESSION['lang']=strtoupper($_POST['lang']);
	if (isset($_SESSION['tagLogin']))
	{
		$tagLogin = $_SESSION['tagLogin'];
	} 
	else
	 {
		$tagLogin = 0;
	}
	$errorMessage = "";
	if(isset($_SESSION['nik']))
	{
		//echo session_id();
		$NIK = htmlentities(base64_decode($_SESSION['nik']));
		$history_cekExec = odbc_execute($history_cek = odbc_prepare($conn,"select lastSessionID from tr_login where userLogin = ?"), array($NIK));
		if(odbc_result($history_cek,1) != session_id())
		{
			echo "<script>alert('Please login first to access this page.');</script>";
			echo "<script>window.location='0-logout.php'</script>";
		}else{
			// cek BPJS DIMAS
									
									/*$queryactiveBPJS=odbc_exec($conn,"SELECT Active FROM tb_Bpjs WHERE NIK='".$NIK."' AND PISA='1'");
									$statusBPJS=odbc_result($queryactiveBPJS,1);*/

									$queryactiveBPJS = odbc_prepare($conn, "SELECT Active FROM tb_Bpjs WHERE NIK=? AND PISA=?");
									$exec = odbc_execute($queryactiveBPJS, array($NIK, 1));
									$statusBPJS = odbc_result($queryactiveBPJS, 1);

									/*if (check_survey($rfc,$conn_eSurvey,$NIK)) {
										echo "<script>window.location='redirect.php?to=esurvey'</script>";
									} else {*/
										echo "<script>window.location='home.php'</script>";
									// }

									// if($statusBPJS=="1")
									// {
										// var_dump('1',check_survey($rfc,$conn_eSurvey,$NIK));
										// echo "<script>window.location='home.php'</script>";
									// }
									// else{
									// 	echo "<script>window.location='BPJSadd.php'</script>";
									// }
		}

	}
	else 
	{
		$NIK = htmlentities($_POST['NIK']);
		$PW = htmlentities($_POST['PW']);
		
		$fce1 = saprfc_function_discover($rfc,"ZHRFM_GETAUT");
		if (! $fce1 )
		{
			$errorMessage = "An Error Occured, Please Try Again Later.";
			echo "<script>alert('".$errorMessage."');</script>";
											// echo "<script>window.location='index.php'</script>";
		}
		else
		{
			//cek dia counter password berapa kali
			$counterPasswordExec = odbc_execute($counterPassword = odbc_prepare($conn, "select counterPassword FROM tr_login where userLogin = ?"), array($NIK));
			$counterSeccCode = odbc_result($counterPassword,'counterPassword');
			if($counterSeccCode < 3)
			{
					$cekExec = odbc_execute($cek = odbc_prepare($conn, "select count(userLogin),StatusNonSAP FROM tr_login where userLogin = ? group by StatusNonSAP"), array($NIK));
					$cekStatus = odbc_result($cek,'StatusNonSAP');
					if(odbc_result($cek,1) > 0 && $cekStatus!='1')
					{
							
							$sql = "select userLogin,convert(varchar(16),decryptbypassphrase(userLogin,password)),convert(varchar(16),decryptbypassphrase(userLogin,generatedPassword)),isActive, lock, email, phoneNumber, StatusNonSAP FROM tr_login where userLogin = ?";


							$queryExec = odbc_execute($query = odbc_prepare($conn, $sql), array($NIK));
							$password = odbc_result($query,2);
							$isActive = odbc_result($query,4);
							$isLock = odbc_result($query,5);
							$emailSAP = odbc_result($query,6);
							$hpSAP = odbc_result($query,7);
							$_SESSION['NoSAP'] = odbc_result($query,8);
							
							saprfc_import ($fce1,"FI_PERNR",$NIK);
							$rfc_rc = saprfc_call_and_receive ($fce1);
							$TEMPAT = saprfc_export ($fce1,"TEMPAT");
							$TGLLAHIR = saprfc_export ($fce1,"TGLLAHIR");
							if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) ; else $errorMessage = "An Error Occured, Please Try Again Later."; exit; }
							$rown = saprfc_table_rows ($fce1,"FI_CV");
							saprfc_function_free($fce1);

							// var_dump($TEMPAT, $TGLLAHIR, $isActive, $password, $isLock );

							if($TEMPAT != "" && $TGLLAHIR != "00000000" and $isActive==1 and $PW==$password and $isLock==0)
							{
									//cek apakah email dan hp nya berubah?
									$fce = saprfc_function_discover($rfc,"ZHRFM_HP_EMAIL");
									if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
									//Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
									saprfc_import ($fce,"FI_PERNR",$NIK);
									saprfc_import ($fce,"FI_PERNR_DIAKSES",$NIK);
									//Fill internal tables
									saprfc_table_init ($fce,"FI_HPEMAIL");
									//Do RFC call of function ZHRFM_HP_EMAIL, for handling exceptions use saprfc_exception()
									$rfc_rc = saprfc_call_and_receive ($fce);
									if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
									//Retrieve export parameters
									$AUTORISASI = saprfc_export ($fce,"AUTORISASI");
									$FI_EMAIL = saprfc_export ($fce,"FI_EMAIL");
									$FI_TELP = saprfc_export ($fce,"FI_TELP");

									if(!empty($emailSAP) || !empty($hpSAP))
									{
										if (empty($FI_EMAIL) || $FI_EMAIL =='' || strpos($FI_EMAIL, '@') === FALSE || empty($FI_TELP) || $FI_TELP =='')
										{
											if($FI_EMAIL!=strtoupper($emailSAP) || $FI_TELP!=strtoupper($hpSAP))
											{
												//insert email dan phone ke SAP
												$fce = saprfc_function_discover($rfc,"ZHRFM_CHANGE0105");
												if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
												//It's possible to define interface manually. If you would like do it, uncomment following lines:
												//Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
												saprfc_import ($fce,"FI_AKSI","1");
												saprfc_import ($fce,"FI_PERNR",$NIK);
												saprfc_import ($fce,"GROUP","");
												saprfc_import ($fce,"HOLDDATE","");
												saprfc_import ($fce,"KEEP","");
												saprfc_import ($fce,"USER","");
												//Fill internal tables
												saprfc_table_init ($fce,"FI_INPUT");
												saprfc_table_append ($fce,"FI_INPUT", array ("FIELDNAME"=>"BEGDA","FIELDCONTENT"=>date("dmY"),"FILTERNAME"=>"","FILTERVALUE"=>""));
												saprfc_table_append ($fce,"FI_INPUT", array ("FIELDNAME"=>"USRID","FIELDCONTENT"=>$hpSAP,"FILTERNAME"=>"USRTY","FILTERVALUE"=>"0098"));
												saprfc_table_append ($fce,"FI_INPUT", array ("FIELDNAME"=>"USRID_LONG","FIELDCONTENT"=>$emailSAP,"FILTERNAME"=>"USRTY","FILTERVALUE"=>"0099"));

												saprfc_table_init ($fce,"MESSTAB");
												//Do RFC call of function ZHRFM_CHANGE0002, for handling exceptions use saprfc_exception()
												$rfc_rc = saprfc_call_and_receive ($fce);
												if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
												//Retrieve export parameters
												$SUBRC = saprfc_export ($fce,"SUBRC");
												$rows = saprfc_table_rows ($fce,"FI_INPUT");
												for ($i=1;$i<=$rows;$i++)
													$FI_INPUT[] = saprfc_table_read ($fce,"FI_INPUT",$i);
												$rows = saprfc_table_rows ($fce,"MESSTAB");
												for ($i=1;$i<=$rows;$i++)
													$MESSTAB[] = saprfc_table_read ($fce,"MESSTAB",$i);

												if($SUBRC != 0)
												{
													$errorMessage = "Failed insert to SAP.";
													$tagLogin=2;

												}

												//saprfc_function_debug_info($fce);
												saprfc_function_free($fce);
											}
										}
										else
										{
											//update ke table tr_login
		 									$sqlUpdateEmailHP = "update tr_login set email=?, phoneNumber=? where userLogin=?";
											$queryUpdateMailHP = odbc_execute(odbc_prepare($conn, $sqlUpdateEmailHP), array($FI_EMAIL, $FI_TELP, $NIK));
										}
									}
									else
									{
										if($FI_EMAIL!='' && strpos($FI_EMAIL, '@') === TRUE  && $FI_TELP!='')
										{
											//update ke table tr_login
											$sqlUpdateEmailHP = "update tr_login set email=?, phoneNumber=? where userLogin=?";
											$queryUpdateMailHP = odbc_execute(odbc_prepare($conn, $sqlUpdateEmailHP), array($FI_EMAIL, $FI_TELP, $NIK));
										}
									}


									$_SESSION['nik'] = base64_encode($NIK);
									$_SESSION['auth_hrportal'] = 1;
								
									//unset niklogin
									unset($_SESSION['niklogin']);
									unset($_SESSION['counterPassword']);

									$errorMessage = "Login Success.";
									//update counterPassword dan Lock
									$updateCounter="update tr_login set counterPassword=NULL, lock='false' where userLogin=?";		
									$execUpdateCounter = odbc_execute(odbc_prepare($conn, $updateCounter), array($NIK));
									$history_login = odbc_execute(odbc_prepare($conn, "update tr_login set lastSessionID = ?,lastLoginTime = ? where userLogin = ?"), array(session_id(), date('Y-m-d H:i:s'), $NIK));

									 // cek BPJS DIMAS
									
									$queryactiveBPJSExec=odbc_execute($queryactiveBPJS = odbc_prepare($conn,"SELECT Active FROM tb_Bpjs WHERE NIK=? AND PISA=?"), array($NIK, 1));
									$statusBPJS=odbc_result($queryactiveBPJS,1);

									/*if (check_survey($rfc,$conn_eSurvey,$NIK)) {
										echo "<script>window.location='redirect.php?to=esurvey'</script>";
									} else {*/
										echo "<script>window.location='home.php'</script>";
									// }

									// if($statusBPJS=="1")
									// {
										// var_dump('2',check_survey($rfc,$conn_eSurvey,$NIK));
										// echo "<script>window.location='home.php'</script>";
									// }
									// else{
									// 	echo "<script>window.location='BPJSadd.php'</script>";
									// }
									 
							} 
							else 
							{
									$queryCounter="select counterPassword, lock from tr_login where userLogin=?";
									//cek dulu sudah berapa kali salah login
									$cekCounterLockExec = odbc_execute($cekCounterLock = odbc_prepare($conn, $queryCounter), array($NIK));
									$resultCounterPass = odbc_result($cekCounterLock,'counterPassword');
									$resultCounterLock = odbc_result($cekCounterLock,'lock');

									if($isActive==1)
									{	
										//jika counterlock tidak sama dengan 5 dan isActive sama dengan true dan lock tidak terkunci maka
										//akan update counterlock
										if($resultCounterPass!='5' or $resultCounterPass==NULL and $isActive==1 and $resultCounterLock==0)
										{
											$counterPassword=htmlentities($resultCounterPass+1);
											if($counterPassword=='5')
											{
												//update counter password
												$updateCounter="update tr_login set counterPassword=?, lock='true' where userLogin=?";	
											}
											elseif($counterPassword=='4')
											{
												$updateCounter="update tr_login set counterPassword=? where userLogin=?";		
												$errorMessageLock='Warning. Your account will be blocked, if you do not enter your NIK & Password correctly.';
												echo "<script>alert('".$errorMessageLock."');</script>";
												$errorMessage = "You have 4 wrong password, Becarefull. Your account will be blocked, if you do not enter your NIK & Password correctly.";
												$tagLogin=4;
																				// echo "<script>window.location='index.php'</script>";
											}
											else
											{
												$updateCounter="update tr_login set counterPassword=? where userLogin=?";		
											}

											$execUpdateCounter = odbc_execute(odbc_prepare($conn, $updateCounter), array($counterPassword, $NIK));

										}
										else if($resultCounterPass=='5' and $isActive==1 and $resultCounterLock==1)
										{
												$errorMessageLock='Your NIK has been locked. Please forgot password.';
												echo "<script>alert('".$errorMessageLock."');</script>";
																				// echo "<script>window.location='index.php'</script>";
										}
									}

									
									if(isset($_SESSION['niklogin'])!='')
									{
										if($_SESSION['niklogin']==$NIK)
										{
											$_SESSION['counterPassword']++;
										}
										else
										{
											$_SESSION['counterPassword']=1;
											$_SESSION['niklogin']=$NIK;
										}
									}
									//echo "<script>alert('".$NIK."');</script>";
									//echo "<script>alert('".$_SESSION['counterPassword']."');</script>";

									$errorMessage='Login Failed. Please check your Username or Password. 
										      If you forget your password, use <a href="0-forgot.php?KeepThis=true&TB_iframe=true&height=500&width=660" class="thickbox">Forgot password</a>';
									echo "<script>alert('".$errorMessage."');</script>";
																	// echo "<script>window.location='index.php'</script>";
							}
						
					}
					else if(odbc_result($cek,1) > 0 && $cekStatus=='1')
					{
							$sqlSAP = "select userLogin,convert(varchar(16),decryptbypassphrase(userLogin,password)),convert(varchar(16),decryptbypassphrase(userLogin,generatedPassword)),isActive, lock FROM tr_login where userLogin = ?";
							$queryExec = odbc_execute($query = odbc_prepare($conn, $sqlSAP), array($NIK));
							$password = odbc_result($query,2);
							$isActive = odbc_result($query,4);
							$isLock = odbc_result($query,5);
							
							if($PW == $password and $isLock==0) 
							{
								$errorMessage = "Login Success.";
								$_SESSION['nik'] = base64_encode($NIK);
								$_SESSION['chpass'] = 1;
								$_SESSION['NoSAP']  = 1;
								$sqlSession = "update tr_login set lastSessionID =? where userLogin =  ?";
								odbc_execute(odbc_prepare($conn, $sqlSession), array(session_id(), $NIK));

								$_SESSION['auth_hrportal'] = 1;

								$errorMessage = "Login Success.";
								//update counterPassword dan Lock
								$updateCounter="update tr_login set counterPassword=NULL, lock='false' where userLogin=?";		
								$execUpdateCounter = odbc_execute(odbc_prepare($conn, $updateCounter), array($NIK));
								$history_login = odbc_execute(odbc_prepare($conn, "update tr_login set lastSessionID = ?,lastLoginTime = ? where userLogin = ?"), array(session_id(), date('Y-m-d H:i:s'), $NIK));
								
								echo "<script>window.location='home-nonsap.php'</script>";
								///////////////////bbbbb
								
							}
							else
							{
								$queryCounter="select counterPassword, lock from tr_login where userLogin=?";
								//cek dulu sudah berapa kali salah login
								$cekCounterLockExec = odbc_execute($cekCounterLock = odbc_prepare($conn, $queryCounter), array($NIK));
								$resultCounterPass = odbc_result($cekCounterLock,'counterPassword');
								$resultCounterLock = odbc_result($cekCounterLock,'lock');

								//jika counterlock tidak sama dengan 5 dan isActive sama dengan true dan lock tidak terkunci maka
								//akan update counterlock
								if($resultCounterPass!='5' or $resultCounterPass==NULL and $isActive==1 and $resultCounterLock==0)
								{
									$counterPassword=htmlentities($resultCounterPass+1);
									if($counterPassword=='5')
									{
										//update counter password
										$updateCounter="update tr_login set counterPassword=?, lock='true' where userLogin=?";	
									}
									elseif($counterPassword=='4')
									{
										$updateCounter="update tr_login set counterPassword=? where userLogin=?";		
										$errorMessageLock='Becarefull. Your account will be blocked, if you do not enter your NIK & Password correctly.';
										echo "<script>alert('".$errorMessageLock."');</script>";
																		// echo "<script>window.location='index.php'</script>";
									}
									else
									{
										$updateCounter="update tr_login set counterPassword=? where userLogin=?";		
									}

									$execUpdateCounter = odbc_execute(odbc_prepare($conn, $updateCounter),array($counterPassword, $NIK));

								}
								else if($resultCounterPass=='5' and $isActive==1 and $resultCounterLock==1)
								{
										$errorMessageLock='Your NIK has been locked. Please forgot password.';
										echo "<script>alert('".$errorMessageLock."');</script>";
																		// echo "<script>window.location='index.php'</script>";
								}
								

								$errorMessage='Login Failed. Please check your Username or Password.';
								echo "<script>alert('".$errorMessage."');</script>";
																// echo "<script>window.location='index.php'</script>";
							}
					
					}
					else 
					{
							$errorMessage='Login Failed. Please check your Username or Password.';
							echo "<script>alert('".$errorMessage."');</script>";
															// echo "<script>window.location='index.php'</script>";
					}
				
			}
			else
			{
				$counterPassword2Exec = odbc_execute($counterPassword2 = odbc_prepare($conn, "select counterPassword FROM tr_login where userLogin = ?"), array($NIK));
				$counterSeccCode2 = odbc_result($counterPassword2,'counterPassword');
				//echo "<script>alert('".$counterSeccCode2."');</script>";
				$_SESSION['niklogin']=$NIK;
				if($counterSeccCode2 = 3 && $_POST['security_code_logon']=='')
				{
					$errorMessage = "You have 3 wrong password, please fill securityCode.";
					$tagLogin=3;
				}
				else
				{

					//cek securityCode
					var_dump($_SESSION['security_code']);
					// echo "<br>";
					// var_dump($_POST['security_code_logon']);
					if($_SESSION['security_code'] != $_POST['security_code_logon'] && empty($_SESSION['security_code_logon']))
					{
						$errorMessage = "Sorry! Invalid Security Code.";
						$tagLogin=2;
					}
					else
					{
						$cekExec = odbc_execute($cek = odbc_prepare($conn, "select count(userLogin),StatusNonSAP FROM tr_login where userLogin = ? group by StatusNonSAP"), array($NIK));
						$cekStatus = odbc_result($cek,'StatusNonSAP');
						if(odbc_result($cek,1) > 0 && $cekStatus!='1')
						{
								
								$sql = "select userLogin,convert(varchar(16),decryptbypassphrase(userLogin,password)),convert(varchar(16),decryptbypassphrase(userLogin,generatedPassword)),isActive, lock, email, phoneNumber FROM tr_login where userLogin = ?";
								$queryExec = odbc_execute($query = odbc_prepare($conn, $sql), array($NIK));
								$password = odbc_result($query,2);
								$isActive = odbc_result($query,4);
								$isLock = odbc_result($query,5);
								$emailSAP = odbc_result($query,6);
								$hpSAP = odbc_result($query,7);
								
								saprfc_import ($fce1,"FI_PERNR",$NIK);
								$rfc_rc = saprfc_call_and_receive ($fce1);
								$TEMPAT = saprfc_export ($fce1,"TEMPAT");
								$TGLLAHIR = saprfc_export ($fce1,"TGLLAHIR");
								if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) ; else $errorMessage = "An Error Occured, Please Try Again Later."; exit; }
								$rown = saprfc_table_rows ($fce1,"FI_CV");
								// saprfc_function_free($fce);
								if($TEMPAT != "" && $TGLLAHIR != "00000000" and $isActive==1 and $PW==$password and $isLock==0)
								{
									//cek apakah email dan hp nya berubah?
									$fce = saprfc_function_discover($rfc,"ZHRFM_HP_EMAIL");
									if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
									//Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
									saprfc_import ($fce,"FI_PERNR",$NIK);
									saprfc_import ($fce,"FI_PERNR_DIAKSES",$NIK);
									//Fill internal tables
									saprfc_table_init ($fce,"FI_HPEMAIL");
									//Do RFC call of function ZHRFM_HP_EMAIL, for handling exceptions use saprfc_exception()
									$rfc_rc = saprfc_call_and_receive ($fce);
									if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
									//Retrieve export parameters
									$AUTORISASI = saprfc_export ($fce,"AUTORISASI");
									$FI_EMAIL = saprfc_export ($fce,"FI_EMAIL");
									$FI_TELP = saprfc_export ($fce,"FI_TELP");
									
									if(!empty($emailSAP) || !empty($hpSAP))
									{
										if (empty($FI_EMAIL) || $FI_EMAIL =='' || strpos($FI_EMAIL, '@') === FALSE || empty($FI_TELP) || $FI_TELP =='')
										{
											if($FI_EMAIL!=strtoupper($emailSAP) || $FI_TELP!=strtoupper($hpSAP))
											{
												//insert email dan phone ke SAP
												$fce = saprfc_function_discover($rfc,"ZHRFM_CHANGE0105");
												if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
												//It's possible to define interface manually. If you would like do it, uncomment following lines:
												//Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
												saprfc_import ($fce,"FI_AKSI","1");
												saprfc_import ($fce,"FI_PERNR",$NIK);
												saprfc_import ($fce,"GROUP","");
												saprfc_import ($fce,"HOLDDATE","");
												saprfc_import ($fce,"KEEP","");
												saprfc_import ($fce,"USER","");
												//Fill internal tables
												saprfc_table_init ($fce,"FI_INPUT");
												saprfc_table_append ($fce,"FI_INPUT", array ("FIELDNAME"=>"BEGDA","FIELDCONTENT"=>date("dmY"),"FILTERNAME"=>"","FILTERVALUE"=>""));
												saprfc_table_append ($fce,"FI_INPUT", array ("FIELDNAME"=>"USRID","FIELDCONTENT"=>$hpSAP,"FILTERNAME"=>"USRTY","FILTERVALUE"=>"0098"));
												saprfc_table_append ($fce,"FI_INPUT", array ("FIELDNAME"=>"USRID_LONG","FIELDCONTENT"=>$emailSAP,"FILTERNAME"=>"USRTY","FILTERVALUE"=>"0099"));

											
												saprfc_table_init ($fce,"MESSTAB");
												//Do RFC call of function ZHRFM_CHANGE0002, for handling exceptions use saprfc_exception()
												$rfc_rc = saprfc_call_and_receive ($fce);
												if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
												//Retrieve export parameters
												$SUBRC = saprfc_export ($fce,"SUBRC");
												$rows = saprfc_table_rows ($fce,"FI_INPUT");
												for ($i=1;$i<=$rows;$i++)
													$FI_INPUT[] = saprfc_table_read ($fce,"FI_INPUT",$i);
												$rows = saprfc_table_rows ($fce,"MESSTAB");
												for ($i=1;$i<=$rows;$i++)
													$MESSTAB[] = saprfc_table_read ($fce,"MESSTAB",$i);

												if($SUBRC != 0)
												{
													$errorMessage = "Failed insert to SAP.";
													$tagLogin=2;

												}

												//saprfc_function_debug_info($fce);
												saprfc_function_free($fce);

											}
										}
										else
										{
											//update ke table tr_login
											$sqlUpdateEmailHP = "update tr_login set email=?, phoneNumber=? where userLogin=?";
											$queryUpdateMailHP = odbc_execute(odbc_prepare($conn, $sqlUpdateEmailHP), array($FI_EMAIL, $FI_TELP, $NIK));
										}
									}
									else
									{
										if($FI_EMAIL!='' && strpos($FI_EMAIL, '@') === TRUE && $FI_TELP!='')
										{
											//update ke table tr_login
											$sqlUpdateEmailHP = "update tr_login set email=?, phoneNumber=? where userLogin=?";
											$queryUpdateMailHP = odbc_execute(odbc_prepare($conn, $sqlUpdateEmailHP), array($FI_EMAIL, $FI_TELP, $NIK));
										}
									}

									$_SESSION['nik'] = base64_encode($NIK);
									$_SESSION['chpass'] = 1;
									$_SESSION['auth_hrportal'] = 1;
									

									//unset niklogin
									unset($_SESSION['niklogin']);
									unset($_SESSION['counterPassword']);

								
									$errorMessage = "Login Success.";
									//update counterPassword dan Lock
									$updateCounter="update tr_login set counterPassword=NULL, lock='false' where userLogin=?";		
									$execUpdateCounter = odbc_execute(odbc_prepare($conn, $updateCounter), array($NIK));
									$history_login = odbc_execute(odbc_prepare($conn, "update tr_login set lastSessionID = ?,lastLoginTime = ? where userLogin = ?"), array(session_id(), date('Y-m-d H:i:s'), $NIK));
									
									
									// cek BPJS DIMAS
									
									$queryactiveBPJSExec=odbc_execute($queryactiveBPJS = odbc_prepare($conn,"SELECT Active FROM tb_Bpjs WHERE NIK=? AND PISA=?"), array($NIK, 1));
									$statusBPJS=odbc_result($queryactiveBPJS,1);

									/*if (check_survey($rfc,$conn_eSurvey,$NIK)) {
										echo "<script>window.location='redirect.php?to=esurvey'</script>";
									} else {*/
										echo "<script>window.location='home.php'</script>";
									// }

									// if($statusBPJS=="1")
									// {
										// var_dump('3',check_survey($rfc,$conn_eSurvey,$NIK));
										// echo "<script>window.location='home.php'</script>";
									// }
									// else{
									// 	echo "<script>window.location='BPJSadd.php'</script>";
									// }

									} 
									else 
									{
										$queryCounter="select counterPassword, lock from tr_login where userLogin=?";
										//cek dulu sudah berapa kali salah login
										$cekCounterLockExec = odbc_execute($cekCounterLock = odbc_prepare($conn, $queryCounter), array($NIK));
										$resultCounterPass = odbc_result($cekCounterLock,'counterPassword');
										$resultCounterLock = odbc_result($cekCounterLock,'lock');

										if($isActive==1)
										{	
											//jika counterlock tidak sama dengan 5 dan isActive sama dengan true dan lock tidak terkunci maka
											//akan update counterlock
											if($resultCounterPass!='5' or $resultCounterPass==NULL and $isActive==1 and $resultCounterLock==0)
											{
												$counterPassword=htmlentities($resultCounterPass+1);
												if($counterPassword=='5')
												{
													//update counter password
													$updateCounter="update tr_login set counterPassword=?, lock='true' where userLogin=?";	
												}
												elseif($counterPassword=='4')
												{
													$updateCounter="update tr_login set counterPassword=? where userLogin=?";		
													$errorMessage = "You have 4 wrong password, Becarefull. Your account will be blocked, if you do not enter your NIK & Password correctly.";
													$tagLogin=4;
													$errorMessageLock='Warning. Your account will be blocked, if you do not enter your NIK & Password correctly.';
													echo "<script>alert('".$errorMessageLock."');</script>";
																					// echo "<script>window.location='index.php'</script>";
												}
												else
												{
													$updateCounter="update tr_login set counterPassword=? where userLogin=?";		
												}

												$execUpdateCounter = odbc_execute(odbc_prepare($conn, $updateCounter), array($counterPassword, $NIK));

											}
											else if($resultCounterPass=='5' and $isActive==1 and $resultCounterLock==1)
											{
													$errorMessageLock='Your NIK has been locked. Please forgot password.';
													echo "<script>alert('".$errorMessageLock."');</script>";
																					// echo "<script>window.location='index.php'</script>";
											}
										}

										$errorMessage='Login Failed. Please check your Username or Password.';
										echo "<script>alert('".$errorMessage."');</script>";
																		// echo "<script>window.location='index.php'</script>";
									}
							
						}
						else if(odbc_result($cek,1) > 0 && $cekStatus=='1')
						{

								$sqlSAP = "select userLogin,convert(varchar(16),decryptbypassphrase(userLogin,password)),convert(varchar(16),decryptbypassphrase(userLogin,generatedPassword)),isActive, lock FROM tr_login where userLogin = ?";
								$queryExec = odbc_execute($query = odbc_prepare($conn, $sqlSAP), array($NIK));
								$password = odbc_result($query,2);
								$isActive = odbc_result($query,4);
								$isLock = odbc_result($query,5);
								
								if($PW == $password and $isLock==0) 
								{
									$errorMessage = "Login Success.";
									$_SESSION['nik'] = base64_encode($NIK);
									$_SESSION['chpass'] = 1;
									$_SESSION['NoSAP']  = 1;
									$_SESSION['auth_hrportal'] = 1;
									$sqlSession = "update tr_login set lastSessionID =? where userLogin =  ?";
									odbc_execute(odbc_prepare($conn, $sqlSession), array(session_id(), $NIK));

									$errorMessage = "Login Success.";
									//update counterPassword dan Lock
									$updateCounter="update tr_login set counterPassword=NULL, lock='false' where userLogin=?";		
									$execUpdateCounter = odbc_execute(odbc_prepare($conn, $updateCounter), array($NIK));
									$history_login = odbc_execute(odbc_prepare($conn, "update tr_login set lastSessionID = ?,lastLoginTime = ? where userLogin = ?"), array(session_id(), date('Y-m-d H:i:s'), $NIK));

								}
								else
								{
									$queryCounter="select counterPassword, lock from tr_login where userLogin=?";
									//cek dulu sudah berapa kali salah login
									$cekCounterLockExec = odbc_execute($cekCounterLock = odbc_prepare($conn, $queryCounter), array($NIK));
									$resultCounterPass = odbc_result($cekCounterLock,'counterPassword');
									$resultCounterLock = odbc_result($cekCounterLock,'lock');

									//jika counterlock tidak sama dengan 5 dan isActive sama dengan true dan lock tidak terkunci maka
									//akan update counterlock
									if($resultCounterPass!='5' or $resultCounterPass==NULL and $isActive==1 and $resultCounterLock==0)
									{
										$counterPassword=htmlentities($resultCounterPass+1);
										if($counterPassword=='5')
										{
											//update counter password
											$updateCounter="update tr_login set counterPassword=?, lock='true' where userLogin=?";	
										}
										elseif($counterPassword=='4')
										{
											$updateCounter="update tr_login set counterPassword=? where userLogin=?";		
											$errorMessage = "You have 4 wrong password, Becarefull. Your account will be blocked, if you do not enter your NIK & Password correctly.";
											$tagLogin=4;
											
											// 								// echo "<script>window.location='index.php'</script>";
										}
										else
										{
											$updateCounter="update tr_login set counterPassword=? where userLogin=?";		
										}

										$execUpdateCounter = odbc_execute(odbc_prepare($conn, $updateCounter), array($counterPassword, $NIK));

									}
									else if($resultCounterPass=='5' and $isActive==1 and $resultCounterLock==1)
									{
											$errorMessageLock='Your NIK has been locked. Please forgot password.';
											echo "<script>alert('".$errorMessageLock."');</script>";
											// 								// echo "<script>window.location='index.php'</script>";
									}
									

									$errorMessage='Login Failed. Please check your Username or Password.';
									echo "<script>alert('".$errorMessage."');</script>";
									// 								// echo "<script>window.location='index.php'</script>";
								}
						
						}
						else 
						{
								$errorMessage='Login Failed. Please check your Username or Password.';
								echo "<script>alert('".$errorMessage."');</script>";
								// 								// echo "<script>window.location='index.php'</script>";
						}
					}
				}
			}
	}
}
	
$_SESSION['login_tag'] = $tagLogin;
$_SESSION['login_msg']= $errorMessage;
// 								// echo "<script>window.location='index.php'</script>";
include "include/sapdisconnect.php";

?>
