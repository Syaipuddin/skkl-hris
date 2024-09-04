<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class tagihan_model extends CI_Model {
	function __construct(){
		parent::__construct();
		$this->idcard = $this->load->database('default', TRUE);
		$this->load->library('pagination');
	}
	
	function get_total_row_all_data_idcard_tagihan(){
		$query = $this->idcard->query("SELECT count(*) as row FROM tb_trx_invoice WHERE status IN (1,2,3) AND flag_proses = 1")->row_array();
		return $query['row'];
	}

	function get_total_row_tagihan_persadmin_sap($persadmin){
		$query = $this->idcard->query("SELECT count(*) as row FROM tb_persadmin_sap WHERE persadmin='$persadmin'")->row_array();
		return $query['row'];
	}
	
	function get_total_row_tagihan_persadmin_nonsap($persadmin){
		$query = $this->idcard->query("SELECT count(*) as row FROM tb_persadmin_login WHERE persadmin='$persadmin'")->row_array();
		return $query['row'];
	}
	
	function get_all_row_tagihan_persadmin_sap($persadmin){
		$query = $this->idcard->query("SELECT group_text FROM tb_persadmin_sap WHERE persadmin='$persadmin'")->row_array();
		return $query['group_text'];
	}
	
	function get_all_row_tagihan_persadmin_nonsap($persadmin){
		$query = $this->idcard->query("SELECT nama_unit FROM tb_persadmin_login WHERE persadmin='$persadmin'")->row_array();
		return $query['nama_unit'];
	}
	
	function get_total_row_all_data_tagihan($start_period, $end_period){
		$query = $this->idcard->query("
				SELECT count(*) as row, nominal_invoice, sum(nominal_invoice) as jumlah
				FROM tb_trx_invoice 
				WHERE tgl_cetak >= '$start_period' AND
					  tgl_cetak <= '$end_period' AND
					  status in (1,2,3) AND
					  flag_proses = 1
				GROUP BY nominal_invoice
		")->row_array();
		return $query;
	}
	
	function get_total_row_all_data_tagihan_kasir($start_period, $end_period){
		$query = $this->idcard->query("
				SELECT count(*) as row, nominal_invoice, sum(nominal_invoice) as jumlah
				FROM tb_trx_invoice 
				WHERE tgl_cetak >= '$start_period' AND
					  tgl_cetak <= '$end_period' AND
					  status in (1,2,3) AND
					  flag_proses = 0
				GROUP BY nominal_invoice
		")->row_array();
		return $query;
	}
	
	function get_total_row_all_data_tagihan2($persadmin,$start_period, $end_period){
		/*echo "SELECT count(*) as row, nominal_invoice, sum(nominal_invoice) as jumlah
                                FROM tb_trx_invoice
                                WHERE persadmin='$persadmin' AND
                                          tgl_cetak >= '$start_period' AND
                                          tgl_cetak <= '$end_period' AND
                                          status in (1,2,3) AND
                                          flag_proses = 1
                                GROUP BY nominal_invoice"; */
		$query = $this->idcard->query("
				SELECT count(*) as row, nominal_invoice, sum(nominal_invoice) as jumlah 
				FROM tb_trx_invoice 
				WHERE persadmin='$persadmin' AND
					  tgl_cetak >= '$start_period' AND
					  tgl_cetak <= '$end_period' AND
					  status in (1,2,3) AND
					  flag_proses = 1
				GROUP BY nominal_invoice
		")->row_array();
		return $query;
	}
	
	function get_total_row_all_data_tagihan2_kasir($persadmin,$start_period, $end_period){
		$query = $this->idcard->query("
				SELECT count(*) as row, nominal_invoice, sum(nominal_invoice) as jumlah 
				FROM tb_trx_invoice 
				WHERE persadmin='$persadmin' AND
					  tgl_cetak >= '$start_period' AND
					  tgl_cetak <= '$end_period' AND
					  status in (1,2,3) AND
					  flag_proses = 0
				GROUP BY nominal_invoice
		")->row_array();
		return $query;
	}
	
	function update_status_cetak($start_period, $end_period, $persadmin){
		$now = date("Y-m-d H:i:s");
		$query = $this->idcard->query("
				UPDATE tb_trx_invoice SET status_invoice = 1, tgl_cetak_invoice = '$now'
				WHERE persadmin = '$persadmin' AND
					  tgl_cetak >= '$start_period' AND
					  tgl_cetak <= '$end_period' AND
					  status in (1,2,3) AND
					  flag_proses = 1 AND
					  status_invoice != 1
		");
	}
	
	function get_tarif_per_persadmin($persadmin, $start_period, $end_period, $status, $status_card){
		$query_text = "SELECT count(*) as row, nominal_invoice 
					   FROM tb_trx_invoice
					   WHERE persadmin = '$persadmin' AND
							 tgl_cetak >= '$start_period' AND
							 tgl_cetak <= '$end_period' AND
							 flag_proses = 1";
		if($status!=''){
			$query_text = $query_text." AND status_invoice = $status";
		}
		
		if($status_card!=''){
			$query_text = $query_text." AND status = $status_card";
		}else{
			$query_text = $query_text." AND status in (1,2,3)";
		}
		$query_text = $query_text." GROUP BY nominal_invoice";
		//echo $query_text;
		$query = $this->idcard->query($query_text)->row();
	
	return $query;
	
	}

	function get_all_data_idcard_tagihan($no_page, $perpage){
		if($no_page == 0){
            $first = 1;
            $last  = $perpage;
        }else{
            $first = $no_page + 1;
            $last  = $first + ($no_page -1);
        }
		$query = $this->idcard->query("WITH CTE AS (
									   SELECT a.*,
											  ROW_NUMBER() OVER (ORDER BY a.id_trx desc) as RowNumber 
									   FROM tb_trx_invoice a 
									   WHERE status in (1,2,3) AND flag_proses = 1
									   ) 
									   SELECT * FROM CTE WHERE RowNumber BETWEEN $first AND $last");
        return $query->result_array();  
	}

	function get_all_data_search_idcard_tagihan($pers_admin, $start_period, $end_period, $status, $status_card){
		$this->idcard->select('*');
		$this->idcard->from('tb_trx_invoice');
		
		if($status!=''){
			if($status==1){
				$this->idcard->where('status_invoice',1);
			}
			elseif($status==0){
				$this->idcard->where('status_invoice',0);			
			} 
		}
		
		$this->idcard->where('tgl_cetak >=', $start_period);
		$this->idcard->where('tgl_cetak <=', $end_period);
		$this->idcard->where('flag_proses', 1);

		if(!empty($pers_admin)){
			$this->idcard->where('persadmin',$pers_admin);
		}
		
		if($status_card!=''){
			$this->idcard->where('status',$status_card);
		}else{
			$this->idcard->where('status in (1,2,3)');
		}
		
		$get = $this->idcard->get();
		return $get->result_array();
	}
	
	function get_all_persadmin(){
		$query = $this->idcard->query("
				exec SPSelectPersAdminName 
		");
		return $query->result_array();
    }
	
}
