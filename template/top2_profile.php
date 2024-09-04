<?php
	date_default_timezone_set('asia/jakarta');
	session_start();
	ob_start("ob_gzhandler");
	include "include/connection.php";
	if (isset($_GET['lang'])) {
		$_SESSION['lang'] = $_GET['lang'];
	}

	if (!isset($_SESSION['lang'])) {
		$_SESSION['lang']='EN';
	}

	include "language/Home_library_word.php";
	include "include/page_counter.php";
	if(isset($_SESSION['nik'])!='')
	{
		$NIK = base64_decode($_SESSION['nik']);
	}

	include "include/sapconnect.php";
	require_once 'keycloak/includes/backend-call.php';

	// tambah addslashes di semua $_GET (supaya terhindar dari ' dan sql injection)
	if (isset($_GET)!=''){
		foreach ($_GET as $key => $value) {
			$value = addslashes($value);
		}
	}

	if (isset($_REQUEST)!=''){
		foreach ($_REQUEST as $key => $value) {
			$value = addslashes($value);
		}
	}

	/*if ($NIK == '068101' || $NIK == '005290' || $NIK == '052962') {
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}*/

	/*if ($NIK == '068101' || $NIK == '005290') {
		var_dump($NIK);
		echo "<br>";
		foreach ($_SERVER as $key => $value) {
			echo $key." : ";
			echo $value;
			echo "<br>";
		}
	}*/

	$isSAPConnect = true;

		if (isset($_SESSION['auth_hrportal']) && $_SESSION['auth_hrportal']==1) {
			if(isset($_SESSION['nik'])==false){
				echo "<script>alert('Please login22 first to access this page.')</script>";
				echo "<script>window.location='index.php'</script>";
			}

			$sqlHistory = "select lastSessionID from tr_login where userLogin = ?";
			// $history_cek = odbc_exec($conn,$sqlHistory);
			odbc_execute($history_cek = odbc_prepare($conn,$sqlHistory), array($NIK));
			/*var_dump(odbc_result($history_cek,1));
			echo " ";
			var_dump(session_id());*/

			if(odbc_result($history_cek,1) != session_id()){
				echo "<script>alert('Please login first to access this page.');</script>";
				echo "<script>window.location='0-logout.php'</script>";
			}
		}
		else{
			if (isset($_SESSION['token']) && isset($_SESSION['nik'])) {
				
				$config = json_decode(file_get_contents('keycloak/keycloak.json'), TRUE);
			    $usrpwd = json_decode(file_get_contents('keycloak/backendconfig.json'), TRUE);
			
				$tokenCheckResult = CallAPI($config['auth-server-url'] . "/realms/".$usrpwd['realm']."/protocol/openid-connect/token/introspect", $_SESSION['token'], $usrpwd['backend-usrpwd']);
			    $active = json_decode($tokenCheckResult);

			    $fce1 = saprfc_function_discover($rfc,"ZHRFM_GETAUT");
				if (! $fce1 )
				{
					$errorMessage = "An Error Occured, Please Try Again Later.";
					echo "<script>alert('".$errorMessage."');</script>";
					echo "<script>window.location='index.php'</script>";
				}
				else
				{
					saprfc_import ($fce1,"FI_PERNR",htmlentities($active->preferred_username));
					$rfc_rc = saprfc_call_and_receive ($fce1);
					$TEMPAT = saprfc_export ($fce1,"TEMPAT");
					$TGLLAHIR = saprfc_export ($fce1,"TGLLAHIR");
					if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) ; else $errorMessage = "An Error Occured, Please Try Again Later."; exit; }
					$rown = saprfc_table_rows ($fce1,"FI_CV");
					saprfc_function_free($fce1);

					$sql = "select userLogin,convert(varchar(16),decryptbypassphrase(userLogin,password)),convert(varchar(16),decryptbypassphrase(userLogin,generatedPassword)),isActive, lock, email, phoneNumber, StatusNonSAP FROM tr_login where userLogin = ?";


					$queryExec = odbc_execute($query = odbc_prepare($conn, $sql), array($active->preferred_username));
					$isActive_trlogin = odbc_result($query,4);

					if ($NIK > 99999) {
						$ms_niktelp = odbc_prepare($conn, "SELECT COUNT(NIK) as jml FROM ms_niktelp WHERE NIK = ?");
						odbc_execute($ms_niktelp, array($NIK));
						$jml_ms_niktelp = odbc_result($ms_niktelp, "jml");


						if ($jml_ms_niktelp == 0) {
							$TEMPAT = "";
							$TGLLAHIR = "00000000";
						}
						else{
							// diisi tempat tgl lahir supaya ga kena penjagaan
							$TEMPAT = "TEMPAT";
							$TGLLAHIR = "TGLLAHIR";
						}
					}

				    if ($active->active == true && $TEMPAT != "" && $TGLLAHIR != "00000000" /*&& $isActive_trlogin==1*/) {
				    	$nik = htmlentities($active->preferred_username);
						$_SESSION['nik'] = base64_encode($nik);
						$_SESSION['auth_mykg'] = 1;
						$history_login = odbc_execute(odbc_prepare($conn, "update tr_login set lastSessionID = ?,lastLoginTime = ? where userLogin = ?"), array(session_id(), date('Y-m-d H:i:s'), $nik));
				    }
				    else{
				    	
						$menu_mykg = $_SERVER['PHP_SELF'];
						$link_array = explode('/',$menu_mykg);
						$menu_mykg = end($link_array);
						$_SESSION['menu_mykg'] = $menu_mykg;
						header("Location: ../keycloak/0-login_myKG.php?v=".urlencode(date('Ymd-His')));
				    }
				}
			}
			else{
				$menu_mykg = $_SERVER['PHP_SELF'];
				$link_array = explode('/',$menu_mykg);
				$menu_mykg = end($link_array);
				$_SESSION['menu_mykg'] = $menu_mykg;
				header("Location: ../keycloak/0-login_myKG.php?v=".urlencode(date('Ymd-His')));
			}
		}

	
	// get status kontrak karyawan buat jagain trainee ga boleh masuk hrportal
			// dan buat jagain khusus payarea KM wajib redirect ke odoo
			$ms_niktelp = odbc_prepare($conn, "SELECT status, PayArea FROM ms_niktelp WHERE NIK = ?");
			odbc_execute($ms_niktelp, array($NIK));

			$status_kary = strtoupper(odbc_result($ms_niktelp, "status"));
			$payarea = odbc_result($ms_niktelp, "PayArea");

			/*if ($NIK == '085711') {
				var_dump($status_kary);
			}*/

			if ($status_kary == 'TRAINEE') {

                $menu_url = "template/0-menu_trainee.php";
            }
            elseif ($payarea == 'KM') {

                $menu_url = "template/0-menu.php";
            }
            else{
            	$menu_url = "template/0-menu.php";
            }
	    
	
	// curiga hack, jadi logout smua device dlu
	// if (base64_decode($_SESSION['nik']) == '002194') {
	$tr_login = odbc_prepare($conn, "SELECT * FROM tr_login WHERE userLogin = ?");
	odbc_execute($tr_login, array(base64_decode($_SESSION['nik'])));
	$email_tr_login = odbc_result($tr_login, "email");
	// if ($email_tr_login == 'PUJAWIDYANA123@GMAIL.COM' || (strpos($email_tr_login, "PUJA") && strpos($email_tr_login, "WIDYANA"))) {
	if ($_SESSION['nik'] == '072171' && strtoupper($email_tr_login) != 'DININGRATCAHYA462@GMAIL.COM') {
		// echo "<script>alert('"."**Akun anda dicurigai tidak aman\nsementara waktu akun anda akan kami logout otomatis dari semua device selama periode tanggal 27-10-2021.\nJika ada pertanyaan lebih lanjut, dapat hubungi HR Unit anda atau email ke hris@chr.kompasgramedia.com.\nTerima kasih."."')</script>";
		session_destroy();
    	header("Location: 0-logout.php");
 	}
	
	include "include/class.browser.php"; 
	$browser = Browser::detect();


	$server=$_SERVER['HTTP_HOST'];
	$url='https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
	save_page_urls($conn, $server, $url, $NIK, session_id(), $ip_address);
	include 'include/check_Flag.php';
	/*check_Flag("flagEmtelpUpd", $NIK, $conn);*/
?>
<!DOCTYPE html>
<html lang="en" oncontextmenu="return false;" translate="no">
<head>
 <meta charset="utf-8">
	<title><?php
		if (isset($pageTitle) !=''){ echo 'Kompas Gramedia - HR Portal | '.$pageTitle;}else{echo 'Kompas Gramedia - HR Portal';}
	//	echo "Kompas Gramedia - HR Portal";
	?>
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/bootstrap.custom.css" rel="stylesheet">
	<link href="css/custom-tag.css" rel="stylesheet">
	<link href="css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet">
	<link href="css/fg.menu.css" rel="stylesheet">
	<link href="css/ThickBox.css" rel="stylesheet">
	<link href="css/f_clone_Notify.css" rel="stylesheet">
	<link href="img/logo/icon-logo-KG.gif" rel="shortcut icon"/>
	<link rel="stylesheet" href="css/bootstrap-image-gallery.min.css">
	<link rel="stylesheet" href="css/remodal/remodal.css">
  	<link rel="stylesheet" href="css/remodal/remodal-default-theme.css">
	<?php
	if ($browser['name']=='msie' and $browser['version'] =='6.0'){
		//echo "<script>window.location='browser-problem.php'</script>";
			echo '<link href="css/bootstrap-ieFix.css" rel="stylesheet">';
	}
	?>
</head>
<body>
<div class="row warper">
<?php include "template/0-banner.php";?>
<div class="container">
<div class="row">
	<div class="navbar span12">
		<div class="navbar-inner ">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<!--<a class="brand" href="#">HR Portal</a>-->
				<div class="nav-collapse">
					<ul class="nav" style="margin-right: 0px;"><?php

						if (isset($_SESSION['NoSAP'])==0){
							$home= 'home.php';
						}else{
							$home='home-nonsap.php';
						}
					?>
						<li><a href="<?php echo $home ?>"><?php echo $word[39]; //Home ?> <i class="icon-home icon-white"></i></a></li>

						<?php
						if (isset($_SESSION['NoSAP'])==0){
							echo '<li>';
							include $menu_url;
							// include "template/0-menu.php";
							echo '</li>';
							echo '<li><a href="files/Userguide-HRPortal.zip" title="'.$word[40].'">'.$word[44].' <i class="icon-download-alt icon-white"></i></a></li>'; //Download User Guide New HR Portal
						}else{
								$today=date('Y-m-d');
								$getQryEmail   = "select * from tr_login WHERE userLogin=? ";
								// $queryEmailResult  = odbc_exec($conn, $getQryEmail);
								odbc_execute($queryEmailResult = odbc_prepare($conn,$getQryEmail), array($NIK));
									$url="http://".$_SERVER['HTTP_HOST'];
									if($url != "http://10.10.55.7"){
										echo '<li><a href="0-logout.php?ref=http://elearning.kompasgramedia.com/redirect/catch.php?u='. $NIK.'-'.md5($today).'-'.odbc_result($queryEmailResult,"email"). '" target="_blank">'.$word[164].'</a></li> '; //E-Learning
									}else{
										echo '<li><a href="0-logout.php?ref=http://10.10.55.8/redirect/catch.php?u='. $NIK.'-'.md5($today).'-'.odbc_result($queryEmailResult,"email") .'" target="_blank">'.$word[164].'</a></li>'; //E-Learning
									}

							/*
							$today=date('Y-m-d');
							$getQryEmail   = "select * from tr_login WHERE userLogin=? ";
							$queryEmailResult  = odbc_exec($conn, $getQryEmail);
							odbc_execute($queryEmailResult = odbc_prepare($conn,$getQryEmail), array($NIK));
							echo '<li><a href="0-logout.php?ref=http://elearning.kompasgramedia.com/redirect/catch.php?u='. $NIK.'-'.md5($today).'-'.odbc_result($queryEmailResult,"email"). '" target="_blank">E-Learning <i class="icon-book icon-white"></i></a></li>';
							*/
							echo '<li><a href="">E-CCI <i class="icon-check icon-white"></i></a></li>';
						}
						?>

					</ul>
					<img class="brandLogo" src="img/logo/logo_kg.png"/>
					<ul class="nav pull-right" style="margin-left: 0px;">
						<li><a href="term.php?id=2"><?php echo $word[41]; //Terms of Use ?> <i class="icon-file icon-white"></i> </a></li>
						<li><a href="HRHD_FAQList.php"><?php echo $word[42]; //Help ?> <i class="icon-question-sign icon-white"></i> </a></li>
						<?php
							echo "<li>";
							include "template/0-lang.php";
							echo "</li>";
						?>
					<?php
						if(isset($_SESSION['kompas'])=='0')
                                             	{
							echo '<li><a href="0-logout.php">Logout <i class="icon-off icon-white"></i></a></li>';
						}
					?>
					</ul>

				</div><!--/.nav-collapse -->

			</div>

		</div>

	</div>
</div>
<div class="mainContenct">
<?php
// include 'heregistrasi_popup.php';
// $GLOBALS['heregistrasi_popup'] = CheckFlagHeregistrasi($NIK, $conn);
check_Flag("flagNpwp", $NIK, $conn);

// survey dobloo
// include 'survey_dobloo_popup.php';
// $GLOBALS['survey_dobloo'] = checkFlag($NIK, $conn);
?>