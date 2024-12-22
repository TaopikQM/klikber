<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RiwayatServis extends CI_Controller {

    public function __construct(){
        parent::__construct();

        // Cek sesi
        if (!$this->session->userdata('idus')) {
            redirect('landing/menu');
        }
        date_default_timezone_set("Asia/Jakarta");

        // Load model dan library
        $this->load->model('pinjam/Mriwayatservis');
        $this->load->model('pinjam/Mmobil'); // Load the Mmobil model
        $this->load->library('form_validation');
        $this->load->library('encryption');

        $this->encryption->initialize([
            'cipher' => 'aes-128',
            'mode' => 'ctr',
            'key' => 'HJKHASJKD^**&&*(NJSHAHIDAsdfsa'
        ]);
    }

    public function konten($value=''){
        $data['konten'] = $value;
        $this->load->view('pinjam/home', $data);
    }

    public function index(){
        $this->load->helper('pinjam');

        // Ambil data mobil dan riwayat servis
        $data['datamobil'] = $this->Mmobil->get_all_mobil();
        //$data['riwayat_servis'] = $this->Mriwayatservis->get_servis_history_with_mobil();
        $data['riwayat_servis'] = $this->Mriwayatservis->getAllRiwayat();

        // Tampilkan view
        $content = $this->load->view('pinjam/page/v_data_riwayat_servis', $data, TRUE);
        $this->konten($content);      
    }

    // public function show($id = NULL) {
    // 	$data['idmobil']=$id;
    //     if ($id === NULL) {
    //         // Tangani kasus tanpa ID
    //         $data['riwayat_servis'] = $this->Mriwayatservis->get_data_riwayat_servis(); // Ambil semua data jika ID tidak ada
    //     } else {
    //         // Ambil data berdasarkan ID
    //         $data['riwayat_servis'] = $this->Mriwayatservis->getAllRiwayat();
    //     }
    //     $content = $this->load->view('pinjam/page/v_show_data_riwayat_servis', $data, TRUE);
    //     $this->konten($content);
    //   /*  echo "<pre>";
    //     print_r($data);
    //     echo "</pre>";*/
    // }
    
    public function show($idmobil = NULL) {
        if ($idmobil === NULL || $idmobil == 0) {
            // Jika ID mobil tidak valid, arahkan kembali atau tampilkan pesan
            $data['riwayat_servis'] = [];
            $data['mobil'] = NULL;
            $data['idmobil'] = NULL;
        } else {
            // Ambil data riwayat servis berdasarkan ID mobil
            $data['riwayat_servis'] = $this->Mriwayatservis->getAllRiwayatByMobilId($idmobil);
            $data['mobil'] = $this->Mriwayatservis->getMobilById($idmobil);
            $data['idmobil'] = $idmobil;
        }
    
        $content = $this->load->view('pinjam/page/v_show_data_riwayat_servis', $data, TRUE);
        $this->konten($content);
    }
    
    

    public function tambah($id = NULL) {
        if ($id !== NULL) {
            // Jika ID diberikan, ambil data terkait ID
            $data['datamobil'] = $this->Mriwayatservis->get_data_mobil_id($id)->result();
            $data['allmobil']= $this->Mriwayatservis->get_data_mobil()->result();
            $data['driver']= $this->Mriwayatservis->get_all_drivers()->result();
            if (empty($data['datamobil'])) {
                // Jika tidak ada data untuk ID tersebut, redirect atau tampilkan pesan error
                $this->session->set_flashdata('notif', ['tipe' => '3', 'isi' => 'Data tidak ditemukan.']);
                redirect('riwayatservis/show' . $id);
            }
        } else {
            // Jika tidak ada ID, set data kosong atau inisialisasi data default
            $data['allmobil']= $this->Mriwayatservis->get_data_mobil()->result();
            $data['driver']= $this->Mriwayatservis->get_all_drivers()->result();
            $data['datamobil'] = NULL;
        }
        
        // Tampilkan tampilan dengan data yang diambil
        $content = $this->load->view('pinjam/page/in-riwayat_servis', $data, TRUE);
        $this->konten($content);
    }    
    
    public function save_riwayat() {
        // Validasi form
        $this->form_validation->set_rules('kilometer', 'Kilometer', 'required');
        $this->form_validation->set_rules('nm_pemegang', 'Nama Pemegang', 'required');
        $this->form_validation->set_rules('blnket', 'Tanggal Servis', 'required');
        $this->form_validation->set_rules('ket', 'Keterangan Servis', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, tampilkan form dengan error
            $this->session->set_flashdata('notif', ['tipe' => '3', 'isi' => validation_errors()]);
            redirect('riwayatservis/tambah'); // Mengarahkan kembali ke form tambah
        } else {
            // Ambil data dari form
            $data = [
                'idmobil' => $this->input->post('idmobil'),
                'kilometer' => $this->input->post('kilometer'),
                'nm_pemegang' => $this->input->post('nm_pemegang'),
                'blnket' => $this->input->post('blnket'),
                'ket' => $this->input->post('ket'),
            ];

            $keterangan_list = explode("\n", trim($data['ket']));
    
            // Ambil ID dari form
            $id = $this->input->post('id');
    
            if (empty($id)) {
                // ID kosong, berarti tambah data baru
                $success = $this->Mriwayatservis->save_riwayat_servis($data);
                if ($success) {
                    $notif = 'Riwayat servis berhasil ditambahkan.';
                } else {
                    $notif = 'Gagal menambahkan riwayat servis.';
                }
            } else {
                // ID ada, berarti update data yang ada
                $success = $this->Mriwayatservis->update($id, $data);
                $notif = $success ? 'Riwayat servis berhasil diperbarui.' : 'Gagal memperbarui riwayat servis.';
            }
    
            // Set flashdata untuk notifikasi
            $this->session->set_flashdata('notif', ['tipe' => $success ? '1' : '3', 'isi' => $notif]);
    
            // Redirect ke halaman show dengan ID mobil yang sesuai
            redirect('riwayatservis/show/' . $data['idmobil']);
        }
    }

    // public function delete($id) {
    //     if ($this->Mriwayatservis->delete($id)) {
    //         $this->session->set_flashdata('notif', ['tipe' => 1, 'isi' => 'Data berhasil dihapus.']);
    //     } else {
    //         $this->session->set_flashdata('notif', ['tipe' => 3, 'isi' => 'Gagal menghapus data.']);
    //     }
    //     redirect('riwayatservis/show/' .$data['idmobil']);
    // }
    
    public function delete($id) {
        // Mengambil idmobil sebelum data dihapus
        $riwayatServis = $this->Mriwayatservis->getById($id);
        $idmobil = $riwayatServis['idmobil'];
    
        if ($this->Mriwayatservis->delete($id)) {
            $this->session->set_flashdata('notif', ['tipe' => 1, 'isi' => 'Data berhasil dihapus.']);
        } else {
            $this->session->set_flashdata('notif', ['tipe' => 3, 'isi' => 'Gagal menghapus data.']);
        }
    
        // Redirect ke halaman show berdasarkan idmobil
        redirect('riwayatservis/show/' . $idmobil);
    }
    
    public function edit($id) {
        if ($id === NULL) {
            show_404(); // Tampilkan halaman 404 jika ID tidak ada
            return;
        }
    
        // Ambil data mobil dan riwayat servis berdasarkan ID mobil
        $data['datamobil'] = $this->Mriwayatservis->get_data_mobil_id($id)->result();
        $data['riwayat_servis'] = $this->Mriwayatservis->getRiwayatById($id);
        $data['driver'] = $this->Mriwayatservis->get_all_drivers()->result();
        $data['id'] = $id; // Kirim id ke view
    
        // Tampilkan tampilan dengan data yang diambil
        $content = $this->load->view('pinjam/page/v-edit_data_riwayat_servis', $data, TRUE);
        $this->konten($content);
    }

    public function save_e_riwayat() {

        $this->form_validation->set_rules('kilometer', 'Kilometer', 'required');
        $this->form_validation->set_rules('nm_pemegang', 'Nama Pemegang', 'required');
        $this->form_validation->set_rules('blnket', 'Tanggal Servis', 'required');
        $this->form_validation->set_rules('ket', 'Keterangan Servis', 'required');
        $this->form_validation->set_rules('idmobil', 'ID Mobil', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('notif', ['tipe' => '3', 'isi' => validation_errors()]);
            redirect('riwayatservis/edit/' . $this->input->post('id'));
        } else {
            $data = [
                'kilometer' => $this->input->post('kilometer'),
                'nm_pemegang' => $this->input->post('nm_pemegang'),
                'blnket' => $this->input->post('blnket'),
                'ket' => $this->input->post('ket'),
            ];
    
            $id = $this->input->post('id'); // Pastikan mengambil id riwayat servis, bukan id mobil
            $idmobil = $this->input->post('idmobil');
    
            // Gunakan id riwayat servis untuk update data
            $success = $this->Mriwayatservis->s_e_data_riwayat($data, $id);
            $notif = $success ? 'Riwayat servis berhasil diperbarui.' : 'Gagal memperbarui riwayat servis.';
    
            $this->session->set_flashdata('notif', ['tipe' => $success ? '1' : '3', 'isi' => $notif]);
            redirect('riwayatservis/show/' . $idmobil);
        }
    }
    
    

    function cetak_surat_pengantar($id) {
        // Load helper, library, dan model yang diperlukan
        $this->load->helper('pinjam');
        $this->load->library('fpdf');
        $this->load->model('Mriwayatservis');
    
        // Mengambil data utama berdasarkan ID
        // $data['dataitem'] = $this->Mriwayatservis->get_data_sp_by_id($id)->row_array();
        $data['riwayat_servis'] = $this->Mriwayatservis->getSPById($id);

        // Pass data to view
        $this->load->view('pinjam/page/v_pdf_pengantar', $data);
    }
    
}
?>
