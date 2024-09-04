<?php

	session_start();
	ob_start("ob_gzhandler");
	$NIK = base64_decode($_SESSION['nik']);
	if (!isset($_SESSION['lang'])) {
		$_SESSION['lang']='EN';
	}
	include "include/connection.php";
	include "include/sapconnect.php";
	include "language/Home_library_word.php";

$isSAPConnect = true;

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

	if(isset($_SESSION['nik'])==false){
		echo "<script>alert('Please logintem first to access this page.')</script>";
		echo "<script>window.location='index.php'</script>";
	}else{
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
	}
?>
<html translate="no">
<link href="css/ThickBox.css" rel="stylesheet">
