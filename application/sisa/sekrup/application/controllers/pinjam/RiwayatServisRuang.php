<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RiwayatServisRuang extends CI_Controller {

    public function __construct(){
        parent::__construct();

        // Cek sesi
        if (!$this->session->userdata('idus')) {
            redirect('landing/menu');
        }
        date_default_timezone_set("Asia/Jakarta");

        // Load model dan library
        $this->load->model('pinjam/Mriwayatservisruang');
        $this->load->model('pinjam/Mruang'); // Load the Mmobil model
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
        $data['dataruang'] = $this->Mruang->get_all_ruangan();
        $data['rs_ruang'] = $this->Mriwayatservisruang->get_servis_history_with_ruang();
    
        // Tampilkan view
        $content = $this->load->view('pinjam/page/v_data_riwayat_servis_ruang', $data, TRUE);
        $this->konten($content);      
    }

    public function show($id_ruang = NULL) {
        if ($id_ruang === NULL || $id_ruang == 0) {
            // Jika ID mobil tidak valid, arahkan kembali atau tampilkan pesan
            $data['rs_ruang'] = [];
            $data['ruangan'] = NULL;
            $data['id_ruang'] = NULL;
        } else {
            // Ambil data riwayat servis berdasarkan ID mobil
            $data['rs_ruang'] = $this->Mriwayatservisruang->getAllRiwayatByRuangId($id_ruang);
            $data['ruangan'] = $this->Mriwayatservisruang->getRuangById($id_ruang);
            $data['id_ruang'] = $id_ruang;
        }
    
        $content = $this->load->view('pinjam/page/v_show_data_riwayat_servis_ruang', $data, TRUE);
        $this->konten($content);
    }
    
    public function tambah($id = NULL) {
        if ($id !== NULL) {
            // Jika ID diberikan, ambil data terkait ID
            $data['dataruang'] = $this->Mriwayatservisruang->get_data_ruang_id($id)->result();
            $data['allruang']= $this->Mriwayatservisruang->get_data_ruang()->result();
            if (empty($data['dataruang'])) {
                // Jika tidak ada data untuk ID tersebut, redirect atau tampilkan pesan error
                $this->session->set_flashdata('notif', ['tipe' => '3', 'isi' => 'Data tidak ditemukan.']);
                redirect('riwayatservisruang/show' . $id);
            }
        } else {
            // Jika tidak ada ID, set data kosong atau inisialisasi data default
            $data['allruang']= $this->Mriwayatservisruang->get_data_ruang()->result();
            $data['dataruang'] = NULL;
        }
        
        // Tampilkan tampilan dengan data yang diambil
        $content = $this->load->view('pinjam/page/in-rs_ruang', $data, TRUE);
        $this->konten($content);
    } 

    public function save_riwayat() {
        // Validasi form
        $this->form_validation->set_rules('tgl_servis', 'Tanggal Servis', 'required');
        $this->form_validation->set_rules('ket', 'Keterangan Servis', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, tampilkan form dengan error
            $this->session->set_flashdata('notif', ['tipe' => '3', 'isi' => validation_errors()]);
            redirect('riwayatservisruang/tambah'); // Mengarahkan kembali ke form tambah
        } else {
            // Ambil data dari form
            $data = [
                'id_ruang' => $this->input->post('id_ruang'),
                'tgl_servis' => $this->input->post('tgl_servis'),
                'ket' => $this->input->post('ket'),
            ];
    
            // Ambil ID dari form
            $id = $this->input->post('id');
    
            if (empty($id)) {
                // ID kosong, berarti tambah data baru
                $success = $this->Mriwayatservisruang->save_rs_ruang($data);
                if ($success) {
                    $notif = 'Riwayat servis berhasil ditambahkan.';
                } else {
                    $notif = 'Gagal menambahkan riwayat servis.';
                }
            } else {
                // ID ada, berarti update data yang ada
                $success = $this->Mriwayatservisruang->update($id, $data);
                $notif = $success ? 'Riwayat servis berhasil diperbarui.' : 'Gagal memperbarui riwayat servis.';
            }
    
            // Set flashdata untuk notifikasi
            $this->session->set_flashdata('notif', ['tipe' => $success ? '1' : '3', 'isi' => $notif]);
    
            // Redirect ke halaman show dengan ID mobil yang sesuai
            redirect('riwayatservisruang/show/' . $data['id_ruang']);
        }
    }

    // public function save_riwayat() {
    //     // Validasi form
    //     $this->form_validation->set_rules('tgl_servis', 'Tanggal Servis', 'required');
    //     $this->form_validation->set_rules('ket', 'Keterangan Servis', 'required');
    
    //     if ($this->form_validation->run() == FALSE) {
    //         // Jika validasi gagal, tampilkan form dengan error
    //         $this->session->set_flashdata('notif', ['tipe' => '3', 'isi' => validation_errors()]);
    //         redirect('riwayatservisruang/tambah'); // Mengarahkan kembali ke form tambah
    //     } else {
    //         // Ambil data dari form
    //         $data = [
    //             'id_ruang' => $this->input->post('id_ruang'),
    //             'tgl_servis' => $this->input->post('tgl_servis'),
    //             'ket' => $this->input->post('ket'),
    //         ];
    
    //         // Ambil ID dari form
    //         $id = $this->input->post('id');
    
    //         if (empty($id)) {
    //             // ID kosong, berarti tambah data baru
    //             $success = $this->Mriwayatservisruang->save_riwayat_servis_ruang($data);
    //             if ($success) {
    //                 $notif = 'Riwayat servis berhasil ditambahkan.';
    //             } else {
    //                 $notif = 'Gagal menambahkan riwayat servis.';
    //             }
    //         } else {
    //             // ID ada, berarti update data yang ada
    //             $success = $this->Mriwayatservisruang->update($id, $data);
    //             $notif = $success ? 'Riwayat servis berhasil diperbarui.' : 'Gagal memperbarui riwayat servis.';
    //         }
    
    //         // Set flashdata untuk notifikasi
    //         $this->session->set_flashdata('notif', ['tipe' => $success ? '1' : '3', 'isi' => $notif]);
    
    //         // Redirect ke halaman show dengan ID mobil yang sesuai
    //         redirect('riwayatservisruang/show/' . $data['idmobil']);
    //     }
    // }


    public function delete($id) {
        // Mengambil idmobil sebelum data dihapus
        $riwayatServisruang = $this->Mriwayatservisruang->getById($id);
        $id_ruang = $riwayatServisruang['id_ruang'];
    
        if ($this->Mriwayatservisruang->delete($id)) {
            $this->session->set_flashdata('notif', ['tipe' => 1, 'isi' => 'Data berhasil dihapus.']);
        } else {
            $this->session->set_flashdata('notif', ['tipe' => 3, 'isi' => 'Gagal menghapus data.']);
        }
    
        // Redirect ke halaman show berdasarkan idmobil
        redirect('riwayatservisruang/show/' . $id_ruang);
    }
    
    // public function edit($id) {
    //     if ($id === NULL) {
    //         show_404(); // Tampilkan halaman 404 jika ID tidak ada
    //         return;
    //     }
    
    //     // Ambil data mobil dan riwayat servis berdasarkan ID mobil
    //     $data['dataruang'] = $this->Mriwayatservisruang->get_data_ruang_id($id)->result();
    //     $data['allruang'] = $this->Mriwayatservisruang->get_data_ruang()->result();
    //     $data['rs_ruang'] = $this->Mriwayatservisruang->getRuangById($id);
    
    //     // Tampilkan tampilan dengan data yang diambil
    //     $content = $this->load->view('pinjam/page/v-edit_riwayat_servis_ruang', $data, TRUE);
    //     $this->konten($content);
    // }

    public function edit($id) {
        if ($id === NULL) {
            show_404(); // Tampilkan halaman 404 jika ID tidak ada
            return;
        }
    
        // Ambil data mobil dan riwayat servis berdasarkan ID mobil
        $data['dataruang'] = $this->Mriwayatservisruang->get_data_ruang_id($id)->result();
        $data['rs_ruang'] = $this->Mriwayatservisruang->getRiwayatById($id);
        $data['riwayat_servis'] = $this->Mriwayatservisruang->get_riwayat_ruang_by_id($id); // Pastikan variabel ini didefinisikan
        $data['id'] = $id; // Kirim id ke view

        // Tampilkan tampilan dengan data yang diambil
        $content = $this->load->view('pinjam/page/v-edit_riwayat_servis_ruang', $data, TRUE);
        $this->konten($content);
    }
    
    public function save_e_riwayat_ruang() {
        // Set rules
        $this->form_validation->set_rules('tgl_servis', 'Tanggal Servis', 'required');
        $this->form_validation->set_rules('ket', 'Keterangan Servis', 'required');
        $this->form_validation->set_rules('id_ruang', 'ID Ruang', 'required'); // Menambahkan aturan untuk ID Mobil
    
        // Run validation
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('notif', ['tipe' => '3', 'isi' => validation_errors()]);
            redirect('riwayatservisruang/edit/' . $this->input->post('id_ruang'));
        } else {
           
            $id_ruang = $this->input->post('id_ruang'); // Ambil ID mobil dari input form
    
            // Ambil data
            $data = [
                'tgl_servis' => $this->input->post('tgl_servis'),
                'ket' => $this->input->post('ket'),
            ];
            $id = $this->input->post('id');
    
            $success = $this->Mriwayatservisruang->s_e_data_riwayat_ruang($data, $id); // Menyertakan ID mobil dalam penyimpanan
            $notif = $success ? 'Riwayat servis berhasil diperbarui.' : 'Gagal memperbarui riwayat servis.';
    
            $this->session->set_flashdata('notif', ['tipe' => $success ? '1' : '3', 'isi' => $notif]);
            redirect('riwayatservisruang/show/' . $id_ruang); // Mengalihkan ke halaman show dengan ID mobil
        }
    }

    

    
    
}
?>
