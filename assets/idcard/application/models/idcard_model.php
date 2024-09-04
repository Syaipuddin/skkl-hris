<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Idcard_model extends CI_Model {
	function __construct()
	{
		parent::__construct();
		$this->idcard = $this->load->database('default', TRUE);
		$this->load->library('pagination');

	}

	function get_total_row_all_data_idcard_persadmin($persadmin){
	 $query = $this->idcard->query("SELECT count(*) as row FROM id_card_online where persadmin='$persadmin' AND tgl_cetak is null")->row_array();
	  return $query['row'];
	}

	function get_all_data_idcard_persadmin($no_page, $perpage, $persadmin)
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
			                                ROW_NUMBER() OVER (ORDER BY a.tgl_request asc) as RowNumber FROM id_card_online a 
			                                WHERE a.persadmin='$persadmin' AND a.tgl_cetak is null) SELECT * FROM CTE 
											WHERE RowNumber BETWEEN $first AND $last"); 
        

        return $query->result_array();  
	}


	function get_total_row_all_data_idcard($no_page, $perpage,$first='', $last=''){
	$query_data = "WITH CTE AS (
                                        SELECT  a.*,REPLACE(a.path_photo,'\','/') as foto,
                                                ROW_NUMBER() OVER (ORDER BY a.tgl_request asc) as RowNumber
                                                FROM id_card_online a WHERE a.tgl_cetak is null)
                                                                        SELECT COUNT(*) as row FROM CTE WHERE RowNumber BETWEEN $first AND $last";
	 $query = $this->idcard->query("SELECT count(*) as row FROM id_card_online WHERE tgl_cetak is null")->row_array();
//	 $query = $this->idcard->query($query_data)->row_array();

	  return $query['row'];
	}

	function get_all_data_idcard($no_page, $perpage)
	{

	   if($no_page == 0){
            $first = 1;
            $last  = $perpage;
        }else{
            $first = $no_page + 1;
            $last  = $first + ($no_page -1);
        }
			
      /* echo "WITH CTE AS (SELECT  a.*,REPLACE(a.path_photo,'\','/') as foto,
                                                ROW_NUMBER() OVER (ORDER BY a.id_cardonline desc) as RowNumber 
                                                FROM id_card_online a WHERE a.tgl_cetak is null) 
        								SELECT * FROM CTE WHERE RowNumber BETWEEN $first AND $last";
	*/        
	$query = $this->idcard->query("WITH CTE AS (
                                        SELECT  a.*,REPLACE(a.path_photo,'\','/') as foto,
                                                ROW_NUMBER() OVER (ORDER BY a.tgl_request asc) as RowNumber 
                                                FROM id_card_online a WHERE a.tgl_cetak is null) 
        								SELECT * FROM CTE WHERE RowNumber BETWEEN $first AND $last"); 
        
        return $query->result_array();  
	}



	function get_idcard_by_id($id)
	{
		$selected_field = "*, REPLACE(path_photo,'\','/') as foto ";
		$this->idcard->select($selected_field,FALSE);
		$this->idcard->from('id_card_online');
		$this->idcard->join('tb_warna', 'tb_warna.warna_type = id_card_online.warna ', 'inner');
		$this->idcard->where('id_cardonline',$id);
		$this->idcard->where('tgl_cetak',null);
		$get = $this->idcard->get();
		return $get->row();
	}
	
	function get_idcard_by_nik($nik)
        {
                $selected_field = "*";
                $this->idcard->select($selected_field,FALSE);
                $this->idcard->from('id_card_online');
                $this->idcard->where('nik',$nik);
                $get = $this->idcard->get();
                return $get->row();
        }

	function get_all_data_search_idcard($nama, $pers_admin)
	{
		$selected_field = '*';
		$this->idcard->select($selected_field,FALSE);
		$this->idcard->from('id_card_online');
		$this->idcard->like('nama', $nama); 
		$this->idcard->where('tgl_cetak',null);

		if(!empty($pers_admin))
		{
			$this->idcard->where('persadmin',$pers_admin);
		}
		
		$get = $this->idcard->get();
		return $get->result_array();  
	}



	function add_idcard($arr_new_idcard)
	{
		$this->idcard->insert('id_card_online',$arr_new_idcard);
	}

	function update_idcard($id,$arr_update_idcard)
	{
		$this->idcard->where('id_cardonline', $id);
		$this->idcard->update('id_card_online',$arr_update_idcard);
	}
	
	function update_idcard_by_nik($nik,$arr_update_idcard)
	{
		$this->idcard->where('nik', $nik);
		$this->idcard->update('id_card_online',$arr_update_idcard);
	}
        
        function update_karya1_by_nik($nik)
	{
            $query = $this->idcard->query("UPDATE KARYA1 SET FCDUPLIKAT+=1, Action=6 where FCIDNO='$nik'");            
//            return $query->result_array();  
	}

	function delete_idcard($id,$arr_delete_idcard)
	{
		$this->idcard->where('id_cardonline', $id);
		$this->idcard->delete('id_card_online');
	}

	public function get_warna()
	  {
	    $this->idcard->from('tb_warna');
	    $this->idcard->order_by('id_warna');
	    return $this->idcard->get()->result();
	  }

	  public function get_tanggal()
	  {
	    $this->idcard->from('tb_warna');
	    $this->idcard->order_by('id_warna');
	    return $this->idcard->get()->result();
	  }
	 
	function get_pers_admin_logos_by_id($id)
	{
		//PORTAL].[dbo].[ms_PersAdminOrganization]
		// print_r(json_encode($NIK)) ;

		$this->idcard->select('nik');
		$this->idcard->from('id_card_online');
		$this->idcard->where('id_cardonline',$id);
		$get = $this->idcard->get()->row();

		/////

		$this->idcards = $this->load->database('portal', TRUE);
		///

		$preresult= $this->idcards->select('PersAdmin')->from('ms_niktelp')->where('NIK',$get->nik)->get()->row();

		// print_r(json_encode($preresult));

		$result = $this->idcards->select('Organization_name')->from('ms_PersAdminOrganization')->where('PersAdmin_Id',$preresult->PersAdmin)->get()->result_array();
		//echo $result->age;
		return $result;
		
	}

	function get_pers_admin_logos_by_nik($nik, $date)
	{
		//PORTAL].[dbo].[ms_PersAdminOrganization]
		// print_r(json_encode($NIK)) ;

		// $this->idcard->select('persadmin');
		// $this->idcard->from('id_card_online');
		// $this->idcard->where('nik',$nik);
		// $get = $this->idcard->get()->row();

		$this->idcards = $this->load->database('portal', TRUE);
		///

		 $preresult= $this->idcards->select('PersAdmin')->from('ms_niktelp')->where('NIK',$nik)->get()->row();

		// print_r(json_encode($preresult));

		// $preresult->PersAdmin

		$result = $this->idcards->select('Organization_name')->from('ms_PersAdminOrganization')->where('PersAdmin_Id',$preresult->PersAdmin)->get()->result_array();
		//echo $result->age;
		return $result;
		
	}

	// function get_photo_sisdm($nik)
	// {
	// 	$this->sisdm->select('*')->from('Photo');
	// 	$query=$this->sisdm->get();
	// 	return $query->row();
	// }
	public function get_decree_list($nik, $tanggal) 
 { 
  $this->saprfc->connect(); 
  $this->saprfc->functionDiscover('ZHRFM_IDCARD_HIRING'); 
  $importParamName = array( 
   'FI_PERNR', 
   'FI_TANGGAL' 
  ); 
  $importParamValue = array( 
   $nik, 
   $tanggal 
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



