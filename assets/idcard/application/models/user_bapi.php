<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_bapi extends CI_Model {

        function __construct()
        {
                // Call the Model constructor
                parent::__construct();
                $this->load->helper('array');
                $this->load->library('saprfc');
        }

	public function get_pers_admin_emp($nik_emp, $tgl_sekarang)
        {
                $this->saprfc->connect();
                $this->saprfc->functionDiscover('ZHRFM_CV_MINI');
                $importParamName = array(
                        'FI_PERNR','FI_PERNR_DIAKSES','FI_TANGGAL'
                );
                $importParamValue = array(
                        $nik_emp, $nik_emp, $tgl_sekarang
                );
                $this->saprfc->importParameter($importParamName, $importParamValue);
                $this->saprfc->setInitTable('FI_CV');
                $this->saprfc->executeSAP();
                $obj = $this->saprfc->fetch_row('FI_CV','object',1);
                $this->saprfc->free();
                $this->saprfc->close();
                return $obj;
        }

}
