<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Medicallibrary
{
    public $conn="";
    public $rfc="";
    public $NIK="";
    public function __construct(){
        include "include/connection.php";
        include "include/sapconnect.php";
        include_once "include/check_Flag.php";
        $this->conn = $conn;
        $this->rfc = $rfc;
    }

    /*--------------- GET ODOO TOKEN ---------------------*/
    public function getOdooToken($user=NULL,$password=NULL)
    {
        // get TOKEN ODOOO

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-type: application/json'
        ));

        $postdata = array(
            'login' => $user,
            "password"=> $password,
        );

        $postdata = json_encode($postdata);

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, "https://kg-pms-odoo-dev1.mykg.id/kg/api/auth/user/token");
        curl_setopt($ch,CURLOPT_POST, count($postdata));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);

        //execute post
        ob_start();
        curl_exec($ch);

        $result = ob_get_clean();

         //close connection
        curl_close($ch);

        $json_decode = json_decode($result);
        $token = $json_decode->access_token;
        $_SESSION['token_odoo'] = $token;

        return $token;
    }
    /*--------------- GET ODOO TOKEN ---------------------*/


    /*--------------- CHECK 171 -------------------*/
    public function getCek171($data)
    {

        $this->NIK = $data["NIK"];
        $flag_odoo = check_access_odoo($data["NIK"]);

        if ($flag_odoo == 1) {
            $data = $this->getCek171Odoo($data);
        } else {
            $data = $this->getCek171SAP($data);
        }

        return $data;
    }

    public function getIndexCek171($flag_odoo){
        if ($flag_odoo) {
            $index_name['BENGR'] = 'medical_man_group';
        }
        else{
            $index_name['BENGR'] = 'BENGR';
        }
        return $index_name;
    }

    public function getCek171Odoo($data){
        // $token = $this->getOdooToken('admin', 'admin');
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        }
        else{
            $token = $this->getOdooToken('admin', 'admin');
        }

        $ch = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization
        ));

        $param = array(
                        "nik"=>$data['NIK'],
                    );

        $param = http_build_query($param);

        $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/medical-group-employee?".$param;

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);

        //execute post
        ob_start();
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = ob_get_clean();

        //close connection
        curl_close($ch);

        if ($httpcode == 401) {
            $token = $this->getOdooToken('admin', 'admin');
            $this->getCek171Odoo($data);
        }
        else if ($httpcode == 200) {

            $json_decode = json_decode($result, true);

            // var_dump('result: ',$json_decode['data'][0]['parent_id'][1]);
            // flag odoo = 1
            $index_name = $this->getIndexCek171(1);
            $json_decode['index_name'] = $index_name;
            $json_decode['rows'] = 1;
            return $json_decode;
        }
        else{
            return False;
        }
    }

    public function getCek171SAP(){
        $NIK = $this->NIK;
        $rfc = $this->rfc;

        $fce_bengr = saprfc_function_discover($rfc,"ZHRFM_CEK_171");
        if (! $fce_bengr ) { echo "Discovering interface of function module failed"; exit; }
        saprfc_import ($fce_bengr,"FI_PERNR",$NIK);
        //Fill internal tables
        saprfc_table_init ($fce_bengr,"FI_171");
        //Do RFC call of function ZHRFM_MR_PATIENT, for handling exceptions use saprfc_exception()
        $rfc_rc = saprfc_call_and_receive ($fce_bengr);
        if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce_bengr)); else echo (saprfc_error($fce_bengr)); exit; }
        //Retrieve export parameters
        $rows_bengr = saprfc_table_rows ($fce_bengr,"FI_171");

        // flag odoo = 0
        $index_name = $this->getIndexCek171(0);

        return array("rows"=>$rows_bengr, "fce"=>$fce_bengr, "index_name"=>$index_name);
        
    }
    /*------------------ Check 171 -------------------------*/


    /*------------------ GET PAGU_MEDICAL ---------------------*/
    public function getPaguMedical($data){
        $this->NIK = $data['NIK'];
        $flag_odoo = check_access_odoo($data['NIK']);

        // var_dump("data:",$data);

        if ($flag_odoo == 1) {
            $data = $this->getPaguMedicalOdoo($data);
        }
        else{
            $data = $this->getPaguMedicalSAP($data);
        }

        return $data;
    }

    public function getIndexNamePaguMedical($flag_odoo){
        if ($flag_odoo) {
            $index_name['TANGGAL'] = 'all_date';
            $index_name['ABSEN'] = 'working_time';
            $index_name['HARI'] = 'days';
            $index_name['JAMMASUK'] = 'checkin_emp';
            $index_name['NOTERMINALMASUK'] = 'location_in_emp';
            $index_name['JAMPULANG'] = 'checkout_emp';
            $index_name['NOTERMINALPULANG'] = 'location_out_emp';
            $index_name['ALASAN'] = 'reason_emp';
        }
        else{
            $index_name['TANGGAL'] = 'TANGGAL';
            $index_name['ABSEN'] = 'ABSEN';
            $index_name['HARI'] = 'HARI';
            $index_name['JAMMASUK'] = 'JAMMASUK';
            $index_name['NOTERMINALMASUK'] = 'NOTERMINALMASUK';
            $index_name['JAMPULANG'] = 'JAMPULANG';
            $index_name['NOTERMINALPULANG'] = 'NOTERMINALPULANG';
            $index_name['ALASAN'] = 'ALASAN';
        }
        return $index_name;
    }


    public function getPaguMedicalOdoo($data){
        // $token = $this->getOdooToken('admin', 'admin');
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        }
        else{
            $token = $this->getOdooToken('admin', 'admin');
        }

        $ch = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization
        ));

        $param = array(
                        "nik"=>$data['NIK'],
                        "start_date"=>$data["start_date"],
                        "end_date"=>$data["end_date"]
                    );

        $param = http_build_query($param);

        $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/medical-limit-employee?".$param;

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);

        //execute post
        ob_start();
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = ob_get_clean();

        //close connection
        curl_close($ch);

        if ($httpcode == 401) {
            $token = $this->getOdooToken('admin', 'admin');
            $this->getPaguMedicalOdoo($data);
        }
        else if ($httpcode == 200) {

            $json_decode = json_decode($result, true);
            // paksa ini hanya ambil 1 data aja, karena pasti startdate enddate isinya 1 thn yg sama aja
            $json_decode = $json_decode['data'][0];
            $index_name = $this->getIndexNamePaguMedical(1);
            $json_decode['index_name'] = $index_name;
            // $json_decode['rows'] = count($json_decode['data']);

            $json_decode["FI_PAGU"] = "";
            // var_dump($json_decode);

            return $json_decode;
        }
        else{
            return False;
        }
        
    }


    public function getPaguMedicalSAP($data){
        $NIK = $this->NIK;
        $rfc = $this->rfc;

        //GET CHIEF
        $fce_plafon_med = saprfc_function_discover($rfc,"ZHRFM_PAGU_MEDICAL");
        if (! $fce_plafon_med ) { echo "Discovering interface of function module failed"; exit; }
        saprfc_import ($fce_plafon_med,"FI_PERNR",$data["NIK"]);
        saprfc_import ($fce_plafon_med,"FI_TGL_AWAL",$data["start_date"]);
        saprfc_import ($fce_plafon_med,"FI_TGL_AKHIR",$data["end_date"]);
        $rfc_rc = saprfc_call_and_receive ($fce_plafon_med);
        if ($rfc_rc != SAPRFC_OK)
        { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce_plafon_med)); else echo (saprfc_error($fce_plafon_med)); exit; }

        $FI_PAGU_RAWAT_JALAN = saprfc_export ($fce_plafon_med,"FI_PAGU_RAWAT_JALAN");
        $FI_USED_RAWAT_JALAN = saprfc_export ($fce_plafon_med,"FI_USED_RAWAT_JALAN");

        $FI_PAGU = saprfc_export ($fce_plafon_med,"FI_PAGU");

        $FI_PAGU_LENSA = saprfc_export ($fce_plafon_med,"FI_PAGU_LENSA");
        $FI_USED_PAGU_LENSA = saprfc_export ($fce_plafon_med,"FI_USED_PAGU_LENSA");

        $FI_PAGU_BINGKAI = saprfc_export ($fce_plafon_med,"FI_PAGU_BINGKAI");
        $FI_USED_PAGU_BINGKAI = saprfc_export ($fce_plafon_med,"FI_USED_PAGU_BINGKAI");

        $FI_PAGU_LENSA_PASANGAN = saprfc_export ($fce_plafon_med,"FI_PAGU_LENSA_PASANGAN");
        $FI_USED_PAGU_LENSA_PASANGAN = saprfc_export ($fce_plafon_med,"FI_USED_PAGU_LENSA_PASANGAN");

        $FI_PAGU_BINGKAI_PASANGAN = saprfc_export ($fce_plafon_med,"FI_PAGU_BINGKAI_PASANGAN");
        $FI_USED_PAGU_BINGKAI_PASANGAN = saprfc_export ($fce_plafon_med,"FI_USED_PAGU_BINGKAI_PASANGAN");

        $FI_PAGU_LENSA_ANAK1 = saprfc_export ($fce_plafon_med,"FI_PAGU_LENSA_ANAK1");
        $FI_USED_PAGU_LENSA_ANAK1 = saprfc_export ($fce_plafon_med,"FI_USED_PAGU_LENSA_ANAK1");

        $FI_PAGU_BINGKAI_ANAK1 = saprfc_export ($fce_plafon_med,"FI_PAGU_BINGKAI_ANAK1");
        $FI_USED_PAGU_BINGKAI_ANAK1 = saprfc_export ($fce_plafon_med,"FI_USED_PAGU_BINGKAI_ANAK1");

        $FI_PAGU_LENSA_ANAK2 = saprfc_export ($fce_plafon_med,"FI_PAGU_LENSA_ANAK2");
        $FI_USED_PAGU_LENSA_ANAK2 = saprfc_export ($fce_plafon_med,"FI_USED_PAGU_LENSA_ANAK2");

        $FI_PAGU_BINGKAI_ANAK2 = saprfc_export ($fce_plafon_med,"FI_PAGU_BINGKAI_ANAK2");
        $FI_USED_PAGU_BINGKAI_ANAK2 = saprfc_export ($fce_plafon_med,"FI_USED_PAGU_BINGKAI_ANAK2");

        $FI_PAGU_LENSA_ANAK3 = saprfc_export ($fce_plafon_med,"FI_PAGU_LENSA_ANAK3");
        $FI_USED_PAGU_LENSA_ANAK3 = saprfc_export ($fce_plafon_med,"FI_USED_PAGU_LENSA_ANAK3");

        $FI_PAGU_BINGKAI_ANAK3 = saprfc_export ($fce_plafon_med,"FI_PAGU_BINGKAI_ANAK3");
        $FI_USED_PAGU_BINGKAI_ANAK3 = saprfc_export ($fce_plafon_med,"FI_USED_PAGU_BINGKAI_ANAK3");

        $return = array(
            "FI_PAGU_RAWAT_JALAN" => $FI_PAGU_RAWAT_JALAN,
            "FI_USED_RAWAT_JALAN" => $FI_USED_RAWAT_JALAN,

            "FI_PAGU"=>$FI_PAGU,

            "FI_PAGU_LENSA"=>$FI_PAGU_LENSA,
            "FI_USED_PAGU_LENSA"=>$FI_USED_PAGU_LENSA,

            "FI_PAGU_BINGKAI"=>$FI_PAGU_BINGKAI,
            "FI_USED_PAGU_BINGKAI"=>$FI_USED_PAGU_BINGKAI,

            "FI_PAGU_LENSA_PASANGAN"=>$FI_PAGU_LENSA_PASANGAN,
            "FI_USED_PAGU_LENSA_PASANGAN"=>$FI_USED_PAGU_LENSA_PASANGAN,

            "FI_PAGU_BINGKAI_PASANGAN"=>$FI_PAGU_BINGKAI_PASANGAN,
            "FI_USED_PAGU_BINGKAI_PASANGAN"=>$FI_USED_PAGU_BINGKAI_PASANGAN,

            "FI_PAGU_LENSA_ANAK1"=>$FI_PAGU_LENSA_ANAK1,
            "FI_USED_PAGU_LENSA_ANAK1"=>$FI_USED_PAGU_LENSA_ANAK1,

            "FI_PAGU_BINGKAI_ANAK1"=>$FI_PAGU_BINGKAI_ANAK1,
            "FI_USED_PAGU_BINGKAI_ANAK1"=>$FI_USED_PAGU_BINGKAI_ANAK1,

            "FI_PAGU_LENSA_ANAK2"=>$FI_PAGU_LENSA_ANAK2,
            "FI_USED_PAGU_LENSA_ANAK2"=>$FI_USED_PAGU_LENSA_ANAK2,

            "FI_PAGU_BINGKAI_ANAK2"=>$FI_PAGU_BINGKAI_ANAK2,
            "FI_USED_PAGU_BINGKAI_ANAK2"=>$FI_USED_PAGU_BINGKAI_ANAK2,

            "FI_PAGU_LENSA_ANAK3"=>$FI_PAGU_LENSA_ANAK3,
            "FI_USED_PAGU_LENSA_ANAK3"=>$FI_USED_PAGU_LENSA_ANAK3,

            "FI_PAGU_BINGKAI_ANAK3"=>$FI_PAGU_BINGKAI_ANAK3,
            "FI_USED_PAGU_BINGKAI_ANAK3"=>$FI_USED_PAGU_BINGKAI_ANAK3
        );

        saprfc_function_free($fce_plafon_med);

        return $return;
    }
    /*------------------ GET PAGU MEDICAL ---------------------*/


    /*------------------ GET PAGU FRAME ---------------------*/
    public function getPaguFrame($data){
        $this->NIK = $data['NIK'];
        $flag_odoo = check_access_odoo($data['NIK']);

        // var_dump("data:",$data);

        if ($flag_odoo == 1) {
            $data = $this->getPaguFrameOdoo($data);
        }
        else{
            $data = $this->getPaguFrameSAP($data);
        }

        return $data;
    }

    public function getIndexNamePaguFrame($flag_odoo){
        if ($flag_odoo) {
            $index_name['PERNR'] = 'PERNR';
            $index_name['CLAMT'] = 'CLAMT';
            $index_name['SUBTY'] = 'SUBTY';
            $index_name['CHLNR'] = 'CHLNR';
        }
        else{
            $index_name['PERNR'] = 'PERNR';
            $index_name['CLAMT'] = 'CLAMT';
            $index_name['SUBTY'] = 'SUBTY';
            $index_name['CHLNR'] = 'CHLNR';
        }
        return $index_name;
    }


    public function getPaguFrameOdoo($data){
        // $token = $this->getOdooToken('admin', 'admin');
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        }
        else{
            $token = $this->getOdooToken('admin', 'admin');
        }

        $ch = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization
        ));

        $param = array(
                        "nik"=>$data['NIK']
                    );

        $param = http_build_query($param);

        $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/medical-transaction-frame?".$param;

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);

        //execute post
        ob_start();
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = ob_get_clean();

        //close connection
        curl_close($ch);

        if ($httpcode == 401) {
            $token = $this->getOdooToken('admin', 'admin');
            $this->getPaguFrameOdoo($data);
        }
        else if ($httpcode == 200) {

            $json_decode = json_decode($result, true);
            $index_name = $this->getIndexNamePaguFrame(1);
            $json_decode['index_name'] = $index_name;
            $json_decode['rows'] = count($json_decode['data']);
            for ($i=0; $i < $json_decode['rows']; $i++) {
                $json_decode["data"][$i]['PERNR'] = $data['NIK'];
            }
            
            // var_dump($json_decode);

            return $json_decode;
        }
        else{
            return False;
        }
        
    }


    public function getPaguFrameSAP($data){
        $NIK = $this->NIK;
        $rfc = $this->rfc;

        //Get pengajuan frame 2 tahun terakhir karyawan
        $fce_frame = saprfc_function_discover($rfc,"ZHRFM_PAGU_FRAME");
        if (! $fce_frame ) { echo "Discovering interface of function module failed"; exit; }
        saprfc_import ($fce_frame,"FI_PERNR",$data['NIK']);
        //Fill internal tables
        saprfc_table_init ($fce_frame,"FI_T7XAST");
        //Do RFC call of function ZHRFM_MR_PATIENT, for handling exceptions use saprfc_exception()
        $rfc_rc = saprfc_call_and_receive ($fce_frame);
        if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce_frame)); else echo (saprfc_error($fce_frame)); exit; }

        //Retrieve export parameters
        $row_frame = saprfc_table_rows($fce_frame,'FI_T7XAST');

        $index_name = $this->getIndexNamePaguFrame(0);

        $return = array(
            "fce"=>$fce_frame,
            "rows" => $row_frame,
            "index_name"=>$index_name
        );

        return $return;
    }
    /*------------------ GET PAGU FRAME ---------------------*/


    /*------------------ GET ZHRFM_CEK_PENDINGREM ---------------------*/
    public function getPendingRem($data){
        $this->NIK = $data['NIK'];
        $flag_odoo = check_access_odoo($data['NIK']);

        // var_dump("data:",$data);

        if ($flag_odoo == 1) {
            $data = $this->getPendingRemOdoo($data);
        }
        else{
            $data = $this->getPendingRemSAP($data);
        }

        return $data;
    }

    public function getIndexNamePendingRem($flag_odoo){
        if ($flag_odoo) {
            $index_name['CLMDT'] = 'claim_date';
            $index_name['BPLAN'] = 'bplan';
            $index_name['ERAMT'] = 'employeer_amount';
        }
        else{
            $index_name['CLMDT'] = 'CLMDT';
            $index_name['BPLAN'] = 'BPLAN';
            $index_name['ERAMT'] = 'ERAMT';
        }
        return $index_name;
    }


    public function getPendingRemOdoo($data){
        // $token = $this->getOdooToken('admin', 'admin');
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        }
        else{
            $token = $this->getOdooToken('admin', 'admin');
        }

        $ch = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization
        ));

        $param = array(
                        "nik"=>$data['NIK']
                    );

        $param = http_build_query($param);

        $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/medical-transaction-pending?".$param;

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);

        //execute post
        ob_start();
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = ob_get_clean();

        //close connection
        curl_close($ch);

        if ($httpcode == 401) {
            $token = $this->getOdooToken('admin', 'admin');
            $this->getPendingRemOdoo($data);
        }
        else if ($httpcode == 200) {

            $json_decode = json_decode($result, true);
            $index_name = $this->getIndexNamePendingRem(1);
            $json_decode['index_name'] = $index_name;
            $json_decode['rows'] = count($json_decode['data']);

            return $json_decode;
        }
        else{
            return False;
        }
        
    }


    public function getPendingRemSAP($data){
        $NIK = $this->NIK;
        $rfc = $this->rfc;

        //cek transaksi yang pending di SAP
        $fce_pending = saprfc_function_discover($rfc,"ZHRFM_CEK_PENDINGREMSAP");
        if (! $fce_pending ) { echo "Discovering interface of function module failed"; exit; }
        saprfc_import ($fce_pending,"FI_PERNR",$data['NIK']);
        $rfc_rc = saprfc_call_and_receive ($fce_pending);
        if ($rfc_rc != SAPRFC_OK)
        { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce_pending)); else echo (saprfc_error($fce_pending)); exit; }

        $row = saprfc_table_rows($fce_pending,'FI_T7XAST');

        $index_name = $this->getIndexNamePendingRem(0);

        $return = array(
            "fce"=>$fce_pending,
            "rows" => $row,
            "index_name"=>$index_name
        );

        return $return;
    }
    /*------------------ GET ZHRFM_CEK_PENDINGREM ---------------------*/


    /*------------------ GET PATIENT ---------------------*/
    public function getPatient($data){
        $this->NIK = $data['NIK'];
        $flag_odoo = check_access_odoo($data['NIK']);

        // var_dump("data:",$data);

        if ($flag_odoo == 1) {
            $data = $this->getPatientOdoo($data);
        }
        else{
            $data = $this->getPatientSAP($data);
        }

        return $data;
    }

    public function getIndexNamePatient($flag_odoo){
        if ($flag_odoo) {
            $index_name['FANAM'] = 'name';
            $index_name['SUBTY'] = 'family_relation';
            $index_name['OBJPS'] = 'child_no';
        }
        else{
            $index_name['FANAM'] = 'FANAM';
            $index_name['SUBTY'] = 'SUBTY';
            $index_name['OBJPS'] = 'OBJPS';
        }
        return $index_name;
    }


    public function getPatientOdoo($data){
        // $token = $this->getOdooToken('admin', 'admin');
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        }
        else{
            $token = $this->getOdooToken('admin', 'admin');
        }

        $ch = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization
        ));

        $param = array(
                        "nik"=>$data['NIK']
                    );

        $param = http_build_query($param);

        $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/employee-family?".$param;

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);

        //execute post
        ob_start();
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = ob_get_clean();

        //close connection
        curl_close($ch);

        if ($httpcode == 401) {
            $token = $this->getOdooToken('admin', 'admin');
            $this->getPatientOdoo($data);
        }
        else if ($httpcode == 200) {

            $json_decode = json_decode($result, true);
            $index_name = $this->getIndexNamePatient(1);
            $json_decode['index_name'] = $index_name;
            $json_decode['rows'] = count($json_decode['data']);

            return $json_decode;
        }
        else{
            return False;
        }
        
    }


    public function getPatientSAP($data){
        $NIK = $this->NIK;
        $rfc = $this->rfc;

        $fce = saprfc_function_discover($rfc,"ZHRFM_MR_PATIENT");
        if (! $fce ) { echo "Discovering interface of function module failed"; exit; }

        //Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
        saprfc_import ($fce,"FI_PERNR",$data['NIK']);
        //Fill internal tables
        saprfc_table_init ($fce,"FI_MR_PATIENT");
        //Do RFC call of function ZHRFM_MR_PATIENT, for handling exceptions use saprfc_exception()
        $rfc_rc = saprfc_call_and_receive ($fce);
        if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
        //Retrieve export parameters
        $rows = saprfc_table_rows ($fce,"FI_MR_PATIENT");

        $index_name = $this->getIndexNamePatient(0);

        $return = array(
            "fce"=>$fce,
            "rows" => $rows,
            "index_name"=>$index_name
        );

        return $return;
    }
    /*------------------ GET Patient ---------------------*/


    /*------------------ GET BPLAN ---------------------*/
    public function getBPLAN($data){
        $this->NIK = $data['NIK'];
        $flag_odoo = check_access_odoo($data['NIK']);

        // var_dump("data:",$data);

        if ($flag_odoo == 1) {
            $data = $this->getBPLANOdoo($data);
        }
        else{
            $data = $this->getBPLANSAP($data);
        }

        return $data;
    }


    public function getIndexNameBPLAN($flag_odoo){
        if ($flag_odoo) {
            $index_name['BPLAN'] = 'bplan';
            $index_name['PNDOC'] = 'code';
            $index_name['LTEXT'] = 'name';
        }
        else{
            $index_name['BPLAN'] = 'BPLAN';
            $index_name['PNDOC'] = 'PNDOC';
            $index_name['LTEXT'] = 'LTEXT';
        }
        return $index_name;
    }


    public function getBPLANOdoo($data){
        // $token = $this->getOdooToken('admin', 'admin');
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        }
        else{
            $token = $this->getOdooToken('admin', 'admin');
        }

        $ch = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization
        ));

        /*$param = array(
                        "nik"=>$data['NIK']
                    );

        $param = http_build_query($param);*/

        $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/medical-bplan-detail";

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);

        //execute post
        ob_start();
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = ob_get_clean();

        //close connection
        curl_close($ch);

        if ($httpcode == 401) {
            $token = $this->getOdooToken('admin', 'admin');
            $this->getBPLANOdoo($data);
        }
        else if ($httpcode == 200) {

            $json_decode = json_decode($result, true);
            $index_name = $this->getIndexNameBPLAN(1);
            $json_decode['index_name'] = $index_name;
            $json_decode['rows'] = count($json_decode['data']);

            return $json_decode;
        }
        else{
            return False;
        }
        
    }


    public function getBPLANSAP($data){
        $NIK = $this->NIK;
        $rfc = $this->rfc;

        $fce = saprfc_function_discover($rfc,"ZHRFM_MR_BPLAN");
        if (! $fce ) { echo "Discovering interface of function module failed"; exit; }

        saprfc_import ($fce,"FI_BAREA",$data['barea_34']);
        //Fill internal tables
        saprfc_table_init ($fce,"FI_MR_BPLAN");
        //Do RFC call of function ZHRFM_MR_PATIENT, for handling exceptions use saprfc_exception()
        $rfc_rc = saprfc_call_and_receive ($fce);
        if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
        //Retrieve export parameters
        $rows_bplan = saprfc_table_rows ($fce,"FI_MR_BPLAN");

        $index_name = $this->getIndexNameBPLAN(0);

        for ($i=1;$i<=$rows_bplan;$i++)
        {
            $FI_MR_BPLAN[] = saprfc_table_read ($fce,"FI_MR_BPLAN",$i);
        }

        $return = array(
            "fce"=>$fce,
            "rows" => $rows_bplan,
            "index_name"=>$index_name,
            "data"=>$FI_MR_BPLAN
        );

        return $return;
    }

    public function filter_by_param($data, $param, $index_name){
        $filtered_data = array_filter($data, function($value) use($param, $index_name) {
            if (is_array($param)) {
                return in_array($value[$index_name["BPLAN"]], $param);
            }
            else{
                return $value[$index_name["BPLAN"]] == $param;
            }
        });

        return $filtered_data;
    }

    public function display_filtered_data($data, $index_name)
    {
        if (is_array($data)) {
            foreach ($data as $key) {
                $type_jenis_dokter = $key[$index_name["BPLAN"]];
                $detail_jenis_dokter = $key[$index_name['PNDOC']];
                $nama_jenis_dokter = $key[$index_name['LTEXT']];
                echo '<option value="'.$type_jenis_dokter.'+'.$detail_jenis_dokter.'">'.$nama_jenis_dokter.'</option>';
            }
        }
        else{
            $type_jenis_dokter = $data[$index_name['BPLAN']];
            $detail_jenis_dokter = $data[$index_name['PNDOC']];
            $nama_jenis_dokter = $data[$index_name['LTEXT']];
            echo '<option value="'.$type_jenis_dokter.'+'.$detail_jenis_dokter.'">'.$nama_jenis_dokter.'</option>';
        }
    }
    /*------------------ GET BPLAN ---------------------*/


    /*------------------ GET BPLAN Detail---------------------*/
    public function getBPLANDetail($data){
        $this->NIK = $data['NIK'];
        $flag_odoo = check_access_odoo($data['NIK']);

        // var_dump("data:",$data);

        if ($flag_odoo == 1) {
            $data = $this->getBPLANDetailOdoo($data);
        }
        else{
            $data = $this->getBPLANDetailSAP($data);
        }

        return $data;
    }

    public function getIndexNameBPLANDetail($flag_odoo){
        if ($flag_odoo) {
            $index_name['DOCID'] = 'code';
            $index_name['PNDOC'] = 'bplan';
            $index_name['NAME1'] = 'name';
        }
        else{
            $index_name['DOCID'] = 'DOCID';
            $index_name['PNDOC'] = 'PNDOC';
            $index_name['NAME1'] = 'NAME1';
        }
        return $index_name;
    }


    public function getBPLANDetailOdoo($data){
        // $token = $this->getOdooToken('admin', 'admin');
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        }
        else{
            $token = $this->getOdooToken('admin', 'admin');
        }

        $ch = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization
        ));

        $param = array(
                        "bplan"=>$data['detail_specialist']
                    );

        $param = http_build_query($param);

        $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/medical-bplan-detail?".$param;

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);

        //execute post
        ob_start();
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = ob_get_clean();

        //close connection
        curl_close($ch);

        if ($httpcode == 401) {
            $token = $this->getOdooToken('admin', 'admin');
            $this->getBPLANDetailOdoo($data);
        }
        else if ($httpcode == 200) {

            $json_decode = json_decode($result, true);
            $index_name = $this->getIndexNameBPLANDetail(1);
            $json_decode['index_name'] = $index_name;
            $json_decode['rows'] = count($json_decode['data']);

            return $json_decode;
        }
        else{
            return False;
        }
        
    }


    public function getBPLANDetailSAP($data){
        $NIK = $this->NIK;
        $rfc = $this->rfc;

        $fce = saprfc_function_discover($rfc,"ZHRFM_MR_BPLAN_DETAIL");
        if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
        saprfc_import ($fce,"FI_PNDOC",$data['detail_specialist']);
        saprfc_table_init ($fce,"FI_MR_BPLAN_DTL");
        $rfc_rc = saprfc_call_and_receive ($fce);
        if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
        $rows = saprfc_table_rows ($fce,"FI_MR_BPLAN_DTL");

        $index_name = $this->getIndexNameBPLANDetail(0);

        /*for ($i=1;$i<=$rows;$i++)
        {
            $FI_MR_BPLAN_DTL[] = saprfc_table_read ($fce,"FI_MR_BPLAN_DTL",$i);
        }*/

        $return = array(
            "fce"=>$fce,
            "rows" => $rows,
            // "data"=>$FI_MR_BPLAN_DTL,
            "index_name"=>$index_name
        );

        return $return;
    }

    /*------------------ GET BPLAN Detail ---------------------*/


    /*------------------ GET BANKKEY ---------------------*/
    public function getBankkey($data){
        $this->NIK = $data['NIK'];
        $flag_odoo = check_access_odoo($data['NIK']);

        // var_dump("data:",$data);

        if ($flag_odoo == 1) {
            $data = $this->getBankkeyOdoo($data);
        }
        else{
            $data = $this->getBankkeySAP($data);
        }

        return $data;
    }

    public function getIndexNameBankkey($flag_odoo){
        if ($flag_odoo) {
            $index_name['FANAM'] = 'name';
            $index_name['SUBTY'] = 'family_relation';
            $index_name['OBJPS'] = 'child_no';
        }
        else{
            $index_name['FANAM'] = 'FANAM';
            $index_name['SUBTY'] = 'SUBTY';
            $index_name['OBJPS'] = 'OBJPS';
        }
        return $index_name;
    }


    public function getBankkeyOdoo($data){
        // $token = $this->getOdooToken('admin', 'admin');
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        }
        else{
            $token = $this->getOdooToken('admin', 'admin');
        }

        $ch = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization
        ));

        $param = array(
                        "nik"=>$data['NIK']
                    );

        $param = http_build_query($param);

        $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/employee-bank-code?".$param;

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);

        //execute post
        ob_start();
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = ob_get_clean();

        //close connection
        curl_close($ch);

        if ($httpcode == 401) {
            $token = $this->getOdooToken('admin', 'admin');
            $this->getBankkeyOdoo($data);
        }
        else if ($httpcode == 200) {

            $json_decode = json_decode($result, true);
            $index_name = $this->getIndexNameBankkey(1);
            $json_decode['index_name'] = $index_name;
            $json_decode['rows'] = 1;
            $json_decode['FI_BANKL'] = $json_decode['bank_code'];

            return $json_decode;
        }
        else{
            return False;
        }
        
    }


    public function getBankkeySAP($data){
        $NIK = $this->NIK;
        $rfc = $this->rfc;

        $fce = saprfc_function_discover($rfc,"ZHRFM_CEK_BANKKEY");
        if (! $fce ) { echo "Discovering interface of function module failed"; exit; }

        //Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
        saprfc_import ($fce,"FI_PERNR",$data["NIK"]);
        //Fill internal tables
        //Do RFC call of function ZHRFM_MR_CALC_TARIF, for handling exceptions use saprfc_exception()
        $rfc_rc = saprfc_call_and_receive ($fce);
        if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }

        //Retrieve export parameters
        $cek_bank1 = saprfc_export($fce, "FI_BANKL");

        // $index_name = $this->getIndexNameBankkey(0);

        $return = array(
            "fce"=>$fce,
            "rows" => 1,
            "FI_BANKL"=>$cek_bank1
        );

        return $return;
    }
    /*------------------ GET BANKKEY ---------------------*/

}
    
?>
