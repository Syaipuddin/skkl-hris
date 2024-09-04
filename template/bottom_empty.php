<?php
odbc_close($conn);
odbc_close($connSISDM);
include "include/sapdisconnect.php";
ob_flush() ?>
