<?php
function check_input($data)
{
	$data = trim($data);
    	$data = stripslashes($data);
    	//$data = htmlspecialchars($data);
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
?>