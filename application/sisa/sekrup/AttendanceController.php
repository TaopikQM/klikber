<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AttendanceController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('absen/AttendanceModel');
        
    } 

    public function index(){	
		$this->konten();
	}
	function konten($value=''){
		$data['konten']=$value;
		$this->load->view('absen/face-register',$data);
    
	}

    public function checkDescriptor(){
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $descriptor = isset($data['descriptor']) ? $data['descriptor'] : [];
        
        $results = $this->AttendanceModel->checkDescriptor();
            $descriptorExists = false;
            $matchedName = '';

            foreach ($results as $user) {
                $storedDescriptor = explode(',', $user['descriptor']);
                $distance = $this->euclideanDistance($descriptor, $storedDescriptor);

                if ($distance < 0.5) {
                    $descriptorExists = true;
                    $matchedName = $user['name'];
                }
            }

            echo json_encode(array('exists' => $descriptorExists, 'name' => $matchedName));

    }

   
    public function checkUser() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        $name = isset($data['name']) ? trim($data['name']) : ''; // Ambil nama dari request dan trim
        if (!$name) {
            return $this->output->set_status_header(400)->set_output(json_encode(array('exists' => false, 'message' => 'Nama tidak boleh kosong.')));
        }
        $user = $this->AttendanceModel->checkUser($name);
    
        if ($user) { 
            return $this->output->set_status_header(200)->set_output(json_encode(array('exists' => true, 'nim' => $user['nim'], 'name' => $user['name'])));
        } else {
            return $this->output->set_status_header(400)->set_output(json_encode(array('exists' => false, 'message' => 'Pengguna tidak ditemukan.')));
        }
    }
    
    
    public function register() {
        // Ambil data JSON dari input
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $newId = $this->AttendanceModel->generate_base64_id();

        // Ambil dan trim data
        $id=$newId;
        $nim = isset($data['nim']) ? trim($data['nim']) : '';
        $name = isset($data['name']) ? trim($data['name']) : '';
        $descriptor = isset($data['descriptor']) ? $data['descriptor'] : [];

        // Cek apakah descriptor adalah array dan gabungkan menjadi string
        if (is_array($descriptor)) {
            $descriptor = implode(',', $descriptor);
        }

        // Debug log untuk memastikan data yang diterima
        log_message('debug', 'Received data: ' . json_encode($data));

        // Panggil model untuk melakukan pendaftaran
        $result = $this->AttendanceModel->register($id, $nim, $name, $descriptor);

        if ($result) {
            return $this->output->set_status_header(200)->set_output(json_encode(array('success' => true)));
        } else {
            return $this->output->set_status_header(400)->set_output(json_encode(array('success' => false, 'message' => 'Data tidak boleh kosong.')));
        }
    }
 

    public function checkAttendance() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        $name = isset($data['name']) ? trim($data['name']) : '';
        $date = isset($data['date']) ? trim($data['date']) : '';//date('Y-m-d'); // Atur tanggal saat ini
        $attendance = $this->AttendanceModel->checkAttendance($name, $date);
            echo json_encode(array('exists' => $attendance ? true : false));
    }


    public function saveAttendance() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        $newId = $this->AttendanceModel->generate_base64_id();
        $id = $newId;
        // $nim = isset($data['nim']) ? trim($data['nim']) : '';
        $name = isset($data['name']) ? trim($data['name']) : '';
        // $descriptor = isset($data['descriptor']) ? $data['descriptor'] : [];
        $date = isset($data['date']) ? trim($data['date']) : '';//date('Y-m-d'); // Atur tanggal saat ini
        $ip = isset($data['ip']) ? trim($data['ip']) : '';
        $kegiatan = isset($data['kegiatan']) ? trim($data['kegiatan']) : '';
        $latitude = isset($data['latitude']) ? trim($data['latitude']) : '';
        $longitude = isset($data['longitude']) ? trim($data['longitude']) : '';
        $timein = isset($data['timein']) ? trim($data['timein']) : '';
        $timeot = isset($data['timeot']) ? trim($data['timeot']) : '';

        $data = array(
            'id' => $newId,//$this->input->post('id'),
            'date' => $date,
            'ip' => $ip,//$this->input->post('ip'),
            'kegiatan' => $kegiatan,//$this->input->post('kegiatan'),
            'latitude' => $latitude,//$this->input->post('latitude'),
            'longitude' => $longitude,//$this->input->post('longitude'),
            'name' => $name,
            'timein' => $timein,//$this->input->post('timein'),
            'timeot' => $timeot,//$this->input->post('timeot')
        );
        $this->AttendanceModel->saveAttendance($data);
        echo json_encode(array('success' => true));
    }

    public function updateAttendance(){
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $id = $data['id'];
        $timeot = isset($data['timeot']) ? trim($data['timeot']) : '';
        $kegiatan = isset($data['kegiatan']) ? trim($data['kegiatan']) : '';
        // $this->input->post('id');
            $data = array(
                'timeot' => $timeot,//$this->input->post('timeot'),
                'kegiatan' => $kegiatan,//$this->input->post('kegiatan')
            );
            $this->AttendanceModel->updateAttendance($id, $data);
            echo json_encode(array('success' => true));
    }

    public function checkAttendanceP(){
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $name = isset($data['name']) ? trim($data['name']) : '';
        $date = isset($data['date']) ? trim($data['date']) : '';//date('Y-m-d'); // Atur tanggal saat ini
        
        $result = $this->AttendanceModel->checkAttendanceP($name, $date);
            if ($result) {
                $response = array(
                    'exists' => true,
                    'kegiatan' => $result['kegiatan'], // Pastikan hasil dari model memiliki key 'kegiatan'
                    'timeot' => $result['timeot'] // Pastikan hasil dari model memiliki key 'timeot'
                );
                echo json_encode($result);
            } else {
                echo json_encode(array('exists' => false, 'error' => 'No attendance found'), 404);
        //     
                // echo json_encode(array('error' => 'No attendance found'), 500);
            }
    }

    public function saveAttendanceUP(){
       $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        $newId = $this->AttendanceModel->generate_base64_id();
        $id = $newId;
        // $nim = isset($data['nim']) ? trim($data['nim']) : '';
        $name = isset($data['name']) ? trim($data['name']) : '';
        // $descriptor = isset($data['descriptor']) ? $data['descriptor'] : [];
        $date = isset($data['date']) ? trim($data['date']) : '';//date('Y-m-d'); // Atur tanggal saat ini
        $ip = isset($data['ip']) ? trim($data['ip']) : '';
        $kegiatan = isset($data['kegiatan']) ? trim($data['kegiatan']) : '';
        $latitude = isset($data['latitude']) ? trim($data['latitude']) : '';
        $longitude = isset($data['longitude']) ? trim($data['longitude']) : '';
        $timein = isset($data['timein']) ? trim($data['timein']) : '';
        $timeot = isset($data['timeot']) ? trim($data['timeot']) : '';

        $data = array(
            'id' => $newId,//$this->input->post('id'),
            'date' => $date,
            'ip' => $ip,//$this->input->post('ip'),
            'kegiatan' => $kegiatan,//$this->input->post('kegiatan'),
            'latitude' => $latitude,//$this->input->post('latitude'),
            'longitude' => $longitude,//$this->input->post('longitude'),
            'name' => $name,
            'timein' => $timein,//$this->input->post('timein'),
            'timeot' => $timeot,//$this->input->post('timeot')
        );
        if ($this->AttendanceModel->saveAttendanceUP($data)) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Data gagal disimpan.'));
        }
    
    }
    // Fungsi Euclidean Distance
    private function euclideanDistance($descriptor1, $descriptor2) {
        $sum = 0;
        $count = min(count($descriptor1), count($descriptor2)); // Ensure we do not exceed bounds
        for ($i = 0; $i < $count; $i++) {
            $diff = $descriptor1[$i] - $descriptor2[$i];
            $sum += $diff * $diff;
        }
        return sqrt($sum);
    }

    //tabel baru
    public function getAllData() {
        // Mendapatkan semua data dari register dan attendance
        $data = $this->AttendanceModel->getAllData();
        $response = [
            'success' => true,
            // 'register' => $data['register'],
            'attendance' => $data['t_attendances']
        ];

        // Mengirimkan response dalam format JSON
        echo json_encode($response);
    }

    public function updateData() {
        // Mengedit data berdasarkan ID
        $id = $this->input->post('id');
        $updatedData = $this->input->post('updatedData');
        
        if ($this->AttendanceModel->updateData($id, $updatedData)) {
            $response = ['success' => true, 'message' => 'Data berhasil diperbarui di tabel attendance.'];
        } else {
            $response = ['success' => false, 'message' => 'Data tidak ditemukan di tabel attendance.'];
        }

        // Mengirimkan response dalam format JSON
        echo json_encode($response);
    }

    public function deleteData() {
        // Menghapus data berdasarkan ID dan tabel
        $id = $this->input->post('id');
        $table = $this->input->post('table');

        if ($this->AttendanceModel->deleteData($id, $table)) {
            $response = ['success' => true, 'message' => "Data dari tabel $table berhasil dihapus."];
        } else {
            $response = ['success' => false, 'message' => "Data tidak ditemukan di tabel $table."];
        }

        // Mengirimkan response dalam format JSON
        echo json_encode($response);
    }

    
    public function deleteItems() {
        // Mengambil array ID dari request POST
        $ids = $this->input->post('ids');
    
        // Pastikan $ids adalah array dan panggil fungsi deleteItems di model
        if (is_array($ids) && $this->AttendanceModel->deleteItems($ids)) {
            // Jika berhasil
            $response = ['success' => true, 'message' => 'Items deleted successfully.'];
        } else {
            // Jika gagal
            $response = ['success' => false, 'message' => 'Error deleting items.'];
        }
    
        // Mengirimkan response dalam format JSON
        echo json_encode($response);
    }
    
    




}





