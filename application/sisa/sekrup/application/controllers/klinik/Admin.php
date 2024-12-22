<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends CI_Controller {

   
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
            $this->load->model('Klinik/MDokter');
            $this->load->model('Klinik/MPoli');
            $this->load->model('Klinik/MUsers');
            $this->load->model('Klinik/MAdmin');
            
            $this->load->model('Klinik/MObat');
            $this->load->model('Klinik/MRole');
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
            $data['admins'] = $this->MAdmin->get_all();
            $data['total_dokter'] = $this->MDokter->count_dokter();
            $data['total_pasien'] = $this->MPasien->count_pasien();
            $data['total_obat'] = $this->MObat->count_obat();
            $data['total_poli'] = $this->MPoli->count_poli();
            $data['total_users'] = $this->MUsers->count_users();
            $data['total_admin'] = $this->MAdmin->count_admin();
            $data['total_role'] = $this->MRole->count_role();
            $ghj=$this->load->view('klinik/page/dashboard-up', $data,TRUE);
            $this->konten($ghj);	//v_data_admin
        }
    
        public function createe() {
            $ghj = $this->load->view('klinik/page/in-admin', '', TRUE);
            $this->konten($ghj);
        }
        public function create() {
            
            // $data['users'] = $this->MUsers->get_all();
            // $last_id_admin = $this->MAdmin->get_last_id_admin();
        
            // $new_id_admin = $last_id_admin + 1;
        
            // $tahun = date('Y');
            // $bulan = date('m');
            // $username = 'A'.$tahun . $bulan . '-' . str_pad($new_id_admin, 3, '0', STR_PAD_LEFT);
        
            // // Kirim data ke view
            // $data = [
            //     'new_id_admin' => $new_id_admin, // ID pasien baru
            //     'username' => $username, 
            // ];
        
            // Load view dan tampilkan data
            $ghj = $this->load->view('klinik/page/in-admin', '', TRUE);
            $this->konten($ghj);
        }
    
        public function store() {
            // Validasi input
            $this->form_validation->set_rules('nama', 'Nama Dokter', 'required');
            $this->form_validation->set_rules('alamat', 'Alamat', 'required');
            $this->form_validation->set_rules('no_hp', 'No HP', 'required');
        
            if ($this->form_validation->run() == FALSE) {
                $data['admins'] = $this->MAdmin->get_all();
                $ghj = $this->load->view('klinik/page/in-admin', $data, TRUE);
                $this->konten($ghj);
            } else {
                $data_admin = [
                    'nama' => $this->input->post('nama'),
                    'alamat' => $this->input->post('alamat'),
                    'no_hp' => $this->input->post('no_hp'),
                ];
        
                $id_admin = $this->MAdmin->insert($data_admin); // Ambil ID yang baru disimpan
        
                // Dapatkan ID terakhir dan tambahkan 1
                $last_id = $this->MAdmin->get_last_id_admin();
                $new_id = $last_id ;

                // Format username dengan 3 angka
                $formatted_id = str_pad($new_id, 3, '0', STR_PAD_LEFT); // Contoh: '001', '002', '003'

                $username = 'A' . date('Y') . '-' . $formatted_id;
                $password = 'A' . date('Y') . '-' . $formatted_id;

                $data_user = [
                    'username' => $username,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'id_role' => 1, 
                ];
                // Simpan data user ke tabel user
                $this->MUsers->insert($data_user);
                // Redirect setelah penyimpanan berhasil
                redirect('admin');
            }
        }
        
    
        public function edit($id) {
            
            $ida=str_replace(array('-','_','~'),array('+','/','='),$id);
            $d=base64_decode($this->encryption->decrypt($ida));
            $has['admin']=$this->MAdmin->get_by_id($d)->result();
            
            $ghj=$this->load->view('klinik/page/v-edit-admin',$has,TRUE);
            $this->konten($ghj);
        }
    
        public function update() {
            // Validasi input
            $id = $this->input->post('id');
            $this->form_validation->set_rules('nama', 'Nama Admin', 'required');
            $this->form_validation->set_rules('alamat', 'Alamat', 'required');
            $this->form_validation->set_rules('no_hp', 'No HP', 'required');
        
            if ($this->form_validation->run() == FALSE) {
                // Jika validasi gagal, tampilkan kembali form edit
                $data['admin'] = $this->MAdmin->get_by_id($id);
                $this->load->view('klinik/page/v-edit-admin', $data);
            } else {
                // Jika validasi berhasil, lanjutkan dengan update
                $data = [
                    'nama' => $this->input->post('nama'),
                    'alamat' => $this->input->post('alamat'),
                    'no_hp' => $this->input->post('no_hp')
                ];
                $this->MAdmin->update($id, $data);
                redirect('admin');
            }
        }
    
        public function delete() {
            
            $id = $this->input->post('id');
            $this->MAdmin->delete($id);
            redirect('admin');
        }
    
        public function profile() {
            $user_data = $this->session->userdata('user_data');
    
            if ($user_data) {
                $data = [
                    'username' => $this->session->userdata('useryyy'),
                    'idus' => $this->session->userdata('idus'),
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