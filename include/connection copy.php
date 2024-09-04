<?php 
$sisdmHost = "LAPTOP-8BCN1OEN";
$sisdmUser = "sa";
$sisdmPass = "123456";

$connSISDM = odbc_connect($sisdmHost,$sisdmUser,$sisdmPass);
if( $connSISDM === false )
{
	echo "Unable to connect SISDM.</br>";
	die(odbc_errormsg());
}
$conn = odbc_connect  ( "PORTAL", "username",  "passwd" );

/*if($conn_card == false )
{
	echo "Unable to connect con card.</br>";
	die(odbc_errormsg());
}*/

//set time jakarta
date_default_timezone_set("Asia/Jakarta");

if( $conn === false )
{
     echo "Unable to connect to HR Portal Database.</br>";
     die(odbc_errormsg());
}

/*if( $connSMSLIVE === false )
{
     echo "Unable to connect to HR Portal SMS Database.</br>";
     die(odbc_errormsg());
}
*/
/*if ($conn57 == false) {
     echo "Unable to connect to Database Repository 57.</br>";
     die(odbc_errormsg());
}*/



/*if( $conn_card === false )
{
     echo "Unable to connect to IDCARD Database.</br>";
     die(odbc_errormsg());
}

if( $conn_esurvey === false )
{
     echo "Unable to connect to SURVEY Database.</br>";
     die(odbc_errormsg());
}

if( $connSMSBlast === false )
{
     echo "Unable to connect to SMS Database.</br>";
     die(odbc_errormsg());
}

if( $conn_eSurvey === false )
{
     echo "Unable to connect to SURVEY Database.</br>";
     die(odbc_errormsg());
}
*/

// if( $conn_card === false )
// {
//      echo "Unable to connect to IDCARD Database.</br>";
//      die(odbc_errormsg());
// }
// if ($conn_eSurvey === FALSE) {
// 	echo "Unable to connect eSurvey.</br>";
// 	die(odbc_errormsg());
// }

//include "cekFeedback.php"; //feedback checking
?>
