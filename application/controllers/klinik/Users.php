<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
   
   
    public function __construct(){
		parent::__construct();
		
		
            $sec=$this->session->userdata('role');
            if ($sec == NULL) {
                redirect('landing/menu');
            }
            elseif ($sec != 'Admin') {
                redirect('landing/menu');
            }
            date_default_timezone_set("Asia/Jakarta");
            $this->load->model('Klinik/MUsers');
            
            $this->load->model('Klinik/MPasien'); 
            $this->load->library('session');
            $this->load->library('form_validation');
            $this->load->library('encryption');
            
            $this->encryption->initialize(
                array(
                        'cipher' => 'aes-128',
                        'mode' => 'ctr',
                        'key' => 'HJKHASJKD^**&&*(NJSHAHIDAsdfsa'
                )
            );
            // date_default_timezone_set("Asia/Jakarta");
        }

        function konten($value=''){
            $data['konten']=$value;
            $this->load->view('Klinik/home',$data);
        }
    
        public function index() {
            $this->load->helper('pinjam');		
            $data['users'] = $this->MUsers->getAllData();
            $ghj=$this->load->view('klinik/page/v_data_users', $data,TRUE);
            $this->konten($ghj);	
        }

        public function tab() {
            // $this->load->helper('pinjam');		
            $data['users'] = $this->MUsers->get_all();
            $ghj=$this->load->view('klinik/page/v_data_users', $data,TRUE);
            $this->konten($ghj);	
        }
        public function log() {
            $data['users'] = $this->MUsers->getAllData();
            $ghj=$this->load->view('klinik/page/v_data_log_users', $data,TRUE);
            $this->konten($ghj);	
        }

        public function delete() {
            
            $id = $this->input->post('id');
            $this->MUsers->delete($id);
            redirect('users');
        }
} 