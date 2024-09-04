<?php
	$pageTitle="Home";
	include "template/top2.php"; //Load template pembuka dan load css eksternal
	include "include/date_lib.php";
	include	"language/Home_library_word.php";
        include "include/enkripsiNIK.php";

        if ($NIK == '068101'/* || $NIK == '005290' || $NIK == '052962'*/) {
        	var_dump("session: ", base64_decode($_SESSION['nik']));
		echo "<br>";
        	foreach ($_SERVER as $key => $value) {
			echo $key." : ";
			echo $value;
			echo "<br>";
		}

		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
        }

function get_cv_mini($NIK,$conn,$rfc)
{
	if (extension_loaded('newrelic')) { // Ensure PHP agent is available
		$new_relic = newrelic_add_custom_tracer("get_cv_mini");
		$change_name = newrelic_name_transaction("/home.php/get_cv_mini");
		newrelic_end_transaction(); // stop recording the current transaction
		newrelic_start_transaction(ini_get("newrelic.appname"));
	}

	$tr_login = odbc_prepare($conn, "SELECT flagEmtelpUpd FROM tr_login WHERE userLogin = ?");
	odbc_execute($tr_login, array($NIK));
	$flag_EmTelp = odbc_result($tr_login, "flagEmtelpUpd");

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

	$FI_NIKAH = saprfc_export ($fce,"FI_NIKAH");
	// SET STATUS NIKAH KE SESSION, ini buat HEREGISTRASI SPY GA PERLU PANGGIL BAPI TRUS SIAP CEK FLAG
	$_SESSION['status_nikah'] = strtolower($FI_NIKAH);

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

// panggil function cv mini
$data_cv_mini = get_cv_mini($NIK,$conn,$rfc);
//$nama_panggilan = $MiniProfile["NAMAPANGGILAN"];
//end of ZHRFM_CV_MINI
				function get_listsisacuti($NIK,$rfc)
				{
					if (extension_loaded('newrelic')) { // Ensure PHP agent is available
						$new_relic = newrelic_add_custom_tracer("get_listsisacuti");
						$change_name = newrelic_name_transaction("/home.php/get_listsisacuti");
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
		$new_relic = newrelic_add_custom_tracer("get_listtime");
		$change_name = newrelic_name_transaction("/home.php/get_listtime");
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
		$new_relic = newrelic_add_custom_tracer("get_query");
		$change_name = newrelic_name_transaction("/home.php/get_query");
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


function view_data($conn,$conn_card,$rfc,$NIK,$todayDate_SAPFormat,$TTL,$nama_panggilan,$userName,$position,$emp_unit,$word,$menuFlag,$flagUltahMasaKerja,$persadmin,$today,$tgl_mulai,$tgl_selesai,$persadmin_parent)
{
	if (extension_loaded('newrelic')) { // Ensure PHP agent is available
		$new_relic = newrelic_add_custom_tracer("view_data");
		$change_name = newrelic_name_transaction("/home.php/view_data");
		newrelic_end_transaction(); // stop recording the current transaction
		newrelic_start_transaction(ini_get("newrelic.appname"));
	}
?>
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
									$photo     = str_replace(array("P:/","./ASSETS/"), "https://idcard.kompasgramedia.com/assets/", $photo);

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
						}

						?>



					<div class="span4" style="text-align:center">
						<div class="img-div" style="">
							<img src="<?php echo $photo; ?>" style=""> <br>

						</div>
					<br>
					<span class="name profile text-center"><?php echo $userName; ?></span><br/>
					<span class="position profile"><?php echo $position; ?></span><br/>
					<span class="unit profile"><?php echo $emp_unit; ?></span><br/>
					<?php /*if (check_access_odoo($NIK)!=1):*/ ?>
						<a href="list_profile_anjab.php" class="moreLink"><?php echo $word[6]; //View Full Profile ?></a><br/>
					<?php /*endif*/ ?>
					<!-- <span style="color: red;"><br><b>Halaman Profile sedang Maintenance!</b></span><br> -->
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

						<!-- LINK IKLAN -->
						<div class="accordion-group">

							<div class="accordion-heading">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseSeven">
									<?php echo "Partner Link" //Gallery ?>
								</a>
							</div>
							<div id="collapseSeven" class="accordion-body collapse">
								<div class="accordion-inner">
										<a href="0-logout.php?ref=http://simpin.gemahripah.or.id" target="_blank"><img src="img/sidebar/gemahripah.jpg" /></img></a>
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
								<a class="btnMyKG" href="https://mykg.id"><span><b>Go to :</b></span><br><img style="padding: 0;" src="img/mykgLOGO.png" width="150" height="150"/></a>	
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
								echo '<div class="post"><img src="PersonalInfo/img.php?type=bd" class="illustration">
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
								echo '<div class="post"><img src="PersonalInfo/img.php?type=bd" class="illustration">
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
				
				<!-- INI UNTUK KALAU ADA KEBUTUHAN MENAMPILKAN STREAMING DI HRPORTAL -->
			<!-- <div class="panel panel-default">
	                  <div class="panel-heading" style="background-color: #8acdf5;"><h3>[LIVE] Prosesi Pemakaman Jakob Oetama Pendiri Kompas Gramedia</h3></div>
					  <div class="panel-body">
	                    <div class="row">
							<div class="span">
								<iframe width="550" height="315" src="https://www.youtube.com/embed/9Yx_BkvHrxg?autoplay=1" frameborder="0" allowfullscreen></iframe>
							</div>
						</div>
	                  </div>
	                </div> -->
<!--                                <div class="panel panel-default">
                                    <div class="panel-heading" style="background-color: #8acdf5;"><h3>Information</h3></div>
				  <div class="panel-body">
                                    <div style="width:100%;">
                                        <b>Maaf sempat terjadi kerusakan pada Server IDCard.
Jika foto anda tidak sesuai dengan foto di IDCard, mohon bantuannya mengirimkan foto anda via email ke idcard@chr.kompasgramedia.com untuk kami update</b>
                                    </div>
                                  </div>
                                </div>-->

			<?php if($today >= $tgl_mulai && $today <= $tgl_selesai) { ?>
				<div class="panel panel-default">
					  <div class="panel-heading" style="background-color: #a6cd60"><h3>Beasiswa Widyadharma</h3></div>
					  <div class="panel-body">
						<div class="row">
							<div class="span">
							<a href="homeBeaSiswa.php">
								<img style="box-shadow: 0 4px 1px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" src="img/announce/lokasi_beasiswa_banner_kosong.jpg" /></img>
							</a>
							</div>
						</div>
					  </div>
				</div>
			<?php } ?>
<!--			<div class="panel panel-default">
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
				</div>-->
                        <!-- <div class="panel panel-default">
                            <div class="panel-heading" style="background-color: #8acdf5;"><h3>Information</h3></div>
                          <div class="panel-body">
                            <div style="width:100%;">
                                    <img src='/img/banner_home/wa woro woro.jpg' />
                            </div>
                            <br>
                            <div style="width:100%;">
                            	<a href="https://mykg.id/kg-updates/info-unit/sudah-tahu-belum-kamu-punya-akses-kompas-id-12-bulan7"><img src='/img/banner_home/banner-hr-portal-mykgid_2023.jpg' /></a>
                            </div>
                          </div>
                        </div> -->

<!--                        <div class="panel panel-default">
                            <div class="panel-heading" style="background-color: #8acdf5;"><h3>OETAMA CUP</h3></div>
                          <div class="panel-body">
                            <div style="width:100%;">
                                <a href="home_oetama.php">
                                <img src='/img/banner_home/jadwalhasil.jpg' />
                                </a>
                            </div>
                          </div>
                        </div>-->

                        <!-- INI BANNER BUAT PEMILIHAN KETUA CABOR PORKA -->
                                <?php
                                $today = date("Y-m-d");
                                $start_date_porka = date("2024-06-20");
                                $end_date_porka = date("2024-07-10");
                                ?>
                    		<?php if (strtotime($today) >= strtotime($start_date_porka) AND strtotime($today) <= strtotime($end_date_porka)): ?>
                    			<div class="panel panel-default">
					  <div class="panel-heading" style="background-color: #a6cd60"><h3>Pemilihan Ketua Cabor PORKA Kompas Gramedia</h3></div>
					  <div class="panel-body">
						<div class="row">
							<div class="span">
							<a href="PORKA_frmpemilihan.php">
								<img style="box-shadow: 0 4px 1px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" src="img/banner_home/banner_poling_PORKA.jpg" /></img>
							</a>
							</div>
						</div>
					  </div>
					</div>
                    		<?php endif ?>
                    		<!-- ENDDDDD - INI BANNER BUAT PEMILIHAN KETUA CABOR PORKA -->

			<div class="panel panel-default">
                                    <div class="panel-heading" style="background-color: #8acdf5;"><h3>IT POLICY</h3></div>
				  <div class="panel-body">
                                    <div style="width:100%;">
                                        
                                        <img src='/img/banner_home/BIJAK BERMEDIA SOSIAL.jpg' />
                                    </div>
                                  </div>
                                </div>
                                
                                <!-- <div class="panel panel-default">
                                    <div class="panel-heading" style="background-color: #8acdf5;"><h3>HR PORTAL MOBILE V2</h3></div>
				  <div class="panel-body">
                                    <div style="width:100%;">
                                        <a href="homeHRPORTALMOBILE.php">
                                            <img src="img/announce/android_banner2.jpg" style="
                                                height: 270px;
                                                width: 100%;
                                                padding-bottom: 13px;
                                            ">
                                        </a>
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
                                </div> -->

					<!-- <div class="panel panel-default">
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
					</div> -->
				<!-- <?php
				if ($persadmin=='002') {
				?>
				<div class="panel panel-default">
                                  <div class="panel-heading" style="background-color: #8acdf5;"><h3>Inovasi GoRP</h3></div>
                                  <div class="panel-body">
                                    <div class="row">
                                                <div class="span">
							<a href="https://bit.ly/innospace2019"><img src="img/banner_home/E-Flyer Survey Internal 1024x1024.jpg" /></a>
							<a href="https://bit.ly/innospace2019"><img src="img/banner_home/E-Flyer InnoSpace Final.jpg" /></a>
                                                </a>
                                                </div>
                                        </div>
                                  </div>
                                </div>    
				<?php } ?> -->
				
				
					 

                                <div class="panel panel-default">
                                    <div class="panel-heading" style="background-color: #8acdf5;"><h3>BPJS KetenagaKerjaan</h3></div>
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

<!--				<div class="panel panel-default">
				  <div class="panel-heading" style="background-color: #8acdf5;"><h3>Reservasi Wisma KG</h3></div>
				  <div class="panel-body">
				    <div class="row">
						<div class="span">
						<a href="wismalink.php"><img src="img/announce/reservasi_online_wkg.png" /></img></a>
						</a>
						</div>
					</div>
				  </div>
				</div>-->
<!--
				<div class="panel panel-default">
				  <div class="panel-heading" style="background-color: #8acdf5;"><h3>Promo Garuda Indonesia Untuk Kompas Gramedia Group</h3></div>
				  <div class="panel-body">
				    <div class="row">
						<div class="span">
						<a href="#" data-remodal-target="modal-gi-1"><img width="190px" style="box-shadow: 0 4px 1px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" src="img/banner_home/banner_home_1.jpg">
						</a>
						<a href="#" data-remodal-target="modal-gi-3"><img width="190px" style="box-shadow: 0 4px 1px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" src="img/banner_home/banner_home_2.jpg">
						</a>
						<a href="#" data-remodal-target="modal-gi-2">
						<img width="190px" style="box-shadow: 0 4px 1px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" src="img/banner_home/banner_home_3.jpg">
						</a>
						</div>
					</div>
				  </div>
				</div>
-->
				<!-- Modal Dialog GI Promo -->

				<div class="remodal" data-remodal-id="modal-gi-1"
				  data-remodal-options="hashTracking: false, closeOnOutsideClick: false">

				  <button data-remodal-action="close" class="remodal-close"></button>

				  <div class="row" style="text-align:left;">
				  <img class="span12" src="img/banner_home/PENDAFTARAN_PASIEN.jpg">
				  </div>
				  <br>
				</div>
<!--
				<div class="remodal" data-remodal-id="modal-gi-2"
				  data-remodal-options="hashTracking: false, closeOnOutsideClick: false">

				  <button data-remodal-action="close" class="remodal-close"></button>
				   <div class="row" style="text-align:left;">
				  <img class="span8" src="template/1-ViewImages.php?id=201611301341">
				  	Kepada Karyawan dan Keluarga Kompas Gramedia Group,</p>
					<p style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">
						Nikmati keuntungan dari Garuda Indonesia hanya dengan menyebutkan&nbsp;kode&nbsp;<strong>"KMPN"&nbsp;</strong>dan menunjukan ID Card Anda serta kartu keluarga (istri/suami dan maksimal 3 orang anak)&nbsp;pada&nbsp;saat pemesanan tiket.</p>
					<ol style="margin-left: 40px;">
						<li>
							Diskon* hingga 12% untuk rute Internasional dan 6% untuk rute Domestik</li>
						<li>
							<em style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">Corporate account check-in counter</em><span style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">&nbsp;di Soekarno - Hatta International Airport (Jakarta) dan Changi International Airport (Singapura)</span></li>
					</ol>
					<p style="color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">Cara Mengakses:</p>
					<ul style="margin-left: 40px;">
					<li>Kunjungi&nbsp;<strong><a href="http://www.garuda-indonesia.com/kmpn" target="_blank">www.garuda-indonesia.com/kmpn</a>&nbsp;</strong>atau download mobile app Garuda Indonesia di Play Store/App Store</li>
					<li>Buat reservasi perjalanan</li>
					<li>Masukkan&nbsp;<strong style="color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">"KMPN"</strong><span style="color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">&nbsp;pada kolom&nbsp;</span><em style="color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">promo code</em></li>
					<li>Gunakan email perusahaan saat mengisi data penumpang (contoh:&nbsp;<a style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;" href="mailto:abcd@chr.kompasgramedia.com">abcd@chr.kompasgramedia.com</a><span style="color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">)</span></li>
					</ul>
					<p style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">
						Reservasi tiket Anda sekarang melalui contact centre&nbsp;<strong>(021) 2351 9988,&nbsp;</strong>kunjungi&nbsp;kantor penjualan Garuda Indonesia terdekat atau agen perjalanan yang ditunjuk perusahaan. Untuk informasi lebih lanjut, silahkan hubungi Ms. Chrystine di&nbsp;<strong>+62 852 1030 6746 /&nbsp;<a data-mce-href="mailto:chrystine.t@garuda-indonesia.com" href="mailto:chrystine.t@garuda-indonesia.com">chrystine.t@garuda-indonesia.com</a></strong></p>
					<p style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">
						&nbsp;</p>
					<p data-mce-style="font-size: 10px;" style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;">
						<em>*Periode pemesanan hingga 31 Maret 2017 dan periode penerbangan hingga 31 Juni 2017, syarat &amp; ketentuan berlaku</em></p>
				  </div>
				  <br>

				</div>

				<div class="remodal" data-remodal-id="modal-gi-3"
				  data-remodal-options="hashTracking: false, closeOnOutsideClick: false">

				  <button data-remodal-action="close" class="remodal-close"></button>
				  <div class="row" style="text-align:left;">
				  <img class="span8" src="template/1-ViewImages.php?id=201611301335">
				  	<p>Kepada Karyawan dan Keluarga Kompas Gramedia Group,</p>
					<p style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">
						Nikmati keuntungan dari Garuda Indonesia hanya dengan memasukkan kode&nbsp;<strong>"KMPN"&nbsp;</strong>pada kolom&nbsp;<em>promo code&nbsp;</em>di Website atau Mobile App Garuda Indonesia.</p>
					<ol style="margin-left: 40px;">
						<li>
							Diskon* hingga 12% untuk rute Internasional dan 6% untuk rute Domestik</li>
						<li>
							<em style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">Corporate account check-in counter</em><span style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">&nbsp;di Soekarno - Hatta International Airport (Jakarta) dan Changi International Airport (Singapura)</span></li>
					</ol>
					<p style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">
						Cara Mengakses:</p>
					<ol style="margin-left: 40px;">
						<li>
							Kunjungi&nbsp;<strong><a data-mce-href="http://www.garuda-indonesia.com/kmpn" target="_blank" href="http://www.garuda-indonesia.com/kmpn">www.garuda-indonesia.com/kmpn</a>&nbsp;</strong>atau download mobile app Garuda Indonesia di Play Store/App Store</li>
						<li>
							Buat reservasi perjalanan</li>
						<li>
							Masukkan&nbsp;<strong style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">"KMPN"</strong><span style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">&nbsp;pada kolom promo code</span></li>
						<li>
							Gunakan email perusahaan saat mengisi data penumpang (contoh:&nbsp;<a data-mce-href="mailto:abcd@chr.kompasgramedia.com" href="mailto:abcd@chr.kompasgramedia.com" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">abcd@chr.kompasgramedia.com</a><span style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">)</span></li>
					</ol>
					<p style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">
						Keuntungan mitra korporasi juga dapat diakses melalui contact centre&nbsp;<strong>(021) 2351 9988,&nbsp;</strong>kantor penjualan kami atau agen perjalanan yang ditunjuk perusahaan. Untuk informasi lebih lanjut, silahkan hubungi Ms. Chrystine di&nbsp;<strong>+62 852 1030 6746 /&nbsp;<a data-mce-href="mailto:chrystine.t@garuda-indonesia.com" href="mailto:chrystine.t@garuda-indonesia.com">chrystine.t@garuda-indonesia.com</a></strong></p>
					<p style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">
						&nbsp;</p>
					<p data-mce-style="font-size: 10px;" style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;">
						<em>*Periode pemesanan hingga 31 Maret 2017 dan periode penerbangan hingga 31 Juni 2017, syarat &amp; ketentuan berlaku</em></p>
				  </div>
				  <br>

				</div>
   -->
				<!-- End Modal Dialog GI Promo -->


				<!-- <a href="files/Formulir_Pendaftaran_Band.xls"><img src="img/announce/paguyuban_musik_kg.jpg" /></img></a>-->
			<!-- <a href="home_beasiswa_lokasi.php" border="none" ><img src="img/announce/lokasi_beasiswa_banner.jpg" /></img></a>
                <p><strong>Jadwal & Lokasi Tes Beasiswa Widyadharma</strong>, silakan lihat detailnya <a href="home_beasiswa_lokasi.php" border="none" >disini.</a>
 -->
		<!-- 	<a href="homeBeaSiswa.php" border="none" ><img src="img/announce/beasiswabanner.png" /></img></a>  -->
		<?php //if ($persadmin=='002') {
               //echo '<a href="home_dynamic.php"><img src="img/banner_home/dynamic_lp_home.jpg" /></img></a>';
               //}




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
				
				<!-- promo barter ditakeout dulu -->
				<!-- <a href="promo_view.php"><img src="img/announce/barter.jpg" /></img></a> -->

				<!--<a href="files/FORMULIR_PENDAFTARAN_KVISION.pdf"><img src="img/announce/paketkvision.jpg" /></img></a>-->


				<!--<a href="files/doorprize.zip"><img src="img/announce/doorprize.jpg" /></img></a>-->
				<!-- <img src="img/announce/kvision_new.jpg"></img> -->
				<!-- <img src="img/announce/banner_hp2.png"></img> -->

				</div>
			</div>

<?php

/*if ($GLOBALS['survey_dobloo']) {
	$survey_dobloo = $GLOBALS['survey_dobloo'];
}

 // get survey dobloo unique link
 $survey_unique_link_qry = odbc_prepare($conn, "SELECT unique_link,skip FROM tb_survey_dobloo WHERE NIK = ?");
 odbc_execute($survey_unique_link_qry, array($NIK));
 $survey_unique_link = odbc_result($survey_unique_link_qry, "unique_link");
 $skip_flag = odbc_result($survey_unique_link_qry, 'skip');

if ($survey_unique_link && $survey_dobloo['skip_flag'] == 3 && ($survey_dobloo['flag'] == 0 || $survey_dobloo['flag'] == 2)) {
	echo '<div data-remodal-id="modal">
	  <button data-remodal-action="cancel" class="remodal-close right"></button>
	  <p>
	   <a id="a_modal" target = "_parent" href="'.$survey_unique_link.'"><img src="img/banner_home/survey_dobloo_2023_fix.png"/></a>
	  </p>
	</div>';
}*/

if ($persadmin=='002' || $persadmin_parent=='002') {
  /*echo '<div data-remodal-id="modal">
  <button data-remodal-action="close" class="remodal-close right"></button>
  <!-- <h1>Lomba Design IDCARD</h1>  -->
  <p>
   <a href="#"><img src="img/banner_home/poster_GramediansMengajar_rev.jpeg" /></a>
  </p>
</div>';*/

/*echo '<div data-remodal-id="modal2">
  <button data-remodal-action="close" class="remodal-close right"></button>
  <p>
   <a href="https://bit.ly/FinancialSurveyKG"><img src="img/banner_home/financial_survey.jpeg" /></a>
  </p>
</div>';*/

/*echo '<div data-remodal-id="modal3">
  <button data-remodal-action="close" class="remodal-close right"></button>
  <!-- <h1>Survey HR-PORTAL</h1>  -->
  <p>
   <a href="https://mykg.id/kg-updates/info-kg/survei-pemakaian-hr-portal"><img src="img/banner_home/banner_survey_hrportal.jpeg"/></a>
  </p>
</div>';*/

/*echo '<div data-remodal-id="modal2">
  <button data-remodal-action="close" class="remodal-close right"></button>
  <!-- <h1>KGU Research 2020</h1>  -->
  <p>
   <a href="http://bit.ly/NOVACROCHETING"><img src="img/banner_home/Banner_Nova.jpeg" /></a>
  </p>
</div>';*/

/*echo '<div data-remodal-id="modal2">
  <button data-remodal-action="close" class="remodal-close right"></button>
  <!-- <h1>KGU Research 2020</h1>  -->
  <p>
   <a href="http://bit.ly/ResearchLearningKGU"><img src="img/banner_home/KGU_RESEARCH_2020_Poster.jpeg" /></a>
  </p>
</div>';*/
    
    
//    MY KG
//    echo '<div data-remodal-id="modal">
//   <button data-remodal-action="close" class="remodal-close right"></button>
//   <p>
//    <a href="https://docs.google.com/forms/d/e/1FAIpQLScboiKp_jljwmnYhKIqCBiT1rY1CAPlEE1XLcOPV5z78iwPUg/viewform?entry.1958232918='.$NIK.'"><img src="img/banner_home/POPUP HR PORTAL.png" /></a>
//   </p>
// </div>';
 }else{
 /*echo '<div data-remodal-id="modal">
  <button data-remodal-action="close" class="remodal-close right"></button>
  <!-- <h1>KGU Research 2020</h1>  -->
  <p>
   <a href="https://bit.ly/FinancialSurveyKG"><img src="img/banner_home/financial_survey.jpeg" /></a>
  </p>
</div>';*/

/*echo '<div data-remodal-id="modal2">
  <button data-remodal-action="close" class="remodal-close right"></button>
  <!-- <h1>Survey hrportal</h1>  -->
  <p>
   <a href="https://mykg.id/kg-updates/info-kg/survei-pemakaian-hr-portal"><img src="img/banner_home/banner_survey_hrportal.jpeg"/></a>
  </p>
</div>';*/

// edit di sini
/*echo '<div data-remodal-id="modal">
  <button data-remodal-action="close" class="remodal-close right"></button>
  <!-- <h1>KGU Research 2020</h1>  -->
  <p>
   <a href="#"><img src="img/banner_home/syukuranKG_20240122.jpeg" /></a>
  </p>
</div>';*/


// untuk semua karyawan popup / dialog after login
// echo '<div data-remodal-id="modal">
//   <button data-remodal-action="close" class="remodal-close right"></button>
//   <p>
//    <a href="https://docs.google.com/forms/d/e/1FAIpQLScboiKp_jljwmnYhKIqCBiT1rY1CAPlEE1XLcOPV5z78iwPUg/viewform?entry.1958232918='.$NIK.'"><img src="img/banner_home/POPUP HR PORTAL.png" /></a>
//   </p>
// </div>';
 }

}
// call function view_data
view_data($conn,$conn_card,$rfc,$NIK,$data_cv_mini['todayDate_SAPFormat'],$data_query['TTL'],$data_query['nama_panggilan'],$data_query['nama_lengkap'],$data_query['positions'],$data_query['emp_unit'],$word,$menuFlag,$flagUltahMasaKerja,$data_query['persadmin'],$today,$tgl_mulai,$tgl_selesai,$data_query['persadmin_parent']);

 
 //                PENGECEKAN DATA SURVEY APAKAH SUDAH ISI ATAU BELUM

//                $sqlchecksurvey="
//                    select count(custom_variables) as jumlahsurvey
//                    from tr_dataSurveymonkey 
//                   where response_status='completed' and substring(custom_variables,15,1)
//                    + substring(custom_variables,21,1)
//                   + substring(custom_variables,27,1)
//                   + substring(custom_variables,33,1)
//                   + substring(custom_variables,39,1)
//                   + substring(custom_variables,45,1) = '$NIK' ";
//                $statussurvey=odbc_exec($conn,$sqlchecksurvey);
//                $jumlahsurvey=odbc_result($statussurvey,"jumlahsurvey");
//                
//                $sqlchecksurveyGKU="
//                    select count(*) as jumlahsurveyGKU from ms_niktelp where (PersAdmin='003' OR PersAdmin='047' OR PersAdmin='153') AND NIK='$NIK' ";
//                $statussurveyGKU=odbc_exec($conn,$sqlchecksurveyGKU);
//                $jumlahsurveyGKU=odbc_result($statussurveyGKU,"jumlahsurveyGKU");
 
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

<script>
    $(function(){
//        
//    var install = $('[data-remodal-id=modalall]').remodal();
//    /**
//      * Opens the modal window
//      */
//     install.open();
//     /**
//      * Closes the modal window
//      */
//     install.close();
//
//     /**
//      * Returns a current state of the modal
//      * @returns {'closed'|'closing'|'opened'|'opening'}
//      */
//     install.getState();
//
//     /**
//      * Destroys the modal window
//      */
//     //inst.destroy();
//        
//   
//   
//   $(document).on('closing', '.remodal', function (e) {
//        var install = $('[data-remodal-id=modalall]').remodal();
//        /**
//          * Opens the modal window
//          */
//         install.open();
//         /**
//          * Closes the modal window
//          */
//         install.close();
//
//         /**
//          * Returns a current state of the modal
//          * @returns {'closed'|'closing'|'opened'|'opening'}
//          */
//         install.getState();
//
//         /**
//          * Destroys the modal window
//          */
//         //inst.destroy();
//    });






//MULAI POPUP

var nik = "<?php echo $NIK ?>";


// khusus buat survey dobloo aja JANGAN LUPA DIAPUS KLO UDAH GA PAKE DOBLOO
// supaya pas click tombol X, ga close popup tapi redirect ke dobloo
/*inst.settings = {
	closeOnOutsideClick: true,
	closeOnEscape: true,
	closeOnCancel: true,
}*/
   
/**
 * Opens the modal window
 */
/*var flag = "<?php echo $survey_dobloo['flag']; ?>";
var skip_flag = "<?php echo $survey_dobloo['skip_flag']; ?>";
if (skip_flag == 3 && (flag == 0 || flag == 2)) {
	var inst = $('[data-remodal-id=modal]').remodal();
	inst.open();
}
if ($('[data-remodal-id=modal_survey_dobloo]').length != 0) {
	var inst_dobloo = $('[data-remodal-id=modal_survey_dobloo]').remodal();
}*/
//if ($('[data-remodal-id=modal]').length != 0) {
// klo ada npwp, jangan buka modal yg lain
/*if ($('[data-remodal-id=modal]').length != 0 && $('[data-remodal-id=npwp_modal]').length == 0) {
	var inst = $('[data-remodal-id=modal]').remodal();
	inst.open();
}*/

/**
 * Closes the modal window
 */
// inst.close();

//open 2 modal banner
/*var persadmin = "<?php echo $persadmin; ?>";*/
// if (persadmin == '002') {
	// khusus buat survey dobloo aja JANGAN LUPA DIAPUS KLO UDAH GA PAKE DOBLOO
	// supaya pas click tombol X, akan diarahin ke halaman survey
	/*$(document).on('cancellation', '.remodal', function (e) {
		if (e.currentTarget.attributes[0].value == 'modal') {
			var survey_link = document.getElementById("a_modal").click();
		}
	});*/
	
	// ini supaya kalau dia ga ada survey yg bisa di-x dan survey dobloo yg ga bisa diclose, dia lgsung open survey berikutnya (buat gorp)
	/*if ((typeof inst == 'undefined' || inst == null ) && (typeof inst_dobloo == 'undefined' || inst_dobloo == null )) {
		var inst2 = $('[data-remodal-id=modal2]').remodal();
		if (inst2){
			inst2.open();
			inst2.close();
			inst2.getState();
		}
	}*/

	$(document).on('closed', '.remodal', function(e){
		// ganti modalnya dobloo dulu
		// if (e.currentTarget.attributes[0].value == 'modal_survey_dobloo' || e.currentTarget.attributes[0].value == 'modal') {
		if (e.currentTarget.attributes[0].value == 'modal') {
			var inst2 = $('[data-remodal-id=modal2]').remodal();
			if (inst2){
				inst2.open();
				inst2.close();
				inst2.getState();
			}
		}
		else if (e.currentTarget.attributes[0].value == 'modal2') {
			var inst3 = $('[data-remodal-id=modal3]').remodal();
			if (inst3) {
				inst3.open();
				inst3.close();
				inst3.getState();
			}
		}
	});
// }

/**
 * Returns a current state of the modal
 * @returns {'closed'|'closing'|'opened'|'opening'}
 */
// inst.getState();

/**
 * Destroys the modal window
 */
//inst.destroy();


<?php
//if ($persadmin!='002') {
?>
        
<?php
//}
?>
});
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
