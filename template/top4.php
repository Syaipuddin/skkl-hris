<?php //session_start();

  include "include/connection.php";
  include "include/sapconnect.php";
  if (!isset($_SESSION['lang'])) {
    $_SESSION['lang']='EN';
  }

  // curiga hack, jadi logout smua device dlu
  // if (base64_decode($_SESSION['nik']) == '002194') {

  /*if ($_SESSION['nik'] == '068101' || $_SESSION['nik'] == '005290' || $_SESSION['nik'] == '052962') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
  }*/

  $tr_login = odbc_prepare($conn, "SELECT * FROM tr_login WHERE userLogin = ?");
  odbc_execute($tr_login, array(base64_decode($_SESSION['nik'])));
  $email_tr_login = odbc_result($tr_login, "email");
  // if ($email_tr_login == 'PUJAWIDYANA123@GMAIL.COM' || (strpos($email_tr_login, "PUJA") && strpos($email_tr_login, "WIDYANA"))) {
  if ($_SESSION['nik'] == '072171' && strtoupper($email_tr_login) != 'DININGRATCAHYA462@GMAIL.COM') {
    // echo "<script>alert('"."**Akun anda dicurigai tidak aman\nsementara waktu akun anda akan kami logout otomatis dari semua device selama periode tanggal 27-10-2021.\nJika ada pertanyaan lebih lanjut, dapat hubungi HR Unit anda atau email ke hris@chr.kompasgramedia.com.\nTerima kasih."."')</script>";
    session_destroy();
    header("Location: 0-logout.php");
  }

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

  include 'include/check_Flag.php';
  /*check_Flag("flagEmtelpUpd", $NIK, $conn);*/
?>
<!DOCTYPE html>
<html lang="en" translate="no">
<head>
 <meta charset="utf-8">
  <title><?php if ($pageTitle !=''){ echo 'Kompas Gramedia - HR Portal | '.$pageTitle.' - Popup';}else{echo 'Kompas Gramedia - HR Portal';}?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="css/bootstrap.css" rel="stylesheet">

  <link href="css/bootstrap.custom.css" rel="stylesheet">
   <link href="css/custom-tag.css" rel="stylesheet">
  <link href="css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet">
  <link href="css/ThickBox.css" rel="stylesheet">
  <link href="img/logo/icon-logo-KG.gif" rel="shortcut icon"/>


</head>
<body class="popup" onload="document.getElementById('captcha-form').focus()" >
<?php
check_Flag("flagNpwp", $NIK, $conn);
echo "<script>console.log('NIK: '".$NIK.")</script>";
include 'heregistrasi_popup.php';
$GLOBALS['heregistrasi_popup'] = CheckFlagHeregistrasi($NIK, $conn);
  // survey dobloo
  // include 'survey_dobloo_popup.php';
  // $GLOBALS['survey_dobloo'] = checkFlag($NIK, $conn);
?>
