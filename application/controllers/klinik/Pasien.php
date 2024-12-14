<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Pasien extends CI_Controller {

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
		}elseif ($sec != 'Pasien' && $sec != 'Admin') {
            redirect('landing/menu');
        }
		date_default_timezone_set("Asia/Jakarta");
		$this->load->model('Klinik/MPasien');
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
        $data['pasiens'] = $this->MPasien->get_all();
        $ghj=$this->load->view('klinik/page/v_data_pasien', $data,TRUE);
		$this->konten($ghj);	//v_data_pasien
    }
    public function tab() {
		$this->load->helper('pinjam');		
        $data['pasiens'] = $this->MPasien->get_all();
        $ghj=$this->load->view('klinik/page/v_data_pasien', $data,TRUE);
		$this->konten($ghj);	//v_data_pasien
    }

   
    public function create() {
        // Ambil ID pasien terakhir
        $last_id_pasien = $this->MPasien->get_last_id_pasien();
    
        // Tentukan ID pasien baru (tambah 1 dari ID terakhir)
        $new_id_pasien = $last_id_pasien + 1;
    
        // Generate nomor RM berdasarkan ID pasien yang baru
        $tahun = date('Y');
        $bulan = date('m');
        $no_rm = $tahun . $bulan . '-' . str_pad($new_id_pasien, 3, '0', STR_PAD_LEFT);
    
        // Kirim data ke view
        $data = [
            'new_id_pasien' => $new_id_pasien, // ID pasien baru
            'no_rm' => $no_rm, // Nomor RM yang baru
        ];
    
        // Load view dan tampilkan data
        $ghj = $this->load->view('klinik/page/in-pasien', $data, TRUE);
        $this->konten($ghj);
    }
    
    public function store() {
        // Validasi input
        $this->form_validation->set_rules('nama', 'Nama Pasien', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('no_ktp', 'No KTP', 'required');
        $this->form_validation->set_rules('no_hp', 'No HP', 'required');
		$this->form_validation->set_rules('no_rm', 'NO RM', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            // Generate nomor RM berdasarkan ID pasien
            $tahun = date('Y');
            $bulan = date('m');
            
            // Data untuk tabel pasien
            $data_pasien = [
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'no_ktp' => $this->input->post('no_ktp'),
                'no_hp' => $this->input->post('no_hp'),
                'no_rm' => $this->input->post('no_rm')
            ];
            // Simpan data pasien ke database
            $this->MPasien->insert($data_pasien);

            // Data untuk tabel user
            $username = $this->input->post('no_rm'); // Gunakan no_rm sebagai username
            $password = $this->input->post('no_rm');
            
            $data_user = [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'id_role' => 2, // Role default dokter
            ];
    
            // Simpan data user ke tabel user
            $this->MUsers->insert($data_user);
    
            // Redirect setelah penyimpanan berhasil
            redirect('pasien');
        }
    }
    
    

    public function edit($id) {
        
		$ida=str_replace(array('-','_','~'),array('+','/','='),$id);
		$d=base64_decode($this->encryption->decrypt($ida));
		$has['pasien']=$this->MPasien->get_by_id($d)->result();
       $ghj=$this->load->view('klinik/page/v-edit-pasien',$has,TRUE);
		$this->konten($ghj);
    }

	public function update() {
		// Validasi input
		$id = $this->input->post('id');
		$this->form_validation->set_rules('nama', 'Nama Pasien', 'required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required');
		$this->form_validation->set_rules('no_ktp', 'No KTP', 'required');
		$this->form_validation->set_rules('no_hp', 'No HP', 'required');
		$this->form_validation->set_rules('no_rm', 'NO RM', 'required');
	
		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal, tampilkan kembali form edit
			$data['pasien'] = $this->MPasien->get_by_id($id);
           $this->load->view('klinik/page/v-edit-pasien', $data);
		} else {
			// Jika validasi berhasil, lanjutkan dengan update
			$data = [
				'nama' => $this->input->post('nama'),
				'alamat' => $this->input->post('alamat'),
				'no_ktp' => $this->input->post('no_ktp'),
                'no_hp' => $this->input->post('no_hp'),
                'no_rm' => $this->input->post('no_rm')
			];
			$this->MPasien->update($id, $data);
			redirect('pasien');
		}
	}

    public function delete() {
		
		$id = $this->input->post('id');
        $this->MPasien->delete($id);
        redirect('pasien');
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

    public function editUs($id) {
        $ida=str_replace(array('-','_','~'),array('+','/','='),$id);
		$d=base64_decode($this->encryption->decrypt($ida));
		$has['users']=$this->MUsers->get_by_id($d)->result();
		$ghj=$this->load->view('klinik/page/v-edit-users',$has,TRUE);
		$this->konten($ghj);
    }

    public function updateUs() {
        
		$idus = $this->input->post('idus');
        
		// $data = $this->input->post('data');
        // $this->db->where('id', $id);
        // return $this->db->update('users', $data);
         // Set aturan validasi untuk perubahan password
         $this->form_validation->set_rules('old_password', 'Password Lama', 'required');
         $this->form_validation->set_rules('new_password', 'Password Baru', 'required|min_length[8]');
         $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password Baru', 'required|matches[new_password]');
         if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, set flashdata dengan pesan error dan redirect
            $this->session->set_flashdata('notif_password', validation_errors());
            // redirect('klinik/page/v-edit-users');
            // redirect(strtolower($this->session->userdata('role')) . '/editUs/' . $idus);
             // Buat URL aman untuk redirect
            $encrypted_idus = $this->encryption->encrypt(base64_encode($idus));
            $secure_id = str_replace(array('+', '/', '='), array('-', '_', '~'), $encrypted_idus);
            $redirect_url = base_url() . $role . '/editUs/' . $secure_id;

            redirect($redirect_url);
        } else {
            // $user = $this->mlogin->check_user($username);
             // Ambil data pengguna berdasarkan ID
            $user = $this->MUsers->get_user_by_id($idus);
        
            $old_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');
            if ($user && password_verify($old_password, $user->password)) {
                // Jika password lama cocok, update password
                if ($this->MUsers->update_password($idus, $new_password)) {
                    $this->session->set_flashdata('notif_password', 'Password berhasil diubah');
                } else {
                    $this->session->set_flashdata('notif_password', 'Gagal mengubah password');
                }
                redirect('landing/menu');
            } else {
                $this->session->set_flashdata('notif_password', 'Password salah');
                redirect('landing#form-section');
            }
            
            // redirect('landing/menu');
        }
    }
    

}