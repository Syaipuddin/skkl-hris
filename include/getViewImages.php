<?php
	ini_set("odbc.defaultlrl", "9999999");
	ini_set("magic_quotes_sybase",0);
	ini_set("magic_quotes_gpc", 0);
	ini_set("magic_quotes_runtime", 0);
	if (isset($_POST['id'])) {
		include 'connection.php';
		$id = htmlentities($_POST['id']);
		$query = "SELECT newsImage,newsImageFileName FROM tr_news WHERE newsID = ?";
		$result = odbc_prepare($conn, $query);
		$exec = odbc_execute($result, array($id));

		// $result = odbc_exec($conn,$query);
		$type = odbc_result($result,2);
		$content = base64_decode(odbc_result($result,1));header("Content-type: $type");
		odbc_close($conn);
		echo $content;
	}
?>