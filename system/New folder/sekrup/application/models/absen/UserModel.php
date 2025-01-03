<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->dbfaces = $this->load->database('absenm', TRUE);
    
    }

    // Mengambil semua pengguna dengan status pendaftaran
    // public function get_users() {
    //     $query = $this->dbfaces->query("
    //         SELECT users.*, 
    //                IF(register.name IS NOT NULL, 'Registered', 'Not Registered') AS registration_status
    //         FROM users
    //         LEFT JOIN register ON users.name = register.name
    //     ");
    //     return $query->result();
    // }
    public function get_users() {
        $query = $this->dbfaces->query("
            SELECT mhs.mhs_id AS id,
                   mhs.mhs_nama AS name, 
                   mhs.mhs_nim AS nim, 
                   pnd.pendaftaran_status as status,
                   reg.name AS registration_status
            FROM t_mahasiswa mhs
            LEFT JOIN t_pendaftaran pnd ON mhs.mhs_id = pnd.mhs_id
            LEFT JOIN t_register reg ON mhs.mhs_nama = reg.name
        ");
        
        $result = $query->result();
        
        foreach ($result as $row) {
            $row->registration_status = $row->registration_status ? 'Registered' : 'Not Registered';
            $row->status = (int)$row->status === 1 ? 'Active' : 'Inactive';
        }

        return $result;
    }  

    public function updateUser($id, $name, $nim) {
        $data = [
            'mhs_nama' => $name,
            'mhs_nim' => $nim
        ];

        // Melakukan update ke database
        $this->dbfaces->where('mhs_id', $id);
        return $this->dbfaces->update('t_mahasiswa', $data);
    }
  

    public function updateS($id, $status) {
        $this->dbfaces->where('mhs_id', $id);
        return $this->dbfaces->update('t_pendaftaran', ['pendaftaran_status' => $status]);
    }
    

    // Fungsi untuk menghapus pengguna berdasarkan mhs_id
    public function delete_user($mhs_id) {
        // Pertama, ambil name berdasarkan mhs_id dari tabel t_mahasiswa
        $this->dbfaces->select('name');
        $this->dbfaces->where('mhs_id', $mhs_id); // Menggunakan mhs_id
        $query = $this->dbfaces->get('t_mahasiswa');

        if ($query->num_rows() > 0) {
            // Ambil name
            $name = $query->row()->name;

            // Hapus dari tabel t_register menggunakan name
            $this->dbfaces->where('name', $name);
            $this->dbfaces->delete('t_register');

            // Hapus dari tabel t_attendance menggunakan name
            $this->dbfaces->where('name', $name);
            $this->dbfaces->delete('t_attendance');

            // Hapus dari tabel t_pendaftaran menggunakan mhs_id
            $this->dbfaces->where('mhs_id', $mhs_id);
            $this->dbfaces->delete('t_pendaftaran');

            // Hapus dari tabel t_mahasiswa menggunakan mhs_id
            $this->dbfaces->where('mhs_id', $mhs_id);
            $this->dbfaces->delete('t_mahasiswa');

            // Mengembalikan true jika ada perubahan, false jika tidak
            return $this->dbfaces->affected_rows() > 0;
        }

        // Jika tidak ada data ditemukan, kembalikan false
        return false;
    }

    // Cek apakah nama sudah terdaftar
    public function check_existing_register($name) {
        $this->dbfaces->where('name', $name);
        $query = $this->dbfaces->get('register');
        return $query->num_rows() > 0;
    }
}
