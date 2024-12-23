<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RiwayatServisAlat extends CI_Controller {

    public function __construct(){
        parent::__construct();

        // Cek sesi
        if (!$this->session->userdata('idus')) {
            redirect('landing/menu');
        }
        date_default_timezone_set("Asia/Jakarta");

        // Load model dan library
        $this->load->model('pinjam/Mriwayatservisalat');
        $this->load->model('pinjam/Malat');
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

    public function index() {
        $this->load->helper('pinjam');
    
        // Ambil data mobil dan riwayat servis
        $data['dataalat'] = $this->Malat->get_all_alat();
        $data['rs_alat'] = $this->Mriwayatservisalat->get_servis_history_with_alat();
    
        // Tampilkan view
        $content = $this->load->view('pinjam/page/v_data_rs_alat', $data, TRUE);
        $this->konten($content);      
    }

    public function show($id_alat = NULL) {
        if ($id_alat === NULL || $id_alat == 0) {
            // Jika ID mobil tidak valid, arahkan kembali atau tampilkan pesan
            $data['rs_alat'] = [];
            $data['alat'] = NULL;
            $data['id_alat'] = NULL;
        } else {
            // Ambil data riwayat servis berdasarkan ID mobil
            $data['rs_alat'] = $this->Mriwayatservisalat->getAllRiwayatByAlatId($id_alat);
            $data['alat'] = $this->Mriwayatservisalat->getAlatById($id_alat);
            $data['id_alat'] = $id_alat;
        }
    
        $content = $this->load->view('pinjam/page/v_show_alat', $data, TRUE);
        $this->konten($content);
    }

    public function tambah($id = NULL) {
        if ($id !== NULL) {
            // Jika ID diberikan, ambil data terkait ID
            $data['dataalat'] = $this->Mriwayatservisalat->get_data_alat_id($id)->result();
            $data['allalat']= $this->Mriwayatservisalat->get_data_alat()->result();
            if (empty($data['dataalat'])) {
                // Jika tidak ada data untuk ID tersebut, redirect atau tampilkan pesan error
                $this->session->set_flashdata('notif', ['tipe' => '3', 'isi' => 'Data tidak ditemukan.']);
                redirect('riwayatservisalat/show' . $id);
            }
        } else {
            // Jika tidak ada ID, set data kosong atau inisialisasi data default
            $data['allalat']= $this->Mriwayatservisalat->get_data_alat()->result();
            $data['dataalat'] = NULL;
        }
        
        // Tampilkan tampilan dengan data yang diambil
        $content = $this->load->view('pinjam/page/in-rs_alat', $data, TRUE);
        $this->konten($content);
    }   
    
    public function save_rs_alat() {
        // Validasi form
        $this->form_validation->set_rules('tgl_servis', 'Tanggal Servis', 'required');
        $this->form_validation->set_rules('ket', 'Keterangan Servis', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, tampilkan form dengan error
            $this->session->set_flashdata('notif', ['tipe' => '3', 'isi' => validation_errors()]);
            redirect('riwayatservisalat/tambah'); // Mengarahkan kembali ke form tambah
        } else {
            // Ambil data dari form
            $data = [
                'id_alat' => $this->input->post('id_alat'),
                'tgl_servis' => $this->input->post('tgl_servis'),
                'ket' => $this->input->post('ket'),
            ];
    
            // Ambil ID dari form
            $id = $this->input->post('id');
    
            if (empty($id)) {
                // ID kosong, berarti tambah data baru
                $success = $this->Mriwayatservisalat->save_rs_alat($data);
                if ($success) {
                    $notif = 'Riwayat servis berhasil ditambahkan.';
                } else {
                    $notif = 'Gagal menambahkan riwayat servis.';
                }
            } else {
                // ID ada, berarti update data yang ada
                $success = $this->Mriwayatservisalat->update($id, $data);
                $notif = $success ? 'Riwayat servis berhasil diperbarui.' : 'Gagal memperbarui riwayat servis.';
            }
    
            // Set flashdata untuk notifikasi
            $this->session->set_flashdata('notif', ['tipe' => $success ? '1' : '3', 'isi' => $notif]);
    
            // Redirect ke halaman show dengan ID mobil yang sesuai
            redirect('riwayatservisalat/show/' . $data['id_alat']);
        }
    }
    
    public function delete($id) {
        
        $riwayatServisAlat = $this->Mriwayatservisalat->getById($id);
        $id_alat = $riwayatServisAlat['id_alat'];
    
        if ($this->Mriwayatservisalat->delete($id)) {
            $this->session->set_flashdata('notif', ['tipe' => 1, 'isi' => 'Data berhasil dihapus.']);
        } else {
            $this->session->set_flashdata('notif', ['tipe' => 3, 'isi' => 'Gagal menghapus data.']);
        }
    
        // Redirect ke halaman show berdasarkan id
        redirect('riwayatservisalat/show/' . $id_alat);
    }

    public function edit($id) {
        if ($id === NULL) {
            show_404(); // Tampilkan halaman 404 jika ID tidak ada
            return;
        }

        // $data['rs_alat'] = $this->Mriwayatservisalat->getAllRiwayatByAlatId($id);
        // $data['alat'] = $this->Mriwayatservisalat->getAlatById($id);
        // $data['id_alat'] = $id;
    
        // Ambil data mobil dan riwayat servis berdasarkan ID mobil
        $data['dataalat'] = $this->Mriwayatservisalat->get_data_alat_id($id)->result(); //tabel alat
        $data['allalat'] = $this->Mriwayatservisalat->get_data_alat()->result();
        $data['rs_alat'] = $this->Mriwayatservisalat->getRiwayatById($id); //riwayat
    
        // Tampilkan tampilan dengan data yang diambil
        $content = $this->load->view('pinjam/page/v-edit_rs_alat', $data, TRUE);
        $this->konten($content);
    }

    public function save_e_rs_alat() {
        // Set rules
        $this->form_validation->set_rules('tgl_servis', 'Tanggal Servis', 'required');
        $this->form_validation->set_rules('ket', 'Keterangan Servis', 'required');
        $this->form_validation->set_rules('id_alat', 'ID Alat', 'required'); // Menambahkan aturan untuk ID Mobil
    
        // Run validation
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('notif', ['tipe' => '3', 'isi' => validation_errors()]);
            redirect('riwayatservisalat/edit/' . $this->input->post('id_alat'));
        } else {
            // Ambil data
            $data = [
                'tgl_servis' => $this->input->post('tgl_servis'),
                'ket' => $this->input->post('ket'),
            ];
    
            $id_alat = $this->input->post('id_alat');
            $id = $this->input->post('id'); // Ambil ID mobil dari input form
    
            $success = $this->Mriwayatservisalat->s_e_data_alat($data, $id); // Menyertakan ID mobil dalam penyimpanan
            $notif = $success ? 'Riwayat servis berhasil diperbarui.' : 'Gagal memperbarui riwayat servis.';
    
            $this->session->set_flashdata('notif', ['tipe' => $success ? '1' : '3', 'isi' => $notif]);
            redirect('riwayatservisalat/show/' . $id_alat); // Mengalihkan ke halaman show dengan ID mobil
        }
    }
    
}
?>
