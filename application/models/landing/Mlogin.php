<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mlogin extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Jakarta");
		$this->db = $this->load->database('klinik', TRUE);
	
		# code...
	}
	 // Fungsi untuk memeriksa data pengguna berdasarkan username
	 public function check_user($username)
	 {
		 $this->db->select('id, username, password, id_role');
		 $this->db->from('users');
		 $this->db->where('username', $username);
		 return $this->db->get()->row();
	 }
	 public function get_role($id)
    {
        $this->db->select('nama_role');
        $this->db->from('role');
        $this->db->where('id', $id);
        return $this->db->get()->row();
    }
	public function record_login($id, $ip_address)
    {
        $data = [
            'id_user' => $id,
            'ip_address' => $ip_address,
            'login_time' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('login_history', $data);
        return $this->db->insert_id();
    }

    public function update_logout($id, $ip_address)
    {
        $data = [
            'logout_time' => date('Y-m-d H:i:s'),
            'ip_address' => $ip_address
        ];
        $this->db->where('id', $id);
        $this->db->update('login_history', $data);
    }
	 // Fungsi untuk menyimpan riwayat login
	 public function save_login_history($id_user, $ip_address, $waktu_login)
	 {
		 $data = [
			 'id_user' => $id_user,
			 'ip_address' => $ip_address,
			 'waktu_login' => $waktu_login,
		 ];
		 $this->db->insert('riwayat_login', $data);
	 }

	 public function get_user_datam($username)
    { 
        $this->db->select('u.*, r.nama_role, d.nama as dokter_nama, d.alamat as dokter_alamat, 
                           p.nama_poli, p.keterangan as poli_keterangan');
        $this->db->from('users u');
        $this->db->join('role r', 'u.id_role = r.id', 'left');
        $this->db->join('dokter d', 'd.id = u.id', 'left');
        $this->db->join('poli p', 'p.id = d.id_poli', 'left');
        $this->db->where('u.username', $username);
        return $this->db->get()->row_array();
    }
	public function validate_password($user_id, $password)
    {
        $user = $this->db->get_where('users', ['id' => $user_id])->row();
        return $user && password_verify($password, $user->password);
    }

    public function update_password($idus, $new_password)
    {
        $data = ['password' => password_hash($new_password, PASSWORD_DEFAULT)];
        $this->db->update('users', $data, ['id' => $idus]);
    }
	
	public function get_user_data($username)
    {
        $role_data = $this->db->select('r.nama_role')
            ->from('users u')
            ->join('role r', 'u.id_role = r.id')
            ->where('u.username', $username)
            ->get()->row();

        if ($role_data->nama_role == 'dokter') {
            return $this->db->select('d.*, p.nama_poli')
                ->from('dokter d')
                ->join('poli p', 'd.id_poli = p.id')
                ->where('d.id', substr($username, 0))
                ->get()->row_array();
        } elseif ($role_data->nama_role == 'pasien') {
            return $this->db->get_where('pasien', ['id' => substr($username, 4)])->row_array();
        } elseif ($role_data->nama_role == 'admin') {
            return $this->db->get_where('admin', ['id' => substr($username, 2)])->row_array();
        }
        return [];
    }
    // Fungsi untuk login
    public function login($username, $password) {
        $this->db->where('username', $username);
        $user = $this->db->get('users')->row();

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }
}
