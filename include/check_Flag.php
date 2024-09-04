<?php
function check_Flag($kolom, $NIK, $conn){
	$host_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
	$path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

	$msg_flag = '';
	$location_flag = '';
	switch ($kolom) {
		case 'flagEmtelpUpd':
			// $msg_flag = "Email dan Phone Number";
			$msg_flag = "No. KTP";
			$location_flag = "home_update_identity.php";
			$msg_tambahan = array("", "");
			$unit_check=null;
			break;
		case 'flagChangePass':
			$msg_flag = "Password";
			$location_flag = "chpass.php";
			// masih dikasih penjagaan khusus CHR (persadmin 004) aja
			$msg_tambahan[0] = "INNER JOIN ms_niktelp ON tr_login.userLogin = ms_niktelp.NIK";
			// $msg_tambahan[1] = "AND ms_niktelp.Unit like ?";
			// cukup kalau ada msg_tambahan aja
			// $unit_check = "%CORPORATE HUMAN RESOURCES%";
			
			// dibuka utk smua unit
			$msg_tambahan[1] = "";
			$unit_check=null;
			break;
		case 'flagNpwp':
			$msg_flag = "NPWP";
			$location_flag = "home_update_nik_npwp_djp.php";
			$msg_tambahan = array("", "");
			$unit_check=null;
			break;
	}

	$tr_login = odbc_prepare($conn, "SELECT tr_login.* FROM tr_login ".$msg_tambahan[0]." WHERE tr_login.userLogin = ? ".$msg_tambahan[1]);
	odbc_execute($tr_login, array($NIK, $unit_check));
	$flag = odbc_result($tr_login, $kolom);
	if (($flag === NULL || $flag === '') && ($path == "/0-login.php" || $path == "/kompas_home.php" || $path == "/keycloak/0-login_myKG.php")) 
	{
		if ($kolom == "flagNpwp") {
			echo '<div data-remodal-id="npwp_modal">
			   <button data-remodal-action="close" class="remodal-close right"></button>
			   <p>
			    <a href="'.$location_flag.'" target="_parent"><img src="img/banner_home/new_djp_banner_1.png"></img></a>
			   </p>
			 </div>';
		}
		else{
			echo "<script>alert('Please Update your $msg_flag first!');window.location='$location_flag';</script>";
		}
	}
	elseif (($flag === NULL || $flag === '') && $kolom == "flagNpwp" && $uri != "/home_update_nik_npwp_djp.php") {
		echo '<div data-remodal-id="npwp_modal">
			   <button data-remodal-action="close" class="remodal-close right"></button>
			   <p>
			    <a href="'.$location_flag.'" target="_parent"><img src="img/banner_home/new_djp_banner_1.png"></img></a>
			   </p>
		</div>';
	}
}

function check_access_odoo($NIK=null, $payArea=null){
	include "connection.php";
	if (!isset($payArea)) {
		$ms_niktelp = odbc_prepare($conn, "SELECT PayArea FROM ms_niktelp WHERE NIK = ?");
		odbc_execute($ms_niktelp, array($NIK));
		$payArea = odbc_result($ms_niktelp, "PayArea");
	}

	// 1 artinya pny akses odoo, 0 artinya pny akses sap
	if ($payArea == 'KM') {
		return 1;
	}
	else{
		return 0;
	}
}
?>