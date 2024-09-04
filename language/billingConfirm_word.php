<?php
	// include "language/Home_library_word.php";
	// echo $word[0];
	if(!isset($_SESSION)){
	   session_start();
	}
	if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'ID'){
	$word = array('nope','Penyetujuan Tagihan ID Card',//1
				  'Jumlah ID Card',//2
				  'Harga per ID Card',//3
				  'Tanggal Mulai',//4	
				  'Tanggal Akhir',//5
				  'Rincian',//6	
				  'Untuk informasi lebih detail silakan hubungi sdr.',//7
				  'Nama',//8
				  'Jenis Request',//9
				  'Tanggal Cetak',//10
				  'Apakah Anda Yakin?',//11
				  'Tidak',//12
				  'Ya',//13
				  'Apakah Anda Mau Mencetak Nota?',//14
				  'Hapus',//15
				  'Rincian',//16
				  'Setuju',//17
				  ''


				 );
	}
	//else if($_SESSION['lang'] == 'EN'){
	else if (isset($_SESSION['lang']) && $_SESSION['lang'] == 'EN'){
	$word = array('nope','Approval Billing ID Card',//1
				  'Total ID Card',//2,
				  'Price for ID Card',//3
				  'Start Date',//4
				  'End Date',//5,
				  'Detail',//6,
				  'For more informastion please contact ',//7
				  'Name',//8
				  'Request Type',//9
				  'Print Date',//10
				  'Are you sure?',//11
				  'No',//12
				  'Yes',//13
				  'Do You Want To Print Nota?',//14
				  'Delete',//15
				  'Detail',//16
				  'Approve',//17
				  ''





				 );
	}
 
?>
