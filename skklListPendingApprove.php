<?php
	
	$pageTitle="SKKL Request Pending Approval";	
	include "template/top2.php"; //Load template pembuka dan load css eksternal
	include "include/date_lib.php";

	
	if(isset($_GET['bulan']))
	{
		$bulan = $_GET["bulan"];
	}
    else
	{
			$bulan = "";
	}

	if(isset($_GET['tahun']))
	{
		$tahun = $_GET["tahun"];
	}
	else
	{
			$tahun = "";
	}

   	$tanggalPeriode = $tahun.$bulan;

	if($tanggalPeriode == "")
	{
			$today = getdate();
			$bulan = $today['mon']-1;
			$tahun = $today['year'];
			$tanggalPeriode = substr("0000".$tahun,-4).substr("00".$bulan,-2);
			//substr("0000".$year,-4).substr("00".$month,-3);
	}
   	$tanggalPeriodeStr = substr($tanggalPeriode,0,4)."-".substr($tanggalPeriode,4,2);	
	
	//Get variable personal Admin dari
/*	$fce = saprfc_function_discover($rfc,"ZHRFM_GETENTSTRUCTURE");
	if (! $fce )
	{
		echo "System Error. Please Try Again Later."; exit;
	}
	saprfc_import ($fce,"FI_PERNR",$NIK);
	saprfc_table_init ($fce,"FI_ENT");
	$rfc_rc = saprfc_call_and_receive ($fce);
	if ($rfc_rc != SAPRFC_OK)
	{
			if ($rfc == SAPRFC_EXCEPTION )
					echo "System Error. Please Try Again Later.";
			else
					echo "System Error. Please Try Again Later."; exit;
	}
	$rows = saprfc_table_rows ($fce,"FI_ENT");
	if ($rows == 0)
	{
		$FI_ENT = '';
	}
	else
	{
		$FI_ENT = saprfc_table_read($fce,"FI_ENT",1);
	}
	
	$PersAdm= $FI_ENT['PERSADMIN'];         */

// $query_get_self=odbc_exec($conn,"select  PersAdmin from ms_niktelp WHERE NIK='$NIK'");

odbc_execute($query_get_self = odbc_prepare($conn,"select  PersAdmin from ms_niktelp WHERE NIK=?"), array($NIK));

	$PersAdm = odbc_result($query_get_self,"PersAdmin");

?>
<h2>SKKL List Pending Approval</h2><br>
<form class="well form-search" name="searchSKKL" action="skklListPendingApprove.php" method="GET">
Periode  :  <select name='bulan' id='bulan' class="span2">
              <option value=''>-- Select Month --</option>
              <option value='01'>January</option>
              <option value='02'>February</option>
              <option value='03'>March</option>
              <option value='04'>April</option>
              <option value='05'>May</option>
              <option value='06'>June</option>
              <option value='07'>July</option>
              <option value='08'>August</option>
              <option value='09'>September</option>
              <option value='10'>October</option>
              <option value='11'>November</option>
              <option value='12'>December</option>
            </select>  -
            <select name='tahun' id='tahun' class="span2">
			  <?php
              $tahunawal = '2011';
              $tanggalhariini = getdate();
              $tahunSekarang = $tanggalhariini['year'];
                echo "<option value=''>-- Select Year --</option>";
               for ($i=$tahunawal; $i<=$tahunSekarang; $i++)
                   {
                         echo "<option value='$i'>".$i."</option>";
                   }
             ?>

            </select>
	<button type="submit" class="btn">Search</button>

                    
<div class="row">
<div class="span1"><h5>Periode :</h5></div>
<div class="span3">
<?php  
	if ($bulan == '1' || $bulan == '01' )
	{
		echo "<h4><span style=color:#000000>January - ".$tahun."</span></h4>";
	}
	elseif ($bulan == '2' || $bulan == '02' )
	{
	   echo "<h4><span style=color:#000000>February - ".$tahun."</span></h4>";
	}
	elseif ($bulan == '3' || $bulan == '03' )
	{
	   echo "<h4><span style=color:#000000>March - ".$tahun."</span></h4>";
	}
	elseif ($bulan == '4' || $bulan == '04' )
	{
	   echo "<h4><span style=color:#000000>April - ".$tahun."</span></h4>";
	}
	elseif ($bulan == '5' || $bulan == '05' )
	{
	   echo "<h4><span style=color:#000000>May - ".$tahun."</span></h4>";
	}
	elseif ($bulan == '6' || $bulan == '06' )
	{
	   echo "<h4><span style=color:#000000>June - ".$tahun."</span></h4>";
	}
	elseif ($bulan == '7' || $bulan == '07' )
	{
	   echo "<h4><span style=color:#000000>July - ".$tahun."</span></h4>";
	}
	elseif ($bulan == '8' || $bulan == '08' )
	{
	   echo "<h4><span style=color:#000000>August - ".$tahun."</span></h4>";
	}
	elseif ($bulan == '9' || $bulan == '09' )
	{
	   echo "<h4><span style=color:#000000>September - ".$tahun."</span></h4>";
	}
	elseif ($bulan == '10' )
	{
	   echo "<h4><span style=color:#000000>October - ".$tahun."</span></h4>";
	}
	elseif ($bulan == '11' )
	{
	   echo "<h4><span style=color:#000000>November - ".$tahun."</span></h4>";
	}
	elseif ($bulan == '12' )
	{
	   echo "<h4><span style=color:#000000>December - ".$tahun."</span></h4>";
	}
?>
	</div>
    </div>
    </form>
<table id="tb_view" class="table table-striped table-bordered table-condensed">
<thead>
<tr>
    <th width="30%" align="center" valign="middle">NIK-Nama</th>
    <th width="1%" align="center" valign="middle">Assignment Date</th>
    <th width="30%" align="center" valign="middle">Superior</th>
    <th width="30%" align="center" valign="middle">Atasan 1</th>
    <th width="30%" align="center" valign="middle">Atasan 2</th>
  </tr>
</thead>
<tbody>
		<?php
			// $queryDoc=odbc_exec($conn,"select  * from tb_SKKLTransaction where Flag is null 		
			// 							and isApproved is null and 
			// 							(REPLACE(CONVERT(VARCHAR, AssignmentDate, 111), '/','-') 
			// 							like '%$tanggalPeriodeStr%') order by AssignmentDate");

			// var_dump("select  * from tb_SKKLTransaction where Flag is null 		
			// 							and isApproved is null and 
			// 							(REPLACE(CONVERT(VARCHAR, AssignmentDate, 111), '/','-') 
			// 							like '%$tanggalPeriodeStr%'') order by AssignmentDate");

			odbc_execute($queryDoc = odbc_prepare($conn,"select  * from tb_SKKLTransaction where Flag is null 		
										and isApproved is null and 
										(REPLACE(CONVERT(VARCHAR, AssignmentDate, 111), '/','-') 
										like ?) order by AssignmentDate"), array("%".$tanggalPeriodeStr."%"));

		//looping data
			while($row = odbc_fetch_row($queryDoc))
			{
				
					//validasi personal admin sama dengan personal admin nik yang diminta lembur
                       /* $fce = saprfc_function_discover($rfc,"ZHRFM_GETENTSTRUCTURE");
                        if (! $fce )
                        {
                            echo "System Error. Please Try Again Later."; exit;
                        }
                        saprfc_import ($fce,"FI_PERNR",odbc_result($queryDoc,"NIK"));
                        saprfc_table_init ($fce,"FI_ENT");
                        $rfc_rc = saprfc_call_and_receive ($fce);
                        if ($rfc_rc != SAPRFC_OK)
                        {
                                if ($rfc == SAPRFC_EXCEPTION )
                                        echo "System Error. Please Try Again Later.";
                                else
                                        echo "System Error. Please Try Again Later."; exit;
                        }
                        $rows = saprfc_table_rows ($fce,"FI_ENT");
                        if ($rows == 0)
                        {
                            $FI_ENT2 = '';
                        }
                        else
                        {
                             $FI_ENT2 = saprfc_table_read($fce,"FI_ENT",1);
                        }
                        
                        $PersAdm2= $FI_ENT2['PERSADMIN']; */
			// $query_get_emp=odbc_exec($conn,"select  PersAdmin from ms_niktelp WHERE NIK='".odbc_result($queryDoc,"NIK")."'");
			odbc_execute($query_get_emp = odbc_prepare($conn,"select  PersAdmin from ms_niktelp WHERE NIK=?"), array(odbc_result($queryDoc,"NIK")));
				$PersAdm2 = odbc_result($query_get_emp,"PersAdmin");	
						
			if ($PersAdm == $PersAdm2)
                        {
		?>
		  <tr class="gradeX">
          	<td align="left"><?php echo odbc_result($queryDoc,"NIK"); ?> - <?php echo odbc_result($queryDoc,"Nama"); ?></td>
			<td align="left"><?php echo convertDateAdis(odbc_result($queryDoc,"AssignmentDate")); ?></td>
			<td align="center"><?php echo odbc_result($queryDoc,"SuperiorNIK"); ?> - <?php 
									echo odbc_result($queryDoc,"NamaSuperior"); ?> </td>
			<td align="center">
			<?php 
			if(odbc_result($queryDoc, 14)!=NULL)
			{
				echo odbc_result($queryDoc,14); ?> - <?php 
				echo odbc_result($queryDoc,15); ?> - <?php 
				if (odbc_result($queryDoc,18) == NULL)
				{
					echo '<font style="color:red">Belum Approve</font>'; 
				}
				else
				{
					echo 'Approve'; 
				}
			}
			?>
            </td>
			<td align="center">
			<?php
			if(odbc_result($queryDoc,16)!=NULL)
			{ 
				echo odbc_result($queryDoc,16); ?> - <?php 
				echo odbc_result($queryDoc,17); ?> - <?php 
				if (odbc_result($queryDoc,19) == NULL)
				{
					echo '<font style="color:red">Belum Approve</font>'; 
				}
				else
				{
					echo 'Approve'; 
				}
			}
			?>
            </td>
		  </tr>
		  <?php
			//}
			  }
			 }
		  ?>
							</tbody>
		</table>
      

<?php
	include "template/bottom2.php"; //Load tenplate penutup dan load javascript eksternal
?>
<script src="js/jquery.dataTables.min.js"></script>
<!-- Javascript dan jquery script dimulai dari sini-->
<script  type="text/javascript">
	$(document).ready(function(){
		$( "#attendanceBeginDate" ).datepicker({
			dateFormat: 'yy-mm-dd'
		});
		$( "#attendanceEndDate" ).datepicker({
			dateFormat: 'yy-mm-dd'
		});
		$('#tb_view').dataTable({
	  	"bDestroy":true,
	  	"sPaginationType": "full_numbers"
	  });
	});
</script>
