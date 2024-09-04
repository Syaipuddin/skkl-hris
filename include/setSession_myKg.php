<?php
	session_start();
	include 'connection.php';
	if (isset($_POST['action'])) {
		if ($_POST['action']=='logout') {
			$_SESSION['action'] = 'logout';
		}
	}
	else{
		if (isset($_POST['error']) && $_POST['error'] == 1) {
			$_SESSION['error']++;
		}
		else{
			$_SESSION['auth_mykg'] = 1;
			$nik = htmlentities($_POST['nik']);
			$_SESSION['nik'] = base64_encode($nik);
			// $NIK = '$nik';
			$_SESSION['lang'] = 'EN';
			$history_login = odbc_execute(odbc_prepare($conn, "update tr_login set lastSessionID = ?,lastLoginTime = ? where userLogin = ?"), array(session_id(), date('Y-m-d H:i:s'), $nik));
			$_SESSION['token'] = $_POST['token'];
			unset($_SESSION['auth_hrportal']);
			unset($_SESSION['menu_mykg']);
			// var_dump($_SESSION['menu_mykg']);

			// get status kontrak karyawan buat jagain trainee ga boleh masuk hrportal
			// dan buat jagain khusus payarea KM wajib redirect ke odoo
			$ms_niktelp = odbc_prepare($conn, "SELECT status, PayArea FROM ms_niktelp WHERE NIK = ?");
			odbc_execute($ms_niktelp, array($nik));

			$status_kary = strtoupper(odbc_result($ms_niktelp, "status"));
			$payarea = odbc_result($ms_niktelp, "PayArea");

			echo json_encode(array('status_kary' => $status_kary, 'payarea' => $payarea));
		}
	}
?>