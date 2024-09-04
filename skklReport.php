<?php
	$pageTitle='SKKL';
  include "template/top2.php"; //Load template pembuka dan load css eksternal
  include "include/date_lib.php";

	//for Get data period
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

		//get variable nik
		//get nik
		if(isset($_REQUEST['uiTxtNik']))
		{
			$nik = $_REQUEST['uiTxtNik'];
		}
		else
		{
			$nik='';
		}

		//flag jika dia nol maka nama unit tidak muncul
		// $flag = $_REQUEST['flag'];


		// $query_get_persadmin= odbc_exec($conn,"select PersAdmin from ms_niktelp where NIK='".$NIK."'");
		odbc_execute($query_get_persadmin = odbc_prepare($conn,"select PersAdmin from ms_niktelp where NIK=?"), $NIK);

		$persadmin=odbc_result($query_get_persadmin, 'PersAdmin');


		//get variable period
		if(isset($_REQUEST['uiSlctTahun']))
		{
			$period = $_REQUEST['uiSlctTahun'].$_REQUEST['uiSlctBulan'];
		}
		else
		{
			$period='';
		}

?>
<div id="page-wrapper">
  <div class="row">
    <div class="span12">
 <h2>SKKL Report</h2><br>


<div  style="overflow-x: auto; overflow-y: hidden;">
                 <?php
                  $batas = 100; //tampilan record per halaman
                  $page_set = 0; // set halaman
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

                  if($_GET['txtKeyword']=='')
                  {
                    $txtKeyword = '';
                    $skkl_Qry="SELECT TOP $batas BeginDate, EndDate,tb_SKKLTransaction.InputDate,tb_SKKLTransaction.NIK,tb_SKKLTransaction.Nama,AssignmentDate,Agenda,SuperiorNIK,FirstApproverNIK,SecondApproverNIK,
                                        substring(convert(varchar,AssignmentDate,112),0,7) as Period from tb_SKKLTransaction inner join ms_niktelp on
                                        tb_SKKLTransaction.NIK=ms_niktelp.NIK WHERE HRNIK is not null and Flag is null and isApproved='true' and PersAdmin=? and
                                        substring(convert(varchar,AssignmentDate,112),0,7)=? AND  ( SKKLID NOT IN ( SELECT TOP $posisi SKKLID from tb_SKKLTransaction where substring(convert(varchar,AssignmentDate,112),0,7)='$period') )  GROUP BY SKKLID,
                                        tb_SKKLTransaction.NIK,tb_SKKLTransaction.Nama,AssignmentDate,Agenda,SuperiorNIK,FirstApproverNIK,SecondApproverNIK, tb_SKKLTransaction.InputDate,BeginDate, EndDate";
                    $parameter_skkl_qry = array($persadmin,$period);

					$value_rec          = "SELECT COUNT(*) as REC FROM tb_SKKLTransaction inner join ms_niktelp on
										tb_SKKLTransaction.NIK=ms_niktelp.NIK WHERE HRNIK is not null and Flag is null and isApproved='true' and PersAdmin=? and
										substring(convert(varchar,AssignmentDate,112),0,7)=?";

					$parameter_value_rec = array($persadmin,$period);
                  }
                  else
                  {
                    $txtKeyword = strtoupper($_GET['txtKeyword']);
                    $skkl_Qry="SELECT TOP $batas  BeginDate, EndDate,tb_SKKLTransaction.InputDate,tb_SKKLTransaction.NIK,tb_SKKLTransaction.Nama,AssignmentDate,Agenda,SuperiorNIK,FirstApproverNIK,SecondApproverNIK,
                                        substring(convert(varchar,AssignmentDate,112),0,7) as Period from tb_SKKLTransaction inner join ms_niktelp on
                                        tb_SKKLTransaction.NIK=ms_niktelp.NIK WHERE HRNIK is not null and Flag is null and tb_SKKLTransaction.NIK=? AND isApproved='true' and PersAdmin=? and
                                        substring(convert(varchar,AssignmentDate,112),0,7)=? AND  ( SKKLID NOT IN ( SELECT TOP $posisi SKKLID from tb_SKKLTransaction   ) )  GROUP BY SKKLID,
                                        tb_SKKLTransaction.NIK,tb_SKKLTransaction.Nama,AssignmentDate,Agenda,SuperiorNIK,FirstApproverNIK,SecondApproverNIK, tb_SKKLTransaction.InputDate,BeginDate, EndDate";
                    
					$parameter_skkl_qry = array($txtKeyword,$persadmin,$period);
					
					$value_rec          = "SELECT COUNT(*) as REC FROM tb_SKKLTransaction inner join ms_niktelp on
										tb_SKKLTransaction.NIK=ms_niktelp.NIK WHERE HRNIK is not null and Flag is null and tb_SKKLTransaction.NIK=? AND isApproved='true' and PersAdmin=? and
										substring(convert(varchar,AssignmentDate,112),0,7)=?";

					$parameter_value_rec = array($txtKeyword,$persadmin,$period);
                    // $medical_Qry="SELECT TOP $batas * from tb_mr_mst_obat WHERE obat like '%$txtKeyword%' AND ( id_obat NOT IN ( SELECT TOP $posisi id_obat from tb_mr_mst_obat ORDER BY id_obat ) )  GROUP BY id_obat, obat, keterangan, active";
                    // $value_rec          = "SELECT COUNT(*) as REC FROM tb_mr_mst_obat WHERE obat like '%$txtKeyword%'";
                  }
                  
//                  echo $skkl_Qry;

                 //Query data

                    // $rs_skkl = odbc_exec($conn, $skkl_Qry);
					odbc_execute($rs_skkl = odbc_prepare($conn,$skkl_Qry), $parameter_skkl_qry);
                    //kondisi if personal admin sama maka keluar

                    // $rs_rec                 = odbc_exec($conn, $value_rec);
					odbc_execute($rs_rec = odbc_prepare($conn,$value_rec), $parameter_value_rec);
                    $jmlData              = odbc_result($rs_rec,"REC");
                    $jmlHal                 = ceil($jmlData/$batas);
                    //--- TABEL ---
                    echo ("
                      <form name='frmSearch' method='get' action='".$_SERVER['SCRIPT_NAME']."'>");
											echo "<div class='row'>
														<div class='span1'>NIK:</div>
														<div class='span7'><input name='txtKeyword' type='text' id='txtKeyword' placeholder='Cari NIK' value='".$txtKeyword."'></div></div>";
											echo ("<div class='row'>
														<div class='span1'>Period :</div>
											<div class='span7'><select name='uiSlctBulan' id='uiSlctBulan'  class='span2' >
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
													<select name='uiSlctTahun' id='uiSlctTahun' class='span2'>");

												$tahunawal = '2011';
												$tanggalhariini = getdate();
											$tahunSekarang = $tanggalhariini['year'];
										 echo "<option value='none'>-- Select Year --</option>";
										 for ($i=$tahunawal; $i<=$tahunSekarang; $i++)
											{
												echo "<option>".$i."</option>";
											}

													echo "</select>
										<button type='submit'  name='btnSearch' id='btnSearch' value='Search' class='btn'>Search</button>
														</div>
											</div>
										<div class='row'>
												<div class='span1'>
												<strong style='font-size:12px;'>Periode :</strong></div>
												<div class='span3'>";

										if(isset($_REQUEST['uiSlctBulan'])!='')
										{
											if ($_REQUEST['uiSlctBulan']== '01' )
																	{
																		echo "<h3><span style=color:#000000>January - ".$_REQUEST['uiSlctTahun']."</span></h3>";
																	}
																	elseif ($_REQUEST['uiSlctBulan'] == '2' || $_REQUEST['uiSlctBulan'] == '02' )
																	{
																		echo "<h3><span style=color:#000000>February - ".$_REQUEST['uiSlctTahun']."</span></h3>";
																	}
																	elseif ($_REQUEST['uiSlctBulan'] == '3' || $_REQUEST['uiSlctBulan'] == '03' )
																	{
																		echo "<h3><span style=color:#000000>March - ".$_REQUEST['uiSlctTahun']."</span></h3>";
																	}
																	elseif ($_REQUEST['uiSlctBulan'] == '4' || $_REQUEST['uiSlctBulan'] == '04' )
																	{
																		echo "<h3><span style=color:#000000>April - ".$_REQUEST['uiSlctTahun']."</span></h3>";
																	}
																	elseif ($_REQUEST['uiSlctBulan'] == '5' || $_REQUEST['uiSlctBulan'] == '05' )
																	{
																		echo "<h3><span style=color:#000000>May - ".$_REQUEST['uiSlctTahun']."</span></h3>";
																	}
																	elseif ($_REQUEST['uiSlctBulan'] == '6' || $_REQUEST['uiSlctBulan'] == '06' )
																	{
																		echo "<h3><span style=color:#000000>June - ".$_REQUEST['uiSlctTahun']."</span></h3>";
																	}
																	elseif ($_REQUEST['uiSlctBulan'] == '7' || $_REQUEST['uiSlctBulan'] == '07' )
																	{
																		echo "<h3><span style=color:#000000>July - ".$_REQUEST['uiSlctTahun']."</span></h3>";
																	}
																	elseif ($_REQUEST['uiSlctBulan'] == '8' || $_REQUEST['uiSlctBulan'] == '08' )
																	{
																		echo "<h3><span style=color:#000000>August - ".$_REQUEST['uiSlctTahun']."</span></h3>";
																	}
																	elseif ($_REQUEST['uiSlctBulan'] == '9' || $_REQUEST['uiSlctBulan'] == '09' )
																	{
																		echo "<h3><span style=color:#000000>September - ".$_REQUEST['uiSlctTahun']."</span></h3>";
																	}
																	elseif ($_REQUEST['uiSlctBulan'] == '10' )
																	{
																		echo "<h3><span style=color:#000000>October - ".$_REQUEST['uiSlctTahun']."</span></h3>";
																	}
																	elseif ($_REQUEST['uiSlctBulan'] == '11' )
																	{
																		echo "<h3><span style=color:#000000>November - ".$_REQUEST['uiSlctTahun']."</span></h3>";
																	}
																	elseif ($_REQUEST['uiSlctBulan'] == '12' )
																	{
																		echo "<h3><span style=color:#000000>December - ".$_REQUEST['uiSlctTahun']."</span></h3>";
																	}
																}

																echo "</div>
											</div>";
											echo ("</form></th>
			<div class='row'><div class='span12' style='text-align:right;'><button type='submit'  name='btnPrint' id='btnPrint' value='Export Excel' class='btn'>Print</button><br><br>
			</div></div>
			<div id='divPrint'>
                      <table width='100%' border='1' id='idTable' cellspacing='0' cellpadding='0' bordercolor='#666666' style='border-collapse:collapse;'> ");
                    echo ("<tr bgcolor='#999999'> ");
                    echo ("<th width='5%'><p align='center'>ASSIGNMENT DATE</p></th><th width='5%'>
										<p align='center'>NIK - NAMA</p></th>
										<th width='5%'><p align='center'>OVERTIME</p></th>
										<th width='5%'><p align='center'>AGENDA</p></th>");
                    echo ('<tr>');
                    //--- SET RECORD --------------
                    $num_rows=1;
                    while (odbc_fetch_row($rs_skkl)) {
                      $input_date = convertDateAdis(odbc_result($rs_skkl, 'InputDate'));
											$nik_nama = odbc_result($rs_skkl, 'NIK').' - '.odbc_result($rs_skkl, 'Nama');
                      $assignment_date = convertDateAdis(odbc_result($rs_skkl, 'AssignmentDate'));
                      $agenda = odbc_result($rs_skkl, 'Agenda');
											$overtime = odbc_result($rs_skkl, 'BeginDate').' - '.odbc_result($rs_skkl, 'EndDate');

                      echo ("<td><font><p align='left' style='padding-left:5px;'>$assignment_date</font></p></td>");
											echo ("<td><font><p align='left' style='padding-left:5px;'>$nik_nama</font></p></td>");
                      echo ("<td><font><p align='left' style='padding-left:5px;'>$overtime</font></p></td>");
                      echo ("<td><font><p align='left' style='padding-left:5px;'>$agenda</font></p></td>");

                      echo ('</tr>');
                      $num_rows = $num_rows+1;
                    }
                    echo ('</table></div>');
                ?>
    <div style='float: right;'>
    <?php

      if ($hal > 1)
      {
        $prev = $hal-1;
        echo "<a href=skklReport.php?hal=$prev&txtKeyword=$txtKeyword&uiSlctBulan=$bulanPeriod&uiSlctTahun=$tahunPeriod><< Prev </a>";
      }else{
        echo "<< Prev";
      }
      //————————–Tampilkan link halaman 1,2,3,… —————————
      for($i=1;$i<=$jmlHal;$i++)
      {
        if ($i != $hal){
        echo "<a href=skklReport.php?hal=$i&txtKeyword=$txtKeyword&uiSlctBulan=$bulanPeriod&uiSlctTahun=$tahunPeriod>| $i | </a>";
        }else{
        echo " $i ";
        }
      }
      //————————-Link ke halaman berikutnya (Next)—————————
      if ($hal < $jmlHal)
      {
        $next = $hal + 1;
        echo "<a href=skklReport.php?hal=$next&txtKeyword=$txtKeyword&uiSlctBulan=$bulanPeriod&uiSlctTahun=$tahunPeriod> | Next >></a>";
      }else{
        echo "Next >> ";
      }

    ?>
    </div>

  </div>

  </div>
</div>
    </div>


<?php
include "template/bottom2.php"; //Load tenplate penutup dan load javascript eksternal
?>

<script type="text/javascript">
<!--
function confirmPost()
{
  var agree=confirm("Are you sure to delete this data?");
  if (agree)
  return true ;
  else
  return false ;
}
// -->

		$("#btnPrint").click(function (e) 
		{
		    //getting data from our tables
	     	    e.preventDefault();		
		    var result = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#divPrint').html());
		    var link = document.createElement("a");
		    document.body.appendChild(link);  // You need to add this line
		    link.download =  'exported_table_' + Math.floor((Math.random() * 9999999) + 1000000) + '.xls';
		    link.href = result;
		    link.click();	

		});

// $('#FormHRList').submit(function()
// {
// 	if($('input[@name=uiChkSKKLGrp]:checked').size() == 0){
//    		alert('Please Make A Selection for checkbox');
// 		return false;
// 	}
// 	else if($('input[@name=uiChkSKKLGrp]:checked').size() == 0){
//    		alert('Please Make A Selection for checkbox');
//    		return false;
// 	}else
// 	{
// 		document.getElementById('page-wrapper').style.visibility='hidden';
// 		$('#loading').show();
// 		return true;
// 	}
// });

</script>

