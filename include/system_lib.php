<?php
	date_default_timezone_set('asia/jakarta');
 function track_user($conn,$type,$NIK){
 	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  //check ip from share internet{
    $ip=$_SERVER['HTTP_CLIENT_IP'];
  }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
  } else  {
    $ip=$_SERVER['REMOTE_ADDR'];
  }

	//echo $NIK;
	$GETDATE = date("Y-m-d h:i:s");
	//echo $type;
	//echo $ip;
//	$sql = "INSERT INTO tr_system_track ([NIK],[date_time],[type],[IP_Address]) VALUES ('$NIK','$GETDATE','$type','$ip')";
 // odbc_exec($conn, $sql);

 }
?>
