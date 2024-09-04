<?php
	$pageTitle="SKKL";
	include "template/top3.php";
	include "include/date_lib.php"; 
    if(isset($_GET['superior']))
    {
        $adisthana = strtoupper($_GET["superior"]);
        //$superior = strtoupper($superior);
    }
	else
		{
			$adisthana = "";
		}
?>
<h3>Search Superior Name</h3>
<div class="row">
<form name="search" action="leavePopUpSearchPimpinanKG.php" class="well span8"  method="GET">
<div class="row">
    	<div class="span1">Name</div>
        <div class="span3"><input name="superior" id="superior" type="text" class="required" ></div>
		<div class="span3"><button type="submit" class="btn">Search</button></div>
	</div>
</form>
</div>
<h4>Search Name : </h4>
<?php
echo "<h5>".$adisthana."</h5>";
$fce = saprfc_function_discover($rfc,"ZHRFM_SEARCH_NAMA");
if (! $fce )
{
	echo "System Error. Please Try Again Later."; exit;
}
saprfc_import ($fce,"FI_NAMA",$adisthana);
saprfc_table_init ($fce,"FI_CARI");
$rfc_rc = saprfc_call_and_receive ($fce);
if ($rfc_rc != SAPRFC_OK)
{
	if ($rfc == SAPRFC_EXCEPTION )
		echo "System Error. Please Try Again Later.";
	else
		echo "System Error. Please Try Again Later."; exit;
}
$rows = saprfc_table_rows ($fce,"FI_CARI");
if($rows != 0)
{

?>
<table class="table table-striped table-bordered table-condensed">
	<thead>
    <th width="40">NIK</th>
    <th width="140">NAMA</th>
    <th width="100">POSISI</th>
    <th width="180">UNIT</th>
    </thead>
<?php
	for ($i=1;$i<=$rows;$i++)
	{
		$FI_CARI = saprfc_table_read ($fce,"FI_CARI",$i);
		?>
		<tr>
                        <td><?php echo str_pad($FI_CARI['PERNR'], 6, "0", STR_PAD_LEFT);?></td>
                        <td><?php echo $FI_CARI['NAMA'];?>&nbsp;</td>
                        <td><?php echo $FI_CARI['POSISI'];?>&nbsp;</td>
                        <td><?php echo $FI_CARI['UNIT'];?>&nbsp;</td>
		</tr>
	<?php
	}
	?>
	</table>
	<?php

}
//Debug info
saprfc_function_free($fce);

include "template/bottom2.php"; //Load tenplate penutup dan load javascript eksternal
?>


<!-- Javascript dan jquery script mulai tulis disini-->
<script  type="text/javascript">
$(document).ready(
  function() 
  { 
    $("#search").validate(
    {
      rules:  
      	{
            superior: {required: true}
        }
    });     
  });   
</script>