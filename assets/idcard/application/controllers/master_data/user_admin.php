<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_admin extends CI_Controller {

	function __construct()
	{
        parent::__construct();
        if(!$this->session->userdata('loginFlag'))
        {   
            redirect('backend');
        }
        $this->url_add_process = URL_ADD_USER_ADMIN;
        $this->url_edit_new_process = URL_EDIT_USER_ADMIN;
        $this->url_delete_new_process = URL_DELETE_USER_ADMIN;
        $this->url_search_user = URL_SEARCH_USER;
        $this->load->model('user_model');
		
	}

	public function index()
	{
		/* URL LINKS */
        if($this->session->userdata('loginFlag'))
        {
             /* URL LINKS */
             redirect('master_data/user_admin/lists');
        }
        else
        {
            redirect('backend');            
        }
	}

	
	public function lists()
    {
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;


        $config["base_url"] = base_url() . URL_USER_LIST;
        $config["per_page"] = 10;
        $total_rows_group = $this->user_model->get_total_row_all_data_admin();
        $request_data_group = $this->user_model->get_all_data_admin($page, $config["per_page"]);
        $config["total_rows"] = $total_rows_group;
        $config["uri_segment"] = 4;
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $this->pagination->initialize($config);
        $data["request_data"] = $request_data_group;
        $data["links"] = $this->pagination->create_links();
        $data['title_user']= TITLE_USER;
        $link['new_user'] = $this->url_add_process."/";
        $link['edit_user'] = $this->url_edit_new_process."/";
        $link['delete_user'] = $this->url_delete_new_process."/";
        $data['action'] = $this->url_search_user."/";
        $data['link'] = $link;
        

        $this->load->view(URL_TEMPLATE_MAIN_TOP);
        $this->load->view('backend/user_admin/user_view', $data);
        $this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
    }

    public function search()
    {
        $nama = $this->input->post('txt_nama');
     
        if($nama=='')
        {
            redirect(URL_USER_LIST); 
        }
        
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $config["base_url"] = base_url() . URL_USER_LIST;
        $config["per_page"] = 10;
        $config["uri_segment"] = 4;
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $this->pagination->initialize($config);
        $request_data_group = $this->user_model->search_name($nama);
        $data["request_data"] = $request_data_group;
        $link['new_user'] = $this->url_add_process."/";
        $link['edit_user'] = $this->url_edit_new_process."/";
        $link['delete_user'] = $this->url_delete_new_process."/";
        $data['action'] = $this->url_search_user."/";
        $data['link'] = $link;
        $data['title_user']= TITLE_USER;
        
        $this->load->view(URL_TEMPLATE_MAIN_TOP);
        $this->load->view('backend/user_admin/user_view', $data);
        $this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
    }



    /* USER ADD, EDIT, DELETE */

    public function add_user()
    {
        $options_sap = array(OPT_SAP_VALUE => OPT_SAP_TEXT, OPT_NON_SAP_VALUE => OPT_NON_SAP_TEXT); 
        //$options_group = array(OPT_ROLE_ADMIN_STATUS_VALUE => OPT_ROLE_ADMIN_STATUS_TEXT, OPT_ROLE_STAFF_STATUS_VALUE => OPT_ROLE_STAFF_STATUS_TEXT); 
        $data['option_sap'] = $options_sap;
        $data['act']= 'add';
        //$data['option_group'] = $options_group;
        $data['group_list'] = $this->user_model->get_group_id();
        $data['persadmin_list'] = $this->user_model->get_persadmin();
        $data['action'] = URL_ADD_USER_ADMIN_EXE;
        $data['title_user']= TITLE_USER_ADD;
        $this->load->view(URL_TEMPLATE_MAIN_TOP);
        $this->load->view('backend/user_admin/user', $data);
        $this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
        $this->load->view('backend/user_admin/user_js');
    }

    public function add_user_process()
    {
        $data['action'] =  URL_ADD_USER_ADMIN_EXE;
        $nik = $this->input->post('nik');
        $nama = $this->input->post('nama');
        $unit = $this->input->post('unit');
        $password = $this->input->post('password');
        $group = $this->input->post('group_id');
        $user_sap = $this->input->post('hidden_id_sap');
        $persadmin = $this->input->post('persadmin');
        $ddn_persadmin = $this->input->post('ddn_persadmin');
        $count_user = $this->user_model->check_user_nik($nik);


        if($count_user >= 1)
        {
            $this->session->set_flashdata('notif_type','alert-danger');
            $this->session->set_flashdata('notif_text','User has been register');
            redirect(URL_ADD_USER_ADMIN);    
        }
        else{

            if($persadmin!='')
            {
                $arr_new_user = array('nik' => $nik, 'password' => md5($password), 
                        'nama' => $nama, 'unit' => $unit, 
                        'id_group' => $group, 'persadmin' => $persadmin, 
                        'nama' => $nama, 'unit' => $unit, 
                        'is_sap' => $user_sap,'active' => 1);
            }
            else
            {
                $arr_new_user = array('nik' => $nik, 'password' => md5($password), 
                        'nama' => $nama, 'unit' => $unit, 
                        'id_group' => $group, 'persadmin' => $ddn_persadmin, 
                        'nama' => $nama, 'unit' => $unit, 
                        'is_sap' => $user_sap,'active' => 1);
            }
            
            $this->user_model->add_user($arr_new_user);
            $this->session->set_flashdata('notif_type','alert-success');
            $this->session->set_flashdata('notif_text','Success Add User');
            redirect(URL_USER_LIST);  
        }

    }


    public function edit_user($id)
    {
        $options_sap = array(OPT_SAP_VALUE => OPT_SAP_TEXT, OPT_NON_SAP_VALUE => OPT_NON_SAP_TEXT); 
        $data['option_sap'] = $options_sap;
        $data['group_list'] = $this->user_model->get_group_id();
        $data['persadmin_list'] = $this->user_model->get_persadmin();
        
        $data['title_user']= TITLE_USER_EDIT;
        $data['act']= 'edit';
        $data['old']=$this->user_model->get_user_by_id($id);
        $data['action'] =  URL_EDIT_USER_ADMIN_EXE;
        $this->load->view(URL_TEMPLATE_MAIN_TOP);
        $this->load->view('backend/user_admin/user',$data);
        $this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
        $this->load->view('backend/user_admin/user_js');
    }

    public function edit_user_process()
    {
        $data['action'] =  URL_EDIT_USER_ADMIN_EXE;
        $nik = $this->input->post('nik');
        $nama = $this->input->post('nama');
        $unit = $this->input->post('unit');
        $password = $this->input->post('password');
        $group = $this->input->post('group_id');
        $user_sap = $this->input->post('hidden_id_sap');
        $persadmin = $this->input->post('persadmin');
        $ddn_persadmin = $this->input->post('ddn_persadmin');
        $count_user = $this->user_model->check_user_nik($nik);
        $id_user = $this->input->post('hidden_id_user');

        if($persadmin!='')
        {
            $arr_update_user = array('nik' => $nik, 'password' => md5($password), 
                    'nama' => $nama, 'unit' => $unit, 
                    'id_group' => $group, 'persadmin' => $persadmin, 
                    'nama' => $nama, 'unit' => $unit, 
                    'is_sap' => $user_sap,'active' => 1);
        }
        else
        {
            $arr_update_user = array('nik' => $nik, 'password' => md5($password), 
                    'nama' => $nama, 'unit' => $unit, 
                    'id_group' => $group, 'persadmin' => $ddn_persadmin, 
                    'nama' => $nama, 'unit' => $unit, 
                    'is_sap' => $user_sap,'active' => 1);
        }
        
        $this->user_model->update_user($id_user,$arr_update_user);
        $this->session->set_flashdata('notif_type','alert-success');
        $this->session->set_flashdata('notif_text','Success Update User');
        redirect(URL_USER_LIST);  
    

    }

    public function delete_user($id)
    {
        $arr_delete_user = array('active' => 0);
        $this->user_model->delete_user($id,$arr_delete_user);
        $this->session->set_flashdata('notif_type','alert-success');
        $this->session->set_flashdata('notif_text','Success Delete User');
        redirect(URL_USER_LIST);    
    }



    public function get_data_user_name(){
        $nama = $this->input->post('nama');
       $result = $this->user_model->search_data_by_name($nama);
       echo json_encode($result);
    }

    public function search_user()
    {
        $data['action'] = SEARCH_USER_PROCESS_NAME;
        $this->load->view('backend/user_admin/search_user',$data);
    }

    public function search_name_process()
    {
        $data['action'] = SEARCH_USER_PROCESS_NAME;
        $nama = $this->input->post('nama');
        $data['request_data']=$this->user_model->search_data_by_name($nama);
        $this->load->view('backend/user_admin/search_user',$data);
    }
}
