<?php
	include "template/top3.php";
	
	$taskStatus	=	$_REQUEST['task'];
	$skklID	=	$_REQUEST['skklId'];

	if ($taskStatus == md5('updateSKKLHR'))
	{	
		//update flag
		// $updateFlag = odbc_exec($conn, "Update tb_SKKLTransaction set Flag='2'	 where SKKLID='$skklID'");
		odbc_execute($updateFlag = odbc_prepare($conn,"Update tb_SKKLTransaction set Flag='2'	 where SKKLID=?"),array($skklID));
	
		//back to documentlist
		header('location:skklHRList.php');
	}

	include "template/bottom3.php";
?>
