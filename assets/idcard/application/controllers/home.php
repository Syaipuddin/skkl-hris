<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller 
{
	private $url_add;

	function __construct()
	{
		parent::__construct();

		if(!$this->session->userdata('loginFlag'))
		{	
			redirect('backend');
		}

		$this->url_add_process = URL_ADD_NIK_IDCARD;
		$this->url_edit_new_process = URL_EDIT_NIK_IDCARD;
		$this->url_reject_process = "idcard/reject_process";

		$this->url_delete_new_process = URL_DELETE_NIK_IDCARD;

		$this->url_extend_process = URL_EXTEND_NIK_IDCARD;
		
		$this->url_lost_process = URL_LOST_NIK_IDCARD;
		
		$this->url_broken_process = URL_BROKEN_NIK_IDCARD;

		 $this->url_search_nama = URL_SEARCH_NAMA;
		
		$this->load->model('persadmin_model');
		$this->load->model('idcard_model');
		$this->load->model('user_model');
    	$this->load->library('saprfc');
    	$this->load->library('phpmailer');
		$this->load->model('user_bapi');
	}

	public function index()
	{
		if($this->session->userdata('loginFlag'))
		{
			 /* URL LINKS */
			 redirect('home/lists');
		}
		else
		{
			redirect('backend');			
		}
		
	}

	public function get_data_nik(){
		$nik = $this->input->post('nik');
	   	$result = $this->user_model->get_data_user_sap($nik);
	   	echo json_encode($result);
	}

	public function get_data_nik_sap(){
		$nik = $this->input->post('nik');
	   	$result = $this->user_bapi->get_pers_admin_emp($nik,date('Ymd'));
	   	echo json_encode($result);
	}

	// public function get_data_tanggal_berlaku(){
	// 	$nik = $this->input->post('nik');
	//    	$result = $this->user_model->get_pers_admin_emp($nik,date('Ymd'));
	//    	echo json_encode($result);
	// }

	public function get_pers_admin_nik(){
		$nik = $this->input->post('nik');
	   	$result = $this->idcard_model->get_pers_admin_logos_by_nik($nik,date('Ymd'));
	   	echo json_encode($result);
	}
	

	public function get_data_photo(){
		$nik = $this->input->post('nik');
		$status = $this->input->post('status');
	 	  $result = $this->user_model->get_data_photo_by_sisdm($nik, $status);
	   echo json_encode($result);
	}


	public function lists()
	{
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

    	$config["base_url"] = base_url() . URL_HOME_LIST;
        $config["per_page"] = 10;
		if($this->session->userdata('role')!=OPT_ROLE_ADMIN_STATUS_VALUE)
		{
			$total_rows_group = $this->idcard_model->get_total_row_all_data_idcard_persadmin($this->session->userdata('persadmin'));
			$request_data_group = $this->idcard_model->get_all_data_idcard_persadmin($page, $config["per_page"],$this->session->userdata('persadmin'));
		}else{
			$total_rows_group = $this->idcard_model->get_total_row_all_data_idcard($page, $config["per_page"]);
			$request_data_group = $this->idcard_model->get_all_data_idcard($page, $config["per_page"]);
		} 
        $config["total_rows"] = $total_rows_group;
        $config["uri_segment"] = 3;
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
    	$this->pagination->initialize($config);
        $data["request_data"] = $request_data_group;
    	$data["links"] = $this->pagination->create_links();
    	$link['new_id_card'] = $this->url_add_process."/";
    	$link['extend_id_card'] = $this->url_extend_process."/";
    	$link['lost_id_card'] = $this->url_lost_process."/";
    	$link['broken_id_card'] = $this->url_broken_process."/";
    	$link['edit_new_idcard'] = $this->url_edit_new_process."/";
    	$link['reject_idcard'] = $this->url_reject_process."/";
    	$link['delete_new_idcard'] = $this->url_delete_new_process."/";
    	$data['action'] = $this->url_search_nama."/";
        $data['link'] = $link;
        	$data['pers_admin_list']  = $this->persadmin_model->get_all_persadmin_home_union();
       
		$this->load->view(URL_TEMPLATE_MAIN_TOP);
		$this->load->view('frontend/home', $data);
		$this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
	}


	//TAMBAHAN REJECT START
	public function reject_action(){
		$nik =  $_POST['nik'];
		$idTable =  $_POST['idTable'];
		$comment = $_POST['comment'];
		$nikx = str_replace(" ", "", $nik);
		
		$nikAdmin = $this->user_model->get_hr_nik_request($nik);
		
		$emailRs = $this->user_model->get_email($nikAdmin['hr_nik_request']);

		echo "Reject Success";
		/*echo $nikx;
		echo " " . $idTable;
		echo " " . $comment;
		echo " " . $nikAdmin['hr_nik_request'];
		echo " " . $emailRs['Email'];*/


		$this->user_model->insert_reject($nikAdmin['hr_nik_request'] ,$comment);
		$this->user_model->delete_request($idTable);

		// KIRIM EMAIL

		


		$mailbawahan = new PHPMailer();
        $mailbawahan->IsSMTP();
        $mailbawahan->Host = "10.10.55.10";
        $mailbawahan->SMTPAuth = true;
        $mailbawahan->Username = 'hrportal@chr.kompasgramedia.com';
        $mailbawahan->Password = 'abc123';
        $mailbawahan->SetFrom("hrportal@chr.kompasgramedia.com", "[HRPortal] DO NOT REPLY THIS EMAIL!");
        $mailbawahan->IsHTML(true);

     
        $mailbawahan->AddAddress($emailRs);
        $mailbawahan->Subject = "Employee Request ID Card Reject Notification";

        $mailbawahan->Body = $comment ;

        //sending email
        if(!$mailbawahan->Send())
        {
                echo "Error sending: " . $mail->ErrorInfo;
        }
	}

	//TAMBAHAN REJECT END

	public function search()
	{
		$nama = $this->input->post('txt_nama');
		$persadmin = $this->input->post('slc_persadmin');

        	if($nama=='' && $persadmin=='')
		{
			redirect(URL_HOME_LIST);	
		}
	
		$data['pers_admin_list']  = $this->persadmin_model->get_all_persadmin_home_union();
		$data['action'] = $this->url_search_nama."/";
		$link['new_id_card'] = $this->url_add_process."/";
	    	$link['extend_id_card'] = $this->url_extend_process."/";
    		$link['lost_id_card'] = $this->url_lost_process."/";
    		$link['broken_id_card'] = $this->url_broken_process."/";
    		$link['edit_new_idcard'] = $this->url_edit_new_process."/";
    		$link['reject_idcard'] = $this->url_reject_process."/";
    		$link['delete_new_idcard'] = $this->url_delete_new_process."/";
       		$data['link'] = $link;


        	if($this->session->userdata('role')!=OPT_ROLE_ADMIN_STATUS_VALUE)
		{
			$request_data_group = $this->idcard_model->get_all_data_search_idcard($nama,$this->session->userdata('persadmin'));
		}else{
			$request_data_group = $this->idcard_model->get_all_data_search_idcard($nama, $persadmin);
		}

		$data["request_data"] = $request_data_group;
        
		$this->load->view(URL_TEMPLATE_MAIN_TOP);
		$this->load->view('frontend/home', $data);
		$this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
