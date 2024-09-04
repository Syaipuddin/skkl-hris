<?php
		include "template/top3.php";
	include "include/date_lib.php";
	require "include/class.phpmailer.php";	
?>
<div class="row span9">
<?php
	
	$id	=	$_REQUEST['id'];
	$task	=	$_REQUEST['task'];
//echo md5('confirmSKKL');	
	
	if ($task == md5('confirmSKKL'))
	{
		
		//save data
	   	// $update=odbc_exec($conn,"UPDATE tb_SKKLTransaction SET Accepted = 'true' where SKKLID='$id'");

		odbc_execute($update = odbc_prepare($conn,"UPDATE tb_SKKLTransaction SET Accepted = 'true' where SKKLID=?"),array($id));
    
		if($update){
			echo "<center><label class='alert alert-success'>Your data has been accepted<br><h2>Thank You</h2></label></center>";			
		}else{
			echo("<center><label class='alert alert-error'>accepted data failed</label></center>");
		}
		
	}
	
	

?>
<center><input type="button" value="Close" onClick="self.parent.tb_remove(true);" /></center>
</div>
<?php
	include "template/bottom3.php";
?>
