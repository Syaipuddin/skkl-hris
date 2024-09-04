<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

$pageTitle = "Profile";
// include "template/top2.php"; //Load template pembuka dan load css eksternal
// utk sementara diganti dulu ke top yg lain, soalnya suka ngebug bannernya muncul mulu
include "template/top2.php";
include "include/date_lib.php";
include "ESS/profile.php";


$essProfile = new ESSProfileLibrary();
$data = $essProfile->profileGetter($NIK);

  // GET HEREGISTRASI ICON
  function getHeregistrasiIcon($flag){
    if ($flag == 0) {
      $icon_heregistrasi = '<img src="img/icon/attention_yellow_smaller.png" style="margin-right: 10px;" alt="Icon" border="none" />';
    }
    else{
      $icon_heregistrasi = '<img src="img/icon/check.png" style="margin-right: 10px;" alt="Icon" border="none" />';
    }

    return $icon_heregistrasi;
  }

  function getData($name, $fce, $data) {
    $rows = (array_key_exists($fce, $data)) ? saprfc_table_rows($data[$fce], $name): sizeof($data[$name]);
    $result = array();
    for ($i=1; $i<=$rows;$i++) {
      if (array_key_exists($fce, $data)) {     
        $fceData = $data[$fce];
        $result = saprfc_table_read($fceData, $name, $i);
      } else {
        $result = $data[$name];
      }
    }

    return $result;
  }

  ?>
  <!-- INI BUAT ACCORDION -->
<style>


.accordion {
/*  background-color: #eee;*/
  background-color: #d4e4fb;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
  transition: 0.4s;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.active_accordion, .accordion:hover {
/*  background-color: #ccc;*/
  background-color: #eaf2ff;
}

.panel {
  padding: 0 18px;
  background-color: white;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
}


.accordion:after {
  content: '\25BC'; /* Unicode character for "plus" sign (+) */
  font-size: 13px;
  color: #777;
  float: right;
  margin-left: 5px;
}

.accordion.active:after {
  content: "\25B2"; /* Unicode character for "minus" sign (-) */
}
</style>

<?php
  $query = "SELECT * FROM ms_FlagHeregistrasi WHERE NIK = ?";
  $query_flag = odbc_prepare($conn, $query);
  odbc_execute($query_flag, array($NIK));

  $FlagPersonalData = odbc_result($query_flag, 'FlagPersonalData');
  $FlagAddress = odbc_result($query_flag, 'FlagAddress');
  $FlagContact = odbc_result($query_flag, "FlagContact");
  $FlagPersonalIdentity = odbc_result($query_flag, 'FlagPersonalIdentity');
  $FlagBankDetails = odbc_result($query_flag, 'FlagBankDetails');
  $FlagFamily = odbc_result($query_flag, "FlagFamily");
  $FlagFormalEdu = odbc_result($query_flag, 'FlagFormalEdu');
  $FlagTrainingCourse = odbc_result($query_flag, "FlagTrainingCourse");
  $FlagWorkExperience = odbc_result($query_flag, "FlagWorkExperience");
  $TotalFlag = odbc_result($query_flag, "TotalFlag");
  $TglUpdate = date("Y-m-d", strtotime(odbc_result($query_flag, "TglUpdate")));

  $FI_CV = getData("FI_CV", 'fce', $data);
  $FI_3385 = getData("FI_3385", 'fce', $data);
  $FI_BANK = getData("FI_BANK", "fce", $data);
  $FI_BNKA = getData("FI_BNKA", "fce", $data);
  $FI_MEDICAL = getData("FI_MEDICAL", "fce", $data);
  $FI_0319 = getData("FI_0319", "fce", $data);

  $retired_id = "";

  if (array_key_exists('fce', $data)) {
    if (array_key_exists('ACCID', $FI_0319)) {
      $retired_id = $FI_0319['ACCID'];
    }

    if (array_key_exists('BPJSI', $FI_3385)) {
      $FI_CV['BPJSI'] = $FI_3385['BPJSI'];
    } else {
      $FI_CV["BPJSI"] = "";
    }

    $IS_SAP = true;
  } else {
    $retired_id = $data["RETIRED_ID"];
    $IS_SAP = false;
  }

?>

<style type="text/css">
  .icon-centang-heading {
    display: flex;
    align-items: center;
  }
  .icon-centang {
      margin-right: 10px;
  }
</style>



<div id="exTab2" class="container">
  <ul class="nav nav-tabs">
    <?php
    if (isset($_GET['id'])) {
      echo '<li>';
    } else {
      echo '<li  class="active">';
    }
    ?>
    <a href="#1" data-toggle="tab">Profile</a>
    </li>
			<!-- <li><a href="#2" data-toggle="tab">Anjab</a></li> -->
    <?php
    if (isset($_GET['id'])) {
      echo '<li  class="active">';
    } else {
      echo '<li>';
    }
    ?>
    <a href="#3" id="tab_3" data-toggle="tab">Object On loan</a>
    </li>
  </ul>

  <div class="tab-content ">
    <?php
    if (isset($_GET['id'])) {
      echo '<div class="tab-pane" id="1">';
    } else {
      echo '<div class="tab-pane active" id="1">';
    }
    ?>

    <?php
    //Discover interface for function module ZHRFM_CV
    // $fce = saprfc_function_discover($rfc, "ZHRFM_CV");
    // if (!$fce) {
    //   echo "Discovering interface of function module failed";
    //   exit;
    // }

    // saprfc_import($fce, "FI_PERNR", $NIK);
    // saprfc_import($fce, "FI_PERNR_DIAKSES", $NIK);
    // //Fill internal tables
    // saprfc_table_init($fce, "FI_0105");
    // saprfc_table_init($fce, "FI_ANAK");
    // saprfc_table_init($fce, "FI_AWARD");
    // saprfc_table_init($fce, "FI_BANK");
    // saprfc_table_init($fce, "FI_BNKA");
    // saprfc_table_init($fce, "FI_CV");
    // saprfc_table_init($fce, "FI_MEDICAL");
    // saprfc_table_init($fce, "FI_MUTASI");
    // saprfc_table_init($fce, "FI_NSEKOLAH");
    // saprfc_table_init($fce, "FI_PENGLM");
    // saprfc_table_init($fce, "FI_SEKOLAH");
    // //Do RFC call of function ZHRFM_CV, for handling exceptions use saprfc_exception()
    // $rfc_rc = saprfc_call_and_receive($fce);
    // if ($rfc_rc != SAPRFC_OK) {
    //   if ($rfc == SAPRFC_EXCEPTION)
    //     echo ("Exception raised: " . saprfc_exception($fce));
    //   else
    //     echo (saprfc_error($fce));
    //   exit;
    // }
    // //Retrieve export parameters
    // $AUTORISASI = saprfc_export($fce, "AUTORISASI");
    // $FI_EXT = saprfc_export($fce, "FI_EXT");
    // $FI_NOKANTOR = saprfc_export($fce, "FI_NOKANTOR");
    // $rows = saprfc_table_rows($fce, "FI_0105");
    // if ($rows != 0) {
    //   for ($i = 1; $i <= $rows; $i++) {
    //     $FI_0105[] = saprfc_table_read($fce, "FI_0105", $i);
    //   }
    //   //            $FI_0105 = saprfc_table_read ($fce,"FI_0105",1);
    //   foreach ($FI_0105 as &$val) {
    //     if ($val['SUBTY'] == '0098') {
    //       $mobile_portal = $val['USRID'];
    //     }

    //   }
    // }
    
    ?>
    <div id="printarea" class="row">
      <div class="span11">
        <h2>Profile<input id="btn_print" class="btn pull-right" type="button" value="Print"
            onclick="javascript:printDiv('printarea')" />
          <a href="PD_HistoryListRequest.php?keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px"
            class='thickbox'><button class="btn btn-info btn-info-right" type="button">History List Request</button></a>
        </h2>
        <div class="row">
          <div class="span12">
            <?php /*if ($IS_SAP) echo '<a href="#" class="thickbox"><img src="img/icon/check.png" style="margin-right: 10px;" alt="Icon" border="none" /></a>';*/ ?>
            <h3>
              <?php
              // if ($IS_SAP) echo '<img src="img/icon/check.png" style="margin-right: 10px;" alt="Icon" border="none" />';
              $icon_heregistrasi = getHeregistrasiIcon($FlagPersonalData);
              echo $icon_heregistrasi; ?>
              
              Personal Data
              <?php
              if ($IS_SAP) echo '<a href="PD_PersonalData.php?keepThis=true&TB_iframe=true&height=500&width=700"
              style="font-size:10px" class="thickbox"><img src="img/icon/edit.png" border="none" /></a>';

                  // $GetStatusRead=odbc_exec($conn,"select COUNT(*) as ReadTotal
                  //         from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestDetail B on
                  //         A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID inner join PD_ChangeRequest C on
                  //         B.ChangeRequestID=C.ChangeRequestID
                  //         inner join PD_Field D on D.FieldID=B.FieldID
                  //         where NIK='$NIK' and StatusReadEmployee='False' and Status='2' and
                  //         FlagPA=1 and SectionID=1");

                  odbc_execute($GetStatusRead = odbc_prepare($conn,"select COUNT(*) as ReadTotal
                          from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestDetail B on
                          A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID inner join PD_ChangeRequest C on
                          B.ChangeRequestID=C.ChangeRequestID
                          inner join PD_Field D on D.FieldID=B.FieldID
                          where NIK=? and StatusReadEmployee='False' and Status='2' and
                          FlagPA=1 and SectionID=1"), array($NIK));

              $totalRead = odbc_result($GetStatusRead, "ReadTotal");
              if ($totalRead != 0) {
                echo '<a href="PD_ViewListReadPersonalData.php?userNIK=' . $NIK . '&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/informationmessage.gif" border="none" /></a>';
              } elseif ($totalRead == 0) {
                //cek pengajuan request ada atau tidak
                    // $GetCekStatusRequest=odbc_exec($conn,"select COUNT(*) as TotalRequest from
                    //                     PD_ChangeRequestDetail A
                    //                     inner join PD_Field B on A.FieldID=B.FieldID
                    //                     inner join PD_ChangeRequestDetailUpdate C on
                    //                     A.ChangeRequestInfotypeID=C.ChangeRequestInfotypeID
                    //                     where UserNIK='$NIK' AND SectionID=1");

                    odbc_execute($GetCekStatusRequest = odbc_prepare($conn,"select COUNT(*) as TotalRequest from
                                        PD_ChangeRequestDetail A
                                        inner join PD_Field B on A.FieldID=B.FieldID
                                        inner join PD_ChangeRequestDetailUpdate C on
                                        A.ChangeRequestInfotypeID=C.ChangeRequestInfotypeID
                                        where UserNIK=? AND SectionID=1"), array($NIK));

                $TotalRequest = odbc_result($GetCekStatusRequest, "TotalRequest");

                if ($TotalRequest != 0) {

                      $getDataPD="select distinct ChangeRequestInfotypeID as chrinfotypeid,[RequestDate], InfoTypeName,FieldContent,NIK from VW_PDPersonalData where NIK=? and SectionID=1";

                      // $rowSet = odbc_exec($conn,$getDataPD);
                      odbc_execute($rowSet = odbc_prepare($conn,$getDataPD),array($NIK));

                  while ($row = odbc_fetch_row($rowSet)) {
                        $idUpdateNotes=odbc_result($rowSet,'chrinfotypeid');
                        $getTotalNotes="select COUNT (*) as total_notes from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestInfotype B
                                    on A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID
                                    inner join PD_ChangeRequest C on C.ChangeRequestID=B.ChangeRequestID where A.ChangeRequestInfotypeID=? and Finish=0";
                        // $total_notes = odbc_exec($conn,$getTotalNotes);
                        odbc_execute($total_notes = odbc_prepare($conn,$getTotalNotes), array($idUpdateNotes));
                        $flag = 'true';
                        if (odbc_result($total_notes, 'total_notes') != 0) {

                              $getNotes="select * from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestInfotype B
                                          on A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID
                                          inner join PD_ChangeRequest C on C.ChangeRequestID=B.ChangeRequestID where A.ChangeRequestInfotypeID=? and Finish=0 order by Date desc";
                              // $NotesReply = odbc_exec($conn,$getNotes);

                              odbc_execute($NotesReply = odbc_prepare($conn,$getNotes), array($idUpdateNotes));

                          if (odbc_result($NotesReply, 'Status') != 3 && odbc_result($NotesReply, 'Status') != 4 && odbc_result($NotesReply, 'Status') != 6) {
                            $flag = 'false';
                            break;
                          }
                        }
                  }


                  if ($flag == 'false') {
                    echo '<a href="PD_ViewListReadPersonalData.php?userNIK=' . $NIK . '&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/information.png" border="none" /></a>';
                  }
                }
              }
              ?>
            </h3>
          </div>

          
          <div class="span8">
            <div class="row">
              <div class="span3">NIK</div>
              <div class="span5">
                <?php echo $NIK;?>
              </div>
            </div>
            
            <div class="row">
              <div class="span3">name</div>
              <div class="span5">
                <?php echo $FI_CV['NAMALENGKAP']?>
              </div>
            </div>
            <div class="row">
              <div class="span3">Initial</div>
              <div class="span5">
                <?php echo $FI_CV['INISIAL']?>
              </div>
            </div>
            <div class="row">
              <div class="span3">Nickname</div>
              <div class="span5">
                <?php echo $FI_CV['NAMAPANGGILAN']?>
              </div>
            </div>
            <div class="row">
              <div class="span3">Place/Date of Birth</div>
              <div class="span5">
                <?php echo $FI_CV['TEMPATLLAHIR'].', '.convertDate($FI_CV['TTL'])?>
              </div>
            </div>
            <div class="row">
              <div class="span3">Gender</div>
              <div class="span5">
                <?php echo $FI_CV['JENISKELAMIN'] ?>
              </div>
            </div>
            <div class="row">
              <div class="span3">Marital Status</div>
              <div class="span5">
                <?php echo $FI_CV['STATUSPERKAWINAN']?>
              </div>
            </div>
            <div class="row">
              <div class="span3">Marriage Date</div>
              <div class="span5">
                <?php echo convertDate($FI_CV['TANGGALPASANGAN']) ?>
              </div>
            </div>
            <div class="row">
              <div class="span3">Religion</div>
              <div class="span5">
                <?php echo $FI_CV['AGAMA'] ?>
              </div>
            </div>
            <div class="row">
              <div class="span3">Blood Type</div>
              <div class="span5">
                <?php echo $FI_CV['GOLDARAH']?>
              </div>
            </div>


          </div>
          <?php
          //Cari Foto dari ID Card Online
                                                $qry_c_photo = "SELECT count(*) as count FROM id_card_online WHERE nik = '$NIK'";
                                                $parameter1 = array($NIK);
                                                $qry_card = odbc_prepare($conn_card,$qry_c_photo);
                                                odbc_execute($qry_card, array($parameter1));
                                                // $c_photo     = odbc_result(odbc_exec($conn_card, $qry_c_photo,1), 'count');
                                                $c_photo = odbc_result($qry_card,'count');
                                                if ($c_photo == 1) {
                                                        $photo ='';
                                                        $qry_photo = "SELECT old_photo_path,path_photo FROM id_card_online WHERE nik = '$NIK'";
                                                        $parameter2 = array($NIK);
                                                        // $photo     = odbc_result(odbc_exec($conn_card, $qry_photo,1),'old_photo_path');
                                                        $prep_photo = odbc_prepare($conn_card,$qry_photo);
                                                        odbc_execute($prep_photo, array($parameter2));
                                                        $photo = odbc_result($prep_photo,'old_photo_path');
                                                        // $old_photo     = odbc_result(odbc_exec($conn_card, $qry_photo,1),'old_photo_path');
                                                        $prep_old_photo = odbc_prepare($conn_card,$qry_photo);
                                                        odbc_execute($prep_old_photo, array($parameter2));
                                                        $old_photo = odbc_result($prep_old_photo,'old_photo_path');

                                                        if (is_null($photo) == TRUE or $photo == '') {
                                                                // $photo     = odbc_result(odbc_exec($conn_card, $qry_photo,1),'path_photo');
                                                                $prep_photo = odbc_prepare($conn_card,$qry_photo);
                                                                odbc_execute($prep_photo, array($parameter2));
                                                                $photo = odbc_result($prep_photo,'path_photo');

                                                        }
                                                        elseif($old_photo==$photo)
                                                        {
                                                                $prep_photo = odbc_prepare($conn_card,$qry_photo);
                                                                odbc_execute($prep_photo, array($parameter2));
                                                                $photo = odbc_result($prep_photo,'path_photo');
                                                                // $photo     = odbc_result(odbc_exec($conn_card, $qry_photo,1),'path_photo');
                                                        }
                                                        $photo     = strtoupper(preg_replace("/\\\\/", "/", $photo));
                                                        $from      = $_SERVER['HTTP_HOST'];
                                                        switch ($from) {
                                                                case 'hr.kompasgramedia.com':
                                                                case 'hr.kompasgramedia.com:800':
                                                                case 'hr2.kompasgramedia.com':
                                                                        // request dari internet
                                                                        $photo     = str_replace(array("P:/","./ASSETS/"), "https://idcard.kompasgramedia.com/assets/", $photo);

                                                                        break;
                                                                case '10.10.55.7':
                                                                case '10.10.55.5':
                                                                case '10.10.55.16':
                                                                case '10.10.55.67':
                                                                case '10.9.70.30':
                                                                case '10.9.70.30:800':
                                                                case '10.9.70.31':
                                                                case '10.9.70.32':
                                                                case 'hr.kompasgramedia.co.id':
                                                                case 'hr.kompasgramedia.co.id:800':
                                                                        // request dari intranet
                                                                        $photo     = str_replace(array("P:/","./ASSETS/"), "http://10.10.55.45/assets/", $photo);



                                                                        break;
                                                                case '10.10.55.18':
                                                                case '10.10.55.25':
                                                                        // request dari dev
                                                                        $photo     = str_replace(array("P:/","./ASSETS/"), "http://idcard.kompasgramedia.com/assets/", $photo);



                                                                        break;
                                                        }
                                                }
	         ?>
      <div class="span2">
        <?php  echo '<img class="span2 offset1"  src="'.$photo.'"/>'; ?>
      </div>		

          <div class="span12">
            <h3>
              <?php
              // if ($IS_SAP) echo '<a href="#" class="thickbox"><img src="img/icon/check.png" style="margin-right: 10px;" alt="Icon" border="none" /></a>';
              $icon_heregistrasi = getHeregistrasiIcon($FlagAddress);
              echo $icon_heregistrasi;
              ?>
              Address 
              <?php
	      
	      if ($IS_SAP) echo '<a href="PD_PersonalAddressData.php?keepThis=true&TB_iframe=true&height=500&width=700"
              style="font-size:10px" class="thickbox"><img src="img/icon/edit.png" border="none" /></a>';
	      
                  // $GetStatusRead=odbc_exec($conn,"select COUNT(*) as ReadTotal
                  //         from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestDetail B on
                  //         A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID inner join PD_ChangeRequest C on
                  //         B.ChangeRequestID=C.ChangeRequestID
                  //         inner join PD_Field D on D.FieldID=B.FieldID
                  //         where NIK='$NIK' and StatusReadEmployee='False' and Status='2' and
                  //         FlagPA=1 and SectionID=2");

                  odbc_execute($GetStatusRead = odbc_prepare($conn,"select COUNT(*) as ReadTotal
                          from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestDetail B on
                          A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID inner join PD_ChangeRequest C on
                          B.ChangeRequestID=C.ChangeRequestID
                          inner join PD_Field D on D.FieldID=B.FieldID
                          where NIK=? and StatusReadEmployee='False' and Status='2' and
                          FlagPA=1 and SectionID=2"), array($NIK));

              $totalRead = odbc_result($GetStatusRead, "ReadTotal");
              if ($totalRead != 0) {
                echo '<a href="PD_ViewListReadPersonalData.php?userNIK=' . $NIK . '&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/informationmessage.gif" border="none" /></a>';
              } elseif ($totalRead == 0) {

                    //cek pengajuan request ada atau tidak
                    // $GetCekStatusRequest=odbc_exec($conn,"select COUNT(*) as TotalRequest from
                    //                     PD_ChangeRequestDetail A
                    //                     inner join PD_Field B on A.FieldID=B.FieldID
                    //                     inner join PD_ChangeRequestDetailUpdate C on
                    //                     A.ChangeRequestInfotypeID=C.ChangeRequestInfotypeID
                    //                     where UserNIK='$NIK' AND SectionID=2");

                    odbc_execute($GetCekStatusRequest = odbc_prepare($conn,"select COUNT(*) as TotalRequest from
                                        PD_ChangeRequestDetail A
                                        inner join PD_Field B on A.FieldID=B.FieldID
                                        inner join PD_ChangeRequestDetailUpdate C on
                                        A.ChangeRequestInfotypeID=C.ChangeRequestInfotypeID
                                        where UserNIK=? AND SectionID=2"), array($NIK));

                    $TotalRequest=odbc_result($GetCekStatusRequest,"TotalRequest");

                if ($TotalRequest != 0) {

                      $getDataPD="select distinct ChangeRequestInfotypeID as  chrinfotypeid,[RequestDate], InfoTypeName,FieldContent,NIK
                            from VW_PDPersonalData where NIK=? and SectionID=2";

                      // $rowSet = odbc_exec($conn,$getDataPD);
                      odbc_execute($rowSet = odbc_prepare($conn,$getDataPD), array($NIK));

                  while ($row = odbc_fetch_row($rowSet)) {
                    $idUpdateNotes = odbc_result($rowSet, 'chrinfotypeid');
                        $getNotes="select *  from PD_ChangeRequestDetailUpdate where ChangeRequestInfotypeID=? order by Date desc";
                        // $NotesReply = odbc_exec($conn,$getNotes);
                        odbc_execute($NotesReply = odbc_prepare($conn,$getNotes), array($idUpdateNotes));

                    $flag = 'true';
                    if (odbc_result($NotesReply, 'Status') != 3 && odbc_result($NotesReply, 'Status') != 4 && odbc_result($NotesReply, 'Status') != 6) {
                      $flag = 'false';
                      break;
                    }

                  }

                  if ($flag == 'false') {
                    echo '<a href="PD_ViewListReadPersonalData.php?userNIK=' . $NIK . '&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/information.png" border="none" /></a>';
                  }
                }
              }
              ?>
            </h3>
            <div class="row">
              <div class="span3">ID Address :</div>
              <div class="span9">
                <?php
                    if  (array_key_exists('fce2', $data)) {
                      $rows3 = saprfc_table_rows($data['fce2'], "FI_ID_ADDRESS");
                    } else {
                      $rows3 = sizeof($data['ADDRESS']['data']);
                    }

                    

                    for($i=1;$i<=$rows3;$i++){
                      $index_name = $data['ADDRESS']['index_name'];

                      if (array_key_exists('fce2', $data)) {
                        $fce2 = $data['fce2'];
                        $FI_ID_ADDRESS= saprfc_table_read($fce2,"FI_ID_ADDRESS",$i);
                        $IDregion = GetRegion($FI_ID_ADDRESS[$index_name['STATE']], $rfc);
                        $IDcountry = GetCountry($FI_ID_ADDRESS[$index_name['LAND1']], $rfc);
                      } else {
                        $FI_ID_ADDRESS = $data['ADDRESS']['data'][$i-1];
                        $IDregion = $essProfile->isIDField($FI_ID_ADDRESS[$index_name['STATE']]);
                        $IDcountry = $essProfile->isIDField($FI_ID_ADDRESS[$index_name['LAND1']]);
                      }

                      // CHECK IF DATA FROM ODOO AND PASS ONLY WHEN THE TYPE ARE MATCH WITH ID ADDRESS
                      if (!$IS_SAP && $FI_ID_ADDRESS[$index_name['TYPE']] != 'id') {
                        continue;
                      } 
                    
                      echo $FI_ID_ADDRESS[$index_name['STRAS']].','.$FI_ID_ADDRESS[$index_name['LOCAT']].','.$FI_ID_ADDRESS[$index_name['ORT01']].','.$FI_ID_ADDRESS[$index_name['PSTLZ']].','.$IDregion.','.$IDcountry;
                      echo '<br>Telp :'.$FI_ID_ADDRESS[$index_name['TELNR']];

                      }
                ?>
              </div>
            </div>
            <div class="row">
              <div class="span3">Residence Address :</div>
              <div class="span9">
                <?php
                    if (array_key_exists('fce', $data)) { 
                      $rows4 = saprfc_table_rows($fce2, "FI_RESIDENCE_ADDRESS");
                    } else {
                      $rows4 = sizeof($data['ADDRESS']['data']);
                    }

                    for($i=1; $i <= $rows4; $i++)
                        {
                          
                          $index_name = $data['ADDRESS']['index_name'];

                          if (array_key_exists('fce2', $data)) {
                            $FI_RESIDENCE_ADDRESS= saprfc_table_read ($data['fce2'],"FI_RESIDENCE_ADDRESS",$i);
                            $residenceRegion = GetRegion($FI_RESIDENCE_ADDRESS['STATE'], $rfc);
                            $residenceCountry = GetCountry($FI_RESIDENCE_ADDRESS['LAND1'], $rfc);
                          } else {
                            $FI_RESIDENCE_ADDRESS = $data['ADDRESS']['data'][$i-1];
                            $residenceRegion = $essProfile->isIDField($FI_ID_ADDRESS[$index_name['STATE']]);
                            $residenceCountry = $essProfile->isIDField($FI_ID_ADDRESS[$index_name['LAND1']]);
                          }

                          // CHECK IF DATA FROM ODOO AND PASS ONLY WHEN THE TYPE ARE MATCH WITH RESIDENCE ADDRESS
                          if (!$IS_SAP && $FI_RESIDENCE_ADDRESS[$index_name['TYPE']] != 'residence') {
                            continue;
                          } 

                          if (array_key_exists('fce3', $data)) {
                            $FI_RESIDENCE_STATUS = saprfc_table_read ($data['fce3'],"FI_RESIDENCE_STATUS",$i);
                            $residenceStatus = GetResidenceStatus($FI_RESIDENCE_STATUS['AUSBE'], $rfc);
                          } else {
                            $residenceStatus = $FI_RESIDENCE_ADDRESS[$index_name['AUSBE']];
                          }

                          echo $FI_RESIDENCE_ADDRESS[$index_name['STRAS']].','.$FI_RESIDENCE_ADDRESS[$index_name['LOCAT']].','.$FI_RESIDENCE_ADDRESS[$index_name['ORT01']].','.$FI_RESIDENCE_ADDRESS[$index_name['PSTLZ']].','.$residenceRegion.','.$residenceCountry;
                          echo '<br>Telp :'.$FI_RESIDENCE_ADDRESS[$index_name['TELNR']].'<br>Status :'.$residenceStatus;
                        }
                ?>
              </div>
            </div>
            <div class="row">
              <div class="span3">Tax Address :</div>
              <div class="span9">
                <?php
                  if (array_key_exists('fce2', $data)) {
                    $rows5 = saprfc_table_rows($data['fce2'], "FI_TAX_ADDRESS");
                  } else {
                    $rows5 = sizeof($data['ADDRESS']['data']);
                  }

                  for($i=1; $i<=$rows5; $i++)
                      {
                        $index_name = $data['ADDRESS']['index_name'];
                        
                        if (array_key_exists('fce2', $data)) {
                          $FI_TAX_ADDRESS = saprfc_table_read ($data['fce2'],"FI_TAX_ADDRESS",$i);
                          $taxRegion = GetRegion($FI_TAX_ADDRESS['STATE'], $rfc);
                          $taxCountry = GetCountry($FI_TAX_ADDRESS['LAND1'], $rfc);
                        } else {
                          $FI_TAX_ADDRESS = $data['ADDRESS']['data'][$i-1];
                          $taxRegion = $essProfile->isIDField($FI_TAX_ADDRESS[$index_name['STATE']]);
                          $taxCountry = $essProfile->isIDField($FI_TAX_ADDRESS[$index_name['LAND1']]);
                        }

                        if (!$IS_SAP && $FI_TAX_ADDRESS[$index_name['TYPE']] != 'tax') {
                          continue;
                        } 

                        echo $FI_TAX_ADDRESS[$index_name['STRAS']].','.$FI_TAX_ADDRESS[$index_name['LOCAT']].','.$FI_TAX_ADDRESS[$index_name['ORT01']].','.$FI_TAX_ADDRESS[$index_name['PSTLZ']].','.$taxRegion.','.$taxCountry;
                        echo '<br>Telp :'.$FI_TAX_ADDRESS[$index_name['TELNR']];
                      }
                ?>
              </div>
            </div>
            <div class="row">
              <div class="span3">1st Relatives Status :</div>
              <div class="span9">
                <?php
                  if (array_key_exists('fce2', $data)) {
                    $rows1 = saprfc_table_rows($data['fce2'], "FI_1ST_RELATIVES");
                  } else {
                    $rows1 = sizeof($data['ADDRESS']['data']);
                  }

                  for ($i=1; $i<=$rows1; $i++)
                      {
                        $index_name = $data['ADDRESS']['index_name'];

                        if(array_key_exists('fce2', $data)){
                          $FI_1ST_RELATIVES= saprfc_table_read ($data['fce2'],"FI_1ST_RELATIVES",$i);
                          $firstRelativesRegion = GetRegion($FI_1ST_RELATIVES['STATE'], $rfc);
                          $firstRelativesCountry = GetCountry($FI_1ST_RELATIVES['LAND1'], $rfc);
                        } else {
                          $FI_1ST_RELATIVES = $data['ADDRESS']['data'][$i-1];
                          $firstRelativesRegion = $essProfile->isIDField($FI_1ST_RELATIVES[$index_name['STATE']]);
                          $firstRelativesCountry = $essProfile->isIDField($FI_1ST_RELATIVES[$index_name['LAND1']]);
                        }   

                        if (!$IS_SAP && $FI_1ST_RELATIVES[$index_name['TYPE']] != 'relatives_1') {
                          continue;
                        } 

                        echo $FI_1ST_RELATIVES[$index_name['NAME2']].'<br>';
                        echo $FI_1ST_RELATIVES[$index_name['STRAS']].','.$FI_1ST_RELATIVES[$index_name['LOCAT']].','.$FI_1ST_RELATIVES[$index_name['ORT01']].','.$FI_1ST_RELATIVES[$index_name['PSTLZ']].','.$firstRelativesRegion.','.$firstRelativesCountry;
                        echo '<br>Telp :'.$FI_1ST_RELATIVES[$index_name['TELNR']];
                      }
                ?>
              </div>
            </div>

            <div class="row">
              <div class="span3">2nd Relatives Status :</div>
              <div class="span9">
                <?php
                  if (array_key_exists('fce2', $data)) {
                    $rows2 = saprfc_table_rows($data['fce2'], "FI_2ND_RELATIVES");
                  } else {
                    $rows2 = sizeof($data['ADDRESS']['data']);
                  }

                  for($i=1; $i<=$rows2; $i++)
                        {
                          if(array_key_exists('fce2', $data)) {
                            $FI_2ND_RELATIVES = saprfc_table_read ($data['fce2'],"FI_2ND_RELATIVES",$i);
                            $secondRelativesRegion = GetRegion($FI_2ND_RELATIVES['STATE'], $rfc);
                            $secondRelativesCountry = GetCountry($FI_2ND_RELATIVES['LAND1'], $rfc);
                          } else {
                            $FI_2ND_RELATIVES = $data['ADDRESS']['data'][$i-1];
                            $secondRelativesRegion = $essProfile->isIDField($FI_2ND_RELATIVES[$index_name['STATE']]);
                            $secondRelativesCountry = $essProfile->isIDField($FI_2ND_RELATIVES[$index_name['LAND1']]);
                          }

                          if (!$IS_SAP && $FI_2ND_RELATIVES[$index_name['TYPE']] != 'relatives_2') {
                            continue;
                          } 

                          echo $FI_2ND_RELATIVES[$index_name['NAME2']].'<br>';
                          echo $FI_2ND_RELATIVES[$index_name['STRAS']].','.$FI_2ND_RELATIVES[$index_name['LOCAT']].','.$FI_2ND_RELATIVES[$index_name['ORT01']].','.$FI_2ND_RELATIVES[$index_name['PSTLZ']].','.$secondRelativesRegion.','.$secondRelativesCountry;
                          echo '<br>Telp :'.$FI_2ND_RELATIVES[$index_name['TELNR']];
                        }
                  
                ?>
              </div>
            </div>

          </div>
          <div class="span12">
            <h3>
              <?php
              // if ($IS_SAP) echo '<img src="img/icon/check.png" style="margin-right: 10px;" alt="Icon" border="none" />';
              $icon_heregistrasi = getHeregistrasiIcon($FlagContact);
              echo $icon_heregistrasi;
              ?>
	      
	      Contact 
              <?php
              if ($IS_SAP) echo "<a href='PD_ContactData.php?keepThis=true&TB_iframe=true&height=500&width=700' class='thickbox'
              style='font-size:10px'><img src='img/icon/edit.png' border='none' /></a>";
	      
                  // $GetStatusRead=odbc_exec($conn,"select COUNT(*) as ReadTotal
                  //         from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestDetail B on
                  //         A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID inner join PD_ChangeRequest C on
                  //         B.ChangeRequestID=C.ChangeRequestID
                  //         inner join PD_Field D on D.FieldID=B.FieldID
                  //         where NIK='$NIK' and StatusReadEmployee='False' and Status='2' and
                  //         FlagPA=1 and SectionID=3");

                  odbc_execute($GetStatusRead = odbc_prepare($conn,"select COUNT(*) as ReadTotal
                          from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestDetail B on
                          A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID inner join PD_ChangeRequest C on
                          B.ChangeRequestID=C.ChangeRequestID
                          inner join PD_Field D on D.FieldID=B.FieldID
                          where NIK=? and StatusReadEmployee='False' and Status='2' and
                          FlagPA=1 and SectionID=3"), array($NIK));

              $totalRead = odbc_result($GetStatusRead, "ReadTotal");
              if ($totalRead != 0) {
                echo '<a href="PD_ViewListReadPersonalData.php?userNIK=' . $NIK . '&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/informationmessage.gif" border="none" /></a>';
              } elseif ($totalRead == 0) {

                //cek pengajuan request ada atau tidak
                    // $GetCekStatusRequest=odbc_exec($conn,"select COUNT(*) as TotalRequest from
                    //                     PD_ChangeRequestDetail A
                    //                     inner join PD_Field B on A.FieldID=B.FieldID
                    //                     inner join PD_ChangeRequestDetailUpdate C on
                    //                     A.ChangeRequestInfotypeID=C.ChangeRequestInfotypeID
                    //                     where UserNIK='$NIK' AND SectionID=3");

                    odbc_execute($GetCekStatusRequest = odbc_prepare($conn,"select COUNT(*) as TotalRequest from
                                        PD_ChangeRequestDetail A
                                        inner join PD_Field B on A.FieldID=B.FieldID
                                        inner join PD_ChangeRequestDetailUpdate C on
                                        A.ChangeRequestInfotypeID=C.ChangeRequestInfotypeID
                                        where UserNIK=? AND SectionID=3"), array($NIK));
                $TotalRequest = odbc_result($GetCekStatusRequest, "TotalRequest");

                if ($TotalRequest != 0) {

                  $getDataPD = "select distinct ChangeRequestInfotypeID as chrinfotypeid,[RequestDate], InfoTypeName,FieldContent,NIK
                            from VW_PDPersonalData where NIK=? and SectionID=3";

                      // $rowSet = odbc_exec($conn,$getDataPD);
                      odbc_execute($rowSet = odbc_prepare($conn,$getDataPD), array($NIK));

                  while ($row = odbc_fetch_row($rowSet)) {
                    $idUpdateNotes = odbc_result($rowSet, 'chrinfotypeid');
                        $getNotes="select *  from PD_ChangeRequestDetailUpdate where ChangeRequestInfotypeID=? order by Date desc";
                        // $NotesReply = odbc_exec($conn,$getNotes);
                        odbc_execute($NotesReply = odbc_prepare($conn,$getNotes), array($idUpdateNotes));

                    $flag = 'true';
                    if (odbc_result($NotesReply, 'Status') != 3 && odbc_result($NotesReply, 'Status') != 4 && odbc_result($NotesReply, 'Status') != 6) {
                      $flag = 'false';
                      break;
                    }

                  }

                  if ($flag == 'false') {
                    echo '<a href="PD_ViewListReadPersonalData.php?userNIK=' . $NIK . '&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/information.png" border="none" /></a>';
                  }
                }
              }
              ?>
            </h3>

            <div class="row">
              <div class="span3">Mobile Phone No :</div>
              <div class="span3">
                <?php echo $FI_CV['HP']?>
              </div>
            </div>
            <div class="row">
              <div class="span3">E-mail :</div>
              <div class="span3">
                <?php echo $FI_CV['EMAIL'] ?>
              </div>
            </div>

            <!--
                  <div class="row">
                    <div class="span3">Registered E-mail:</div>
                    <div class="span3"><?php echo odbc_result(odbc_exec($conn, "select email from tr_login where userLogin = '" . $NIK . "'"), 1) ?></div>
                  </div>
                  -->

          </div>
          <div class="span12">
            <h3>
              <?php
	      // if ($IS_SAP) echo '<img src="img/icon/check.png" style="margin-right: 10px;" alt="Icon" border="none" />';
              $icon_heregistrasi = getHeregistrasiIcon($FlagPersonalIdentity);
              echo $icon_heregistrasi;
              ?>
              Personal Identity 
              <?php
              if ($IS_SAP) echo "<a
              href='PD_PersonalIdentityData.php?keepThis=true&TB_iframe=true&height=500&width=700'
              style='font-size:10px' class='thickbox'><img src='img/icon/edit.png' border='none' /></a>";

                  // $GetStatusRead=odbc_exec($conn,"select COUNT(*) as ReadTotal
                  //         from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestDetail B on
                  //         A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID inner join PD_ChangeRequest C on
                  //         B.ChangeRequestID=C.ChangeRequestID
                  //         inner join PD_Field D on D.FieldID=B.FieldID
                  //         where NIK='$NIK' and StatusReadEmployee='False' and Status='2' and
                  //         FlagPA=1 and SectionID=4");

                  odbc_execute($GetStatusRead = odbc_prepare($conn,"select COUNT(*) as ReadTotal
                          from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestDetail B on
                          A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID inner join PD_ChangeRequest C on
                          B.ChangeRequestID=C.ChangeRequestID
                          inner join PD_Field D on D.FieldID=B.FieldID
                          where NIK=? and StatusReadEmployee='False' and Status='2' and
                          FlagPA=1 and SectionID=4"), array($NIK));

              $totalRead = odbc_result($GetStatusRead, "ReadTotal");
              if ($totalRead != 0) {
                echo '<a href="PD_ViewListReadPersonalData.php?userNIK=' . $NIK . '&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/informationmessage.gif" border="none" /></a>';
              } elseif ($totalRead == 0) {

                    //cek pengajuan request ada atau tidak
                    // $GetCekStatusRequest=odbc_exec($conn,"select COUNT(*) as TotalRequest from
                    //                     PD_ChangeRequestDetail A
                    //                     inner join PD_Field B on A.FieldID=B.FieldID
                    //                     inner join PD_ChangeRequestDetailUpdate C on
                    //                     A.ChangeRequestInfotypeID=C.ChangeRequestInfotypeID
                    //                     where UserNIK='$NIK' AND SectionID=4");

                    odbc_execute($GetCekStatusRequest = odbc_prepare($conn,"select COUNT(*) as TotalRequest from
                                        PD_ChangeRequestDetail A
                                        inner join PD_Field B on A.FieldID=B.FieldID
                                        inner join PD_ChangeRequestDetailUpdate C on
                                        A.ChangeRequestInfotypeID=C.ChangeRequestInfotypeID
                                        where UserNIK=? AND SectionID=4"), array($NIK));

                $TotalRequest = odbc_result($GetCekStatusRequest, "TotalRequest");

                if ($TotalRequest != 0) {

                      $getDataPD="select distinct ChangeRequestInfotypeID as chrinfotypeid,[RequestDate], InfoTypeName,FieldContent,NIK
                            from VW_PDPersonalData where NIK=? and SectionID=4";

                      // $rowSet = odbc_exec($conn,$getDataPD);

                      odbc_execute($rowSet = odbc_prepare($conn,$getDataPD), array($NIK));

                  while ($row = odbc_fetch_row($rowSet)) {
                        $idUpdateNotes=odbc_result($rowSet,'chrinfotypeid');
                        $getNotes="select *  from PD_ChangeRequestDetailUpdate where ChangeRequestInfotypeID=? order by Date desc";
                        // $NotesReply = odbc_exec($conn,$getNotes);
                        odbc_execute($NotesReply = odbc_prepare($conn,$getNotes), array($idUpdateNotes));

                    $flag = 'true';
                    if (odbc_result($NotesReply, 'Status') != 3 && odbc_result($NotesReply, 'Status') != 4 && odbc_result($NotesReply, 'Status') != 6) {
                      $flag = 'false';
                      break;
                    }

                  }

                  if ($flag == 'false') {
                    echo '<a href="PD_ViewListReadPersonalData.php?userNIK=' . $NIK . '&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/information.png" border="none" /></a>';
                  }
                }
              }
              ?>
            </h3>
            <div class="row">
              <div class="span3">ID Number</div>
              <div class="span3">
                <?php echo $FI_CV['NOKTP']; ?>
              </div>
            </div>
                  <!-- ditakeout dlu taxid, spy kary ngisi dlu semua taxid yg 16 digit -->
                  <!-- <div class="row">
                    <div class="span3">Tax ID</div>
                    <div class="span3"><?php echo $FI_CV['TAXID']?></div>
                  </div> -->
                  <div class="row">
                    <div class="span3">BPJS Kesehatan Number</div>
                    <div class="span3"><?php echo $FI_CV['BPJSI']?></div>
                  </div>
            <div class="row">
              <div class="span3">Jamsostek Number</div>
              <div class="span3">
                <?php echo $FI_CV['NOJAMSOSTEK'] ?> <!-- to be changed later -->
              </div>
            </div>
            <div class="row">
              <div class="span3">Retired ID Number (ID Pensiun)</div> <!-- to be changed later -->
              <div class="span3">
                <?php echo $retired_id ?>
              </div>
            </div>
            <div class="row">
              <div class="span3">"A" Driving License Number</div>
              <div class="span3">
                <?php echo $FI_CV['NOSIMA'] ?>
              </div>
            </div>
            <div class="row">
              <div class="span3">"B" Driving License Number</div>
              <div class="span3">
                <?php echo $FI_CV['NOSIMB'] ?>
              </div>
            </div>
            <div class="row">
              <div class="span3">"C" Driving License Number</div>
              <div class="span3">
                <?php echo $FI_CV['NOSIMC']?>
              </div>
            </div>
            <div class="row">
              <div class="span3">Passport Number</div>
              <div class="span3">
                <?php echo $FI_CV['NOPASPORT']?>
              </div>
            </div>
          </div>
          <div class="span6">
            <h3>
              <?php
              // if ($IS_SAP) echo '<img src="img/icon/check.png" style="margin-right: 10px;" alt="Icon" border="none" />';
              /*$icon_heregistrasi = getHeregistrasiIcon($FlagBankDetails);
              echo $icon_heregistrasi;*/
              ?>
              Bank Details 
              <?php
	      // $GetStatusRead=odbc_exec($conn,"select COUNT(*) as ReadTotal
                  //         from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestDetail B on
                  //         A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID inner join PD_ChangeRequest C on
                  //         B.ChangeRequestID=C.ChangeRequestID
                  //         inner join PD_Field D on D.FieldID=B.FieldID
                  //         where NIK='$NIK' and StatusReadEmployee='False' and Status='2' and
                  //         FlagPA=1 and SectionID=5");

              // DITUTUP DULU, KARENA BANK WAJIB KIRIM BUKTI
              /*if ($IS_SAP) echo "<a href='PD_BankData.php?keepThis=true&TB_iframe=true&height=500&width=700'
              style='font-size:10px' class='thickbox'><img src='img/icon/edit.png' border='none' /></a>";*/

                  odbc_execute($GetStatusRead = odbc_prepare($conn,"select COUNT(*) as ReadTotal
                          from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestDetail B on
                          A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID inner join PD_ChangeRequest C on
                          B.ChangeRequestID=C.ChangeRequestID
                          inner join PD_Field D on D.FieldID=B.FieldID
                          where NIK=? and StatusReadEmployee='False' and Status='2' and
                          FlagPA=1 and SectionID=5"), array($NIK));

              $totalRead = odbc_result($GetStatusRead, "ReadTotal");
              if ($totalRead != 0) {
                echo '<a href="PD_ViewListReadPersonalData.php?userNIK=' . $NIK . '&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/informationmessage.gif" border="none" /></a>';
              } elseif ($totalRead == 0) {

                //cek pengajuan request ada atau tidak
                    // $GetCekStatusRequest=odbc_exec($conn,"select COUNT(*) as TotalRequest from
                    //                     PD_ChangeRequestDetail A
                    //                     inner join PD_Field B on A.FieldID=B.FieldID
                    //                     inner join PD_ChangeRequestDetailUpdate C on
                    //                     A.ChangeRequestInfotypeID=C.ChangeRequestInfotypeID
                    //                     where UserNIK='$NIK' AND SectionID=5");

                    odbc_execute($GetCekStatusRequest = odbc_prepare($conn,"select COUNT(*) as TotalRequest from
                                        PD_ChangeRequestDetail A
                                        inner join PD_Field B on A.FieldID=B.FieldID
                                        inner join PD_ChangeRequestDetailUpdate C on
                                        A.ChangeRequestInfotypeID=C.ChangeRequestInfotypeID
                                        where UserNIK=? AND SectionID=5"), array($NIK));

                $TotalRequest = odbc_result($GetCekStatusRequest, "TotalRequest");

                if ($TotalRequest != 0) {

                  $getDataPD = "select distinct ChangeRequestInfotypeID as chrinfotypeid,[RequestDate], InfoTypeName,FieldContent,NIK
                            from VW_PDPersonalData where NIK=? and SectionID=5";

                      // $rowSet = odbc_exec($conn,$getDataPD);
                      odbc_execute($rowSet = odbc_prepare($conn,$getDataPD), array($NIK));

                  while ($row = odbc_fetch_row($rowSet)) {
                    $idUpdateNotes = odbc_result($rowSet, 'chrinfotypeid');
                        $getNotes="select *  from PD_ChangeRequestDetailUpdate where ChangeRequestInfotypeID=? order by Date desc";
                        // $NotesReply = odbc_exec($conn,$getNotes);

                        odbc_execute($NotesReply = odbc_prepare($conn,$getNotes), array($idUpdateNotes));

                    $flag = 'true';
                    if (odbc_result($NotesReply, 'Status') != 3 && odbc_result($NotesReply, 'Status') != 4 && odbc_result($NotesReply, 'Status') != 6) {
                      $flag = 'false';
                      break;
                    }

                  }

                  if ($flag == 'false') {
                    echo '<a href="PD_ViewListReadPersonalData.php?userNIK=' . $NIK . '&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/information.png" border="none" /></a>';
                  }
                }
              }
              ?>
            </h3>

            <div class="row">
              <div class="span3">Holder Name</div>
              <div class="span3">
                <?php echo $FI_BANK['EMFTX']?>
              </div>
            </div>

            <div class="row">
              <div class="span3">Bank Account No</div>
              <div class="span3">
                <?php echo $FI_BANK['BANKN']?>
              </div>
            </div>

                  <div class="row">
                    <div class="span3">Bank Name</div>
                    <div class="span3"><?php echo $FI_BNKA['BANKA']?></div>
                  </div>

            <!--<div class="row">
                    <div class="span3">Bank Branch</div>
                    <div class="span3"><?php echo $FI_BNKA['BRNCH'] ?></div>
                  </div>

                  <div class="row">
                    <div class="span3">Bank Address</div>
                    <div class="span3"><?php echo $FI_BNKA['STRAS'] ?></div>
                  </div>

                  <div class="row">
                    <div class="span3">Bank City</div>
                    <div class="span3"><?php echo $FI_BNKA['ORT01'] ?></div>
                  </div>-->

          </div>
          <div class="span12">
            <h3>Employee Info</h3>
            <div class="row">
              <div class="span3">Unit</div>
              <div class="span9">
                <?php echo $FI_CV['UNIT'] ?>
              </div>
            </div>
            <div class="row">
              <div class="span3">Position</div>
              <div class="span9">
                <?php echo $FI_CV['POSITIONS'] ?>
              </div>
            </div>
            <div class="row">
              <div class="span3">Employee Status</div>
              <div class="span9">
                <?php echo $FI_CV['STATUS'] ?>
              </div>
            </div>
            <div class="row">
              <div class="span3">Employee Start Date</div>
              <div class="span9">
                <?php echo convertDate($FI_CV['TGLMASUK']) ?>
              </div>
            </div>
            <div class="row">
              <div class="span3">Full Employment Date</div>
              <div class="span9">
                <?php echo convertDate($FI_CV['TGLDIANGKAT']) ?>
              </div>
            </div>
            <div class="row">
              <div class="span3">Hospital Care 1st Program Group</div>
              <div class="span9">
                <?php echo $FI_CV['GOLRS1'] ?>
              </div>
            </div>
            <div class="row">
              <div class="span3">Hospital Care 2nd Program Group</div>
              <div class="span9">
                <?php echo $FI_CV['GOLRS2'] ?>
              </div>
            </div>
            <div class="row">
              <div class="span3">DLK Group</div>
              <div class="span9">
                <?php echo $FI_CV['GOLDLK'] ?>
              </div>
            </div>
            <div class="row">
              <div class="span3">Attendance Location</div>
              <div class="span9">
                <?php echo $FI_CV['LOKASIABSEN'] ?>
              </div>
            </div>
            <div class="row">
              <div class="span3">HR Unit</div>
              <div class="span9">
                <?php echo $FI_CV['HRUNIT'] ?>
              </div>
            </div>
          </div>
            <div class="span11">
              <br>
              <hr>
              <p style="color: blue; text-align: right; margin: 0px;">*click for detail</p>
            </div>
	    
	    <?php
          if (array_key_exists('fce', $data)) {
            $rows = saprfc_table_rows($data['fce'], "FI_ANAK");
          } else {
            $rows = sizeof($data['FI_ANAK']);
          }

          if ($rows > 0) {
            ?>
	    
            <div class="span11">
              <br>
                <button class="accordion" value="family">
                  <h3><?php
                    $icon_heregistrasi = getHeregistrasiIcon($FlagFamily);
                    echo $icon_heregistrasi;
                    ?>Family
                  </h3>
                </button>
                <div class="panel">
                  <div id="return_ajax_family">
                  </div>
                </div>
            </div>
          <?php }
          //if($rows != 0){
          ?>
          <div class="span11">
            <button class="accordion" value="education">
              <h3><?php
            // if ($IS_SAP) echo '<img src="img/icon/check.png" style="margin-right: 10px;" alt="Icon" border="none" />';
              $icon_heregistrasi = getHeregistrasiIcon($FlagFormalEdu);
              echo $icon_heregistrasi;
              ?>Formal Education
              </h3>
            </button>
            <div class="panel">
              <div id="return_ajax_education">
              </div>
            </div>
          </div>
          <?php
          //}
          //if($rows != 0){
          ?>
          <div class="span11">
            <button class="accordion" value="training">
              <h3><?php
              $icon_heregistrasi = getHeregistrasiIcon($FlagTrainingCourse);
              echo $icon_heregistrasi;
              ?>Training & Courses
              </h3>
            </button>
            <div class="panel">
              <div id="return_ajax_training">
              </div>
            </div>
          </div>
          <?php
          //}
          //if($rows != 0){
          ?>
          <div class="span11">
            <button class="accordion" value="work">
              <h3><?php
              $icon_heregistrasi = getHeregistrasiIcon($FlagWorkExperience);
              echo $icon_heregistrasi;
              ?>Work Experience
              </h3>
            </button>
            <div class="panel">
              <div id="return_ajax_work">
              </div>
            </div>
          </div>
          <?php
          //}
          // $rows = saprfc_table_rows($fce, "FI_AWARD");

          if (array_key_exists('fce', $data)) {
            $rows = saprfc_table_rows($data['fce'], "FI_AWARD");
          } else {
            $rows = 0;
          }

          if ($rows > 0) {
            ?>
            <div class="span11">
              <button class="accordion" value="awards">
                <h3>Awards</h3>
              </button>
              <div class="panel">
                <div id="return_ajax_awards">
                </div>
              </div>
            </div>
          <?php }
          // $rows = saprfc_table_rows ($fce,"FI_MUTASI");
          if (array_key_exists('fce', $data)) {
            $rows = saprfc_table_rows($data['fce'], "FI_MUTASI");
          } else {
            $rows = sizeof($data['FI_MUTASI']);
          }

          if ($rows > 0) {
            ?>
            <div class="span11">
            <button class="accordion" value="mutasi">
              <h3>Organizational Assignment</h3>
            </button>
              <div class="panel">
              	<div id="return_ajax_mutasi">
                </div>
              </div>
            </div>
          <?php } 
            // ONLY EXIST IN ODOO
            if (array_key_exists('fce', $data)) {
              $rows = 0;
            } else {
              $rows = sizeof($data['FI_ADTJB']['data']);
            }

            if ($rows > 0) {
              ?> 
              <div class="span11">
                <button class="accordion" value="additional">
                  <h3>Additional Job Position</h3>
                </button>
                  <div class="panel">
                    <div id="return_ajax_additional">
                    </div>
                  </div>
                </div>
          
          <?php } ?>

        </div>
      </div>
    </div>
  </div>
  <div class="tab-pane" id="2">

    <div class="accordion" id="accordion_list">

      <div class="accordion-group">
        <div class="accordion-heading background_accordion">
          <a class="accordion-toggle" id="link_organisasi" data-toggle="collapse" data-parent="#accordion2"
            href="#collapseOne">Kedudukan Dalam Organisasi</a>
        </div>
        <div id="collapseOne" class="accordion-body collapse">
          <div class="accordion-inner">
            <div id="ajax_organisasi" style="height:100%;"><br><br><br>
              <center><img src="img/loading.gif"><br><br><br><br><br>
                <h4>Please Wait . . . </h4>
              </center>
            </div>
          </div>
        </div>
      </div>


      <div class="accordion-group">
        <div class="accordion-heading background_accordion">
          <a class="accordion-toggle" data-toggle="collapse" id="link_uraian_pekerjaan" data-parent="#accordion2"
            href="#collapseTwo">Uraian Pekerjaan</a>
        </div>
        <div id="collapseTwo" class="accordion-body collapse">
          <div class="accordion-inner">
            <div id="ajax_uraian_pekerjaan" style="height:210px"><br><br><br>
              <center><img src="img/loading.gif"><br>
                <h4>Please Wait . . . </h4>
              </center>
            </div>
          </div>
        </div>
      </div>


      <div class="accordion-group">
        <div class="accordion-heading background_accordion">
          <a class="accordion-toggle" data-toggle="collapse" id="link_uraian_posisi" data-parent="#accordion3"
            href="#collapseThree">Uraian Posisi</a>
        </div>
        <div id="collapseThree" class="accordion-body collapse">
          <div class="accordion-inner">
            <div id="ajax_uraian_posisi" style="height:210px"><br><br><br>
              <center><img src="img/loading.gif" height="30" width="30"><br>
                <h4>Please Wait . . . </h4>
              </center>
            </div>
          </div>
        </div>
      </div>


      <div class="accordion-group">
        <div class="accordion-heading background_accordion">
          <a class="accordion-toggle" data-toggle="collapse" id="link_kewenangan" data-parent="#accordion2"
            href="#collapseFour">Kewenangan</a>
        </div>
        <div id="collapseFour" class="accordion-body collapse">
          <div class="accordion-inner">
            <div id="ajax_kewenangan" style="height:210px"><br><br><br>
              <center><img src="img/loading.gif" height="30" width="30"><br>
                <h4>Please Wait . . . </h4>
              </center>
            </div>
          </div>
        </div>
      </div>

      <div class="accordion-group">
        <div class="accordion-heading background_accordion">
          <a class="accordion-toggle" data-toggle="collapse" id="link_hubungan_internal" data-parent="#accordion2"
            href="#collapseFive">Hubungan Internal</a>
        </div>
        <div id="collapseFive" class="accordion-body collapse">
          <div class="accordion-inner">
            <div id="ajax_hubungan_internal" style="height:210px"><br><br><br>
              <center><img src="img/loading.gif" height="30" width="30"><br>
                <h4>Please Wait . . . </h4>
              </center>
            </div>
          </div>
        </div>
      </div>

      <div class="accordion-group">
        <div class="accordion-heading background_accordion">
          <a class="accordion-toggle" data-toggle="collapse" id="link_hubungan_eksternal" data-parent="#accordion2"
            href="#collapseSix">Hubungan Eksternal</a>
        </div>
        <div id="collapseSix" class="accordion-body collapse">
          <div class="accordion-inner">
            <div id="ajax_hubungan_eksternal" style="height:210px"><br><br><br>
              <center><img src="img/loading.gif" height="30" width="30"><br>
                <h4>Please Wait . . . </h4>
              </center>
            </div>
          </div>
        </div>
      </div>

      <div class="accordion-group">
        <div class="accordion-heading background_accordion">
          <a class="accordion-toggle" data-toggle="collapse" id="link_dimensi" data-parent="#accordion2"
            href="#collapseSeven">Dimensi</a>
        </div>
        <div id="collapseSeven" class="accordion-body collapse">
          <div class="accordion-inner">
            <div id="ajax_dimensi" style="height:210px"><br><br><br>
              <center><img src="img/loading.gif" height="30" width="30"><br>
                <h4>Please Wait . . . </h4>
              </center>
            </div>
          </div>
        </div>
      </div>

      <div class="accordion-group">
        <div class="accordion-heading background_accordion">
          <a class="accordion-toggle" data-toggle="collapse" id="link_kualifikasi" data-parent="#accordion2"
            href="#collapseEight">Kualifikasi</a>
        </div>
        <div id="collapseEight" class="accordion-body collapse">
          <div class="accordion-inner">
            <div id="ajax_kualifikasi" style="height:210px"><br><br><br>
              <center><img src="img/loading.gif" height="30" width="30"><br>
                <h4>Please Wait . . . </h4>
              </center>
            </div>
          </div>
        </div>
      </div>

      <div class="accordion-group">
        <div class="accordion-heading background_accordion">
          <a class="accordion-toggle" data-toggle="collapse" id="link_kpi" data-parent="#accordion2"
            href="#collapseNine">Key Performance Indicator</a>
        </div>
        <div id="collapseNine" class="accordion-body collapse">
          <div class="accordion-inner">
            <div id="ajax_kpi" style="height:210px"><br><br><br>
              <center><img src="img/loading.gif" height="30" width="30"><br>
                <h4>Please Wait . . . </h4>
              </center>
            </div>
          </div>
        </div>
      </div>


    </div>

    <input type="hidden" id="opernik" value="<?php echo $NIK; ?>" />

  </div>

  <?php
  if (isset($_GET['id'])) {
    echo '<div class="tab-pane active" id="3">';
  } else {
    echo '<div class="tab-pane" id="3">';
  }
  ?>
  <div class="row">
    <div class="span12">
      <h2>Object on Loan</h2>
      <div id="on_loan">

      </div>
    </div>
  </div>
</div>

</div>

<?php
include "template/bottom5.php"; //Load tenplate penutup dan load javascript eksternal
if ($browser['name'] == 'msie' and $browser['version'] == '6.0') {
  echo '<script src="js/bootstrap-ieFix.js"></script>';
} else {
  echo '<script src="js/bootstrap-collapse.js"></script>';
}
include "include/loadingAjax.php";
include "include/loadingChangePage.php";
?>
<script type="text/javascript" src="js/jquery.PrintArea.min.js"></script>
<script src="js/remodal/remodal.js"></script>
<!-- Bootstrap core JavaScript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script src="js/bootstrap.min.js"></script>

<script type="text/javascript">
  <?php
    // echo "var inst = $('[data-remodal-id=modal" . $id_img . "]').remodal();";
  ?>

  // window.addEventListener('beforeunload', function(event) {
  //   // Custom logic before redirect
  //   console.log('Page is about to be unloaded');

  //   // // Custom message (mostly ignored by modern browsers)
  //   // var message = 'Are you sure you want to leave this page?';
  //   // event.returnValue = message; // Legacy way of setting message
  //   // return message; // Modern way of setting message
  //   // $(".loading").show();
  //   // event.preventDefault();
  //   test('test');
  //   // event.returnValue = 'test';
  //   // alert('TEST');
  //   // return 'test';
  // });

  // function test(test) {
  //   console.log('test');
  // }

  $(document).ready(function () {
    // BUAT ACCORDION
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {
        var active = $(this).hasClass('active');
        var this_element = this;

        // JIKA accordionnya tidak aktif akan jalanin ajax
        // kenapa tidak aktif, krn disaat diklik, dia baru set class jd aktif
        if (active == false) {
          // INI type buat mastiin .htmlnya nanti diset ke yg mana
          var type = $(this).val();

          var NIK = "<?php echo $NIK; ?>";
          $.ajax({
            type: "GET",
            url: "PD_ajax_profile_getdata.php",
            data: "type=" + type+"&NIK="+NIK,
            cache: false,
            success: function(msg){

              $("#return_ajax_"+type).html(msg);

              // ini fungsi utk buka tutup accordionnya
              // ini pakai variable this_element, krn pake this aja ga bisa dipanggil di return ajaxnya
              this_element.classList.toggle("active");
              var panel = this_element.nextElementSibling;
              if (panel.style.maxHeight) {
                panel.style.maxHeight = null;
              } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
              } 
            }
          });
        }
        else{

          // ini fungsi utk buka tutup accordionnya
          this.classList.toggle("active");
          var panel = this.nextElementSibling;
          if (panel.style.maxHeight) {
            panel.style.maxHeight = null;
          } else {
            panel.style.maxHeight = panel.scrollHeight + "px";
          } 
        }

        
      });
    }

    $("#startDate").datepicker({
      dateFormat: 'yy-mm-dd'
    });
    $("#endDate").datepicker({
      dateFormat: 'yy-mm-dd'
    });
    var value = $('#opernik').val();

    $.ajax({
      url: "object_on_loan.php",
      data: "nik=" + value,
      type: "post",
      success: function (data) {
        $('#on_loan').html(data);
        $("#on_loan").css({ height: 'auto' });
      }
    });
  });

/*$(function() {

  $('#btn_print').click(function() {
    var container = $(this).attr('rel');
    $('#' + container).printArea();
    return false;
  });

});*/

  function printDiv(divID) {
    //Get the HTML of div
    var divElements = document.getElementById(divID).innerHTML;
    //Get the HTML of whole page
    var oldPage = document.body.innerHTML;
    //Reset the page's HTML with div's HTML only
    document.body.innerHTML =
      "<html><head><title></title></head><body>" +
      divElements + "</body>";
    //Print Page
    window.print();
    //Restore orignal HTML
    document.body.innerHTML = oldPage;
    window.location.reload(true);
  }


  $("#link_organisasi").click(function () {
    var value = $('#opernik').val();
    $.ajax({
      url: "anjab_organisasi.php",
      data: "nik=" + value,
      type: "post",
      success: function (data) {
        $('#ajax_organisasi').html(data);
        $("#ajax_organisasi").css({ height: 'auto' });
      }
    });
  });

  $("#link_uraian_pekerjaan").click(function () {
    var value = $('#opernik').val();
    $.ajax({
      url: "anjab_uraian_pekerjaan.php",
      data: "nik=" + value,
      type: "post",
      success: function (data) {
        $('#ajax_uraian_pekerjaan').html(data);
        $("#ajax_uraian_pekerjaan").css({ height: 'auto' });
      }
    });
  });

  $("#link_uraian_posisi").click(function () {
    var value = $('#opernik').val();
    $.ajax({
      url: "anjab_uraian_posisi.php",
      data: "nik=" + value,
      type: "post",
      success: function (data) {
        $('#ajax_uraian_posisi').html(data);
        $("#ajax_uraian_posisi").css({ height: 'auto' });
      }
    });
  });

  $("#link_kewenangan").click(function () {
    var value = $('#opernik').val();
    $.ajax({
      url: "anjab_kewenangan.php",
      data: "nik=" + value,
      type: "post",
      success: function (data) {
        $('#ajax_kewenangan').html(data);
        $("#ajax_kewenangan").css({ height: 'auto' });
      }
    });
  });

  $("#link_hubungan_internal").click(function () {
    var value = $('#opernik').val();
    $.ajax({
      url: "anjab_hubungan_internal.php",
      data: "nik=" + value,
      type: "post",
      success: function (data) {
        $('#ajax_hubungan_internal').html(data);
        $("#ajax_hubungan_internal").css({ height: 'auto' });
      }
    });
  });

  $("#link_hubungan_eksternal").click(function () {
    var value = $('#opernik').val();
    $.ajax({
      url: "anjab_hubungan_eksternal.php",
      data: "nik=" + value,
      type: "post",
      success: function (data) {
        $('#ajax_hubungan_eksternal').html(data);
        $("#ajax_hubungan_eksternal").css({ height: 'auto' });
      }
    });
  });

  $("#link_dimensi").click(function () {
    var value = $('#opernik').val();
    $.ajax({
      url: "anjab_dimensi.php",
      data: "nik=" + value,
      type: "post",
      success: function (data) {
        $('#ajax_dimensi').html(data);
        $("#ajax_dimensi").css({ height: 'auto' });
      }
    });
  });

  $("#link_kualifikasi").click(function () {
    var value = $('#opernik').val();
    $.ajax({
      url: "anjab_kualifikasi.php",
      data: "nik=" + value,
      type: "post",
      success: function (data) {
        $('#ajax_kualifikasi').html(data);
        $("#ajax_kualifikasi").css({ height: 'auto' });
      }
    });
  });

  $("#link_kpi").click(function () {
    var value = $('#opernik').val();
    $.ajax({
      url: "anjab_kpi.php",
      data: "nik=" + value,
      type: "post",
      success: function (data) {
        $('#ajax_kpi').html(data);
        $("#ajax_kpi").css({ height: 'auto' });
      }
    });
  });

  $("#tab_3").click(function () {
    var value = $('#opernik').val();

    $.ajax({
      url: "object_on_loan.php",
      data: "nik=" + value,
      type: "post",
      success: function (data) {
        $('#on_loan').html(data);
        $("#on_loan").css({ height: 'auto' });
      }
    });
  });

</script>
