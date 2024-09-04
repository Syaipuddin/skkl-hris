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
<h2>SKKL Rekap Lembur Bulan</h2><br>
<!-- Content For Code -->

<form id="form1" name="form1" method="GET" class="well form-search" action="">
<div class="row">
      <div class="span1">NIK :</div>
    	<div class="span2"><?php echo $NIK ?></div>

    <div class="span1">Name :</div>
    <div class="span5"><?php 
					
					//call SAP Data
					$fce = saprfc_function_discover($rfc,"ZHRFM_CV");
					if (! $fce )
					{
						echo "System Error. Please Try Again Later."; exit;
					}
						saprfc_import ($fce,"FI_PERNR",$NIK);
						saprfc_import ($fce,"FI_PERNR_DIAKSES",$NIK);
						saprfc_table_init ($fce,"FI_CV");
						$rfc_rc = saprfc_call_and_receive ($fce);
						if ($rfc_rc != SAPRFC_OK)
						{
								if ($rfc == SAPRFC_EXCEPTION )
										echo "System Error. Please Try Again Later.";
								else
										echo "System Error. Please Try Again Later."; exit;
						}
						$rows = saprfc_table_rows ($fce,"FI_CV");
						$FI_CV = saprfc_table_read($fce,"FI_CV",1);
								
				echo		$nama=$FI_CV['NAMALENGKAP']; 
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
     <table id="tb_view" class="table table table-striped table-bordered table-condensed">
     		<thead><tr>
                        <th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assignment Date</th>
						<th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">Begin</th>
                        <th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">End</th>
                </tr>
            </thead>
            <tbody>
		<?php
		
			//get variable period
			$period = $_REQUEST['uiSlctBulan'].$_REQUEST['uiSlctTahun'];
			
			if ($period != '')
			{
				//call SAP Data
				$fce = saprfc_function_discover($rfc,"ZHRFM_LIST_2007");
				if (! $fce )
				{
					echo "System Error. Please Try Again Later."; exit;
				}
				saprfc_import ($fce,"FI_PERIODE",$period);
				saprfc_import ($fce,"FI_PERNR",$NIK);
				saprfc_table_init ($fce,"FI_CT");
				$rfc_rc = saprfc_call_and_receive ($fce);
				if ($rfc_rc != SAPRFC_OK)
				{
						if ($rfc == SAPRFC_EXCEPTION )
								echo "System Error. Please Try Again Later.";
						else
								echo "System Error. Please Try Again Later."; exit;
				}
				$rows = saprfc_table_rows ($fce,"FI_CT");
				if ($rows != 0)
				{
					for ($i=1;$i<=$rows;$i++)
					{
					$FI_CT = saprfc_table_read ($fce,"FI_CT",$i);
					?>
					<tr>
					<td align="center"><?php echo convertDate($FI_CT['BEGDA']);?>&nbsp;</td>
					<td align="center"><?php echo substr($FI_CT['BEGUZ'],0,2).':'.substr($FI_CT['BEGUZ'],2,2);?>&nbsp;</td>
					<td align="center"><?php echo substr($FI_CT['ENDUZ'],0,2).':'.substr($FI_CT['ENDUZ'],2,2); ?>&nbsp;</td>
					<!--<td align="center"><?php echo $FI_CT['ANZHL'];?>&nbsp;</td>-->
				
			<?php
					}
				}
			}
			?>
            </tr>
            </tbody>
            </table>

<?php
include "template/bottom2.php"; //Load tenplate penutup dan load javascript eksternal
?>


<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
	  $('#tb_view').dataTable({
	  	"bDestroy":true,
	  	"sPaginationType": "full_numbers"
	  });
	});
</script>

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
