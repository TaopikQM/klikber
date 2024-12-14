<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$sec=$this->session->userdata('role');
		if ($sec == NULL) {
			redirect('landing/menu');
		}
		elseif ($sec != 'Admin') { 
			redirect('landing/menu');
		}
		date_default_timezone_set("Asia/Jakarta");
        $this->load->model('Klinik/MRole');
        $this->load->library('form_validation');
		$this->load->library('encryption');
		
		$this->encryption->initialize(
	        array(
	                'cipher' => 'aes-128',
	                'mode' => 'ctr',
	                'key' => 'HJKHASJKD^**&&*(NJSHAHIDAsdfsa'
	        )
		);
    }

    function konten($value=''){
		$data['konten']=$value;
		$this->load->view('Klinik/home',$data);
	}

    public function index() {
		$this->load->helper('pinjam');		
        $data['roles'] = $this->MRole->get_all();
        $ghj=$this->load->view('klinik/page/v_data_role', $data,TRUE);
		$this->konten($ghj);	
    }

    public function create() {
        $ghj=$this->load->view('klinik/page/in-role','',TRUE);
		$this->konten($ghj);
    }

    public function store() {
        $data = [
            'nama_role' => $this->input->post('nama_role')
        ];
        $this->MRole->insert($data);
        redirect('role');
    }
    
    public function edit($id) {
        
		$ida=str_replace(array('-','_','~'),array('+','/','='),$id);
		$d=base64_decode($this->encryption->decrypt($ida));
		$has['role']=$this->MRole->get_by_id($d)->result();
		$ghj=$this->load->view('klinik/page/v-edit-role',$has,TRUE);
		$this->konten($ghj);
    }

    public function update() {
		// Validasi input
		$id = $this->input->post('id');
		$this->form_validation->set_rules('nama_role', 'Nama role', 'required');
	
		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal, tampilkan kembali form edit
			$data['role'] = $this->MRole->get_by_id($id);
			$this->load->view('klinik/page/v-edit-role', $data);
		} else {
			// Jika validasi berhasil, lanjutkan dengan update
			$data = [
				'nama_role' => $this->input->post('nama_role'),
			];
			$this->MRole->update($id, $data);
			redirect('role');
		}
	}

    public function delete() {
        
		$id = $this->input->post('id');
        $this->MRole->delete($id);
        redirect('role');
    }
}