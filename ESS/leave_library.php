<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
include "library.php";

class Leavelibrary extends ESSlibrary
{
    public $conn = "";
    public $rfc = "";
    public $NIK = "";
    public $urlOdooHR = "https://hr.mykg.id/kg/api/hr/";
    public $urlOdooBase = "https://hr.mykg.id/";
    public $userOdoo = ODOO_USER_PORTAL_PROD;
    public $passwdOdoo = ODOO_PASSWD_PORTAL_PROD;
    public function __construct()
    {
        include "include/connection.php";
        include "include/sapconnect.php";
        include_once "include/check_Flag.php";
        include_once "language/credentials_constant.php";
        $this->conn = $conn;
        $this->rfc = $rfc;
        $this->urlOdooHR = "https://hr.mykg.id/kg/api/hr/";
        $this->urlOdooBase = "https://hr.mykg.id/";
        $this->userOdoo = ODOO_USER_PORTAL_PROD;
        $this->passwdOdoo = ODOO_PASSWD_PORTAL_PROD;
    }


    /*--------------- GET List Cuti -------------------*/
    // ini buat yg hny gimmic aja, ckup 1 row existed, then do something
    // klo yg history buat beneran dptin setiap row historynya
    public function getlistCuti($data)
    {

        $this->NIK = $data["NIK"];
        $flag_odoo = check_access_odoo($data["NIK"]);

        if ($flag_odoo == 1) {
            $data = $this->getlistCutiOdoo($data);
        } else {
            $data = $this->getlistCutiSAP($data);
        }

        return $data;
    }


    public function getlistCutiOdoo($data){
        return array("rows" => "0");
    }

    public function getlistCutiSAP($data){

        /*if (strpos($data["leave_date"], "-")) {
            $leave_date_formatted = explode("-",$data['leave_date']);
            $data['leave_date'] = $leave_date_formatted[0].$leave_date_formatted[1].$leave_date_formatted[2];
        }
        else{
            $data['leave_date'] =  substr($data['leave_date'],0,4).substr($data['leave_date'],4,2).substr($data['leave_date'],6,2);
        }*/

        $rfc = $this->rfc;
        $fce = saprfc_function_discover($rfc,"ZHRFM_LISTCUTI");
        if (! $fce )
        {
                echo "System Error. Please Try Again Later."; exit;
        }
        saprfc_import ($fce,"FI_PERNR",$data["d"]);
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

        return array("rows" => $rows, "fce" => $fce);

        // var_dump($fce);
        // if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
        // saprfc_function_free($fce);
        
    }
    /*------------------ GET list CUTI -------------------------*/



    /*------------------ GET WORK PERIOD (MASA KERJA) ---------------------*/
    public function getWorkPeriod($NIK)
    {
        $this->NIK = $NIK;
        $flag_odoo = check_access_odoo($NIK);

        if ($flag_odoo == 1) {
            $data = $this->getWorkPeriodOdoo($NIK);
        } else {
            $data = $this->getWorkPeriodSAP($NIK);
        }

        return $data;
    }


    public function getIndexNameWorkPeriod($flag_odoo)
    {
        if ($flag_odoo) {
            $index_name['FI_BULANMASAKERJA'] = 'FI_BULANMASAKERJA';
            $index_name['FI_BULANMASUK'] = 'FI_BULANMASUK';
            $index_name['FI_TAHUNMASAKERJA'] = 'tahun_masa_kerja';
            $index_name['FI_TAHUNMASUK'] = 'tahun_masuk';

            /*$index_name['BEGDA'] = 'leave_absence_date';
            $index_name['UNAME'] = 'input_user';
            $index_name['AEDTM'] = 'input_date';
            $index_name['SUBTY_TXT'] = 'leave_absence_type';*/
        } else {
            $index_name['FI_BULANMASAKERJA'] = 'FI_BULANMASAKERJA';
            $index_name['FI_BULANMASUK'] = 'FI_BULANMASUK';
            $index_name['FI_TAHUNMASAKERJA'] = 'FI_TAHUNMASAKERJA';
            $index_name['FI_TAHUNMASUK'] = 'FI_TAHUNMASUK';
        }
        return $index_name;
    }

    public function getWorkPeriodOdoo($NIK)
    {
        // $token = $this->getOdooToken('admin', 'admin');
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        } else {
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
        }

        $ch = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization
        ));

        $today = date("Y-m-d");

        $postdata = array(
            'nik' => $NIK
        );
        $postdata = http_build_query($postdata);
        // $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/work-period?" . $postdata;
        $url = $this->urlOdooHR . 'work-period?'. $postdata;

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        //execute post
        ob_start();
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = ob_get_clean();

        //close connection
        curl_close($ch);

        if ($httpcode == 401) {
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
            $this->getWorkPeriodOdoo($NIK);
        } else if ($httpcode == 200) {
            $json_decode = json_decode($result, true);
            // flag odoo = 1
            /*$index_name = $this->getIndexNameLeaveBalance(1);
            $json_decode['index_name'] = $index_name;*/
            $json_decode["FI_TAHUNMASAKERJA"] = $json_decode["tahun_masa_kerja"];
            $json_decode["FI_TAHUNMASUK"] = $json_decode["tahun_masuk"];

            // var_dump("json_decode",$json_decode);

            $json_decode['rows'] = count($json_decode);

            return $json_decode;
        } else {
            return False;
        }
    }

    public function getWorkPeriodSAP()
    {
        $NIK = $this->NIK;
        $rfc = $this->rfc;
         $fce = saprfc_function_discover($rfc,"ZHRFM_LISTSISACUTI");
        if (! $fce )
        {
                echo "System Error. Please Try Again Later."; exit;
        }
        saprfc_import ($fce,"FI_PERNR",$NIK);
        saprfc_table_init ($fce,"FI_SALDO");
        $rfc_rc = saprfc_call_and_receive ($fce);
        if ($rfc_rc != SAPRFC_OK)
        {
                if ($rfc == SAPRFC_EXCEPTION )
                        echo "System Error. Please Try Again Later.";
                else
                        echo "System Error. Please Try Again Later."; exit;
        }

        $FI_BULANMASAKERJA = saprfc_export ($fce,"FI_BULANMASAKERJA");
        $FI_BULANMASUK = saprfc_export ($fce,"FI_BULANMASUK");
        $FI_TAHUNMASAKERJA = saprfc_export ($fce,"FI_TAHUNMASAKERJA");
        $FI_TAHUNMASUK = saprfc_export ($fce,"FI_TAHUNMASUK");
        $rows = saprfc_table_rows ($fce,"FI_SALDO");

        // flag odoo = 0
        // $index_name = $this->getIndexNameWorkPeriod(0);

        $return = array("fce" => $fce, "rows" => $rows, /*"index_name" => $index_name, */"FI_BULANMASAKERJA"=>$FI_BULANMASAKERJA, "FI_BULANMASUK"=>$FI_BULANMASUK,"FI_TAHUNMASAKERJA"=>$FI_TAHUNMASAKERJA,"FI_TAHUNMASUK"=>$FI_TAHUNMASUK);
        return $return;
    }
    /*------------------ GET WORK PERIOD (MASA KERJA) ---------------------*/


    /*------------------ GET LEAVE BALANCE ---------------------*/
    public function getLeaveBalance($NIK)
    {
        $this->NIK = $NIK;
        $flag_odoo = check_access_odoo($NIK);

        if ($flag_odoo == 1) {
            $data = $this->getLeaveBalanceOdoo($NIK);
        } else {
            $data = $this->getLeaveBalanceSAP($NIK);
        }

        return $data;
    }


    public function getIndexNameLeaveBalance($flag_odoo)
    {
        if ($flag_odoo) {
            $index_name['PERNR'] = 'NIK';
            $index_name['CNAME'] = 'CNAME';
            $index_name['CUTAH'] = 'annual_max_request_balance';
            $index_name['CUBES'] = 'long_max_request_balance';
            $index_name['CUBESKE'] = 'long_leave_type';
            $index_name['BEGDA'] = 'BEGDA';
            $index_name['ENDDA'] = 'long_leave_valid_to';

            /*$index_name['BEGDA'] = 'leave_absence_date';
            $index_name['UNAME'] = 'input_user';
            $index_name['AEDTM'] = 'input_date';
            $index_name['SUBTY_TXT'] = 'leave_absence_type';*/
        } else {
            $index_name['PERNR'] = 'PERNR';
            $index_name['CNAME'] = 'CNAME';
            $index_name['CUTAH'] = 'CUTAH';
            $index_name['CUBES'] = 'CUBES';
            $index_name['CUBESKE'] = 'CUBESKE';
            $index_name['BEGDA'] = 'BEGDA';
            $index_name['ENDDA'] = 'ENDDA';
        }
        return $index_name;
    }

    public function getLeaveBalanceOdoo($NIK)
    {
        // $token = $this->getOdooToken('admin', 'admin');
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        } else {
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
        }

        $ch = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization
        ));

        $today = date("Y-m-d");

        $postdata = array(
            'nik' => $NIK
        );
        $postdata = http_build_query($postdata);
        // $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/leave-balances?" . $postdata;
        $url = $this->urlOdooHR . 'leave-balances?'. $postdata;

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        //execute post
        ob_start();
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = ob_get_clean();

        //close connection
        curl_close($ch);

        if ($httpcode == 401) {
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
            $this->getLeaveBalanceOdoo($NIK);
        } else if ($httpcode == 200) {
            $json_decode = json_decode($result, true);
            // flag odoo = 1
            $index_name = $this->getIndexNameLeaveBalance(1);
            $json_decode['index_name'] = $index_name;

            $long_leave_type_explode = explode("-", $json_decode['long_leave_type']);
            if (count($long_leave_type_explode)>1) {
                $json_decode['long_leave_type'] = $long_leave_type_explode[1];
            }
            

            $json_decode['rows'] = count($json_decode);

            return $json_decode;
        } else {
            return False;
        }
    }

    public function getLeaveBalanceSAP()
    {
        $NIK = $this->NIK;
        $rfc = $this->rfc;
        $fce = saprfc_function_discover($rfc, "ZHRFM_LISTSISACUTI");
        if (!$fce) {
            echo "System Error. Please Try Again Later.";
            exit;
        }
        saprfc_import($fce, "FI_PERNR", $NIK);
        saprfc_table_init($fce, "FI_SALDO");
        $rfc_rc = saprfc_call_and_receive($fce);
        if ($rfc_rc != SAPRFC_OK) {
            if ($rfc == SAPRFC_EXCEPTION)
                echo "System Error. Please Try Again Later.";
            else
                echo "System Error. Please Try Again Later.";
            exit;
        }

        $rows = saprfc_table_rows($fce, "FI_SALDO");

        // flag odoo = 0
        $index_name = $this->getIndexNameLeaveBalance(0);

        $return = array("fce" => $fce, "rows" => $rows, "index_name" => $index_name);
        return $return;
    }
    /*------------------ GET LEAVE BALANCE ---------------------*/


    /*------------------ GET HISTORY LEAVE ---------------------*/
    public function getHistoryLeave($NIK, $month, $year)
    {
        $this->NIK = $NIK;
        $flag_odoo = check_access_odoo($NIK);

        if ($flag_odoo == 1) {
            $data = $this->getHistoryLeaveOdoo($NIK, $month, $year);
        } else {
            $data = $this->getHistoryLeaveSAP($NIK, $month, $year);
        }

        return $data;
    }


    public function getIndexNameLeave($flag_odoo)
    {
        if ($flag_odoo) {
            $index_name['BEGDA'] = 'leave_absence_date';
            $index_name['UNAME'] = 'input_user';
            $index_name['AEDTM'] = 'input_date';
            $index_name['SUBTY_TXT'] = 'leave_absence_type';
        } else {
            $index_name['BEGDA'] = 'BEGDA';
            $index_name['UNAME'] = 'UNAME';
            $index_name['AEDTM'] = 'AEDTM';
            $index_name['SUBTY'] = 'SUBTY';
        }
        return $index_name;
    }

    public function getHistoryLeaveOdoo($NIK, $month, $year)
    {
        // $token = $this->getOdooToken('admin', 'admin');
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        } else {
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
            "month" => $month,
            "year" => $year,
            "type" => "leave"
        );
        $postdata = http_build_query($postdata);
        // $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/leave-absence?" . $postdata;
        $url = $this->urlOdooHR . 'leave-absence?'. $postdata;

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        //execute post
        ob_start();
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = ob_get_clean();

        //close connection
        curl_close($ch);

        if ($httpcode == 401) {
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
            $this->getHistoryLeaveOdoo($NIK, $month, $year);
        } else if ($httpcode == 200) {
            $json_decode = json_decode($result, true);
            // flag odoo = 1
            $index_name = $this->getIndexNameLeave(1);
            $json_decode['index_name'] = $index_name;

            $json_decode['rows'] = count($json_decode['data']);

            return $json_decode;
        } else {
            return False;
        }
    }

    public function getHistoryLeaveSAP()
    {
        $NIK = $this->NIK;
        $rfc = $this->rfc;
        $fce = saprfc_function_discover($rfc, "ZHRFM_LISTCUTI");
        if (!$fce) {
            echo "System Error. Please Try Again Later.";
            exit;
        }
        saprfc_import($fce, "FI_PERNR", $NIK);
        saprfc_table_init($fce, "FI_CT");
        $rfc_rc = saprfc_call_and_receive($fce);
        if ($rfc_rc != SAPRFC_OK) {
            if ($rfc == SAPRFC_EXCEPTION)
                echo "System Error. Please Try Again Later.";
            else
                echo "System Error. Please Try Again Later.";
            exit;
        }

        $rows = saprfc_table_rows($fce, "FI_CT");

        // flag odoo = 0
        $index_name = $this->getIndexNameLeave(0);

        $return = array("fce" => $fce, "rows" => $rows, "index_name" => $index_name);
        return $return;
    }
    /*------------------ GET HISTORY LEAVE ---------------------*/



    /*------------------ INSERT LEAVE ---------------------*/
    public function addLeave($data)
    {
        $this->NIK = $data["NIK"];
        $flag_odoo = check_access_odoo($data["NIK"]);

        if ($flag_odoo == 1) {
            $data = $this->addLeaveOdoo($data);
        } else {
            $data = $this->addLeaveSAP($data);
        }

        return $data;
    }


    public function addLeaveOdoo($data)
    {
        // $token = $this->getOdooToken('admin', 'admin');
        // var_dump('masuk ke insert odoo loh');
        $NIK = $this->NIK;
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        } else {
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

            $postdata = array(
                'nik' => $NIK,
                "leave_absence_type" => $data["type"],
                "leave_absence_reason" => ($data["reason"] != "") ? $data["reason"] : $data["type"],
                "input_date" => date("Y-m-d H:i:s", strtotime($data["input_date"])),
                "approver_nik" => $data["approver"]
            );

            $jml_array = count($data['leave_absence_dates']);
            // var_dump('leave_absence_reason:',$postdata);
            $status_date = $data['status_date'];
            if ($status_date == 1) {
                // var_dump($data["leave_absence_dates"]);

                if (strpos($data["leave_absence_dates"][0], "-")===false) {
                    $data["leave_absence_dates"][0] = substr($data["leave_absence_dates"][0], 6, 2) . "-" . substr($data["leave_absence_dates"][0], 4, 2) . "-" . substr($data["leave_absence_dates"][0], 0, 4);
                }

                if (strpos($data["leave_absence_dates"][1], "-")===false) {
                    $data["leave_absence_dates"][1] = substr($data["leave_absence_dates"][1], 6, 2) . "-" . substr($data["leave_absence_dates"][1], 4, 2) . "-" . substr($data["leave_absence_dates"][1], 0, 4);
                }

                // var_dump($data);
                $start_date = date_create($data["leave_absence_dates"][0]);
                $end_date = date_create($data["leave_absence_dates"][1]);
                $diff = date_diff($start_date, $end_date);
                // ditambah 1, krn casenya misal 04-03-2024 dan 06-03-2024
                // diffnya itu 06 - 04 = 2 (04,05-03-2024), padahal harusnya 3 hari (04,05,06-03-2024)
                $num_days = intval($diff->format("%a")) + 1;
                $add_data = array("input_num_days" => $num_days, "start_date" => $data["leave_absence_dates"][0]);
                $postdata = array_merge($postdata, $add_data);
                // var_dump("num_days",$num_days);
            } else {
                $add_data = array("leave_absence_dates" => $data["leave_absence_dates"]);
                $postdata = array_merge($postdata, $add_data);
            }
            // var_dump('postdata_insert:',$postdata);

            $postdata = json_encode($postdata);
            $urlLeaveOdoo = $this->urlOdooHR . 'leave-absence';
            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $urlLeaveOdoo);
            curl_setopt($ch, CURLOPT_POST, count($postdata));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

            //execute post
            ob_start();
            curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $result = ob_get_clean();

            //close connection
            curl_close($ch);

            // var_dump('httpcode: ', $httpcode);

            if ($httpcode == 401) {
                $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
                $this->getHistoryLeaveOdoo($NIK, $month, $year);
            } else if ($httpcode == 200) {
                $json_decode = json_decode($result, true);
                $json_decode['SUBRC'] = "0";

                return $json_decode;
            } else {
                $json_decode = json_decode($result, true);
                $json_decode['SUBRC'] = "1001";
                $json_decode['FI_ERR']["MESSAGE"] = $json_decode["message"];
                // var_dump('json_decode: ',$json_decode);
                return $json_decode;
            }
        } else {
            return False;
        }
    }

    public function addLeaveSAP($data)
    {
        $NIK = $this->NIK;
        $rfc = $this->rfc;

        //insert to sap

        // status_date = 0 -> tanggal per baris.
        // status_date = 1 -> start_date - end_date.

        $status_date = $data['status_date'];
        if ($status_date == 0) {
            if (is_array($data['leave_absence_dates'])) {
                // var_dump("masuk ke array");
                // buat klo status_datenya = 0 dan datanya berbentuk array (data tidak dilooping sebelumnya)
                $jml_array = count($data['leave_absence_dates']);
                for ($i = 0; $i < $jml_array; $i++) {
                    // var_dump($data,$data['type'],$NIK);
                    if (strpos($data["leave_absence_dates"][$i], "-")) {
                        $leave_date_formatted = explode("-", $data['leave_absence_dates'][$i]);
                        $data['leave_absence_dates'][$i] = $leave_date_formatted[0] . $leave_date_formatted[1] . $leave_date_formatted[2];
                    } else {
                        $data['leave_absence_dates'][$i] =  substr($data['leave_absence_dates'], 0, 4) . substr($data['leave_absence_dates'], 4, 2) . substr($data['leave_absence_dates'], 6, 2);
                    }

                    $return = $this->addLeaveToSAP(
                        $NIK,
                        $data['type'],
                        $data['leave_absence_dates'][$i],
                        $data['leave_absence_dates'][$i]
                    );
                    // var_dump($return);
                    $SUBRC[$i] = $return['SUBRC'];
                    $FI_ERR[$i] = $return["FI_ERR"];
                }
            } else {
                // var_dump("masuk ke non array");
                // buat klo status_datenya = 0 dan datanya bukan array (biasanya ini jln di dlm proses looping di luar)
                if (strpos($data["leave_absence_dates"], "-")) {
                    $leave_date_formatted = explode("-", $data['leave_absence_dates']);
                    $data['leave_absence_dates'] = $leave_date_formatted[0] . $leave_date_formatted[1] . $leave_date_formatted[2];
                } else {
                    $data['leave_absence_dates'] =  substr($data['leave_absence_dates'], 0, 4) . substr($data['leave_absence_dates'], 4, 2) . substr($data['leave_date'], 6, 2);
                }

                $return = $this->addLeaveToSAP($NIK, $data['type'], $data['leave_absence_dates'], $data['leave_absence_dates']);
                // var_dump($return);
                $SUBRC = $return['SUBRC'];
                $FI_ERR = $return["FI_ERR"];
            }
        } else {
            if (strpos($data["leave_absence_dates"][0], "-")) {
                $leave_date_exploded = explode("-", $data['leave_absence_dates'][0]);
                $data['leave_absence_dates'][0] = $leave_date_exploded[0] . $leave_date_exploded[1] . $leave_date_exploded[2];
            } else {
                $data['leave_absence_dates'][0] =  substr($data['leave_absence_dates'], 0, 4) . substr($data['leave_absence_dates'], 4, 2) . substr($data['leave_absence_dates'], 6, 2);
            }

            if (strpos($data["leave_absence_dates"][1], "-")) {
                $leave_date_exploded = explode("-", $data['leave_absence_dates'][1]);
                $data['leave_absence_dates'][1] = $leave_date_exploded[0] . $leave_date_exploded[1] . $leave_date_exploded[2];
            } else {
                $data['leave_absence_dates'][1] =  substr($data['leave_absence_dates'], 0, 4) . substr($data['leave_absence_dates'], 4, 2) . substr($data['leave_absence_dates'], 6, 2);
            }

            $return = $this->addLeaveToSAP($NIK, $data['type'], $data['leave_absence_dates'][0], $data['leave_absence_dates'][1]);
            $SUBRC = $return['SUBRC'];

            $FI_ERR = $return["FI_ERR"];
        }

        if (is_array($SUBRC)) {
            if (in_array('1001', $SUBRC, 1) || in_array(1001, $SUBRC, 1)) {
                $SUBRC = 1001;
            } else {
                $SUBRC = 0;
            }
        }

        return $data = array("SUBRC" => $SUBRC, "FI_ERR" => $FI_ERR);
    }

    public function addLeaveToSAP($NIK, $leaveType, $begda, $endda)
    {

        // khusus 1003 atau cuti melahirkan, FI_AKHIR hrs diisi = FI_AWAL
        if ($leaveType == '1003') {
            $endda = $begda;
        }

        $rfc = $this->rfc;
        $fce = saprfc_function_discover($rfc, "ZHRFM_CREATE_2001");
        // var_dump($NIK, $leaveType, $begda, $endda);

        if (!$fce) {
            echo "Discovering interface of function module failed";
            exit;
        }

        saprfc_import($fce, "FI_AKHIR", $endda);
        saprfc_import($fce, "FI_AWAL", $begda);
        saprfc_import($fce, "FI_PERNR", $NIK);
        saprfc_import($fce, "FI_SUBTYPE", $leaveType);
        saprfc_table_init($fce, "FI_HASIL");
        $rfc_rc = saprfc_call_and_receive($fce);
        if ($rfc_rc != SAPRFC_OK) {
            if ($rfc == SAPRFC_EXCEPTION)
                echo "System Error. Please Try Again Later.";
            else
                echo "System Error. Please Try Again Later.";
            exit;
        }

        //Retrieve export parameters
        // $SUBRC = saprfc_export($fce, "SUBRC");
        /*$FI_ERR = saprfc_export($fce, "FI_ERR");
        $FI_ERR = $this->checkErrorMsg($FI_ERR);*/
        $rows = saprfc_table_rows($fce, "FI_HASIL");
        // print_r($rows);
        // for ($j = 1; $j <= $rows; $j++)
        //     $MESSTAB[] = saprfc_table_read($fce, "FI_HASIL", $j);

        if ($rows == 0) {
            // echo 'berhasil';
            $SUBRC = "0";
            $FI_ERR = "MESSAGE";
        } else {
            // echo 'tidak berhasil';
            $SUBRC = "1001";
            $FI_ERR = saprfc_table_read($fce, "FI_HASIL", 1);;
        }

        saprfc_function_free($fce);
        // $FI_ERR = saprfc_table_read($fce, "FI_HASIL", $rows);
        // $FI_ERR = $FI_ERR["MESSAGE"];
        // $FI_ERR = $FI_ERR["MESSAGE"];
        return array("SUBRC" => $SUBRC, "FI_ERR" => $FI_ERR);
    }

    /* --  END LEAVE -- */



    /*------------------ DELETE LEAVE ---------------------*/

    public function deleteAfterLeave($data)
    {

        $this->NIK = $data["NIK"];
        $flag_odoo = check_access_odoo($data["NIK"]);
        // var_dump("masuk 2x ga?");

        if ($flag_odoo == 1) {
            $data = $this->deleteAfterLeaveOdoo($data);
        } else {
            $data = $this->deleteAfterLeaveSAP($data);
        }

        return $data;
    }

    public function deleteAfterLeaveOdoo($data){
        $NIK = $this->NIK;
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        } else {
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
        }

        if (isset($data)) {
            $ch = curl_init();

            $authorization = "Authorization: Bearer $token";

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                $authorization,
                'Content-type: application/json'
            ));

            $patchdata = array(
                'nik' => $NIK,
                "cancel_approval_nik" => $data["approver"]
            );

            $jml_array = count($data['leave_absence_date']);
            $status_date = $data['status_date'];
            // var_dump("leave_absence_date: ",$data["leave_absence_date"]);
            if ($status_date == 1) {
                // var_dump("leave_absence_date: ",$data["leave_absence_date"][0]);
                if (strpos($data["leave_absence_date"][0], "-")===false) {
                    $data["leave_absence_date"][0] = substr($data["leave_absence_date"][0], 6, 2) . "-" . substr($data["leave_absence_date"][0], 4, 2) . "-" . substr($data["leave_absence_date"][0], 0, 4);
                    // var_dump("test1");
                }

                if (strpos($data["leave_absence_date"][1], "-")===false) {
                    $data["leave_absence_date"][1] = substr($data["leave_absence_date"][1], 6, 2) . "-" . substr($data["leave_absence_date"][1], 4, 2) . "-" . substr($data["leave_absence_date"][1], 0, 4);
                    // var_dump("test2");
                }

                // var_dump($data);
                $start_date = date_create($data["leave_absence_date"][0]);
                $end_date = date_create($data["leave_absence_date"][1]);
                $diff = date_diff($start_date, $end_date);
                // ditambah 1, krn casenya misal 04-03-2024 dan 06-03-2024
                // diffnya itu 06 - 04 = 2 (04,05-03-2024), padahal harusnya 3 hari (04,05,06-03-2024)
                $num_days = intval($diff->format("%a")) + 1;
                $add_data = array("leave_absence_date" => $data["leave_absence_date"][0]);
                $patchdata = array_merge($patchdata, $add_data);
                // var_dump("num_days",$num_days);
            } else {
                $add_data = array("leave_absence_date" => $data["leave_absence_date"][0]);
                $patchdata = array_merge($patchdata, $add_data);
            }

            // var_dump('patchdata:',$patchdata);

            $patchdata = json_encode($patchdata);
            $urlLeaveOdoo = $this->urlOdooHR . 'leave-absence-cancellation';
            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $urlLeaveOdoo);
            curl_setopt($ch, CURLOPT_POST, count($patchdata));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $patchdata);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

            //execute post
            ob_start();
            curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $result = ob_get_clean();

            //close connection
            curl_close($ch);

            if ($httpcode == 401) {
                $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
                $this->deleteAfterLeaveOdoo($NIK, $month, $year);
            } else if ($httpcode == 200) {
                $json_decode = json_decode($result, true);
                $json_decode['SUBRC'] = "0";
                // var_dump('json_decode: ',$json_decode);
                return $json_decode;
            } else {
                $json_decode = json_decode($result, true);
                $json_decode['SUBRC'] = "1001";
                $json_decode['FI_ERR'] = $json_decode["message"];
                // var_dump('json_decode: ',$json_decode);
                return $json_decode;
            }
        } else {
            return False;
        }
    }

    public function deleteAfterLeaveSAP($data){
        $NIK = $this->NIK;
        $rfc = $this->rfc;

        //insert to sap

        // status_date = 0 -> tanggal per baris.
        // status_date = 1 -> start_date - end_date.

        $status_date = $data['status_date'];
        if ($status_date == 0) {
            if (is_array($data['leave_absence_date'])) {
                // var_dump("masuk ke array");
                // buat klo status_datenya = 0 dan datanya berbentuk array (data tidak dilooping sebelumnya)
                $jml_array = count($data['leave_absence_date']);
                for ($i = 0; $i < $jml_array; $i++) {
                    // var_dump($data,$data['type'],$NIK);
                    if (strpos($data["leave_absence_date"][$i], "-")) {
                        $leave_date_formatted = explode("-", $data['leave_absence_date'][$i]);
                        $data['leave_absence_date'][$i] = $leave_date_formatted[0] . $leave_date_formatted[1] . $leave_date_formatted[2];
                    } else {
                        $data['leave_absence_date'][$i] =  substr($data['leave_absence_date'], 0, 4) . substr($data['leave_absence_date'], 4, 2) . substr($data['leave_absence_date'], 6, 2);
                    }

                    $return = $this->deleteAfterLeaveToSAP(
                        $NIK,
                        $data['type'],
                        $data['leave_absence_date'][$i],
                        $data['leave_absence_date'][$i]
                    );
                    // var_dump($return);
                    $SUBRC[$i] = $return['SUBRC'];
                    $FI_ERR[$i] = $return["FI_ERR"];
                }
            } else {
                // var_dump("masuk ke non array");
                // buat klo status_datenya = 0 dan datanya bukan array (biasanya ini jln di dlm proses looping di luar)
                if (strpos($data["leave_absence_date"], "-")) {
                    $leave_date_formatted = explode("-", $data['leave_absence_date']);
                    $data['leave_absence_date'] = $leave_date_formatted[0] . $leave_date_formatted[1] . $leave_date_formatted[2];
                } else {
                    $data['leave_absence_date'] =  substr($data['leave_absence_date'], 0, 4) . substr($data['leave_absence_date'], 4, 2) . substr($data['leave_absence_date'], 6, 2);
                }

                $return = $this->deleteAfterLeaveToSAP($NIK, $data['type'], $data['leave_absence_date'], $data['leave_absence_date']);
                // var_dump($return);
                $SUBRC = $return['SUBRC'];
                $FI_ERR = $return["FI_ERR"];
            }
        } else {
            if (strpos($data["leave_absence_date"][0], "-")) {
                $leave_date_exploded = explode("-", $data['leave_absence_date'][0]);
                $data['leave_absence_date'][0] = $leave_date_exploded[0] . $leave_date_exploded[1] . $leave_date_exploded[2];
            } else {
                $data['leave_absence_date'][0] =  substr($data['leave_absence_date'], 0, 4) . substr($data['leave_absence_date'], 4, 2) . substr($data['leave_absence_date'], 6, 2);
            }

            if (strpos($data["leave_absence_date"][1], "-")) {
                $leave_date_exploded = explode("-", $data['leave_absence_date'][1]);
                $data['leave_absence_date'][1] = $leave_date_exploded[0] . $leave_date_exploded[1] . $leave_date_exploded[2];
            } else {
                $data['leave_absence_date'][1] =  substr($data['leave_absence_date'], 0, 4) . substr($data['leave_absence_date'], 4, 2) . substr($data['leave_absence_date'], 6, 2);
            }

            $return = $this->deleteAfterLeaveToSAP($NIK, $data['type'], $data['leave_absence_date'][0], $data['leave_absence_date'][1]);
            $SUBRC = $return['SUBRC'];

            $FI_ERR = $return["FI_ERR"];
        }

        if (is_array($SUBRC)) {
            if (in_array('1001', $SUBRC, 1) || in_array(1001, $SUBRC, 1)) {
                $SUBRC = 1001;
            } else {
                $SUBRC = 0;
            }
        }

        return $data = array("SUBRC" => $SUBRC, "FI_ERR" => $FI_ERR);
    }

    public function deleteAfterLeaveToSAP($NIK, $leaveType, $begda, $endda)
    {
        $rfc = $this->rfc;
        $fce = saprfc_function_discover($rfc,"ZHRFM_DELETE_CUTI");
        if (! $fce )
        {
            echo "System Error. Please Try Again Later."; exit;
        }
        saprfc_import ($fce,"FI_AKHIR",$endda);
        saprfc_import ($fce,"FI_AWAL",$begda);
        saprfc_import ($fce,"FI_PERNR",$NIK);
        saprfc_import ($fce,"FI_SUBTYPE",$leaveType);
        $rfc_rc = saprfc_call_and_receive ($fce);
        $SUBRC = "0";
        $FI_ERR = "MESSAGE";
        // var_dump($rfc_rc);
        return array("SUBRC" => $SUBRC, "FI_ERR" => $FI_ERR, "fce"=>$fce);
        // var_dump($fce);
        // if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
        // saprfc_function_free($fce);
    }

    /* -- END LEAVE -- */
 
    public function deleteLeave($data)
    {

        $this->NIK = $data["NIK"];
        $flag_odoo = check_access_odoo($data["NIK"]);

        if ($flag_odoo == 1) {
            $data = $this->deleteLeaveOdoo($data);
        } else {
            $data = $this->deleteLeaveSAP($data);
        }

        return $data;
    }


    public function deleteLeaveOdoo($data){
        $json_decode['SUBRC'] = "0";
        return $json_decode;
    }

    public function deleteLeaveSAP($data){

        if (strpos($data["leave_absence_dates"], "-")) {
            $leave_date_formatted = explode("-",$data['leave_absence_dates']);
            $data['leave_absence_dates'] = $leave_date_formatted[0].$leave_date_formatted[1].$leave_date_formatted[2];
        }
        else{
            $data['leave_absence_dates'] =  substr($data['leave_absence_dates'],0,4).substr($data['leave_absence_dates'],4,2).substr($data['leave_absence_dates'],6,2);
        }

        $rfc = $this->rfc;
        $fce = saprfc_function_discover($rfc,"ZHRFM_DELETE_CUTI");
        if (! $fce )
        {
        	echo "System Error. Please Try Again Later."; exit;
        }
		saprfc_import ($fce,"FI_AKHIR",$data['leave_absence_dates']);
		saprfc_import ($fce,"FI_AWAL",$data['leave_absence_dates']);
		saprfc_import ($fce,"FI_PERNR",$data['NIK']);
		saprfc_import ($fce,"FI_SUBTYPE",$data['type']);
		$rfc_rc = saprfc_call_and_receive ($fce);
        $SUBRC = "0";
        $FI_ERR = "MESSAGE";
        // var_dump($rfc_rc);
        return array("SUBRC" => $SUBRC, "FI_ERR" => $FI_ERR, "fce"=>$fce);
        // var_dump($fce);
		// if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
		// saprfc_function_free($fce);
		
    }

    /* -- END LEAVE -- */
}
