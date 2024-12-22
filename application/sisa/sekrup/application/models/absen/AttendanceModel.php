<?php
class AttendanceModel extends CI_Model {

    protected $dbfaces;

    public function __construct() {
        parent::__construct(); // Ensure parent constructor is called
        $this->dbfaces = $this->load->database('absenm', TRUE);
    }

    // Function to check descriptors in the register table
    public function checkDescriptor() {
        $query = $this->dbfaces->get('t_register');
        return $query->result_array(); // Return all data from the t_register table
    }

    // Function to check if the user is active in the users table
    // public function checkUser($name) {
    //     $this->dbfaces->where('name', $name);
    //     $this->dbfaces->where('status', 'active');
    //     $query = $this->dbfaces->get('users');
    //     // Debug log
    //     log_message('debug', 'Query executed: ' . $this->dbfaces->last_query());

    //     if ($query->num_rows() > 0) {
    //         return $query->row_array(); // Mengembalikan satu baris hasil
    //     } 



    //     return null;
    //     // return $this->dbfaces->get('users')->row_array();
    // }
    // public function checkUser($name) {
    //     $this->dbfaces->where('name', $name);
    //     $this->dbfaces->where('status', '1');
    //     $query = $this->dbfaces->get('users');
    
    //     if ($query->num_rows() > 0) {
    //         return $query->row_array(); // Mengembalikan satu baris hasil
    //     }
    
    //     return null;
    // }
    // public function checkUser($name) {
    //     // Pertama, cari mhs_id dari t_mahasiswa berdasarkan nama
    //     $this->dbfaces->select('mhs.mhs_id AS id');
    //     $this->dbfaces->from('t_mahasiswa mhs');
    //     $this->dbfaces->where('mhs.mhs_nama', $name);
    //     $queryMahasiswa = $this->dbfaces->get();
    
    //     // Jika mhs_id ditemukan
    //     if ($queryMahasiswa->num_rows() > 0) {
    //         $mahasiswa = $queryMahasiswa->row_array();
    //         $mhs_id = $mahasiswa['id'];
    
    //         // Selanjutnya, periksa pendaftaran_status di t_pendaftaran berdasarkan mhs_id
    //         $this->dbfaces->select('pendaftaran_status');
    //         $this->dbfaces->from('t_pendaftaran');
    //         $this->dbfaces->where('mhs_id', $mhs_id);
    //         $queryPendaftaran = $this->dbfaces->get();
    
    //         // Jika pendaftaran_status ditemukan
    //         if ($queryPendaftaran->num_rows() > 0) {
    //             $pendaftaran = $queryPendaftaran->row_array();
    
    //             // Cek apakah pendaftaran_status sama dengan 1
    //             if ((int)$pendaftaran['pendaftaran_status'] === 1) {
    //                 return [
    //                     'id' => $mhs_id,
    //                     'name' => $name,
    //                     'pendaftaran_status' => $pendaftaran['pendaftaran_status'],
    //                 ]; // Mengembalikan hasil jika aktif
    //             } else {
    //                 return null; // Kembali null jika tidak aktif
    //             }
    //         }
    //     }
    
    //     return null; // Jika mhs_id tidak ditemukan
    // }
    public function checkUser($name) {
        $name = trim($name); // Menghapus spasi di depan dan belakang nama
    
        // Pertama, cari mhs_id dan mhs_nim dari t_mahasiswa berdasarkan nama
        $this->dbfaces->select('mhs.mhs_id AS id, mhs.mhs_nim AS nim'); // Ambil mhs_id dan mhs_nim
        $this->dbfaces->from('t_mahasiswa mhs');
        $this->dbfaces->where('mhs.mhs_nama', $name);
        $queryMahasiswa = $this->dbfaces->get();
    
        // Jika mhs_id ditemukan
        if ($queryMahasiswa->num_rows() > 0) {
            $mahasiswa = $queryMahasiswa->row_array();
            $mhs_id = $mahasiswa['id'];
    
            // Selanjutnya, periksa pendaftaran_status di t_pendaftaran berdasarkan mhs_id
            $this->dbfaces->select('pendaftaran_status');
            $this->dbfaces->from('t_pendaftaran');
            $this->dbfaces->where('mhs_id', $mhs_id);
            $queryPendaftaran = $this->dbfaces->get();
    
            // Jika pendaftaran_status ditemukan
            if ($queryPendaftaran->num_rows() > 0) {
                $pendaftaran = $queryPendaftaran->row_array();
    
                // Cek apakah pendaftaran_status sama dengan 1
                if ((int)$pendaftaran['pendaftaran_status'] === 1) {
                    return [
                        'id' => $mhs_id, // ID mahasiswa
                        'name' => $name, // Nama mahasiswa
                        'nim' => $mahasiswa['nim'], // NIM mahasiswa
                        'pendaftaran_status' => $pendaftaran['pendaftaran_status'],
                    ]; // Mengembalikan hasil jika aktif
                }
            }
        }
    
        return null; // Jika mhs_id tidak ditemukan atau status tidak aktif
    }
    
    
    

    // Function to save data to the t_register table
    // public function t_register($data) {
    //     return $this->dbfaces->insert('t_register', $data);
    // }
    public function register($id, $nim, $name, $descriptor) {
        // Pastikan semua parameter tidak kosong
        if (empty($nim) || empty($name) || empty($descriptor)) {
            return false; // Kembalikan false jika ada yang kosong
        }

        // Siapkan data untuk diinsert
        $data = array(
            'id' => $id,
            'nim' => $nim,
            'name' => $name,
            'descriptor' => $descriptor
        );

        // Lakukan insert
        return $this->dbfaces->insert('t_register', $data);
    }

    // Function to check attendance based on name and date
    public function checkAttendance($name, $date) {
        $this->dbfaces->where('name', $name);
        $this->dbfaces->where('date', $date);
        return $this->dbfaces->get('t_attendances')->row_array();
    }

    // Function to save attendance
    public function saveAttendance($data) {
        // Cek apakah data yang diperlukan ada
        if (empty($data['name']) || empty($data['date'])) {
            return false; // Kembalikan false jika data tidak lengkap
        }
        return $this->dbfaces->insert('t_attendances', $data);
    }

    // Function to update attendance
    public function updateAttendance($id, $data) {
        $this->dbfaces->where('id', $id);
        return $this->dbfaces->update('t_attendances', $data);
    }

    // Function to get t_attendances by name and date
    // public function checkAttendanceP($name, $date) {
    //     $this->dbfaces->where('name', $name);
    //     $this->dbfaces->where('date', $date);
    //     return $this->dbfaces->get('t_attendances')->row_array();
    // }
    public function checkAttendanceP($name, $date)
    {
        $this->dbfaces->where('name', $name);
        $this->dbfaces->where('date', $date);
        $query = $this->dbfaces->get('t_attendances'); // Ganti 't_attendances' dengan nama tabel Anda

        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            return [
                'exists' => true,
                'id' => $result['id'],
                'kegiatan' => $result['kegiatan'],
                'timeot' => $result['timeot']
            ];
        } else {
            return ['exists' => false];
        }
    }

    

    // Function to save t_attendances with error handling
    public function saveAttendanceUP($data) {
        
        // Cek apakah data yang diperlukan ada
        if (empty($data['name']) || empty($data['date'])) {
            return false; // Kembalikan false jika data tidak lengkap
        }

        return $this->dbfaces->insert('t_attendances', $data);
        // try {
        //     $this->dbfaces->insert('t_attendances', $data);
        //     return array('success' => true, 'message' => 'Attendance saved successfully');
        // } catch (Exception $e) {
        //     log_message('error', 'Error saving t_attendances: ' . $e->getMessage());
        //     return false;
        // }
    }

    public function generate_base64_id() {
        $randomBytes = random_bytes(16); 
        return base64_encode($randomBytes); 
    }


//tabel t_attendances
// public function getAllData() {
//     // Mendapatkan semua data dari t_register, t_attendances, dan t_mahasiswa
//     $registerData = $this->dbfaces->get('t_register')->result_array();
//     $attendanceData = $this->dbfaces->get('t_attendances')->result_array();
//     $mahasiswaData = $this->dbfaces->get('t_mahasiswa')->result_array(); // Ambil data dari t_mahasiswa

//     return [
//         't_register' => $registerData,
//         't_attendances' => $attendanceData,
//         't_mahasiswa' => $mahasiswaData // Kembalikan data mahasiswa
//     ];
// }
// public function getAllData() {
//     // Mendapatkan semua data dari t_register
//     // $registerData = $this->dbfaces->get('t_register')->result_array();
    
//     // Mendapatkan data dari t_attendances dan t_mahasiswa dengan join
//     $attendanceData = $this->dbfaces->query("
//         SELECT 
//             a.*, 
//             m.mhs_univ, 
//             m.mhs_nim, 
//             m.mhs_jurusan 
//         FROM 
//             t_attendances AS a 
//         LEFT JOIN 
//             t_mahasiswa AS m 
//         ON 
//             a.name = m.mhs_nama
//     ")->result_array();

//     return [
//         // 't_register' => $registerData,
//         't_attendances' => $attendanceData // Kembalikan data attendances dengan informasi mahasiswa
//     ];
// }
    public function getAllData() {
        // Mendapatkan data dari t_attendances dan t_mahasiswa dengan join
        $attendanceData = $this->dbfaces->query("
            SELECT 
                a.*, 
                m.mhs_univ, 
                m.mhs_nim, 
                m.mhs_jurusan 
            FROM 
                t_attendances AS a 
            LEFT JOIN 
                t_mahasiswa AS m 
            ON 
                a.name = m.mhs_nama
            GROUP BY 
                a.id
        ")->result_array();

        return [
            't_attendances' => $attendanceData // Kembalikan data attendances dengan informasi mahasiswa
        ];
    }





    public function updateData($id, $updatedData) {
        // Update data berdasarkan ID di tabel t_attendances
        $this->dbfaces->where('id', $id);
        $this->dbfaces->update('t_attendances', $updatedData);

        return $this->dbfaces->affected_rows() > 0;
    }

    public function deleteData($id) {
        // Menghapus data berdasarkan ID dari tabel tertentu
        $this->dbfaces->where('id', $id);
        $this->dbfaces->delete($t_attendances);

        return $this->dbfaces->affected_rows() > 0;
    }

    public function deleteItems($ids)
    {
        if (!empty($ids)) {
           $this->dbfaces->where_in('id', $ids);
            $result = $this->dbfaces->delete('t_attendances');
    
            log_message('debug', 'Query delete yang dieksekusi: ' . $this->db->last_query());
    
            return $result;
        }
        return false;
    }
}
