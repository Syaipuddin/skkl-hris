<?php session_start(); 
ob_start("ob_gzhandler");

	include "include\connection.php";
	
$isSAPConnect = true;
	if($_SESSION['surveyCCI']==1 and $_SESSION['survey']== 0 and $isSurvey==0){
		echo "<script>window.location='survey.php'</script>";
	}

	if(isset($_SESSION['nik'])==false){
		echo "<script>alert('Please login first to access this page.')</script>";
		echo "<script>window.location='index.php'</script>";
	}else{
		
	}
	$NIK = base64_decode($_SESSION['nik']);
	$sqlHistory = "select lastSessionID from tr_login where userLogin = '".$NIK."'";
	$history_cek = odbc_exec($conn,$sqlHistory);

	include "include\sapconnect.php";
	if(odbc_result($history_cek,1) != session_id()){
		echo "<script>alert('Please login first to access this page.');</script>";
		echo "<script>window.location='0-logout.php'</script>";
	}
	include "include/class.browser.php"; 
$browser = Browser::detect(); 

//echo "<font style='font-size:8px;'>ws1</font>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8">
	<title><?php if ($pageTitle !=''){ echo 'Kompas Gramedia - HR Portal | '.$pageTitle;}else{echo 'Kompas Gramedia - HR Portal';}?></title>
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
	<?php
	if ($browser['name']=='msie' and $browser['version'] =='6.0'){
		//echo "<script>window.location='browser-problem.php'</script>";
			echo '<link href="css/bootstrap-ieFix.css" rel="stylesheet">';
	}
	?>
</head>
<body >
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
					<ul class="nav"><?php
						if ($_SESSION['NoSAP']==0){
							$home= 'home.php';
						}else{
							$home='home-nonsap.php';
						}
					?>
						<li><a href="<?php echo $home ?>">Home <i class="icon-home icon-white"></i></a></li>

						<?php 
						if ($_SESSION['NoSAP']==0){
							echo '<li>';
							include "template/0-menu.php" ;
							echo '</li>';
							echo '<li><a href="files/Userguide-HRPortal.zip" title=" Download User Guide New HR Portal">User Guide <i class="icon-download-alt icon-white"></i></a></li>';
						}else{
							$today=date('Y-m-d'); 
							$getQryEmail   = "select * from tr_login WHERE userLogin='$NIK' ";
							$queryEmailResult  = odbc_exec($conn, $getQryEmail);
							echo '<li><a href="0-logout.php?ref=http://elearning.kompasgramedia.com/redirect/catch.php?u='. $NIK.'-'.md5($today).'-'.odbc_result($queryEmailResult,"email"). '" target="_blank">E-Learning <i class="icon-book icon-white"></i></a></li>';
							echo '<li><a href="">E-CCI <i class="icon-check icon-white"></i></a></li>';
						}
						?>

					</ul>
					<img class="brandLogo" src="img/logo/logo_kg.png"/>
					<ul class="nav pull-right">
						<li><a href="term.php?id=2">Term of Use <i class="icon-file icon-white"></i> </a></li>
						<li><a href="HRHD_FAQList.php">Help <i class="icon-question-sign icon-white"></i> </a></li>
						<li><a href="0-logout.php">Logout <i class="icon-off icon-white"></i></a></li>
						
					</ul>
					
				</div><!--/.nav-collapse -->
				
			</div>

		</div>

	</div>
</div>
<div class="mainContenct">