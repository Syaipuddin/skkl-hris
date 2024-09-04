<?php
	// include "language/Home_library_word.php";
	// echo $word[0];
	if(!isset($_SESSION)){
	   session_start();
	}
	if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'ID'){
	$word = 	array('nope','Riwayat Tagihan ID Card',//1
				  'Tanggal Awal:',//2
				  'Tanggal Akhir:',//3
				  'Jumlah ID Card:',//4	
				  'Biaya ID Card',//5
				  'Tanggal Penagihan',//6	
				  'Tanggal Pembayaran',//7
				  'Disetujui Oleh',//8
				  'Rincian',//9
				  'Cetak Nota',//10
				  'Nama',//11
				  'Tipe Request',//12
				  'Tanggal Tercetak',//13
				  'Rincian',//14
				  'Cetak',//15
				  ''


				 );
	}
	//else if($_SESSION['lang'] == 'EN'){
	else if (isset($_SESSION['lang']) && $_SESSION['lang'] == 'EN'){
	$word = array('nope','History ID Card',//1
				  'Start Date:',//2,
				  'End Date:',//3
				  'Total ID Card',//4
				  'Cost for ID Card',//5,
				  'Billing Date',//6,
				  'Payment Date',//7
				  'Approved By',//8
				  'Detail',//9
				  'Print Nota',//10
				  'Name',//11
				  'Request Type',//12
				  'Printed Date',//13
				  'Detail',//14
				  'Print',//15
				  ''





				 );
	}
 
?>
