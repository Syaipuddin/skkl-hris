<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Charles
class Tagihan extends CI_Controller 
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

		$this->url_search_nama = URL_SEARCH_NAMA_TAGIHAN;
		
		$this->load->model('tagihan_model');
		$this->load->model('user_model');
		
		$this->load->helper('number');
	}

	public function index()
	{
		if($this->session->userdata('loginFlag'))
		{
			 /* URL LINKS */
			 redirect(URL_TAGIHAN_LISTS);
		}
		else
		{
			redirect('backend');			
		}
		
	}
	
	public function test(){
		
	}

	public function lists()
	{
		$data['pers_admin_list'] = $this->tagihan_model->get_all_persadmin();
		$options_status = array('' => 'All', OPT_CETAK_VALUE => OPT_CETAK_TEXT, OPT_BLM_CETAK_VALUE => OPT_BLM_CETAK_TEXT); 
		$data['option_status'] = $options_status;
		$status_card = array('' => 'All', OPT_STATUS_NEW_VALUE => OPT_STATUS_NEW_TEXT, OPT_STATUS_EXT_VALUE => OPT_STATUS_EXT_TEXT, OPT_STATUS_LOST_VALUE => OPT_STATUS_LOST_TEXT); 
		$data['option_status_card'] = $status_card;
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

    	$config["base_url"] = base_url() . URL_TAGIHAN_LISTS;
        $config["per_page"] = 20;

        //if($this->session->userdata('role')==OPT_ROLE_ADMIN_STATUS_VALUE){
        	$total_rows_group = $this->tagihan_model->get_total_row_all_data_idcard_tagihan();
			$request_data_group = $this->tagihan_model->get_all_data_idcard_tagihan($page, $config["per_page"]);
        //}
		
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
    	$link['edit_new_idcard'] = $this->url_edit_new_process."/";
    	$link['delete_new_idcard'] = $this->url_delete_new_process."/";
    	$data['action'] = $this->url_search_nama."/";
        $data['link'] = $link;
		
		$this->load->view(URL_TEMPLATE_MAIN_TOP);
		$this->load->view('backend/tagihan/tagihan_view', $data);
		$this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
		$this->load->view('backend/tagihan/tagihan_view_js');
	}

	public function pembilang($num,$lang){
		$hasil = number_to_words($num,$lang);
		return substr_replace($hasil, strtoupper(substr($hasil, 0, 1)), 0, 1);
	}
	
	public function search()
	{
		$data['pers_admin_list']  = $this->tagihan_model->get_all_persadmin();
		$options_status = array('' => 'All', OPT_CETAK_VALUE => OPT_CETAK_TEXT, OPT_BLM_CETAK_VALUE => OPT_BLM_CETAK_TEXT); 
		$data['option_status'] = $options_status;
		$status_card = array('' => 'All', OPT_STATUS_NEW_VALUE => OPT_STATUS_NEW_TEXT, OPT_STATUS_EXT_VALUE => OPT_STATUS_EXT_TEXT, OPT_STATUS_LOST_VALUE => OPT_STATUS_LOST_TEXT, OPT_STATUS_BROKEN_VALUE => OPT_STATUS_BROKEN_TEXT); 
		$data['option_status_card'] = $status_card;
		
		$slc_persadmin = $this->input->post('slc_persadmin');
		$options_status = $this->input->post('slc_status');
		$start_period = $this->input->post('start_period');
		
		$date1 = date("Y-m-d h:i:s", strtotime($this->input->post('end_period') . "+1 day -1 second"));
		$end_period = $date1;
		
		$status_card = $this->input->post('slc_reqstatus');
		
		$data['action'] = $this->url_search_nama."/";
		$link['new_id_card'] = $this->url_add_process."/";
    	$link['extend_id_card'] = $this->url_extend_process."/";
    	$link['lost_id_card'] = $this->url_lost_process."/";
    	$link['edit_new_idcard'] = $this->url_edit_new_process."/";
    	$link['delete_new_idcard'] = $this->url_delete_new_process."/";
        $data['link'] = $link;
        //if($this->session->userdata('role')==OPT_ROLE_ADMIN_STATUS_VALUE){
			$data['total_card'] = $this->tagihan_model->get_total_row_all_data_tagihan($start_period, $end_period);
			$data['total_card_kasir'] = $this->tagihan_model->get_total_row_all_data_tagihan_kasir($start_period, $end_period);
			
			$data['total_card_selected_persadmin'] = $this->tagihan_model->get_total_row_all_data_tagihan2($slc_persadmin,$start_period, $end_period);
			$data['total_card_selected_persadmin_kasir'] = $this->tagihan_model->get_total_row_all_data_tagihan2_kasir($slc_persadmin,$start_period, $end_period);
			
			$request_data_group = $this->tagihan_model->get_all_data_search_idcard_tagihan($slc_persadmin, $start_period, $end_period, $options_status, $status_card);
			$tampung = $this->tagihan_model->get_tarif_per_persadmin($slc_persadmin, $start_period, $end_period, $options_status, $status_card);
		//}
		
		$data["request_data"] = $request_data_group;
		
		if ($this->tagihan_model->get_total_row_tagihan_persadmin_nonsap($slc_persadmin) >= 1){
			$pers_admin = $this->tagihan_model->get_all_row_tagihan_persadmin_nonsap($slc_persadmin);
		}
		else{
			if ($this->tagihan_model->get_total_row_tagihan_persadmin_sap($slc_persadmin) >= 1){
				$pers_admin = $this->tagihan_model->get_all_row_tagihan_persadmin_sap($slc_persadmin);
			}
		}
		
		if(count($tampung)){
			$rp = $tampung->row * $tampung->nominal_invoice;
			$this->session->set_userdata('tagihan_kartu',number_format($tampung->row, 0, ",", "."));
			$this->session->set_userdata('tagihan_biayaperkartu',number_format($tampung->nominal_invoice, 0, ",", "."));
			$this->session->set_userdata('tagihan_terbilang', $this->pembilang($rp, 'id'));
			$this->session->set_userdata('rp',number_format($rp, 0, ",", "."));
			$data["rp"] = number_format($rp, 0, ",", ".");
			$data["terbilang"] = $this->pembilang($rp, 'id');
		}
		
		$this->session->set_userdata('nama_persadmin',$pers_admin);
		
		$data["start_period"] = $start_period;
		$data["end_period"] = $this->input->post('end_period');
		$data["slc_persadmin"] = $slc_persadmin;
		$data["slc_status"] = $this->input->post('slc_status');
		$data["slc_reqstatus"] = $this->input->post('slc_reqstatus');
		
		$this->session->set_userdata('tanggal', $this->bulan($this->input->post('start_period'),$this->input->post('end_period')));
		$data["pers_admin"] = $pers_admin;
		
		$this->load->view(URL_TEMPLATE_MAIN_TOP);
		$this->load->view('backend/tagihan/tagihan_view', $data);
		$this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
		$this->load->view('backend/tagihan/tagihan_view_js');
	}
	
	public function create_excel($slc_persadmin, $start_period, $end_period, $options_status='', $status_card=''){
		//$date1 = date("Y-m-d h:i:s", strtotime($this->input->post('end_period') . "+1 day -1 second"));
		$date1 = date("Y-m-d h:i:s", strtotime($end_period . "+1 day -1 second"));
		$end_period = $date1;

		$request_data_group = $this->tagihan_model->get_all_data_search_idcard_tagihan($slc_persadmin, $start_period, $end_period, $options_status, $status_card);
		$data["request_data"] = $request_data_group;
		
		$this->load->view('backend/tagihan/tagihan_excel', $data);
	}
	
	public function ajax(){
		$this->tagihan_model->update_status_cetak($this->input->post('start_period'),$this->input->post('end_period'),$this->input->post('slc_persadmin'));
	}
	
	public function bulan($StartDate, $EndDate){
		$tanggal_mulai = date("d", strtotime($StartDate));
		$bulan_mulai = date("m", strtotime($StartDate));
		$tahun_mulai = date("Y", strtotime($StartDate));
		
		$tanggal_selesai = date("d", strtotime($EndDate));
		$bulan_selesai = date("m", strtotime($EndDate));
		$tahun_selesai = date("Y", strtotime($EndDate));
		
		$this->load->helper('pembilang');
		$pembilang_bulan_mulai = pembilang_bulan_id($bulan_mulai);
		
		if($bulan_mulai == $bulan_selesai && $tahun_mulai == $tahun_selesai){
			return $tanggal_mulai.' - '.$tanggal_selesai.' '.$pembilang_bulan_mulai.' '.$tahun_mulai;
		}
		else{
			$pembilang_bulan_selesai = pembilang_bulan_id($bulan_selesai);
			return $tanggal_mulai.' '.$pembilang_bulan_mulai.' '.$tahun_mulai.' - '.$tanggal_selesai.' '.$pembilang_bulan_selesai.' '.$tahun_selesai;
		}
	}
	

}

