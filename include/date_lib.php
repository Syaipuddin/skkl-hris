<?php
function indonesian_date($timestamp = '', $date_format = 'l, j F Y | H:i', $suffix = 'WIB') {
	if (trim ($timestamp) == '')
	{
	        $timestamp = time ();
	}
	elseif (!ctype_digit ($timestamp))
	{
	    $timestamp = strtotime ($timestamp);
	}
	# remove S (st,nd,rd,th) there are no such things in indonesia :p
	$date_format = preg_replace ("/S/", "", $date_format);
	$pattern = array (
	    '/Mon[^day]/','/Tue[^sday]/','/Wed[^nesday]/','/Thu[^rsday]/',
	    '/Fri[^day]/','/Sat[^urday]/','/Sun[^day]/','/Monday/','/Tuesday/',
	    '/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/',
	    '/Jan[^uary]/','/Feb[^ruary]/','/Mar[^ch]/','/Apr[^il]/','/May/',
	    '/Jun[^e]/','/Jul[^y]/','/Aug[^ust]/','/Sep[^tember]/','/Oct[^ober]/',
	    '/Nov[^ember]/','/Dec[^ember]/','/January/','/February/','/March/',
	    '/April/','/June/','/July/','/August/','/September/','/October/',
	    '/November/','/December/',
	);
	$replace = array ( 'Sen','Sel','Rab','Kam','Jum','Sab','Min',
	    'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu',
	    'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des',
	    'Januari','Februari','Maret','April','Juni','Juli','Agustus','Sepember',
	    'Oktober','November','Desember',
	);
	$date = date ($date_format, $timestamp);
	$date = preg_replace ($pattern, $replace, $date);
	$date = "{$date} {$suffix}";
	return $date;
}
function tanggal_indo($tanggal)
{
	//format "Y-m-d"
    $bulan = array (1 =>   'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember'
                );
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
}

function check_input($data)
{
	$data = trim($data);
    	$data = stripslashes($data);
    	$data = htmlspecialchars($data);
	$data = str_replace("'","",$data);
    	return $data;
}
function convertDate($data)
{
	$rtn="";
	if($data != "00000000")
	{
		$rtn .= substr($data,6,2)." ";
		switch(substr($data,4,2))
		{
			case "01": $rtn .=  "January";break;
			case "02": $rtn .=  "February";break;
			case "03": $rtn .=  "March";break;
			case "04": $rtn .=  "April";break;
			case "05": $rtn .=  "May";break;
			case "06": $rtn .=  "June";break;
			case "07": $rtn .=  "July";break;
			case "08": $rtn .=  "August";break;
			case "09": $rtn .=  "September";break;
			case "10": $rtn .=  "October";break;
			case "11": $rtn .=  "November";break;
			case "12": $rtn .=  "December";break;
		}
		$rtn .=  " ".substr($data,0,4);
	}
	else
		$rtn .=  "-";
	return $rtn;
}

function convertDate5($data) // menampilkan bulan dan tahun saja
{
	$rtn="";
	if($data != "00000000")
	{
		//$rtn .= substr($data,6,2)." ";
		switch(substr($data,4,2))
		{
			case "01": $rtn .=  "January";break;
			case "02": $rtn .=  "February";break;
			case "03": $rtn .=  "March";break;
			case "04": $rtn .=  "April";break;
			case "05": $rtn .=  "May";break;
			case "06": $rtn .=  "June";break;
			case "07": $rtn .=  "July";break;
			case "08": $rtn .=  "August";break;
			case "09": $rtn .=  "September";break;
			case "10": $rtn .=  "October";break;
			case "11": $rtn .=  "November";break;
			case "12": $rtn .=  "December";break;
		}
		$rtn .=  " ".substr($data,0,4);
	}
	else
		$rtn .=  "-";
	return $rtn;
}

function convertDate6($data) // menampilkan tahun dan bulan (contoh --> 2011-01)
{
	$rtn="";
	if($data != "00000000")
	{
		$rtn .=  " ".substr($data,0,4);
                 $rtn .=  "-";
                $rtn .=  " ".substr($data,4,2);
	}
	else
		$rtn .=  "-";
	return $rtn;
}

function convertDateAdis($data)
{
	$rtn="";
	if($data != "00000000000000000000000")     //2011-02-01 00:00:00.000 --> Menampilkan tanggal bulan tahun
	{
		$rtn .= substr($data,8,2)." ";
		switch(substr($data,5,2))
		{
			case "01": $rtn .=  "January";break;
			case "02": $rtn .=  "February";break;
			case "03": $rtn .=  "March";break;
			case "04": $rtn .=  "April";break;
			case "05": $rtn .=  "May";break;
			case "06": $rtn .=  "June";break;
			case "07": $rtn .=  "July";break;
			case "08": $rtn .=  "August";break;
			case "09": $rtn .=  "September";break;
			case "10": $rtn .=  "October";break;
			case "11": $rtn .=  "November";break;
			case "12": $rtn .=  "December";break;
		}
		$rtn .=  " ".substr($data,0,4);
	}
	else
		$rtn .=  "-";
	return $rtn;
}

function convertDateDimas($data)
{
	$rtn="";
	if($data != "00000000000000000000000")     //01 jan 2016 --> Menampilkan tanggal bulan 3 huruf dan tahun
	{
		$rtn .= substr($data,8,2)." ";
		switch(substr($data,5,2))
		{
			case "01": $rtn .=  "Jan";break;
			case "02": $rtn .=  "Feb";break;
			case "03": $rtn .=  "Mar";break;
			case "04": $rtn .=  "Apr";break;
			case "05": $rtn .=  "May";break;
			case "06": $rtn .=  "Jun";break;
			case "07": $rtn .=  "Jul";break;
			case "08": $rtn .=  "Aug";break;
			case "09": $rtn .=  "Sep";break;
			case "10": $rtn .=  "Oct";break;
			case "11": $rtn .=  "Nov";break;
			case "12": $rtn .=  "Dec";break;
		}
		$rtn .=  " ".substr($data,0,4);
	}
	else
		$rtn .=  "-";
	return $rtn;
}

function convertDateAdis2($data)
{
	$rtn="";
	if($data != "00000000000000000000000")     //2011-02-01 00:00:00.000 --> Menampilkan Bulan dan Tahun saja
	{
		//$rtn .= substr($data,8,2)." ";
		switch(substr($data,5,2))
		{
			case "01": $rtn .=  "January";break;
			case "02": $rtn .=  "February";break;
			case "03": $rtn .=  "March";break;
			case "04": $rtn .=  "April";break;
			case "05": $rtn .=  "May";break;
			case "06": $rtn .=  "June";break;
			case "07": $rtn .=  "July";break;
			case "08": $rtn .=  "August";break;
			case "09": $rtn .=  "September";break;
			case "10": $rtn .=  "October";break;
			case "11": $rtn .=  "November";break;
			case "12": $rtn .=  "December";break;
		}
		$rtn .=  " ".substr($data,0,4);
	}
	else
		$rtn .=  "-";
	return $rtn;
}

function convertDay($data)
{
	$rtn="";
	switch($data)
	{
		case 'Senin':$rtn='Monday';break;
		case 'Selasa':$rtn='Tuesday';break;
		case 'Rabu':$rtn='Wednesday';break;
		case 'Kamis':$rtn='Thursday';break;
		case 'Jumat':$rtn='Friday';break;
		case 'Sabtu':$rtn='Saturday';break;
		case 'Minggu':$rtn='Sunday';break;
	}
	
	return $rtn;
}
function convertDate2($data)
{
	$rtn="";
	if($data != "00000000")
	{
		$rtn .= substr($data,6,2)."/".substr($data,4,2)."/".substr($data,0,4);
	}
	else
		$rtn .=  "-";
	return $rtn;
}
function convertDate3($data)
{
	$rtn="";
	if($data != "00000000")
	{
		$rtn .= substr($data,6,2)."/".substr($data,4,2)."/".substr($data,0,4);
	}
	else
		$rtn .=  "-";
	return $rtn;
}

function convertDate4($data)
{
	$rtn="";
	if($data != "00000000")
	{
		$rtn .= substr($data,6,2)."-".substr($data,4,2)."-".substr($data,0,4);
	}
	else
		$rtn .=  "-";
	return $rtn;
}
function convert2($data)
{
	$str="";
	$x=strlen($data);
	while($x > 0)
	{
		if($x>12)
		{
			$str .= substr($data,0,$x-12).".";
			$data = substr($data,$x-12);
		}
		else if($x>9)
		{
			$str .= substr($data,0,$x-9).".";
			$data = substr($data,$x-9);
		}
		else if($x>6)
		{
			$str .= substr($data,0,$x-6).".";
			$data = substr($data,$x-6);
		}
		else if($x>3)
		{
			$str .= substr($data,0,$x-3).".";
			$data = substr($data,$x-3);
		}
		else
		{
			$str .= $data;
			$data = "";
			$x = 0;
			break;
		}
		$x=strlen($data);
	}
	$str .= "";
	return $str;
}

function isLeapYear ($year){
	if ($year % 100 ==0 and $year % 400 ==0){
		return 1;
	} else if ($year %4 == 0){
		return 1;
	} else {
		return 0;
	}
}

function isValidDate ($year,$month,$date){
	if (isLeapYear($year)==true){
		if ($month == 1 or $month == 3 or $month == 5 or $month == 7 or $month == 8 or $month == 10 or  $month == 12){
			if ($date >=1 and $date <= 31) {
				return 1;
			} else {
				return 0;
			}
		} else if ($month == 4 or $month == 6 or$month == 9 or $month == 11) {
			if ($date >=1 and $date <= 30) {
				return 1;
			} else {
				return 0;
			}
		} else if ($month ==2) {
			if ($date >=1 and $date <= 29) {
				return 1;
			} else {
				return 0;
			}
		}
	} else {
		if ($month == 1 or $month == 3 or $month == 5 or $month == 7 or $month == 8 or $month == 10 or  $month == 12){
			if ($date >=1 and $date <= 31) {
				return 1;
			} else {
				return 0;
			}
		} else if ($month == 4 or $month == 6 or$month == 9 or $month == 11) {
			if ($date >=1 and $date <= 30) {
				return 1;
			} else {
				return 0;
			}
		} else if ($month ==2) {
			if ($date >=1 and $date <= 28) {
				return 1;
			} else {
				return 0;
			}
		}
	}
}


function getTimestamp($data)
{
    $partes = explode('-', $data);
    return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
}

function isRangeDate($date_from_user,$start_date,$end_date="31-12-2035"){
	$startDatedt = getTimestamp($start_date);
	$endDatedt = getTimestamp($end_date);
	$usrDatedt = getTimestamp($date_from_user);

	if (($usrDatedt >= $startDatedt) && ($usrDatedt <= $endDatedt)){
	  return true;
	}else{
	 	return false;
	}
}

function startDateMounth($mounthBack=1){
	$timeStamp= mktime(0, 0, 0, date("m")-$mounthBack, 1,   date("Y"));
	return date('d-m-Y',$timeStamp);
}

// validate attendance dan SKKL untuk pengajuan
function checkDateMealAllowanceReq($tahunAttendanceReq,$bulanAttendanceReq,$tglAttendanceReq)
{
	//set variable
	$tglSystemNow = date('Ymd');

	//parse date system
	$tahunSystem = substr($tglSystemNow, 0,4);
	$bulanSystem = substr($tglSystemNow, 4,2);
	$tglSystem = substr($tglSystemNow, 6,2);

	//bulan system dikurangi 1 bulan
	$pastMonth = date("m", strtotime("-1 month")) ;
	//tahun system dikurangi 1 tahun
	$pastYear = date("Y", strtotime("-1 year")) ;

	//condition validate
	if ($bulanSystem == $bulanAttendanceReq && $tahunSystem == $tahunAttendanceReq)
	{
	   //insert ke table
		return true;
	}
	else
	{
	   if ($bulanSystem != 1)
	   {
	   	  // jangan lupa balikin lagi jadi < 3
	      if ($tglSystem < 3 && $bulanAttendanceReq == $pastMonth && $tahunAttendanceReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($tglSystem < 5 && $bulanAttendanceReq == $pastMonth && $tahunAttendanceReq >= $tahunSystem && $tahunSystem.$bulanSystem == "202403")
	      {
	      	return true;
	      }
	      elseif ($bulanAttendanceReq >= $bulanSystem && $tahunAttendanceReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($bulanAttendanceReq < $bulanSystem && $tahunAttendanceReq > $tahunSystem)
	      {
	         return true;
	      }
	      else
		   {
			   return false;
		   }
		}
	   else
	   {
	      if($tglSystem < 3 && $bulanAttendanceReq >= $bulanSystem && $tahunAttendanceReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($tglSystem < 3 && $bulanAttendanceReq == 12 && $tahunAttendanceReq==$pastYear)
	      {
	         return true;
	      }
	      elseif ($bulanAttendanceReq >= $bulanSystem && $tahunAttendanceReq >= $tahunSystem)
	      {
	         return true;
	      }
	      else
	      {
	         return false;
	      }
	   }
	}
}

// function sementara doang

// validate attendance dan SKKL untuk pengajuan
function checkDateMealAllowanceReqSementara($tahunAttendanceReq,$bulanAttendanceReq,$tglAttendanceReq)
{
	//set variable
	$tglSystemNow = date('Ymd');

	//parse date system
	$tahunSystem = substr($tglSystemNow, 0,4);
	$bulanSystem = substr($tglSystemNow, 4,2);
	$tglSystem = substr($tglSystemNow, 6,2);

	//bulan system dikurangi 1 bulan
	$pastMonth = date("m", strtotime("-1 month")) ;
	//tahun system dikurangi 1 tahun
	$pastYear = date("Y", strtotime("-1 year")) ;

	//condition validate
	if ($bulanSystem == $bulanAttendanceReq && $tahunSystem == $tahunAttendanceReq)
	{
	   //insert ke table
		return true;
	}
	else
	{
	   if ($bulanSystem != 1)
	   {
	      if ($tglSystem < 6 && $bulanAttendanceReq == $pastMonth && $tahunAttendanceReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($bulanAttendanceReq >= $bulanSystem && $tahunAttendanceReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($bulanAttendanceReq < $bulanSystem && $tahunAttendanceReq > $tahunSystem)
	      {
	         return true;
	      }
	      else
		   {
			   return false;
		   }
		}
	   else
	   {
	      if($tglSystem < 3 && $bulanAttendanceReq >= $bulanSystem && $tahunAttendanceReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($tglSystem < 3 && $bulanAttendanceReq == 12 && $tahunAttendanceReq==$pastYear)
	      {
	         return true;
	      }
	      elseif ($bulanAttendanceReq >= $bulanSystem && $tahunAttendanceReq >= $tahunSystem)
	      {
	         return true;
	      }
	      else
	      {
	         return false;
	      }
	   }
	}
}

function checkDateMealAllowanceAgreeSementara($tahunAttendanceReq,$bulanAttendanceReq,$tglAttendanceReq)
{
	//set variable
	$tglSystemNow = date('Ymd');

	//parse date system
	$tahunSystem = substr($tglSystemNow, 0,4);
	$bulanSystem = substr($tglSystemNow, 4,2);
	$tglSystem = substr($tglSystemNow, 6,2);

	//bulan system dikurangi 1 bulan
	$pastMonth = date("m", strtotime("-1 month")) ;
	//tahun system dikurangi 1 tahun
	$pastYear = date("Y", strtotime("-1 year")) ;
/*
	var_dump($tahunAttendanceReq);
	echo "<br>";
	var_dump($bulanAttendanceReq);
	echo "<br>";
	var_dump($tglAttendanceReq);
	echo "<br>";*/

	//condition validate
	if ($bulanSystem == $bulanAttendanceReq && $tahunSystem == $tahunAttendanceReq)
	{
	   //insert ke table
		return true;
	}
	else
	{
	   if ($bulanSystem != 1)
	   {
	      if ($tglSystem < 7 && $bulanAttendanceReq == $pastMonth && $tahunAttendanceReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($bulanAttendanceReq >= $bulanSystem && $tahunAttendanceReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($bulanAttendanceReq < $bulanSystem && $tahunAttendanceReq > $tahunSystem)
	      {
	         return true;
	      }
	      else
		   {
			   return false;
		   }
		}
	   else
	   {
	      if($tglSystem < 5 && $bulanAttendanceReq >= $bulanSystem && $tahunAttendanceReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($tglSystem < 5 && $bulanAttendanceReq == 12 && $tahunAttendanceReq==$pastYear)
	      {
	         return true;
	      }
	      elseif ($bulanAttendanceReq >= $bulanSystem && $tahunAttendanceReq >= $tahunSystem)
	      {
	         return true;
	      }
	      else
	      {
	         return false;
	      }
	   }
	}
}


// function sementara doang - END


function checkDateOvertimeReq($tahunOvertimeReq,$bulanOvertimeReq,$tglOvertimeReq)
{
	//set variable
	$tglSystemNow = date('Ymd');

	//parse date system
	$tahunSystem = substr($tglSystemNow, 0,4);
	$bulanSystem = substr($tglSystemNow, 4,2);
	$tglSystem = substr($tglSystemNow, 6,2);

	//bulan system dikurangi 1 bulan
	$pastMonth = date("m", strtotime("-1 month")) ;
	//tahun system dikurangi 1 tahun
	$pastYear = date("Y", strtotime("-1 year")) ;

	//condition validate
	if ($bulanSystem == $bulanOvertimeReq && $tahunSystem == $tahunOvertimeReq)
	{
	   //insert ke table
		return true;
	}
	else
	{
	   if ($bulanSystem != 1)
	   {
	      if ($tglSystem < 3 && $bulanOvertimeReq == $pastMonth && $tahunOvertimeReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($bulanOvertimeReq >= $bulanSystem && $tahunOvertimeReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($bulanOvertimeReq < $bulanSystem && $tahunOvertimeReq > $tahunSystem)
	      {
	         return true;
	      }
	      else
		   {
			   return false;
		   }
		}
	   else
	   {
	      if($tglSystem < 3 && $bulanOvertimeReq >= $bulanSystem && $tahunOvertimeReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($tglSystem < 3 && $bulanOvertimeReq == 12 && $tahunOvertimeReq==$pastYear)
	      {
	         return true;
	      }
	      elseif ($bulanOvertimeReq >= $bulanSystem && $tahunOvertimeReq >= $tahunSystem)
	      {
	         return true;
	      }
	      else
	      {
	         return false;
	      }
	   }
	}
}

// Akhir script buat atasan approve
// validate attendance dan SKKL untuk pengajuan
function checkDateMealAllowanceAgree($tahunAttendanceReq,$bulanAttendanceReq,$tglAttendanceReq)
{
	//set variable
	$tglSystemNow = date('Ymd');

	//parse date system
	$tahunSystem = substr($tglSystemNow, 0,4);
	$bulanSystem = substr($tglSystemNow, 4,2);
	$tglSystem = substr($tglSystemNow, 6,2);

	//bulan system dikurangi 1 bulan
	$pastMonth = date("m", strtotime("-1 month")) ;
	//tahun system dikurangi 1 tahun
	$pastYear = date("Y", strtotime("-1 year")) ;
/*
	var_dump($tahunAttendanceReq);
	echo "<br>";
	var_dump($bulanAttendanceReq);
	echo "<br>";
	var_dump($tglAttendanceReq);
	echo "<br>";*/

	//condition validate
	if ($bulanSystem == $bulanAttendanceReq && $tahunSystem == $tahunAttendanceReq)
	{
	   //insert ke table
		return true;
	}
	else
	{
	   if ($bulanSystem != 1)
	   {
	      if ($tglSystem < 5 && $bulanAttendanceReq == $pastMonth && $tahunAttendanceReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($bulanAttendanceReq >= $bulanSystem && $tahunAttendanceReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($bulanAttendanceReq < $bulanSystem && $tahunAttendanceReq > $tahunSystem)
	      {
	         return true;
	      }
	      else
		   {
			   return false;
		   }
		}
	   else
	   {
	      if($tglSystem < 5 && $bulanAttendanceReq >= $bulanSystem && $tahunAttendanceReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($tglSystem < 5 && $bulanAttendanceReq == 12 && $tahunAttendanceReq==$pastYear)
	      {
	         return true;
	      }
	      elseif ($bulanAttendanceReq >= $bulanSystem && $tahunAttendanceReq >= $tahunSystem)
	      {
	         return true;
	      }
	      else
	      {
	         return false;
	      }
	   }
	}
}

function checkDateOvertimeAgree($tahunOvertimeReq,$bulanOvertimeReq,$tglOvertimeReq)
{
	//set variable
	$tglSystemNow = date('Ymd');

	//parse date system
	$tahunSystem = substr($tglSystemNow, 0,4);
	$bulanSystem = substr($tglSystemNow, 4,2);
	$tglSystem = substr($tglSystemNow, 6,2);

	//bulan system dikurangi 1 bulan
	$pastMonth = date("m", strtotime("-1 month")) ;
	//tahun system dikurangi 1 tahun
	$pastYear = date("Y", strtotime("-1 year")) ;

	//condition validate
	if ($bulanSystem == $bulanOvertimeReq && $tahunSystem == $tahunOvertimeReq)
	{
	   //insert ke table
		return true;
	}
	else
	{
	   if ($bulanSystem != 1)
	   {
	      if ($tglSystem < 6 && $bulanOvertimeReq == $pastMonth && $tahunOvertimeReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($bulanOvertimeReq >= $bulanSystem && $tahunOvertimeReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($bulanOvertimeReq < $bulanSystem && $tahunOvertimeReq > $tahunSystem)
	      {
	         return true;
	      }
	      else
		   {
			   return false;
		   }
		}
	   else
	   {
	      if($tglSystem < 5 && $bulanOvertimeReq >= $bulanSystem && $tahunOvertimeReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($tglSystem < 5 && $bulanOvertimeReq == 12 && $tahunOvertimeReq==$pastYear)
	      {
	         return true;
	      }
	      elseif ($bulanOvertimeReq >= $bulanSystem && $tahunOvertimeReq >= $tahunSystem)
	      {
	         return true;
	      }
	      else
	      {
	         return false;
	      }
	   }
	}
}
// Akhir script buat pengajuan

function checkDateOvertimeAgreeTgl7($tahunOvertimeReq,$bulanOvertimeReq,$tglOvertimeReq)
{
	//set variable
	$tglSystemNow = date('Ymd');

	//parse date system
	$tahunSystem = substr($tglSystemNow, 0,4);
	$bulanSystem = substr($tglSystemNow, 4,2);
	$tglSystem = substr($tglSystemNow, 6,2);

	//bulan system dikurangi 1 bulan
	$pastMonth = date("m", strtotime("-1 month")) ;
	//tahun system dikurangi 1 tahun
	$pastYear = date("Y", strtotime("-1 year")) ;

	//condition validate
	if ($bulanSystem == $bulanOvertimeReq && $tahunSystem == $tahunOvertimeReq)
	{
	   //insert ke table
		return true;
	}
	else
	{
	   if ($bulanSystem != 1)
	   {
	      if ($tglSystem < 6 && $bulanOvertimeReq == $pastMonth && $tahunOvertimeReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($bulanOvertimeReq >= $bulanSystem && $tahunOvertimeReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($bulanOvertimeReq < $bulanSystem && $tahunOvertimeReq > $tahunSystem)
	      {
	         return true;
	      }
	      else
		   {
			   return false;
		   }
		}
	   else
	   {
	      if($tglSystem < 8 && $bulanOvertimeReq >= $bulanSystem && $tahunOvertimeReq >= $tahunSystem)
	      {
	         return true;
	      }
	      elseif ($tglSystem < 8 && $bulanOvertimeReq == 12 && $tahunOvertimeReq==$pastYear)
	      {
	         return true;
	      }
	      elseif ($bulanOvertimeReq >= $bulanSystem && $tahunOvertimeReq >= $tahunSystem)
	      {
	         return true;
	      }
	      else
	      {
	         return false;
	      }
	   }
	}
}
// Akhir script buat pengajuan


function check_same($array)
{
	$max = count($array);
	for ($i=0; $i<$max ; $i++) {
		$target = $array[$i];
		for ($j=$i+1; $j<$max ; $j++) {
			if($i!=$j){
				if($target==$array[$j]){
					return true;
				}
			}
		}
	}
	return false;
}

function convertDateYMD($data)
{
	$rtn="";
	if($data != "00000000")
	{
		$rtn .= substr($data,0,4)."-".substr($data,4,2)."-".substr($data,6,2);
	}
	else
		$rtn .=  "-";
	return $rtn;
}

function idr_money_format($angka)
{
        $money = number_format($angka,2,'.',',');
        return $money;
}


function convertDateSQL($data)
{
	$rtn="";
	if($data != "00000000000000000000000")     //2011-02-01 00:00:00.000 --> Menampilkan tahun-bulan-tanggal
	{
		$rtn .= substr($data,0,4)."-";
		$rtn .= substr($data,5,2);
		$rtn .=  "-".substr($data,8,2);
	}
	else
		$rtn .=  "-";
	return $rtn;
}

?>
