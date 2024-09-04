<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends CI_Controller 
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
		$this->url_delete_new_process = URL_DELETE_NIK_IDCARD;

		$this->url_extend_process = URL_EXTEND_NIK_IDCARD;
		
		$this->url_lost_process = URL_LOST_NIK_IDCARD;
		
		$this->url_broken_process = URL_BROKEN_NIK_IDCARD;

		 $this->url_search_nama = URL_SEARCH_NAMA_REPORT;
		
		$this->load->model('report_model');
		$this->load->model('user_model');
	}

	public function index()
	{
		if($this->session->userdata('loginFlag'))
		{
			 /* URL LINKS */
			 redirect(URL_REPORT_LISTS);
		}
		else
		{
			redirect('backend');			
		}
		
	}


	public function lists()
	{
		$data['pers_admin_list']  = $this->report_model->get_all_persadmin();
		$options_status = array('' => 'All', OPT_CETAK_VALUE => OPT_CETAK_TEXT, OPT_BLM_CETAK_VALUE => OPT_BLM_CETAK_TEXT); 
		$data['option_status'] = $options_status;
		$status_card = array('' => 'All', OPT_STATUS_NEW_VALUE => OPT_STATUS_NEW_TEXT, OPT_STATUS_EXT_VALUE => OPT_STATUS_EXT_TEXT, OPT_STATUS_LOST_VALUE => OPT_STATUS_LOST_TEXT, OPT_STATUS_BROKEN_VALUE => OPT_STATUS_BROKEN_TEXT); 
		$data['option_status_card'] = $status_card;
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

    	$config["base_url"] = base_url() . URL_REPORT_LISTS;
        $config["per_page"] = 10;

        if($this->session->userdata('role')==OPT_ROLE_ADMIN_STATUS_VALUE)
        {
        	$total_rows_group = $this->report_model->get_total_row_all_data_idcard_report();
			$request_data_group = $this->report_model->get_all_data_idcard_report($page, $config["per_page"]);
        }
        else
        {
        	$total_rows_group = $this->report_model->get_total_row_all_data_report_persadmin($this->session->userdata('persadmin'));
			$request_data_group = $this->report_model->get_all_data_idcard_report_by_persadmin($page, $config["per_page"],$this->session->userdata('persadmin'));
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
    	$link['delete_new_idcard'] = $this->url_delete_new_process."/";
    	$data['action'] = $this->url_search_nama."/";
        $data['link'] = $link;
        
       
		$this->load->view(URL_TEMPLATE_MAIN_TOP);
		$this->load->view('backend/report/report_view', $data);
		$this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
		$this->load->view('backend/report/report_view_js');
	}

	public function search()
	{
		$data['pers_admin_list']  = $this->report_model->get_all_persadmin();
		$options_status = array('' => 'All', OPT_CETAK_VALUE => OPT_CETAK_TEXT, OPT_BLM_CETAK_VALUE => OPT_BLM_CETAK_TEXT); 
		$data['option_status'] = $options_status;
		$status_card = array('' => 'All', OPT_STATUS_NEW_VALUE => OPT_STATUS_NEW_TEXT, OPT_STATUS_EXT_VALUE => OPT_STATUS_EXT_TEXT, OPT_STATUS_LOST_VALUE => OPT_STATUS_LOST_TEXT, OPT_STATUS_BROKEN_VALUE => OPT_STATUS_BROKEN_TEXT); 
		$data['option_status_card'] = $status_card;
		$nama = $this->input->post('txt_nama');
		$slc_persadmin = $this->input->post('slc_persadmin');
		$options_status=$this->input->post('id_cetak');
		$periode_start = $this->input->post('txt_period_start');
		$periode_end = $this->input->post('txt_period_end');
		$status_card=$this->input->post('id_status_card');

		if($nama=='' && $options_status=='' && $periode_start=='' && $periode_end=='')
		{
			redirect(URL_REPORT_LISTS);	
		}
		
		$data['action'] = $this->url_search_nama."/";
		$link['new_id_card'] = $this->url_add_process."/";
    	$link['extend_id_card'] = $this->url_extend_process."/";
    	$link['lost_id_card'] = $this->url_lost_process."/";
    	$link['broken_id_card'] = $this->url_broken_process."/";
    	$link['edit_new_idcard'] = $this->url_edit_new_process."/";
    	$link['delete_new_idcard'] = $this->url_delete_new_process."/";
        $data['link'] = $link;
        if($this->session->userdata('role')==OPT_ROLE_ADMIN_STATUS_VALUE)
        {
		$request_data_group = $this->report_model->get_all_data_search_idcard_report($nama,$slc_persadmin, $periode_start, $periode_end,$options_status,$status_card);
	}else{
		$request_data_group = $this->report_model->get_all_data_search_idcard_report($nama,$this->session->userdata('persadmin'), $periode_start,$periode_end,$options_status,$status_card);
	}
		

		$data["request_data"] = $request_data_group;
        
		$this->load->view(URL_TEMPLATE_MAIN_TOP);
		$this->load->view('backend/report/report_view', $data);
		$this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
		$this->load->view('backend/report/report_view_js');
	}

}

