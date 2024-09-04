<?php
	$pageTitle="SKKL";
	include "template/top2.php"; //Load template pembuka dan load css eksternal
	include "include/date_lib.php";
?>

	<h2>SKKL Status (Superior)</h2><br>
<div class="row">
<div class="span3">Personnel Number : <?php echo $NIK;?></div>
        Name :
		<?php
		        // $QryNama= odbc_exec($conn,"select Nama,NIK from ms_niktelp where NIK='$NIK'");
				odbc_execute($QryNama = odbc_prepare($conn,"select Nama,NIK from ms_niktelp where NIK=?"), array($NIK));
                        echo $nama=odbc_result($QryNama, 1);
		?>
        </div>

<form id="form1" name="form1" method="post" action="skklStatusSuperiorProses.php">
	<table id="tb_view" class="table table table-striped table-bordered table-condensed">
	<thead>
    <tr>
    	<th width="9%" style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;"> <input type="checkbox" id="checkAll"> </th>
	<th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">Sub Ordinate</th>
	<th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">Assignment Date</th>
	<th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">Overtime</th>
	<th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">Superior</th>
    <th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">Superior 1</th>
	<th style="background-color:#92AFF0;color:#FFF;font-weight:bold;text-align:center;">Superior 2</th>
	</tr>
    </thead><tbody>
	<?php

	$batas = 10; //tampilan record per halaman
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

	//Query data
	 $query="SELECT TOP $batas A.SKKLID,A.InputDate,A.NIK, A.Nama, A.AssignmentDate, A.BeginDate,A.EndDate,A.SuperiorNIK, A.NamaSuperior,
A.FirstApproverNIK as atasan1, A.NamaFirstApprove as nama_atasan1, A.isFirstApproved as approve1, A.SecondApproverNIK as atasan2, A.NamaSecondApprove as nama_atasan2, A.isSecondApproved as approve2,  A.Flag, A.isApproved,
A.HRNIK from tb_SKKLTransaction A where isApproved is null and (FirstApproverNIK = ? AND isFirstApproved is NULL OR SecondApproverNIK = ?
AND isSecondApproved is NULL) AND
(SKKLID NOT IN (SELECT TOP $posisi SKKLID from
tb_SKKLTransaction A  where isApproved is null and ('$NIK'= FirstApproverNIK AND isFirstApproved is NULL OR '$NIK'= SecondApproverNIK
AND isSecondApproved is NULL) AND isApproved is null ORDER BY SKKLID))
group by A.InputDate, A.Nama, A.AssignmentDate,A.BeginDate,A.EndDate,A.SuperiorNIK,
A.NamaSuperior,A.FirstApproverNIK, A.NamaFirstApprove, A.SecondApproverNIK, A.NamaSecondApprove,
A.NIK,A.Flag, A.SKKLID, A.isApproved, A.HRNIK, A.isFirstApproved, A.isSecondApproved";
// $StatusSuperiorQry=odbc_exec($conn,$query);

odbc_execute($StatusSuperiorQry = odbc_prepare($conn,$query), array($NIK,$NIK));

	//
	// $StatusSuperiorQry=odbc_exec($conn,"select * from tb_SKKLTransaction
	// 						where ('$NIK'= FirstApproverNIK AND isFirstApproved is NULL OR '$NIK'= SecondApproverNIK
	// 						AND isSecondApproved is NULL) AND isApproved is null");

	//looping datagrid

	$i=1;
	while($row = odbc_fetch_row($StatusSuperiorQry))
	{
		$value_rec          = "SELECT COUNT(*) as REC FROM tb_SKKLTransaction A
											where isApproved is null and (FirstApproverNIK = ? AND isFirstApproved is NULL OR SecondApproverNIK = ?
											AND isSecondApproved is NULL)";
		// $rs_rec                 = odbc_exec($conn, $value_rec);
		odbc_execute($rs_rec = odbc_prepare($conn,$value_rec), array($NIK,$NIK));
		$jmlData              = odbc_result($rs_rec,"REC");
		$jmlHal                 = ceil($jmlData/$batas);
	?>
    <tr>
     <td valign="middle" style="text-align:center;">
     <input name="uiChkSKKLGrp[]" type="checkbox"  value="<?php echo odbc_result($StatusSuperiorQry,"SKKLID")?>" id="uiChkSKKLGrp" />
                            </td>
     <td valign="middle" style="text-align:center;"><?php echo odbc_result($StatusSuperiorQry,"NIK")?>
	<br />- <?php echo odbc_result($StatusSuperiorQry,"Nama");?>
	</td>
    <td valign="middle" style="text-align:center;">
	<a href="skklpopUpAtasan.php?task=<?php echo  md5('viewSKKL') ?>&SKKLid=<?php echo odbc_result($StatusSuperiorQry,"SKKLID")?>&keepThis=true&TB_iframe=true&height=405&width=800"  class="thickbox">
        <?php echo convertDateAdis(odbc_result($StatusSuperiorQry,"AssignmentDate"))?> </a>
    </td>
	<td valign="middle" style="text-align:center;">
    <?php echo odbc_result($StatusSuperiorQry,"BeginDate").'-'.odbc_result($StatusSuperiorQry,"EndDate") ?> 	</td>
	<td  style="text-align:center;">
    <?php echo odbc_result($StatusSuperiorQry,'SuperiorNIK');?>
    <br />-
    <?php
    echo odbc_result($StatusSuperiorQry,'NamaSuperior');
    ?>
    </td>
    <td style="text-align:center;">
    <?php
        echo odbc_result($StatusSuperiorQry,'atasan1');
    ?>
    <br />
    -
	<?php
       echo odbc_result($StatusSuperiorQry,'nama_atasan1');
        ?>
        <?php


                if(odbc_result($StatusSuperiorQry,'approve1') ==NULL)
                {
                    //nothing
                    echo '<br>- Not Yet Approved';
                }
                else if(odbc_result($StatusSuperiorQry,'approve1') ==FALSE)
                {
                    echo 'Rejected';
                }
                else if(odbc_result($StatusSuperiorQry,'approve1') ==TRUE)
                {
                    echo 'Approved';
                }

        ?>
        </td>
        <td style="text-align:center;">
        <?php
            echo odbc_result($StatusSuperiorQry,'atasan2');
        ?>
        <br />
        -
        <?php
          echo odbc_result($StatusSuperiorQry,'nama_atasan2');
            ?>

            <?php
              if(odbc_result($StatusSuperiorQry,'approve2') ==NULL)
              {
                  //nothing
                  echo '<br>- Not Yet Approved';
							}
							else if(odbc_result($StatusSuperiorQry,'approve2') ==FALSE)
							{
								echo 'Rejected';
							}
							else if(odbc_result($StatusSuperiorQry,'approve2') ==TRUE)
							{
								echo 'Approved';
							}
							?>
            </td>
        </tr>
		<?php
            $i++;
            }
        ?>
        </tbody>
                    </table>

										<div style="text-align=right;">
										<?php

										//echo $_SERVER['SERVER_ADDR'];
										//echo $_SERVER['PHP_SELF'];
											if ($hal > 1)
											{
												$prev = $hal-1;
												echo "<a href=skklStatusSuperior.php?hal=$prev><< Prev </a>";
											}else{
												echo "<< Prev";
											}
											//————————–Tampilkan link halaman 1,2,3,… —————————
											for($i=1;$i<=$jmlHal;$i++)
											{
												if ($i != $hal){
												echo "<a href=skklStatusSuperior.php?hal=$i>| $i | </a>";
												}else{
												echo " $i ";
												}
											}
											//————————-Link ke halaman berikutnya (Next)—————————
											if ($hal < $jmlHal)
											{
												$next = $hal + 1;
												echo "<a href=skklStatusSuperior.php?hal=$next> | Next >></a>";
											}else{
												echo "Next >> ";
											}

										?>
										</div>

                    <div class="row">
             <div class="span1 offset5"><button class="btn btn-primary"  type="submit" name="btnApprove" id="btnApprove" value="Approve">Approve</button> </div>
             <div class="span1"><button class="btn btn-primary" type="submit" name="btnReject" id="btnReject" value="Reject">
             Reject</button></div>
         </div>
             </form>


<div id='loading'>
            <h3>Loading page...</h3>
                <img src="img/loadingAnimation.gif" />
              <small><br>...please wait data on processing.</small>
</div>

<?php
include "template/bottom2.php"; //Load tenplate penutup dan load javascript eksternal
?>
<script src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf-8">
$("#checkAll").click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
});
	$(document).ready(function() {
	  $('#tb_view').dataTable({
	  	"bDestroy":true,
	  	"sPaginationType": "full_numbers",
		"bPaginate": false
	  });
	});
</script>


<script type="text/javascript">

$(":submit").live('click', function() {
    if($(this).val()=='Reject')
    {
        var agree=confirm("Are you sure to Reject this data?");
        if (agree)
        return true ;
        else
        return false ;
    }
})


$('#form1').submit(function()
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
