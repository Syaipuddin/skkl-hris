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

	$isSAPConnect = true;
	// unset($_SESSION['auth_mykg']);
			if(isset($_SESSION['nik'])==false){
				echo "<script>alert('Please login22 first to access this page.')</script>";
				echo "<script>window.location='index.php'</script>";
			}

			$sqlHistory = "select lastSessionID from tr_login where userLogin = ?";
			// $history_cek = odbc_exec($conn,$sqlHistory);
			odbc_execute($history_cek = odbc_prepare($conn,$sqlHistory), array($NIK));

			if(odbc_result($history_cek,1) != session_id()){
				echo "<script>alert('Please login first to access this page.');</script>";
				echo "<script>window.location='0-logout.php'</script>";
			}

			// get status kontrak karyawan buat jagain trainee ga boleh masuk hrportal
			// dan buat jagain khusus payarea KM wajib redirect ke odoo
			$ms_niktelp = odbc_prepare($conn, "SELECT status, PayArea FROM ms_niktelp WHERE NIK = ?");
			odbc_execute($ms_niktelp, array($NIK));

			$status_kary = strtoupper(odbc_result($ms_niktelp, "status"));
			$payarea = odbc_result($ms_niktelp, "PayArea");

			if ($status_kary == 'TRAINEE') {
                $menu_url = "template/0-menu_trainee.php";
            }
            elseif ($payarea == 'KM') {
                $menu_url = "template/0-menu.php";
            }
            else{
            	$menu_url = "template/0-menu.php";
            }
	
	include "include/class.browser.php"; 
	$browser = Browser::detect();


	$server=$_SERVER['HTTP_HOST'];
	$url='https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
	save_page_urls($conn, $server, $url, $NIK, session_id(), $ip_address);

	include 'include/check_Flag.php';
  /*check_Flag("flagEmtelpUpd", $NIK, $conn);*/
	// survey dobloo
	// include 'survey_dobloo_popup.php';
	// $GLOBALS['survey_dobloo'] = checkFlag($NIK, $conn);
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
<?php include "template/0-banner.php" ?>
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
							//include "template/0-menu.php" ;
							include $menu_url;
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
check_Flag("flagNpwp", $NIK, $conn);
include 'heregistrasi_popup.php';
$GLOBALS['heregistrasi_popup'] = CheckFlagHeregistrasi($NIK, $conn);
?>