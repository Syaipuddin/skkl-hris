<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {
	function __construct()
	{
		parent::__construct();
		$this->portal = $this->load->database('portal', TRUE);
		$this->idcard = $this->load->database('default', TRUE);

	}

	function login($nik, $password)
	{
		$result = $this->get_data_user($nik);
		
		$return_function=array();
		$return_function[_RETURN_DETAIL] = array();
		$return_function['return_session'] = array();

		if(sizeof($result)==0)
		{
			$return_function[_RETURN_VALUE]= FN_FAILED;
			array_push($return_function[_RETURN_DETAIL],FN_USER_LOGIN_INVALID_NIK);
		}
		else
		{
			$row=$result[0];
			
			if (md5($password)==$row->password)
			{
				$return_function['return_session']['loginname'] = $row->nik;
				//$return_function['return_session']['username'] = $row->username;
			//	$row_array = $this->get_row_by_id_array($row->id_user);
				$array_module_access = array();
				$arr = array("user","bonus");
				foreach($arr as $key=>$value)
				{
					if($row_array["a_".$value."_view"]==1)
						$array_module_access["a_".$value."_view"] = $row_array["a_".$value."_view"];
					if($row_array["a_".$value."_print"]==1)
						$array_module_access["a_".$value."_print"] = $row_array["a_".$value."_print"];
					if($row_array["a_".$value."_all"]==1)
						$array_module_access["a_".$value."_all"] = $row_array["a_".$value."_all"];
				}
				$return_function['return_session']['module_access']= $array_module_access;
				$return_function[_RETURN_VALUE]= FN_SUCCESS;
			}else{
				$return_function[_RETURN_VALUE]= FN_FAILED;
				array_push($return_function[_RETURN_DETAIL],FN_USER_LOGIN_WRONG_PASSWORD);
			}
		}

		return $return_function;
	}
	
	function get_data_user_sap($nik)
	{
		$selected_field = '*';
		$this->portal->select($selected_field,FALSE);
		$this->portal->from('ms_niktelp');
		$this->portal->where('NIK',$nik);
		$get = $this->portal->get();
		return $get->row();
	}

	function get_data_user_non_sap($nik)
	{
		$selected_field = '*';
		$this->idcard->select($selected_field,FALSE);
		$this->idcard->from('tb_user');
		$this->idcard->where('nik',$nik);
		$get = $this->idcard->get();
		return $get->row();
	}

	function search_name($nama)
	{
		$selected_field = '*';
		$this->idcard->select($selected_field,FALSE);
		$this->idcard->from('tb_user');
		$this->idcard->join('tb_group', 'tb_group.id_group = tb_user.id_group', 'inner');
		$this->idcard->like('nama', strtoupper($nama)); 
		$get = $this->idcard->get();
		return $get->result_array();  
	}

	function search_data_by_name($nama)
	{
		$selected_field = 'NIK, Nama,Unit';
		$this->portal->select($selected_field,FALSE);
		$this->portal->from('ms_niktelp');
		$this->portal->like('Nama', strtoupper($nama)); 
		$query=$this->portal->get();
		return $query->result();
	}


	function get_data_photo_by_sisdm($nik, $status)
	{
			if($status==1)
			{
				$this->idcard->from('id_card_online');
				$this->idcard->where('NIK',$nik);
				$count_id = $this->idcard->count_all_results();
				if($count_id==0)
				{
					$selected_field = "*,  REPLACE(path_photo,'\','/') as foto ";
					$this->idcard->select($selected_field,FALSE);
					$this->idcard->from('id_card_online');
					$this->idcard->where('NIK',$nik);
					$get = $this->idcard->get();
					return $get->row();		
				}
				else
				{
					$selected_field = 'path_photo, warna';
					$this->idcard->select($selected_field,FALSE);
					$this->idcard->from('id_card_online');
					$this->idcard->where('NIK',$nik);
					$get = $this->idcard->get();
					return $get->row();	
				}

					
			}else
			{
				$selected_field = "*,  REPLACE(path_photo,'\','/') as foto ";
				$this->idcard->select($selected_field,FALSE);
				$this->idcard->from('id_card_online');
				$this->idcard->where('NIK',$nik);
				$get = $this->idcard->get();
				return $get->row();		
			}
	}

	function check_user_nik_sap($nik)
	{
		$this->portal->where('NIK',$nik);
		$this->portal->from('ms_niktelp');
		return $count = $this->portal->count_all_results();
	}

	function check_user_idcard($nik)
	{
		$this->idcard->where('nik',$nik);
		$this->idcard->from('id_card_online');
		return $count = $this->idcard->count_all_results();
	}

	function get_user_by_nik($nik,$is_sap){
		$selected_field = 'TOP 1 *';
		$this->idcard->select($selected_field,FALSE);
		$this->idcard->from('tb_user');
		$this->idcard->join('tb_group', 'tb_group.id_group = tb_user.id_group', 'inner');
		if($is_sap==0)
		{
			$this->idcard->join('tb_persadmin_login', 'tb_persadmin_login.persadmin = tb_user.persadmin', 'inner');
		}
		$this->idcard->where('nik',$nik);
		$this->idcard->where('active',1);
		$get = $this->idcard->get();
		return $get->row();
	}

	function get_total_row_all_data_admin(){
	 $query = $this->idcard->query("SELECT count(*) as row FROM tb_user")->row_array();
	  return $query['row'];
	}

	function check_user_nik($nik)
	{
		$this->idcard->where('nik',$nik);
		$this->idcard->from('tb_user');
		return $count = $this->idcard->count_all_results();
	}

	function get_all_data_admin($no_page, $perpage)
	{

	   if($no_page == 0){
            $first = 1;
            $last  = $perpage;
        }else{
            $first = $no_page + 1;
            $last  = $first + ($no_page -1);
        }
        $query = $this->idcard->query("WITH CTE AS ( SELECT a.*,b.role, ROW_NUMBER() 
        							OVER (ORDER BY a.id_user asc) as RowNumber FROM tb_user a 
									inner join tb_group b on b.id_group=a.id_group
									WHERE a.active=1
									)SELECT * FROM CTE WHERE RowNumber BETWEEN $first AND $last"); 
        return $query->result_array();  
	}

	function get_group_id()
	{
		$this->idcard->select('role, id_group')->from('tb_group');
		$query=$this->idcard->get();
		return $query->result();
	}

	function get_persadmin()
	{
		$this->idcard->select('persadmin, id_persadmin')->from('tb_persadmin_login');
		$query=$this->idcard->get();
		return $query->result();
	}

	function get_user_by_id($id)
	{
		$selected_field = '*';
		$this->idcard->select($selected_field,FALSE);
		$this->idcard->from('tb_user');
		$this->idcard->where('id_user',$id);
		$get = $this->idcard->get();
		return $get->row();
	}


	function add_user($arr_new_user)
	{
		$this->idcard->insert('tb_user',$arr_new_user);
	}

	function update_user($id,$arr_update_user)
	{
		$this->idcard->where('id_user', $id);
		$this->idcard->update('tb_user',$arr_update_user);
	}

	function delete_user($id,$arr_delete_user)
	{
		$this->idcard->where('id_user', $id);
		$this->idcard->delete('tb_user');
	}

	//EDIT REJECT START

	function delete_request($idtable){
		$this->idcard->where('id_cardonline',$idtable);
		$this->idcard->delete('id_card_online');
	}

	function get_hr_nik_request($id){
		$selected_field = "hr_nik_request";
		$this->idcard->select($selected_field,FALSE);
		$this->idcard->from('id_card_online');
		$this->idcard->where('NIK',$id);
		$get = $this->idcard->get();
		return $get->row_array();		
	}

	function insert_reject($id, $comment){
		$data = array(
			'NIK' => $id,
			'comment' => $comment,
			'status' => '0'
			);
		$this->portal->insert('idcard_reject_process', $data);
	}

	function get_email($id){
		$selected_field = "Email";
		$this->portal->select($selected_field,FALSE);
		$this->portal->from('ms_niktelp');
		$this->portal->where('NIK',$id);
		$get = $this->portal->get();
		return $get->row_array();		
	}

	//EDIT REJECT END

}
