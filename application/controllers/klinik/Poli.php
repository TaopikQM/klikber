<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Poli extends CI_Controller {

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
		$this->load->model('Klinik/MPoli');
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
        $data['polis'] = $this->MPoli->get_all();
        $ghj=$this->load->view('klinik/page/v_data_poli', $data,TRUE);
		$this->konten($ghj);	
    }

    public function create() {
        $ghj=$this->load->view('klinik/page/in-poli','',TRUE);
		$this->konten($ghj);
    }

    public function store() {
        $data = [
            'nama_poli' => $this->input->post('nama_poli'),
            'keterangan' => $this->input->post('keterangan')
        ];
        $this->MPoli->insert($data);
        redirect('poli');
    }

    public function edit($id) {
        
		$ida=str_replace(array('-','_','~'),array('+','/','='),$id);
		$d=base64_decode($this->encryption->decrypt($ida));
		$has['poli']=$this->MPoli->get_by_id($d)->result();
		$ghj=$this->load->view('klinik/page/v-edit-poli',$has,TRUE);
		$this->konten($ghj);
    }

    
	public function update() {
		// Validasi input
		$id = $this->input->post('id');
		$this->form_validation->set_rules('nama_poli', 'Nama Poli', 'required');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
	
		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal, tampilkan kembali form edit
			$data['poli'] = $this->MPoli->get_by_id($id);
			$this->load->view('klinik/page/v-edit-poli', $data);
		} else {
			// Jika validasi berhasil, lanjutkan dengan update
			$data = [
				'nama_poli' => $this->input->post('nama_poli'),
				'keterangan' => $this->input->post('keterangan')
			];
			$this->MPoli->update($id, $data);
			redirect('poli');
		}
	}

    public function delete() {
		
		$id = $this->input->post('id');
        $this->MPoli->delete($id);
        redirect('poli');
    }
}