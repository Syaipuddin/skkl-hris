<?php
    /*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
    include_once 'ESS/library.php';
    include_once "include/check_Flag.php";
    include "PD_getRegionCountry.php";
    include "PD_getResidenceStatus.php";
    ?>
    <!-- INI SUPAYA POPUP MUNCUL -->
    <link href="css/ThickBox.css" rel="stylesheet">
    <?php

    class ESSProfileLibrary {
        
        private $libraryInstance;
        public $conn = "";
        public $rfc = "";
        public $flag = 0;

        public function __construct() {
            include "include/connection.php";
            include "include/sapconnect.php";
            // include_once "include/check_Flag.php";
            include_once "include/date_lib.php";
            $this->conn = $conn;
            $this->rfc = $rfc;
            $this->libraryInstance = new ESSlibrary();
            $this->NIK = "";
            $this->fce = "";
        }

        public function call_user_func($function_name,$NIK)
        {
            $this->NIK = $NIK;
            call_user_func([$this,$function_name]);
        }

        public function isIDField($field) {
          // CHECK IF THE VALUE IS ODOO ID FIELD OR NOT, 
          // IF TRUE ASSIGN THE SECOND INDEX AUTOMATICALLY
          if (count($field) == 2 ) {
            return $field[1];
          } else {
            return $field;
          }
        }

        public function matchDate($date) {
          $flag = $this->flag;

          // MATCH ODOO DATE FORMAT TO SAP DATE FORMAT
          if($flag == 1) {
            return date('Ymd', strtotime($date));
          } else {
            return $date;
          }
        }

        public function profileGetter($NIK)
        {
            $this->flag=check_access_odoo($NIK);
            $flag = $this->flag;
	          $this->NIK = $NIK;

            if ($flag == 1) {
                $data = $this->getOdooEmployeeProfileAllOdoo($NIK);
            } else {
                $data = $this->getSAPEmployeeProfile($NIK);
                $data['IS_SAP'] = true;
            }

            return $data;
        }

        public function getSAPEmployeeProfile($NIK)
        {
            $rfc = $this->rfc;
            //Discover interface for function module ZHRFM_CV
            $fce = saprfc_function_discover($rfc, "ZHRFM_CV");
            if (!$fce) {
            echo "Discovering interface of function module failed";
            exit;
            }

            // global parameter, utk fungsi lain yg ga bisa lempar parameter
            $this->fce = $fce;

            saprfc_import($fce, "FI_PERNR", $NIK);
            saprfc_import($fce, "FI_PERNR_DIAKSES", $NIK);
            //Fill internal tables
            saprfc_table_init($fce, "FI_0105");
            saprfc_table_init($fce, "FI_0319");
            saprfc_table_init($fce, "FI_3385");
            saprfc_table_init($fce, "FI_ANAK");
            saprfc_table_init($fce, "FI_AWARD");
            saprfc_table_init($fce, "FI_BANK");
            saprfc_table_init($fce, "FI_BNKA");
            saprfc_table_init($fce, "FI_CV");
            saprfc_table_init($fce, "FI_MEDICAL");
            saprfc_table_init($fce, "FI_MUTASI");
            saprfc_table_init($fce, "FI_NSEKOLAH");
            saprfc_table_init($fce, "FI_PENGLM");
            saprfc_table_init($fce, "FI_SEKOLAH");
            //Do RFC call of function ZHRFM_CV, for handling exceptions use saprfc_exception()
            $rfc_rc = saprfc_call_and_receive($fce);
            if ($rfc_rc != SAPRFC_OK) {
            if ($rfc == SAPRFC_EXCEPTION)
                echo ("Exception raised: " . saprfc_exception($fce));
            else
                echo (saprfc_error($fce));
            exit;
            }

            //Retrieve export parameters
            $AUTORISASI = saprfc_export($fce, "AUTORISASI");
            $FI_EXT = saprfc_export($fce, "FI_EXT");
            $FI_NOKANTOR = saprfc_export($fce, "FI_NOKANTOR");

            // GET ADDRESSES
            if (!$rfc) {
                echo "RFC connection failed";
                exit;
            }
            //Discover interface for function module ZHRFM_RESIDENCE_STATUS
            $fce3 = saprfc_function_discover($rfc, "ZHRFM_RESIDENCE_STATUS");
            if (!$fce3) {
            echo "Discovering interface of function module failed";
            exit;
            }
            //Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
            saprfc_import($fce3, "FI_PERNR", $NIK);
            saprfc_import($fce3, "FI_PERNR_DIAKSES", $NIK);
            //Fill internal tables
            saprfc_table_init($fce3, "FI_RESIDENCE_STATUS");
            saprfc_table_init($fce3, "FI_T536A");
            //Do RFC call of function ZHRFM_RESIDENCE_STATUS, for handling exceptions use saprfc_exception()
            $rfc_rc = saprfc_call_and_receive($fce3);
            if ($rfc_rc != SAPRFC_OK) {
            if ($rfc == SAPRFC_EXCEPTION)
                echo ("Exception raised: " . saprfc_exception($fce3));
            else
                echo (saprfc_error($fce));
            exit;
            }
            //Retrieve export parameters
            $AUTORISASI = saprfc_export($fce3, "AUTORISASI");
            $FI_EXT = saprfc_export($fce3, "FI_EXT");
            $FI_NOKANTOR = saprfc_export($fce3, "FI_NOKANTOR");
            $rowsResidenceStatus = saprfc_table_rows($fce3, "FI_RESIDENCE_STATUS");


            if (!$rfc) {
            echo "RFC connection failed";
            exit;
            }

            //Discover interface for function module ZHRFM_ADDRESS
            $fce2 = saprfc_function_discover($rfc, "ZHRFM_ADDRESS");
            if (!$fce2) {
            echo "Discovering interface of function module failed";
            exit;
            }

            //Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
            saprfc_import($fce2, "FI_PERNR", $NIK);
            saprfc_import($fce2, "FI_PERNR_DIAKSES", $NIK);
            //Fill internal tables
            saprfc_table_init($fce2, "FI_1ST_RELATIVES");
            saprfc_table_init($fce2, "FI_2ND_RELATIVES");
            saprfc_table_init($fce2, "FI_ID_ADDRESS");
            saprfc_table_init($fce2, "FI_RESIDENCE_ADDRESS");
            saprfc_table_init($fce2, "FI_TAX_ADDRESS");
            //Do RFC call of function ZHRFM_ADDRESS, for handling exceptions use saprfc_exception()
            $rfc_rc = saprfc_call_and_receive($fce2);
            if ($rfc_rc != SAPRFC_OK) {
            if ($rfc == SAPRFC_EXCEPTION)
                echo ("Exception raised: " . saprfc_exception($fce2));
            else
                echo (saprfc_error($fce2));
            exit;
            }
            //Retrieve export parameters
            $AUTORISASI = saprfc_export($fce2, "AUTORISASI");
            $FI_EXT = saprfc_export($fce2, "FI_EXT");
            $FI_NOKANTOR = saprfc_export($fce2, "FI_NOKANTOR");

            $ADDRESS['index_name'] = array(
              'TYPE'=>'TYPE',
              'STATE'=>'STATE',
              'LAND1'=>'LAND1',
              'STRAS'=>'STRAS',
              'LOCAT'=>'LOCAT',
              'ORT01'=>'ORT01',
              'PSTLZ'=>'PSTLZ',
              'TELNR'=>'TELNR',
              'NAME2'=>'NAME2',
              'AUSBE'=>'AUSBE'
          );

            $FI_ANAK['index_name'] = array(
              'FAMILYSTATUS' => 'ST',
              'GENDER' => 'FASEX',
              'NAME' => 'FCNAM',
              'BIRTHDATE' => 'FGBDT',
              'CHILDNO' => 'OBJPS',
              'COVERED' => 'TERTANGGUNG',
              'STATUS' => 'FAMSA',
              'STARTDATE' => 'BEGDA',
              'DELIMIT' => 'KETERANGAN'
            );

            $FI_SEKOLAH['index_name'] = array(
              'GRADE' => 'JENJANG',
              'NAME' => 'SEKOLAHSD',
              'FACULTY' => 'FAKULTAS',
              'MAJOR' => 'JURUSAN',
              'CITY' => 'NAMAKOTA',
              'COUNTRY' => 'NEGARA',
              'PERIODSTART' => 'PERIODEAWAL',
              'PERIODAKHIR' => 'PERIODEAKHIR',
              'SCORE' => 'NILAI',
              'SLART' => 'SLART',
              "AUSBI" => "AUSBI",
              "SLTP1" => "SLTP1",
              "ZJURUSAN" => "ZJURUSAN"
            );

            $FI_NSEKOLAH['index_name'] = array(
              "TYPE" => 'NAMAJENJANG',
              "NAME" => 'KSBEZ',
              'INSTITUTE' => 'LEMBAGA',
              'STARTDATE' => 'PERIODEAWAL',
              'ENDDATE' => 'PERIODEAKHIR',
              'ACTION' => 'ZBIAYA',
              'COUNTRY' => 'NEGARA',
              'PAYMENT' => 'BIAYA'
            );

            $FI_PENGLM['index_name'] = array(
              "COMPANY" => 'ARBGB',
              'COMPANYADDRESS' => 'COMPANY',
              'POSITION' => 'JABATAN',
              'STARTDATE' => 'BEGDA',
              'ENDDATE' => 'ENDDA',
              'CITY' => 'CITY',
              'NEGARA' => 'NEGARA'
            );

            $FI_MUTASI['index_name'] = array(
              'STARTDATE' => 'BEGDA',
              'ENDDATE' => 'ENDDA',
              'UNIT' => 'UNIT',
              'STATUS' => 'STATUS',
              'POSITION' => 'JABATAN'
            );

            $FI_ADTJB['index_name'] = array(
              'STARTDATE' => 'BEGDA',
              'ENDDATE' => 'ENDDA',
              'UNIT' => 'UNIT',
              'JABATAN' => 'JABATAN'
            );

            return array(
                'fce'=>$fce,
                'fce2'=>$fce2,
                'fce3'=>$fce3,
                "FI_ANAK" => $FI_ANAK,
                "ADDRESS"=> $ADDRESS,
                "FI_SEKOLAH" => $FI_SEKOLAH,
                "FI_NSEKOLAH" => $FI_NSEKOLAH,
                "FI_PENGLM" => $FI_PENGLM,
                "FI_MUTASI" => $FI_MUTASI,
                "FI_ADTJB" => $FI_ADTJB
            );
        }

        public function getOdooEmployeeProfileAllOdoo($NIK)
        {
            $profile = json_decode($this->getOdooEmployeeProfile($NIK, 'all'), true);
            
            $FI_CV = array(
                "NAMALENGKAP"=>$profile['name'],
                "INISIAL"=>$profile['initial_name'],
                "NAMAPANGGILAN"=>$profile['nickname'],
                "TEMPATLLAHIR"=>$profile['place_of_birth'],
                "TTL"=>date('Ymd', strtotime($profile['birthday'])),
                "HP"=>$profile['mobile_phone'],
                "JENISKELAMIN"=>$profile['gender'],
                "STATUSPERKAWINAN"=>$profile['marital'],
                "TANGGALPASANGAN"=>date('Ymd', strtotime($profile['marriage_date'])),
                "AGAMA"=>$profile['religion_id'],
                "GOLDARAH"=>$profile['blood_type'],
                "EMAIL"=>$profile['work_email'],
                "NOKTP"=> $profile['identification_id'],
                "TAXID"=>$profile['npwp_no'],
                "BPJSI"=>$profile['bpjsks_no'],
                "NOJAMSOSTEK"=>$profile['bpjstk_no'],
                "NOSIMA"=>$profile['driving_license_a'],
                "NOSIMB"=>$profile['driving_license_b'],
                "NOSIMC"=>$profile['driving_license_c'],
                "NOPASPORT"=>$profile['passport_id'],
                "UNIT"=>$profile['department_complete_name'],
                "POSITIONS"=>$profile['job_id'],
                "STATUS"=>$profile['employee_status'],
                "TGLMASUK"=>date('Ymd', strtotime($profile['joining_date'])),
                "TGLDIANGKAT"=>date('Ymd', strtotime($profile['permanent_date'])),
                "GOLRS1"=>$profile['medical_man_group_id'],
                "GOLRS2"=>$profile['medical_employee_class_id'],
                "GOLDLK"=>$profile['travel_employee_class_id'],
                "LOKASIABSEN"=>"", #NO LOKASIABSEN PROVIDED FROM ODOO
                "HRUNIT"=>$profile['pa_admin_id'],
                "ACCID"=>$profile['retired_id']

            );

            $FI_BANK = array(
                "EMFTX"=>$profile['account_name'],
                "BANKN"=>$profile['account_number'],
            );
          
            $ADDRESS['index_name'] = array(
                'TYPE'=>'address_type',
                'STATE'=>'state_id',
                'LAND1'=>'country_id',
                'STRAS'=>'street',
                'LOCAT'=>'street2',
                'ORT01'=>'city',
                'PSTLZ'=>'zip',
                'TELNR'=>'phone',
                'NAME2'=>'relatives_name',
                'AUSBE'=>'residence_status'
            );

            $ADDRESS['data'] = $profile['address_ids'];

            $FI_ANAK = array();
            $FI_ANAK['index_name'] = array(
              'FAMILYSTATUS' => 'family_relation',
              'GENDER' => 'gender',
              'NAME' => 'name',
              'BIRTHDATE' => 'birth_date',
              'CHILDNO' => 'child_no',
              'COVERED' => 'medical_reimburse',
              'STATUS' => 'family_relation',
              'STARTDATE' => 'valid_start_date',
              'DELIMIT' => 'uncovered_desc'
            );
            $FI_ANAK['data'] = $profile['fam_ids'];
            

            $FI_SEKOLAH = array();
            $FI_SEKOLAH['index_name'] = array(
              'GRADE' => 'type_id',
              'NAME' => 'institute_id',
              'FACULTY' => 'faculty_id',
              'MAJOR' => 'major_id',
              'CITY' => 'city',
              'COUNTRY' => 'country_id',
              'PERIODSTART' => 'enter_year',
              'PERIODAKHIR' => 'qualified_year',
              'SCORE' => 'score',
              'SLART' => 'type_id',
              "AUSBI" => "",
              "SLTP1" => "",
              "ZJURUSAN" => "",
            );
            $FI_SEKOLAH['data'] = $profile['education_ids'];
            
                
              // $eduArray = array(
              //     'JENJANG'=>$edu->type_id[1],
              //     'SEKOLAHSD'=>$edu->institute_id[1],
              //     "FAKULTAS"=>$edu->faculty_id[1],
              //     "JURUSAN"=>$edu->major_id[1],
              //     "NAMAKOTA"=>$edu->city,
              //     "NEGARA"=>$edu->country_id[1],
              //     "PERIODEAWAL"=>date('Ymd', strtotime($edu->enter_year)),
              //     "PERIODEAKHIR"=>date('Ymd', strtotime($edu->qualified_year)),
              //     "PERIODE"=>substr(date('Ymd', strtotime($edu->qualified_year)),0,4),
              //     "NILAI"=>$edu->score,
              //     "AUSBI"=>$edu->institute_id[1],
              //     "SLTP1"=>$edu->faculty_id[1],
              //     "ZJURUSAN"=>$edu->major_id[1]
              // );

            $FI_NSEKOLAH = array();
            $FI_NSEKOLAH['index_name'] = array(
              "TYPE" => 'training_type',
              "NAME" => 'course_name',
              'INSTITUTE' => 'institute_name',
              'STARTDATE' => 'from_date',
              'ENDDATE' => 'to_date',
              'ACTION' => 'country_id',
              'COUNTRY' => 'country_id',
              'PAYMENT' => 'cost_center'
            );
            $FI_NSEKOLAH['data'] = $profile['certification_ids'];

            $FI_PENGLM = array();
            $FI_PENGLM['index_name'] = array(
              "COMPANY" => 'employeer',
              'COMPANYADDRESS' => 'employeer',
              'POSITION' => 'job_name',
              'STARTDATE' => 'from_date',
              'ENDDATE' => 'to_date',
              'CITY' => 'city',
              'NEGARA' => 'country_id'
            );
            $FI_PENGLM['data'] = $profile['profession_ids'];

            $FI_MUTASI = array();
            $FI_MUTASI['index_name'] = array(
              'STARTDATE' => 'start_date',
              'ENDDATE' => 'end_date',
              'UNIT' => 'pa_admin_id',
              'POSITION' => 'job_id',
              'STATUS' => 'type_id'
              );
            $FI_MUTASI['data'] = $profile['all_external_action_ids'];

            $FI_ADTJB = array();
            $FI_ADTJB['index_name'] = array(
              'STARTDATE' => 'start_date',
              'ENDDATE' => 'end_date',
              'UNIT' => 'department_id',
              'JABATAN' => 'job_id'
            );
            $FI_ADTJB['data'] = $profile['additional_job_position_ids'];
            
            $data = array(
                "FI_CV" => $FI_CV,
                "MOBILE_PORTAL"=>$profile['mobile_phone'],
                "FI_BANK"=>$FI_BANK,
                "FI_BNKA"=>array("BANKA"=>$profile['bank_id']),
                "ADDRESS"=>$ADDRESS,
                "FI_ANAK"=>$FI_ANAK,
                "FI_SEKOLAH"=>$FI_SEKOLAH,
                "FI_NSEKOLAH"=>$FI_NSEKOLAH,
                "FI_PENGLM"=>$FI_PENGLM,
                "FI_AWARD"=>array(), # no awards in employee-profile controller (ODOO)
                "FI_MUTASI"=>$FI_MUTASI,
                "FI_ADTJB"=>$FI_ADTJB,
                "RETIRED_ID"=>$FI_CV["ACCID"],
                // TO PREVENT ERROR WHEN FETCHING DATA CONDITIONALLY
                "FI_MEDICAL"=> array(),
                "FI_3385" => array(),
                "FI_0319"=> array(),
                "FI_0318"=> array(),
                "IS_SAP"=>false
            );

            return $data;
        }
        

        public function getEmployeePersonalData($data) {
          $flag = check_access_odoo($data['NIK']);
            if ($flag == 1) {
                $data = $this->getOdooEmployeePersonalData($data);
            } else {
                $data = $this->getSAPEmployeePersonalData($data);
            }

            return $data;
        }

        public function getSAPEmployeePersonalData($data) {
          $rfc = $this->rfc;

          $fce = saprfc_function_discover($rfc,"ZHRFM_CV");
          if (! $fce )
          {
            echo "System Error. Please Try Again Later."; exit;
          }
          saprfc_import ($fce,"FI_PERNR",$data['NIK']);
          saprfc_import ($fce,"FI_PERNR_DIAKSES",$data['NIK']);
          saprfc_table_init ($fce,"FI_ANAK");
          saprfc_table_init ($fce,"FI_AWARD");
          saprfc_table_init ($fce,"FI_CV");
          saprfc_table_init ($fce,"FI_MEDICAL");
          saprfc_table_init ($fce,"FI_MUTASI");
          saprfc_table_init ($fce,"FI_NSEKOLAH");
          saprfc_table_init ($fce,"FI_PENGLM");
          saprfc_table_init ($fce,"FI_SEKOLAH");
          $rfc_rc = saprfc_call_and_receive ($fce);
          if ($rfc_rc != SAPRFC_OK)
          {
            if ($rfc == SAPRFC_EXCEPTION )
              echo "System Error. Please Try Again Later.";
            else
              echo "System Error. Please Try Again Later."; exit;
          }
          $AUTORISASI = saprfc_export ($fce,"AUTORISASI");
          $rows = saprfc_table_rows ($fce,"FI_CV");
          $FI_CV = saprfc_table_read ($fce,"FI_CV",1);

          $index_name = array(
            'NAMALENGKAP'=> 'NAMALENGKAP',
            'INISIAL'=> 'INISIAL',
            'NAMAPANGGILAN'=> 'NAMAPANGGILAN',
            'TTL'=> 'TTL',
            'TEMPATLLAHIR'=> 'TEMPATLLAHIR',
            'JENISKELAMIN'=> 'JENISKELAMIN',
            'STATUSPERKAWINAN'=> 'STATUSPERKAWINAN',
            'TANGGALPASANGAN'=> 'TANGGALPASANGAN',
            'AGAMA'=> 'AGAMA',
            'GOLDARAH'=> 'GOLDARAH'
          );

          return array(
            'FI_CV' => $FI_CV,
            'index_name' => $index_name
          );
        }

        public function getOdooEmployeePersonalData($data) {
          $NIK = $data["NIK"];

          $FI_CV = json_decode($this->getOdooEmployeeProfile($NIK, 'families'));

          $index_name = array(
            'NAMALENGKAP'=> 'name',
            'INISIAL'=> 'initial_name',
            'NAMAPANGGILAN'=> 'nickname',
            'TTL'=> 'birthday',
            'TEMPATLLAHIR'=> 'place_of_birth',
            'JENISKELAMIN'=> 'gender',
            'STATUSPERKAWINAN'=> 'marital',
            'TANGGALPASANGAN'=> 'marriage_date',
            'AGAMA'=> 'religion_id',
            'GOLDARAH'=> 'blood_type'
          );

          return array(
            'FI_CV' => $FI_CV,
            'index_name' => $index_name
          );

        }
          
        public function getEmployeeContact($data) {
          $flag = check_access_odoo($data['NIK']);
            if ($flag == 1) {
                $data = $this->getOdooEmployeeContact($data);
            } else {
                $data = $this->getSAPEmployeeContact($data);
            }

            return $data;
        }

        public function getSAPEmployeeContact($data){
          $rfc = $this->rfc;

          $fce = saprfc_function_discover($rfc, "ZHRFM_CV");
          if (!$fce) {
            echo "System Error. Please Try Again Later.";
            exit;
          }
          saprfc_import($fce, "FI_PERNR", $data['NIK']);
          saprfc_import($fce, "FI_PERNR_DIAKSES", $data['NIK']);
          saprfc_table_init($fce, "FI_ANAK");
          saprfc_table_init($fce, "FI_AWARD");
          saprfc_table_init($fce, "FI_CV");
          saprfc_table_init($fce, "FI_MEDICAL");
          saprfc_table_init($fce, "FI_MUTASI");
          saprfc_table_init($fce, "FI_NSEKOLAH");
          saprfc_table_init($fce, "FI_PENGLM");
          saprfc_table_init($fce, "FI_SEKOLAH");
          saprfc_table_init($fce, "FI_0105");
          $rfc_rc = saprfc_call_and_receive($fce);
          if ($rfc_rc != SAPRFC_OK) {
            if ($rfc == SAPRFC_EXCEPTION)
              echo "System Error. Please Try Again Later.";
            else
              echo "System Error. Please Try Again Later.";
            exit;
          }
          $AUTORISASI = saprfc_export($fce, "AUTORISASI");
          
          $FI_CV = saprfc_table_read($fce, "FI_CV", 1);
          $FI_0105 = saprfc_table_read($fce, "FI_0105", 1);

          $index_name = array(
            'USRID'=> 'USRID',
            'EMAIL'=> 'EMAIL',
          );

          return array(
            'FI_CV' => $FI_CV,
            'FI_0105' => $FI_0105,
            'index_name' => $index_name
          );

        }

        public function getOdooEmployeeContact($data) {
          $NIK = $data["NIK"];

          $FI_CV = json_decode($this->getOdooEmployeeProfile($NIK, 'families'));

          $index_name = array(
            'USRID'=> 'mobile_phone',
            'EMAIL'=> 'work_email',
          );

          return array(
            'FI_CV' => $FI_CV,
            'FI_0105' => "",
            'index_name' => $index_name
          );

        }

        public function getEmployeePersonalIdentity($data) {
          $flag = check_access_odoo($data['NIK']);
            if ($flag == 1) {
                $data = $this->getOdooEmployeePersonalIdentity($data);
            } else {
                $data = $this->getSAPEmployeePersonalIdentity($data);
            }

            return $data;
        }

        public function getSAPEmployeePersonalIdentity($data) {
          $rfc = $this->rfc;

          $fce = saprfc_function_discover($rfc,"ZHRFM_CV");
          if (! $fce )
          {
            echo "System Error. Please Try Again Later."; exit;
          }
          saprfc_import ($fce,"FI_PERNR",$data['NIK']);
          saprfc_import ($fce,"FI_PERNR_DIAKSES",$data['NIK']);
          saprfc_table_init ($fce,"FI_ANAK");
          saprfc_table_init ($fce,"FI_AWARD");
          saprfc_table_init ($fce,"FI_CV");
          saprfc_table_init ($fce,"FI_MEDICAL");
          saprfc_table_init ($fce,"FI_MUTASI");
          saprfc_table_init ($fce,"FI_NSEKOLAH");
          saprfc_table_init ($fce,"FI_PENGLM");
          saprfc_table_init ($fce,"FI_SEKOLAH");
          $rfc_rc = saprfc_call_and_receive ($fce);
          if ($rfc_rc != SAPRFC_OK)
          {
            if ($rfc == SAPRFC_EXCEPTION )
              echo "System Error. Please Try Again Later.";
            else
              echo "System Error. Please Try Again Later."; exit;
          }
          $AUTORISASI = saprfc_export ($fce,"AUTORISASI");
          $rows = saprfc_table_rows ($fce,"FI_CV");
          $FI_CV = saprfc_table_read ($fce,"FI_CV",1);

          $index_name = array(
            'NOKTP' =>'NOKTP',
            'TAXID' =>'TAXID',
            'NOJAMSOSTEK' =>'NOJAMSOSTEK',
            'NOSIMA' =>'NOSIMA',
            'NOSIMB' =>'NOSIMB',
            'NOSIMC' =>'NOSIMC',
            'NOJAMSOSTEK' => 'NOJAMSOSTEK',
            'NOPASPORT' =>'NOPASPORT'
          );

          return array(
            'FI_CV' => $FI_CV,
            'index_name' => $index_name
          );
        }

        public function getOdooEmployeePersonalIdentity($data) {
          $NIK = $data["NIK"];

          $FI_CV = json_decode($this->getOdooEmployeeProfile($NIK, 'families'));

          $index_name = array(
            'NOKTP' =>'identification_id',
            'TAXID' =>'npwp_no',
            'NOJAMSOSTEK' =>'bpjstk_no',
            'NOSIMA' =>'driving_license_a',
            'NOSIMB' =>'driving_license_b',
            'NOSIMC' =>'driving_license_c',
            'NOJAMSOSTEK' => 'bpjstk_no',
            'NOPASPORT' =>'passport_id'
          );

          return array(
            'FI_CV' => $FI_CV,
            'index_name' => $index_name
          );
        }

        public function getEmployeePersonalAddress($data) {
          $flag = check_access_odoo($data['NIK']);
            if ($flag == 1) {
                $data = $this->getOdooEmployeePersonalAddress($data);
            } else {
                $data = $this->getSAPEmployeePersonalAddress($data);
            }

            return $data;
        }

        public function getSAPEmployeePersonalAddress($data) {
          $rfc = $this->rfc;

          if (! $rfc ) { echo "RFC connection failed"; exit; }
          //Discover interface for function module ZHRFM_RESIDENCE_STATUS
          $fce3 = saprfc_function_discover($rfc,"ZHRFM_RESIDENCE_STATUS");
          if (! $fce3 ) { echo "Discovering interface of function module failed"; exit; }
          //Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
          saprfc_import ($fce3,"FI_PERNR",$data['NIK']);
          saprfc_import ($fce3,"FI_PERNR_DIAKSES",$data['NIK']);
          //Fill internal tables
          saprfc_table_init ($fce3,"FI_RESIDENCE_STATUS");
          saprfc_table_init ($fce3,"FI_T536A");
          //Do RFC call of function ZHRFM_RESIDENCE_STATUS, for handling exceptions use saprfc_exception()
          $rfc_rc = saprfc_call_and_receive ($fce3);
          if ($rfc_rc != SAPRFC_OK) 
          { 
            if ($rfc == SAPRFC_EXCEPTION ) 
            echo ("Exception raised: ".saprfc_exception($fce3)); else echo (saprfc_error($fce)); exit; 
          }
          //Retrieve export parameters
          $AUTORISASI = saprfc_export ($fce3,"AUTORISASI");
          $FI_EXT = saprfc_export ($fce3,"FI_EXT");
          $FI_NOKANTOR = saprfc_export ($fce3,"FI_NOKANTOR");
          $rowsResidenceStatus = saprfc_table_rows ($fce3,"FI_RESIDENCE_STATUS");

          if (! $rfc ) { echo "RFC connection failed"; exit; }
          //Discover interface for function module ZHRFM_ADDRESS
          $fce2 = saprfc_function_discover($rfc,"ZHRFM_ADDRESS");
          if (! $fce2 ) { echo "Discovering interface of function module failed"; exit; }

          //Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
          saprfc_import ($fce2,"FI_PERNR",$data['NIK']);
          saprfc_import ($fce2,"FI_PERNR_DIAKSES",$data['NIK']);
          //Fill internal tables
          saprfc_table_init ($fce2,"FI_1ST_RELATIVES");
          saprfc_table_init ($fce2,"FI_2ND_RELATIVES");
          saprfc_table_init ($fce2,"FI_ID_ADDRESS");
          saprfc_table_init ($fce2,"FI_RESIDENCE_ADDRESS");
          saprfc_table_init ($fce2,"FI_TAX_ADDRESS");
          //Do RFC call of function ZHRFM_ADDRESS, for handling exceptions use saprfc_exception()
          $rfc_rc = saprfc_call_and_receive ($fce2);
          if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce2)); else echo (saprfc_error($fce2)); exit; }
          //Retrieve export parameters
          $AUTORISASI = saprfc_export ($fce2,"AUTORISASI");
          $FI_EXT = saprfc_export ($fce2,"FI_EXT");
          $FI_NOKANTOR = saprfc_export ($fce2,"FI_NOKANTOR");
          $rows1 = saprfc_table_rows ($fce2,"FI_1ST_RELATIVES");
          $rows2 = saprfc_table_rows ($fce2,"FI_2ND_RELATIVES");
          $rows3 = saprfc_table_rows ($fce2,"FI_ID_ADDRESS");
          $rows4 = saprfc_table_rows ($fce2,"FI_RESIDENCE_ADDRESS");
          $rows5 = saprfc_table_rows ($fce2,"FI_TAX_ADDRESS");

          $index_name = array(
            'TYPE'=>'TYPE',
            'STATE'=>'STATE',
            'LAND1'=>'LAND1',
            'STRAS'=>'STRAS',
            'LOCAT'=>'LOCAT',
            'ORT01'=>'ORT01',
            'PSTLZ'=>'PSTLZ',
            'TELNR'=>'TELNR',
            'NAME2'=>'NAME2',
            'AUSBE'=>'AUSBE'
          );

          return array(
            'row1'=>$rows1,
            'row2'=>$rows2,
            'row3'=>$rows3,
            'row4'=>$rows4,
            'row5'=>$rows5,
            'fce3'=>$fce3,
            'fce2'=>$fce2,
            'index_name' => $index_name,
            'rowsResidenceStatus'=>$rowsResidenceStatus
          );
        }

        public function getOdooEmployeePersonalAddress($data) {
          $NIK = $data["NIK"];

          $ADDRESS = json_decode($this->getOdooEmployeeProfile($NIK, 'families'));

          $index_name = array(
            'TYPE'=>'address_type',
            'STATE'=>'state_id',
            'LAND1'=>'country_id',
            'STRAS'=>'street',
            'LOCAT'=>'street2',
            'ORT01'=>'city',
            'PSTLZ'=>'zip',
            'TELNR'=>'phone',
            'NAME2'=>'relatives_name',
            'AUSBE'=>'residence_status'
          );

          $FI_ID_ADDRESS = '';
          $FI_RESIDENCE_ADDRESS = '';
          $FI_TAX_ADDRESS = '';
          $FI_1ST_RELATIVES = '';
          $FI_2ND_RELATIVES = '';

          for ($i=0;$i < sizeof($ADDRESS); $i++) {
            if ($ADDRESS[$i]['address_type'] == 'id') {
              $FI_ID_ADDRESS = $ADDRESS[$i];

            } else if ($ADDRESS[$i]['address_type'] == 'residence') {
              $FI_RESIDENCE_ADDRESS = $ADDRESS[$i];

            } else if ($ADDRESS[$i]['address_type'] == 'tax') {
              $FI_TAX_ADDRESS = $ADDRESS[$i];

            } else if ($ADDRESS[$i]['address_type'] == 'relatives_1') {
              $FI_1ST_RELATIVES = $ADDRESS[$i];

            } else if ($ADDRESS[$i]['address_type'] == 'relatives_2') {
              $FI_2ND_RELATIVES = $ADDRESS[$i];

            }

          }

          return array(
            'index_name' => $index_name,
            'FI_ID_ADDRESS' =>$FI_ID_ADDRESS,
            'FI_RESIDENCE_ADDRESS' =>$FI_RESIDENCE_ADDRESS,
            'FI_TAX_ADDRESS' =>$FI_TAX_ADDRESS,
            'FI_1ST_RELATIVES' =>$FI_1ST_RELATIVES,
            'FI_2ND_RELATIVES' =>$FI_2ND_RELATIVES,
            'row1'=>($FI_1ST_RELATIVES != '') ? sizeof($FI_1ST_RELATIVES) : '',
            'row2'=>($FI_2ST_RELATIVES != '') ? sizeof($FI_2ST_RELATIVES) : '',
            'row3'=>($FI_ID_ADDRESS != '') ? sizeof($FI_ID_ADDRESS) : '',
            'row4'=>($FI_RESIDENCE_ADDRESS != '') ? sizeof($FI_RESIDENCE_ADDRESS) : '',
            'row5'=>($FI_TAX_ADDRESS != '') ? sizeof($FI_TAX_ADDRESS) : '',
          );
        }

        public function getEmployeeFamily($data) {
            $flag = check_access_odoo($data['NIK']);
            if ($flag == 1) {
                $data = $this->getOdooEmployeeFamily($data);
            } else {
                $data = $this->getSAPEmployeeFamily($data);
            }

            return $data;
        }

        
        public function getOdooEmployeeProfile($NIK, $profile_type)
        {

            $ch = curl_init();
            
            if (isset($_SESSION['token_odoo'])) {
                $token = $_SESSION['token_odoo'];
            }
            else {
                $token = $this->libraryInstance->getOdooToken('admin', 'admin');
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
                "nik" => $NIK,
                'profile_type' => $profile_type
            );

            $postdata = json_encode($param);

            $url = "https://kg-pms-odoo-dev1.mykg.id/kg/api/hr/employee-profile";

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
                $token = $this->libraryInstance->getOdooToken('admin', 'admin');
                $this->getOdooEmployeeProfile($NIK, $profile_type);
            } else if ($httpcode == 200)
            {
                return $result;
            } else {
                return $result;
            }

        }

        public function getSAPEmployeeFamily($data) {
          $rfc = $this->rfc;

          $index_name = array(
            'FAMILYSTATUS' => 'ST',
            'GENDER' => 'FASEX',
            'NAME' => 'FCNAM',
            'BIRTHDATE' => 'FGBDT',
            'CHILDNO' => 'OBJPS',
            'COVERED' => 'TERTANGGUNG',
            'STATUS' => 'FAMSA',
            'STARTDATE' => 'BEGDA',
            'DELIMIT' => 'KETERANGAN',
            'FGBOT'=>'FGBOT',
            'FANAT'=>'FANAT',
            'FANATTEXT'=>'FANATTEXT',
            'FGBLD'=>'FGBLD',
            'FGBLDTEXT'=>'FGBLDTEXT',
            'KETERANGAN'=>'KETERANGAN',
            'MARCT' => "MARCT",
            'JOBTL'=> "JOBTL",
            'ZFAMPERNR' => "ZFAMPERNR",
            'ZKETRANGAN' => "ZKETRANGAN"
          );

          if ($data['statusFamily'] == 1) {
              $fce = saprfc_function_discover($rfc,"ZHRFM_CV");
              if (! $fce )
              {
                  echo "System Error. Please Try Again Later."; exit;
              }
              saprfc_import ($fce,"FI_PERNR",$data['NIK']);
              saprfc_import ($fce,"FI_PERNR_DIAKSES",$data['NIK']);
              saprfc_table_init ($fce,"FI_ANAK");
              saprfc_table_init ($fce,"FI_AWARD");
              saprfc_table_init ($fce,"FI_CV");
              saprfc_table_init ($fce,"FI_MEDICAL");
              saprfc_table_init ($fce,"FI_MUTASI");
              saprfc_table_init ($fce,"FI_NSEKOLAH");
              saprfc_table_init ($fce,"FI_PENGLM");
              saprfc_table_init ($fce,"FI_SEKOLAH");
              // saprfc_table_init ($fce,"FI_0318");
              // saprfc_table_init ($fce,"FI_21");
              $rfc_rc = saprfc_call_and_receive ($fce);
              if ($rfc_rc != SAPRFC_OK)
              {
                  if ($rfc == SAPRFC_EXCEPTION )
                      echo "System Error. Please Try Again Later.";
                  else
                      echo "System Error. Please Try Again Later."; exit;
              }
              $AUTORISASI = saprfc_export ($fce,"AUTORISASI");
              $rows = saprfc_table_rows ($fce,"FI_ANAK");
              $FI_ANAK = saprfc_table_read ($fce,"FI_ANAK",1);

              $rows = saprfc_table_rows ($fce,"FI_0318");
              if($rows !=0)
              {
                  $FI_0318 = saprfc_table_read ($fce,"FI_0318",1);
              }

              return array(
                  "FI_ANAK"=>$FI_ANAK,
                  "FI_0318"=>$FI_0318,
                  "index_name"=> $index_name
              );

          } else {

              //Discover interface for function module ZHRFM_FAMILY
              $fce = saprfc_function_discover($rfc,"ZHRFM_FAMILY");
              if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
              //Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
              saprfc_import ($fce,"FI_OBJPS",$data['anakKe']);
              saprfc_import ($fce,"FI_PERNR",$data['NIK']);
              saprfc_import ($fce,"FI_SUBTY",$data['statusFamily']);
              //Fill internal tables
              saprfc_table_init ($fce,"FI_FAMILY");
              //Do RFC call of function ZHRFM_FAMILY, for handling exceptions use saprfc_exception()
              $rfc_rc = saprfc_call_and_receive ($fce);
              if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
              //Retrieve export parameters
              $rows = saprfc_table_rows ($fce,"FI_FAMILY");
              if($rows !=0)
              {
                  $FI_FAMILY = saprfc_table_read ($fce,"FI_FAMILY",1);
              }

              return array(
                  "FI_ANAK"=>$FI_FAMILY,
                  "FI_0318"=>array(
                      'MARCT' => "",
                      'JOBTL'=> "",
                      'ZFAMPERNR' => "",
                      'ZKETRANGAN' => ""
                  ),
                  "index_name"=> $index_name
              );
          }
      }

      public function getOdooEmployeeFamily($data) {

          $periodeAwal = $data["periodeAwal"];
          $famName = $data['famName'];
          $anakKe = $data['anakKe'];
          $NIK = $data['NIK'];
          $row = $data['row'];

          $data = json_decode($this->getOdooEmployeeProfile($NIK, 'families'));

          $FI_ANAK = $data[$row-1];

          $FI_ANAK = array(
              "FAMILYSTATUS"=>'family_relation',
              "GENDER"=>'name',
              'FASEX'=>'gender',
              'BIRTHDATE'=>'birth_date',
              'CHILDNO'=>'child_no',
              'COVERED'=>'medical_reimburse',
              "FAMSA"=> 'family_relation',
              'FGBOT'=>'birth_place',
              'FANAT'=>'country_id',
              'FANATTEXT'=>'country_id',
              'FGBLD'=>'birth_country_id',
              'FGBLDTEXT'=>'birth_country_id',
              'KETERANGAN'=>'uncovered_desc',
              'STARTDATE'=>'valid_start_date',
              'MARCT' => "MARCT",
              'JOBTL'=> "JOBTL",
              'ZFAMPERNR' => "ZFAMPERNR",
              'ZKETRANGAN' => "ZKETRANGAN"
          );

          return array(
              "FI_ANAK"=>$FI_ANAK,
              "FI_0318"=>array(
                  'MARCT' => "",
                  'JOBTL'=> "",
                  'ZFAMPERNR' => "",
                  'ZKETRANGAN' => ""
              )
          );
      }

      public function getEmployeeEducation($data) {
          $flag = check_access_odoo($data['NIK']);
          if ($flag == 1) {
              $data = $this->getOdooEmployeeEducation($data);
          } else {
              $data = $this->getSAPEmployeeEducation($data);
          }

          return $data;
      }

      public function getSAPEmployeeEducation($data) {
          $rfc = $this->rfc;

          $index_name = array(
            'GRADE' => 'JENJANG',
            'NAME' => 'SEKOLAHSD',
            'FACULTY' => 'FAKULTAS',
            'MAJOR' => 'JURUSAN',
            'CITY' => 'NAMAKOTA',
            'COUNTRY' => 'NEGARA',
            'PERIODSTART' => 'PERIODEAWAL',
            'PERIODAKHIR' => 'PERIODEAKHIR',
            "PERIODE"=>'PERIODE',
            'SCORE' => 'NILAI',
            'SLART' => 'SLART',
            "AUSBI" => "AUSBI",
            "SLTP1" => "SLTP1",
            "ZJURUSAN" => "ZJURUSAN",
            "SLAND"=>'SLAND',
            "SERTIFIKAT"=>'SERTIFIKAT',
            "ZCOUNC"=>'ZCOUNC',
            "ZFINALEDU"=>'ZFINALEDU'
          );

          $fce = saprfc_function_discover($rfc,"ZHRFM_FORMAL_EDUCATION");
          if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
          saprfc_import ($fce,"FI_AUSBI",$data['school']);
          saprfc_import ($fce,"FI_BEGDA",$data['periodeAwal']);
          saprfc_import ($fce,"FI_ENDDA",$data['periodeAkhir']);
          saprfc_import ($fce,"FI_FAKULTAS",$data['fakultas']);
          saprfc_import ($fce,"FI_JURUSAN",$data['jurusan']);
          saprfc_import ($fce,"FI_PERNR",$data['NIK']);
          saprfc_import ($fce,"FI_PERNR_DIAKSES",$data['NIK']);
          saprfc_import ($fce,"FI_SLART",$data['grade']);
          //Fill internal tables
          saprfc_table_init ($fce,"FI_SEKOLAH");
          //Do RFC call of function ZHRFM_FORMAL_EDUCATION, for handling exceptions use saprfc_exception()
          $rfc_rc = saprfc_call_and_receive ($fce);
          if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
          //Retrieve export parameters
          $AUTORISASI = saprfc_export ($fce,"AUTORISASI");
          $FI_EXT = saprfc_export ($fce,"FI_EXT");
          $FI_NOKANTOR = saprfc_export ($fce,"FI_NOKANTOR");
          $rows = saprfc_table_rows ($fce,"FI_SEKOLAH");
          $FI_SEKOLAH = saprfc_table_read ($fce,"FI_SEKOLAH",1);

          return array(
            'FI_SEKOLAH' => $FI_SEKOLAH,
            'index_name' => $index_name
          );
      }

      public function getOdooEmployeeEducation($data) {
          $NIK = $data["NIK"];
          $periodeAwal = $data["periodeAwal"];
          $grade = $data["grade"];
          $row = $data['row'];

          $data = json_decode($this->getOdooEmployeeProfile($NIK, 'formal_educations'));

          // if ($grade == "TK") $convGrade = "TK";
          // if ($grade == "SD") $convGrade = "SD";
          // if ($grade == "SP") $convGrade = "SMP";
          // if ($grade == "SA") $convGrade = "SMA";
          // if ($grade == "D1") $convGrade = "Diploma I";
          // if ($grade == "D2") $convGrade = "Diploma II";
          // if ($grade == "D3") $convGrade = "Diploma III";
          // if ($grade == "D4") $convGrade = "Diploma IV";
          // if ($grade == "S1") $convGrade = "Bachelor Degree";
          // if ($grade == "S2") $convGrade = "Master Degree";
          // if ($grade == "S3") $convGrade = "Doctoral Degree";

          $index_name = array(
              'GRADE'=>$edu->type_id[1],
              'NAME'=>$edu->institute_id[1],
              "FACULTY"=>$edu->faculty_id[1],
              "MAJOR"=>$edu->major_id[1],
              "CITY"=>$edu->city,
              "COUNTRY"=>$edu->country_id[1],
              "PERIODSTART"=>date('Ymd', strtotime($edu->enter_year)),
              "PERIODAKHIR"=>date('Ymd', strtotime($edu->qualified_year)),
              "PERIODE"=>substr(date('Ymd', strtotime($edu->qualified_year)),0,4),
              "SCORE"=>$edu->score,
              "SLART"=>$grade,
              "AUSBI"=>$edu->institute_id[1],
              "SLTP1"=>$edu->faculty_id[1],
              "ZJURUSAN"=>$edu->major_id[1],
              "SLAND"=>$edu->country_id[1],
              "SERTIFIKAT"=>$edu->certificate,
              "ZCOUNC"=>$edu->city,
              "ZFINALEDU"=>$edu->is_final_education
          );

          return array(
            'FI_SEKOLAH'=> $data[$row],
            'index_name'=>$index_name
          );
      }

      public function getEmployeeWorkExperience($data) {
          $flag = check_access_odoo($data['NIK']);
          if ($flag == 1) {
              $data = $this->getOdooWorkExperience($data);
          } else {
              $data = $this->getSAPWorkExperience($data);
          }

          return $data;
      }

      public function getSAPWorkExperience($data) {
          //Discover interface for function module ZHRFM_WORK_EXP
          $rfc = $this->rfc;
          $fce = saprfc_function_discover($rfc,"ZHRFM_WORK_EXP");
          if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
          //It's possible to define interface manually. If you would like do it, uncomment following lines:
          //Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
          saprfc_import ($fce,"FI_BEGDA",$data['periodeAwal']);
          saprfc_import ($fce,"FI_EMPLOYER",$data['company']);
          saprfc_import ($fce,"FI_ENDDA",$data['periodeAkhir']);
          saprfc_import ($fce,"FI_PERNR",$data['NIK']);
          saprfc_import ($fce,"FI_PERNR_DIAKSES",$data['NIK']);
          saprfc_import ($fce,"FI_POSITION",$data["position"]);
          //Fill internal tables
          saprfc_table_init ($fce,"FI_EXP");
          //Do RFC call of function ZHRFM_WORK_EXP, for handling exceptions use saprfc_exception()
          $rfc_rc = saprfc_call_and_receive ($fce);
          if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
          //Retrieve export parameters
          $AUTORISASI = saprfc_export ($fce,"AUTORISASI");
          $FI_EXT = saprfc_export ($fce,"FI_EXT");
          $FI_NOKANTOR = saprfc_export ($fce,"FI_NOKANTOR");
          $rows = saprfc_table_rows ($fce,"FI_EXP");
          $FI_EXP = saprfc_table_read ($fce,"FI_EXP",1);
          
          $index_name = array(
            'ARBGB' => 'ARBGB',
            'COMPANY'=> 'COMPANY',
            'CITY' => 'CITY',
            'NEGARA'=> 'NEGARA',
            'BEGDA'=> 'BEGDA',
            'ENDDA'=> 'ENDDA',
            'JABATAN'=> 'JABATAN',
            "COUNTRYKEY"=>"COUNTRYKEY",
            'POSITIONS'=>"POSITIONS" 
        );

        return array(
          'FI_EXP' => $FI_EXP,
          'index_name' => $index_name
        );

      }

      public function getOdooWorkExperience($data) {
          $NIK = $data["NIK"];
          $periodeAwal = $data["periodeAwal"];
          $periodeAkhir = $data["periodeAkhir"];
          $Company = $data["company"];
          $row = $data['row'];

          $data = json_decode($this->getOdooEmployeeProfile($NIK, 'work_experiences'));

          $work = $data[$row];
          
          $index_name = array(
              'ARBGB' => 'employeer',
              'COMPANY'=> 'employeer',
              'CITY' => 'city',
              'NEGARA'=> 'country_id',
              'BEGDA'=> 'from_date',
              'ENDDA'=> 'to_date',
              'JABATAN'=> 'job_name',
              "COUNTRYKEY"=>"",
              'POSITIONS'=>"" # TO BE DISCUSSED
          );

          return array(
            'FI_EXP' => $work,
            'index_name' => $index_name
          );
      }

      public function getEmployeeCertifications($data) {
          $flag = check_access_odoo($data['NIK']);
          if ($flag == 1) {
              $data = $this->getOdooCertifications($data);
          } else {
              $data = $this->getSAPCertifications($data);
          }

          return $data;
      }

      public function getSAPCertifications($data){
          $rfc = $this->rfc;
          $fce = saprfc_function_discover($rfc,"ZHRFM_NONFORMAL_EDUCATION");
          if (! $fce ) { echo "Discovering interface of function module failed"; exit; }

          saprfc_import ($fce,"FI_BEGDA",$data['periodeAwal']);
          saprfc_import ($fce,"FI_ENDDA",$data['periodeAkhir']);
          saprfc_import ($fce,"FI_INSTI",$data['institution']);
          saprfc_import ($fce,"FI_KSBEZ",$data['course']);
          saprfc_import ($fce,"FI_PERNR",$data['NIK']);
          saprfc_import ($fce,"FI_PERNR_DIAKSES",$data['NIK']);
          //Fill internal tables
          saprfc_table_init ($fce,"FI_SEKOLAH");
          //Do RFC call of function ZHRFM_FORMAL_EDUCATION, for handling exceptions use saprfc_exception()
          $rfc_rc = saprfc_call_and_receive ($fce);
          if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
          //Retrieve export parameters
          $AUTORISASI = saprfc_export ($fce,"AUTORISASI");
          $FI_EXT = saprfc_export ($fce,"FI_EXT");
          $FI_NOKANTOR = saprfc_export ($fce,"FI_NOKANTOR");
          $rows = saprfc_table_rows ($fce,"FI_SEKOLAH");
          $FI_SEKOLAH = saprfc_table_read ($fce,"FI_SEKOLAH",1);

          $fce_country = saprfc_function_discover($rfc,"ZHRFM_PD_COUNTRY");
          if (! $fce_country ) { echo "Discovering interface of function module failed"; exit; }
          saprfc_table_init ($fce_country,"FI_T005T");
          //Do RFC call of function ZHRFM_PD_CITY, for handling exceptions use saprfc_exception()
          $rfc_rc = saprfc_call_and_receive ($fce_country);
          if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce_country)); else echo (saprfc_error($fce_country)); exit; }
          $rows_country = saprfc_table_rows ($fce_country,"FI_T005T");

          $index_name = array(
            "TYPENAME" => 'NAMAJENJANG',
            "TYPE" => "JENJANG",
            "NAME" => 'KSBEZ',
            'INSTITUTE' => 'LEMBAGA',
            'STARTDATE' => 'PERIODEAWAL',
            'ENDDATE' => 'PERIODEAKHIR',
            'ACTION' => 'ZBIAYA',
            'COUNTRY' => 'NEGARA',
            'PAYMENT' => 'BIAYA',         
            "ANZKL"=>"ANZKL",
            'ANZEH'=>"ANZEH", 
            "SERTIFIKAT"=>"SERTIFIKAT",
            "SLAND"=>"SLAND",
            'HARI'=>"HARI",
          );

          return array(
              "FI_SEKOLAH"=>$FI_SEKOLAH,
              "ROW_COUNTRY"=>$rows_country,
              "fce_country"=>$fce_country,
              "index_name" => $index_name
          );
      }

      public function getOdooCertifications($data) {
          $NIK = $data["NIK"];
          $periodeAwal = $data["periodeAwal"];
          $institution = $data["institution"];
          $course = $data["course"];
          $row = $data['row'];

          $certData = json_decode($this->getOdooEmployeeProfile($NIK, 'training_courses'));
          
          $FI_SEKOLAH = $certArray[$row-1];

          $index_name = array(
              "TYPE"=>"",
              "TYPENAME"=>'training_type',
              "NAME"=>'course_name',
              "INSTITUTE"=>'institute_name',
              'ACTION'=>'cost_center',
              "ANZKL"=>'duration_of_course',
              'ANZEH'=>'duration_time',
              "COUNTRY"=>'country_id',
              "STARTDATE"=>'from_date',
              "ENDDATE"=>'to_date',
              "SERTIFIKAT"=>'certificate',
              "PAYMENT"=>'cost_center',
              "SLAND"=>'country_id',
              'HARI'=>'duration_of_course',
          );

          
          return array(
              "FI_SEKOLAH"=>$FI_SEKOLAH,
              "ROW_COUNTRY"=>0, # PREVENT ERROR ON TrainingUpdateData
              'index_name' => $index_name
          );
      }

        function getRows($name, $fce, $data) {
            $rows = 0;
            if (array_key_exists($fce, $data)) {
              $rows = saprfc_table_rows($data[$fce], $name);
            } else {
              $rows = sizeof($data[$name]['data']);
            }
        
            return $rows;
          }

        public function showFamily($fce=null, $data=null){
            $NIK = $this->NIK;
            // GET data
            $data = (isset($data)) ? $data : $this->profileGetter($NIK);
            $fce = (isset($fce)) ? $fce : 'fce';

            $rows = $this->getRows("FI_ANAK", $fce, $data);
            $conn = $this->conn;
            $IS_SAP = $data['IS_SAP'];
            $NIK = $this->NIK;
                ?>
                  <h3>
                      Family 
                      <?php

                      if ($IS_SAP) echo '<a href="PD_FamilyChildData.php?keepThis=true&TB_iframe=true&height=500&width=700"
                        style="font-size:10px" title="add child" class="thickbox"><img src="img/icon/addChild.png"
                        border="none" /></a>';

                  // $GetStatusRead=odbc_exec($conn,"select COUNT(*) as ReadTotal
                  //         from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestDetail B on
                  //         A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID inner join PD_ChangeRequest C on
                  //         B.ChangeRequestID=C.ChangeRequestID
                  //         inner join PD_Field D on D.FieldID=B.FieldID
                  //         where NIK='$NIK' and StatusReadEmployee='False' and Status='2' and
                  //         FlagPA=1 and SectionID=6");

                  odbc_execute($GetStatusRead = odbc_prepare($conn,"select COUNT(*) as ReadTotal
                          from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestDetail B on
                          A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID inner join PD_ChangeRequest C on
                          B.ChangeRequestID=C.ChangeRequestID
                          inner join PD_Field D on D.FieldID=B.FieldID
                          where NIK=? and StatusReadEmployee='False' and Status='2' and
                          FlagPA=1 and SectionID=6"), array($NIK));

                $totalRead = odbc_result($GetStatusRead, "ReadTotal");
                if ($totalRead != 0 && $IS_SAP) {
                  echo '<a href="PD_ViewListReadPersonalData.php?userNIK=' . $NIK . '&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/informationmessage.gif" border="none" /></a>';
                } elseif ($totalRead == 0) {

                    //cek pengajuan request ada atau tidak
                    // $GetCekStatusRequest=odbc_exec($conn,"select COUNT(*) as TotalRequest from
                    //                     PD_ChangeRequestDetail A
                    //                     inner join PD_Field B on A.FieldID=B.FieldID
                    //                     inner join PD_ChangeRequestDetailUpdate C on
                    //                     A.ChangeRequestInfotypeID=C.ChangeRequestInfotypeID
                    //                     where UserNIK='$NIK' AND SectionID=6");

                    odbc_execute($GetCekStatusRequest = odbc_prepare($conn,"select COUNT(*) as TotalRequest from
                                        PD_ChangeRequestDetail A
                                        inner join PD_Field B on A.FieldID=B.FieldID
                                        inner join PD_ChangeRequestDetailUpdate C on
                                        A.ChangeRequestInfotypeID=C.ChangeRequestInfotypeID
                                        where UserNIK=? AND SectionID=6"), array($NIK));

                    $TotalRequest=odbc_result($GetCekStatusRequest,"TotalRequest");

                  if ($TotalRequest != 0) {

                      $getDataPD="select distinct ChangeRequestInfotypeID as chrinfotypeid,[RequestDate], InfoTypeName,FieldContent,NIK
                            from VW_PDPersonalData where NIK=? and SectionID=6";

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

                    if ($flag == 'false' && $IS_SAP) {
                      echo '<a href="PD_ViewListReadPersonalData.php?userNIK=' . $NIK . '&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/information.png" border="none" /></a>';
                    }
                  }
                }

                if ($IS_SAP) echo '<a href="PD_FamilyDetail.php?keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px"
                  title="Family Detail" class="thickbox"><img src="img/icon/detailFamily.png" border="none" /></a>';
                ?>
                
              </h3>
              <table class="table table-striped table-bordered table-condensed">
                <thead>
                  <tr>
                    <th width="120">Family 
                      Status</th>
                    <th width="400">Name</th>
                    <th width="100">Gender</th>
                    <th width="100" style="text-align:center">Date of Birth</th>
                    <th width="80" style="text-align:right">Child No.</th>
                    <th width="80">Covered</th>
                    <th width="80">Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    for($i=1; $i<=$rows; $i++){

                      if (array_key_exists('fce', $data)) {
                        $FI_ANAK = saprfc_table_read($data['fce'],"FI_ANAK",$i);
                      } else { 
                        $FI_ANAK = $data["FI_ANAK"]['data'][$i-1];
                      }

                      $index_name = $data['FI_ANAK']['index_name'];
                      $status_delimit = "";

                      if (array_key_exists('fce', $data)) {
                        if($FI_ANAK[$index_name['DELIMIT']]=='01') 
                          { $status_delimit='Death';}
                        if ($FI_ANAK[$index_name['DELIMIT']]=='02')
                          { $status_delimit='Divorced';}
                        if ($FI_ANAK[$index_name['DELIMIT']]=='03')
                          { $status_delimit='Married';}
                        if ($FI_ANAK[$index_name['DELIMIT']]=='04')
                          { $status_delimit='Age > 26 years';}
                        if ($FI_ANAK[$index_name['DELIMIT']] =='05')
                          { $status_delimit='Working';}
                        if ($FI_ANAK[$index_name['DELIMIT']] =='06')
                          { $status_delimit='Graduated';}
                      } else {
                        if ($FI_ANAK[$index_name['DELIMIT']] == 'death') $status_delimit = 'Death';
                        if ($FI_ANAK[$index_name['DELIMIT']] == 'divorce') $status_delimit = 'Divorce';
                        if ($FI_ANAK[$index_name['DELIMIT']] == 'married') $status_delimit = 'Married';
                        if ($FI_ANAK[$index_name['DELIMIT']] == 'age26') $status_delimit = 'Age > 26 Years Old';
                        if ($FI_ANAK[$index_name['DELIMIT']] == 'age23') $status_delimit = 'Age > 23 Years Old';
                        if ($FI_ANAK[$index_name['DELIMIT']] == 'graduated') $status_delimit = 'Graduated';
                        if ($FI_ANAK[$index_name['DELIMIT']] == 'worked') $status_delimit = 'Worked';
                        if ($FI_ANAK[$index_name['DELIMIT']] == 'child2') $status_delimit = 'Child No > 2';
                      }
                      
                      $tertanggung = $FI_ANAK[$index_name['COVERED']] ? "Yes" : "No" ;
                      $FI_ANAK[$index_name['BIRTHDATE']] = $this->matchDate($FI_ANAK[$index_name['BIRTHDATE']]);
                      
                      echo '<tr>';
                      echo '<td>'.ucwords($FI_ANAK[$index_name['FAMILYSTATUS']]).'</td>';
                      echo '<td>'.$FI_ANAK[$index_name['NAME']].'</td>';
                      if($FI_ANAK[$index_name['GENDER']]==1){
                        $faSex="Male";
                      }else{
                        $faSex="Female";
                      }
                      echo '<td>'.$faSex.'</td>';
                      echo '<td style="text-align:center">'.convertDate2($FI_ANAK[$index_name['BIRTHDATE']]).'</td>';
                      $child_no = $FI_ANAK[$index_name['CHILDNO']] > 0 ? $FI_ANAK[$index_name['CHILDNO']] : " ";
                      echo '<td style="text-align:right">'.$child_no.'</td>';
                      echo '<td>'.$tertanggung.'</td>';
                      echo '<td>'.ucwords($status_delimit).'</td>';
                      if ($IS_SAP)
                        echo '<td style="text-align:center;"><a href="PD_FamilyChildDataUpdate.php?subType='.$FI_ANAK['FAMSA'].'&objps='.$FI_ANAK['OBJPS']. '&periodeAwal=' . $FI_ANAK["BEGDA"] . '&famName=' . $FI_ANAK['FCNAM'] . '&row_family=' . $i .'&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/edit.png" border="none" /></a></td>';
                      else
                        echo '<td></td>';
                      echo '</tr>';
                    }
                  
                  ?>
                </tbody>
              </table>
              <?php
        }

        function showFormalEducation($fce=null, $data=null){
            $NIK = $this->NIK;
            // GET data
            $data = (isset($data)) ? $data : $this->profileGetter($NIK);
            $fce = (isset($fce)) ? $fce : 'fce';

            $rows = $this->getRows("FI_SEKOLAH", $fce, $data);
            $conn = $this->conn;
            $NIK = $this->NIK;
            $IS_SAP = $data['IS_SAP'];

            // REQUIRED IF CALLED INSIDE <?php 
            ?>
            <h3>Formal Education 

              <?php
        
                 if ($IS_SAP) echo '<a href="PD_FormalEducationData.php?keepThis=true&TB_iframe=true&height=500&width=700"
                style="font-size:10px" title="Add Education" class="thickbox"><img src="img/icon/addEducation.png"
                  border="none" /></a>';
        
                  // $GetStatusRead=odbc_exec($conn,"select COUNT(*) as ReadTotal
                  //         from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestDetail B on
                  //         A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID inner join PD_ChangeRequest C on
                  //         B.ChangeRequestID=C.ChangeRequestID
                  //         inner join PD_Field D on D.FieldID=B.FieldID
                  //         where NIK='$NIK' and StatusReadEmployee='False' and Status='2' and
                  //         FlagPA=1 and SectionID=7");

                  odbc_execute($GetStatusRead = odbc_prepare($conn,"select COUNT(*) as ReadTotal
                          from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestDetail B on
                          A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID inner join PD_ChangeRequest C on
                          B.ChangeRequestID=C.ChangeRequestID
                          inner join PD_Field D on D.FieldID=B.FieldID
                          where NIK=? and StatusReadEmployee='False' and Status='2' and
                          FlagPA=1 and SectionID=7"), array($NIK));

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
                    //                     where UserNIK='$NIK' AND SectionID=7");

                    odbc_execute($GetCekStatusRequest = odbc_prepare($conn,"select COUNT(*) as TotalRequest from
                                        PD_ChangeRequestDetail A
                                        inner join PD_Field B on A.FieldID=B.FieldID
                                        inner join PD_ChangeRequestDetailUpdate C on
                                        A.ChangeRequestInfotypeID=C.ChangeRequestInfotypeID
                                        where UserNIK=? AND SectionID=7"), array($NIK));

                $TotalRequest = odbc_result($GetCekStatusRequest, "TotalRequest");

                if ($TotalRequest != 0) {

                  $getDataPD = "select distinct ChangeRequestInfotypeID as chrinfotypeid,[RequestDate], InfoTypeName,FieldContent,NIK
                            from VW_PDPersonalData where NIK=? and SectionID=7";

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
            <table class="table table-striped table-bordered table-condensed">
              <thead>
                <tr>
                  <th width="30">Grade</th>
                  <th width="200">Name</th>
                  <th width="180">Faculty</th>
                  <th width="150">Major</th>
                  <th width="150">Location</th>
                  <th width="70" style="text-align:center">Period</th>
                  <th width="50" style="text-align:right">G.P.A</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php

                  $rows = $this->getRows('FI_SEKOLAH', $fce, $data);

                  for($i=1; $i<=$rows; $i++){
                    if (array_key_exists('fce', $data)) {
                      $FI_SEKOLAH = saprfc_table_read($data['fce'],'FI_SEKOLAH',$i);
                    } else {
                      $FI_SEKOLAH = $data['FI_SEKOLAH']['data'][$i-1];
                    }

                    $index_name = $data['FI_SEKOLAH']['index_name'];

                    $FI_SEKOLAH[$index_name['GRADE']] = $this->isIDField($FI_SEKOLAH[$index_name['GRADE']]);
                    $FI_SEKOLAH[$index_name['NAME']] = $this->isIDField($FI_SEKOLAH[$index_name['NAME']]);
                    $FI_SEKOLAH[$index_name['FACULTY']] = $this->isIDField($FI_SEKOLAH[$index_name['FACULTY']]);
                    $FI_SEKOLAH[$index_name['MAJOR']] = $this->isIDField($FI_SEKOLAH[$index_name['MAJOR']]);
                    $FI_SEKOLAH[$index_name['COUNTRY']] = $this->isIDField($FI_SEKOLAH[$index_name['COUNTRY']]);

                    // if ($edu->type_id[1] == "TK") $grade = "TK";
                    // if ($edu->type_id[1] == "SD") $grade = "SD";
                    // if ($edu->type_id[1] == "SMP") $grade = "SP";
                    // if ($edu->type_id[1] == "SMA") $grade = "SA";
                    // if ($edu->type_id[1] == "Diploma I") $grade = "D1";
                    // if ($edu->type_id[1] == "Diploma II") $grade = "D2";
                    // if ($edu->type_id[1] == "Diploma III") $grade = "D3";
                    // if ($edu->type_id[1] == "Diploma IV") $grade = "D4";
                    // if ($edu->type_id[1] == "Bachelor Degree") $grade = "S1";
                    // if ($edu->type_id[1] == "Master Degree") $grade = "S2";
                    // if ($edu->type_id[1] == "Doctoral Degree") $grade = "S3";

                    echo '<tr>';
                    echo '<td>'.$FI_SEKOLAH[$index_name['GRADE']].'</td>';
                    echo '<td>'.$FI_SEKOLAH[$index_name['NAME']].'</td>';
                    if ($FI_SEKOLAH[$index_name['FACULTY']]!= "00000"){
                      $fsFak = ucwords(strtolower($FI_SEKOLAH[$index_name['FACULTY']]));
                    }else{
                      $fsFak = '-';
                    }
                    echo '<td>'.$fsFak.'</td>';
                    echo '<td>'.ucwords(strtolower($FI_SEKOLAH[$index_name['MAJOR']])).'</td>';
                    echo '<td>'.$FI_SEKOLAH[$index_name['CITY']].' - '.$FI_SEKOLAH[$index_name['COUNTRY']].'</td>';
                    echo '<td style="text-align:center">'.substr($FI_SEKOLAH[$index_name['PERIODSTART']],0,4).' - '.substr($FI_SEKOLAH[$index_name['PERIODAKHIR']],0,4).'</td>';
                    echo '<td style="text-align:right">'.$FI_SEKOLAH[$index_name['SCORE']].'</td>';
                    if ($IS_SAP)
                      echo '<td style="text-align:center;"><a href="PD_FormalEducationUpdateData.php?periodeAwal='.$FI_SEKOLAH[$index_name['PERIODSTART']].'&periodeAkhir='.$FI_SEKOLAH[$index_name['PERIODAKHIR']].'&grade='.$FI_SEKOLAH[$index_name['SLART']].'&school='.urlencode($FI_SEKOLAH[$index_name['AUSBI']]).'&fakultas='.$FI_SEKOLAH['SLTP1'].'&jurusan='.$FI_SEKOLAH['ZJURUSAN'].'&row='.$rows.'&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/edit.png" border="none" /></a></td>';
                    else
                      echo '<td></td>';
                    echo '</tr>';
                  }
                
                ?>
              </tbody>
            </table>
          <?php
        }

        function showTraining($fce=null, $data=null) {
            $NIK = $this->NIK;
            // GET data
            $data = (isset($data)) ? $data : $this->profileGetter($NIK);
            $fce = (isset($fce)) ? $fce : 'fce';

            $rows = $this->getRows("FI_NSEKOLAH", $fce, $data);
            $conn = $this->conn;
            $NIK = $this->NIK;
            $IS_SAP = $data['IS_SAP'];

            ?>
            <h3>
              
              Training & Courses 

                <?php

              if ($IS_SAP) echo '<a href="PD_TrainingData.php?keepThis=true&TB_iframe=true&height=500&width=700"
                style="font-size:10px" title="Add Training" class="thickbox"><img src="img/icon/addTraining.png"
                border="none" /></a>';
        
                    // $GetStatusRead=odbc_exec($conn,"select COUNT(*) as ReadTotal
                    //         from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestDetail B on
                    //         A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID inner join PD_ChangeRequest C on
                    //         B.ChangeRequestID=C.ChangeRequestID
                    //         inner join PD_Field D on D.FieldID=B.FieldID
                    //         where NIK='$NIK' and StatusReadEmployee='False' and Status='2' and
                    //         FlagPA=1 and SectionID=8");


                    odbc_execute($GetStatusRead = odbc_prepare($conn,"select COUNT(*) as ReadTotal
                            from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestDetail B on
                            A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID inner join PD_ChangeRequest C on
                            B.ChangeRequestID=C.ChangeRequestID
                            inner join PD_Field D on D.FieldID=B.FieldID
                            where NIK=? and StatusReadEmployee='False' and Status='2' and
                            FlagPA=1 and SectionID=8"), array($NIK));

              $totalRead = odbc_result($GetStatusRead, "ReadTotal");
              if ($totalRead != 0 && $IS_SAP) {
                echo '<a href="PD_ViewListReadPersonalData.php?userNIK=' . $NIK . '&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/informationmessage.gif" border="none" /></a>';
              } elseif ($totalRead == 0) {

                //cek pengajuan request ada atau tidak
                      // $GetCekStatusRequest=odbc_exec($conn,"select COUNT(*) as TotalRequest from
                      //                     PD_ChangeRequestDetail A
                      //                     inner join PD_Field B on A.FieldID=B.FieldID
                      //                     inner join PD_ChangeRequestDetailUpdate C on
                      //                     A.ChangeRequestInfotypeID=C.ChangeRequestInfotypeID
                      //                     where UserNIK='$NIK' AND SectionID=8");

                      odbc_execute($GetCekStatusRequest = odbc_prepare($conn,"select COUNT(*) as TotalRequest from
                                          PD_ChangeRequestDetail A
                                          inner join PD_Field B on A.FieldID=B.FieldID
                                          inner join PD_ChangeRequestDetailUpdate C on
                                          A.ChangeRequestInfotypeID=C.ChangeRequestInfotypeID
                                          where UserNIK=? AND SectionID=8"), array($NIK));
                $TotalRequest = odbc_result($GetCekStatusRequest, "TotalRequest");

                if ($TotalRequest != 0) {

                  $getDataPD = "select distinct ChangeRequestInfotypeID as chrinfotypeid,[RequestDate], InfoTypeName,FieldContent,NIK
                              from VW_PDPersonalData where NIK=? and SectionID=8";

                        // $rowSet = odbc_exec($conn,$getDataPD);
                        odbc_execute($rowSet = odbc_prepare($conn,$getDataPD), array($NIK));

                  while ($row = odbc_fetch_row($rowSet)) {
                    $idUpdateNotes = odbc_result($rowSet, 'chrinfotypeid');
                    $getNotes = "select *  from PD_ChangeRequestDetailUpdate where ChangeRequestInfotypeID='" . $idUpdateNotes . "' order by Date desc";
                          // $NotesReply = odbc_exec($conn,$getNotes);

                          odbc_execute($NotesReply = odbc_prepare($conn,$getNotes), array($idUpdateNotes));

                    $flag = 'true';
                    if (odbc_result($NotesReply, 'Status') != 3 && odbc_result($NotesReply, 'Status') != 4 && odbc_result($NotesReply, 'Status') != 6) {
                      $flag = 'false';
                      break;
                    }

                  }

                  if ($flag == 'false' && $IS_SAP) {
                    echo '<a href="PD_ViewListReadPersonalData.php?userNIK=' . $NIK . '&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/information.png" border="none" title="list of requests"/></a>';
                  }
                }
              }
              ?>
            </h3>
            <table class="table table-striped table-bordered table-condensed">
              <thead>
                <tr>
                  <th width="100">Type</th>
                  <th>Name</th>
                  <th width="200">Institution Name</th>
                  <th width="50">Start Date</th>
                  <th width="50">End Date</th>
                  <th width="30">Payment</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php

                for($i=1; $i<=$rows; $i++){
                  
                    if (array_key_exists('fce', $data)) {
                      $FI_NSEKOLAH = saprfc_table_read($data['fce'],"FI_NSEKOLAH", $i); 
                      $duration_time = $FI_NSEKOLAH['ANZKL'];
                      $index_name = $data['FI_NSEKOLAH']['index_name'];
                    } else {
                      $FI_NSEKOLAH = $data['FI_NSEKOLAH']['data'][$i-1];
                      $index_name = $data['FI_NSEKOLAH']['index_name'];
                      $duration_time = $FI_NSEKOLAH['duration_of_course']. " " .$FI_NSEKOLAH['duration_time'];
                    }

                    $FI_NSEKOLAH[$index_name['INSTITUTE']] = $this->isIDField($FI_NSEKOLAH[$index_name['INSTITUTE']]);
                    $FI_NSEKOLAH[$index_name['COUNTRY']] = $this->isIDField($FI_NSEKOLAH[$index_name['COUNTRY']]);
                    $FI_NSEKOLAH[$index_name['ACTION']] = $this->isIDField($FI_NSEKOLAH[$index_name['ACTION']]);

                    $FI_NSEKOLAH[$index_name['STARTDATE']] = $this->matchDate($FI_NSEKOLAH[$index_name['STARTDATE']]);
                    $FI_NSEKOLAH[$index_name['ENDDATE']] = $this->matchDate($FI_NSEKOLAH[$index_name['ENDDATE']]);

                    switch ($FI_NSEKOLAH[$index_name['TYPE']]) {
                      case "dik_fu":
                        $FI_NSEKOLAH[$index_name['TYPE']] = "Diklat Fungsional";
                        break;
                      case "dik_um":
                        $FI_NSEKOLAH[$index_name['TYPE']] = "Diklat Umum";
                        break;
                      case "dik_man":
                        $FI_NSEKOLAH[$index_name['TYPE']] = "Diklat Manajerial";
                        break;
                      case "dev_mdp":
                        $FI_NSEKOLAH[$index_name['TYPE']] = "Dev. Program MDP";
                        break;
                      case "dev_mmdp":
                        $FI_NSEKOLAH[$index_name['TYPE']] = "Dev. Program MMDP";
                        break;
                    }

                    switch ($FI_NSEKOLAH[$index_name['PAYMENT']]) {
                      case 'company':
                        $FI_NSEKOLAH[$index_name['PAYMENT']] = "Company Expenses";
                        break;
                      case 'personal':
                        $FI_NSEKOLAH[$index_name['PAYMENT']] = "Personal Expenses";
                        break;
                      case 'sponsor':
                        $FI_NSEKOLAH[$index_name['PAYMENT']] = "Sponsorship Expenses";
                        break;
                      case 'sponsor_company':
                        $FI_NSEKOLAH[$index_name['PAYMENT']] = "Sponsorship Company";
                        break;
                    }

                    echo '<tr>';
                    echo '<td>'.$FI_NSEKOLAH[$index_name['TYPE']].'</td>';
                    echo '<td>'.$FI_NSEKOLAH[$index_name['NAME']].'</td>';
                    echo '<td>'.$FI_NSEKOLAH[$index_name['INSTITUTE']].'-'.$FI_NSEKOLAH[$index_name['ACTION']].'-'.$duration_time.' '.$FI_NSEKOLAH[$index_name['COUNTRY']].'</td>';
                    echo '<td>'.convertDate2($FI_NSEKOLAH[$index_name['STARTDATE']]).'</td>';
                    echo '<td>'.convertDate2($FI_NSEKOLAH[$index_name['ENDDATE']]).'</td>';
                    echo '<td>'.$FI_NSEKOLAH[$index_name['PAYMENT']].'</td>';
                    if ($IS_SAP)
                      echo '<td style="text-align:center;"><a href="PD_TrainingUpdateData.php?periodeAwal='.$FI_NSEKOLAH[$index_name['STARTDATE']].'&periodeAkhir='.$FI_NSEKOLAH[$index_name['ENDDATE']].'&institution='.urlencode($FI_NSEKOLAH[$index_name['NAME']]).'&course='.urlencode($FI_NSEKOLAH[$index_name['TYPE']]).'&isSap='.$IS_SAP.'&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/edit.png" border="none" /></a></td>';
                    else
                      echo '<td></td>';
                    echo '</tr>';
                  }
                ?>
              </tbody>
            </table>

          <?php
        }

        function showExperience($fce=null, $data=null) {
            $NIK = $this->NIK;
            // GET data
            $data = (isset($data)) ? $data : $this->profileGetter($NIK);
            $fce = (isset($fce)) ? $fce : 'fce';

            $rows = $this->getRows("FI_PENGLM", $fce, $data);
            $conn = $this->conn;
            $NIK = $this->NIK;
            $IS_SAP = $data['IS_SAP'];

            ?>
            <h3>
              Work Experience 

              <?php

              if ($IS_SAP) echo '<a href="PD_WorkData.php?keepThis=true&TB_iframe=true&height=500&width=700"
                style="font-size:10px" title="Add Work Experience" class="thickbox"><img src="img/icon/addWork.png"
                border="none" /></a>';

                    // $GetStatusRead=odbc_exec($conn,"select COUNT(*) as ReadTotal
                    //         from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestDetail B on
                    //         A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID inner join PD_ChangeRequest C on
                    //         B.ChangeRequestID=C.ChangeRequestID
                    //         inner join PD_Field D on D.FieldID=B.FieldID
                    //         where NIK='$NIK' and StatusReadEmployee='False' and Status='2' and
                    //         FlagPA=1 and SectionID=9");


                    odbc_execute($GetStatusRead = odbc_prepare($conn,"select COUNT(*) as ReadTotal
                            from PD_ChangeRequestDetailUpdate A inner join PD_ChangeRequestDetail B on
                            A.ChangeRequestInfotypeID=B.ChangeRequestInfotypeID inner join PD_ChangeRequest C on
                            B.ChangeRequestID=C.ChangeRequestID
                            inner join PD_Field D on D.FieldID=B.FieldID
                            where NIK=? and StatusReadEmployee='False' and Status='2' and
                            FlagPA=1 and SectionID=9"), array($NIK));

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
                      //                     where UserNIK='$NIK' AND SectionID=9");

                      odbc_execute($GetCekStatusRequest = odbc_prepare($conn,"select COUNT(*) as TotalRequest from
                                          PD_ChangeRequestDetail A
                                          inner join PD_Field B on A.FieldID=B.FieldID
                                          inner join PD_ChangeRequestDetailUpdate C on
                                          A.ChangeRequestInfotypeID=C.ChangeRequestInfotypeID
                                          where UserNIK=? AND SectionID=9"), array($NIK));
                $TotalRequest = odbc_result($GetCekStatusRequest, "TotalRequest");

                if ($TotalRequest != 0) {

                  $getDataPD = "select distinct ChangeRequestInfotypeID as chrinfotypeid,[RequestDate], InfoTypeName,FieldContent,NIK
                              from VW_PDPersonalData where NIK=? and SectionID=9";

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

                  if ($IS_SAP) {
                    echo '<a href="PD_ViewListReadPersonalData.php?userNIK=' . $NIK . '&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/information.png" border="none" /></a>';
                  }
                }
              }
              ?>
            </h3>
            <table class="table table-striped table-bordered table-condensed">
              <thead>
                <tr>
                  <th>Company</th>
                  <th>Company Address</th>
                  <th width="150">Position</th>
                  <th width="50">Start Date</th>
                  <th width="50">End Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php

                for($i=1; $i<=$rows; $i++){
                  
                    if (array_key_exists('fce', $data)) {
                      $FI_PENGLM = saprfc_table_read($data['fce'], "FI_PENGLM", $i); 
                      $index_name = $data['FI_PENGLM']['index_name'];
                    } else {
                      $FI_PENGLM = $data['FI_PENGLM']['data'][$i-1];
                      $index_name = $data['FI_PENGLM']['index_name'];
                    }
                    
                    $FI_PENGLM[$index_name['NEGARA']] = $this->isIDField($FI_PENGLM[$index_name['NEGARA']]);

                    $FI_PENGLM[$index_name['STARTDATE']] = $this->matchDate($FI_PENGLM[$index_name['STARTDATE']]);
                    $FI_PENGLM[$index_name['ENDDATE']] = $this->matchDate($FI_PENGLM[$index_name['ENDDATE']]);

                    echo '<tr>';
                    echo '<td>'.$FI_PENGLM[$index_name['COMPANY']].'</td>';
                    echo '<td>'.$FI_PENGLM[$index_name['COMPANYADDRESS']].' - '.ucwords(strtoupper($FI_PENGLM[$index_name['CITY']])).' - '.ucwords(strtoupper($FI_PENGLM[$index_name['NEGARA']])).'</td>';
                    echo '<td>'.$FI_PENGLM[$index_name['POSITION']].'</td>';
                    echo '<td>'.convertDate2($FI_PENGLM[$index_name['STARTDATE']]).'</td>';
                    echo '<td>'.convertDate2($FI_PENGLM[$index_name['ENDDATE']]).'</td>';
                    if ($IS_SAP)
                      echo '<td style="text-align:center;"><a href="PD_WorkUpdateData.php?periodeAwal='.$FI_PENGLM[$index_name['STARTDATE']].'&periodeAkhir='.$FI_PENGLM[$index_name['ENDDATE']].'&Company='.$FI_PENGLM[$index_name['COMPANY']].'&Position='.$FI_PENGLM[$index_name['POSITION']].'&isSap='.$IS_SAP.'&keepThis=true&TB_iframe=true&height=500&width=700" style="font-size:10px" class="thickbox"><img src="img/icon/edit.png" border="none" /></a></td>';
                    else
                      echo '<td></td>';
                    echo '</tr>';
                  }
                ?>
              </tbody>
            </table>
          <?php
        }

        function showAward($fce=null, $data=null) {
            $NIK = $this->NIK;
            // GET data
            $data = (isset($data)) ? $data : $this->profileGetter($NIK);
            $fce = (isset($fce)) ? $fce : 'fce';

            $rows = $this->getRows("FI_AWARD", $fce, $data);
            $conn = $this->conn;
            $IS_SAP = $data['IS_SAP'];
    
                ?>
                <h3>Awards</h3>
                <table class="table table-striped table-bordered table-condensed">
                  <thead>
                    <th width="50">Start Date</th>
                    <th width="50">End Date</th>
                    <th>Status</th>
                    <th>Type</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    for ($i=1; $i<=$rows; $i++) {
                      if (array_key_exists('fce', $data)) {
                        $FI_AWARD = saprfc_table_read($data[$fce], "FI_AWARD", $i);
                      } else {
                        $FI_AWARD = array();
                      }
                      echo '<tr>';
                      echo '<td>' . convertDate2($FI_AWARD['BEGDA']) . '</td>';
                      echo '<td>' . convertDate2($FI_AWARD['ENDDA']) . '</td>';
                      echo '<td>' . $FI_AWARD['STATUS'] . '</td>';
                      echo '<td>' . $FI_AWARD['AWDTP'] . '</td>';
                      echo '</tr>';
                    }
                    ?>
                  </tbody>
                </table>
              <?php 
        }

        function showMutasi($fce=null, $data=null) {
            $NIK = $this->NIK;
            // GET data
            $data = (isset($data)) ? $data : $this->profileGetter($NIK);
            $fce = (isset($fce)) ? $fce : 'fce';

            $rows = $this->getRows("FI_MUTASI", $fce, $data);
            $conn = $this->conn;
            $IS_SAP = $data['IS_SAP'];?>
    
              <h3>Organizational Assignment</h3>
                  <table class="table table-striped table-bordered table-condensed">
                    <thead>
                      <th width="50">Start Date</th>
                      <th width="50">End Date</th>
                      <th>Unit</th>
                      <th>Position</th>
                      <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      for($i=1; $i<=$rows; $i++){
                          if (array_key_exists('fce', $data)) {
                            $FI_MUTASI = saprfc_table_read($data['fce'], "FI_MUTASI", $i);
                          } else {
                            $FI_MUTASI = $data["FI_MUTASI"]['data'][$i-1];
                          }

                          $index_name = $data['FI_MUTASI']['index_name'];

                          $FI_MUTASI[$index_name['STARTDATE']] = $this->matchDate($FI_MUTASI[$index_name['STARTDATE']]);
                          $FI_MUTASI[$index_name['ENDDATE']] = $this->matchDate($FI_MUTASI[$index_name['ENDDATE']]);
                          
                          $FI_MUTASI[$index_name['UNIT']] = $this->isIDField($FI_MUTASI[$index_name['UNIT']]);
                          $FI_MUTASI[$index_name['STATUS']] = $this->isIDField($FI_MUTASI[$index_name['STATUS']]);

                          echo '<tr>';
                          echo '<td>'.convertDate2($FI_MUTASI[$index_name['STARTDATE']]).'</td>';
                          echo '<td>'.convertDate2($FI_MUTASI[$index_name['ENDDATE']]).'</td>';
                          echo '<td>'.$FI_MUTASI[$index_name['UNIT']].'</td>';
                          echo '<td>'.$FI_MUTASI[$index_name['POSITION']].'</td>';
                          echo '<td>'.$FI_MUTASI[$index_name['STATUS']].'</td>';
                          echo '</tr>';
                        }
                      
                      ?>
                    </tbody>
                  </table>
              <?php
        }
        
        function showAddJob($fce=null, $data=null) {  
            $NIK = $this->NIK;
            // GET data
            $data = (isset($data)) ? $data : $this->profileGetter($NIK);
            $fce = (isset($fce)) ? $fce : 'fce';

            $rows = $this->getRows("FI_ADTJB", $fce, $data);
            $conn = $this->conn;
            $IS_SAP = $data['IS_SAP'];

                ?> 
                <h3>Additional Job Position</h3>
                      <table class="table table-striped table-bordered table-condensed">
                        <thead>
                          <th width="50">Start Date</th>
                          <th width="50">End Date</th>
                          <th>Unit</th>
                          <th>Position</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          for ($i=0; $i<$rows; $i++) {
                            $FI_ADTJB = $data["FI_ADTJB"]['data'][$i];
                            $index_name = $data['FI_ADTJB']['index_name'];

                            $FI_ADTJB[$index_name['STARTDATE']] = $this->matchDate($FI_ADTJB[$index_name['STARTDATE']]);
                            $FI_ADTJB[$index_name['ENDDATE']] = $this->matchDate($FI_ADTJB[$index_name['ENDDATE']]);

                            $FI_ADTJB[$index_name['UNIT']] = $this->isIDField($FI_ADTJB[$index_name['UNIT']]);
                            $FI_ADTJB[$index_name['JABATAN']] = $this->isIDField($FI_ADTJB[$index_name['JABATAN']]);

                            echo '<tr>';
                            echo '<td>' . convertDate2($FI_ADTJB[$index_name['STARTDATE']]) . '</td>';
                            echo '<td>' . convertDate2($FI_ADTJB[$index_name['ENDDATE']]) . '</td>';
                            echo '<td>' . $FI_ADTJB[$index_name['UNIT']] . '</td>';
                            echo '<td>' . $FI_ADTJB[$index_name['JABATAN']] . '</td>';
                            echo '</tr>';
                      }
                      ?>
                    </tbody>
                  </table>
            <?php
        }
        
    }
    ?>
    <script src="js/thickbox.js"></script>