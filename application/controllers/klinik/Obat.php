<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Obat extends CI_Controller {

    
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
		$this->load->model('Klinik/MObat');
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
        $data['obats'] = $this->MObat->get_all();
        $ghj=$this->load->view('klinik/page/v_data_obat', $data,TRUE);
		$this->konten($ghj);	
    }

    public function create() {
        $ghj=$this->load->view('klinik/page/in-obat','',TRUE);
		$this->konten($ghj);
    }

    public function store() {
        $data = [
            'nama_obat' => $this->input->post('nama_obat'),
            'kemasan' => $this->input->post('kemasan'),
            'harga' => $this->input->post('harga')
        ];
        $this->MObat->insert($data);
        redirect('obat');
    }

    public function edit($id) {
        $ida=str_replace(array('-','_','~'),array('+','/','='),$id);
		$d=base64_decode($this->encryption->decrypt($ida));
		$has['obat']=$this->MObat->get_by_id($d)->result();
		$ghj=$this->load->view('klinik/page/v-edit-obat',$has,TRUE);
		$this->konten($ghj);
    }

    public function update() {
		// Validasi input
		$id = $this->input->post('id');
		$this->form_validation->set_rules('nama_obat', 'Nama Obat', 'required');
		$this->form_validation->set_rules('kemasan', 'Kemasan', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required');

		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal, tampilkan kembali form edit

			
			$data['obat'] = $this->MObat->get_by_id($id);
			$this->load->view('klinik/page/v-edit-obat', $data);
		} else {
			// Jika validasi berhasil, lanjutkan dengan update
			$data = [
				'nama_obat' => $this->input->post('nama_obat'),
				'kemasan' => $this->input->post('kemasan'),
				'harga' => $this->input->post('harga')
			];
			$this->MObat->update($id, $data);
			redirect('obat');
		}
	}

    
    public function delete() {
        $id = $this->input->post('id');
        $this->MObat->delete($id);
        redirect('obat');
    }
}