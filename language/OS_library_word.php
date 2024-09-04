<?php
	if(!isset($_SESSION)){
	   session_start();
	}
	if($_SESSION['lang'] == 'ID'){
	$word = array('Aspek Penilaian Evaluasi', 'Tipe Pola', 								//1
					'Jenis Kegiatan','Nama Pekerjaan',									//3
					'Outsourcing','Kabupaten/Kota',										//5
					'Tanggal Mulai','Tanggal Berakhir',									//7
					'Masukan','Biaya(%)',												//9
					'Vendor','Rekapitulasi',											//11
					'Unit','Nilai',														//13
					'Email','Fax',														//15
					'Website','Contact Person',											//17
					'Tahun Berdiri','Pemilik',											//19
					'Kelola','ID Aspek',												//21
					'Nama Aspek','Jumlah Pekerja',										//23
					'Tambah Aspek','Ubah Data',											//25
					'Hapus Data','Update',												//27												
					'Batal','Hapus',													//29
					'Area','Pertanyaan',												//31
					'Kembali','Data perbulan belum diisi',								//33
					'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember', //45
					'Evaluasi','Nilai Evaluasi',										//47
					'Ubah Evaluasi','Bulan Evaluasi',									//49
					'Nilai','Angka',													//51
					'Catatan','Ya','Tidak',												//54
					'wajib','Petunjuk Penilaian',										//56
					'Nomor Urut','Evaluasi',											//58
					'Submit','Alamat',													//60
					'Telepon','Cakupan Area',											//62
					'Unit yang memakai :','Jenis Pekerjaan yang disediakan :',			//64
					'Pilih Area','Pilih Tipe Kegiatan Penunjang',						//66
					'Pilih Jenis Pekerjaan','Data belum ada',							//68
					'Isi','Ubah',														//70
					'Ubah Jumlah Pekerja','Ubah Biaya(%)',								//72
					'Data Perusahaan','Tambah Aspek Penilaian Evaluasi',				//74
					'Reset','Tambah Kegiatan Penunjang Outsourcing',					//76
					'Pilih Pola Outsourcing','Tambah Jenis Pekerjaan Outsourcing',		//78
					'Tambah Tipe Pola Outsourcing','Tambah Pertanyaan Evaluasi',		//80
					'Pilih Aspek Penilaian Evaluasi','Pilih Unit',						//82
					'Pilih Jenis Pekerjaan','Tambah Kolom',								//84
					'Hapus','Tambah Vendor',											//86
					'Kegiatan Penunjang Outsourcing','Tambah Kegiatan',					//88
					'Jenis Pekerjaan Outsourcing','Tambah Pekerjaan',					//90
					'Tipe Pola Outsourcing','Tambah Pola',								//92
					'Pertanyaan Evaluasi','Tambah Pertanyaan',							//94
					'Provinsi','Tahun','Bulan',											//96
					'Data Vendor','Ubah Masukan',										//99
					'Data Setiap Area','Data Setiap Jenis Pekerjaan Outsourcing',		//101
					'Hasil evaluasi Unit ',' terhadap Perusahaan ',						//103
					'Biaya(%) Unit ','No. Urut',										//105
					'Masukan Unit ','',													//107
					);
	}
	//else if($_SESSION[lang] == 'EN'){
	else{
	$word = array('Evaluation of Assessment', 'Outsourcing Type', 						//1
					'Outsourcing Activity','Job Title',									//3
					'Outsourcing','District/City',										//5
					'Start Date','End Date',											//7
					'Feedback','Fee(%)',												//9
					'Vendor','Report',													//11
					'Units','Rating',													//13
					'Email','Fax',														//15
					'Website','Contact Person',											//17
					'Since','Owner',													//19
					'Maintain','Aspect ID',												//21
					'Aspect Name','Total of Worker',									//23
					'Insert Aspect','Edit Data',										//25
					'Delete Data','Update',												//27													
					'Cancel','Delete',													//29
					'Area','Question',													//31
					'Back','Data has not been filled',									//33
					'January','February','March','April','Mey','June','July','August','September','October','November','December', //45
					'Evaluate','Evaluation Score',										//47
					'Edit Evaluation','Evaluation Month',								//49
					'Value','Score',													//51
					'Note','Yes','No',													//54
					'required','Evaluation Clue',										//56
					'Sequence Number','Evaluation',										//58
					'Submit','Address',													//60
					'Telephone','Coverage Area',										//62
					'List of units that use :','List of jobs that provided :',			//64
					'Choose Area','Choose Outsourcing Activity',						//66
					'Choose Job Title','Data does not exist',							//68
					'Insert','Edit',													//70
					'Edit Total of Worker','Edit Fee(%)',								//72
					'Company Profile','Insert Evaluation of Assessment',				//74
					'Reset','Insert Outsourcing Activity',								//76
					'Choose Outsourcing Type','Insert Job Title',						//78
					'Insert Outsourcing Type','Insert Evaluation Question',				//80
					'Choose Evaluation of Assessment','Choose Units',					//82
					'Choose Outsourcing Job','Add More Fields',							//84
					'Remove','Insert Vendor',											//86
					'Outsourcing Activity','Insert Activity',							//88
					'Outsourcing Job Title','Insert Job',								//90
					'Outsourcing Type','Insert Type',									//92
					'Evaluation Question','Insert Question',							//94
					'Province','Year','Month',											//96
					'All Vendor','Edit Feedback',										//99
					'Data of each Area','Data of each Outsourcing Job',					//101
					'Evaluation Score from Units ',' on company ',						//103
					'Fee(%) from Units ','Seq. Num',									//105
					'Feedback from Unit ','',											//107
					);	
	}
?>