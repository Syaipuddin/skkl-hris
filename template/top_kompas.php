<!DOCTYPE html>
<html lang="en" oncontextmenu="return false;" translate="no">
<head>
 <meta charset="utf-8">
	<title><?php if ($pageTitle !=''){ echo 'Kompas Gramedia - HR Portal | '.$pageTitle;}else{echo 'Kompas Gramedia - HR Portal';}?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<link href="css/custom-theme/jquery-ui-1.8.16.custom.css" rel="stylesheet">
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/bootstrap.custom.css" rel="stylesheet">
	<link href="css/ThickBox.css" rel="stylesheet">
	<link href="css/custom-tag.css" rel="stylesheet">
	<link href="img/logo/icon-logo-KG.gif" rel="shortcut icon"/>
	<script type="text/javascript" src="js/porthole.js"></script>
   	

<?php 
	session_start();
	$url="http://".$_SERVER['HTTP_HOST'];
	include "include/connection.php"; 
	include "include/sapconnect.php"; 
	include "include/class.browser.php";
	$browser = Browser::detect(); 

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

		
	if($url == "https://hr.kompasgramedia.com"){
		if (($browser['name']=='msie' and ($browser['version'] =='6.0' or $browser['version'] =='7.0')) or ($browser['name']=='mozilla' and ($browser['version'] =='2.0' or $browser['version'] =='3.0' or $browser['version'] =='3.5'or $browser['version'] =='unknown')) ){
		echo "<script>window.location='browser-problem.php'</script>";
		}
	}else{
		if (($browser['name']=='msie' and ($browser['version'] =='6.0' or $browser['version'] =='7.0')) or ($browser['name']=='mozilla' and ($browser['version'] =='2.0' or $browser['version'] =='3.0' or $browser['version'] =='3.5'or $browser['version'] =='unknown')) ){
			echo "<script>window.location='browser-problem.php'</script>";
		}
		/*if (($browser['name']=='msie' and ($browser['version'] =='6.0' or $browser['version'] =='7.0')) or ($browser['name']=='mozilla' and ($browser['version'] =='2.0' or $browser['version'] =='3.0' or $browser['version'] =='3.5'or $browser['version'] =='unknown')) ){
		//echo "<script>window.location='browser-problem.php'</script>";
			echo '<link href="css/bootstrap-ieFix.css" rel="stylesheet">';
		}*/
	}

	include 'include/check_Flag.php';
	/*check_Flag("flagEmtelpUpd", $_SESSION['nik'], $conn);*/
	// survey dobloo
	// include 'survey_dobloo_popup.php';
	// $GLOBALS['survey_dobloo'] = checkFlag($NIK, $conn);
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
					<ul class="nav">
					<?php 
						if(empty($_SESSION['nik']))
						{
						//	echo '<li><a href="index.php">Home <i class="icon-home icon-white"></i></a></li>';
						}
						else {
						//	echo '<li><a href="home.php">Home <i class="icon-home icon-white"></i></a></li>';
						}
					?>
						<!--<li><a href="http://www.kompasgramedia.com/career">Career <i class="icon-lock icon-white"></i></a></li>
						<li><a href="contact.php">Contact Us <i class="icon-envelope icon-white"></i></a></li>-->
						<li></li>
					</ul>
				</div><!--/.nav-collapse -->
				<img class="brandLogo" src="img/logo/logo_kg.png"/>
				<ul class="nav pull-right">
			<!--	<li><a href="term.php?id=1">Term of Use <i class="icon-file icon-white"></i> </a></li> -->
				<?php 
						if(!empty($_SESSION['nik']))
						{
							if(empty($_SESSION['kompas']))
							{
								echo '<li><a href="0-logout.php">Logout <i class="icon-off icon-white"></i></a></li>';
							}
						}
						
					?>
				</ul>
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