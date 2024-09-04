<?php
#$sisdmHost = "localhost";
#$sisdmUser = "";
#$sisdmPass = "Hrport@l2012";

#$connSISDM = odbc_connect($sisdmHost,$sisdmUser,$sisdmPass);
#if( $connSISDM === false )
#{
#        echo "Unable to connect SISDM.</br>";
#        die(odbc_errormsg());
#}
$conn = odbc_connect("Driver={SQL Server};Server=localhost;Database=PORTAL", 'mudapane', 'mudapane');
if($conn === false) {
    echo "Tidak dapat terhubung ke PORTAL.</br>";
    die(odbc_errormsg());
}
//$conn_card = odbc_connect("IDCARD", "presensi",  "abc12345");
//$connSMS = odbc_connect  ( "SMS" , "hris00",  "Hrport@l2012" );
//$connSMSBlast = odbc_connect  ( "SMS_BLAST" , "sms_user",  "hr1s0k3" );
//$connRemKes = odbc_connect  ( "REMKES" , "Remkes",  "sekmer" ); 
//$connRemKes = odbc_connect  ( "REMKES" , "hris00",  "Hrport@l2012" ); 
//$conn_eSurvey = odbc_connect  ( "SURVEY" , "hris00",  "Hrport@l2012" );
//$conn_esurvey = odbc_connect("SURVEY", "hris00",  "Hrport@l2012" );
#if($connRemKes == false )
#{
#        echo "Unable to connect REMKES.</br>";
#        die(odbc_errormsg());
#}

//set time jakarta
//date_default_timezone_set("Asia/Jakarta");

if( $conn === false )
{
     echo "Unable to connect to HR Portal Database.</br>";
     die(odbc_errormsg());
}
#if ($conn_eSurvey === FALSE) {
#        echo "Unable to connect eSurvey.</br>";
#        die(odbc_errormsg());
#}
//include "cekFeedback.php"; //feedback checking
?>
