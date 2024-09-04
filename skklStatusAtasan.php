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
<h2>SKKL Status Assignment</h2><br>
<!-- Content For Code -->


  <form id="form1" name="form1" method="GET" action="" class="well form-search"  autocomplete="off" onsubmit="return validateForm(this)">
<div class="row">
<div class="span3">Personnel Number : <?php echo $NIK;?></div>
        Name :
		<?php 
			// $getnama = odbc_exec($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = '$NIK'");

			odbc_execute($getnama = odbc_prepare($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = ?"), array($NIK));

                        echo $nama=odbc_result($getnama, "Nama");
			?>
            </div>                     
      <div class="row">
      <div class="span1">Period :</div>
      <div class="span10">
        <select name='uiSlctBulan' class="span2">
          <option value="none" >-- Select Month --</option>
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
     	<div class="span2"><h3>Periode :</h3></div>
        <div class="span3">
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
  </form>
  
     <table id="tb_view" class="table table table-striped table-bordered table-condensed">
     		<thead><tr>
            	<th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">Input Date</th>
                <th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">Sub Ordinate</th>
                <th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assignment Date</th>
				<th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">Superior 1</th>
                <th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">Superior 2</th>
              </tr>
            </thead>
            <tbody>
        <?php
		
		//get variable period
                $tampungtanggal="";
                
                if(isset($_REQUEST['uiSlctTahun']) && isset($_REQUEST['uiSlctBulan']))
                {
		$tampungtanggal=$_REQUEST['uiSlctTahun'].$_REQUEST['uiSlctBulan'];
                }
                $period = $tampungtanggal;

		//Query data	
		// $StatusAtasanQry=odbc_exec($conn,"select *,
		// 							substring(convert(varchar,InputDate,112),0,7) as Period from 		
		// 							tb_SKKLTransaction where substring(convert(varchar,InputDate,112),0,7)='$period'
		// 							and SuperiorNIK='$NIK'");
	
		odbc_execute($StatusAtasanQry = odbc_prepare($conn,"select *,
									substring(convert(varchar,InputDate,112),0,7) as Period from 		
									tb_SKKLTransaction where substring(convert(varchar,InputDate,112),0,7)=?
									and SuperiorNIK=?"), array($period,$NIK));

	 	//looping datagrid
		$i=1;
		while($row = odbc_fetch_row($StatusAtasanQry))
            {	
		?>
                <tr>

                	<td valign="middle" style="text-align:center;">
                  	<a href="skklpopUpAtasan.php?task=<?php echo md5('viewSKKL') ?>&SKKLid=<?php echo odbc_result($StatusAtasanQry,"SKKLID")?>&assignmentDate=<?php echo odbc_result($StatusAtasanQry,"AssignmentDate")?>&atasanNIK=<?php echo odbc_result($StatusAtasanQry,"SuperiorNIK")?>&agenda=<?php echo odbc_result($StatusAtasanQry,"Agenda")?>&keepThis=true&TB_iframe=true&height=500&width=800"  class="thickbox">
					<?php echo convertDateAdis(odbc_result($StatusAtasanQry,"InputDate"))?></a>
                	</td>
                	<td valign="middle" style="text-align:center;">
					<?php echo odbc_result($StatusAtasanQry,"NIK")?>
                    <br />-
					<?php
					echo odbc_result($StatusAtasanQry,"Nama");
				 	?> 
                 	<br />- 
					<?php
						//cek status approve 
						if (odbc_result($StatusAtasanQry,"Accepted") == NULL)
						{
							echo 'Not Yet Accepted';	
						}else
						{
							echo 'Accepted';
						}
					?> </td>
                 	<td valign="middle" style="text-align:center;">
                	<?php echo convertDateAdis(odbc_result($StatusAtasanQry,"AssignmentDate")); ?>
                 	</td>
                  	<td style="text-align:center;">
					<?php echo odbc_result($StatusAtasanQry, 14)?> 
                    <br />-
                    <?php
                    echo odbc_result($StatusAtasanQry,15);
					 ?>
                    <br />-
                    <?php
						//cek status approve 
						if (odbc_result($StatusAtasanQry,18) == NULL)
						{
							if (odbc_result($StatusAtasanQry,14) == NULL)
							{
								echo '';
							}
							else
							{
								echo 'Not Yet Approved';	
							}	
						}
						else if (odbc_result($StatusAtasanQry,18) =='1')
						{
							echo 'Approved';
						}
						else
						{
							echo '<span style="color:Red">Rejected</span>';
						}
					?> 
                    </td>
                  	<td style="text-align:center;">
					<?php echo odbc_result($StatusAtasanQry,16)?> 
                    <br />-
                    <?php
						echo odbc_result($StatusAtasanQry,17);
					?>
                    <br />-
                    <?php
					//cek status approve 
					if (odbc_result($StatusAtasanQry,19) == NULL)
					{
						if (odbc_result($StatusAtasanQry,16) == NULL)
						{
							echo '';
						}
						else
						{
							echo 'Not Yet Approved';	
						}
						
					}
					else if (odbc_result($StatusAtasanQry,19) =='1')
						{
							echo 'Approved';
						}
						else
						{
							echo '<span style="color:Red">Rejected</span>';
						}
					?>
                    </td>
               	</tr>
    	<?php
			$i++;}
		?>
        </tbody>
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

<!-- Javascript dan jquery script dimulai dari sini-->

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

