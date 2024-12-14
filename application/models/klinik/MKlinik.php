<?php
class MKlinik extends CI_Model {

    public function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('klinik', TRUE);
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

    // Fungsi untuk menambah admin
    public function insert_admin($data) {
        return $this->db->insert('admin', $data);
    }

    // Fungsi untuk mendapatkan semua admin
    public function get_all_admin() {
        return $this->db->get('admin')->result();
    }

    // Fungsi untuk mengupdate admin
    public function update_admin($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('admin', $data);
    }

    // Fungsi untuk menghapus admin
    public function delete_admin($id) {
        $this->db->where('id', $id);
        return $this->db->delete('admin');
    }

    // Fungsi untuk menambah dokter
    public function insert_dokter($data) {
        return $this->db->insert('dokter', $data);
    }

    // Fungsi untuk mendapatkan semua dokter
    public function get_all_dokter() {
        return $this->db->get('dokter')->result();
    }

    // Fungsi untuk mengupdate dokter
    public function update_dokter($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('dokter', $data);
    }

    // Fungsi untuk menghapus dokter
    public function delete_dokter($id) {
        $this->db->where('id', $id);
        return $this->db->delete('dokter');
    }

    // Fungsi untuk menambah pasien
    public function insert_pasien($data) {
        return $this->db->insert('pasien', $data);
    }

    // Fungsi untuk mendapatkan semua pasien
    public function get_all_pasien() {
        return $this->db->get('pasien')->result();
    }

    // Fungsi untuk mengupdate pasien
    public function update_pasien($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('pasien', $data);
    }

    // Fungsi untuk menghapus pasien
    public function delete_pasien($id) {
        $this->db->where('id', $id);
        return $this->db->delete('pasien');
    }

    // Fungsi untuk menambah poli
    public function insert_poli($data) {
        return $this->db->insert('poli', $data);
    }

    // Fungsi untuk mendapatkan semua poli
    public function get_all_poli() {
        return $this->db->get('poli')->result();
    }

    // Fungsi untuk mengupdate poli
    public function update_poli($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('poli', $data);
    }

    // Fungsi untuk menghapus poli
    public function delete_poli($id) {
        $this->db->where('id', $id);
        return $this->db->delete('poli');
    }

    // Fungsi untuk menambah jadwal periksa
    public function insert_jadwal($data) {
        return $this->db->insert('jadwal_periksa', $data);
    }

    // Fungsi untuk mendapatkan semua jadwal periksa
    public function get_all_jadwal() {
        return $this->db->get('jadwal_periksa')->result();
    }

    // Fungsi untuk mengupdate jadwal periksa
    public function update_jadwal($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('jadwal_periksa', $data);
    }

    // Fungsi untuk menghapus jadwal periksa
    public function delete_jadwal($id) {
        $this->db->where('id', $id);
        return $this->db->delete('jadwal_periksa');
    }

    // Fungsi untuk menambah obat
    public function insert_obat($data) {
        return $this->db->insert('obat', $data);
    }

    // Fungsi untuk mendapatkan semua obat
    public function get_all_obat() {
        return $this->db->get('obat')->result();
    }

    // Fungsi untuk mengupdate obat
    public function update_obat($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('obat', $data);
    }

    // Fungsi untuk menghapus obat
    public function delete_obat($id) {
        $this->db->where('id', $id);
        return $this->db->delete('obat');
    }

    // Fungsi untuk menambah role
    public function insert_role($data) {
        return $this->db->insert('role', $data);
    }

    // Fungsi untuk mendapatkan semua role
    public function get_all_role() {
        return $this->db->get('role')->result();
    }

    // Fungsi untuk mengupdate role
    public function update_role($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('role', $data);
    }

    // Fungsi untuk menghapus role
    public function delete_role($id) {
        $this->db->where('id', $id);
        return $this->db->delete('role');
    }

    // Fungsi untuk generate username dan password untuk dokter
    public function generate_username_dokter($id_dokter, $id_poli) {
        $year = date('Y');
        return 'D' . $year . $id_dokter . $id_poli;
    }

    // Fungsi untuk generate username dan password untuk admin
    public function generate_username_admin($id_admin) {
        $year = date('Y');
        return 'A' . $year . $id_admin;
    }

    // Fungsi untuk generate username dan password untuk pasien
    public function generate_username_pasien($id_pasien) {
        $year_month = date('Ym');
        return $year_month . '-' . $id_pasien;
    }
}