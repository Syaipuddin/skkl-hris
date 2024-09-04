<?php
	session_start();
	if($_SESSION['lang'] == 'ID'){
	$word = array('Form Permintaan Training', 'Dari Tanggal', 									// 1
					'Sampai Tanggal', 'Cari',													// 3
					'Rentang Waktu', 'Rencana Waktu Kerja',										// 5
					'Tanggal', 'Jadwal Kerja Harian Original',									// 7
					'Training', 'Penjelasan / Alasan',											// 9
					'Training dengan NIK', 'Ajukan Training',									// 11
					'Tahap','Lanjut',															// 13
					'Masukkan NIK Atasan','Atasan',												// 15
					'NIK','Nama',																// 17
					'Training telah disubmit.<br />E-mail and notifikasi telah dikirimkan.','Beritahu teman anda dan',	// 19
					"Silahkan tunggu persetujuan dari atasan.",'Terima kasih.',					// 21
					'Training - List Konfirmasi','Selesai',										// 23
					'Training - Form Konfirmasi','NIK-Nama',									// 25
					'Tanggal Request','Permintaan Training dari :',								// 27
					'Anda baru saja mengkonfirmasi sebuah permintaan Training.','Anda baru saja menolak sebuah permintaan Training',																// 29
					'Training dengan teman','List Permintaan Training / Status',				// 31
					'Riwayat Permintaan Training','DWS | Penjelasan | Jam Bekerja',				// 33
					'Jam','Semua data training masih sama dengan awal.',						// 35
					'Tidak ada permintaan training.','Training - Antrian Persetujuan',			// 37
					'Training - Detail Persetujuan','Unit',										// 39
					'Setuju','Riwayat Persetujuan Training',									// 41
					'Detail Riwayat Persetujuan Training','Waktu Training | DWS Sebelumnya >> DWS Training',			// 43
					'Waktu Training','DWS Sebelumnya',											// 45
					'DWS Training','Disetujui',													// 47
					'Ditolak','Detail Riwayat Training',										// 49
					'Cari NIK Atasan','Judul Training',											// 51
					'Angkatan','Silahkan Pilih Training',										// 53
					'Batal','Simpan',															// 55
					'Permintaan anda telah terkirim','Silahkan tunggu respon terhadap permintaan anda',	// 57
					'Halaman ini akan secara otomatis diarahkan ke halaman utama dalam','detik',// 59
					'Ajukan Permintaan Baru','Gagal menyimpan permintaan training',				// 61
					'Daftar Persetujuan Training','Organisasi',							   		// 63				
					'Posisi','Sejarah Kursus dan Pelatihan',									// 65
					'Nama Pelatihan','Nama Institusi',											// 67
					'Alasan','Status Persetujuan Atasan',										// 69
					'Tolak','Permintaan Berhasil Disetujui',									// 71
					'Namun, jumlah partisipan sudah mencapai kuota maksimal, permintaan akan disimpan di daftar tunggu','Permintaan Berhasil Ditolak', //73
					'Gagal Mengolah Permintaan','Status Persetujuan Permintaan',			    // 75
					'Kembali ke Daftar Persetujuan Training','Ajukan' 							//77
					);
	}
	//else if($_SESSION[lang] == 'EN'){
	else{
	$word = array('Training Request Form', 'Start Date', 										// 1
					'End Date', 'Search',														// 3
					'Date Range', 'Planned Working Time',										// 5
					'Date', 'Original DWS',														// 7
					'Training', 'Description / Reason',											// 9
					'Substitute with a Friend who has Personnel Number', 'Submit Training',		// 11
					'Step','Next',																// 13
					'Please Input Personnel Number of your Superior(s)','Superior',				// 15
					'Personnel Number','Name',													// 17
					'Training Submitted.<br />E-mail and notification are sent.','Confirm your friend and',				// 19
					"Please wait for your superior's approval.",'Thank You.',					// 21
					'Training Request - Confirmation List','Finish',							// 23
					'Training Request - Confirmation Form','Personnel Number-Name',				// 25
					'Request Date','Training Request from :',									// 27
					'You have just confirmed a Training Request.','You have just rejected a Training Request.',		// 29
					'Substitute with Friend','Training Request List / Status',					// 31
					'Training Request History','DWS | Description | Working Hour(s)',			// 33
					'Hour','All Training data is still same as the default one.',				// 35
					'No Training Request.','Training Request - Approval List',					// 37
					'Training Request - Approval Detail','Unit',								// 39
					'Approve','Training Approval History',										// 41
					'Training Approval History Detail','Training Time | DWS Before Sub >> DWS Training',		// 43
					'Training Time','DWS Before Sub',											// 45
					'DWS Training','Approved',													// 47
					'Rejected','Training History Detail',										// 49
					'Search Superior Personnel Number','Training Title',						// 51
					'Batch','Please Choose a Training',											// 53
					'Cancel','Save',															// 55
					'Your request has been successfully submitted','Please wait a response to your request',	// 57
					'This page will be automatically redirected to our home page in ','seconds',// 59
					'Submit a New Request','A failure has occurred in submitting your request',	// 61
					'List Training Approval','Organization',									// 63
					'Position','Training & Courses History',									// 65
					'Course Name','Institution Name',											// 67
					'Reason','Approval Status Superior',										// 69
					'Reject','The Request Has Been Successfully Approved',						// 71
					'But, The Training Has Already Reached The Maximum quota, The Request Will Be Recorded In Waiting List','The Request Has Been Successfully Rejected', //73
					'Failed to Process Request','Request Approval Status',						// 75
					'Back to List Training Approval','Submit' //77
					);
	}
?>