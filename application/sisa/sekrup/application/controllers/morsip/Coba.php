<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coba extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('morsip/mmorsip');
		$this->load->library('form_validation');
		$this->load->library('encryption');
		
		$this->encryption->initialize(
	        array(
	                'cipher' => 'aes-128',
	                'mode' => 'ctr',
	                'key' => 'HJJHJKhahsgdgIYUGKHBJKH^&*^^%^&%^*988qw7e9'
	        )
		);
		date_default_timezone_set("Asia/Jakarta");
		
	}
	public function index(){	
		echo $this->encryption->encrypt("admin_0_superadmin");
		
	}
}