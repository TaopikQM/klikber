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
            $this->load->model('Klinik/MDokter');
            $this->load->model('Klinik/MPoli');
            $this->load->model('Klinik/MUsers');
            $this->load->model('Klinik/MAdmin');
            
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
            // $this->load->helper('pinjam');		
            $data['users'] = $this->MUsers->get_all();
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









        
    public function get_al() {
        return $this->db->get('users')->result();
    }

    public function insert($data) {
        return $this->db->insert('users', $data);
    }
    public function edit($id) {
        $ida=str_replace(array('-','_','~'),array('+','/','='),$id);
		$d=base64_decode($this->encryption->decrypt($ida));
		$has['users']=$this->MUsers->get_by_id($d)->result();
		$ghj=$this->load->view('klinik/page/v-edit-users',$has,TRUE);
		$this->konten($ghj);
    }

    public function update($id, $data) {
        // $this->db->where('id', $id);
        // return $this->db->update('users', $data);
         // Set aturan validasi untuk perubahan password
         $this->form_validation->set_rules('old_password', 'Password Lama', 'required');
         $this->form_validation->set_rules('new_password', 'Password Baru', 'required|min_length[8]');
         $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password Baru', 'required|matches[new_password]');
         if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, set flashdata dengan pesan error dan redirect
            $this->session->set_flashdata('notif_password', validation_errors());
            redirect('klinik/page/v-edit-users');
        } else {
            // Ambil data dari input form
            $username = $this->session->userdata('useryyy');
            $old_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');

            // Proses pembaruan password
            if ($this->mlogin->update_password($username, $old_password, $new_password)) {
                $this->session->set_flashdata('notif_password', 'Password berhasil diubah');
            } else {
                $this->session->set_flashdata('notif_password', 'Password lama tidak sesuai');
            }
            redirect('landing/menu');
        }
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }
    public function get_last_id_pasien() {
        // Ambil ID pasien terakhir berdasarkan ID yang terbesar
        $this->db->select_max('id');  // Ambil nilai terbesar dari kolom id_pasien
        $query = $this->db->get('users');
        
        // Ambil hasil query
        $result = $query->row();
        return $result ? $result->id : 0;  // Jika tidak ada data, return 0
    }
    

    function get_by_id($id){
		$this->db->where('id', $id);
		return $this->db->get('users');
 
	}
    public function get_by_id_users($id)
    {
        $this->db->select('users.*')
                ->from('users')
                ->where('users.id', $id);
        return $this->db->get()->row();
    }


    public function get_aset_id($id) {
        return $this->db->get_where('users', ['id' => $id])->row_array();
    }

} 