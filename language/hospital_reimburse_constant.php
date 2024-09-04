<?php
	if(!isset($_SESSION)){
	   session_start();
	}
	if($_SESSION['lang'] == 'ID'){
		define("header_title", 'Data Penggantian Rumah Sakit');
		define("start_date", 'Tanggal Awal');
		define("end_date", 'Tanggal Akhir');
		define("search", 'Cari');
		define("range", 'Jarak Tanggal :');
		define("type_claim", 'Tipe Claim');
		define("date_claim", 'Tanggal Claim');
		define("date_bill", 'Tanggal Kwitansi');
		define("number_claim", 'Nomor Claim');
		define("name_hospital", 'Nama Rumah Sakit');
		define("check_in", 'Check In');
		define("check_out", 'Check Out');
	}
	else{
		define("header_title", 'Hospital Reimburse Data');
		define("start_date", 'Start Date');
		define("end_date", 'End Date');
		define("search", 'Search');
		define("range", 'Range Date :');
		define("type_claim", 'Claim Type');
		define("date_claim", 'Claim Date');
		define("date_bill", 'Billing Date');
		define("number_claim", 'Claim Number');
		define("name_hospital", 'Hospital Name');
		define("check_in", 'Check In');
		define("check_out", 'Check Out');
	}
	
?>
