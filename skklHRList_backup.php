<?php
		$pageTitle="SKKL";	
	include "template/top2.php"; //Load template pembuka dan load css eksternal
	include "include/date_lib.php";
	
?>
<div id="page-wrapper">
  <div class="row">
    <div class="span12">
 <h2>SKKL HR List</h2><br>
<?php
  	//Get variable personal Admin dari
	$fce = saprfc_function_discover($rfc,"ZHRFM_GETENTSTRUCTURE");
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
	
	$PersAdm= $FI_ENT['PERSADMIN']; 
?>

<form id="FormHRList" name="FormHRList" method="post" action="skklHRListProses.php">
<div  style="overflow-x: auto; overflow-y: hidden;; background-color: #ffffc6"> 
	<table id="example" class="table table table-striped table-bordered table-condensed">
    	<thead>
        <tr>
           <th width="6%" style="background-color:#92AFF0;color:#000;font-weight:bold;text-align:center;">Lokasi</th>
           <th width="6%" style="background-color:#92AFF0;color:#000;font-weight:bold;text-align:center;"></th>
           <th width="8%" style="background-color:#92AFF0;color:#000;font-weight:bold;text-align:center;">Input Date</th>
           <th width="9%" style="background-color:#92AFF0;color:#000;font-weight:bold;text-align:center;">Employee</th>
           <th width="16%" style="background-color:#92AFF0;color:#000;font-weight:bold;text-align:center;">Assignment Date</th>
       		<th width="11%" style="background-color:#92AFF0;color:#000;font-weight:bold;text-align:center;">Daily Work Schedule </th>

           <th width="8%" style="background-color:#92AFF0;color:#000;font-weight:bold;text-align:center;">Time Event</th>
           <th width="9%" style="background-color:#92AFF0;color:#000;font-weight:bold;text-align:center;">Attendance Quota</th>
           <th width="8%" style="background-color:#92AFF0;color:#000;font-weight:bold;text-align:center;">Superior </th>
           <th width="13%" style="background-color:#92AFF0;color:#000;font-weight:bold;text-align:center;">Superior 1</th>
           <th width="12%" style="background-color:#92AFF0;color:#000;font-weight:bold;text-align:center;">Superior 2</th>
           <th width="12%" style="background-color:#92AFF0;color:#000;font-weight:bold;text-align:center;"></th>
              </tr>
            </thead>
        	<tbody>
                 <?php
                 //Query data	
                    $StatusHRQry=odbc_exec($conn,"select top 300 A.SKKLID,A.InputDate,A.NIK, A.Nama, A.AssignmentDate, A.BeginDate,A.EndDate,A.SuperiorNIK, 
												A.NamaSuperior,A.FirstApproverNIK, A.NamaFirstApprove, A.SecondApproverNIK, A.NamaSecondApprove, B.SubArea, B.SubAreaText, A.Flag
                                                from tb_SKKLTransaction A inner join ms_niktelp B on A.NIK =B.NIK
                                                where isApproved='true' and HRNIK is NULL and (Flag is null or Flag ='1') group by B.SubAreaText, A.InputDate, A.Nama, A.AssignmentDate, 
												A.BeginDate,A.EndDate,A.SuperiorNIK, A.NamaSuperior,A.FirstApproverNIK, A.NamaFirstApprove, A.SecondApproverNIK, 
												A.NamaSecondApprove, B.SubArea,A.NIK,A.Flag, A.SKKLID ");
                    
                    //looping datagrid
				
                    while($row = odbc_fetch_row($StatusHRQry))
                    {	
					
						//validasi personal admin sama dengan personal admin nik yang diminta lembur
                        $fce = saprfc_function_discover($rfc,"ZHRFM_GETENTSTRUCTURE");
                        if (! $fce )
                        {
                            echo "System Error. Please Try Again Later."; exit;
                        }
                        saprfc_import ($fce,"FI_PERNR",odbc_result($StatusHRQry,"NIK"));
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
                        
                        $PersAdm2= $FI_ENT2['PERSADMIN']; 
                        //kondisi if personal admin sama maka keluar 
                        

                        if ($PersAdm == $PersAdm2)
                        {
                ?>
                    <tr class="gradeX">
					<td><?php echo odbc_result($StatusHRQry,"SubAreaText")?></td>                    
                    <td valign="middle" style="text-align:center;">
						 <?php
						 if(odbc_result($StatusHRQry,"Flag") == '1')
								{ echo '<img src="img/icon/alert.png" border="0" />';} 
						?>
                        <input name="uiChkSKKLGrp[]" type="checkbox"  value="<?php echo odbc_result($StatusHRQry,"SKKLID")?>" id="uiChkSKKLGrp" />
                        </td>
                        <td valign="middle" style="text-align:center;">
                        <a href="skklpopUpHR.php?task=<?php echo  md5('EntrySKKLHR') ?>&SKKLid=<?php echo odbc_result($StatusHRQry,"SKKLID")?>&keepThis=true&TB_iframe=true&height=405&width=800"  class="thickbox"><?php echo convertDateAdis(odbc_result($StatusHRQry,"InputDate"))?></a>
                        </td>
                        <td valign="middle" style="text-align:center;">
                          <?php echo odbc_result($StatusHRQry,"NIK")?> 
                            <br />- 
                            <?php 
                             echo odbc_result($StatusHRQry,"Nama");
                            ?>  
                        </td>
                        <td valign="middle" style="text-align:center;">
                        <?php echo convertDateAdis(odbc_result($StatusHRQry,"AssignmentDate"))?> 
                        </td>
                       
                        
                           <td valign="middle" style="text-align:center;">
                        <?php 
						
							$timeEvent= substr(odbc_result($StatusHRQry,"AssignmentDate"),0,4).substr(odbc_result($StatusHRQry,"AssignmentDate"),5,2).substr(odbc_result($StatusHRQry,"AssignmentDate"),8,2);
				
							$fce = saprfc_function_discover($rfc,"ZHRFM_LIST_TIME");
							if (! $fce )
							{
								echo "System Error. Please Try Again Later."; exit;
							}
							saprfc_import ($fce,"FI_AKHIR",$timeEvent);
							saprfc_import ($fce,"FI_AWAL",$timeEvent);
							saprfc_import ($fce,"FI_PERNR",odbc_result($StatusHRQry,"NIK"));
							saprfc_table_init ($fce,"FI_ABSEN");
							$rfc_rc = saprfc_call_and_receive ($fce);
							if ($rfc_rc != SAPRFC_OK)
							{
								if ($rfc == SAPRFC_EXCEPTION )
									echo "System Error. Please Try Again Later.";
								else
									echo "System Error. Please Try Again Later."; exit;
							}
							$FI_RTEXT = saprfc_export ($fce,"FI_RTEXT");
							$rows = saprfc_table_rows ($fce,"FI_ABSEN");
							$FI_ABSEN = saprfc_table_read ($fce,"FI_ABSEN",$rows);
							
							echo $FI_ABSEN['ABSEN'];
						?> 
                        </td>
                        
                         <td valign="middle" style="text-align:center;">
                        <?php 
						
							$timeEvent= substr(odbc_result($StatusHRQry,"AssignmentDate"),0,4).substr(odbc_result($StatusHRQry,"AssignmentDate"),5,2).substr(odbc_result($StatusHRQry,"AssignmentDate"),8,2);
				
							echo $FI_ABSEN['JAMMASUK'].'-'.$FI_ABSEN['JAMPULANG']; 
						?> 
                        </td>
                        
                         <td valign="middle" style="text-align:center;">
                        <?php echo odbc_result($StatusHRQry,"BeginDate").'-'.odbc_result($StatusHRQry,"EndDate") ?> 
                        </td>
                        
                        <td style="text-align:center;"> 
                        <?php echo odbc_result($StatusHRQry,"SuperiorNIK")?> 
                        <br />- 
                        <?php 
                         echo odbc_result($StatusHRQry,"NamaSuperior");
                        ?>  
                       </td>
                        <td style="text-align:center;">
                        <?php echo odbc_result($StatusHRQry,"FirstApproverNIK")?> 
                        <br />- 
                       <?php 
                         echo odbc_result($StatusHRQry,"NamaFirstApprove");
                        ?>  
                        </td>
                        <td style="text-align:center;">
                        <?php echo odbc_result($StatusHRQry,"SecondApproverNIK")?> 
                        <br />- 
                                             <?php 
                         echo odbc_result($StatusHRQry,"NamaSecondApprove");
                        ?>  
                        </td>
						<td style="text-align:center;">
						 <?php
						 if(odbc_result($StatusHRQry,"Flag") == '1')
								{ echo '<a href="skklHRUpdateFlagProses.php?task='.md5('updateSKKLHR').'&skklId='.odbc_result($StatusHRQry,"SKKLID").'"><img src="img/icon/delete.png" border="0" /></a>';} 
						?>
						</td>
                        <?php
                        }
                        ?>
                    </tr>
                    <?php
					}
					?>
            <!--</div>-->
        </tbody>
    </table>

    <button class="btn btn-primary"  type="submit" name="btnApprove" id="btnApprove" value="Approve"  />Approve</button>
	</div>     
    </form>
  </div>
</div>
    </div>     
                
    
<div id='loading'>
    <h3>Loading page...</h3>
    <img src="img/loadingAnimation.gif" />
  <small><br>...please wait data on processing.</small>
</div> 

<?php
include "template/bottom2.php"; //Load tenplate penutup dan load javascript eksternal
?>

<script type="text/javascript">
$('#FormHRList').submit(function() 
{
	if($('input[@name=uiChkSKKLGrp]:checked').size() == 0){
   		alert('Please Make A Selection for checkbox');
		return false;
	}
	else if($('input[@name=uiChkSKKLGrp]:checked').size() == 0){
   		alert('Please Make A Selection for checkbox');
   		return false;
	}else
	{
		document.getElementById('page-wrapper').style.visibility='hidden';
		$('#loading').show();
		return true;
	}
});

</script>

<script src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    oTable = $('#example').dataTable({
				"bJQueryUI": true,
				"bFilter":false,
	  			"bPaginate": false,
        "fnDrawCallback": function ( oSettings ) {
            if ( oSettings.aiDisplay.length == 0 )
            {
                return;
            }
             
            var nTrs = $('#example tbody tr');
            var iColspan = nTrs[0].getElementsByTagName('td').length;
            var sLastGroup = "";
            for ( var i=0 ; i<nTrs.length ; i++ )
            {
                var iDisplayIndex = oSettings._iDisplayStart + i;
                var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];
                if ( sGroup != sLastGroup )
                {
                    var nGroup = document.createElement( 'tr' );
                    var nCell = document.createElement( 'td' );
                    nCell.colSpan = iColspan;
                    nCell.className = "group";
                    nCell.innerHTML = sGroup;
                    nGroup.appendChild( nCell );
                    nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
                    sLastGroup = sGroup;
                }
            }
        },
        "aoColumnDefs": [
            { "bVisible": false, "aTargets": [ 0 ] }
        ],
        "aaSortingFixed": [[ 0, 'asc' ]],
        "aaSorting": [[ 1, 'asc' ]],
        "sDom": 'lfr<"giveHeight"t>ip'
    });
} );
</script>                       
