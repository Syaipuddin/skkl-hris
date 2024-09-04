<?php
    include_once "include/check_Flag.php";
    include "ESS/profile.php";

    class NPWPEdit {
        
        public $flag = 0;
        public $conn = "";
        public $rfc = "";
        private $libraryInstance;

        public function __construct() {
            include "include/connection.php";
            include "include/sapconnect.php";
            include_once "include/check_Flag.php";
            include_once 'ESS/library.php';
            $this->conn = $conn;
            $this->rfc = $rfc;
            $this->libraryInstance = new ESSProfileLibrary();
            $this->essInstance = new ESSlibrary();
        }

        public function getNPWP($NIK) {
            $this->flag=check_access_odoo($NIK);
            $flag = $this->flag;

            if ($flag == 1) {
                $npwp = json_decode($this->getNPWPOdoo($NIK));
                $profile = json_decode($this->libraryInstance->getOdooEmployeeProfile($NIK, 'all'));
                
                $FI_CV = array(
                    'STATUSPERKAWINAN' => $profile->marital,
                    'JENISKELAMIN' => $profile->gender,
                    'NAMALENGKAP'=> $profile->name
                );

                $data = array(
                    'FI_CV' => $FI_CV,
                    'FE_ID' => $profile->identification_id,
                    'FE_TAXNAME' => $npwp->spouse_name,
                    'FE_TAXID' => $npwp->spouse_tax_id
                );

            } else {
                $data = $this->getNPWPSAP($NIK);
            }

            return $data;
        }

        public function setNPWP($NIK) {
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                var_dump($NIK);
                echo "-----------------------TRIGGERED------------------------";
            }
        }
        
        public function getNPWPSAP($NIK) {
            $ms_niktelp = odbc_prepare($this->conn, "SELECT * FROM ms_niktelp WHERE NIK = ?");
            odbc_execute($ms_niktelp, array($NIK));
            $nama = odbc_result($ms_niktelp, "Nama");
      
      
            $fce = saprfc_function_discover($this->rfc,"ZHRFM_GET0185");
            if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
            saprfc_import ($fce,"FI_KEYDATE",date("Ymd"));
            saprfc_import ($fce,"FI_PERNR",$NIK);
            saprfc_import ($fce,"FI_SUBTYPE","01");
    
            $rfc_rc = saprfc_call_and_receive ($fce);
            if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
    
            $FE_ID = saprfc_export ($fce,"FE_ID");
            $FE_TAXID = saprfc_export ($fce, "FE_TAXID");
            $FE_TAXNAME = saprfc_export ($fce, "FE_TAXNAME");
    
            // GET FEMALE AND NIKAH
                $fce2 = saprfc_function_discover($this->rfc,"ZHRFM_CV");
            if (! $fce2 ) { echo "Discovering interface of function module failed"; exit; }
            saprfc_import ($fce2,"FI_PERNR",$NIK);
            saprfc_import ($fce2,"FI_PERNR_DIAKSES",$NIK);
            saprfc_table_init ($fce2,"FI_CV");
            $rfc_rc = saprfc_call_and_receive ($fce2);
            if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce2)); else echo (saprfc_error($fce2)); exit; }
            $rows = saprfc_table_rows ($fce2,"FI_CV");
    
            if($rows)
                {
                    $FI_CV = saprfc_table_read ($fce2,"FI_CV",1);
                }

            $data = array(
                'FI_CV' => $FI_CV,
                'FE_ID' => $FE_ID,
                'FE_TAXID' => $FE_TAXID,
                'FE_TAXNAME' => $FE_TAXNAME
            );

            return $data;
        }

        public function getNPWPOdoo($NIK) {
            $ch = curl_init();
            
            if (isset($_SESSION['token_odoo'])) {
                $token = $_SESSION['token_odoo'];
            }
            else {
                $token = $this->essInstance->getOdooToken('admin', 'admin');
            }

            $ch = curl_init();

            $authorization = "Authorization: Bearer $token";

            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    $authorization,
                    'Content-type: application/json'
                )
            );
            
            $param = array(
                "nik" => $NIK
            );

            $postdata = json_encode($param);

            $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/get-pemadanan-npwp";

            // set the url, number o POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_POST, count($postdata));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            ob_start();

            curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $result = ob_get_clean();
            

            // close conn
            curl_close($ch);

            if($httpcode == 401) 
            {
                $token = $this->essInstance->getOdooToken('admin', 'admin');
                $this->getNPWPSAP($NIK, $profile_type);
            } else if ($httpcode == 200)
            {
                $json_decode = json_decode($result, true);
                return $result;
            } else {
                return $result;
            }   
        }

        public function setNPWPSAP($NIK, $rfc, $link) {

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
                    // array('FIELDNAME' => 'TAXID', 'FIELDCONTENT' => $new_npwp),
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
                var_dump($today_Ymd);
                var_dump($input_table);

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

                var_dump($today_Ymd);
                var_dump($input_table);

                foreach ($input_table as $input_row) {
                    saprfc_table_append ($fce,"FT_DATA", $input_row);
                }

                saprfc_table_init ($fce,"MESSTAB");

                // $rfc_rc = saprfc_call_and_receive ($fce);
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

                var_dump(odbc_errormsg($conn));

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
            
                // $insert_err_log = odbc_prepare($conn, "INSERT INTO tb_npwp_error_log (NIK, old_ktp, new_ktp, old_npwp, new_npwp,subrc_ktp,subrc_npwp, error_ktp, error_npwp) VALUES (?,?,?,?,?,?,?,?,?)");
                // odbc_execute($insert_err_log, array($NIK, $old_ktp, $new_ktp, $old_npwp, $new_npwp, $SUBRC_ktp, $SUBRC_npwp, $MESSTAB_ktp[0]['MSGV1'], $MESSTAB_npwp[0]['MSGV1']));
                }

                header('Refresh: 5; URL='.$link.'/home.php');
            }
        }

        public function setSpouseTaxAndIdentificationOdoo($NIK, $link) {
            
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

                if ($new_npwp) {
                    if (!$nama_pasangan) {
                        $nama_pasangan = $nama_kary;
                    }
                } 
            }

            if ($sama < 1 && $new_ktp && $status_npwp == 'spouse' && $SUBRC_npwp == 0) {
                $param = array(
                    "nik" => $NIK,
                    'identification_id' => $new_ktp
                );

                $endpoint = "edit-identification-id";

             } else if($status_npwp == 'self' && $old_ktp != $new_npwp && $SUBRC_npwp == 0){
                $param = array(
                    "nik" => $NIK,
                    'spouse_name' => $nama_pasangan,
                    "spouse_tax_id" => $new_ktp
                );

                $endpoint = "edit-spouse-tax-id";

             } else {
                $param = array(
                    "nik" => "",
                    "idenfitication_id"=> ""
                );

                $endpoint = "edit-identification-id";
                
             }

            $ch = curl_init();
            
            if (isset($_SESSION['token_odoo'])) {
                $token = $_SESSION['token_odoo'];
            }
            else {
                $token = $this->essInstance->getOdooToken('admin', 'admin');
            }

            $ch = curl_init();

            $authorization = "Authorization: Bearer $token";

            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    $authorization,
                    'Content-type: application/json'
                )
            );

            $postdata = json_encode($param);

            $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/".$endpoint;

            // set the url, number o POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_POST, count($postdata));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            ob_start();

            curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $result = ob_get_clean();
            

            // close conn
            curl_close($ch);

            if($httpcode == 401) 
            {
                echo "error";
                $token = $this->essInstance->getOdooToken('admin', 'admin');
                $this->getOdooEmployeeProfile($NIK, $profile_type);
            } else if ($httpcode == 200) {
                return json_decode($result, true);
            } else {
                return $result;
            }
            
            header('Refresh: 5; URL='.$link.'/home.php');

        }

    }
?>