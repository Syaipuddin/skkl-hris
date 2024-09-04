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

// $query_get_self=odbc_exec($conn,"select  PersAdmin from ms_niktelp WHERE NIK='$NIK'");
odbc_execute($query_get_self = odbc_prepare($conn,"select  PersAdmin from ms_niktelp WHERE NIK=?"), array($NIK));
  $PersAdm = odbc_result($query_get_self,"PersAdmin");
?>

<form id="FormHRList" name="FormHRList" method="post" action="skklHRListProses.php">
<div  style="overflow-x: auto; overflow-y: hidden;; background-color: #ffffc6"> 
	<table id="example" class="table table table-striped table-bordered table-condensed">
    	<thead>
        <tr>
	<input type="checkbox" id="checkAll"> Check All
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
                  $batas = 50; //tampilan record per halaman
                  $page_set = 5; // set halaman
                  if(!isset($_GET['hal']))
                  {
                    $hal = 1;
                    $posisi = 0;
                  }else{
                    $hal = $_GET['hal'];
                    if ($hal == 1)
                    {
                      $posisi = 0;
                    }else if($hal == 2){
                      $posisi = $batas;
                    }else if ($hal > 2) {
                  //formula di bawah ini dibuat sangat fleksibel, Anda tinggal merubah $batas (tampilan record per halaman)
                      $posisi = ($hal*($batas-1))+($hal-$batas);
                    }
                  }
                  
                  /*echo "SELECT TOP $batas A.SKKLID,A.InputDate,A.NIK, A.Nama, A.AssignmentDate, A.BeginDate,A.EndDate,A.SuperiorNIK, A.NamaSuperior,
            A.FirstApproverNIK as atasan1, A.NamaFirstApprove as nama_atasan1, A.SecondApproverNIK as atasan2, A.NamaSecondApprove as nama_atasan2, B.SubArea, B.SubAreaText, A.Flag, A.isApproved,B.PersAdmin,
            A.HRNIK from tb_SKKLTransaction A inner join ms_niktelp B on A.NIK =B.NIK
            where isApproved='true' and HRNIK is NULL and (Flag is null or Flag ='1')  AND PersAdmin='$PersAdm' AND (MONTH(InputDate) BETWEEN '$dateBefore' AND '$getDate') AND
            (SKKLID NOT IN (SELECT TOP $posisi SKKLID from 
tb_SKKLTransaction A inner join ms_niktelp B on A.NIK =B.NIK where isApproved='true' and HRNIK is NULL and 
(Flag is null or Flag ='1') AND PersAdmin='$PersAdm' AND (MONTH(InputDate) BETWEEN '$dateBefore' AND '$getDate') ORDER BY SKKLID)) 
            group by B.SubAreaText, A.InputDate, A.Nama, A.AssignmentDate,A.BeginDate,A.EndDate,A.SuperiorNIK, 
            A.NamaSuperior,A.FirstApproverNIK, A.NamaFirstApprove, A.SecondApproverNIK, A.NamaSecondApprove, B.SubArea,
            A.NIK,A.Flag, A.SKKLID, A.isApproved, A.HRNIK, B.PersAdmin";*/

                  /*$qry = "SELECT TOP $batas A.SKKLID,A.InputDate,A.NIK, A.Nama, A.AssignmentDate, A.BeginDate,A.EndDate,A.SuperiorNIK, A.NamaSuperior,
            A.FirstApproverNIK as atasan1, A.NamaFirstApprove as nama_atasan1, A.SecondApproverNIK as atasan2, A.NamaSecondApprove as nama_atasan2, B.SubArea, B.SubAreaText, A.Flag, A.isApproved,B.PersAdmin,
            A.HRNIK from tb_SKKLTransaction A inner join ms_niktelp B on A.NIK =B.NIK
            where isApproved='true' and HRNIK is NULL and (Flag is null or Flag ='1')  AND PersAdmin='$PersAdm' AND MONTH(InputDate) = MONTH(GETDATE()) AND
            (SKKLID NOT IN (SELECT TOP $posisi SKKLID from 
tb_SKKLTransaction A inner join ms_niktelp B on A.NIK =B.NIK where isApproved='true' and HRNIK is NULL and 
(Flag is null or Flag ='1') AND PersAdmin='$PersAdm' AND MONTH(InputDate) = MONTH(GETDATE()) ORDER BY SKKLID)) 
            group by B.SubAreaText, A.InputDate, A.Nama, A.AssignmentDate,A.BeginDate,A.EndDate,A.SuperiorNIK, 
            A.NamaSuperior,A.FirstApproverNIK, A.NamaFirstApprove, A.SecondApproverNIK, A.NamaSecondApprove, B.SubArea,
            A.NIK,A.Flag, A.SKKLID, A.isApproved, A.HRNIK, B.PersAdmin";

            var_dump($qry);*/
            
                 //Query data	
                //  $StatusHRQry=odbc_exec($conn,"SELECT TOP $batas A.SKKLID,A.InputDate,A.NIK, A.Nama, A.AssignmentDate, A.BeginDate,A.EndDate,A.SuperiorNIK, A.NamaSuperior, A.FirstApproverNIK as atasan1, A.NamaFirstApprove as nama_atasan1, A.SecondApproverNIK as atasan2, A.NamaSecondApprove as nama_atasan2, B.SubArea, B.SubAreaText, A.Flag, A.isApproved,B.PersAdmin, A.HRNIK from tb_SKKLTransaction A inner join ms_niktelp B on A.NIK =B.NIK where isApproved='true' and HRNIK is NULL and (Flag is null or Flag ='1') AND PersAdmin='$PersAdm' AND (InputDate BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-1, 0) AND DATEADD (dd, -1, DATEADD(mm, DATEDIFF(mm, 0, GETDATE()) + 1, 0))) AND (SKKLID NOT IN (SELECT TOP $posisi SKKLID from tb_SKKLTransaction A inner join ms_niktelp B on A.NIK =B.NIK where isApproved='true' and HRNIK is NULL and (Flag is null or Flag ='1') AND PersAdmin='$PersAdm' AND (InputDate BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-1, 0) AND DATEADD (dd, -1, DATEADD(mm, DATEDIFF(mm, 0, GETDATE()) + 1, 0))) ORDER BY SKKLID)) group by B.SubAreaText, A.InputDate, A.Nama, A.AssignmentDate,A.BeginDate,A.EndDate,A.SuperiorNIK, A.NamaSuperior,A.FirstApproverNIK, A.NamaFirstApprove, A.SecondApproverNIK, A.NamaSecondApprove, B.SubArea, A.NIK,A.Flag, A.SKKLID, A.isApproved, A.HRNIK, B.PersAdmin");
                 odbc_execute($StatusHRQry = odbc_prepare($conn,"SELECT TOP $batas A.SKKLID,A.InputDate,A.NIK, A.Nama, A.AssignmentDate, A.BeginDate,A.EndDate,A.SuperiorNIK, A.NamaSuperior, A.FirstApproverNIK as atasan1, A.NamaFirstApprove as nama_atasan1, A.SecondApproverNIK as atasan2, A.NamaSecondApprove as nama_atasan2, B.SubArea, B.SubAreaText, A.Flag, A.isApproved,B.PersAdmin, A.HRNIK from tb_SKKLTransaction A inner join ms_niktelp B on A.NIK =B.NIK where isApproved='true' and HRNIK is NULL and (Flag is null or Flag ='1') AND PersAdmin=? AND (InputDate BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-1, 0) AND DATEADD (dd, -1, DATEADD(mm, DATEDIFF(mm, 0, GETDATE()) + 1, 0))) AND (SKKLID NOT IN (SELECT TOP $posisi SKKLID from tb_SKKLTransaction A inner join ms_niktelp B on A.NIK =B.NIK where isApproved='true' and HRNIK is NULL and (Flag is null or Flag ='1') AND PersAdmin='$PersAdm' AND (InputDate BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-1, 0) AND DATEADD (dd, -1, DATEADD(mm, DATEDIFF(mm, 0, GETDATE()) + 1, 0))) ORDER BY SKKLID)) group by B.SubAreaText, A.InputDate, A.Nama, A.AssignmentDate,A.BeginDate,A.EndDate,A.SuperiorNIK, A.NamaSuperior,A.FirstApproverNIK, A.NamaFirstApprove, A.SecondApproverNIK, A.NamaSecondApprove, B.SubArea, A.NIK,A.Flag, A.SKKLID, A.isApproved, A.HRNIK, B.PersAdmin"), array($PersAdm));
                 //        $rs     = odbc_exec($conn, $StatusHRQry);
                 /*var_dump("SELECT TOP $batas A.SKKLID,A.InputDate,A.NIK, A.Nama, A.AssignmentDate, A.BeginDate,A.EndDate,A.SuperiorNIK, A.NamaSuperior, A.FirstApproverNIK as atasan1, A.NamaFirstApprove as nama_atasan1, A.SecondApproverNIK as atasan2, A.NamaSecondApprove as nama_atasan2, B.SubArea, B.SubAreaText, A.Flag, A.isApproved,B.PersAdmin, A.HRNIK from tb_SKKLTransaction A inner join ms_niktelp B on A.NIK =B.NIK where isApproved='true' and HRNIK is NULL and (Flag is null or Flag ='1') AND PersAdmin='$PersAdm' AND (InputDate BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-1, 0) AND GETDATE()) AND (SKKLID NOT IN (SELECT TOP $posisi SKKLID from tb_SKKLTransaction A inner join ms_niktelp B on A.NIK =B.NIK where isApproved='true' and HRNIK is NULL and (Flag is null or Flag ='1') AND PersAdmin='$PersAdm' AND (InputDate BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-1, 0) AND GETDATE()) ORDER BY SKKLID)) group by B.SubAreaText, A.InputDate, A.Nama, A.AssignmentDate,A.BeginDate,A.EndDate,A.SuperiorNIK, A.NamaSuperior,A.FirstApproverNIK, A.NamaFirstApprove, A.SecondApproverNIK, A.NamaSecondApprove, B.SubArea, A.NIK,A.Flag, A.SKKLID, A.isApproved, A.HRNIK, B.PersAdmin");*/
                    //Total Jumlah Record
                    
                    
                    //looping datagrid
				
                    while($row = odbc_fetch_row($StatusHRQry))
                    {	
					
						//validasi personal admin sama dengan personal admin nik yang diminta lembur

			// $query_get_emp=odbc_exec($conn,"select  PersAdmin from ms_niktelp WHERE NIK='".odbc_result($StatusHRQry,"NIK")."'");
      odbc_execute($query_get_emp = odbc_prepare($conn,"select  PersAdmin from ms_niktelp WHERE NIK=?"), array(odbc_result($StatusHRQry,"NIK")));
                        $PersAdm2 = odbc_result($query_get_emp,"PersAdmin");    
                        //kondisi if personal admin sama maka keluar 
                        
                        $value_rec          = "SELECT COUNT(*) as REC FROM tb_SKKLTransaction A inner join ms_niktelp B 
                                              on A.NIK=B.NIK where isApproved='true' and HRNIK is NULL and 
                                              (Flag is null or Flag ='1')  and PersAdmin=? AND (InputDate BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-1, 0) AND DATEADD (dd, -1, DATEADD(mm, DATEDIFF(mm, 0, GETDATE()) + 1, 0)))";
                        // $rs_rec                 = odbc_exec($conn, $value_rec);
                        odbc_execute($rs_rec = odbc_prepare($conn,$value_rec), array($PersAdm2));
                        $jmlData              = odbc_result($rs_rec,"REC");
                        $jmlHal                 = ceil($jmlData/$batas);

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
							
							echo substr($FI_RTEXT,11,11);
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
                        <?php echo odbc_result($StatusHRQry,"atasan1")?> 
                        <br />- 
                       <?php 
                         echo odbc_result($StatusHRQry,"nama_atasan1");
                        ?>  
                        </td>
                        <td style="text-align:center;">
                        <?php echo odbc_result($StatusHRQry,"atasan2")?> 
                        <br />- 
                                             <?php 
                         echo odbc_result($StatusHRQry,"nama_atasan2");
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

    <div style="text-align=right;">
    <?php

	//echo $_SERVER['SERVER_ADDR'];
	//echo $_SERVER['PHP_SELF'];
      if ($hal > 1)
      {
        $prev = $hal-1;
        echo "<a href=skklHRList.php?hal=$prev><< Prev </a>";
      }else{
        echo "<< Prev";
      }
      //————————–Tampilkan link halaman 1,2,3,… —————————
      for($i=1;$i<=$jmlHal;$i++)
      {
        if ($i != $hal){
        echo "<a href=skklHRList.php?hal=$i>| $i | </a>";
        }else{
        echo " $i ";
        }
      }
      //————————-Link ke halaman berikutnya (Next)—————————
      if ($hal < $jmlHal)
      {
        $next = $hal + 1;
        echo "<a href=skklHRList.php?hal=$next> | Next >></a>";
      }else{
        echo "Next >> ";
      }

    ?>
    </div>
    
  </div>     
  <div style="vertical-align:center;">
    <center>
    <button class="btn btn-primary"  type="submit" name="btnApprove" id="btnApprove" value="Approve"  />Approve</button>
   </center>
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

$("#checkAll").click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
});

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
               
