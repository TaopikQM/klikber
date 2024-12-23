<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Poliklinik extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('MDokter');
        $this->dbfaces = $this->load->database('absenm', TRUE);
    
    }

    public function index() {
        
        $this->load->view('poliklinik/page/in-dokter',TRUE);
        
    }
    public function tambah() {
        
        $this->load->view('poliklinik/page/in-dokter',TRUE);
        
    } 

    public function add_dokter()
    {
        // Ambil data dari form
        $nama_dokter = $this->input->post('nama');
        $alamat_dokter = $this->input->post('alamat');
        $no_hp_dokter = $this->input->post('no_hp');
        $id_poli = $this->input->post('id_poli');

        // Menyimpan data dokter ke tabel dokter
        $data_dokter = [
            'nama' => $nama_dokter,
            'alamat' => $alamat_dokter,
            'no_hp' => $no_hp_dokter,
            'id_poli' => $id_poli
        ];

        // Menyimpan data dokter dan mendapatkan ID dokter yang baru
        $id_dokter = $this->Dokter_model->add_dokter($data_dokter);

        // Ambil tahun sekarang
        $tahun = date('Y');

        // Ambil awalan nama dokter
        $nama_parts = explode(' ', $nama_dokter);
        $awalan_nama = strtoupper(substr($nama_parts[0], 0, 1)); 

        // Buat username sesuai format
        $username = 'D' . $tahun . $id_dokter . $awalan_nama;

        // Password sama dengan username
        $password = $username;

        // Menyimpan data ke tabel users
        $data_user = [
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'dokter', 
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Menyimpan data user
        $this->Dokter_model->add_user($data_user);

        // Berikan pesan sukses atau redirect
        $this->session->set_flashdata('success', 'Dokter berhasil ditambahkan');
        redirect('dokter/list'); // Ganti dengan URL yang sesuai
    }


    
    
}
