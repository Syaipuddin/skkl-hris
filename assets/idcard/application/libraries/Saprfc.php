<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * SAP RFC Email Class
 *
 * Memungkinan berkomunikasi dengan data SAP via BAPI.
 *
 * @package		Awsomeness
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Freon L
 */
class Saprfc{
	var $ashost		= '';
	var $sysnr 		= '';
	var $client 	= '';
	var $user 		= '';
	var $passwd 	= '';
	var $msgsrv 	= '';
	var $r3name 	= '';
	var $codepage = '';

	function Saprfc($config = array()){
		if (count($config) == 0)
		{
			//$this->config->load('sap_rfc');
			/*$config['ashost']   = '10.9.12.100';
			$config['sysnr']    = '30';
			$config['client']   = '600';
			$config['user']     = 'HCM-PORTAL-1';
			$config['passwd']   = 'hris2010';
			$config['msgsrv']   = '';
			$config['r3name']   = 'LHR';
			$config['codepage'] = '4110';*/

			$config['ashost']   = SAP_HOST_PROD;
			$config['sysnr']    = SAP_SYSNR_PROD;
			$config['client']   = SAP_CLIENT_PROD;
			$config['user']     = SAP_USER_PORTAL1;
			$config['passwd']   = SAP_PASSWD_PORTAL1;
			$config['msgsrv']   = '';
			$config['r3name']   = SAP_R3NAME_PROD;
			$config['codepage'] = SAP_CODEPAGE_PROD;

			// $config['ashost']   = $this->config->item('sap_ashost');
			// $config['sysnr']    = $this->config->item('sap_sysnr');
			// $config['client']   = $this->config->item('sap_client');
			// $config['user']     = $this->config->item('sap_user');
			// $config['passwd']   = $this->config->item('sap_passwd');
			// $config['msgsrv']   = $this->config->item('sap_msgsrv');
			// $config['r3name']   = $this->config->item('sap_r3name');
			// $config['codepage'] = $this->config->item('sap_codepage');
		}

		$this->sapAttr($config);
	}
	
	function sapAttr($config) {
		$this->sapConn = array (
			"ASHOST"   => $config['ashost'],
			"SYSNR"    => $config['sysnr'],
			"CLIENT"   => $config['client'],
			"USER"     => $config['user'],
			"PASSWD"   => $config['passwd'],
			"MSGSRV"   => $config['msgsrv'],
			"R3NAME"   => $config['r3name'],
			"CODEPAGE" => $config['codepage']);
		return $this->sapConn;	
	}
		
	function connect() {
		return $this->rfc = saprfc_open($this->sapConn);
	}
	
	function functionDiscover($functionName) {
		$this->fce = saprfc_function_discover($this->rfc, $functionName) or die ("fungsi $functionName tidak ditemukan");

	}

	function importParameter($importParamName, $importParamValue) {
		
		for ($i=0;$i<count($importParamName);$i++) {
			saprfc_import ($this->fce,$importParamName[$i],$importParamValue[$i]);
		}
	}
		
	function setInitTable($initTableName) {
		saprfc_table_init ($this->fce,$initTableName);
	}
	
	function executeSAP() {
		$this->rfc_rc = saprfc_call_and_receive($this->fce);
		if ($this->rfc_rc != SAPRFC_OK){
			if ($this->rfc == SAPRFC_EXCEPTION )
				echo ("Exception raised: ".saprfc_exception($this->fce));
			else
				echo ("Call error: ".saprfc_error($this->fce));
		}
		return $this->rfc_rc;
	}
	function getParameter($ParamName){
		return saprfc_export ($this->fce,$ParamName);
	}
	function fetch_rows($initTableName,$fetch_type='object') { //untuk banyak baris
		$rows = saprfc_table_rows($this->fce,$initTableName);
		if($rows < 1){ 
			$_dataRows = NULL; 
		}
		switch ($fetch_type) {
			case 'object':
				for ($i=1; $i<=$rows; $i++){
			 		$_dataRows[$i-1] = (object) saprfc_table_read($this->fce,$initTableName,$i);
			 	}
				break;
			case 'array':
				for ($i=1; $i<=$rows; $i++){
			 		$_dataRows[$i-1] = saprfc_table_read($this->fce,$initTableName,$i);
			 	}
				break;
			default:
				for ($i=1; $i<=$rows; $i++){
			 		$_dataRows[$i-1] = (object) saprfc_table_read($this->fce,$initTableName,$i);
			 	}
				break;
		}
		return $_dataRows;
	}
	function fetch_row($initTableName,$fetch_type='object',$i=1) { //untuk satu baris
		switch ($fetch_type) {
			case 'object':
				$_dataRows = (object) saprfc_table_read ($this->fce,$initTableName,$i);
				break;
			case 'array':
				$_dataRows = saprfc_table_read ($this->fce,$initTableName,$i);
				break;
			default:
				$_dataRows = (object) saprfc_table_read ($this->fce,$initTableName,$i);
				break;
		}
		return $_dataRows;
	}
	
	function free() {
		saprfc_function_free($this->fce);
	}
	
	function close() {
		saprfc_close($this->rfc);
	}
	
	function insert($initTableName,$importParamValue){
		return saprfc_table_insert ($this->fce, $initTableName, $importParamValue, 1);
	}
	
	function export($initTableName){
		return saprfc_export ($this->fce,$initTableName);
	}
}
