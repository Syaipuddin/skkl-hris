<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class report_model extends CI_Model {
	function __construct()
	{
		parent::__construct();
		$this->idcard = $this->load->database('default', TRUE);
		$this->load->library('pagination');

	}

	function get_total_row_all_data_idcard_report(){
	 $query = $this->idcard->query("SELECT count(*) as row FROM id_card_online")->row_array();
	  return $query['row'];
	}

	function get_total_row_all_data_report_persadmin($persadmin){
	 $query = $this->idcard->query("SELECT count(*) as row FROM id_card_online where persadmin='$persadmin'")->row_array();
	  return $query['row'];
	}

	

	function get_all_data_idcard_report($no_page, $perpage)
	{

	   if($no_page == 0){
            $first = 1;
            $last  = $perpage;
        }else{
            $first = $no_page + 1;
            $last  = $first + ($no_page -1);
        }

        
        	$query = $this->idcard->query("WITH CTE AS (
                                        SELECT  a.*,REPLACE(a.path_photo,'\','/') as foto,
                                                ROW_NUMBER() OVER (ORDER BY a.id_cardonline desc) as RowNumber 
                                                FROM id_card_online a ) 
        								SELECT * FROM CTE WHERE RowNumber BETWEEN $first AND $last"); 
        
        return $query->result_array();  
	}

	function get_all_data_idcard_report_by_persadmin($no_page, $perpage, $pers_admin)
	{

	   if($no_page == 0){
            $first = 1;
            $last  = $perpage;
        }else{
            $first = $no_page + 1;
            $last  = $first + ($no_page -1);
        }

        
        	$query = $this->idcard->query("WITH CTE AS (
                                        SELECT  a.*,REPLACE(a.path_photo,'\','/') as foto,
                                                ROW_NUMBER() OVER (ORDER BY a.id_cardonline desc ) as RowNumber 
                                                FROM id_card_online a
                                                WHERE a.persadmin='$pers_admin') 
        								SELECT * FROM CTE WHERE RowNumber BETWEEN $first AND $last"); 
        
        return $query->result_array();  
	}


	function get_all_data_search_idcard_report($nama, $pers_admin, $periode_start, $periode_end, $status, $status_card)
	{
		$selected_field = "*, REPLACE(path_photo,'\','/') as foto ";
		$this->idcard->select($selected_field,FALSE);
		$this->idcard->from('id_card_online');
		
		if($status!='')
		{
			if($status==1)
			{
				$this->idcard->where('tgl_cetak is not null');
			}
			elseif($status==0){
				$this->idcard->where('tgl_cetak',null);			
			} 
		}
		

		/*if(!empty($periode))
		{
			$this->idcard->like('CONVERT(VARCHAR(10),tgl_cetak,120)', $periode); 
		}*/

		if(!empty($periode_start) || !empty($periode_end))
		{
			$this->idcard->where("tgl_cetak BETWEEN '$periode_start' AND '$periode_end'");
		}		

		if(!empty($nama))
		{
			$this->idcard->like('nama', $nama); 
		}	

		if(!empty($pers_admin))
		{
			$this->idcard->where('persadmin',$pers_admin);
		}
		
		if($status_card!='')
		{
			$this->idcard->where('status',$status_card);
		}
		
		$get = $this->idcard->get();
		//echo $this->idcard->last_query();
		return $get->result_array();  
	}
	
	public function get_all_persadmin()
        {
            $this->idcard->select('DISTINCT persadmin');
            $this->idcard->from('id_card_online');
            $this->idcard->where('persadmin is not null');
            return $this->idcard->get()->result();
        }
}
