<?php

class MasterData_controller extends MY_Controller {

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
		
	}
	
	function index()
	{
		redirect($this->url_lists);
	}
	
	protected function _view_form($data)
	{
		$this->load->view(URL_TEMPLATE_MAIN_TOP,$data);
		$this->load->view($this->url_realpath_form,$data);
		$this->load->view(URL_TEMPLATE_MAIN_BOTTOM);
		$this->load->view($this->url_realpath_form."_js");
	}
	
}

