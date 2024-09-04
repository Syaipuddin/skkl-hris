<?php
	if(!isset($_SESSION)){
	   session_start();
	}
	if($_SESSION['lang'] == 'ID'){
	$word = array('Input Personal Info','NIK',												// 1
				  'Pesan','Total NIK',														// 3
				  'Ajukan','Judul',															// 5
				  'Deskripsi','Tanggal Mulai',												// 7
				  'Tanggal Selesai','Peserta',												// 9
				  'Lihat','Gunakan file (format csv)',										// 11
				  'Unduh contoh csv','Semua file kecuali format csv akan diabaikan',		// 13
				  'Tambah NIK satu per satu','Cari NIK',									// 15
				  'Non-aktifkan Event','Simpan Perubahan',									// 17
				  'Memperbarui Personal Info','Sedang diproses',							// 19
				  'Gambar','Semua',															// 21
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
	$word = array('Personal Info Input','Personnel Number',									// 1
				  'Message','Total Personnel Number',										// 3
				  'Submit Info','Title',													// 5
				  'Description','Start Date',												// 7
				  'End Date','Participant',													// 9
				  'View','Use file (.csv format)',											// 11
				  'Download .csv template','All file except .csv will be ignored',			// 13
				  'Add Personnel Number one by one','Search Personnel Number',				// 15
				  'Deactivate Event','Save Changes',										// 17
				  'Personal Info Update','Processing',										// 19
				  'Image','All',															// 21
				  'Personal Info List','No.',												// 23
				  'Active','Details',														// 25
				  'E-mail','Yes',															// 27
				  'No','View Details',														// 29
				  'View Participant','Send',												// 31
				  'Email is Sent to Participant.','<< Back to List Info',					// 33
				  'Personal Info Event Detail','Event Detail',								// 35
				  'Personal Message','<< Back',												// 37
				  'Invalid File','Uploaded file name : ',									// 39
				  'Name','Verify',															// 41
				  'Ok','Personal Info Process',												// 43
				  'List of Participant','Select',											// 45
				  'Dept','Delete Participant',												// 47
				  '<< Back to Event Info'													// 49
				 );
	}
	
?>