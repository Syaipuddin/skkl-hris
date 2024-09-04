<?php
	// include "language/Home_library_word.php";
	// echo $word[0];
	if(!isset($_SESSION)){
	   session_start();
	}
	if($_SESSION['lang'] == 'ID'){
	$word = array('Nama Acara','Tanggal Mulai', //1
					'bahasa', 'Tanggal Selesai',//3
					'Pengambilan Beasiswa Pertama', 'Tahun Ajaran',//5
					'Lokasi Tes', 'Form Pembukaan Pendaftaran Beasiswa Perguruan Tinggi',//7
					'Apakah Anda Yakin', '',//9
					'Pendaftar Beasiswa', 'Periode Daftar',//11
					'NIK', 'Nama Karyawan',//13
					'Unit', 'Nama Anak',//15
					'Nilai PK', 'Rata2 UAS',//17
					'Rata2 UN', 'Periode Pendaftaran',//19
					'NIK', 'Nama Karyawan',//21
					'Nama Anak', 'Status',//23
					'Nama Bank', 'Nama Rekening',//25
					'Nomer Rekening', 'Semester 1',//27
					'Semester 2', 'Semester 3',//29
					'Semester 4', 'Semester 5',//31
					'Semester 6', 'Semester 7',//33
					'Semester 8', 'Semester 9',//35
					'Semester 10', 'IPK',//37
					'Nominal', 'Bulan Ambil',//39
					'IPK', 'Nominal',//41
					'Bulan Ambil', 'IPK',//43
					'Nominal', 'Bulan Ambil',//45
					'IPK', 'Nominal',//47
					'Bulan Ambil', 'IPK',//49
					'Nominal', 'Bulan Ambil',//51
					'IPK', 'Nominal',//53
					'Bulan Ambil', 'IPK',//55
					'Nominal', 'Bulan Ambil',//57
					'IPK', ' Nominal',//59
					'Bulan Ambil', 'IPK',//61
					'Nominal', 'Bulan Ambil',//63
					'IPK', 'Nominal',//65
					'Bulan Ambil', '',//67
					'Daftar Siswa', 'Periode Daftar',//69
					'NIK', 'Nama Karyawan',//71
					'Unit', 'Nama Anak',//73
					'Tahun Masuk', 'Status Daftar',//75
					'Status Test', '',//77
					'Upload Data Lama', '',//79
					


                                );
	}
	//else if($_SESSION['lang'] == 'EN'){
	else{
	$word = array('Event Name','Start Date', //1
					'language', 'End Date', //3	
					'Scholarship Retrieval','Academic Year' , //5	
					'Test Location', 'Widyadharma Scholarship Registration ',//7	
					' Are You Sure? ', '',//9	  
					'Scholarship Registrants', 'Registration Period',//11
					'Personnel No', 'Employee Name',//13
					'Unit', 'Child Name',//15
					'Final Grade', 'Average Final Test' ,//17
					'Average National Test', 'Registration Period',//19
					'NIK', 'Employee Name',//21
					'Child Name', 'Status',//23
					'Bank Name', 'Account Name',//25
					'Rekening Number', 'Semester 1',//27
					'Semester 2', 'Semester 3',//29
					'Semester 4', 'Semester 5',//31
					'Semester 6', 'Semester 7',//33
					'Semester 8', 'Semester 9',//35
					'Semester 10', 'GPA',//37
					'Nominal', 'Withdraw',//39
					'GPA', 'Nominal',//41
					'Withdraw', 'GPA',//43
					'Nominal', 'Withdraw',//45
					'GPA', 'Nominal',//47
					'Withdraw', 'GPA',//49
					'Nominal', 'Withdraw',//51
					'GPA', 'Nominal',//53
					'Withdraw', 'GPA',//55
					'Nominal', 'Withdraw',//57
					'GPA', 'Nominal',//59
					'Withdraw', 'GPA',//61
					'Nominal', 'Withdraw',//63
					'GPA', 'Nominal',//65
					'Withdraw', '',//67
					'Student List', 'Registration Period',//69
					'Personnel No', 'Employee Name',//71
					'Unit','Child Name',//73
					'Year In', 'Registration Status',//75
					'Test Status', '',//77
					'Upload Old Data', '',//79


              );
	}
	
?>