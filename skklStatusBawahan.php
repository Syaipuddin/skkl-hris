<?php
	$pageTitle="SKKL";	
	include "template/top2.php"; //Load template pembuka dan load css eksternal
	include "include/date_lib.php";

?>
<h2>SKKL Status Bawahan</h2><br>
<!-- Content For Code -->

<div class="row">
<div class="span3">Personnel Number : <?php echo $NIK;?></div>
        Name :
		<?php 
			$getnama = odbc_exec($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = '$NIK'");
                	echo $nama=odbc_result($getnama, "Nama");
			?>
</div><br />
<table id="tb_view" class="table table table-striped table-bordered table-condensed">
     		<thead><tr>
                        <th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">Input Date</th>
                        <th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assignment Date</th>
                        <th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Status</th>
						<th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">Superior</th>
                        <th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">Superior 1</th>
                        <th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">Superior 2</th>
              </tr></thead><tbody>

<?php
	
	//Query data	
	$StatusBawahanQry=odbc_exec($conn,"select * from tb_SKKLTransaction where NIK='$NIK' AND isApproved='true' and Accepted is null");
	
		
	//looping datagrid
	$i=1;
	while($row = odbc_fetch_row($StatusBawahanQry))
    	{	
	
?>
                <tr>
                	<td valign="middle" style="text-align:center;">
                  	<a href="skklpopUpBawahan.php?task=<?php echo md5('confirmSKKL') ?>&SKKLid=<?php echo odbc_result($StatusBawahanQry,"SKKLID")?>&secondNIK=<?php echo odbc_result($StatusBawahanQry,16)?>&firstNIK=<?php echo odbc_result($StatusBawahanQry,14)?>&keepThis=true&TB_iframe=true&height=500&width=800"  class="thickbox">
					<?php echo convertDateAdis(odbc_result($StatusBawahanQry,"InputDate"))?></a>
                	</td>
                	<td valign="middle" style="text-align:center;"><?php echo convertDateAdis(odbc_result($StatusBawahanQry,"AssignmentDate"))?></td>
                	<td valign="middle" style="text-align:center;">
                	<?php 
						
						if (odbc_result($StatusBawahanQry,"Accepted") == NULL)
						{
							echo 'Not Yet Accepted';
						}else
						{
							echo 'Accepted';	
						}
					?>
                 	</td>
                  	<td  style="text-align:center;">
					<?php 
						echo odbc_result($StatusBawahanQry,12);
					?>
                    -
                    <?php 
						echo odbc_result($StatusBawahanQry,13);
					?></td>
                 	<td style="text-align:center;">
                    <?php 
						echo odbc_result($StatusBawahanQry,14);
					?>
                    -
                    <?php 
						echo odbc_result($StatusBawahanQry,15);
					?>
                    </td>
                  	<td style="text-align:center;">
                    <?php 
						echo odbc_result($StatusBawahanQry,16);
					?>
                    -
                    <?php 
						echo odbc_result($StatusBawahanQry,17);
					?>
                    </td>
               	</tr>
<?php
		$i++;
		}
?></tbody>
            </table>
	
<?php
include "template/bottom2.php"; //Load tenplate penutup dan load javascript eksternal
?>
<script src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
	  $('#tb_view').dataTable({
	  	"bDestroy":true,
	  	"sPaginationType": "full_numbers"
	  });
	});
</script>
