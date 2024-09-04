<?php

session_start();
//ob_start("ob_gzhandler");
$NIK = base64_decode($_SESSION['nik']);
	include "include/connection.php";
	include "include/sapconnect.php";
	include "language/Home_library_word.php";
        require_once 'keycloak/includes/backend-call.php';

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

	if (isset($_SESSION['auth_hrportal']) && $_SESSION['auth_hrportal']==1) {
                        if(isset($_SESSION['nik'])==false){
                                echo "<script>alert('Please login first to access this page.')</script>";
                                echo "<script>window.location='index.php'</script>";
                        }else{
                                
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

                                        if ($active->active == true && $TEMPAT != "" && $TGLLAHIR != "00000000" && $isActive_trlogin==1) {
                                                $nik = htmlentities($active->preferred_username);
                                                $_SESSION['nik'] = base64_encode($nik);
                                                $_SESSION['lang'] = 'EN';
                                                $_SESSION['auth_mykg'] = 1;
                                                $history_login = odbc_execute(odbc_prepare($conn, "update tr_login set lastSessionID = ?,lastLoginTime = ? where userLogin = ?"), array(session_id(), date('Y-m-d H:i:s'), $nik));
                                        }
                                        else{
		                                // echo "<script>alert('Please login first to access this page.');</script>";
		                                // echo "<script>window.location='0-logout.php'</script>";
		                                $menu_mykg = $_SERVER['PHP_SELF'];
		                                $link_array = explode('/',$menu_mykg);
		                                $menu_mykg = end($link_array);
		                                $_SESSION['menu_mykg'] = $menu_mykg;
		                                header("Location: ../keycloak/0-login_myKG.php");
		                        }

                                }
                        }
                        else{
                                $menu_mykg = $_SERVER['PHP_SELF'];
                                $link_array = explode('/',$menu_mykg);
                                $menu_mykg = end($link_array);
                                $_SESSION['menu_mykg'] = $menu_mykg;
                                header("Location: ../keycloak/0-login_myKG.php");
                        }
                }
                // include 'include/check_Flag.php';
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
    // check_Flag("flagEmtelpUpd", $NIK, $conn);
?>
<html translate="no">
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
<!--        <link href="css/bootstrap-chosen.css" rel="stylesheet"> -->
