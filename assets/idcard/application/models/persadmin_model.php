<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Persadmin_model extends CI_Model {
	function __construct()
	{
		parent::__construct();
		$this->portal = $this->load->database('portal', TRUE);
		$this->idcard = $this->load->database('default', TRUE);

	}


	function get_total_row_all_data_persadmin(){
	 $query = $this->idcard->query("SELECT count(*) as row FROM tb_persadmin_login")->row_array();
	  return $query['row'];
	}

	function get_all_data_persadmin_sap(){
	 $query = $this->portal->query("select distinct PersAdmin, PersAdminText from ms_niktelp where PersAdmin !=''");
	return $query->result_array();
	}


	function check_user_nik($nik)
	{
		$this->idcard->where('nik',$nik);
		$this->idcard->from('tb_user');
		return $count = $this->idcard->count_all_results();
	}

	function check_persadmin($persadmin)
	{
		$this->idcard->where('persadmin',$persadmin);
                $this->idcard->from('tb_persadmin_login');
                return $count = $this->idcard->count_all_results();
	}

	function get_all_data_persadmin($no_page, $perpage)
	{

	   if($no_page == 0){
            $first = 1;
            $last  = $perpage;
        }else{
            $first = $no_page + 1;
            $last  = $first + ($no_page -1);
        }
        $query = $this->idcard->query("WITH CTE AS ( SELECT a.*, ROW_NUMBER() 
        							OVER (ORDER BY a.id_persadmin asc) as RowNumber FROM tb_persadmin_login a
									)SELECT * FROM CTE WHERE RowNumber BETWEEN $first AND $last"); 
        return $query->result_array();  
	}
	
	function search_name($nama)
        {
                $selected_field = '*';
                $this->idcard->select($selected_field,FALSE);
                $this->idcard->from('tb_persadmin_login');
                $this->idcard->like('nama_unit', strtoupper($nama));
                $get = $this->idcard->get();
                return $get->result_array();
        }	

    function add_persadmin($arr_new_persadmin)
	{
		$this->idcard->insert('tb_persadmin_login',$arr_new_persadmin);
	}

	function update_persadmin($id,$arr_update_persadmin)
	{
		$this->idcard->where('id_persadmin', $id);
		$this->idcard->update('tb_persadmin_login',$arr_update_persadmin);
	}

	function get_persadmin_by_id($id)
	{
		$selected_field = '*';
		$this->idcard->select($selected_field,FALSE);
		$this->idcard->from('tb_persadmin_login');
		$this->idcard->where('id_persadmin',$id);
		$get = $this->idcard->get();
		return $get->row();
	}

	public function get_all_persadmin_home()
        {
            $this->idcard->select('DISTINCT a.persadmin, b.group_text');
            $this->idcard->from('id_card_online a');
	    $this->idcard->join('tb_persadmin_sap b', 'a.persadmin=b.persadmin');
            $this->idcard->where('tgl_cetak is null');
            return $this->idcard->get()->result();
        }

	public function get_all_persadmin_home_union()
	{
	/*	$this->idcard->select('DISTINCT a.persadmin, b.group_text');
            	$this->idcard->from('id_card_online a');
	    	$this->idcard->join('tb_persadmin_sap b', 'a.persadmin=b.persadmin');
            	$this->idcard->where('tgl_cetak is null');
		$query1=$this->idcard->get_compiled_select();

		$this->idcard->select('DISTINCT a.persadmin, c.nama_unit');
                $this->idcard->from('id_card_online a');
                $this->idcard->join('tb_persadmin_sap c', 'c.persadmin=b.persadmin');
                $this->idcard->where('tgl_cetak is null');
                $query2=$this->idcard->get_compiled_select();
 
		$query = $this->idcard->query($query1." UNION ".$query2);
	*/	
		$query="SELECT DISTINCT
				a.persadmin, b.group_text
			FROM
				id_card_online a
			INNER JOIN tb_persadmin_sap b ON a.persadmin = b.persadmin
			WHERE
				tgl_cetak IS NULL
			union
			SELECT DISTINCT a.persadmin, c.nama_unit
				FROM id_card_online a 
			INNER JOIN tb_persadmin_login c on c.persadmin=a.persadmin
			WHERE tgl_cetak is null";
		return $this->idcard->query($query)->result();
		
	}
}
