<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

class ESSlibrary
{
    public $conn="";
    public $rfc="";
    public $NIK="";
    public $urlOdooHR = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/";
    public $urlOdooBase = "https://kg-pms-odoo-dev1.mykg.id/";
    public $userOdoo = 'admin';
    public $passwdOdoo = 'admin';

    public function __construct(){
        include "include/connection.php";
        include "include/sapconnect.php";
        include_once "include/check_Flag.php";
        include_once "language/credentials_constant.php";
        $this->conn = $conn;
        $this->rfc = $rfc;
        $this->urlOdooHR = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/";
        $this->urlOdooBase = "https://kg-pms-odoo-dev1.mykg.id/";
        $this->userOdoo = 'admin';
        $this->passwdOdoo = 'admin';
    }

    /*--------------- GET ODOO TOKEN ---------------------*/
    public function getOdooToken($user=NULL,$password=NULL)
    {
        /*if (!isset($user) && !isset($password)) {
            $user = $userOdoo;
            $password = $passwdOdoo;
        }*/
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
        $url = $this->urlOdooBase . 'kg/api/auth/user/token';

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
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
        // var_dump('token: ',$json_decode);
        $_SESSION['token_odoo'] = $token;

        return $token;
    }
    /*--------------- GET ODOO TOKEN ---------------------*/


    /*--------------- DAY CHECK -------------------*/

    public function dayCheck($data)
    {

        $this->NIK = $data["NIK"];
        $flag_odoo = check_access_odoo($data["NIK"]);

        if ($flag_odoo == 1) {
            $data = $this->dayCheckOdoo($data);
        } else {
            $data = $this->dayCheckSAP($data);
        }

        return $data;
    }


    public function dayCheckOdoo($data){
        return array("FE_ABSENCE" => "", "FE_ATTENDANCE" => "","FE_PUBLICHOLIDAY"=>"","FE_STATUS"=>"","FE_TPROG"=>"","FI_CUTI"=>"");
    }

    public function dayCheckSAP($data){

        /*if (strpos($data["leave_date"], "-")) {
            $leave_date_formatted = explode("-",$data['leave_date']);
            $data['leave_date'] = $leave_date_formatted[0].$leave_date_formatted[1].$leave_date_formatted[2];
        }
        else{
            $data['leave_date'] =  substr($data['leave_date'],0,4).substr($data['leave_date'],4,2).substr($data['leave_date'],6,2);
        }*/

        $rfc = $this->rfc;
        $fce = saprfc_function_discover($rfc,"ZHRFM_DAYCHECK");
        if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
        //Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
        saprfc_import ($fce,"FI_PERNR",$data['NIK']);
        saprfc_import ($fce,"FI_TANGGAL",$data['tanggalcuti']);
        //Fill internal tables
        //Do RFC call of function ZHRFM_DAYCHECK, for handling exceptions use saprfc_exception()
        $rfc_rc = saprfc_call_and_receive ($fce);
        if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }

        $FE_ABSENCE = saprfc_export ($fce,"FE_ABSENCE");
        $FE_ATTENDANCE = saprfc_export ($fce,"FE_ATTENDANCE");
        $FE_PUBLICHOLIDAY = saprfc_export ($fce,"FE_PUBLICHOLIDAY");
        $FE_STATUS = saprfc_export ($fce,"FE_STATUS");
        $FE_TPROG = saprfc_export ($fce,"FE_TPROG");
        $FI_CUTI = saprfc_export ($fce,"FI_CUTI");

        return array("FE_ABSENCE" => $FE_ABSENCE, "FE_ATTENDANCE" => $FE_ATTENDANCE,"FE_PUBLICHOLIDAY"=>$FE_PUBLICHOLIDAY,"FE_STATUS"=>$FE_STATUS,"FE_TPROG"=>$FE_TPROG,"FI_CUTI"=>$FI_CUTI);

        // var_dump($fce);
        // if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
        // saprfc_function_free($fce);
        
    }
    /*------------------ GET dayCheck -------------------------*/


    /*------------------ GET TIME EVENT ---------------------*/
    public function getEmployeeTimeEvent($data){
        $this->NIK = $data['NIK'];
        $flag_odoo = check_access_odoo($data['NIK']);

        // var_dump("data:",$data);

        if ($flag_odoo == 1) {
            $data = $this->getEmployeeTimeEventOdoo($data);
        }
        else{
            $data = $this->getEmployeeTimeEventSAP($data);
        }

        return $data;
    }

    public function getIndexNameTimeEvent($flag_odoo){
        if ($flag_odoo) {
            $index_name['TANGGAL'] = 'all_date';
            $index_name['ABSEN'] = 'working_time';
            $index_name['HARI'] = 'days';
            $index_name['JAMMASUK'] = 'check_in';
            $index_name['NOTERMINALMASUK'] = 'location_in';
            $index_name['JAMPULANG'] = 'check_out';
            $index_name['NOTERMINALPULANG'] = 'location_out';
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


    public function getEmployeeTimeEventOdoo($data){
        // $token = $this->getOdooToken('admin', 'admin');
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        }
        else{
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
        }

        $ch = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization
        ));

        $param = array(
                        "nik"=>$data['NIK'],
                        "period_date_start"=>$data["startDate"],
                        "period_date_end"=>$data["endDate"]
                    );

        $param = http_build_query($param);

        // $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/checkinout?".$param;
        $url = $this->urlOdooHR . 'checkinout?'.$param;

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
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
            $this->getEmployeeTimeEventOdoo($data);
        }
        else if ($httpcode == 200) {

            $json_decode = json_decode($result, true);

            // var_dump('result: ',$json_decode['data'][0]);
            // flag odoo = 1
            $index_name = $this->getIndexNameTimeEvent(1);
            $json_decode['index_name'] = $index_name;
            $json_decode['FI_RTEXT'] = $json_decode['data'][0]['resource_calendar_name'];
            $json_decode['rows'] = count($json_decode['data']);

            return $json_decode;
        }
        else{
            return False;
        }
        
    }


    public function getEmployeeTimeEventSAP($data){
        $NIK = $this->NIK;
        $rfc = $this->rfc;
        // var_dump("ini masuk brp kali");
        // var_dump("dataTimeevent: ",$data);
        // if ($data['startDate']==''  ||  $data['endDate'] ==''){
            
        //     $data['endDate'] = date('Ymd');
        //     $data['startDate'] = date('Ymd',strtotime('-30 days'));
        // }else if (strpos($data["startDate"], "-") && strpos($data["endDate"], "-")) {
        //     $data['startDate'] = str_replace("-", "", $data['startDate']);
        //     $data['endDate'] = str_replace("-", "", $data['endDate']);
        // }

        //GET CHIEF
        $fce = saprfc_function_discover($rfc,"ZHRFM_LIST_TIME");
        if (! $fce )
        {
            echo "System Error. Please Try Again Later."; exit;
        }
        saprfc_import ($fce,"FI_AKHIR",$data['endDate']);
        saprfc_import ($fce,"FI_AWAL",$data['startDate']);
        saprfc_import ($fce,"FI_PERNR",$data['NIK']);
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

        // flag odoo = 0
        $index_name = $this->getIndexNameTimeEvent(0);

        return array('FI_RTEXT' => $FI_RTEXT, "rows"=>$rows, "fce"=>$fce, "index_name"=>$index_name);
    }
    /*------------------ GET TIME EVENT ---------------------*/


    /*------------------ GET NIK ATASAN ---------------------*/
    public function getEmployeeSuperior($NIK){
        $this->NIK = $NIK;
        $flag_odoo = check_access_odoo($NIK);

        if ($flag_odoo == 1) {
            $data = $this->getEmployeeSuperiorOdoo($NIK);
        }
        else{
            $data = $this->getEmployeeSuperiorSAP($NIK);
        }

        return $data;
    }


    public function getEmployeeSuperiorOdoo($NIK){
        // $token = $this->getOdooToken('admin', 'admin');
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        }
        else{
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
        }

        $ch = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization
        ));

        $param = array(
                        "fields"=>"employee_id,name,parent_id",
                        "domain"=>"[('employee_id', '=', '".$NIK."')]"
                    );

        $param = http_build_query($param);

        // $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/res/hr.employee?".$param;
        $url = $this->urlOdooBase . 'kg/api/res/hr.employee?'.$param;

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
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
            $this->getEmployeeSuperiorOdoo($NIK);
        }
        else if ($httpcode == 200) {

            $json_decode = json_decode($result, true);

            // var_dump('result: ',$json_decode['data'][0]['parent_id'][1]);
            // flag odoo = 1
            // $index_name = $this->getIndexNameAttendance(1);
            // $json_decode['index_name'] = $index_name;

            $json_decode['rows'] = count($json_decode['data']);

            return $json_decode;
        }
        else{
            return False;
        }
        
    }


    public function getEmployeeSuperiorSAP(){
        $NIK = $this->NIK;
        $rfc = $this->rfc;
        //GET CHIEF
        $fce = saprfc_function_discover($rfc,"ZHRFM_GETCHIEF");
        if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
    //Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
        saprfc_import ($fce,"FI_KEYDATE",date('Ymd'));
        saprfc_import ($fce,"FI_PERNR",$NIK);
        //Fill internal tables
        saprfc_table_init ($fce,"FE_HRP1000");
        //Do RFC call of function ZHRFM_GETCHIEF, for handling exceptions use saprfc_exception()
        $rfc_rc = saprfc_call_and_receive ($fce);
        if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
        $rows = saprfc_table_rows ($fce,"FE_HRP1000");

        // flag odoo = 0
        // $index_name = $this->getIndexNameAttendance(0);

        $return = array("fce"=>$fce, "rows"=>$rows/*, "index_name"=>$index_name*/);
        return $return;
    }
    /*------------------ GET NIK ATASAN ---------------------*/


    /*------------------ GET ATTENDANCE TYPE ---------------------*/
    public function getAttendanceType($NIK){
        $this->NIK = $NIK;
        $flag_odoo = check_access_odoo($NIK);

        if ($flag_odoo == 1) {
            $data = $this->getAttendanceTypeOdoo($NIK);
        }
        else{
            $data = $this->getAttendanceTypeSAP($NIK);
        }

        return $data;
    }


    public function getAttendanceTypeOdoo($NIK){
        // $token = $this->getOdooToken('admin', 'admin');
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        }
        else{
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
        }

        $ch = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization
        ));

        // $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/attendance-regular-type";
        $url = $this->urlOdooHR . 'attendance-regular-type';

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
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
            $this->getAttendanceTypeOdoo($NIK);
        }
        else if ($httpcode == 200) {

            $json_decode = json_decode($result, true);

            // var_dump('result: ',$json_decode['data'][0]['parent_id'][1]);
            // flag odoo = 1
            // $index_name = $this->getIndexNameAttendance(1);
            // $json_decode['index_name'] = $index_name;

            $json_decode['rows'] = count($json_decode['data']);

            return $json_decode;
        }
        else{
            return False;
        }
        
    }


    public function getAttendanceTypeSAP(){
        $NIK = $this->NIK;
        $rfc = $this->rfc;
        // GET BAPI
        $fce = saprfc_function_discover($rfc,"ZHRFM_LIST_ABSENCE_TYPE");
        if (! $fce )
        {
                    echo "System Error. Please Try Again Later."; exit;
        }
        saprfc_import ($fce,"FI_TYPE",'2002');
        saprfc_table_init ($fce,"FI_DWS");
        $rfc_rc = saprfc_call_and_receive ($fce);
        if ($rfc_rc != SAPRFC_OK)
        {
            if ($rfc == SAPRFC_EXCEPTION )
                echo "System Error. Please Try Again Later.";
            else
                echo "System Error. Please Try Again Later."; exit;
        }
        $rows = saprfc_table_rows ($fce,"FI_DWS");

        // flag odoo = 0
        // $index_name = $this->getIndexNameAttendance(0);

        $return = array("fce"=>$fce, "rows"=>$rows/*, "index_name"=>$index_name*/);
        return $return;
    }
    /*------------------ GET ATTENDANCE TYPE ---------------------*/



    /*------------------ GET ATTENDANCE MAX DAYS ---------------------*/
    public function getAttendanceMaxDays($data){
        $this->NIK = $data["NIK"];
        $flag_odoo = check_access_odoo($data["NIK"]);
        if ($flag_odoo == 1) {
            $data = $this->getAttendanceMaxDaysOdoo($data);
        }
        else{
            $data = $this->getAttendanceMaxDaysSAP($data);
        }

        return $data;
    }


    public function getAttendanceMaxDaysOdoo($data){
        // $token = $this->getOdooToken('admin', 'admin');
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        }
        else{
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
        }

        $ch = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization
        ));

        $param = array(
                        "attendance_type"=>$data["absenceType"]
                    );

        $param = http_build_query($param);

        // $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/attendance-regular-type-detail?".$param;
        $url = $this->urlOdooHR . 'attendance-regular-type-detail?'.$param;

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
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
            $this->getAttendanceMaxDaysOdoo($data);
        }
        else if ($httpcode == 200) {

            $json_decode = json_decode($result, true);



            // var_dump('result: ',$json_decode['data'][0]['parent_id'][1]);
            // flag odoo = 1
            // $index_name = $this->getIndexNameAttendance(1);
            // $json_decode['index_name'] = $index_name;
            $json_decode['rows'] = count($json_decode);

            return $json_decode;
        }
        else{
            return False;
        }
        
    }


    public function getAttendanceMaxDaysSAP($data){
        $NIK = $this->NIK;
        $rfc = $this->rfc;
        $fce = saprfc_function_discover($rfc,"ZHRFM_GETMAXDAYABSENCE");
        if (! $fce )
        {
                echo "System Error. Please Try Again Later."; exit;
        }
        saprfc_import ($fce,"FI_SUBTY",$data["absenceType"]);
        $rfc_rc = saprfc_call_and_receive ($fce);
        if ($rfc_rc != SAPRFC_OK)
        {
                if ($rfc == SAPRFC_EXCEPTION )
                        echo "System Error. Please Try Again Later.";
                else
                        echo "System Error. Please Try Again Later."; exit;
        }
        $FE_MAXTG = saprfc_export ($fce,"FE_MAXTG");

        // $jumlah_cuti = $FE_MAXTG;

        // flag odoo = 0
        // $index_name = $this->getIndexNameAttendance(0);

        $return = array("fce"=>$fce, "FE_MAXTG"=>$FE_MAXTG/*, "index_name"=>$index_name*/);
        return $return;
    }
    /*------------------ GET ATTENDANCE MAX DAYS ---------------------*/



    /*------------------ GET HISTORY ATTENDANCE ---------------------*/
    public function getHistoryAttendance($NIK,$month,$year){
        $this->NIK = $NIK;
        $flag_odoo = check_access_odoo($NIK);

        if ($flag_odoo == 1) {
            $data = $this->getHistoryAttendanceOdoo($NIK,$month,$year);
        }
        else{
            $data = $this->getHistoryAttendanceSAP($NIK,$month,$year);
        }

        return $data;
    }


    public function getIndexNameAttendance($flag_odoo){
        if ($flag_odoo) {
            $index_name['BEGDA'] = 'attendance_date';
            $index_name['UNAME'] = 'input_user';
            $index_name['AEDTM'] = 'input_date';
            $index_name['SUBTY_TXT'] = 'attendance_type';
        }
        else{
            $index_name['BEGDA'] = 'BEGDA';
            $index_name['UNAME'] = 'UNAME';
            $index_name['AEDTM'] = 'AEDTM';
            $index_name['SUBTY'] = 'SUBTY';
        }
        return $index_name;
    }

    public function getHistoryAttendanceOdoo($NIK, $month, $year){
        // $token = $this->getOdooToken('admin', 'admin');
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        }
        else{
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
        }

        $ch = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization
        ));

        $today = date("Y-m-d");

        $postdata = array(
                        'nik' => $NIK,
                        "month"=> $month,
                        "year" => $year
                    );
        $postdata = http_build_query($postdata);

        // $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/attendance-regular?".$postdata;
        $url = $this->urlOdooHR . 'attendance-regular?'.$param;

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
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
            $this->getHistoryAttendanceOdoo($NIK, $month, $year);
        }
        else if ($httpcode == 200) {
            $json_decode = json_decode($result, true);
            // flag odoo = 1
            $index_name = $this->getIndexNameAttendance(1);
            $json_decode['index_name'] = $index_name;

            $json_decode['rows'] = count($json_decode['data']);

            return $json_decode;
        }
        else{
            return False;
        }
        
    }

    public function getHistoryAttendanceSAP(){
        $NIK = $this->NIK;
        $rfc = $this->rfc;
        $fce = saprfc_function_discover($rfc,"ZHRFM_LIST_ATTENDANCE");
        if (! $fce ) 
        { 
            echo "System Error. Please Try Again Later."; exit;
        }
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

        // flag odoo = 0
        $index_name = $this->getIndexNameAttendance(0);

        $return = array("fce"=>$fce, "rows"=>$rows, "index_name"=>$index_name);
        return $return;
    }
    /*------------------ GET HISTORY ATTENDANCE ---------------------*/


    /*------------------ INSERT ATTENDANCE ---------------------*/
    public function insertAttendance($data){
        $this->NIK = $data["NIK"];
        $flag_odoo = check_access_odoo($data["NIK"]);

        // var_dump("flag_odoo",$flag_odoo);

        if ($flag_odoo == 1) {
            $data = $this->insertAttendanceOdoo($data);
        }
        else{
            $data = $this->insertAttendanceSAP($data);
        }

        return $data;
    }

    public function deleteAttendance($data){
        $this->NIK = $data["NIK"];
        $flag_odoo = check_access_odoo($data["NIK"]);

        if ($flag_odoo == 1) {
            $data = $this->deleteAttendanceOdoo();
        }
        else{
            $data = $this->deleteAttendanceSAP($data);
        }

        return $data;
    }
    
    public function deleteAttendanceOdoo(){
        // odoo langsung kasih return sukses aja
        $json_decode['SUBRC'] = "0";
        return $json_decode;
    }

    public function deleteAttendanceSAP($data){
        $rfc = $this->rfc;
        // var_dump("masuk delete SAP");
                // if (!is_array($data["attendance_date"])) {
                    // buat klo status_datenya = 0 dan datanya bukan array (biasanya ini jln di dlm proses looping di luar)
                    if (strpos($data["attendance_date"], "-")) {
                        $attendance_date_formatted = explode("-",$data['attendance_date']);
                        $data['attendance_date'] = $attendance_date_formatted[2].$attendance_date_formatted[1].$attendance_date_formatted[0];
                    }
                    else{
                        $data['attendance_date'] =  substr($data['attendance_date'],6,2).substr($data['attendance_date'],4,2).substr($data['attendance_date'],0,4);
                    }
                // }
                

                //delete to sap
                $fce = saprfc_function_discover($rfc,"ZHRFM_DEL_2002");
                if (! $fce ) 
                { echo "Discovering interface of function module failed"; exit; }
                
                saprfc_import ($fce,"CHOIC_005","2002");
                saprfc_import ($fce,"CTU","X");
                saprfc_import ($fce,"FI_BEGDA",$data['attendance_date']);
                saprfc_import ($fce,"FI_BEGDA2",$data['attendance_date']);
                saprfc_import ($fce,"FI_ENDDA",$data['attendance_date']);
                saprfc_import ($fce,"FI_ENDDA2",$data['attendance_date']);
                saprfc_import ($fce,"FI_PERNR",$data['NIK']);
                saprfc_import ($fce,"GROUP","");
                saprfc_import ($fce,"HOLDDATE","");
                saprfc_import ($fce,"KEEP","");
                saprfc_import ($fce,"MODE","N");
                saprfc_import ($fce,"NODATA","/");
                saprfc_import ($fce,"TIMR6_002","X");
                saprfc_import ($fce,"UPDATE","L");
                saprfc_import ($fce,"USER","");
                //Fill internal tables
                saprfc_table_init ($fce,"MESSTAB");
                $rfc_rc = saprfc_call_and_receive ($fce);
                if ($rfc_rc != SAPRFC_OK) 
                { 
                    if ($rfc == SAPRFC_EXCEPTION ) 
                    echo ("Exception raised: ".saprfc_exception($fce)); 
                    else echo (saprfc_error($fce)); exit; 
                }
                //Retrieve export parameters
                $SUBRC = saprfc_export ($fce,"SUBRC");
                $rows = saprfc_table_rows ($fce,"MESSTAB");
                saprfc_function_free($fce);
                
                return array("SUBRC"=>$SUBRC,"rows"=>$rows);
    }

    public function listAttendance($data){
        $this->NIK = $data["NIK"];
        $flag_odoo = check_access_odoo($data["NIK"]);

        if ($flag_odoo == 1) {
            $data = $this->listAttendanceOdoo();
        }
        else{
            $data = $this->listAttendanceSAP($data);
        }

        return $data;
    }

    public function listAttendanceOdoo(){
        // var_dump("masuknya sini loh");
        return array("flag"=>0);
    }

    public function listAttendanceSAP($data){
        $rfc = $this->rfc;
        $fce = saprfc_function_discover($rfc,"ZHRFM_LIST_2002");
                    if (! $fce ) { echo "Discovering interface of function module failed"; 
                                    exit; }
                    saprfc_import ($fce,"FI_BEGDA",$data["attendance_date"][0]);
                    saprfc_import ($fce,"FI_ENDDA",$data["attendance_date"][1]);   
                    saprfc_import ($fce,"FI_PERNR",$data["NIK"]);
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
                    // var_dump($rows);
                    
                    if($data['type']=='5001' || $data['type']=='5002' || $data['type']=='5003')
                    {
                        $flag=0;
                        for ($x=1;$x<=$rows;$x++)
                        {
                            $FI_CT = saprfc_table_read ($fce,"FI_CT",$x);
                            $FI_CT['BEGDA'].'<br>';
                            $flag =1;
                        }
                        // var_dump($FI_CT);
                    }
                    else{
                        $flag = 0;
                        $FI_CT = 0;
                    }

                    // var_dump("flag_list: ",$flag);

        return array("FI_CT"=>$FI_CT,"flag"=>$flag);
    }

    public function checkErrorMsg($error=''){
        $substr_message = substr($error, strpos($error,"is locked"));
        if ($substr_message == "is locked for master data maintenance") {
                $error = 'System is locked due to payroll processing';
        }
        return $error;
    }

    public function insertAttendanceBAPI($NIK,$type,$begda,$endda){
        $rfc = $this->rfc;
        $fce = saprfc_function_discover($rfc,"ZHRFM_CREATE_2002");
        // var_dump($NIK,$type,$begda,$endda);
        if (! $fce )
        { echo "Discovering interface of function module failed"; exit; }
                                
        saprfc_import ($fce,"ATT_TYPE","$type");
        saprfc_import ($fce,"CHOIC_003","2002");
        saprfc_import ($fce,"CTU","X");
        saprfc_import ($fce,"FI_BEGDA",$begda);
        saprfc_import ($fce,"FI_ENDDA",$endda);
        saprfc_import ($fce,"FI_PERNR",$NIK);
        saprfc_import ($fce,"GROUP","");
        saprfc_import ($fce,"HOLDDATE","");
        saprfc_import ($fce,"KEEP","");
        saprfc_import ($fce,"MODE","N");
        saprfc_import ($fce,"NODATA","/");
        saprfc_import ($fce,"TIMR6_002","X");
        saprfc_import ($fce,"UPDATE","L");
        saprfc_import ($fce,"USER","");
        //Fill internal tables
        saprfc_table_init ($fce,"MESSTAB");
        $rfc_rc = saprfc_call_and_receive ($fce);
        if ($rfc_rc != SAPRFC_OK) 
        { 
            if ($rfc == SAPRFC_EXCEPTION ) 
            echo ("Exception raised: ".saprfc_exception($fce)); 
            else echo (saprfc_error($fce)); exit; 
        }

        //Retrieve export parameters
        $SUBRC = saprfc_export ($fce,"SUBRC");
        $FI_ERR = saprfc_export ($fce,"FI_ERR");
        $FI_ERR = $this->checkErrorMsg($FI_ERR);
        $rows = saprfc_table_rows ($fce,"MESSTAB");
        for ($j=1;$j<=$rows;$j++)
            $MESSTAB[] = saprfc_table_read ($fce,"MESSTAB",$j);
                                    
        saprfc_function_free($fce);
        // var_dump($type,$begda,$endda,$NIK,$FI_ERR);
        return array("SUBRC"=>$SUBRC, "FI_ERR"=>$FI_ERR);
    }

    public function insertAttendanceOdoo($data){
        // $token = $this->getOdooToken('admin', 'admin');
        // var_dump('masuk ke insert odoo loh');
        $NIK = $this->NIK;
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        }
        else{
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
        }

        if (isset($data)) {
            $ch = curl_init();

            $authorization = "Authorization: Bearer $token";

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                $authorization,
                'Content-type: application/json'
            ));

            $today = date("Y-m-d");
            // var_dump("input_date:",$data["input_date"]);
            $postdata = array(
                            'nik' => $NIK,
                            "attendance_type"=> $data["type"],
                            "attendance_reason" => ($data["reason"] != "") ? $data["reason"] : $data["type"],
                            "input_date"=>date("Y-m-d H:i:s", strtotime($data["input_date"])),
                            "approver_nik"=>$data["approver"]
                        );
            // var_dump("test");
            $jml_array = count($data['attendance_date']);
            $status_date = $data['status_date'];
            if ($status_date == 1) {
                if (!strpos($data["attendance_date"][0], "-")) {
                    $data["attendance_date"][0] = substr($data["attendance_date"][0], 0,4)."-".substr($data["attendance_date"][0], 4,2)."-".substr($data["attendance_date"][0], 6,2);
                }

                if (!strpos($data["attendance_date"][1], "-")) {
                    $data["attendance_date"][1] = substr($data["attendance_date"][1], 0,4)."-".substr($data["attendance_date"][1], 4,2)."-".substr($data["attendance_date"][1], 6,2);
                }
                
                // var_dump($data);
                $start_date=date_create($data["attendance_date"][0]);
                $end_date=date_create($data["attendance_date"][1]);
                $diff=date_diff($start_date,$end_date);
                // ditambah 1, krn casenya misal 04-03-2024 dan 06-03-2024
                // diffnya itu 06 - 04 = 2 (04,05-03-2024), padahal harusnya 3 hari (04,05,06-03-2024)
                $num_days = intval($diff->format("%a"))+1;
                $add_data = array("input_num_days"=>$num_days,"start_date"=>$data["attendance_date"][0]);
                $postdata = array_merge($postdata,$add_data);
                // var_dump("num_days",$num_days);
            }
            else{
                $add_data = array("attendance_dates"=>$data["attendance_date"]);
                $postdata = array_merge($postdata,$add_data);
            }

            // var_dump('postdata: ',$postdata);

            $postdata = json_encode($postdata);

            // $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/attendance-regular";
            $url = $this->urlOdooHR . 'attendance-regular';

            //set the url, number of POST vars, POST data
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_POST, count($postdata));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);

            //execute post
            ob_start();
            curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $result = ob_get_clean();

            //close connection
            curl_close($ch);

            // var_dump('result: ',$result);

            if ($httpcode == 401) {
                $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
                $this->insertAttendanceOdoo($data);
            }
            else if ($httpcode == 200) {
                $json_decode = json_decode($result, true);
                // var_dump($json_decode);
                $json_decode['SUBRC'] = "0";
                            
                return $json_decode;
            }
            else{
                $json_decode = json_decode($result, true);
                $json_decode['SUBRC'] = "1001";
                $json_decode['FI_ERR'] = $json_decode["message"];
                // var_dump($json_decode);
                return $json_decode;
            }
        }
        else{
            return False;
        }
        
    }

    public function insertAttendanceSAP($data){
        $NIK = $this->NIK;
        $rfc = $this->rfc;

        //insert to sap

        // status_date = 0 -> tanggal per baris.
        // status_date = 1 -> start_date - end_date.
        
        $status_date = $data['status_date'];
        if ($status_date == 0) {
            // var_dump("attendance_date: ",$data['attendance_date']);
            if (is_array($data['attendance_date'])) {
                // var_dump("masuk ke array");
                // buat klo status_datenya = 0 dan datanya berbentuk array (data tidak dilooping sebelumnya)
                $jml_array = count($data['attendance_date']);
                for ($i=0;$i<$jml_array;$i++) {
                    // var_dump($data,$data['type'],$NIK);
                    if (strpos($data["attendance_date"][$i], "-")) {
                        $attendance_date_formatted = explode("-",$data['attendance_date'][$i]);
                        $data['attendance_date'][$i] = $attendance_date_formatted[2].$attendance_date_formatted[1].$attendance_date_formatted[0];
                    }
                    else{
                        $data['attendance_date'][$i] =  substr($data['attendance_date'],6,2).substr($data['attendance_date'],4,2).substr($data['attendance_date'],0,4);
                    }

                    $return = $this->insertAttendanceBAPI($NIK, $data['type'], $data['attendance_date'][$i], $data['attendance_date'][$i]);
                    // var_dump($return);
                    $SUBRC[$i] = $return['SUBRC'];
                    $FI_ERR[$i] = $return["FI_ERR"];

                    // var_dump($attendance_date_formatted);
                    /*$fce = saprfc_function_discover($rfc,"ZHRFM_CREATE_2002");
                    if (! $fce )
                    { echo "Discovering interface of function module failed"; exit; }
                                
                    saprfc_import ($fce,"ATT_TYPE",$data['type']);
                    saprfc_import ($fce,"CHOIC_003","2002");
                    saprfc_import ($fce,"CTU","X");
                    saprfc_import ($fce,"FI_BEGDA",$attendance_date_formatted);
                    saprfc_import ($fce,"FI_ENDDA",$attendance_date_formatted);
                    saprfc_import ($fce,"FI_PERNR",$NIK);
                    saprfc_import ($fce,"GROUP","");
                    saprfc_import ($fce,"HOLDDATE","");
                    saprfc_import ($fce,"KEEP","");
                    saprfc_import ($fce,"MODE","N");
                    saprfc_import ($fce,"NODATA","/");
                    saprfc_import ($fce,"TIMR6_002","X");
                    saprfc_import ($fce,"UPDATE","L");
                    saprfc_import ($fce,"USER","");
                    //Fill internal tables
                    saprfc_table_init ($fce,"MESSTAB");
                    $rfc_rc = saprfc_call_and_receive ($fce);
                    if ($rfc_rc != SAPRFC_OK) 
                    { 
                        if ($rfc == SAPRFC_EXCEPTION ) 
                        echo ("Exception raised: ".saprfc_exception($fce)); 
                        else echo (saprfc_error($fce)); exit; 
                    }

                    //Retrieve export parameters
                    $SUBRC[$i] = saprfc_export ($fce,"SUBRC");
                    $FI_ERR[$i] = saprfc_export ($fce,"FI_ERR");
                    $FI_ERR[$i] = $this->checkErrorMsg($FI_ERR[$i]);
                    $rows = saprfc_table_rows ($fce,"MESSTAB");
                    for ($j=1;$j<=$rows;$j++)
                        $MESSTAB[] = saprfc_table_read ($fce,"MESSTAB",$j);
                                    
                    saprfc_function_free($fce);*/
                }
            }
            else{
                // var_dump("masuk ke non array");
                // buat klo status_datenya = 0 dan datanya bukan array (biasanya ini jln di dlm proses looping di luar)
                if (strpos($data["attendance_date"], "-")) {
                    $attendance_date_formatted = explode("-",$data['attendance_date']);
                    $data['attendance_date'] = $attendance_date_formatted[2].$attendance_date_formatted[1].$attendance_date_formatted[0];
                }
                else{
                    $data['attendance_date'] =  substr($data['attendance_date'],6,2).substr($data['attendance_date'],4,2).substr($data['attendance_date'],0,4);
                }

                $return = $this->insertAttendanceBAPI($NIK, $data['type'], $data['attendance_date'], $data['attendance_date']);
                // var_dump($return);
                $SUBRC = $return['SUBRC'];
                $FI_ERR = $return["FI_ERR"];
            }
        }
        else{
            if (strpos($data["attendance_date"][0], "-")) {
                $attendance_date_exploded = explode("-",$data['attendance_date'][0]);
                $data['attendance_date'][0] = $attendance_date_exploded[2].$attendance_date_exploded[1].$attendance_date_exploded[0];
                // var_dump('dlm_att_date1:',$data['attendance_date']);
            }
            else{
                $data['attendance_date'][0] =  substr($data['attendance_date'][0],6,2).substr($data['attendance_date'][0],4,2).substr($data['attendance_date'][0],0,4);
            }

            if (strpos($data["attendance_date"][1], "-")) {
                $attendance_date_exploded = explode("-",$data['attendance_date'][1]);
                $data['attendance_date'][1] = $attendance_date_exploded[2].$attendance_date_exploded[1].$attendance_date_exploded[0];
                // var_dump('dlm_att_date2:',$data['attendance_date']);
            }
            else{
                $data['attendance_date'][1] =  substr($data['attendance_date'][1],6,2).substr($data['attendance_date'][1],4,2).substr($data['attendance_date'][1],0,4);
            }            

            $return = $this->insertAttendanceBAPI($NIK, $data['type'], $data['attendance_date'][0], $data['attendance_date'][1]);
            $SUBRC = $return['SUBRC'];
            $FI_ERR = $return["FI_ERR"];
            // var_dump($return);

            /*$fce = saprfc_function_discover($rfc,"ZHRFM_CREATE_2002");
            if (! $fce ) 
            { echo "Discovering interface of function module failed"; exit; }
                        
            saprfc_import ($fce,"ATT_TYPE",$data['type']);
            saprfc_import ($fce,"CHOIC_003","2002");
            saprfc_import ($fce,"CTU","X");
            saprfc_import ($fce,"FI_BEGDA",$data['attendance_date'][0]);
            saprfc_import ($fce,"FI_ENDDA",$data['attendance_date'][1]);
            saprfc_import ($fce,"FI_PERNR",$NIK);
            saprfc_import ($fce,"GROUP","");
            saprfc_import ($fce,"HOLDDATE","");
            saprfc_import ($fce,"KEEP","");
            saprfc_import ($fce,"MODE","N");
            saprfc_import ($fce,"NODATA","/");
            saprfc_import ($fce,"TIMR6_002","X");
            saprfc_import ($fce,"UPDATE","L");
            saprfc_import ($fce,"USER","");
            //Fill internal tables
            saprfc_table_init ($fce,"MESSTAB");
            $rfc_rc = saprfc_call_and_receive ($fce);
            if ($rfc_rc != SAPRFC_OK) 
            { 
                if ($rfc == SAPRFC_EXCEPTION ) 
                echo ("Exception raised: ".saprfc_exception($fce)); 
                else echo (saprfc_error($fce)); exit; 
            }

            //Retrieve export parameters
            $SUBRC = saprfc_export ($fce,"SUBRC");
            $FI_ERR = saprfc_export ($fce,"FI_ERR");
            $FI_ERR = $this->checkErrorMsg($FI_ERR);
            $rows = saprfc_table_rows ($fce,"MESSTAB");
            for ($j=1;$j<=$rows;$j++)
                $MESSTAB[] = saprfc_table_read ($fce,"MESSTAB",$j);
                            
            saprfc_function_free($fce);*/
        }

        if(is_array($SUBRC))
        {
            if (in_array('1001', $SUBRC, 1) || in_array(1001, $SUBRC, 1)) {
                $SUBRC = 1001;
            }
            else{
                $SUBRC = 0;
            }
        }

        if (is_array($FI_ERR)) {
            $FI_ERR_keys = array_keys(array_filter($FI_ERR, function($value) {
                return $value !== null;
            }));

            if (count($FI_ERR_keys) > 0) {
                $FI_ERR = $FI_ERR[$FI_ERR_keys[0]];
            }
            else{
                $FI_ERR = "";
            }
        }

        return $data = array("SUBRC"=>$SUBRC,"FI_ERR"=>$FI_ERR);
    }
    /*------------------ INSERT ATTENDANCE ---------------------*/

}
?>
