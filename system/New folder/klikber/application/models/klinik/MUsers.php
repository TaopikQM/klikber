<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MUsers extends CI_Model { 
    
    public function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('klinik', TRUE);
	}

    public function get_all() {
        // return $this->db->get('Users')->result();
        $query = $this->db->query("SELECT u.*, r.*
        FROM users u
        INNER JOIN role r ON u.id_role = r.id");
        return $query->result();
    }
    public function getAllData()
    {
        $query = $this->db->query("SELECT u.*, r.*, lh.*
        FROM users u
        INNER JOIN role r ON u.id_role = r.id
        INNER JOIN login_history lh ON u.id = lh.id_user");
        return $query->result();
    }

    public function insert($data) {
        return $this->db->insert('Users', $data);
    } 

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('Users', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('Users');
    }

    function get_by_id($id){
		$this->db->where('id', $id);
		return $this->db->get('Users');

	}

    public function get_user_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('users')->row();
    }
    
    public function get_user_by_username($username) {
        $this->db->select('users.id, users.username, users.password, users.id_role');
        $this->db->from('users');
        $this->db->where('username', $username);
        return $this->db->get()->row_array();
    }
    
    public function get_role_by_id($id_role) {
        $this->db->select('nama_role');
        $this->db->from('role');
        $this->db->where('id', $id_role);
        return $this->db->get()->row_array();
    }

    public function get_user_datas($username) {
        // Cek format username
        if (preg_match('/^D\d{4}\d+$/', $username)) { // Format Dokter: D+tahun+id_dokter
            $id = substr($username, 5); // Ambil id_dokter dari username
            $this->db->select('nama, alamat, no_hp');
            $this->db->from('dokter');
            $this->db->where('id', $id);
            $query = $this->db->get();
            $role = 'Dokter';
        } elseif (preg_match('/^\d{6}-\d{3}$/', $username)) { // Format Pasien: tahun+bulan-urutan
            $id = substr($username, 7); // Ambil id_pasien dari format
            $this->db->select('nama, alamat, no_ktp, no_hp');
            $this->db->from('pasien');
            $this->db->where('id', $id);
            $query = $this->db->get();
            $role = 'Pasien';
        } elseif (preg_match('/^A\d{4}\d+$/', $username)) { // Format Admin: A+tahun+id_admin
            $id = substr($username, 5); // Ambil id_admin dari username
            $this->db->select('nama, alamat, no_hp');
            $this->db->from('admin');
            $this->db->where('id', $id);
            $query = $this->db->get();
            $role = 'Admin';
        } else {
            return null; // Format tidak valid
        }

        // Jika data ditemukan, masukkan ke array
        if ($query->num_rows() > 0) {
            $user_data = $query->row_array();
            $user_data['role'] = $role;
            return $user_data;
        }

        return null; // Data tidak ditemukan
    }
    
    // Ambil data berdasarkan username
    public function get_user_data($username) {
        if (preg_match('/^D(\d{4})-(\d+)$/', $username, $matches)) {
            // Format Dokter
            $id_dokter = $matches[2];
            $this->db->select('dokter.*, poli.nama_poli');
            $this->db->from('dokter');
            $this->db->join('poli', 'poli.id = dokter.id_poli');
            $this->db->where('dokter.id', $id_dokter);
            return $this->db->get()->row_array();
        } elseif (preg_match('/^(\d{4})(\d{2})-(\d{3})$/', $username, $matches)) {
            // Format Pasien
            $id_pasien = $matches[3];
            $this->db->select('*');
            $this->db->from('pasien');
            $this->db->where('id', $id_pasien);
            return $this->db->get()->row_array();
        } elseif (preg_match('/^A(\d{4})(\d+)$/', $username, $matches)) {
            // Format Admin
            $id_admin = $matches[2];
            $this->db->select('*');
            $this->db->from('admin');
            $this->db->where('id', $id_admin);
            return $this->db->get()->row_array();
        }

        return null;
    }
    public function get_user_datam($username) {
        // Menentukan role dan tabel berdasarkan format username
        if (preg_match('/^D(\d{4})(\d+)$/', $username, $matches)) {
            $table = 'dokter';
            $id_field = 'id';
            $id = $matches[2]; // ID dari username format D20241
        } elseif (preg_match('/^(\d{4}-\d{2}-\d{3})$/', $username, $matches)) {
            $table = 'pasien';
            $id_field = 'id';
            $id = ltrim($matches[0], '0'); // ID dari username format 2024-01-001
        } elseif (preg_match('/^A(\d{4})(\d+)$/', $username, $matches)) {
            $table = 'admin';
            $id_field = 'id';
            $id = $matches[2]; // ID dari username format A20241
        } else {
            return null; // Format tidak dikenali
        }

        // Query untuk mendapatkan data user berdasarkan tabel terkait
        $this->db->select("$table.*, users.id_role, role.nama_role")
                 ->from($table)
                 ->join('users', "users.username = '$username'", 'inner')
                 ->join('role', 'users.id_role = role.id', 'inner')
                 ->where("$table.$id_field", $id);
        $query = $this->db->get();
        return $query->row_array(); // Mengembalikan data user dalam array
    }
    public function update_passwordk($idus, $old_password, $new_password)
    {
        // Ambil pengguna berdasarkan `idus`
        $user = $this->db->get_where('users', ['id' => $idus])->row();
     
        // Jika pengguna ditemukan dan password lama cocok
        if ($user && password_verify($old_password, $user->password)) {
            // Set new password
            $data = ['password' => password_hash($new_password, PASSWORD_BCRYPT)];
            $this->db->where('id', $idus);
            $this->db->update('users', $data);
            return $this->db->affected_rows() > 0; // Return true jika ada perubahan
        }
    
        return false; // Password lama tidak sesuai
    }
    
    public function update_passworda($id, $new_password) {
        $data = array(
            'password' => password_hash($new_password, PASSWORD_DEFAULT) // Hash password baru
        );
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }
    public function update_password($id, $new_password) {
        $data = array(
            'password' => password_hash($new_password, PASSWORD_DEFAULT) // Hash password baru
        );
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }
    public function count_users() {
        return $this->db->count_all_results('users');
      }

      public function count_logs() {
        return $this->db->count_all_results('login_history');
      }

    
    
    
}