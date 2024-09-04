<?php
	// include "language/Home_library_word.php";
	// echo $word[0];
	if(!isset($_SESSION)){
	   session_start();
	}
	if($_SESSION['lang'] == 'ID'){
	$word = array('Tanggal Request','Nama', 			//1
                      'Phone','Kalender Kehadiran', 			//3
                      'Tanggal Lahir','Dari Kota', 			//5
                      'Ke Kota','Tanggal Mulai', 			//7
                      'Tanggal Akhir','Tanggal Mulai DLK', 		//9
                      'Tanggal Akhir DLK','', 		//11
                      'Asal Pergi','Asal Pulang', 			//13
                      'Tujuan Pergi','Tujuan Pulang', 			//15
                      'Alasan Tolak','Dari', 			//17
                      'Ke','Berangkat', 			//19
                      'Pulang','Diundang pihak ketiga', 			//21
                      'Rincian Biaya DLK','Rincian Biaya Pasca', 			//23
                      'Alasan DLK','Perjalanan', 			//25
                      'Akomodasi','Tol', 			//27
                      'BBM','Parkir', 			//29
                      'Entertain','Total Reimburse', 			//31
                      'Tanggal Pengajuan','NIK Karyawan', 		//33
					  'Alasan Entertain','Total Reimburse', 			//35
					  'Entertain','Total Reimburse', 			//37
					  'Entertain','Total Reimburse', 			//39
					  'Entertain','Total Reimburse', 			//41
				 );
	}
	//else if($_SESSION['lang'] == 'EN'){
	else{
	$word = array('Request Date','Name',                            //1
		      'Telpon','Attendance Calendar', 			//3
		      'Birth Date','From City', 			//5
		      'To City','Start Date',                           //7
		      'End Date','Start Date DLK', 			//9
		      'End Date DLK','', 		//11
                      'Origin Pergi','Origin Pulang', 			//13
		      'Destination Pergi','Destination Pulang', 	//15
		      'Rejected Reason','From', 			//17
		      'to','Departure', 			//19
		      'Return','Called by third party', 			//21
              'DLK Cost Details','Post DLK Cost Details', 			//23
		      'DLK Reason','Travel Details', 			//25
		      'Accommodation Cost','Toll Cost', 			//27
		      'Fuel Cost','Parking Cost', 			//29
		      'Entertainment Cost','Reimbursement Total', 			//31
			  'Request Date','Employee NIK', 		//33
			  'Entertainment Reason','Total Reimburse', 			//35
			  'Entertain','Total Reimburse', 			//37
			  'Entertain','Total Reimburse', 			//39
			  'Entertain','Total Reimburse', 			//41		  		  		  	  	  
                                                    
				 );
	}
	
?>