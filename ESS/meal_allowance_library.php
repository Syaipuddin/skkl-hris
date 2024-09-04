<?php
include "library.php";

class Meallibrary extends ESSlibrary
{
    public $conn = "";
    public $rfc = "";
    public $NIK = "";
    public $urlOdoo = "https://hr.mykg.id/kg/api/hr/";
    public $userOdoo = ODOO_USER_PORTAL_PROD;
    public $passwdOdoo = ODOO_PASSWD_PORTAL_PROD;
    public function __construct()
    {
        include "include/connection.php";
        include "include/sapconnect.php";
        include_once "include/check_Flag.php";
        $this->conn = $conn;
        $this->rfc = $rfc;
        $this->urlOdoo = "https://hr.mykg.id/kg/api/hr/";
        $this->userOdoo = ODOO_USER_PORTAL_PROD;
        $this->passwdOdoo = ODOO_PASSWD_PORTAL_PROD;
    }


    /*--------------- GET LIST MEAL ALLOWANCE -------------------*/
    public function getlistMealAllow($data)
    {

        $this->NIK = $data["NIK"];
        $flag_odoo = check_access_odoo($data["NIK"]);

        if ($flag_odoo == 1) {
            $data = $this->getlistMealAllowOdoo($data);
        } else {
            $data = $this->getlistMealAllowSAP($data);
        }

        return $data;
    }


    public function getIndexNameMeal($flag_odoo)
    {
        if ($flag_odoo) {
            $index_name['TGLPEMBAYARAN'] = 'period';
            $index_name['HADIRSIANG'] = 'noon_attendance';
            $index_name['HADIRMALAM'] = 'night_attendance';
            $index_name['TARIFSIANG'] = 'noon_attendance_rate';
            $index_name['TARIFMALAM'] = 'night_attendance_rate';
            $index_name['ERISIANG'] = 'noon_eri';
            $index_name['ERIMALAM'] = 'night_eri';
            $index_name['ATTENDANCE'] = 'attendance';
            // ini dipake untuk denda
            $index_name['DENDA'] = 'meal_allowance_correction';

            $index_name['TERBAYAR'] = 'total_paid';
        } else {
            $index_name['TGLPEMBAYARAN'] = 'TGLPEMBAYARAN';
            $index_name['HADIRSIANG'] = 'HADIRSIANG';
            $index_name['HADIRMALAM'] = 'HADIRMALAM';
            $index_name['TARIFSIANG'] = 'TARIFSIANG';
            $index_name['TARIFMALAM'] = 'TARIFMALAM';
            $index_name['ERISIANG'] = 'ERISIANG';
            $index_name['ERIMALAM'] = 'ERIMALAM';
            $index_name['ATTENDANCE'] = 'ATTENDANCE';
            $index_name['DENDA'] = 'DENDA';
            $index_name['TERBAYAR'] = 'TERBAYAR';
        }
        return $index_name;
    }

    public function getlistMealAllowOdoo($data)
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
            'nik' => $data['NIK'],
            "period_date_start" => $data['start_date'],
            "period_date_end" => $data['end_date']
        );
        $postdata = http_build_query($postdata);
        $url = $this->urlOdoo."meal-allowance?" . $postdata;
        // var_dump($url);

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
        // var_dump($result);

        if ($httpcode == 401) {
            $token = $this->getOdooToken($this->passwdOdoo, $this->passwdOdoo);
            $this->getlistMealAllowOdoo($data);
        } else if ($httpcode == 200) {
            $json_decode = json_decode($result, true);
            // var_dump($json_decode);

            // DENDA dari odoo dibagi 100 dulu, spy saat dipakai di * 100 hasilnya tetap = value return di odoo
            // JANGAN LUPA BUAT PAKAI INI, saat dipanggil wajib * 100 spy nilainya benar
            // foreach ($json_decode['data'] as $arr => $value) {
                /*$index_name['TARIFSIANG'] = 'noon_attendance_rate';
                $index_name['TARIFMALAM'] = 'night_attendance_rate';
                $index_name['TERBAYAR'] = 'total_paid';*/
                // var_dump($arr['noon_attendance_rate']);

                /*$arr['noon_attendance_rate'] = ($arr['noon_attendance_rate'] > 0) ? $arr['noon_attendance_rate']+1 : $arr['noon_attendance_rate'];

                $arr['night_attendance_rate'] = ($arr['night_attendance_rate'] > 0) ? $arr['night_attendance_rate']/100 : $arr['night_attendance_rate'];

                $arr['meal_allowance_correction'] = ($arr['meal_allowance_correction'] > 0) ? $arr['meal_allowance_correction']/100 : $arr['meal_allowance_correction'];*/
                // var_dump($arr,$value);
            // }

            // var_dump($json_decode);


            // flag odoo = 1
            $index_name = $this->getIndexNameMeal(1);
            $json_decode['index_name'] = $index_name;

            $json_decode['rows'] = count($json_decode['data']);

            return $json_decode;
        } else {
            return False;
        }
    }

    public function getlistMealAllowSAP($data)
    {
        $NIK = $this->NIK;
        $rfc = $this->rfc;
        $fce_um = saprfc_function_discover($rfc,"ZHRFM_UM");
        if (! $fce_um ) { echo "Discovering interface of function module failed"; exit; }

        //Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
        saprfc_import ($fce_um,"FI_PERNR",$data['NIK']);
        saprfc_import ($fce_um,"FI_TGLENDPROSES",$data['start_date']);
        saprfc_import ($fce_um,"FI_TGLPROSES",$data['end_date']);
                            
        //Fill internal tables
        saprfc_table_init ($fce_um,"FI_UM");
                            
        //Do RFC call of function ZHRFM_UM, for handling exceptions use saprfc_exception()
        $rfc_rc = saprfc_call_and_receive ($fce_um);
        if ($rfc_rc != SAPRFC_OK) 
        { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce_um)); else echo (saprfc_error($fce_um)); exit; }
                            
        //Retrieve export parameters
        $rows = saprfc_table_rows ($fce_um,"FI_UM");

        // flag odoo = 0
        $index_name = $this->getIndexNameMeal(0);

        $return = array("fce_um" => $fce_um, "rows" => $rows, "index_name" => $index_name);
        return $return;
    }
    /*------------------ GET MEAL ALLOWANCE ---------------------*/


    /* -- END MEAL -- */
}
