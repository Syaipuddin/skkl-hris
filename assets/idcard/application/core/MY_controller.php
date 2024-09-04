<?php

class MY_Controller extends CI_Controller {

	private $url_lists;
	private $url_add;
	private $url_edit;
	private $url_add_process;
	private $url_edit_process;
	private $url_realpath_view;
	private $url_realpath_form;
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('session_model');
		
		if ( !$this->session_model->get_loggedin_loginname()){ 
    		redirect(URL_HOME);
		}
		
	}
	
}
