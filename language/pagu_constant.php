<?php
	if(!isset($_SESSION)){
	   session_start();
	}
	if($_SESSION['lang'] == 'ID'){
		define("header_title", 'Biaya Kesehatan Tahun');
		define("keterangan", 'Keterangan');	
		define("pagu", 'Pagu');	
		define("biaya_ganti", 'Biaya Pergantian');	
		define("sisa_pagu", 'Sisa Pagu');
	}
	else{
		define("header_title", 'Annuall Cost Healthcare');
		define("keterangan", 'Description');	
		define("pagu", 'Ceiling');	
		define("biaya_ganti", 'Reimbursement');	
		define("sisa_pagu", 'Ceiling Balance');
	}
	
?>
