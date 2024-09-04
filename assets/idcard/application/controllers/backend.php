<?php 

class backend extends CI_Controller {

	public function __construct()
   {
        parent::__construct();	

        $this->load->model('user_model');
		// $this->load->model('session_model');
	}

	function unauthorized_page()
	{
		$this->load->view('unauthorized_page');
	}

	public function index()
	{
		$data['notif'] = $this->session->flashdata('notif');
		$this->load->view('layout/header_login');
		$this->load->view('backend/login',$data);
		$this->load->view('layout/footer_login');
	}

	public function portal_login($nik,$password,$is_sap)
	{
		$sap_db = $this->user_model->get_data_user_sap($nik);	

			//insert ke table user

			$count_user = $this->user_model->check_user_nik($nik);
			if($count_user==0)
			{
			 	$arr_new_user = array('nik' => $nik, 'password' => $password, 
                        'nama' => $sap_db->Nama, 'unit' => $sap_db->Nama, 
                        'id_group' => 2, 'persadmin' => $sap_db->PersAdmin,
                        'is_sap' => 1,'active' => 1);
				$this->user_model->add_user($arr_new_user);
			}
        		
			$role=$this->user_model->get_user_by_nik($nik, 1)->role;
			$newdata = array(
				'nik'      => $nik,
				'role'        => $role,
				'nama'        => $sap_db->Nama,
				'unit'        => $sap_db->Unit,
				'loginFlag'     => TRUE,
				'persadmin'     => $sap_db->PersAdmin,
				'is_sap'		=> 1,
			);
			$this->session->set_userdata($newdata);

    

			redirect('home');
	}

	public function login_process()
	{
		$nik=$this->input->post('nik');
		$password=$this->input->post('password');
		
		
		$sap_db = $this->user_model->get_data_user_sap($nik);	

		if(empty($sap_db))
		{
			$db = $this->user_model->get_user_by_nik($nik,0);		
			$nama = $db->nama;
			$unit = $db->unit;
			$persadmin = $db->persadmin;
		}else{
			$nama = $sap_db->Nama;
			$unit = $sap_db->Unit;
			$persadmin = $sap_db->PersAdmin;
			$db = $this->user_model->get_user_by_nik($nik,1);	
		}

		if (md5($password)==$db->password){
			
			$newdata = array(
				'nik'      => $db->nik,
				'role'        => $db->role,
				'nama'        => $nama,
				'unit'        => $unit,
				'loginFlag'     => TRUE,
				'persadmin'     => $persadmin,
				'is_sap'		=> $db->is_sap,
			);
			$this->session->set_userdata($newdata);

			redirect('home');
		}
		else
		{
			$this->session->set_flashdata('notif','wrong nik and password');
			redirect('backend');	
		}
		
	}

	function logout(){
		$this->session->sess_destroy();
		redirect('http://hr.kompasgramedia.com');
	}
	
}
