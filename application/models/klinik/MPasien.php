<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MPasien extends CI_Model {
    public function __construct()
    {
		parent::__construct();
		$this->db = $this->load->database('klinik', TRUE);
	}


    public function get_all() {
        return $this->db->get('pasien')->result();
    }

    public function get_all_with_username() {
        $this->db->select('pasien.*, users.username');
        $this->db->from('pasien');
        $this->db->join('users', "CAST(SUBSTRING(users.username, -3) AS UNSIGNED) = pasien.id AND users.username LIKE 'P%'", 'left');
        return $this->db->get()->result();
    }
    

    public function insert($data) {
        return $this->db->insert('pasien', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('pasien', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('pasien');
    }
    public function get_last_id_pasien() {
        // Ambil ID pasien terakhir berdasarkan ID yang terbesar
        $this->db->select_max('id');  // Ambil nilai terbesar dari kolom id_pasien
        $query = $this->db->get('pasien');
        
        // Ambil hasil query
        $result = $query->row();
        return $result ? $result->id : 0;  // Jika tidak ada data, return 0
    }
    

    function get_by_id($id){
		$this->db->where('id', $id);
		return $this->db->get('pasien');
 
	}
    public function get_by_id_pasien($id)
    {
        $this->db->select('pasien.*')
                ->from('pasien')
                ->where('pasien.id', $id);
        return $this->db->get()->row();
    }


    public function get_aset_id($id) {
        return $this->db->get_where('pasien', ['id' => $id])->row_array();
    }

    public function count_pasien() {
        return $this->db->count_all_results('pasien');
    }

    public function get_pasien_by_jadwal($id_jadwal) {
        $this->db->select('pasien.nama, pasien.no_hp, daftar_poli.keluhan, daftar_poli.no_antrian');
        $this->db->from('daftar_poli');
        $this->db->join('pasien', 'daftar_poli.id_pasien = pasien.id');
        $this->db->where('daftar_poli.id_jadwal', $id_jadwal);
        return $this->db->get()->result();
    }

    public function get_daftar_pasien($id_dokter)
    {
        $this->db->select('dp.no_antrian, p.nama, p.no_hp, dp.keluhan, jp.hari, jp.jam_mulai, jp.jam_selesai');
        $this->db->from('daftar_poli dp');
        $this->db->join('pasien p', 'dp.id_pasien = p.id');
        $this->db->join('jadwal_periksa jp', 'dp.id_jadwal = jp.id');
        $this->db->where('jp.id_dokter', $id_dokter);
        $this->db->order_by('dp.no_antrian', 'ASC');
        return $this->db->get()->result_array();
    }




    

    // Generate nomor antrian
    public function generateNomorAntriaan($id_jadwal) {
        // Periksa apakah perlu reset nomor antrian
        $this->db->where('id', $id_jadwal);
        $jadwal = $this->db->get('jadwal_periksa')->row_array();
    
        if (strtotime($jadwal['last_reset_day']) <= strtotime('-7 days')) {
            // Reset nomor antrian jika sudah lewat 7 hari
            $this->db->set('last_reset_day', date('Y-m-d'));
            $this->db->where('id', $id_jadwal);
            $this->db->update('jadwal_periksa');

            return 1; // Reset ke nomor 1
        }

        // Hitung nomor antrian berikutnya
        $this->db->where('id', $id_jadwal);
        $total = $this->db->count_all_results('daftar_poli');

        return $total + 2;
    }
    public function generateNomorAntrian($id_jadwal) {
        // Ambil informasi jadwal berdasarkan ID
        $this->db->where('id', $id_jadwal);
        $jadwal = $this->db->get('jadwal_periksa')->row_array();
    
        if (!$jadwal) {
            return false; // Jika jadwal tidak ditemukan
        }
    
        // Periksa apakah perlu reset nomor antrian (lebih dari 7 hari)
        if (strtotime($jadwal['last_reset_day']) <= strtotime('-7 days')) {
            // Reset nomor antrian jika sudah lewat 7 hari
            $this->db->set('last_reset_day', date('Y-m-d'));
            $this->db->where('id', $id_jadwal);
            $this->db->update('jadwal_periksa');
    
            return 1; // Mulai ulang dari nomor 1
        }
    
        // Hitung nomor antrian berikutnya berdasarkan id_jadwal
        $this->db->where('id_jadwal', $id_jadwal);
        $this->db->order_by('no_antrian', 'DESC'); // Urutkan nomor antrian dari yang terbesar
        $daftar_poli = $this->db->get('daftar_poli')->row_array();
    
        if ($daftar_poli) {
            return $daftar_poli['no_antrian'] + 1; // Tambahkan nomor antrian berikutnya
        }
    
        return 1; // Jika belum ada entri, mulai dari nomor 1
    }
    
    public function generateNomorAntriman($id_jadwal) {
        // Periksa apakah perlu reset nomor antrian
        $this->db->where('id', $id_jadwal);
        $jadwal = $this->db->get('jadwal_periksa')->row_array();
        
        if (strtotime($jadwal['last_reset_day']) <= strtotime('-7 days')) {
            // Reset nomor antrian jika sudah lebih dari 7 hari
            $this->db->set('last_reset_day', date('Y-m-d'));
            $this->db->where('id', $id_jadwal);
            $this->db->update('jadwal_periksa');
    
            return 1; // Reset ke nomor 1
        }
    
        // Hitung nomor antrian berikutnya berdasarkan id_jadwal
        $this->db->where('id_jadwal', $id_jadwal);
        $total = $this->db->count_all_results('daftar_poli');
    
        return $total + 1; // Nomor antrian berikutnya
    }
    

    // Simpan data pendaftaran poli
    public function saveDaftarPoli($data) {
        $this->db->insert('daftar_poli', $data);
        return $this->db->insert_id(); // Mengembalikan ID yang baru ditambahkan
    }

    public function getRiwayatByPasiemn($id) {
        $this->db->select('daftar_poli.*, pasien.no_rm, jadwal_periksa.*, dokter.nama AS dokter_nama, dokter.id_poli');
        $this->db->from('daftar_poli');
        $this->db->join('pasien', 'daftar_poli.id_pasien = pasien.id', 'inner');
        $this->db->join('jadwal_periksa', 'daftar_poli.id_jadwal = jadwal_periksa.id', 'inner');
        $this->db->join('dokter', 'jadwal_periksa.id_dokter = dokter.id', 'inner');
        $this->db->where('daftar_poli.id_pasien', $id);
        return $this->db->get()->result_array();
    }
    public function getRiwayatByPasien($id) {
        $this->db->select('
            daftar_poli.*,
            pasien.no_rm,
            jadwal_periksa.hari,
            jadwal_periksa.jam_mulai,
            jadwal_periksa.jam_selesai,
            dokter.nama AS dokter_nama,
            dokter.id_poli,
            poli.nama_poli AS poli_nama
        '); 
        $this->db->from('daftar_poli');
        $this->db->join('pasien', 'daftar_poli.id_pasien = pasien.id', 'inner');
        $this->db->join('jadwal_periksa', 'daftar_poli.id_jadwal = jadwal_periksa.id', 'inner');
        $this->db->join('dokter', 'jadwal_periksa.id_dokter = dokter.id', 'inner');
        $this->db->join('poli', 'dokter.id_poli = poli.id', 'inner'); // Join ke tabel poli
        $this->db->where('daftar_poli.id_pasien', $id);
        // return $this->db->get()->result_array();
        $result = $this->db->get()->result_array();
        
        // Cek apakah pasien sudah diperiksa
        foreach ($result as &$row) {
            $this->db->select('id');
            $this->db->from('periksa');
            $this->db->where('id_daftar_poli', $row['id']); // Periksa berdasarkan id_daftar_poli
            $periksa = $this->db->get()->row_array();
    
            $row['status'] = $periksa ? 'Sudah Diperiksa' : 'Belum Diperiksa';
        }
    
        return array_filter($result, function ($row) {
            return $row['status'] === 'Belum Diperiksa'; // Hanya kembalikan data dengan status "Belum Diperiksa"
        });
    }
    
    public function isSudahDiperiksa($id) {
        $this->db->where('id_daftar_poli', $id);
        $query = $this->db->get('periksa');
        return $query->num_rows() > 0; // Return true jika ada record
    }

    public function get_by_ktp($no_ktp) {
        return $this->db->get_where('pasien', ['no_ktp' => $no_ktp])->row();
    }
    

    public function getRiwayatPeriksaa($id_pasien) {
        $this->db->select('p.id AS id_periksa, p.*, dp.*, ps.*, GROUP_CONCAT(o.nama_obat SEPARATOR ", ") AS obat'); // Menentukan kolom yang ingin diambil dan menggunakan GROUP_CONCAT untuk menggabungkan nama obat
        $this->db->from('periksa p');
        $this->db->join('daftar_poli dp', 'p.id_daftar_poli = dp.id');
        $this->db->join('pasien ps', 'dp.id_pasien = ps.id');
        $this->db->join('detail_periksa dpr', 'p.id = dpr.id_periksa');
        $this->db->join('obat o', 'dpr.id_obat = o.id');
        $this->db->join('jadwal_periksa jp', 'dp.id_jadwal = jp.id');
        // $this->db->where('jp.id_dokter', $id_dokter); // Filter berdasarkan ID dokter yang sedang login

        $this->db->where('dp.id_pasien', $id_pasien); // Filter berdasarkan ID dokter yang sedang login
        
        $this->db->where('dp.status_periksa', 0); 
        $this->db->group_by('p.id'); // Melakukan grup berdasarkan `id_periksa` untuk menggabungkan obat dengan id yang sama

        $query = $this->db->get();
        return $query->result(); // Mengembalikan hasil query
    }

    
    
    public function getRiwayatPeriksa($id_pasien) {
        $this->db->select('
            dp.id AS id_daftar_poli,
            dp.*, 
            p.id AS id_periksa, 
            p.*, 
            ps.*, 
            jp.hari, 
            jp.jam_mulai, 
            jp.jam_selesai, 
            dokter.nama AS dokter_nama, 
            poli.nama_poli AS poli_nama, 
            GROUP_CONCAT(o.nama_obat SEPARATOR ", ") AS obat
        ');
        $this->db->from('daftar_poli dp');
        $this->db->join('pasien ps', 'dp.id_pasien = ps.id', 'inner');
        $this->db->join('jadwal_periksa jp', 'dp.id_jadwal = jp.id', 'inner');
        $this->db->join('dokter', 'jp.id_dokter = dokter.id', 'inner');
        $this->db->join('poli', 'dokter.id_poli = poli.id', 'inner'); 
        $this->db->join('periksa p', 'dp.id = p.id_daftar_poli', 'inner');
        $this->db->join('detail_periksa dpr', 'p.id = dpr.id_periksa', 'inner');
        $this->db->join('obat o', 'dpr.id_obat = o.id', 'left');
        
        // Filter berdasarkan pasien
        $this->db->where('dp.id_pasien', $id_pasien);
        
        // Group by id_periksa untuk menggabungkan obat-obat terkait
        $this->db->group_by('p.id');
    
        // Eksekusi query dan ambil hasilnya
        $query = $this->db->get();
        return $query->result(); // Mengembalikan hasil query
    }
    
}  