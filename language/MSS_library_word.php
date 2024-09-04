<?php
	// include "language/Home_library_word.php";
	// echo $word[0];
	if(!isset($_SESSION)){
	   session_start();
	}
	if($_SESSION['lang'] == 'ID'){
	$word = array('Kalender Cuti','Kalender Kehadiran', 														//1
				  'Kalender Ketidakhadiran','Profil', 															//3
				  'Cari','Rekapitulasi Data Kehadiran',															//5
				  'Tanggal','Atas',																				//7
				  'Bawah','Pilih Tanggal',																		//9
				  'Keterlambatan','Lembur',																		//11
				  'Nama','Data Presensi',																		//13
				  'NIK','Saldo Cuti',																			//15
				  'Catatan: arahkan pointer ke tabel untuk melihat detail','Struktur Organisasi',				//17
				  'Penjadwalan Jam Kerja','Organisasi', 														//19
				  'PILIH','Semua Bawahan', 																		//21
				  'Bawahan Langsung','Data Penggantian Biaya Kesehatan', 										//23
				  'Organisasi belum dipilih','Tanggal Faktur',													//25
				  'Tanggal Proses','Alasan',																	//27
				  'Total Faktur','Tanggungan Karyawan',									 						//29
				  'Tanggungan Perusahaan','Biaya yang tak diganti',			 									//31
				  'Daftar Pemegang Jabatan','Telah disetujui',					 								//33
				  'Belum disetujui','NIK subordinates belum dipilih',																			//35
				  'Kriteria seleksi yang Anda pilih akan membutuhkan waktu proses yang lama.<br /> Apakah Anda ingin melanjutkan proses ?','Ya',	//37
				  'Tidak','Peringatan',//39
				  'Klik nama karyawan untuk melihat rincian.','Permintaan Anda tidak berhasil diselesaikan. Silahkan hubungi administrator HR anda atau coba lagi nanti.',//41
				  'No','Organisasi Text',//43
				  'Unit','Pers Admin',//45
				  'Tanggal Awal','Tanggal Akhir',//47
				  'Diinput Oleh','Waktu Input',//49
				  'Kalender','HRSS Pengaturan Akun',//51
				  'Tambah HR','Peran',//53
				  'Aktif','Tambah Sekertaris',//55
				  'Modul','Edit Sekertaris',//57
				  'Tambah HR','Tipe',//59
				  'Org ID','Berdasarkan Organisasi',//61
				  'Berdasarkan Nama/NIK','Maaf, anda tidak mempunyai hak akses untuk membuka NIK ini',//63
				  'Klik di sini untuk mengganti','Report Karyawan Aktif', //65
                                  'Tanggal','Hapus Sekertaris', //67
                                  'Hapus HR', 'Apakah anda yakin untuk menghapus data ini?', //69
				  'Posisi','Nomor Serial',																		//71
				  'Daftar Karyawan','Nomor Serial ID Card',														//73
					'Barang Pinjaman', 'Wawancara Pengunduran Diri',												//75
				  'Daftar Riwayat Pengunduran Diri', 'Daftar Pertanyaan',		//77
				  		
				  'Kategori Pertanyaan',
				  'Cari :'								//79																			//78
				 );
	}
	//else if($_SESSION['lang'] == 'EN'){
	else{
	$word = array('Leave Calendar','Attendance Calendar', 														//1
				  'Absences Calendar','Profile', 																//3
				  'Search','Time Data Recapitulation',															//5
				  'Range Date','Top',																			//7
				  'Bottom','Range Date',																		//9
				  'Overdue','Overtime',																			//11
				  'Name','Time Event',																			//13
				  'NIK','Leave Balance',																		//15
				  'Note: Mouse over your pointer on the table to see the detail','Tree Chart Organization',		//17
				  'Shift Scheduling','Organization',											 				//19
				  'SELECT','All Subordinate', 																	//21
				  'Direct Subordinate','Medical Reimburse Data',  												//23
				  'Organization has not been selected','Invoice Date',											//25
				  'Process Date','Reason',																		//27
				  'Invoice Total','Employee Payment',															//29
				  'Company Payment','Non-Reimburse Amount',														//31
				  'Job Holder List','Approved',																	//33
				  'Pending Approved','NIK has not been selected',		//35
				  'Selection criteria could result in a long runtime.<br /> Do you want to continue processing?','Yes',//37
				  'No','Warning',//39
				  'Click the employee name to view the details.','Your request was not completed successfully. Please contact your HR administrator or try again later.',//41
				  'No','Organization Text',//43
				  'Unit','Pers Admin',//45
				  'Start Date','End Date',//47
				  'Input By','Input Time',//49
				  'Calendar','HRSS Account Management',//51
				  'Add HR','Role',//53
				  'Active','Add Secretary',//55
				  'Modul','Edit Secretary',//57
				  'Edit HR','Type',//59
				  'Org ID','By Organization',//61
				  'By Name/NIK','No authorization to read this personnel number',//63
				  'Click here to change','Active Employee Report', //65
                                  'Date','Delete Secretary', //67
                                  'Delete HR','Are you sure want to delete?', //69
				  'Position','Serial Number',																		//71
				  'List Employee','ID Card Serial Number',															//73
					'Object On Loan',	'Exit Interview',																//75
				  'Exit Interview History List', 'Exit Interview Question List',									//77
				  'Category',																						//78
				  'Search :'								//79
				 );
	}

?>
