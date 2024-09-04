<?php
	$pageTitle="SKKL";	
	include "template/top2.php"; //Load template pembuka dan load css eksternal
	include "include/date_lib.php";
	//for Get data
	if(isset($_GET['uiSlctBulan']))
        {
            $bulanPeriod = $_GET["uiSlctBulan"];
        }
        else
        {
            $bulanPeriod = "";
        }
		
	if(isset($_GET['uiSlctTahun']))
        {
            $tahunPeriod = $_GET["uiSlctTahun"];
        }
        else
        {
            $tahunPeriod = "";
        }	


?>
<h2>SKKL Rekap Detail</h2><br>
<!-- Content For Code -->

<form id="form1" name="form1" method="GET" class="well form-search" action="">
<div class="row">
      <div class="span1">NIK :</div>
    	<div class="span2"><?php echo $NIK ?></div>

    <div class="span1">Name :</div>
    <div class="span5"><?php 
// $getnama = odbc_exec($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = '$NIK'");
odbc_execute($getnama = odbc_prepare($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = ?"), array($NIK));
                        echo $nama=odbc_result($getnama, "Nama");					
				?>
                </div>
                
      </div>

<div class="row">
      <div class="span1">Period :</div>
      <div class="span9">
      <select name='uiSlctBulan' class="span2">
          <option value='none'>-- Select Month --</option>
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
          </select>
          -
          <select name='uiSlctTahun' class="span2">
            <?php
		  	$tahunawal = '2011';
		  	$tanggalhariini = getdate();
		 	$tahunSekarang = $tanggalhariini['year'];
		 echo "<option value='none'>-- Select Year --</option>";
		 for ($i=$tahunawal; $i<=$tahunSekarang; $i++)
			{
				echo "<option>".$i."</option>";
			}
		 ?>
            </select>
	<button type="submit" class="btn">Search</button>
            </div>
      </div>
      
      <div class="row">
      <div class="span2"><h3>Periode  :  </h3></div>
      <div class="span4">
		<?php  
				if ($bulanPeriod == '1' || $bulanPeriod == '01' )
                {
                	echo "<h3><span style=color:#000000>January - ".$tahunPeriod."</span></h3>";
                }
                elseif ($bulanPeriod == '2' || $bulanPeriod == '02' )
                {
                	echo "<h3><span style=color:#000000>February - ".$tahunPeriod."</span></h3>";
                }
                elseif ($bulanPeriod == '3' || $bulanPeriod == '03' )
                {
                	echo "<h3><span style=color:#000000>March - ".$tahunPeriod."</span></h3>";
                }
                elseif ($bulanPeriod == '4' || $bulanPeriod == '04' )
                {
                	echo "<h3><span style=color:#000000>April - ".$tahunPeriod."</span></h3>";
                }
                elseif ($bulanPeriod == '5' || $bulanPeriod == '05' )
                {
                	echo "<h3><span style=color:#000000>May - ".$tahunPeriod."</span></h3>";
                }
                elseif ($bulanPeriod == '6' || $bulanPeriod == '06' )
                {
                	echo "<h3><span style=color:#000000>June - ".$tahunPeriod."</span></h3>";
                }
                elseif ($bulanPeriod == '7' || $bulanPeriod == '07' )
                {
                	echo "<h3><span style=color:#000000>July - ".$tahunPeriod."</span></h3>";
                }
                elseif ($bulanPeriod == '8' || $bulanPeriod == '08' )
                {
                	echo "<h3><span style=color:#000000>August - ".$tahunPeriod."</span></h3>";
                }
                elseif ($bulanPeriod == '9' || $bulanPeriod == '09' )
                {
                	echo "<h3><span style=color:#000000>September - ".$tahunPeriod."</span></h3>";
   				}
                elseif ($bulanPeriod == '10' )
                {
                	echo "<h3><span style=color:#000000>October - ".$tahunPeriod."</span></h3>";
                }
                elseif ($bulanPeriod == '11' )
                {
                	echo "<h3><span style=color:#000000>November - ".$tahunPeriod."</span></h3>";
               	}
                elseif ($bulanPeriod == '12' )
                {
                	echo "<h3><span style=color:#000000>December - ".$tahunPeriod."</span></h3>";
                }
                ?>
                </div>
      </div>
    </table>
  </form>
     <table id="tb_view" class="table table-striped table-bordered table-condensed">
     		<thead>
            <tr>
                        <th>Assignment Date</th>
						<th>Mandatory Overtime</th>
                        <th>Overtime Hour</th>
                        <th>Rate Hour</th>     
                        <th>Quota</th>        
			<th>DWS In</th>
			<th>DWS Out</th>
                        <th>CekIn</th>
        		<th>CekOut</th>
        		<th>SKKLIN</th>                        
        		<th>SKKLOUT</th>                                                
        		<th>Overtime Susulan</th>
        		<th>Total Overtime</th>                        
                </tr>
            </thead>
            <tbody>
		<?php
		
			//jika bulan != 12 maka bulan + 1 else 12 bulan 01 dan tahun + 1
			if($bulanPeriod != 12)
			{
				$varmonth = $bulanPeriod+1;
				$bulanselected= substr("00".$varmonth,-2);
				if(isset($_REQUEST['uiSlctTahun'])!='')
				{
					$tahunselected = $_REQUEST['uiSlctTahun'];
				}
			}elseif($bulanPeriod==12)
			{
				$varmonth=1;
				$bulanselected= substr("00".$varmonth,-2);
				$tahunselected = $_REQUEST['uiSlctTahun'] +1;
			}
	
			//echo $bulanselected = $_REQUEST['uiSlctBulan'];
			//echo $tahunselected = $_REQUEST['uiSlctTahun'];
			
			
			//get variable period
			if(isset($tahunselected)!='')
			{
				$period = $tahunselected.$bulanselected.'01';
			}
			else
			{
				$period ='';
			}
			
			if ($period != '')
			{
				//call SAP Data
				$fce = saprfc_function_discover($rfc,"ZHRFM_LEMBUR_DETIL_FROMTABEL");
				if (! $fce )
				{
					echo "System Error. Please Try Again Later."; exit;
				}
				saprfc_import ($fce,"FI_PERNR",$NIK);
				saprfc_import ($fce,"FI_TGL",$period);
				saprfc_table_init ($fce,"FI_LEMBUR");
				$rfc_rc = saprfc_call_and_receive ($fce);
				if ($rfc_rc != SAPRFC_OK)
				{
						if ($rfc == SAPRFC_EXCEPTION )
								echo "System Error. Please Try Again Later.";
						else
								echo "System Error. Please Try Again Later."; exit;
				}
				$rows = saprfc_table_rows ($fce,"FI_LEMBUR");
				$rows2 = saprfc_table_rows ($fce,"FI_LEMBUR");
				
				if ($rows != 0)
				{
					$totalALL=0;
					for ($i=1;$i<=$rows;$i++)
					{
					$FI_LEMBURTOTAL = saprfc_table_read ($fce,"FI_LEMBUR",$i);
					$totalALL= $totalALL+$FI_LEMBURTOTAL['TOTALLEMBUR'];
					}
					
					
					for ($i=1;$i<=$rows;$i++)
					{
					$FI_LEMBUR = saprfc_table_read ($fce,"FI_LEMBUR",$i);
					?>
					<tr>
					<td align="center"><?php echo substr($FI_LEMBUR['TANGGAL'],0,4).'/'.substr($FI_LEMBUR['TANGGAL'],4,2).'/'.substr($FI_LEMBUR['TANGGAL'],6,2);?></td>
                    			<td align="center"><?php echo $FI_LEMBUR['LEMBURWAJIB'];?></td>
                    			<td align="center"><?php echo $FI_LEMBUR['JAMLEMBUR'];?></td>
                    			<td align="center"><?php echo $FI_LEMBUR['JAMTARIF'];?></td>
                    			<td align="center"><?php echo $FI_LEMBUR['QUOTA'];?></td>                    
					<td align="center">
                                        <?php echo substr($FI_LEMBUR['DWSIN'],0,2).':'.substr($FI_LEMBUR['DWSIN'],2,2);?>
                                        </td>
					<td align="center">
                                        <?php echo substr($FI_LEMBUR['DWSOUT'],0,2).':'.substr($FI_LEMBUR['DWSOUT'],2,2);?>
                                        </td>
					<td align="center">
					<?php echo substr($FI_LEMBUR['CEKIN'],0,2).':'.substr($FI_LEMBUR['CEKIN'],2,2);?>
                    			</td>
                    			<td align="center">
					<?php echo substr($FI_LEMBUR['CEKOUT'],0,2).':'.substr($FI_LEMBUR['CEKOUT'],2,2);?>
                    			</td>
                    			<td align="center">
					<?php echo substr($FI_LEMBUR['SKKL_IN'],0,2).':'.substr($FI_LEMBUR['SKKL_IN'],2,2); ?>
		                        </td>
					<td align="center">
				<?php echo substr($FI_LEMBUR['SKKL_OUT'],0,2).':'.substr($FI_LEMBUR['SKKL_OUT'],2,2); ?>
                    			</td>
					<td align="center"><?php echo $FI_LEMBUR['ERILEMBUR'];?></td>
					<td align="center"><?php echo $FI_LEMBUR['TOTALLEMBUR'];?></td>	
			<?php
					
					}
				}
			}

				
			?>
            
            				</tr>
			
            				<tr>
           					<td></td>
            					<td></td>
            					<td></td>
            					<td></td>
            					<td></td>
            					<td></td>
            					<td></td>
            					<td></td>
            					<td></td>
						<td></td>
						<td></td>
            					<td>Total :</td>            
					        <td><?php
                					if(isset($totalALL))
                					{
                						echo $totalALL;
                					}
                				?></td>
            				</tr>				
            </tbody>
            </table>

<?php
include "template/bottom2.php"; //Load tenplate penutup dan load javascript eksternal
?>

<script type="text/javascript">
jQuery.validator.addMethod("selectNone", 
	function(value, element) { 
	if (element.value == "none") 
	{ 
		return false; 
	} 
	else return true; 
	}, "Please select an option." ); 

$(document).ready(
	function() 
	{ 
		$("#form1").validate(
		{
			rules: 	{
						uiSlctBulan: {selectNone: true},
						uiSlctTahun: {selectNone:true}
					}
		});			
	}); 		
		
</script>
