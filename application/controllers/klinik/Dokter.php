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
		
		$sec=$this->session->userdata('role');
		if ($sec == NULL) {
			redirect('landing/menu');
		}elseif ($sec != 'Dokter' && $sec != 'Admin') {
            redirect('landing/menu');
        }
		date_default_timezone_set("Asia/Jakarta");
		$this->load->model('Klinik/MDokter');
        $this->load->model('Klinik/MPoli');
        $this->load->model('Klinik/MUsers');
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
        $data['dokters'] = $this->MDokter->get_all();
        $ghj=$this->load->view('klinik/page/v_data_dokter', $data,TRUE);
		$this->konten($ghj);	//v_data_dokter
    }
    public function tab() {
		$this->load->helper('pinjam');		
        $data['dokters'] = $this->MDokter->get_all();
        $ghj=$this->load->view('klinik/page/v_data_dokter', $data,TRUE);
		$this->konten($ghj);	//v_data_dokter
    }

   
    public function create() {
        $data['polis'] = $this->MPoli->get_all();
    
        // Dapatkan ID terakhir dokter, tambahkan 1 untuk ID baru
        $last_id = $this->MDokter->get_last_id_dokter();
        $new_id = $last_id + 1;
    
        // Format username dan password dengan 3 angka
        $formatted_id = str_pad($new_id, 3, '0', STR_PAD_LEFT); // Contoh: '001', '002', '003'
        $username = 'D' . date('Y') . '-' . $formatted_id;
        $password = 'D' . date('Y') . '-' . $formatted_id;
    
        // Tambahkan username dan password ke data view
        $data['username'] = $username;
        $data['password'] = $password;
    
        // Muat view
        $ghj = $this->load->view('klinik/page/in-dokter', $data, TRUE);
        $this->konten($ghj);
    }
    
    public function store() {
        // Validasi input
        $this->form_validation->set_rules('nama', 'Nama Dokter', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('no_hp', 'No HP', 'required');
        $this->form_validation->set_rules('id_poli', 'Poli', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('notif', [
                'tipe' => 2, // alert-warning
                'isi' => 'Validasi gagal. Pastikan semua data diisi dengan benar.'
            ]);
            $this->create();
        } else {
            // Data untuk tabel dokter
            $data_dokter = [
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'no_hp' => $this->input->post('no_hp'),
                'id_poli' => $this->input->post('id_poli'),
            ];
    
            // Simpan data dokter ke tabel dokter
            $id_dokter = $this->MDokter->insert($data_dokter); // Ambil ID yang baru disimpan
    
            // Ambil username dan password dari input
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $data_user = [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'id_role' => 3, // Role default admin
            ];
            // Simpan data user ke tabel user
            $this->MUsers->insert($data_user);
            $this->session->set_flashdata('notif', [
                'tipe' => 1, // alert-primary
                'isi' => 'Data dokter berhasil ditambahkan.'
            ]);
    
            // Redirect setelah penyimpanan berhasil
            redirect('dokter');
        }
    }
    

    public function edit($id) {
        
		$ida=str_replace(array('-','_','~'),array('+','/','='),$id);
		$d=base64_decode($this->encryption->decrypt($ida));
		$has['dokter']=$this->MDokter->get_by_id($d)->result();
        $has['polis'] = $this->MPoli->get_all();
		
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
            $this->session->set_flashdata('notif', [
                'tipe' => 2, // alert-warning
                'isi' => 'Validasi gagal. Pastikan semua data diisi dengan benar.'
            ]);
            
			// // Jika validasi gagal, tampilkan kembali form edit
			// $data['dokter'] = $this->MDokter->get_by_id($id);
            // $data['polis'] = $this->MPoli->get_all();
			// $this->load->view('klinik/page/v-edit-dokter', $data);
            $ida=str_replace(array('-','_','~'),array('+','/','='),$id);
            $d=base64_decode($this->encryption->decrypt($ida));
            $has['dokter']=$this->MDokter->get_by_id($d)->result();
            $has['polis'] = $this->MPoli->get_all();
            
            $ghj=$this->load->view('klinik/page/v-edit-dokter',$has);
		} else {
			// Jika validasi berhasil, lanjutkan dengan update
			$data = [
				'nama' => $this->input->post('nama'),
				'alamat' => $this->input->post('alamat'),
				'no_hp' => $this->input->post('no_hp'),
                'id_poli' => $this->input->post('id_poli')
			];
			$this->MDokter->update($id, $data);
            $this->session->set_flashdata('notif', [
                'tipe' => 1, // alert-primary
                'isi' => 'Data dokter berhasil diperbarui.'
            ]);
			redirect('dokter');
		}
	}

    public function delete() {
		
		$id = $this->input->post('id');
        $this->MDokter->delete($id);
        redirect('dokter');
    }

	public function profile() {
        $user_data = $this->session->userdata('user_data');

		if ($user_data) {
            $data = [
                'username' => $this->session->userdata('useryyy'),
                'role' => $this->session->userdata('role'),
                'id_filtered' => $this->session->userdata('id_filtered'),
                'user_data' => $user_data,
                'name' => $user_data->nama,
				'id' => $user_data->id // Nama user
            ];

          	$ghj=$this->load->view('klinik/page/profile', $data, true);
			$this->konten($ghj);
        } else {
            // Jika user_data tidak ada, redirect ke halaman login
            redirect('landing');
        }
    }
}  