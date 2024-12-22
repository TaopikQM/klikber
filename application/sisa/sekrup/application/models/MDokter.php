<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MDokter extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->db = $this->load->database('absenm', TRUE);
    
    }

    // Fungsi untuk menambah data dokter
    public function add_dokter($data_dokter) {
        // Menyimpan data dokter ke dalam tabel dokter
        $this->db->insert('dokter', $data_dokter);
        return $this->db->insert_id(); // Mengembalikan ID dokter yang baru saja disimpan
    }

    // Fungsi untuk menambah data user (dokter)
    public function add_user($data_user) {
        // Menyimpan data user ke dalam tabel users
        return $this->db->insert('users', $data_user);
    }

    // Fungsi untuk mendapatkan data poli (misalnya untuk dropdown)
    public function get_poli() {
        return $this->db->get('poli')->result();
    }

    function get_data_dokter(){
		$this->db->order_by('id', 'desc');
		return $this->db->get('mobil');
	}
}


