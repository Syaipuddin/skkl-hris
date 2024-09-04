<script src="../js/jquery-1.7.2.min.js"></script>
<div id="hehe">
    
</div>
<?php
session_start();
//include "include/system_lib.php";
$ref = "index.php";
if(isset($_GET["ref"]))
{
	$ref = htmlentities($_GET["ref"]);
}
else
{
	$ref = "index.php";
}
if(isset($_SESSION['nik']))
{
	$NIK = base64_decode($_SESSION['nik']);
	$conn = odbc_connect  ( "HRPORTAL" , "hris00",  "Hrport@l2012" );
//	track_user($conn,'LOGOUT',$NIK);
	/*unset($_SESSION['userType_TaM']);
	unset($_SESSION['userType']);
	unset($_SESSION['nik']);
	unset($_SESSION['chpass']);
	unset($_SESSION['survey']);
	unset($_SESSION['oldPW']);
	unset($_SESSION);
	session_regenerate_id();*/
	session_unset();
	session_destroy();
}
?>
<form id="frmLogout" method="post" action="keycloak/0-login_myKG.php">
	<input type="hidden" name="action" id="action" value="logout">
	<input type="hidden" name="ref" id="ref" value="<?php echo $ref; ?>">
</form>
<script>
	var ref = "<?php echo htmlentities($_GET["ref"]); ?>";
	if (ref!='') {
		window.location=ref;
	}
	else{
		$('#frmLogout').submit();
	}
</script>
