<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('absen/UserModel');
        $this->dbfaces = $this->load->database('absenm', TRUE);
    
    }

    public function index() {
        
        // $this->load->view('absen/face-register');
        $this->load->view('absen/userTabel');
        
        
        // $this->load->view('absen/VdataAttendances');
        
    } 

    public function getAll() {
        // Mendapatkan semua pengguna
        $data = $this->UserModel->get_users(); // Panggil fungsi dari model
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

     // Fungsi untuk mengedit nama dan NIM mahasiswa
     public function update($id) {
        // Mendapatkan data input dari permintaan
        $data = array(
            'mhs_nama' => $this->input->post('mhs_nama'),
            'mhs_nim' => $this->input->post('mhs_nim')
        );

        // Memanggil model untuk memperbarui data
        $result = $this->UserModel->update_user($id, $data);

        // Mengembalikan respons JSON
        if ($result) {
            $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'success', 'message' => 'Data berhasil diperbarui')));
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'error', 'message' => 'Gagal memperbarui data')));
        }
    }

    public function update_status() {
        // Ambil data JSON dari body request
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Pastikan userId dan status ada
        if (!isset($input['userId']) || !isset($input['status'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid input']);
            return; // Menghentikan eksekusi jika input tidak valid
        }
    
        $userId = $input['userId'];
        $newStatus = $input['status']; // Status baru ('Active' atau 'Inactive')
    
        // Konversi status ke integer
        $statusInt = ($newStatus === 'Active') ? 1 : 2; // 1 untuk Active, 2 untuk Inactive
    
        // Update status di database
        $this->dbfaces->where('mhs_id', $userId);
        $this->dbfaces->update('t_pendaftaran', ['pendaftaran_status' => $statusInt]);
    
        if ($this->dbfaces->affected_rows() > 0) {
            echo json_encode(['success' => true, 'newStatus' => $newStatus]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update status']);
        }
    }
    
    public function updateS() {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
    
        // Memeriksa apakah id dan status sudah dikirim
        if (!$id || !$status) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['error' => 'Missing required fields']));
        }
    
        // Mengonversi status dari string ke integer
        $statusInt = ($status === "Active") ? 1 : 2;
    
        // Memanggil fungsi model untuk memperbarui status
        if ($this->UserModel->updateS($id, $statusInt)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['message' => 'Status updated successfully']));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['error' => 'Failed to update status']));
        }
    }

    // public function updateUser() {
    //     $id = $this->input->post('id');
    //     $name = $this->input->post('name');
    //     $nim = $this->input->post('nim');
    
    //     if (!$id || !$name || !$nim) {
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode(['error' => 'Missing required fields']));
    //     }
    
    //     // Memanggil fungsi model untuk memperbarui pengguna
    //     if ($this->UserModel->updateUser($id, $name, $nim)) {
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_output(json_encode(['message' => 'User updated successfully']));
    //     } else {
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(500)
    //             ->set_output(json_encode(['error' => 'Failed to update user']));
    //     }
    // }
    
    public function updateUser() {
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $nim = $this->input->post('nim');

        // Validasi input
        if (!$id || !$name || !$nim) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['error' => 'Missing required fields']));
        }

        // Memanggil fungsi model untuk memperbarui pengguna
        if ($this->UserModel->updateUser($id, $name, $nim)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['message' => 'User updated successfully']));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['error' => 'Failed to update user']));
        }
    }

    
    public function delete() {
        $mhs_id = $this->input->post('mhs_id'); // Ambil mhs_id dari request
    
        if (!$mhs_id) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(['error' => 'Missing required fields']), 400);
        }
    
        // Panggil fungsi delete_user dari model
        if ($this->UserModel->delete_user($mhs_id)) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(['message' => 'User deleted successfully']));
        } else {
            return $this->output->set_content_type('application/json')->set_output(json_encode(['error' => 'Failed to delete user or user not found']), 500);
        }
    }
    
}