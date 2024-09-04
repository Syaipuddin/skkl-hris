<?php 
require_once 'ESS/library.php';
include_once "include/check_Flag.php";
include "PD_getRegionCountry.php";
include "PD_getResidenceStatus.php";



class ESSProfileUpdate extends ESSLibrary {
    public $conn = "";
    public $rfc = "";
    public $flag = 0;
    public $fceRegion;
    public $fceCountry;
    public $fceResidenceList;
    public $dataArray;
    public $idAddressArray;
    public $residenceAddressArray;
    public $taxAddressArray;
    public $relatives1AddressArray;
    public $relatives2AddressArray;
    public $data;
    public $fieldName;
    public $currFilter;

    public function __construct($rfc) {
        parent::__construct();
        include "include/connection.php";
        include "include/sapconnect.php";
        include_once "include/check_Flag.php";
        include_once "include/date_lib.php";
        $this->conn = $conn;
        $this->rfc = $rfc;
        $this->fce = "";
        $this->flag = 0;
        $this->dataArray = array();
        $this->idAddressArray = array();
        $this->residenceAddressArray = array();
        $this->taxAddressArray = array();
        $this->relatives1AddressArray = array();
        $this->relatives2AddressArray = array();

    }

    public function getAccess($NIK) {
        $this->flag = check_access_odoo($NIK);

        return $this->flag;
    }

    public function post($data) {

        if(!isset($data['fce'])) {
            $dataset = array(
                $this->dataArray,
                $this->idAddressArray,
                $this->residenceAddressArray,
                $this->taxAddressArray,
                $this->relatives1AddressArray,
                $this->relatives2AddressArray
            );
            foreach ($dataset as $row) {
                if ($row) {
                    $response = $this->postToOdoo($row);
                }
            }

            $this->dataArray = array();
            $this->idAddressArray = array();
            $this->residenceAddressArray = array();
            $this->taxAddressArray = array();
            $this->relatives1AddressArray = array();
            $this->relatives2AddressArray = array();

            return $response;
        } else {
            return $this->initMesstab($data['fce'], $data['rfc']);
        }
    }

    public function postToOdoo($dataset=[]) {

        if ($this->flag == 1) {

            $ch = curl_init();
            
            if (isset($_SESSION['token_odoo'])) {
                $token = $_SESSION['token_odoo'];
            }
            else {
                $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
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

            $postdata = array();

            foreach ($dataset as $row) {
                $postdata[$row[0]] = $row[1];
            }

            $postdata = json_encode($postdata);

            $url = "$this->urlOdooHR" . "employee-profile-edit";

            // set the url, number o POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_POST, count($postdata));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            ob_start();

            curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $result = ob_get_clean();
            

            // close conn
            curl_close($ch);

            if($httpcode == 401) 
            {
                $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
                $this->postToOdoo($dataset);;
            } else if ($httpcode == 200)
            {
                return '1';
            } else {
                $dec_result = json_decode($result, true);
                $message = $dec_result['message'];
                echo "<script>console.log($message)</script>";
                echo "<center><label class='alert alert-error'>error Odoo  :  can not insert data = $message </label></center>";
                return '0';
            }
        }

        $this->dataArray = array();
    }

    public function getOdooModel($model) {
        $ch = curl_init();
        
        if (isset($_SESSION['token_odoo'])) {
            $token = $_SESSION['token_odoo'];
        }
        else {
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
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

        $url = $this->urlOdooBase."kg/api/res/$model";

        // set the url, number o POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        ob_start();

        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = ob_get_clean();
        

        // close conn~
        curl_close($ch);

        if($httpcode == 401) 
        {
            $token = $this->getOdooToken($this->userOdoo, $this->passwdOdoo);
            $this->getOdooModel($model);
        } else if ($httpcode == 200)
        {
            return $result;
        } else {
            return $result;
        }
    }

    public function postData($data) {
        unset($_SESSION['PDID']); // delete ID after posting for Odoo only!
        if($data['accessKaryawan'] == '1') {
            return $this->postDataOdoo($data['row']);
        } else {
            return $this->appendDataToSAP($data['fce'], $data['rfc'], $data['row'], (isset($data['param'])) ? $data['param'] : "");
        }
    }

    public function postDateData($data) {
        
        if($data['accessKaryawan'] == '1') {
            return $this->postDataOdoo($data['row']);
        } else {
            return $this->appendDataDateSAP($data['fce'],  $data['rfc'], $data['row'], (isset($data['param'])) ? $data['param'] : "");
        }
    }

    // START OF POST SECTION SAP

    public function initBAPI1($data) {
        $rfc = $data['rfc'];
        $query_info_1_4 = $data['query_info_1_4'];

        //insert to sap
        $fce = saprfc_function_discover($rfc,'ZHRFM_CHANGE0002');
        if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
        saprfc_import ($fce,"FI_AKSI",odbc_result($query_info_1_4,"ActionType"));
        saprfc_import ($fce,"FI_PERNR",odbc_result($query_info_1_4,"NIK"));
        saprfc_import ($fce,"GROUP","");
        saprfc_import ($fce,"HOLDDATE","");
        saprfc_import ($fce,"KEEP","");
        saprfc_import ($fce,"USER","");
        //Fill internal tables
        saprfc_table_init ($fce,"FI_INPUT");
        
        return $fce;
    }

    public function initBAPI4($data){
        $rfc = $data['rfc'];
        $conn = $data['conn'];
        $getDataToSAP = $data['getDataToSAPFAMSA'];
        $idDetail = $data['idDetail'];
        $query_info_1_4 = $data['query_info_1_4'];

        //insert to sap
        $fce = saprfc_function_discover($rfc,'ZHRFM_CHANGE0021_1');
        if (! $fce ) { echo "Discovering interface of function module failed"; exit; }

        odbc_execute($execDataFAMSA = odbc_prepare($conn,$getDataToSAP),array($idDetail, "FAMSA"));

        odbc_execute($execDataFCNAM = odbc_prepare($conn,$getDataToSAP),array($idDetail, "FCNAM"));

        // ini buat ngecek dia subtypenya apa: FAMSA itu jenis anak.
        // dikasih subtype biar bs ketauan klo emg gagal maka subrc pasti 1001, kalo berhasil pasti 0 (sebelumnya rancu subrcnya blm sesuai)
        $count_famsa = odbc_num_rows($execDataFAMSA);
        $count_fcnam = odbc_num_rows($execDataFCNAM);


        $famsa = odbc_result($execDataFAMSA, "FieldContent");

        // cukup dicek aja klo jenis anaknya diisi, maka diset subtypenya sesuai jenis anak (2 atau 3)
        if ($count_famsa>0 && isset($famsa) && $count_fcnam>0) {
            // if ($NIK == '007641') {
            // }
            saprfc_import ($fce,"FI_SUBTY",$famsa);
        }
        elseif ($count_famsa==0 && $count_fcnam>0) {
            // cukup klo jenis anaknya ga diisi, maka diset subtypenya = 1 atau sbg pasangan
            // if ($NIK == '007641') {
            // }
            saprfc_import ($fce,"FI_SUBTY",1);
        }

        saprfc_import ($fce,"FI_MODE","N");
        saprfc_import ($fce,"FI_AKSI",odbc_result($query_info_1_4,"ActionType"));
        saprfc_import ($fce,"FI_PERNR",odbc_result($query_info_1_4,"NIK"));
        saprfc_import ($fce,"GROUP","");
        saprfc_import ($fce,"HOLDDATE","");
        saprfc_import ($fce,"KEEP","");
        saprfc_import ($fce,"USER","");


        saprfc_table_init ($fce,"FI_INPUT");

        return $fce;
    }

    public function initBAPI13($data) {
        $rfc = $data['rfc'];
        $execBAPINAME = $data['execBAPINAME'];
        $actionTypeChange = $data['actionTypeChange'];
        $query_get_start_date_param = $data['query_get_start_date_param'];
        $query_get_end_date_param = $data['query_get_end_date_param'];

        //insert to sap
        $fce = saprfc_function_discover($rfc,'ZHRFM_CHANGE0022_A');
        if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
        if(odbc_result($execBAPINAME, 'buffer_row')!='')
        {
            saprfc_import ($fce,"FI_AKSI",odbc_result($execBAPINAME,"buffer_row"));
        }
        else{

            if ($actionTypeChange != odbc_result($execBAPINAME,"ActionType"))
            {
                saprfc_import ($fce,"FI_AKSI",$actionTypeChange);
            }
            else
            {
                saprfc_import ($fce,"FI_AKSI",odbc_result($execBAPINAME,"ActionType"));
            }	
        }

        if(odbc_result($query_get_start_date_param, 'OldContent')!='' && odbc_result($query_get_end_date_param, 'OldContent')!='')
        {
            $start_date_valid= substr(odbc_result($query_get_start_date_param,'OldContent'),8,2).substr(odbc_result($query_get_start_date_param,'OldContent'),5,2).substr(odbc_result($query_get_start_date_param,'OldContent'),0,4);

            $end_date_valid= substr(odbc_result($query_get_end_date_param,'OldContent'),8,2).substr(odbc_result($query_get_end_date_param,'OldContent'),5,2).substr(odbc_result($query_get_end_date_param,'OldContent'),0,4);

            saprfc_import ($fce,"FIL_BEGDA",$start_date_valid);
            saprfc_import ($fce,"FIL_ENDDA",$end_date_valid);	
            
        }else{
            saprfc_import ($fce,"FIL_BEGDA","");
            saprfc_import ($fce,"FIL_ENDDA","");	
        }
        
        //saprfc_import ($fce,"FIL_SUBTY"," ");
        saprfc_import ($fce,"FI_PERNR",odbc_result($execBAPINAME,"NIK"));
        saprfc_import ($fce,"GROUP","");
        saprfc_import ($fce,"HOLDDATE","");
        saprfc_import ($fce,"KEEP","");
        saprfc_import ($fce,"USER","");
        //Fill internal tables
        saprfc_table_init ($fce,"FI_INPUT");

        return $fce;
    }

    public function initBAPI15($data) {
        $rfc = $data['rfc'];
        $execBAPINAME = $data['execBAPINAME'];
        $actionTypeChange = $data['actionTypeChange'];
        $query_get_start_date_param = $data['query_get_start_date_param'];
        $query_get_end_date_param = $data['query_get_end_date_param'];

        //insert to sap
        $fce = saprfc_function_discover($rfc,'ZHRFM_CHANGE0022_B');
        if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
        //It's possible to define interface manually. If you would like do it, uncomment following lines:
        //Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
        if(odbc_result($execBAPINAME, 'buffer_row')!='')
        {
            saprfc_import ($fce,"FI_AKSI",odbc_result($execBAPINAME,"buffer_row"));
        }
        else{
            if ($actionTypeChange != odbc_result($execBAPINAME,"ActionType"))
            {
                saprfc_import ($fce,"FI_AKSI",$actionTypeChange);
            }
            else
            {
                saprfc_import ($fce,"FI_AKSI",odbc_result($execBAPINAME,"ActionType"));
            }	
        }

        if(odbc_result($query_get_start_date_param, 'OldContent')!='' && odbc_result($query_get_end_date_param, 'OldContent')!='')
        {
            $start_date_valid= substr(odbc_result($query_get_start_date_param,'OldContent'),8,2).substr(odbc_result($query_get_start_date_param,'OldContent'),5,2).substr(odbc_result($query_get_start_date_param,'OldContent'),0,4);

            $end_date_valid= substr(odbc_result($query_get_end_date_param,'OldContent'),8,2).substr(odbc_result($query_get_end_date_param,'OldContent'),5,2).substr(odbc_result($query_get_end_date_param,'OldContent'),0,4);

            saprfc_import ($fce,"FIL_BEGDA",$start_date_valid);
            saprfc_import ($fce,"FIL_ENDDA",$end_date_valid);	
            
        }else{
            saprfc_import ($fce,"FIL_BEGDA","");
            saprfc_import ($fce,"FIL_ENDDA","");	
        }
        
        //saprfc_import ($fce,"FIL_SUBTY","");
        saprfc_import ($fce,"FI_PERNR",odbc_result($execBAPINAME,"NIK"));
        saprfc_import ($fce,"GROUP","");
        saprfc_import ($fce,"HOLDDATE","");
        saprfc_import ($fce,"KEEP","");
        saprfc_import ($fce,"USER","");
        //Fill internal tables
        saprfc_table_init ($fce,"FI_INPUT");

        return $fce;
    }

    public function initBAPI($execBAPINAME, $actionTypeChange, $idDetail) {
        $fce = saprfc_function_discover($this->rfc,odbc_result($execBAPINAME,"SAPBAPIName"));
        if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
        //It's possible to define interface manually. If you would like do it, uncomment following lines:
        //Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
        if(odbc_result($execBAPINAME, 'buffer_row')!='')
        {
            saprfc_import ($fce,"FI_AKSI",odbc_result($execBAPINAME,"buffer_row"));
        }
        else{
            if ($actionTypeChange != odbc_result($execBAPINAME,"ActionType"))
            {
                saprfc_import ($fce,"FI_AKSI",$actionTypeChange);
            }
            else
            {
                saprfc_import ($fce,"FI_AKSI",odbc_result($execBAPINAME,"ActionType"));
            }	
        }
        
        saprfc_import ($fce,"FI_PERNR",odbc_result($execBAPINAME,"NIK"));
        saprfc_import ($fce,"GROUP","");
        saprfc_import ($fce,"HOLDDATE","");
        saprfc_import ($fce,"KEEP","");
        saprfc_import ($fce,"USER","");
        
        if(odbc_result($execBAPINAME,"SAPBAPIName") == 'ZHRFM_CHANGE0006')
        {
            saprfc_import ($fce,"FI_MODE","N");
        }

        // KHUSUS CHANGE0021 ATAU FAMILY, PERLU INPUT SUBTYPENYA
        if (odbc_result($execBAPINAME,"SAPBAPIName") == 'ZHRFM_CHANGE0021') {
            saprfc_import ($fce,"FI_MODE","N");
            $getDataToSAP="select distinct SAPBAPIName, SAPFieldName, FieldContent, D.ActionType,
                        SAPFieldFilterName as sap_filter, FilterValue, E.NIK, FieldActionType from PD_ChangeRequestDetail A inner join PD_FieldOdoo B on A.FieldID= B.FieldID
                        inner join PD_InfoTypeOdoo C on B.InfoTypeID=C.InfoTypeID
                        inner join PD_ChangeRequestInfotype D on D.ChangeRequestID=A.ChangeRequestID
                        inner join PD_ChangeRequest E on E.ChangeRequestID=A.ChangeRequestID
                        where A.ChangeRequestID=? and (C.InfoTypeID=4 or C.InfoTypeID=5) and FieldActionType is NULL
                        AND SAPFieldName = ?";
            // $getDataFAMSA = odbc_exec($conn,$getDataToSAP);

            odbc_execute($execDataFAMSA = odbc_prepare($this->conn,$getDataToSAP),array($idDetail, "FAMSA"));

            odbc_execute($execDataOBJPS = odbc_prepare($this->conn,$getDataToSAP),array($idDetail, "OBJPS"));

            // ini buat ngecek dia subtypenya apa: FAMSA itu jenis anak.
            // dikasih subtype biar bs ketauan klo emg gagal maka subrc pasti 1001, kalo berhasil pasti 0 (sebelumnya rancu subrcnya blm sesuai)
            $count_famsa = odbc_num_rows($execDataFAMSA);
            $count_objps = odbc_num_rows($execDataOBJPS);


            $famsa = odbc_result($execDataFAMSA, "FieldContent");
            $objps = odbc_result($execDataOBJPS, "FieldContent");

            // di sini cukup dicek ada FAMSA atau child status aja atau enggak (child status = subtype)
            // subtype utk tau ini edit utk spouse atau child
            if ($count_famsa>0 && isset($famsa)) {
                // if ($NIK == '007641') {
                    echo "count_famsa 1:";
                // }
                saprfc_import ($fce,"FI_SUBTY",$famsa);
            }

            if ($count_objps>0 && isset($objps)) {
                saprfc_import ($fce,"FI_ANAK",$objps);
            }
        }

        echo '<br />';
        
        //Fill internal tables
        saprfc_table_init ($fce,"FI_INPUT");
        return $fce;
    }

    public function appendDataDateSAP($fce, $rfc, $row, $param="") {
        $formatted_date= (false) ? '01082024' : substr($row['FieldContent'],8,2).substr($row['FieldContent'],5,2).substr($row['FieldContent'],0,4);
        saprfc_table_append ($fce,"FI_INPUT", array ("FIELDNAME"=>$row["SAPFieldName"],"FIELDCONTENT"=>$formatted_date,"FILTERNAME"=>$row["sap_filter"],"FILTERVALUE"=>$row["FilterValue"]));

        // return $this->initMesstab($fce, $rfc);
    }

    public function appendDataToSAP($fce, $rfc, $row, $param="") {

        if ($row['SAPFieldName']) {
            $fieldContent= ($param) ? $param : $row["FieldContent"];
            saprfc_table_append ($fce,"FI_INPUT", array ("FIELDNAME"=>$row["SAPFieldName"],"FIELDCONTENT"=>strtoupper($fieldContent),"FILTERNAME"=>$row["sap_filter"],"FILTERVALUE"=>$row["FilterValue"]));
        }
        

        // return $this->initMesstab($fce, $rfc);
    }

    public function initMesstab($fce, $rfc){

        $flaginsertSAP = 0;

        saprfc_table_init ($fce,"MESSTAB");
        //Do RFC call of function ZHRFM_CHANGE0002, for handling exceptions use saprfc_exception()
        $rfc_rc = saprfc_call_and_receive ($fce);
        if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
        //Retrieve export parameters
        $SUBRC = saprfc_export ($fce,"SUBRC");
        $FI_ERR = saprfc_export ($fce,"FI_ERR");
        $rows = saprfc_table_rows ($fce,"FI_INPUT");
        for ($i=1;$i<=$rows;$i++)
            $FI_INPUT[] = saprfc_table_read ($fce,"FI_INPUT",$i);
        $rows = saprfc_table_rows ($fce,"MESSTAB");
        for ($i=1;$i<=$rows;$i++)
            $MESSTAB[] = saprfc_table_read ($fce,"MESSTAB",$i);

    
        if($SUBRC != 0)
        {
            echo "<center><label class='alert alert-error'>error SAP  :  can not insert data $FI_ERR</label></center>";
        }
        else
        {
            $flaginsertSAP='1';
        }

        saprfc_function_debug_info($fce);
        saprfc_function_free($fce);

        return $flaginsertSAP;
 
    }

    // END OF POST SECTION SAP

    // START OF POST SECTION ODOO

    public function postDataDateOdoo($execDataToSAP) {

        if (odbc_result($execDataToSAP, 'OdooFieldName') && odbc_result($execDataToSAP, 'FieldContent')) {
            $this->dataArray[] = array(
                odbc_result($execDataToSAP, 'OdooFieldName'),
                odbc_result($execDataToSAP, 'FieldContent')
            );
        }
    }

    public function postDataOdoo($row) {

        // var_dump($row['OdooFilterValue']);

        switch ($row['OdooFilterValue']) {
            case 'id_address':
                $varName = 'idAddressArray';
                break;
            case 'residence':
                $varName = 'residenceAddressArray';
                break;
            case 'tax':
                $varName = 'taxAddressArray';
                break;
            case 'relatives_1':
                $varName = 'relatives1AddressArray';
                break;
            case 'relatives_2':
                $varName = 'relatives2AddressArray';
                break;
            default:
                $varName = 'dataArray';
                break;
        }

        if ($row['odoo_filter']) { 
            $this->data = $row['FieldContent'];
            $row['FieldContent'] = call_user_func([$this, $row['odoo_filter']]);
        }
        
        if ($row['OdooFieldName']) {      

            $this->{$varName}[] = array(
                $row['OdooFieldName'],
                ($row['OdooFieldName'] == 'id' || $row['OdooFieldName'] == 'education_id' || $row['OdooFieldName'] == 'certification_id' || $row['OdooFieldName'] == 'profession_id') ? (int)$row['FieldContent'] : $row['FieldContent']
            );
        }

        // ADD fam_identity_type in ODOO (not posted in SAP)
        if ($row['OdooFieldName'] == 'fam_country_code') {
            $this->{$varName}[] = array(
                'fam_identity_type',
                ($row['FieldContent'] == 'ID') ? 'wni' :'wna'
                );
        }

        if ($row['NIK'])  $this->{$varName}[] = array('nik', $row['NIK']);
        if ($row['InfoTypeID']  == 6 && $row['OdooFilterValue'] && $row['OdooFilterValue'] != 'id_address')  {
            $this->{$varName}[] = array(
                'address_type', 
                $row['OdooFilterValue']
            );
        }
        if ($row['OdooFilterValue']  == 'id_address')  $row['OdooAPIName'] = 'edit_address';

        if ($row['OdooAPIName'] || $row['OdooAPINameInsert']) {
            $this->{$varName}[] = array(
            'edit_type',
            ($row['OdooFilterValue'] == 'cert') ? $this->matchInfoType13APIName($row) : $this->getAPIName($row)
            );
        }

    }

    public function matchResidenceStatus() {
        $status = '';
        switch($this->data) {
            case 'Z1':
                $status = 'own';
                break;
            case 'Z2':
                $status = 'parent';
                break;
            case 'Z3':
                $status = 'rent';
                break;
            case 'Z4':
                $status = 'company';
                break;
            case 'Z5':
                $status = '';
                break;
            case 'Z6':
                $status = 'stay';
                break;
        }

        return $status;
    }

    public function getAPIName($row) {
        return ($row['ActionType'] > 0) ? $row['OdooAPIName'] : $row['OdooAPINameInsert'];
    }

    public function matchInfoType13APIName($row) {
       return ($row['ActionType'] > 0) ? 'edit_training_courses' : 'add_training_courses';
    }

    public function matchMajor() {
        return (int)$this->data;
    }

    public function match_addresses_type() {
 
       return $this->data; // done to match insertion format with other fields
    }

    public function matchGender() {
        return ($this->data == 'Male') ? 'male' : 'female';
    }

    public function matchCert(){

        return ($this->data == 'ZA' || $this->data == 'ZC') ? 'cert' : 'non_cert';
    }

    public function matchChildStatus() {
        switch($this->data) {
            case '1':
                $status = 'spouse';
                break;
            case '2':
                $status = 'child';
                break;
            case '3':
                $status = 'step_child';
                break;
            default:
                $status = "";
        }

        return $status;
    }

    public function matchCostCenter() {

        switch ($this->data) {
            case '01':
                $cost = 'company';
                break;
            case '02':
                $cost = 'personal';
                break;
            case '03':
                $cost = 'sponsor';
                break;
            case '04':
                $cost = 'sponsor_company';
                break;
        }

        return $cost;
    }

    public function matchDelimit() {
        switch ($this->data) {
            case '01':
                $delimit = 'death';
                break;
            case '02':
                $delimit = 'divorce';
                break;
            case '03':
                $delimit = 'married';
                break;
            case '04':
                $delimit = 'age26';
                break;
            case '05':
                $delimit = 'worked';
                break;
            case '06':
                $delimit = 'graduated';
                break;
            case '07':
                $delimit = 'age23';
                break;
            case 'child2':
                $delimit = 'child2';
                break;   
            default:
                $delimit = false;
                break;
        }
        return $delimit;
    }
    
    public function matchFacultyCode() {
        if ((int)$this->data < 10000) {
            return "0$this->data";
        } else {
            return "$this->data";
        }
    }

    public function matchDurationTime() {

        switch($this->data) {
            case 'Days':
                $duration = 'day';
                break;
            case 'Weeks':
                $duration = 'week';
                break;
            case 'Months':
                $duration = 'mon';
                break;
            case 'Years':
                $duration = 'year';
                break;
            case 'Semester':
                $duration = 'sem';
                break;
            case 'Classes':
                $duration = 'class';
                break;
            default:
                $duration = "";
        }

        return $duration;
    }

    public function matchTrainingType() {

        switch($this->data) {
            case "Z1":
                $type = "dik_fu";
                break;
            case "Z2":
                $type = "dik_um";
                break;
            case "Z3":
                $type = "dik_man";
                break;
            case "Z4":
                $type = "dev_mdp";
                break;
            case "Z5":
                $type = "dev_mmdp";
                break;
            default:
                $type = "";
                break;
        }

        return $type;
    }

    public function matchJenjang() {
        $qry = "select jenjang_odoo from tb_tunjSekolah_jenjang where jenjang = ?";
        $prep_qry = odbc_prepare($this->conn, $qry);
        $result = odbc_execute($prep_qry, array($this->data));

        if(!$result) {
            die("Failed to fetch jenjang table");
        }

        return odbc_result($prep_qry, "jenjang_odoo");
    }

    public function matchReligion() {
        
        switch ($this->data) {
            case 'Z1':
                $religion = 'Islam';
                break;
            case 'Z2':
                $religion = 'Katholik';
                break;
            case 'Z3':
                $religion = 'Kristen';
                break;
            case 'Z4':
                $religion = 'Buddha';
                break;
            case 'Z5':
                $religion = 'Hindu';
                break;
            case 'Z6':
                $religion = 'Kong Hu Cu';
                break;
            case 'Z7':
                $religion = 'Kepercayaan';
                break;
            default:
                $religion = "";
                break;
        }

        return $religion;
            
    }

    public function matchMarriage() {

        switch ($this->data) {
            case "Lajang":
                $marital = "single";
                break;
            case "Nikah":
                $marital = 'married';
                break;
            case "Duda":
                $marital = "widower";
                break;
            case "Janda":
                $marital = 'divorce';
                break;
            default:
                $marital = "";
                break;
        }

        return $marital;
    }

    // ENF OF POST SECTION ODOO


    // #START OF RESIDENCE LIST SECTION

    public function getResidenceStatusOdoo() {

        $vargu = array(
            'own',
            'parent',
            'rent',
            'company',
            'stay'
        );

        $landx = array(
            'own' => 'Milik Sendiri',
            'parent' => 'Milik Orang Tua',
            'rent' => 'Kontrak/Kost',
            'company' => 'Mess Perumahan/Perumahan Dinas',
            'stay' =>  'Menumpang'
        );

        return array(
            'VARGU' => $vargu, #This is residenceStatus ID in SAP
            'LANDX' => $landx
        );
    }

}
    
?>

