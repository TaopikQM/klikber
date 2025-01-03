<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Dokter extends CI_Controller {

	public function __construct(){
		parent::__construct();
		/*$hjk=$this->session->userdata(base64_encode('jajahan'));
		$sec=FALSE;
		foreach ($hjk as $kaa) {
			if ($kaa[base64_encode('apli')]==base64_encode('1_morsip')) {
				$sec=TRUE;
			}
		}*/
		// $sec=$this->session->userdata('idus');
		// if ($sec == NULL) {
		// 	redirect('landing/menu');
		// }
		// date_default_timezone_set("Asia/Jakarta");
		$this->load->model('Klinik/MDokter');
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
        $data['dokters'] = $this->MDokter->get_all();
        $ghj=$this->load->view('klinik/page/v_data_dokter', $data,TRUE);
		$this->konten($ghj);	
    }

    public function create() {
        $ghj=$this->load->view('klinik/page/in-dokter','',TRUE);
		$this->konten($ghj);
    }

    public function store() {
        $nama = $this->input->post('nama');
        $alamat = $this->input->post('alamat');
        $no_hp = $this->input->post('no_hp');
        $id_poli = $this->input->post('id_poli');

        // Generate username $id_dokter = $this->db->insert_id();
        $username = 'D' . date('Y') . $id_dokter . $id_poli;
        $password = $username;

        $data = [
            'nama' => $nama,
            'alamat' => $alamat,
            'no_hp' => $no_hp,
            'id_poli' => $id_poli,
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'id_role' => 3 // Default role for dokter
        ];

        $this->MDokter->insert($data);
        redirect('dokter');
    }

    public function edit($id) {
        
		$ida=str_replace(array('-','_','~'),array('+','/','='),$id);
		$d=base64_decode($this->encryption->decrypt($ida));
		$has['dokter']=$this->MDokter->get_by_id($d)->result();
		$ghj=$this->load->view('klinik/page/v-edit-dokter',$has,TRUE);
		$this->konten($ghj);
    }

	public function update() {
		// Validasi input
		$id = $this->input->post('id');
		$this->form_validation->set_rules('nama', 'Nama Dokter', 'required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required');
		$this->form_validation->set_rules('no_hp', 'No HP', 'required');
		$this->form_validation->set_rules('id_poli', 'ID Poli', 'required');
	
		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal, tampilkan kembali form edit
			$data['dokter'] = $this->MDokter->get_by_id($id);
			$this->load->view('klinik/page/v-edit-dokter', $data);
		} else {
			// Jika validasi berhasil, lanjutkan dengan update
			$data = [
				'nama' => $this->input->post('nama'),
				'alamat' => $this->input->post('alamat'),
				'no_hp' => $this->input->post('no_hp'),
                'id_poli' => $this->input->post('id_poli')
			];
			$this->MDokter->update($id, $data);
			redirect('dokter');
		}
	}

    public function delete() {
		
		$id = $this->input->post('id');
        $this->MDokter->delete($id);
        redirect('dokter');
    }
}



    // Fungsi untuk menampilkan dashboard admin
    public function dashboard() {
        $this->load->view('admin/dashboard');
    }

    // Fungsi untuk mengelola admin
    public function manage_admin() {
        $data['admin'] = $this->MKlinik->get_all_admin();
        $this->load->view('admin/manage_admin', $data);
    }

    public function add_admin() {
        if ($this->input->post()) {
            $data = [
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'no_hp' => $this->input->post('no_hp')
            ];
            $this->MKlinik->insert_admin($data);
            $username = $this->MKlinik->generate_username_admin($this->db->insert_id());
            $this->MKlinik->insert_user(['username' => $username, 'password' => $username, 'id_role' => 1]); // Asumsi role admin adalah 1
            redirect('admin/manage_admin');
        }
        $this->load->view('admin/add_admin');
    }

    public function edit_admin($id) {
        if ($this->input->post()) {
            $data = [
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'no_hp' => $this->input->post('no_hp')
            ];
            $this->MKlinik->update_admin($id, $data);
            redirect('admin/manage_admin');
        }
        $data['admin'] = $this->MKlinik->get_admin_by_id($id);
        $this->load->view('admin/edit_admin', $data);
    }

    public function delete_admin($id) {
        $this->MKlinik->delete_admin($id);
        redirect('admin/manage_admin');
    }

    // Fungsi untuk mengelola dokter
    public function manage_dokter() {
        $data['dokter'] = $this->MKlinik->get_all_dokter();
        $this->load->view('admin/manage_dokter', $data);
    }

    public function add_dokter() {
        if ($this->input->post()) {
            $data = [
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'no_hp' => $this->input->post('no_hp'),
                'id_poli' => $this->input->post('id_poli')
            ];
            $this->MKlinik->insert_dokter($data);
            $username = $this->MKlinik->generate_username_dokter($this->db->insert_id(), $data['id_poli']);
            $this->MKlinik->insert_user(['username' => $username, 'password' => $username, 'id_role' => 2]); // Asumsi role dokter adalah 2
            redirect('admin/manage_dokter');
        }
        $this->load->view('admin/add_dokter');
    }

    public function edit_dokter($id) {
        if ($this->input->post()) {
            $data = [
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'no_hp' => $this->input->post('no_hp'),
                'id_poli' => $this->input->post('id_poli')
            ];
            $this->MKlinik->update_dokter($id, $data);
            redirect('admin/manage_dokter');
        }
        $data['dokter'] = $this->MKlinik->get_dokter_by_id($id);
        $this->load->view('admin/edit_dokter', $data);
    }

    public function delete_dokter($id) {
        $this->MKlinik->delete_dokter($id);
        redirect('admin/manage_dokter');
    }

    // Fungsi untuk mengelola pasien
    public function manage_pasien() {
        $data['pasien'] = $this->MKlinik->get_all_pasien();
        $this->load->view('admin/manage_pasien', $data);
    }

    public function add_pasien() {
        if ($this->input->post()) {
            $data = [
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'no_ktp' => $this->input->post('no_ktp'),
                'no_hp' => $this->input->post('no_hp')
            ];
            $this->MKlinik->insert_pasien($data);
            $username = $this->MKlinik->generate_username_pasien($this->db->insert_id());
            $this->MKlinik->insert_user(['username' => $username, 'password' => $username, 'id_role' => 3]); // Asumsi role pasien adalah 3
            redirect('admin/manage_pasien');
        }
        $this->load->view('admin/add_pasien');
    }

    public function edit_pasien($id) {
        if ($this->input->post()) {
            $data = [
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'no_ktp' => $this->input->post('no_ktp'),
                'no_hp' => $this->input->post('no_hp')
            ];
            $this->MKlinik->update_pasien($id, $data);
            redirect('admin/manage_pasien');
        }
        $data['pasien'] = $this->MKlinik->get_pasien_by_id($id);
        $this->load->view('admin/edit_pasien', $data);
    }

    public function delete_pasien($id) {
        $this->MKlinik->delete_pasien($id);
        redirect('admin/manage_pasien');
    }

    // Fungsi untuk mengelola poli
    public function manage_poli() {
        $data['poli'] = $this->MKlinik->get_all_poli();
        $this->load->view('admin/manage_poli', $data);
    }

    public function add_poli() {
        if ($this->input->post()) {
            $data = [
                'nama_poli' => $this->input->post('nama_poli'),
                'keterangan' => $this->input->post('keterangan')
            ];
            $this->MKlinik->insert_poli($data);
            redirect('admin/manage_poli');
        }
        $this->load->view('admin/add_poli');
    }

    public function edit_poli($id) {
        if ($this->input->post()) {
            $data = [
                'nama_poli' => $this->input->post('nama_poli'),
                'keterangan' => $this->input->post('keterangan')
            ];
            $this->MKlinik->update_poli($id, $data);
            redirect('admin/manage_poli');
        }
        $data['poli'] = $this->MKlinik->get_poli_by_id($id);
        $this->load->view('admin/edit_poli', $data);
    }

    public function delete_poli($id) {
        $this->MKlinik->delete_poli($id);
        redirect('admin/manage_poli');
    }

    // Fungsi untuk mengelola jadwal periksa
    public function manage_jadwal() {
        $data['jadwal'] = $this->MKlinik->get_all_jadwal();
        $this->load->view('admin/manage_jadwal', $data);
    }

    public function add_jadwal() {
        if ($this->input->post()) {
            $data = [
                'id_dokter' => $this->input->post('id_dokter'),
                'hari' => $this->input->post('hari'),
                'jam_mulai' => $this->input->post('jam_mulai'),
                'jam_selesai' => $this->input->post('jam_selesai')
            ];
            $this->MKlinik->insert_jadwal($data);
            redirect('admin/manage_jadwal');
        }
        $this->load->view('admin/add_jadwal');
    }

    public function edit_jadwal($id) {
        if ($this->input->post()) {
            $data = [
                'id_dokter' => $this->input->post('id_dokter'),
                'hari' => $this->input->post('hari'),
                'jam_mulai' => $this->input->post('jam_mulai'),
                'jam_selesai' => $this->input->post('jam_selesai')
            ];
            $this->MKlinik->update_jadwal($id, $data);
            redirect('admin/manage_jadwal');
        }
        $data['jadwal'] = $this->MKlinik->get_jadwal_by_id($id);
        $this->load->view('admin/edit_jadwal', $data);
    }

    public function delete_jadwal($id) {
        $this->MKlinik->delete_jadwal($id);
        redirect('admin/manage_jadwal');
    }

    // Fungsi untuk mengelola obat
    public function manage_obat() {
        $data['obat'] = $this->MKlinik->get_all_obat();
        $this->load->view('admin/manage_obat', $data);
    }

    public function add_obat() {
        if ($this->input->post()) {
            $data = [
                'nama_obat' => $this->input->post('nama_obat'),
                'kemasan' => $this->input->post('kemasan'),
                'harga' => 150000 // Harga default
            ];
            $this->MKlinik->insert_obat($data);
            redirect('admin/manage_obat');
        }
        $this->load->view('admin/add_obat');
    }

    public function edit_obat($id) {
        if ($this->input->post()) {
            $data = [
                'nama_obat' => $this->input->post('nama_obat'),
                'kemasan' => $this-> input->post('kemasan'),
                'harga' => $this->input->post('harga')
            ];
            $this->MKlinik->update_obat($id, $data);
            redirect('admin/manage_obat');
        }
        $data['obat'] = $this->MKlinik->get_obat_by_id($id);
        $this->load->view('admin/edit_obat', $data);
    }

    public function delete_obat($id) {
        $this->MKlinik->delete_obat($id);
        redirect('admin/manage_obat');
    }

    // Fungsi untuk mengelola role
    public function manage_role() {
        $data['role'] = $this->MKlinik->get_all_role();
        $this->load->view('admin/manage_role', $data);
    }

    public function add_role() {
        if ($this->input->post()) {
            $data = [
                'nama_role' => $this->input->post('nama_role')
            ];
            $this->MKlinik->insert_role($data);
            redirect('admin/manage_role');
        }
        $this->load->view('admin/add_role');
    }

    public function edit_role($id) {
        if ($this->input->post()) {
            $data = [
                'nama_role' => $this->input->post('nama_role')
            ];
            $this->MKlinik->update_role($id, $data);
            redirect('admin/manage_role');
        }
        $data['role'] = $this->MKlinik->get_role_by_id($id);
        $this->load->view('admin/edit_role', $data);
    }

    public function delete_role($id) {
        $this->MKlinik->delete_role($id);
        redirect('admin/manage_role');
    }