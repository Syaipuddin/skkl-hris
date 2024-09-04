<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ESSlibrary2
{
    public $conn = "";
    public $rfc = "";
    public $NIK = "";
    public function __construct()
    {
        include "include/connection.php";
        include "include/sapconnect.php";
        include_once "include/check_Flag.php";
        $this->conn = $conn;
        $this->rfc = $rfc;
    }

    /*--------------- GET ODOO TOKEN ---------------------*/
    public function getOdooToken($user = NULL, $password = NULL)
    {
        // get TOKEN ODOOO

        $ch = curl_init();

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-type: application/json'
            )
        );

        $postdata = array(
            'login' => $user,
            "password" => $password,
        );

        $postdata = json_encode($postdata);

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, "https://kg-pms-odoo-dev1.mykg.id/kg/api/auth/user/token");
        curl_setopt($ch, CURLOPT_POST, count($postdata));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

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

    /*------------------ GET CONTACT ---------------------*/
    public function contact($data)
    {
        $this->NIK = $data['NIK'];
        $flag_odoo = check_access_odoo($data['NIK']);

        // var_dump("data:",$data);

        if ($flag_odoo == 1) {
            $data = $this->ContactOdoo($data);
        } else {
            $data = $this->ContactSAP($data);
        }

        return $data;
    }


    public function ContactOdoo($NIK)
    {
        // $token = $this->getOdooToken('admin', 'admin');
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        } else {
            $token = $this->getOdooToken('admin', 'admin');
        }

        $ch = curl_init();

        $authorization = "Authorization: Bearer $token";

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                $authorization
            )
        );

        $param = array(
            "nik" => $data['NIK'],
            "profile_type" => $data["contact"]
        );

        $param = http_build_query($param);

        $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/employee-profile?" . $param;

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
            $token = $this->getOdooToken('admin', 'admin');
            $this->ContactOdoo($NIK);
        } else if ($httpcode == 200) {

            $json_decode = json_decode($result, true);

            // var_dump('result: ',$json_decode['data'][0]['parent_id'][1]);
            // flag odoo = 1
            // $index_name = $this->getIndexNameAttendance(1);
            // $json_decode['index_name'] = $index_name;

            $json_decode['rows'] = count($json_decode['data']);

            return $json_decode;
        } else {
            return False;
        }

    }


    public function ContactSAP()
    {
        $NIK = $this->NIK;
        $rfc = $this->rfc;
        //GET CHIEF
        $fce = saprfc_function_discover($rfc, "ZHRFM_GETCONTACT");
        if (!$fce) {
            echo "Discovering interface of function module failed";
            exit;
        }
        //Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
        saprfc_import($fce, "FI_PERNR", $NIK);
        //Fill internal tables
        saprfc_table_init($fce, "FE_HRP1000");
        //Do RFC call of function ZHRFM_GETCONTACT, for handling exceptions use saprfc_exception()
        $rfc_rc = saprfc_call_and_receive($fce);
        if ($rfc_rc != SAPRFC_OK) {
            if ($rfc == SAPRFC_EXCEPTION)
                echo ("Exception raised: " . saprfc_exception($fce));
            else
                echo (saprfc_error($fce));
            exit;
        }
        $rows = saprfc_table_rows($fce, "FE_HRP1000");

        // flag odoo = 0
        // $index_name = $this->getIndexNameAttendance(0);

        $return = array("fce" => $fce, "rows" => $rows/*, "index_name"=>$index_name*/);
        return $return;
    }
    /*------------------ GET CONTACT ---------------------*/