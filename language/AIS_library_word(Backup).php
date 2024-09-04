<?php
	// include "language/Home_library_word.php";
	// echo $word[0];
	if(!isset($_SESSION)){
	   session_start();
	}
	if($_SESSION['lang'] == 'ID'){
	$word = array('Kalender Cuti','Kalender Kehadiran', 														//1
				  'Kalender Ketidakhadiran','Profil', 															//3
				  'Cari','Form Permintaan Assessment',															//5
				  'Tanggal','Atas',																				//7
				  'Bawah','Pilih Tanggal',																		//9
				  'Keterlambatan','Lembur',																		//11
				  'Nama','AIS Status pengajuan',																		//13
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
				  'Kalender','AIS Pengaturan Assessor',//51	
				  'Tambah Assessor','Peran',//53				
				  'Aktif','Tambah Sekertaris',//55	
				  'Modul','Edit Sekertaris',//57	
				  'Tambah HR','Tipe',//59		
				  'Org ID','Berdasarkan Organisasi',//61	
				  'Berdasarkan Nama/NIK','Maaf, anda tidak mempunyai hak akses untuk membuka NIK ini',//63	
				  'Klik di sini untuk mengganti','Assessment Information System', //65
                                  'Telpon','Email',//67
                                  'AIS Realisasi Assessment','AIS Pengumpulan Laporan',//69
                                  '','',//71
				 );
	}
	//else if($_SESSION['lang'] == 'EN'){
	else{
	$word = array('Leave Calendar','Attendance Calendar', 														//1
				  'Absences Calendar','Profile', 																//3
				  'Search','Assessment Request Form',															//5
				  'Range Date','Top',																			//7
				  'Bottom','Range Date',																		//9
				  'Overdue','Overtime',																			//11
				  'Name','AIS Request Status',																			//13
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
				  'Calendar','AIS Assesor Management',//51
				  'Add Assessor','Role',//53
				  'Active','Add Secretary',//55
				  'Modul','Edit Secretary',//57
				  'Edit HR','Type',//59
				  'Org ID','By Organization',//61
				  'By Name/NIK','No authorization to read this personnel number',//63
				  'Click here to change','Assessment Information System', //65
                                  'Phone','Email',//67
                                  'AIS Assessment Realization','AIS Report Submission',//69
                                  '','',//71
				 );
	}
	
?>