<?php
	if(!isset($_SESSION)){
	   session_start();
	}
	if($_SESSION['lang'] == 'ID'){
	$word = array('Form Permintaan Substitusi', 'Dari Tanggal', 								// 1
					'Sampai Tanggal', 'Cari',													// 3
					'Rentang Waktu', 'Rencana Waktu Kerja',										// 5
					'Tanggal', 'Jadwal Kerja Harian Original',									// 7
					'Substitusi', 'Penjelasan / Alasan',										// 9
					'Substitusi dengan NIK', 'AJukan Substitusi',								// 11
					'Tahap','Lanjut',															// 13
					'Masukkan NIK Atasan','Atasan',												// 15
					'NIK','Nama',																// 17
					'Substitusi telah disubmit.<br />E-mail and notifikasi telah dikirimkan.','Beritahu teman anda dan',	// 19
					"Silahkan tunggu persetujuan dari atasan.",'Terima kasih.',					// 21
					'Substitusi - List Konfirmasi','Selesai',									// 23
					'Substitusi - Form Konfirmasi','NIK-Nama',									// 25
					'Tanggal Request','Permintaan Substitusi dari :',							// 27
					'Anda baru saja mengkonfirmasi sebuah permintaan substitusi.','Anda baru saja menolak sebuah permintaan substitusi',																// 29
					'Substitusi dengan teman','List Permintaan Substitusi / Status',			// 31
					'Riwayat Permintaan Substitusi','DWS | Penjelasan | Jam Bekerja',			// 33
					'Jam','Semua data substitusi masih sama dengan awal.',						// 35
					'Tidak ada permintaan substitusi.','Substitusi - Antrian Persetujuan',		// 37
					'Substitusi - Detail Persetujuan','Unit',									// 39
					'Setuju','Riwayat Persetujuan Substitusi',									// 41
					'Detail Riwayat Persetujuan Substitusi','Waktu Substitusi | DWS Sebelumnya >> DWS Substitusi',			// 43
					'Waktu Substitusi','DWS Sebelumnya',										// 45
					'DWS Substitusi','Disetujui',												// 47
					'Ditolak','Detail Riwayat Substitusi',										// 49
					'Cari NIK Atasan','Cari NIK',												// 51
					'Kriteria rentang tanggal akan membutuhkan waktu proses yang lama.\nSilahkan perkecil rentang tanggal pencarian.','Back',	// 53
					);
	}
	//else if($_SESSION[lang] == 'EN'){
	else{
	$word = array('Substitution Request Form', 'Start Date', 									// 1
					'End Date', 'Search',														// 3
					'Date Range', 'Planned Working Time',										// 5
					'Date', 'Original DWS',														// 7
					'Substitution', 'Description / Reason',										// 9
					'Substitute with a Friend who has Personnel Number', 'Submit Substitution',	// 11
					'Step','Next',																// 13
					'Please Input Personnel Number of your Superior(s)','Superior',				// 15
					'Personnel Number','Name',													// 17
					'Substitution Submitted.<br />E-mail and notification are sent.','Confirm your friend and',				// 19
					"Please wait for your superior's approval.",'Thank You.',					// 21
					'Substitution Request - Confirmation List','Finish',						// 23
					'Substitution Request - Confirmation Form','Personnel Number-Name',			// 25
					'Request Date','Substitution Request from :',								// 27
					'You have just confirmed a Substitution Request.','You have just rejected a Substitution Request.',		// 29
					'Substitute with Friend','Substitution Request List / Status',				// 31
					'Substitution Request History','DWS | Description | Working Hour(s)',		// 33
					'Hour','All Substitution data is still same as the default one.',			// 35
					'No Substitution Request.','Substitution Request - Approval List',			// 37
					'Substitution Request - Approval Detail','Unit',							// 39
					'Approve','Substitution Approval History',									// 41
					'Substitution Approval History Detail','Substitution Time | DWS Before Sub >> DWS Substitution',		// 43
					'Substitution Time','DWS Before Sub',										// 45
					'DWS Substitution','Approved',												// 47
					'Rejected','Substitution History Detail',									// 49
					'Search Superior Personnel Number','Search Personnel Number',				// 51
					'Range date criteria will result in a long runtime.\nPlease select smaller range.','Kembali',					// 53
					);
	}
?>