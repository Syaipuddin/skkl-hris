<?php
ini_set('max_execution_time', '3600');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
	$pageTitle="Home";
	include "template/top2.php"; //Load template pembuka dan load css eksternal
	include "include/date_lib.php";
	include	"language/Home_library_word.php";
        include "include/enkripsiNIK.php";


function get_cv_mini($NIK,$conn,$rfc)
{
	if (extension_loaded('newrelic')) { // Ensure PHP agent is available
		// var_dump("masuk");
		$new_relic = newrelic_add_custom_tracer("skkl_save_and_notif_function");
		$change_name = newrelic_name_transaction("/skklCreateProses.php/skkl_save_and_notif_function");
		/*var_dump("new_relic", $new_relic);
		var_dump("change_name", $change_name);*/
		newrelic_end_transaction(); // stop recording the current transaction
		newrelic_start_transaction(ini_get("newrelic.appname"));
	}

	$tr_login = odbc_prepare($conn, "SELECT * FROM tr_login WHERE userLogin = ?");
	odbc_execute($tr_login, array($NIK));
	//$flag_EmTelp = odbc_result($tr_login, "flagEmtelpUpd");

	/*if (($flag_EmTelp == null || $flag_EmTelp == '')) {
		echo "<script>alert('Please Update your Email and Phone Number first!');window.location='home_update_identity.php';</script>";
	}*/

	$todayDate_SAPFormat = date("Ymd");

	//start of ZHRFM_CV_MINI
	$fce = saprfc_function_discover($rfc,"ZHRFM_CV_MINI");
	if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
	saprfc_import ($fce,"FI_PERNR",$NIK);
	saprfc_import ($fce,"FI_PERNR_DIAKSES",$NIK);
	saprfc_import ($fce,"FI_TANGGAL",$todayDate_SAPFormat);
	saprfc_table_init ($fce,"FI_CV");
	$rfc_rc = saprfc_call_and_receive ($fce);
	if ($rfc_rc != SAPRFC_OK){
		if ($rfc == SAPRFC_EXCEPTION ) {
			echo "System Errorh1. Please Try Again Later.";
		}else {
			echo "System Errorh2. Please Try Again Later."; exit;
		}
	}
	$rows = saprfc_table_rows ($fce,"FI_CV");

	if($rows=='')
	{
		$MiniProfile ='';
		$userName = '';
		$position =  '';
		$persadmin =  '';
	}else
	{
		$MiniProfile = saprfc_table_read ($fce,"FI_CV",1);
		$userName = ucwords(strtolower($MiniProfile["NAMALENGKAP"]));
		$position =  $MiniProfile["POSITIONS"];
		$persadmin =  $MiniProfile["PERSADMIN"];
	}

	return array("MiniProfile"=>$MiniProfile,"userName"=>$userName,"position"=>$position,"persadmin"=>$persadmin,"todayDate_SAPFormat"=>$todayDate_SAPFormat);
}

$data_cv_mini = get_cv_mini($NIK,$conn,$rfc);
//$nama_panggilan = $MiniProfile["NAMAPANGGILAN"];
//end of ZHRFM_CV_MINI

//echo $MiniProfile['TTL'];
				function get_listsisacuti($NIK,$rfc)
				{
					if (extension_loaded('newrelic')) { // Ensure PHP agent is available
						// var_dump("masuk");
						$new_relic = newrelic_add_custom_tracer("get_listsisacuti");
						$change_name = newrelic_name_transaction("/home.php/get_listsisacuti");
						/*var_dump("new_relic", $new_relic);
						var_dump("change_name", $change_name);*/
						newrelic_end_transaction(); // stop recording the current transaction
						newrelic_start_transaction(ini_get("newrelic.appname"));
					}
					//start of saldo cuti
					$fce = saprfc_function_discover($rfc,"ZHRFM_LISTSISACUTI");
					if (! $fce ){
						echo "System Error. Please Try Again Later."; exit;
					}
					saprfc_import ($fce,"FI_PERNR",$NIK);
					saprfc_table_init ($fce,"FI_SALDO");
					$rfc_rc = saprfc_call_and_receive ($fce);
					if ($rfc_rc != SAPRFC_OK){
						if ($rfc == SAPRFC_EXCEPTION )
							echo "System Error. Please Try Again Later.";
						else
							echo "System Error. Please Try Again Later."; exit;
					}
					$rows = saprfc_table_rows ($fce,"FI_SALDO");
					if($rows != 0){
						$FI_SALDO = saprfc_table_read ($fce,"FI_SALDO",1);
						if($FI_SALDO['CUTAH']==""){
							$cutiTahun = 0;
						} else {
							$cutiTahun = $FI_SALDO['CUTAH'];
						}
						if($FI_SALDO['CUBES']==""){
							$cutiBesar = 0;
						} else {
							$cutiBesar = $FI_SALDO['CUBES'];
						}
					}

					return $arrayName = array('cutiTahun' => $cutiTahun, 'cutiBesar' => $cutiBesar);
				}
				$data_list_sisa_cuti = get_listsisacuti($NIK,$rfc);
//end of saldo cuti


function get_listtime($NIK,$rfc,$todayDate_SAPFormat)
{
	if (extension_loaded('newrelic')) { // Ensure PHP agent is available
		// var_dump("masuk");
		$new_relic = newrelic_add_custom_tracer("get_listtime");
		$change_name = newrelic_name_transaction("/home.php/get_listtime");
		/*var_dump("new_relic", $new_relic);
		var_dump("change_name", $change_name);*/
		newrelic_end_transaction(); // stop recording the current transaction
		newrelic_start_transaction(ini_get("newrelic.appname"));
	}

	//start of time event
	$fce = saprfc_function_discover($rfc,"ZHRFM_LIST_TIME");
	if (! $fce )
	{
		echo "System Error. Please Try Again Later."; exit;
	}
	$endDate= date("Ymd", mktime(0, 0, 0, date("m"),date("d")-4,date("Y")));
	saprfc_import ($fce,"FI_AKHIR",$todayDate_SAPFormat);
	saprfc_import ($fce,"FI_AWAL",$endDate);
	saprfc_import ($fce,"FI_PERNR",$NIK);
	saprfc_table_init ($fce,"FI_ABSEN");
	$rfc_rc = saprfc_call_and_receive ($fce);
	if ($rfc_rc != SAPRFC_OK) {
		if ($rfc == SAPRFC_EXCEPTION )
			echo "System Error. Please Try Again Later.";
		else
			echo "System Error. Please Try Again Later."; exit;
	}
	$FI_RTEXT = saprfc_export ($fce,"FI_RTEXT");
	$rowsTimeEvent = saprfc_table_rows ($fce,"FI_ABSEN");

	return array('FI_RTEXT' => $FI_RTEXT, 'rowsTimeEvent'=>$rowsTimeEvent);
}

$data_list_time = get_listtime($NIK,$rfc,$data_cv_mini['todayDate_SAPFormat']);

function get_query($conn,$NIK,$persadmin)
{
	if (extension_loaded('newrelic')) { // Ensure PHP agent is available
		// var_dump("masuk");
		$new_relic = newrelic_add_custom_tracer("get_query");
		$change_name = newrelic_name_transaction("/home.php/get_query");
		/*var_dump("new_relic", $new_relic);
		var_dump("change_name", $change_name);*/
		newrelic_end_transaction(); // stop recording the current transaction
		newrelic_start_transaction(ini_get("newrelic.appname"));
	}

	// menyembunyikan uang makan Oct s/d Dec
	$query_gku = "SELECT count(*) as val FROM ms_niktelp WHERE NIK = '$NIK' AND PersAdminText = 'GKU'";
	$is_gku = odbc_result(odbc_exec($conn,$query_gku),1);

	$sqlall="SELECT * FROM ms_EmpOrg WHERE NIK='00".$NIK."'";
	$queryall = odbc_exec($conn,$sqlall);
	$TTL=str_replace('-', '',substr(odbc_result($queryall, 'TTL'),5,5));

	$query_unit="SELECT Unit, NAMAPANGGILAN, Nama, Positions, PersAdmin FROM ms_niktelp WHERE NIK='$NIK'";
	$get_unit = odbc_exec($conn,$query_unit);
	$emp_unit = odbc_result($get_unit, 'Unit');
	$nama_panggilan = odbc_result($get_unit, 'NAMAPANGGILAN');
	$nama = odbc_result($get_unit, 'Nama');
	$positions = odbc_result($get_unit, 'Positions');
	$persAdmin = odbc_result($get_unit, 'PersAdmin');
	
	$qry_persadmin_parent = "SELECT TOP 1 PersAdmin_parent FROM HRSS_PersAdmin WHERE PersAdmin_Id = '$persadmin' ORDER BY PersAdmin_parent DESC";
	$persadmin_parent = odbc_result(odbc_exec($conn,$qry_persadmin_parent),'PersAdmin_parent');
	
	$sql_query="SELECT convert(varchar(16),decryptbypassphrase(userLogin,password)) as password FROM tr_login where userLogin ='$NIK'";
	$md5_password = md5(odbc_result(odbc_exec($conn, $sql_query),1));

	return $data = array('is_gku' => $is_gku, 'TTL' => $TTL,'emp_unit' => $emp_unit,'nama_panggilan' => $nama_panggilan,'nama_lengkap'=>$nama,'positions'=>$positions,'persadmin'=>$persAdmin,'persadmin_parent' => $persadmin_parent,'md5_password' => $md5_password,);
}

$data_query = get_query($conn,$NIK,$data_cv_mini['persadmin']);

$menuFlag = (isset($menuFlag)) ? $menuFlag : 0;
// $menuFlag = 0;
$flagUltahMasaKerja = (isset($flagUltahMasaKerja)) ? $flagUltahMasaKerja : 0;
// $flagUltahMasaKerja = 0;


function view_data($conn,$conn_card,$NIK,$rfc,$md5_password,$todayDate_SAPFormat,$TTL,$nama_panggilan,$userName,$position,$emp_unit,$word,$menuFlag,$flagUltahMasaKerja,$persadmin)
{
	if (extension_loaded('newrelic')) { // Ensure PHP agent is available
		// var_dump("masuk");
		$new_relic = newrelic_add_custom_tracer("get_listtime");
		$change_name = newrelic_name_transaction("/home.php/get_listtime");
		/*var_dump("new_relic", $new_relic);
		var_dump("change_name", $change_name);*/
		newrelic_end_transaction(); // stop recording the current transaction
		newrelic_start_transaction(ini_get("newrelic.appname"));
	}
?>
<a href="http://10.10.55.25/hr1/index.php/account/login_transfer/<?php echo $NIK ?>/<?php echo $md5_password ?>/EN/<?php echo 'decree%2Fdecree'?>">Online Decree</a>
<br>
<a href="redirect.php?to=hrss&redirect=pa%2Fpermanent">Change To Permanent</a>
			<div class="row">
				<div class="span4">
					<div class="row">
						<div class="span2"><b><?php
							if (substr($todayDate_SAPFormat, 4,4)!=$TTL){
								$hour = date('H');
								if($hour <12){
									echo $word[0].', '; //Good Morning
								}elseif($hour <18){
									echo $word[1].', '; //Good Afternoon
								}elseif ($hour<24) {
									echo $word[2].', '; //Good Evening
								}
							}else{
								echo $word[4].', '; //Happy Birthday
							}
							echo $nama_panggilan. ' ';
						?></b></div>

							<div class="span2" style="text-align:right" ><?php echo '<b>'.date('D d M Y H:i') .'</b>'?></div>
					</div>
				<h2><?php echo $word[5]; //Profile ?></h2>
				<div class="row">

						<?php
						//Cari Foto dari ID Card Online
						$qry_c_photo = "SELECT count(*) as count FROM id_card_online WHERE nik = '$NIK'";
						$c_photo     = odbc_result(odbc_exec($conn_card, $qry_c_photo,1), 'count');
						if ($c_photo == 1) {
							$photo ='';
							$qry_photo = "SELECT old_photo_path,path_photo FROM id_card_online WHERE nik = '$NIK'";
							$photo     = odbc_result(odbc_exec($conn_card, $qry_photo,1),'old_photo_path');
							$old_photo     = odbc_result(odbc_exec($conn_card, $qry_photo,1),'old_photo_path');

							if (is_null($photo) == TRUE or $photo == '') {
								$photo     = odbc_result(odbc_exec($conn_card, $qry_photo,1),'path_photo');

							}
							elseif($old_photo==$photo)
							{
								$photo     = odbc_result(odbc_exec($conn_card, $qry_photo,1),'path_photo');
							}
							$photo     = strtoupper(preg_replace("/\\\\/", "/", $photo));
							$from      = $_SERVER['HTTP_HOST'];
							switch ($from) {
								case 'hr.kompasgramedia.com':
								case 'hr.kompasgramedia.com:800':
								case 'hr2.kompasgramedia.com':
									// request dari internet
									$photo     = str_replace(array("P:/","./ASSETS/"), "http://idcard.kompasgramedia.com/assets/", $photo);

									break;
								case '10.10.55.7':
								case '10.10.55.5':
								case '10.10.55.16':
								case '10.10.55.67':
								case '10.9.70.30':
								case '10.9.70.30:800':
								case '10.9.70.31':
								case '10.9.70.32':
								case 'hr.kompasgramedia.co.id':
								case 'hr.kompasgramedia.co.id:800':
									// request dari intranet
									$photo     = str_replace(array("P:/","./ASSETS/"), "http://10.9.70.44/assets/", $photo);



									break;
								case '10.10.55.18':
								case '10.10.55.25':
									// request dari dev
									$photo     = str_replace(array("P:/","./ASSETS/"), "http://idcard.kompasgramedia.com/assets/", $photo);



									break;
							}
					//	 $headers = get_headers($photo);

					//	 if (substr($headers[0], 9, 3) != "200") {
					//	 	$photo = strtolower($photo);
				//		 }
						}

						?>



					<div class="span4" style="text-align:center">
						<div class="img-div" style="">
							<!-- <img src="<?php echo $photo; ?>" style=""> <br> -->

						</div>
					<br>
					<span class="name profile text-center"><?php echo $userName; ?></span><br/>
					<span class="position profile"><?php echo $position; ?></span><br/>
					<span class="unit profile"><?php echo $emp_unit; ?></span><br/>
					<?php /*if (check_access_odoo($NIK)!=1):*/ ?>
						<a href="list_profile_anjab.php" class="moreLink"><?php echo $word[6]; //View Full Profile ?></a><br/>
					<?php /*endif*/ ?>
					</div>
				</div>
				<?php
				/*		$query_count = "SELECT count(ThemeID) as count
										FROM [PORTAL].[dbo].[PoolingTheme]
										WHERE Active = 1;";
						$count = odbc_exec($conn, $query_count,1);
						$count1 = odbc_result($count, "count");

						$query_popup = "SELECT VoterNIK
										FROM [PORTAL].[dbo].[PoolingVoterHeader];";
										$popup = odbc_exec($conn, $query_popup);
										$sudahikut = false;
										while(odbc_fetch_row($popup)){
											if($NIK == odbc_result($popup, "VoterNIK")){
												$sudahikut = true;
												break;
											}
										}
						if($sudahikut == false && $count1 == 1){
				*/
						?>
				<!--			<div id="floatdiv" style="
							position:relative;
							width:265px;height:50px;top:10px;right:10px;
							padding:16px;background:#FFFFFF;
							border:2px solid #2266AA;
							z-index:100"><center>
							<?php //echo $word[7]; //Do you want to participate in HR Portal Polling ? ?>
							<br /><br /><a href="PollingQuestion.php"><input type="button" class="btn btn-primary" value="<?php //echo $word[8]; //Yes ?>"></a>
							<input type="button" class="btn btn-danger" value="<?php //echo $word[9]; //No ?>" onClick="hide();"></center>
						</div> -->
					<?php// } ?>

					<!-- LINK FAVOURITE -->
                                                <div class="accordion-group">

                                                        <div class="accordion-heading">
                                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseSix">
                                                                        <?php echo "Favourite Link" //Gallery ?>
                                                                </a>
                                                        </div>
                                                        <div id="collapseSix" class="accordion-body collapse">
                                                                <div class="accordion-inner">
                                                                         <ul>
                                                                                <li><a href="reqCuti_1.php">Leave Request</a></li>
                                                                                <li><a href="absenceRequest1.php">Absence Request<a></li>
                                                                                <li><a href="reqAttendance.php">Attendance Request</a></li>
                                                                        </ul>
                                                                    <a href="kg_university/index.html"><img src="img/kg university/button belajar yuk-02.jpg" /></a>
                                                                </div>
                                                        </div>

                                                </div>
                                        


					 <div class="accordion" id="accordion2">
						<div class="accordion-group">
							<div class="accordion-heading">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne" onClick="CallTimeEvent('home-timeevent','ajax_timeevent');"><?php echo $word[10]; //Time Event ?></a>
							</div>
							<div id="collapseOne" class="accordion-body collapse">
								<div class="accordion-inner">
									<div id="ajax_timeevent"><br /><br /><br />
									<center><img src="img/loading.gif" height="30" width="30" /><br /><h4>Please Wait . . . </h4><br /><br /><br /><br /></center>
									</div>
								</div>
							</div>
						</div><?php if (isset($menuFlag) && $menuFlag['view'][2]==1){
								$dateToday = date('Y-m-d');
								$sqlAgenda = "Select * from Agenda_V_MiniAgenda where Attendee = '$NIK' and AgendaDate ='$dateToday' order by AgendaBegin";
								$rowsAgenda = odbc_exec($conn,$sqlAgenda);
								$numAgenda = odbc_num_rows($rowsAgenda);
							?>
						<div class="accordion-group">
							<div class="accordion-heading">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseFive">
									<?php echo $word[17]; //My Agenda Today ?>
								</a>
							</div>
							<div id="collapseFive" class="accordion-body collapse">
								<div class="accordion-inner"><?php
									if($numAgenda!=0){
								?>
									<table class="table table-striped table-condensed">
										<thead>
											<tr><th><?php echo $word[18]; //Time ?></th><th><?php echo $word[19]; //Agenda ?></th><th><?php echo $word[20]; //Place ?></th></tr>
										</thead>
									<tbody>
									<?php
									while ($rowAgenda = odbc_fetch_object($rowsAgenda)){
										echo '<tr>';
										echo '<td>'.$rowAgenda->AgendaBegin.'-'.$rowAgenda->AgendaEnd.'</td>';
										echo '<td>'.$rowAgenda->Agenda.'</td>';
										echo '<td>'.$rowAgenda->EquipmentName.'</td>';
										echo '</tr>';
									}
								?></tbody></table><?php
								}else {
									echo $word[21]; //No Agenda Today.

								}
								?>

							</div>
							</div>
						</div>
						<?php } ?>


						<div class="accordion-group">
							<div class="accordion-heading">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo" onClick="CallTimeEvent('home-saldocuti','ajax_saldocuti');">
									<?php echo $word[22]; //Leave Balance ?>
								</a>
							</div>
							<div id="collapseTwo" class="accordion-body collapse">
								<div class="accordion-inner">
									<div id="ajax_saldocuti" style="height:210px"><br /><br /><br />
									<center><img src="img/loading.gif" height="30" width="30" /><br /><h4>Please Wait . . . </h4></center>
									</div>
								</div>
							</div>
						</div>
						<div class="accordion-group">
							<div class="accordion-heading">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#collapseThree" onClick="CallTimeEvent('home-mealallowance','ajax_mealallowance');">
									<?php echo $word[24]; //Meal Allowance ?>
								</a>
							</div>
							<div id="collapseThree" class="accordion-body collapse">
								<div class="accordion-inner">
									<div id="ajax_mealallowance" style="height:210px"><br /><br /><br />
                    <center><img src="img/loading.gif" height="30" width="30" /><br /><h4>Please Wait . . . </h4></center>
                  </div>
								</div>
							</div>
						</div>
						<div class="accordion-group">
							<div class="accordion-heading">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseFour">
									<?php echo $word[32]; //Gallery ?>
								</a>
							</div>
							<div id="collapseFour" class="accordion-body collapse">
								<div class="accordion-inner">
									<ul>
										<li><a href="gallery.php?album=best_employee_2011">The Best Employee 2011 </a></li>
									</ul>
								</div>
							</div>
						</div>

						<!-- LINK IKLAN -->
						<div class="accordion-group">

							<div class="accordion-heading">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseSeven">
									<?php echo "Partner Link" //Gallery ?>
								</a>
							</div>
							<div id="collapseSeven" class="accordion-body collapse">
								<div class="accordion-inner">
										<!-- <a href="0-logout.php?ref=http://simpin.gemahripah.or.id" target="_blank"><img src="img/sidebar/gemahripah.jpg" /></img></a> -->
								</div>
							</div>

						</div>
						<br>
						<style>
								.btnMyKG{
									text-align: center;
								    width: 80%;
								    font-size: 25px;
								    font-family: "Trebuchet MS", Helvetica, sans-serif
					            }
					            .btnMyKG img 
								{  
									display: inline-block;
								    vertical-align: middle;
								    /*background: #f00;*/
								    padding: 5px;
								    border-radius: 5px;
								}
								.btnMyKG span
								{
									display:inline-block;
								    vertical-align:middle;
								    color: 	#3F5D99;
								}

								.fontGaris {
								   width: 100%; 
								   text-align: center; 
								   border-bottom: 1px solid lightgrey; 
								   line-height: 0.1em;
								   margin: 10px 0 20px;
								}

								.fontGaris span { 
								    background:#fff; 
								    padding:0 30px;
								    color: grey; 
								}
							</style>
						<div class="row">
							<div class="span4" style="text-align: center;">
								<a class="btnMyKG" href="https://dev.mykg.id/login"><span><b>Go to :</b></span><br><img style="padding: 0;" src="img/mykgLOGO.png" width="150" height="150"/></a>	
							</div>
						</div>


						<?php //if($count1 == 1){ ?>
					<!--	<div class="accordion-group">
							<div class="accordion-heading">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseSix">
									<?php //echo $word[33]; //Polling Result ?>
								</a>
							</div>
							<div id="collapseSix" class="accordion-body collapse">
								<div class="accordion-inner">
									<ul>
										<?php
						/*					$query_theme = "SELECT ThemeID, ThemeText
											FROM [PORTAL].[dbo].[PoolingTheme]
											WHERE Active = 1;";
											$theme = odbc_exec($conn, $query_theme,1);
											$themeid = odbc_result($theme, "ThemeID");

											$query_display = "SELECT QuestionID
											FROM [PORTAL].[dbo].[PoolingQuestion]
											WHERE ThemeID = $themeid AND QuestionType = 1 AND Active = 1;";
											$display = odbc_exec($conn, $query_display);
											$f=1;
											$tampung = array();
											echo '<table>';
											while(odbc_fetch_row($display)){
												$tampung[$f] = odbc_result($display, "QuestionID");
												$f++;
											}
											$tampung[0] = $f;

											for($d=1; $d<$tampung[0]; $d++){
												$list .= $tampung[$d];
												if(($d+1) < $tampung[0]){
													$list .= ',';
												}
											}

											$query_info = "SELECT AnswerText, AnswerCounter
											FROM [PORTAL].[dbo].[PoolingAnswer]
											WHERE QuestionID IN ($list);";
											$c=1;
											$info = odbc_exec($conn, $query_info);
											while(odbc_fetch_row($info)){
												$_SESSION['poll'.$c] = odbc_result($info, "AnswerCounter");
												$c++;
											}

											$query_display2 = "SELECT QuestionID, DisplayNumber, DisplayQuestion
											FROM [PORTAL].[dbo].[PoolingQuestion]
											WHERE ThemeID = $themeid AND QuestionType = 1 AND Active = 1;";
											$display2 = odbc_exec($conn, $query_display2);
											$f=1;
											echo '<table>';
											while(odbc_fetch_row($display2)){
												echo '<tr><td width="15" align="left" valign="top">'.odbc_result($display2, "DisplayNumber").'.</td><td> '.odbc_result($display2, "DisplayQuestion");
												echo '</td></tr><tr><td colspan="2"><img src="Pooling/img'.$f.'.php" class="illustration"></td></tr>';
												$f++;
											}
											echo '</table>';
										*/
										?>

									</ul>
								</div>
							</div>
						</div>-->
						<?php //} ?>
					</div>
				</div>
				<div class="span8">
					<div class="well" style="padding-top:6px;padding-bottom:4px">
						<img src="img/icon/help.png" style="float:left;margin-right:15px"><p><br/><?php echo $word[34]; //Do you have any questions about HR Portal? Ask ?> <a href="HRHD_FAQList.php"><?php echo $word[35]; //here ?></a></i></p>
						<div style="clear:both"></div>
					</div>
				<?php
						$now = date("Y-m-d");
						$query_info = "SELECT * FROM [PORTAL].[dbo].[PersoInfoEvent]
						WHERE StartDate <= '$now' AND '$now' <= EndDate
						AND Deactivate = 0 AND ([All] = 1 OR Event_ID IN (SELECT Event_ID FROM PersoInfoParticipant WHERE NIK ='$NIK' AND [Active] = 1))
						ORDER BY EndDate desc;";
						$info = odbc_exec($conn, $query_info);
						$a=1;
						$tmparr = array();
						while(odbc_fetch_row($info)){
							$tmparr[$a][id] = odbc_result($info, "Event_ID");
							$tmparr[$a][title] = odbc_result($info, "Title");
							$tmparr[$a][desc] = odbc_result($info, "Description");
							$tmparr[$a][startDate] = odbc_result($info, "StartDate");
							$tmparr[$a][endDate] = odbc_result($info, "EndDate");
							$a++;
						}
					?>
					<h2><?php echo $word[36]; //Personal Info ?></h2>
					<input type="hidden" id="opernik" value="<?php echo $NIK; ?>" />
						<?php
							/*
							<div id="ajax-personalinfo">
								<div id="perso1" class="well" style="padding-top:6px;padding-bottom:4px">
									<center><label id="myButton" onclick="perso();">Refresh Info</label></center>
								</div>
								<div id="perso2" style="display:none;">
									<center><img src="img/loading.gif" height="30" width="30" /><br /><h4>Please Wait . . . </h4></center>
								</div>
							</div>
							*/

							//$pieces = substr($MiniProfile["TTL"], 4, 4);
							if($TTL == date("md")){
								echo '<div class="post"><img src="PersonalInfo/img.php" class="illustration">
									<h3 class="title">Ulang Tahun</h3>
									<p class="description">';
									$tes = 'Selamat Ulang Tahun '.$userName.' - '.str_pad($NIK, 6, "0", STR_PAD_LEFT);
									if(strlen($tes) > 300){
										$tes = substr($tes, 0,300) . ' . . .';
									}
									echo $tes . '</div><br />';
							}
							include("Perso_ZHRFM_MASA_KERJA.php");
							if($flagUltahMasaKerja){
								echo '<div class="post"><img src="PersonalInfo/img.php" class="illustration">
									<h3 class="title">Ulang Tahun Masa Kerja</h3>
									<p class="description">';
									$tes = 'Selamat Ulang Tahun Masa Kerja yang ke-'.$masaKerja;
									if(strlen($tes) > 300){
										$tes = substr($tes, 0,300) . ' . . .';
									}
									echo $tes . '</div><br />';
							}

							$now2 = date("Y-m-d", mktime(0,0,0,date("m"),1,date("Y")));
							$query_child21 = "SELECT * FROM [PORTAL].[dbo].[tb_Child21]
							WHERE InputDate = '$now2' AND FlagUltah = 1;";
							$child21 = odbc_exec($conn, $query_child21);
							while(odbc_fetch_row($child21)){
								if(odbc_result($child21, "NIK") == $NIK){
									$c1 = odbc_result($child21, "NamaKaryawan");
									$c2 = odbc_result($child21, "ChildName");
									$c3 = date("d F Y",strtotime(odbc_result($child21, "ChildBirthdate")));
									$c4 = odbc_result($child21, "ChildAge");
									echo '<div class="post"><img src="PersonalInfo/img.php" class="illustration">
										<h3 class="title"><font style="color:blue;" >Child 21</font></h3>
										<p class="description">';
										$tes = "Yth. Bpk/Ibu, $c1, Anak Saudara $c2 / $c3 saat ini genap berusia $c4 tahun.<br />Sesuai Peraturan Perusahaan tanggungan anak akan berakhir.<br />Jika masih memenuhi persyaratan ditanggung Perusahaan, harap segera konfirmasi ke HR Unit saudara dengan mengisi form Surat Pernyataan yg dpt diunduh di HR Portal.";
										if(strlen($tes) > 300){
											//$tes = substr($tes, 0,300) . ' . . .';
										}
										echo $tes . '</div><br />';
								}
							}
							$countTemp = (count($tmparr) > 3) ? 3: count($tmparr);
							for($i=1; $i<=$countTemp;$i++){
						?>
						<div class="miniPost post">
							<img src="PersonalInfo/img2.php?a=<?php echo strtoupper(substr($tmparr[$i][title],0,1));?>" class="illustration">
							<h3 class="title"><a href="PersonalInfoMore.php?edit=<?php echo $tmparr[$i][id]; ?>" class="titleLink"><?php echo $tmparr[$i][title]; ?></a></h3>
							<p class="description"><?php $tes2 = $tmparr[$i][desc];
							if(strlen($tes2) > 110){
								$tes2 = substr($tes2, 0,110) . ' . . .';
							}
							echo $tes2 . ' ';
							?><a href="PersonalInfoMore.php?edit=<?php echo $tmparr[$i][id]; ?>" class="moreLink"> <?php echo $word[37]; //See Details ?></a></p>
						</div>
						<?php
							}
							for($i=4; $i<=count($tmparr);$i++){
						?>
						<dl>
							<dt><a href="" class="titleLink"><?php echo $tmparr[$i][title]; ?></a></dt>
							<dd><?php echo $tmparr[$i][desc].' '.strlen($tmparr[$i][desc]).'<br />'.$tmparr[$i][endDate]; ?> <a href="PersonalInfoMore.php?edit=<?php echo $tmparr[$i][id]; ?>" class="moreLink"> <?php echo $word[37]; //See Details ?></a></dd>
						</dl>
						<?php } ?><br /><br />
				<h2><?php echo $word[38]; //Announcement ?></h2>

				<!-- <div class="panel panel-default">
                  <div class="panel-heading" style="background-color: #f0bf60;"><h3>[LIVE] Prosesi Pemakaman Jakob Oetama Pendiri Kompas Gramedia</h3></div>
				  <div class="panel-body">
                    <div class="row">
						<div class="span">
							<iframe width="550" height="315" src="https://www.youtube.com/embed/9Yx_BkvHrxg?autoplay=1" frameborder="0" allowfullscreen></iframe>
						</div>
					</div>
                  </div>
                </div> -->
				<!-- <div class="panel panel-default">
                                    <div class="panel-heading" style="background-color: #8acdf5;"><h3>KG UNIVERSITY</h3></div>
				  <div class="panel-body">
                                    <div style="width:100%;">
                                        <a href="kg_university/index.html">
                                            <img src="img/kg university/banner kg univ-01-01.jpg" style="
                                                height: 270px;
                                                width: 100%;
                                                padding-bottom: 13px;
                                            ">
                                        </a>
                                    </div>
                                  </div>

                                    <style>
                                        #bpjsjhtbutton:hover{
                                            text-decoration:none;
                                        }
                                    </style>
                                </div> -->
                                
                                
                                <div class="panel panel-default">
				  <div class="panel-heading" style="background-color: #a6cd60"><h3>Jadwal & Lokasi Tes Beasiswa Widyadharma</h3></div>
				  <div class="panel-body">
				    <div class="row">
						<div class="span">
						<a href="home_beasiswa_lokasi.php">
							<img style="box-shadow: 0 4px 1px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" src="img/announce/lokasi_beasiswa_banner.jpg" /></img>
						</a>
						</div>
					</div>
				  </div>
				</div>
				
				<?php 
					$tgl_daftar = odbc_exec($conn, "SELECT TOP 1 tgl_mulai, tgl_selesai FROM tanggal_pendaftaran_beasiswa ORDER BY id desc");
					$fetch = odbc_fetch_array($tgl_daftar);
					$tgl_mulai = $fetch['tgl_mulai'];
					$tgl_selesai = $fetch['tgl_selesai'];
					$today = date('Y-m-d');
					if($today >= $tgl_mulai && $today <= $tgl_selesai){
				?>
					 <div class="panel panel-default">
					  <div class="panel-heading" style="background-color: #a6cd60"><h3>Pendaftaran Tes Beasiswa Widyadharma</h3></div>
					  <div class="panel-body">
						<div class="row">
							<div class="span">
							<a href="homeBeaSiswa.php">
								<img style="box-shadow: 0 4px 1px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" src="Beasiswa/home-beasiswa.png" /></img>
							</a>
							</div>
						</div>
					  </div>
					</div>
					<?php } ?>

				<div class="panel panel-default">
				  <div class="panel-heading" style="background-color: #f0bf60"><h3>Pendaftaran Pasien Poliklinik</h3></div>
				  <div class="panel-body">
				    <div class="row">
						<div class="span">
						<a href="#" data-remodal-target="modal-gi-1"><img width="600px" style="box-shadow: 0 4px 1px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" src="img/banner_home/PENDAFTARAN_PASIEN.jpg">
						</a>
						</div>
					</div>
				  </div>
				</div>


                                
                                
                                
                                
                                <div class="panel panel-default">
                                    <div class="panel-heading" style="background-color: #8acdf5;"><h3>BPJS KetenagaKerjaan </h3></div>
				  <div class="panel-body">
                                    <div style="width:100%;">
                                        <a id='bpjsjhtbutton' href="homeBPJStkSALDO.php">
                                            <img src="img/announce/bpjs gambar ketenagakerjaan.jpg" style="
                                                height: 200px;
                                                width: 100%;
                                                padding-bottom: 13px;
                                            "><br />

                                            <div >
                                                <h3 style="color:#E36C0A;text-align:center;">
                                                Informasi Saldo Iuran Jaminan Hari Tua (JHT)
                                                <br />dan Jaminan Pensiun (JP)
                                                </h3>
                                                <br />
                                                <hr />
                                            </div>
                                        </a>
                                    </div>
                                  </div>

                                    <style>
                                        #bpjsjhtbutton:hover{
                                            text-decoration:none;
                                        }
                                    </style>
                                </div>

				<div class="panel panel-default">
				  <div class="panel-heading" style="background-color: #8acdf5;"><h3>Reservasi Wisma KG</h3></div>
				  <div class="panel-body">
				    <div class="row">
						<div class="span">
						<a href="wismalink.php"><img src="img/announce/reservasi_online_wkg.png" /></img></a>
						</a>
						</div>
					</div>
				  </div>
				</div>



				<!-- Modal Dialog GI Promo -->

				<div class="remodal" data-remodal-id="modal-gi-1"
				  data-remodal-options="hashTracking: false, closeOnOutsideClick: false">

				  <button data-remodal-action="close" class="remodal-close"></button>

				  <div class="row" style="text-align:left;">
				  <img class="span8" src="img/banner_home/PENDAFTARAN_PASIEN.jpg">
				  </div>
				  <br>
				</div>

				<!-- End Modal Dialog GI Promo -->


				<!-- <a href="files/Formulir_Pendaftaran_Band.xls"><img src="img/announce/paguyuban_musik_kg.jpg" /></img></a>-->
			<!-- <a href="home_beasiswa_lokasi.php" border="none" ><img src="img/announce/lokasi_beasiswa_banner.jpg" /></img></a>
                <p><strong>Jadwal & Lokasi Tes Beasiswa Widyadharma</strong>, silakan lihat detailnya <a href="home_beasiswa_lokasi.php" border="none" >disini.</a>
 -->
		<!-- 	<a href="homeBeaSiswa.php" border="none" ><img src="img/announce/beasiswabanner.png" /></img></a>  -->
		<?php if ($persadmin=='002' && $NIK=='005439') {
               echo '<a href="home_dynamic.php"><img src="img/banner_home/dynamic_lp_home.png" /></img></a>';
               }




//                PENGECEKAN KHUSUS UNTUK TRIBUN dan CIKARANG TIDAK KELUAR MENU REMUNERATION

                $sqlchecktribun="select COUNT(*) as JUMLAH from ms_niktelp "
                        . "where ((PersAdmin >= '026' and PersAdmin <='045') OR PersArea='0049') and NIK ='$NIK'";
//                $sqlcheckcikarang="select COUNT(*) as JUMLAH from ms_niktelp "
//                        . "where PersArea='0049' and NIK ='$NIK'";
						$Statustribun=odbc_exec($conn,$sqlchecktribun);
//                                                $Statuscikarang=odbc_exec($conn,$sqlcheckcikarang);
						if(odbc_result($Statustribun,"JUMLAH")==0)
						{
		?>


                        <div style="
                                        padding: 10px;
                                    "
                             class="row">
                            <div style="width:50%;float:left;">

                                <img src="img/announce/bpjs gambar.png" style="
                                    height: 200px;
                                    width: 100%;
                                    padding-bottom: 13px;
                                "><br />

                                <button style="
                                            width: 150px;
                                            height: 50px;
                                            font-size: 15px;
                                        "
                                    type="button" class="btn btn-success" onclick="document.location='BPJSadd.php';">Form Pendaftaran</button>
                                <button style="
                                            width: 150px;
                                            height: 50px;
                                            font-size: 15px;
                                        "
                                    type="button" class="btn btn-success" onclick="document.location='homeBPJS.php';">Alur Pelayanan</button>
                            </div>
                            <div style="width:50%;float:left;">
                                <img src="img/announce/bpjs gambar ketenagakerjaan.jpg" style="
                                    height: 200px;
                                    width: 100%;
                                    padding-bottom: 13px;
                                "><br />

                                <button style="
                                            width: 90%;
                                            height: 50px;
                                            font-size: 15px;
					    margin-left: 10%;
                                        "
                                    type="button" class="btn btn-primary" onclick="document.location='homeBPJStk.php';">Alur Pelayanan <br /> Jaminan Kecelakaan Kerja</button>
                            </div>
                        </div>
                        <div style="clear: both;"></div>


                        <?php
                                                }
                        ?>

 				<!-- <a href="files/FormPendaftaranInspirasiKG.docx"><img src="img/announce/inspirasiKG.jpg" /></img></a> -->
				<!--<img src="img/announce/initsari.jpg" /></img>-->
<!--<a href="home_efin.php"><img src="img/announce/efin.jpg" /></img></a>-->
<!--<a href="BPJSadd.php"><img src="img/announce/bpjs.jpg" /></img></a>-->
<a href="promo_view.php"><img src="img/announce/barter.jpg" /></img></a>
<!--<a href="files/FORMULIR_PENDAFTARAN_KVISION.pdf"><img src="img/announce/paketkvision.jpg" /></img></a>
<a href="https://play.google.com/store/apps/details?id=com.oceanimagine.hrportal2"><img src="img/announce/android_banner.jpg" /></img></a>-->

				<!--<a href="files/doorprize.zip"><img src="img/announce/doorprize.jpg" /></img></a>-->
				<!-- <img src="img/announce/kvision_new.jpg"></img> -->
				<img src="img/announce/banner_hp.png"></img>

				</div>
			</div>

<!-- 			 <a href="#modal">Modal1</a><br>
 -->
<?php
 /*if ($persadmin=='002' && $NIK=='005439') {
 	echo '<div data-remodal-id="modal">
   <button data-remodal-action="close" class="remodal-close right"></button>
   <!-- <h1>Lomba Design IDCARD</h1>  -->
   <p>
    <a href="home_inno.php" target="_parent"><img src="img/banner_home/inno_space.jpg"></img></a>
   </p>
 </div>';
 }*/

/* if ($GLOBALS['survey_dobloo']) {
	$survey_dobloo = $GLOBALS['survey_dobloo'];
 }*/

 // get survey dobloo unique link
 /*$survey_unique_link_qry = odbc_prepare($conn, "SELECT unique_link,skip FROM tb_survey_dobloo WHERE NIK = ?");
 odbc_execute($survey_unique_link_qry, array($NIK));
 $survey_unique_link = odbc_result($survey_unique_link_qry, "unique_link");
 $skip_flag = odbc_result($survey_unique_link_qry, 'skip');*/
 // buat testing
 /*$survey_dobloo['flag'] = 0;
 $survey_dobloo['skip_flag'] = 3;

 var_dump('skip_flag: ',$survey_dobloo['skip_flag']);*/

/*if ($survey_unique_link && $survey_dobloo['skip_flag'] == 3 && ($survey_dobloo['flag'] == 0 || $survey_dobloo['flag'] == 2)) {
 	echo '<div data-remodal-id="modal">
	   <button data-remodal-action="close" class="remodal-close right"></button>
	   <!-- <h1>Lomba Design IDCARD</h1>  -->
	   <p>
	    <a href="'.$survey_unique_link.'" target="_parent"><img src="img/banner_home/survey_dobloo_2023_fix.png"></img></a>
	   </p>
	 </div>';
 }*/

 
 //                PENGECEKAN DATA SURVEY APAKAH SUDAH ISI ATAU BELUM

                $sqlchecksurvey="
                    select count(custom_variables) as jumlahsurvey
                    from tr_dataSurveymonkey 
                   where response_status='completed' and substring(custom_variables,15,1)
                    + substring(custom_variables,21,1)
                   + substring(custom_variables,27,1)
                   + substring(custom_variables,33,1)
                   + substring(custom_variables,39,1)
                   + substring(custom_variables,45,1) = '$NIK' ";
                $statussurvey=odbc_exec($conn,$sqlchecksurvey);
                $jumlahsurvey=odbc_result($statussurvey,"jumlahsurvey");
                
                $sqlchecksurveyGKU="
                    select count(*) as jumlahsurveyGKU from ms_niktelp where (PersAdmin='003' OR PersAdmin='047' ) AND NIK='$NIK' ";
                $statussurveyGKU=odbc_exec($conn,$sqlchecksurveyGKU);
                $jumlahsurveyGKU=odbc_result($statussurveyGKU,"jumlahsurveyGKU");
 
 //PENGECEKAN SURVEY
 
 	
	include "template/bottom5.php"; //Load tenplate penutup dan load javascript eksternal
		 if ($browser['name']=='msie' and $browser['version'] =='6.0'){
    	echo '<script src="js/bootstrap-ieFix.js"></script>';
  		}else{
  		echo '<script src="js/bootstrap-collapse.js"></script>';
  		}
		?>

<!-- Javascript dan jquery script dimulai dari sini-->
<script src="js/remodal/remodal.js"></script>
<!-- Events -->

<?php
/*$jumlahsurvey="1";
$jumlahsurveyGKU="1";*/
/*var_dump($jumlahsurvey);
echo "<br>";
var_dump($jumlahsurveyGKU);
if($jumlahsurvey=="0" && $jumlahsurveyGKU=="0")
{*/
?>
<!-- <style>
.ui-widget-overlay {
position: fixed !important;
}

.sNotify_message {
z-index: 0!important;
}
</style> -->
<!-- <div id="dialog-survey">
    <img src="img/survey/ees_poster-03.jpg" id="img1" />
    <img src="img/survey/pengantar-1.jpg" id="img2" />
</div> -->
<script>
    //MODAL UNTUK SURVEY DIMAS START
    
    /*$(function(){
        var counter=0;
        $( "#dialog-survey" ).dialog({
            autoOpen:false,
            closeOnEscape: false,
            title:"SURVEY KARYAWAN. Apabila anda sudah mengisi hingga complete silahkan tunggu 30 detik.",
            resizable: false,
            height: 1350,
            width: 900,
            modal: true,
            open: function(event, ui) {
                $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
            },
            buttons: [
                <?php
                if(date("H")<"10")
                {
                ?>
                {
                  text: "Lewati",
                  icon: "ui-icon-heart",
                  click: function() {
                    $( this ).dialog( "close" );
                  }
                },
                <?php
                }
                ?>
                {
                  text: "Selanjutnya",
                  icon: "ui-icon-heart",
                  'class': 'my-custom-button-class',
                  click: function() {
                      counter++;
                      $("#img1").toggle();
                      $("#img2").toggle();
                      $(".my-custom-button-class > .ui-button-text").text("Isi Survey");
                      if(counter==2)
                      {
                          document.location='https://www.surveymonkey.com/r/284G3JLS27?G2R=[<?=enkripsi($NIK)?>]';
                      }
                  }
                }
              ]
          });
          
          $("#img2").hide();
          $( "#dialog-survey" ).dialog('open');
    });*/
    
    //MODAL UNTUK SURVEY DIMAS END
<?php /*}else{*/
    ?>
    // <script>
    <?php
    // }
// PENUTUP FUNCTION
}

// call function view_data
view_data($conn,$conn_card,$NIK,$rfc,$data_query['md5_password'],$data_cv_mini['todayDate_SAPFormat'],$data_query['TTL'],$data_query['nama_panggilan'],$data_query['nama_lengkap'],$data_query['positions'],$data_query['emp_unit'],$word,$menuFlag,$flagUltahMasaKerja,$data_query['persadmin']);
?>
    
    
   //var inst = $('[data-remodal-id=modal]').remodal();


/**
 * Opens the modal window
 */
/*var flag = "<?php echo $survey_dobloo['flag']; ?>";
var skip_flag = "<?php echo $survey_dobloo['skip_flag']; ?>";*/
/*var skip_flag = 3;
var flag = 0;
console.log('skip_flag'+skip_flag);*/

if (skip_flag == 3 && (flag == 0 || flag == 2)) {
	var inst = $('[data-remodal-id=modal]').remodal();
	inst.open();
}
/*if ($('[data-remodal-id=modal_survey_dobloo]').length != 0) {
	var inst_dobloo = $('[data-remodal-id=modal_survey_dobloo]').remodal();
}*/

/**
 * Closes the modal window
 */
// inst.close();

/**
 * Returns a current state of the modal
 * @returns {'closed'|'closing'|'opened'|'opening'}
 */
// inst.getState();

/**
 * Destroys the modal window
 */
//inst.destroy();
</script>

<script type="text/javascript" src="Pooling/floating-1.8.js">
</script>
<script type="text/javascript">
    floatingMenu.add('floatdiv',
        {
            //centerY: true,
			targetRight:20,
            targetTop: 10
        });
	function hide(){
		document.getElementById('floatdiv').style.display='none';
	}
	function perso(){
		document.getElementById('perso1').style.display='none';
		document.getElementById('perso2').style.display='block';
		CallTimeEvent('home-personalinfo','ajax-personalinfo');
	}
	function CallTimeEvent(page,div){
		var ajaxRequest;

		try{
			ajaxRequest = new XMLHttpRequest();
		} catch (e){
			// Internet Explorer Browsers
			try{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e){
					// Something went wrong
					alert("Your browser broke!");
					return false;
				}
			}
		}
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				var ajaxDisplay = document.getElementById(div);
				ajaxDisplay.innerHTML = ajaxRequest.responseText;
			}
		}
		//if(page == 'home-timeevent' || page == 'home-saldocuti'){
			var nik = document.getElementById('opernik').value;
			var queryString = "?nik=" + nik;
		//}
		ajaxRequest.open("GET", page+".php"+ queryString, true);
		ajaxRequest.send(null);
	};
</script>
