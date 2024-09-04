<?php
  include 'template/top5.php';
  include "include/ldap_function.php";
  	$path=parse_url($_SERVER['HTTP_REFERER']);

  	if($path["host"]=="hr.kompasgramedia.com" or $path["host"]=="hr2.kompasgramedia.com" or $path["host"]=="mykg.id")
  	{
  	    $link='https://hr.kompasgramedia.com';
  	}    
  	else if($path["host"]=="hr.kompasgramedia.co.id")
  	{
  	    $link='http://'.$path["host"];
  	}
  	else if($path["host"]=="10.9.70.30")
  	{
  	    $link='http://'.$path["host"];
  	}
  	else{
  	    $link='https://hr.kompasgramedia.com';
  	}

          $fce = saprfc_function_discover($rfc,"ZHRFM_GET0241");
          if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
          saprfc_import ($fce,"FI_KEYDATE",date("Ymd"));
          saprfc_import ($fce,"FI_PERNR",$NIK);

          $rfc_rc = saprfc_call_and_receive ($fce);
          if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }

          $FE_TAX = saprfc_export ($fce, "FE_TAX");

          $old_npwp = $FE_TAX;

      $old_ktp = $_POST['old_ktp'];
      $new_npwp = $_POST['npwp'];
      $nama_pasangan = $_POST['nama_pasangan'];
      $new_ktp = $_POST['self_ktp'];
      $status_npwp = $_POST['status_npwp'];
      $nama_kary = $_POST['nama'];

      $sama=0;
      $update_gagal=0;

      if ($new_ktp == $old_ktp) {
        $sama++;
      }

      if (!preg_match('/^(?!((\d)\2{5,})\d+$)\d+$/', $new_npwp) && !preg_match('/^(?!((\d)\2{5,})\d+$)\d+$/', $new_ktp)) {
        echo "<center><label class='alert alert-error'><h2 style='color:red;'>Submit data failed!</h2>NPWP or No. KTP <b>CANNOT</b> be filled with <b>9999999999999999</b> or any <b>IDENTICAL NUMBERS</b><br>Please re-submit your data <b>correctly!</b></label></center>";

        header('Refresh: 5; URL='.$link.'/home.php');
      }
      elseif (!preg_match('/^(?!1234567890123456$|1234567890987654$|0123456789012345$|0123456789098765$|0123456789123456$|0123456789987654$|9876543210123456|9876543210987654|0987654321234567|0987654321012345)\d{16}$/', $new_npwp) && !preg_match('/^(?!1234567890123456$|1234567890987654$|0123456789012345$|0123456789098765$|0123456789123456$|0123456789987654$|9876543210123456|9876543210987654|0987654321234567|0987654321012345)\d{16}$/', $new_ktp)) {
        echo "<center><label class='alert alert-error'><h2 style='color:red;'>Submit data failed!</h2>NPWP or No. KTP <b>CANNOT</b> be filled with <b>1234567890123456</b> or any <b>INVALID FORMAT</b><br>Please re-submit your data <b>correctly!</b></label></center>";

        header('Refresh: 5; URL='.$link.'/home.php');
      }
      elseif(strlen($new_npwp)!=16){
        echo "<center><label class='alert alert-error'><h2 style='color:red;'>Submit data failed!</h2>NPWP must be 16 characters! Please use your 16 digits NPWP numbers!</label></center>";

        header('Refresh: 5; URL='.$link.'/home.php');
      }
      else{
        $today_date = date('d');
        $today_month = date('m');
        $today_year = date('Y');

          if (date('m') <= '11' && date('Y') == '2023') {
            $today = date("0111Y");
            $today_Ymd = date("Y1101");
          }
          else{
            $today = date("01mY");
            $today_Ymd = date("Ym01");
          }
        if ($new_npwp) {
          if (!$nama_pasangan) {
            $nama_pasangan = $nama_kary;
          }

          $fce = saprfc_function_discover($rfc,"ZHRFM_CHANGE0241");
          if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
          saprfc_import ($fce,"FI_AKSI",2);
          saprfc_import ($fce,"FI_PERNR",$NIK);
          saprfc_import ($fce,"GROUP","");
          saprfc_import ($fce,"HOLDDATE","");
          saprfc_import ($fce,"KEEP","");
          saprfc_import ($fce,"USER","");
          saprfc_import ($fce,"FI_MODE","N");

          saprfc_table_init ($fce,"FI_INPUT");

          // jadinya npwp 15 digit yg lama ga perlu diupdate dulu
          $input_table =  array(
            array('FIELDNAME' => 'BEGDA', 'FIELDCONTENT' => $today),
            array('FIELDNAME' => 'ZTAXID', 'FIELDCONTENT' => $new_npwp),
            array('FIELDNAME' => 'ZTAXNAME', 'FIELDCONTENT' => $nama_pasangan)
          );

          // jadinya npwp 15 digit yg lama ga perlu diupdate dulu
          // kecuali jika npwp 15 digit yg lamanya kosong
          if (!$old_npwp) {
            array_push($input_table, array('FIELDNAME' => 'TAXID', 'FIELDCONTENT' => $new_npwp));
          }

          foreach ($input_table as $input_row) {
            saprfc_table_append ($fce,"FI_INPUT", $input_row);
          }

          saprfc_table_init ($fce,"MESSTAB");

          $rfc_rc = saprfc_call_and_receive ($fce);
          if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }

          $SUBRC_npwp = saprfc_export ($fce,"SUBRC");

          $rows = saprfc_table_rows ($fce,"MESSTAB");

          for ($i=1;$i<=$rows;$i++)
            $MESSTAB_npwp[] = saprfc_table_read ($fce,"MESSTAB",$i);

        }
        else{
          $SUBRC_npwp = 0;
        }

        if ($sama < 1 && $new_ktp && $status_npwp == 'spouse' && $SUBRC_npwp == 0) {
          // ini buat ganti no ktp klo npwp yg dipilih statusnya milik pasangan, dan KTP yg baru diisi berbeda dgn sebelumnya
          $fce = saprfc_function_discover($rfc,"ZHRFM_UPDATENONGUI");
          if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
          saprfc_import ($fce,"FI_AKSI",0);
          saprfc_import ($fce,"FI_PERNR",$NIK);
          saprfc_import ($fce,"FI_BEGDA",$today_Ymd);
          saprfc_import ($fce,"FI_ENDDA",$today_Ymd);
          saprfc_import ($fce,"FI_INFOTYPE",'0185');
          saprfc_import ($fce,"FI_SCREEN",'2000');
          saprfc_import ($fce,"FI_SUBTYPE",'01');
          saprfc_import ($fce,"GROUP","");
          saprfc_import ($fce,"HOLDDATE","");
          saprfc_import ($fce,"KEEP","");
          saprfc_import ($fce,"MODE","N");
          saprfc_import ($fce,"USER","");

          saprfc_table_init ($fce,"FT_DATA");

        $input_table =  array(
          array('FIELDNAME' => 'P0185-BEGDA', 'FIELDCONTENT' => $today),
          array('FIELDNAME' => 'P0185-ICNUM', 'FIELDCONTENT' => $new_ktp),
        );

          foreach ($input_table as $input_row) {
            saprfc_table_append ($fce,"FT_DATA", $input_row);
          }

          saprfc_table_init ($fce,"MESSTAB");

          $rfc_rc = saprfc_call_and_receive ($fce);
          if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }

          $SUBRC_ktp = saprfc_export ($fce,"SUBRC");

          $rows = saprfc_table_rows ($fce,"MESSTAB");

          for ($i=1;$i<=$rows;$i++)
            $MESSTAB_ktp[] = saprfc_table_read ($fce,"MESSTAB",$i);
        }
        else if($status_npwp == 'self' && $old_ktp != $new_npwp && $SUBRC_npwp == 0){
          // ini buat ganti no ktp klo npwp yg dipilih statusnya milik sendiri, dan berbeda dgn ktp sblumnya

          $fce = saprfc_function_discover($rfc,"ZHRFM_UPDATENONGUI");
          if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
          saprfc_import ($fce,"FI_AKSI",0);
          saprfc_import ($fce,"FI_PERNR",$NIK);
          saprfc_import ($fce,"FI_BEGDA",$today_Ymd);
          saprfc_import ($fce,"FI_ENDDA",$today_Ymd);
          saprfc_import ($fce,"FI_INFOTYPE",'0185');
          saprfc_import ($fce,"FI_SCREEN",'2000');
          saprfc_import ($fce,"FI_SUBTYPE",'01');
          saprfc_import ($fce,"GROUP","");
          saprfc_import ($fce,"HOLDDATE","");
          saprfc_import ($fce,"KEEP","");
          saprfc_import ($fce,"MODE","N");
          saprfc_import ($fce,"USER","");

          saprfc_table_init ($fce,"FT_DATA");

          $input_table =  array(
            array('FIELDNAME' => 'P0185-BEGDA', 'FIELDCONTENT' => $today),
            array('FIELDNAME' => 'P0185-ICNUM', 'FIELDCONTENT' => $new_npwp),
          );

        foreach ($input_table as $input_row) {
          saprfc_table_append ($fce,"FT_DATA", $input_row);
        }

          saprfc_table_init ($fce,"MESSTAB");

          $rfc_rc = saprfc_call_and_receive ($fce);
          if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }

          $SUBRC_ktp = saprfc_export ($fce,"SUBRC");

          $rows = saprfc_table_rows ($fce,"MESSTAB");

          for ($i=1;$i<=$rows;$i++)
            $MESSTAB_ktp[] = saprfc_table_read ($fce,"MESSTAB",$i);
        }
        else{
          $SUBRC_ktp = 0;
        }

        echo "<center><label class='alert alert-success'>Your data has been updated<br><h2>Thank You</h2></label></center>";

        $insert_log = odbc_prepare($conn, "INSERT INTO tb_npwp_log (NIK, old_ktp, new_ktp, old_npwp, new_npwp, name_npwp, subrc_ktp,subrc_npwp, error_ktp, error_npwp, created_date) VALUES (?,?,?,?,?,?,?,?,?,?,GETDATE())");

        odbc_execute($insert_log, array($NIK, $old_ktp, $new_ktp, $old_npwp, $new_npwp, $nama_pasangan, $SUBRC_ktp, $SUBRC_npwp, $MESSTAB_ktp[0]['MSGV1'], $MESSTAB_npwp[0]['MSGV1']));

        if ($SUBRC_ktp == 0 && $SUBRC_npwp == 0) {
          $update_flag = odbc_exec($conn, "UPDATE tr_login SET flagNpwp = 1 WHERE userLogin = '$NIK'");
        }
        else{
          if ($SUBRC_ktp == 1001) {
            $update_flag = odbc_exec($conn, "UPDATE tr_login SET flagNpwp = 2 WHERE userLogin = '$NIK'");
          }
          else if ($SUBRC_npwp == 1001) {
            $update_flag = odbc_exec($conn, "UPDATE tr_login SET flagNpwp = 3 WHERE userLogin = '$NIK'");
          }
          else{
            $update_flag = odbc_exec($conn, "UPDATE tr_login SET flagNpwp = 4 WHERE userLogin = '$NIK'");
          }
        }

        header('Refresh: 5; URL='.$link.'/home.php');
      }

  include 'template/bottom5.php';
?>