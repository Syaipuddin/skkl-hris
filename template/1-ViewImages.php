<?php 
	ini_set("odbc.defaultlrl", "9999999");
	ini_set("magic_quotes_sybase",0);
	ini_set("magic_quotes_gpc", 0);
	ini_set("magic_quotes_runtime", 0);
	if(isset($_GET['id']))
	{
		include "../include/connection.php";
		$id    = htmlentities($_GET['id']);
		// $query = "SELECT newsImage,newsImageFileName FROM tr_news WHERE newsID = '$id'";
		$query = "SELECT newsImage,newsImageFileName FROM tr_news WHERE newsID = ?";
		$result = odbc_prepare($conn, $query);
		$exec = odbc_execute($result, array($id));

		// $result = odbc_exec($conn,$query);
		// odbc_execute($result = odbc_prepare($conn,$query), array($id));
		$type = odbc_result($result,2);
		$content = base64_decode(odbc_result($result,1));header("Content-type: $type");
		echo $content;
		odbc_close($conn);
	}?>