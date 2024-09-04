<?php
	session_start();
	include "include/connection.php";
	$nik 	= base64_decode($_GET['u']);
	$lang = $_GET['l'];
	$to   = $_GET['t'];
	$from = $_SERVER['HTTP_HOST'];
	// check dari request intranet/internet
	switch ($from) {
		case 'hr.kompasgramedia.com':
			$loc = 'http://hr.kompasgramedia.com/';
			break;
		case 'hr2.kompasgramedia.com':
			// request dari internet
			$loc = 'http://hr2.kompasgramedia.com/';
			break;
		case '10.10.55.7':
		case '10.10.55.5':
		case '10.10.55.16':
		case '10.10.55.67':
		case '10.9.70.30':
			$loc = 'http://10.9.70.30/';
			break;
		case 'hr.kompasgramedia.co.id':
			$loc = 'http://hr.kompasgramedia.co.id/';
			break;
		case '10.10.55.18':
		case '10.10.55.25':
			// request dari dev
			$from_flag = "dev";
			$loc = 'http://10.10.55.25/';
			break;
		case 'hr.kompasgramedia.com:800':
			$loc = 'http://hr.kompasgramedia.com:800/';
			break;
		case '10.9.70.30:800':
			$loc = 'http://10.9.70.30:800/';

			break;
	}
	$query = "SELECT COUNT(*) as VAL FROM tr_login WHERE userLogin='$nik' AND isActive = 1 AND lock < 5";
	echo $result = odbc_result(odbc_exec($conn, $query), 'VAL');
	if ($result){
		$_SESSION['lang'] = $lang;
		$_SESSION['nik'] 	= base64_encode($nik);
		$_SESSION['loginNik'] = $nik;
		switch ($to) {
			case 'agenda':
				$loc .= 'agendaList.php?id=all&agendaDate='.date('Y-m-d');
				
				break;
			
			default:
				$loc .= 'home.php';
				
				break;
		}
	} else {
		$loc .= 'index.php';
	}
	echo '<br>'.$loc;
	// header( 'Location: '.$loc ) ;
?>