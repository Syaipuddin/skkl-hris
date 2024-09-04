<?php 
odbc_close($conn);
odbc_close($connSISDM);
unset($_SESSION['auth_mykg']);
include "include/sapdisconnect.php";
ob_flush() ?>
