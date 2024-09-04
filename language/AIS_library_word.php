<?php
	// include "language/Home_library_word.php";
	// echo $word[0];
	if(!isset($_SESSION)){
	   session_start();
	}
	if($_SESSION['lang'] == 'ID'){
	$word = array('Kalender Cuti','Kalender Kehadiran', 												//1
			'Kalender Ketidakhadiran','Profil', 													//3
		          'Cari','Form Permintaan Assessment',													//5
				  'Tanggal','Atas',																		//7
				  'Bawah','Pilih Tanggal',																//9
				  'Keterlambatan','Lembur',																//11
				  'Nama','AIS Status pengajuan',														//13
				  'NIK','Saldo Cuti',																	//15
				  'Catatan: arahkan pointer ke tabel untuk melihat detail','Struktur Organisasi',		//17
				  'Penjadwalan Jam Kerja','Organisasi', 												//19
				  'PILIH','Semua Bawahan', 																//21
				  'Bawahan Langsung','Data Penggantian Biaya Kesehatan', 								//23
				  'Organisasi belum dipilih','Tanggal Faktur',											//25
				  'Tanggal Proses','Alasan',															//27
				  'Total Faktur','Tanggungan Karyawan',									 				//29
				  'Tanggungan Perusahaan','Biaya yang tak diganti',			 							//31
				  'Daftar Pemegang Jabatan','Telah disetujui',					 						//33
				  'Belum disetujui','NIK subordinates belum dipilih',									//35
				  'Kriteria seleksi yang Anda pilih akan membutuhkan waktu proses yang lama.<br /> Apakah Anda ingin melanjutkan proses ?','Ya',											//37
				  'Tidak','Peringatan',																	//39
				  'Klik nama karyawan untuk melihat rincian.','Permintaan Anda tidak berhasil diselesaikan. Silahkan hubungi administrator HR anda atau coba lagi nanti.',						//41
				  'Name','Organisasi Text',																//43
				  'Unit','Pers Admin',																	//45
				  'Tanggal Awal','Tanggal Akhir',														//47
				  'Diinput Oleh','Waktu Input',															//49
				  'Kalender','AIS Pengaturan Assessor',													//51
				  'Tambah Assessor','Peran',															//53
				  'Aktif','Tambah Sekertaris',															//55
				  'Modul','Edit Sekertaris',															//57
				  'Tambah HR','Tipe',																	//59
				  'Org ID','Berdasarkan Organisasi',													//61
				  'Berdasarkan Nama/NIK','Maaf, anda tidak mempunyai hak akses untuk membuka NIK ini',	//63
				  'Klik di sini untuk mengganti','Assessment Information System',						//65
                  'Telpon','Email',																		//67
                  'AIS Realisasi Assessment','AIS Pengumpulan Laporan',									//69
                  '','',																				//71
                  'Terima','Tolak',																		//73
                  'Pending', 'Assessor dipilih',														//75
                  'Assessor belum dipilih', ' Berdasarkan unit HR',										//77
                  'Berdasarkan HR', 'Berdasarkan Assessor',												//79
                  'Berdasarkan SO1', 'Berdasarkan SO2',													//81
                  'Proses update data selesai.', 'Jumlah data',											//83
                  'Jumlah data yang sukses diupdate', 'Jumlah data yang gagal dibaca',					//85
                  'Proses update data gagal', 'File yang diupload harus berformat .xls (excel)',		//87
                  'Nama', 'Unit',																		//89
                  'Posisi', 'Unit ke 1',																//91
                  'AIS Upload Hasil Assessment Excel', 'Pada saat pembuatan excel harap diperhatikan beberapa notes berikut ini:',																//93
                  '1. Data harus dimulai Pada baris dan kolom A14', '2. File yang diupload harus XLS 1997-2003 excel format',															//95
                  '3. Data harus dimulai Pada baris dan kolom A14', '4. data harus dalam format string dengan di beri kutip satu sebelum penulisan data seperti pada contoh',					//97
                  '5. date ditulis dalam format ( DD / MM / YYYY )', '6. Silahkan lihat object ID qualification di SAP',															//99
                  'Lokasi','Assessor',																	//101
                  'Status','Update',																	//103
                  'Asesi','Tipe Form',																	//105
                  'Tipe Asesi','Kompetensi Unit',														//107
                  'Tidak ada kompetensi unit','Ada kompetensi unit',									//109
                  'Posisi ke 1', 'Unit ke 2',															//111
                  'Posisi ke 2', 'Pemetaan Assessment',													//113
                  'Tujuan Assessment', 'Jadwal Assessment',												//115
                  'Prioritas', 'Lokasi Assessment',														//117
                  'Jadwal Feedback', 'Lokasi Feedback',													//119
                  'Alasan Penolakan/Penerimaan Superior', 'Alasan Penolakan/Penerimaan ACKG',			//121
                  'Nama Superior', 'Progres',															//123
                  'Realisasi Tanggal Assessment', 'Tanggal Pengumpulan Laporan',						//125
                  'Tanggal Pengiriman ke Unit', 'Tanggal Feedback',										//127
                  'Tanggal Deadline Pengumpulan Laporan','Tanggal Realisasi Pengumpulan Laporan',		//129
                  'Layer ke 1', 'Layer ke 2',															//131
                  'Direktorat', 'Divisi',																//133
                  'Departemen', 'Section',																//135
                  'Laporan','Penerimaan Assessment',													//137
                  'Data Permintaan Assessment', 'HR Unit Lain',											//139
                  'Request Form', 'Diterima oleh ACKG / Superior',										//141
                  'Realisasi Assessment', 'Pengumpulan Laporan',											//143
                  'Pengiriman ke Unit', 'Feedback',														//145
                  'Tanggal Permintaan', 'Tanggal Assessment',											//147
                  'Status Penerimaan Superior', 'Status Penerimaan ACKG',								//149
                  'Detail', 'Ubah',																		//151
                  'Unit Ke', 'Posisi Ke',																//153
                  'Layer Ke','Layer ke 2',																//155
                  'Alasan', 'Apa Anda yakin ingin menghapus item ini?',									//157
                  'Perintah ini tidak dapat dibatalkan', 'Ya',											//159
                  'Tidak', 'Cari',																		//161
                  'Silahkan mengirimkan persyaratan unit ke email', 'Data Karyawan',                                     //163
                  'Apakah Anda yakin dengan data yang ada di form?', 'AIS Master Assessor',                                    //165
                  'AIS Realisasi Assessment', ''
				 );
	}
	//else if($_SESSION['lang'] == 'EN'){
	else{
	$word = array('Leave Calendar','Attendance Calendar', 												//1
				  'Absences Calendar','Profile', 														//3
				  'Search','Assessment Request Form',													//5
				  'Range Date','Top',																	//7
				  'Bottom','Range Date',																//9
				  'Overdue','Overtime',																	//11
				  'Name','AIS Request Status',															//13
				  'NIK','Leave Balance',																//15
				  'Note: Mouse over your pointer on the table to see the detail',
				      'Tree Chart Organization',														//17
				  'Shift Scheduling','Organization',											 		//19
				  'SELECT','All Subordinate', 															//21
				  'Direct Subordinate','Medical Reimburse Data',  										//23
				  'Organization has not been selected','Invoice Date',									//25
				  'Process Date','Reason',																//27
				  'Invoice Total','Employee Payment',													//29
				  'Company Payment','Non-Reimburse Amount',												//31
				  'Job Holder List','Approved',															//33
				  'Pending Approved','NIK has not been selected',										//35
				  'Selection criteria could result in a long runtime.<br /> Do you want to continue processing?','Yes',																  //37
				  'No','Warning',																		//39
				  'Click the employee name to view the details.','Your request was not completed successfully. Please contact your HR administrator or try again later.',			//41
				  'Name','Organization Text',																//43
				  'Unit','Pers Admin',																	//45
				  'Start Date','End Date',																//47
				  'Input By','Input Time',																//49
				  'Calendar','AIS Assesor Management',													//51
				  'Add Assessor','Role',																//53
				  'Active','Add Secretary',																//55
				  'Modul','Edit Secretary',																//57
				  'Edit HR','Type',																		//59
				  'Org ID','By Organization',															//61
				  'By Name/NIK','No authorization to read this personnel number',						//63
				  'Click here to change','Assessment Information System',								//65
                  'Phone','Email',																		//67
                  'AIS Assessment Realization','AIS Report Submission',									//69
                  '','',																				//71
                  'Approve', 'Reject',																	//73
                  'Pending', 'Assessor has been chosen',													//75
                  'Assessor has not been chosen', 'By HR unit',											//77
                  'By HR', 'By Assessor',																//79
                  'By SO1', 'By SO2',																	//81
                  'Update data process is complete.', 'Amount of data',									//83
                  'Amount of data successfully updated', 'Amount of data failed to read',				//85
                  'Data update process is failed', 'The file must be in .xls (excel) format',	//87
                  'Name', 'Unit',																		//89
                  'Position', 'Unit ke 1',																//91
                  'AIS Upload Hasil Assessment Excel', 'Pada saat pembuatan excel harap diperhatikan beberapa notes berikut ini:',																//93
                  '1. Data harus dimulai Pada baris dan kolom A14', '2. File yang diupload harus XLS 1997-2003 excel format',															//95
                  '3. Data harus dimulai Pada baris dan kolom A14', '4. data harus dalam format string dengan di beri kutip satu sebelum penulisan data seperti pada contoh',					//97
                  '5. date ditulis dalam format ( DD / MM / YYYY )', '6. Silahkan lihat object ID qualification di SAP',															//99
                  'Location','Assessor',																//101
                  'Status','Update',																	//103
                  'Assesse','Form Type',																//105
                  'Assesse Type','Unit Competency',														//107
                  'Unit Competency Unavailable','Unit Competency Available',									//109
                  'Posisi ke 1', 'Unit ke 2',															//111
                  'Posisi ke 2', 'Assessment Mapping',													//113
                  'Assessment Purpose', 'Assessment Schedule',												//115
                  'Priority', 'Assessment Location',														//117
                  'Feedback Schedule', 'Feedback Location',													//119
                  "Superior's Approval/Rejection Reason(s)", "ACKG's Approval/Rejection Reason(s)",			//121
                  "Superior's Name", 'Progress',															//123
                  'Assessment Date Realization', 'Report Submission Date',						//125
                  'Send to Unit Date', 'Feedback Date',										//127
                  'Report Submission Deadline Date','Report Submission Realization Date',		//129
                  'Layer ke 1', 'Layer ke 2',															//131
                  'Directorate', 'Division',																//133
                  'Department', 'Section',																//135
                  'Report','Assessment Approval',													//137
                  'Request Data Assessment', 'Other HR Unit',											//139
                  'Request Form', 'Approved by ACKG/Superior',										//141
                  'Assessment Realization', 'Report Submission',											//143
                  'Send to Unit', 'Feedback',														//145
                  'Request Date', 'Assessment Date',											//147
                  'Superior Approval Status', 'ACKG Approval Status',								//149
                  'Detail', 'Edit',																		//151
                  'Unit Ke', 'Posisi Ke',																//153
                  'Layer Ke','Layer ke 2',																//155
                  'Reason', 'Are you sure you want to delete this item?',								//157
                  'This command cannot be undone', 'Yes',												//159
                  'No', 'Search',																		//161
                  'Please send unit requirement via email', 'Employee Data',                                     //163
                  'Are you sure with the data on the form?', 'AIS Assessor Master',                                    //165
                  'AIS Assessment Realization', ''
				 );
	}
	
?>