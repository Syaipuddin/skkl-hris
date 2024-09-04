<?php
$sisdmHost = "HRPORTAL";
$sisdmUser = "hris00";
$sisdmPass = "Hrport@l2012";

$connSISDM = odbc_connect("Driver={SQL Server};Server=localhost;Database=PORTAL", 'mudapane', 'mudapane');
if( $connSISDM === false )
{
        echo "Unable to connect SISDM.</br>";
        die(odbc_errormsg());
}

// $conn_dev = odbc_connect("HRPORTAL-DEV", "userPortal", "Hrport@l2012");
$conn = odbc_connect( "Driver={SQL Server};Server=localhost;Database=PORTAL", 'mudapane', 'mudapane');

// $conn_card = odbc_connect("IDCARD", "presensi",  "abc12345678");
// $connSMS = odbc_connect  ( "SMS" , "hris00",  "Hrport@l2012" );
// $connSMSLIVE = odbc_connect  ( "SMS" , "hris00",  "Hrport@l2012" );
// $connSMSBlast = odbc_connect  ( "SMS_BLAST" , "sms_user",  "hr1s0k3" );
#$connRemKes = odbc_connect  ( "REMKES" , "Remkes",  "sekmer" ); 
// $connRemKes = odbc_connect  ( "REMKES" , "hris00",  "Hrport@l2012" ); 
// $conn_eSurvey = odbc_connect  ( "SURVEY" , "hris00",  "Hrport@l2012" );
//$conn_esurvey = odbc_connect("SURVEY", "hris00",  "Hrport@l2012" );
/*if($connRemKes == false )
{
        echo "Unable to connect REMKES.</br>";
        die(odbc_errormsg());
}*/

//set time jakarta
date_default_timezone_set("Asia/Jakarta");

if( $conn === false )
{
     echo "Unable to connect to HR Portal Database.</br>";
     die(odbc_errormsg());
}
// if ($conn_eSurvey === FALSE) {
//         echo "Unable to connect eSurvey.</br>";
//         die(odbc_errormsg());
// }
//include "cekFeedback.php"; //feedback checking

//Set addSlashes & HTML Entities to POST

// function isHTML($string){
//  return $string != strip_tags($string) ? true:false;
// }

// if (isset($_POST)) {
//      foreach ($_POST as $post) {
//           $key = key($_POST);
//             // echo "hehe";
//                echo 'Key: '.key($_POST);
//                echo "<br>";
//                echo 'Value: '.$post;
//                echo "<br>";
//                $_POST[$key] = htmlentities($_POST[$key], ENT_QUOTES);
//                /*echo "<br>";
//                echo "$_POST[$key]";
//                echo "<br>";*/
//           next($_POST);
//      }
// }

   /*try {
        $connCE = new PDO('odbc:HRPORTAL', 'hris00', 'Hrport@l2012');
    } catch (PDOException $e) {
        echo $e->getMessage();
    }*/
?>
