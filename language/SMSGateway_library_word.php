<?php
	if(!isset($_SESSION)){
	   session_start();
	}
	if($_SESSION['lang'] == 'ID'){
	$word = array('Ajukan SMS Gateway','NIK',												// 1
				  'Pesan','Total NIK',														// 3
				  'Ajukan','Pilih Tipe Pengajuan SMS Gateway',												// 9
				  'Lihat','Gunakan file (format csv)',										// 11
				  'Unduh contoh .xls','Semua file kecuali format csv akan diabaikan',		// 13
				  'Tambah NIK satu per satu','Unggah File Excel',						// 15
				  'Unduh contoh .xls','Simpan Perubahan',									// 17
				  'Masukkan nomor hp satu per satu','Sedang diproses',							// 19
				  'Daftar Personal Info','No.',												// 23
				  'Aktif','Detail',															// 25
				  'Email','Ya',																// 27
				  'Tidak','Lihat Detail',													// 29
				  'Lihat Peserta','Kirim',													// 31
				  'Email telah dikirimkan kepada peserta.','<< Kembali ke Daftar Info',		// 33
				  'Detail Event Personal Info','Detail Event',								// 35
				  'Pesan Personal','<< Kembali',											// 37
				  'File tidak valid','Nama file yang diunggah : ',							// 39
				  'Nama','Verifikasi',														// 41
				  'Ok','Proses Personal Info',												// 43
				  'Daftar Peserta','Pilih',													// 45
				  'Dept','Hapus Peserta',													// 47
				  '<< Kembali ke Info Event'												// 49
				 );
	}
	//else if($_SESSION['lang'] == 'EN'){
	else{
	$word = array('Request SMS Gateway','Personnel Number',									// 1
				  'Message','Total Personnel Number',										// 3
				  'Submit Info','SMS Gateway Request Type',													// 9
				  'View','Use file (.csv format)',											// 11
				  'Download .xls template','All file except .csv will be ignored',			// 13
				  'Add Personnel Number one by one','Upload File Excel',		// 15
				  'Download .xls template','All file except .csv will be ignored',										// 17
				  'Entry one by one','Processing'
				 );
	}
	
?>