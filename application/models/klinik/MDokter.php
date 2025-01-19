<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MDokter extends CI_Model {

    public function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('klinik', TRUE);
	}

    public function get_all() {
        $this->db->select('dokter.*, poli.nama_poli');
        $this->db->from('dokter');
        $this->db->join('poli', 'poli.id = dokter.id_poli', 'inner');
        return $this->db->get()->result();
    }

    public function get_dokter_with_username() {
        $this->db->select('dokter.id, dokter.nama, dokter.alamat, dokter.no_hp, dokter.id_poli, poli.nama_poli, users.username');
        $this->db->from('dokter');
        $this->db->join('poli', 'poli.id = dokter.id_poli', 'inner');
        $this->db->join('users', "CAST(SUBSTRING(users.username, -3) AS UNSIGNED) = dokter.id AND users.username LIKE 'D%'", 'left');
        return $this->db->get()->result();
    }
    
    public function insert($data) {
        return $this->db->insert('dokter', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('dokter', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('dokter');
    }

    function get_by_id($id){
		$this->db->where('id', $id);
		return $this->db->get('dokter');
	}
    public function get_last_id_dokter() {
        // Ambil ID pasien terakhir berdasarkan ID yang terbesar
        $this->db->select_max('id');  // Ambil nilai terbesar dari kolom id_pasien
        $query = $this->db->get('dokter');
        
        // Ambil hasil query
        $result = $query->row();
        return $result ? $result->id : 0;  // Jika tidak ada data, return 0
    }
    public function get_by_id_dokter1($id)
    {
        $this->db->select('dokter.*')
                ->from('dokter')
                ->where('dokter.id', $id);
        return $this->db->get()->row();
    }
    public function get_by_id_dokter($id)
    {
        $this->db->select('dokter.*, poli.nama_poli')
                ->from('dokter')
                ->join('poli', 'dokter.id_poli = poli.id', 'left') // LEFT JOIN untuk memastikan data dokter tetap ada meskipun poli tidak ditemukan
                ->where('dokter.id', $id);
        return $this->db->get()->row(); // Mengembalikan satu baris data
    }

    public function get_aset_id($id) {
        return $this->db->get_where('dokter', ['id' => $id])->row_array();
    }

    public function count_dokter() {
        return $this->db->count_all_results('dokter');
      }


      public function get_jadwal_periksa() {
        $this->db->select('jadwal_periksa.*, dokter.nama as nama_dokter');
        $this->db->from('jadwal_periksa');
        $this->db->join('dokter', 'jadwal_periksa.id_dokter = dokter.id');
        return $this->db->get()->result();
    }
    public function get_jadwal_by_user_id($id)
    {
        $this->db->select('jadwal_periksa.*, dokter.nama as nama_dokter');
        $this->db->from('jadwal_periksa');
        $this->db->join('dokter', 'jadwal_periksa.id_dokter = dokter.id');
        $this->db->where('jadwal_periksa.id_dokter', $id); // Filter berdasarkan id dari session
        return $this->db->get()->result(); // Mengembalikan hasil query sebagai array objek
    }
    public function get_jadwal_by_id($id)
    {
        $this->db->select('jadwal_periksa.*, dokter.nama as nama_dokter');
        $this->db->from('jadwal_periksa');
        $this->db->join('dokter', 'jadwal_periksa.id_dokter = dokter.id', 'left');
         $this->db->where('jadwal_periksa.id', $id);
        return $this->db->get();
    }

    public function update_jadwal($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('jadwal_periksa', $data);
    }
    public function delete_jadwal($id) {
        $this->db->where('id', $id);
        return $this->db->delete('jadwal_periksa');
    }
    public function cek_jadwal_bentrok($data) {
        // Langkah 1: Validasi jam mulai dan selesai
        if (strtotime($data['jam_mulai']) >= strtotime($data['jam_selesai'])) {
            return [
                'status' => true,
                'pesan' => 'Jam mulai harus lebih kecil dari jam selesai.'
            ];
        }
        
        // Langkah 2: Cek apakah dokter sudah memiliki jadwal di hari yang sama
        $this->db->where('id_dokter', $data['id_dokter']);
        $this->db->where('hari', $data['hari']);
        $query_hari = $this->db->get('jadwal_periksa');
    
        if ($query_hari->num_rows() > 0) {
            return [
                'status' => true,
                'pesan' => 'Dokter sudah memiliki jadwal pada hari yang sama.'
            ];
        }

        // Langkah 3: Dapatkan id_poli dari tabel dokter berdasarkan id_dokter
        $this->db->select('id_poli');
        $this->db->where('id', $data['id_dokter']);
        $query_poli = $this->db->get('dokter');
        $result_poli = $query_poli->row();

        if (!$result_poli) {
            return [
                'status' => true,
                'pesan' => 'Data poli tidak ditemukan untuk dokter ini.'
            ];
        }

        $id_poli = $result_poli->id_poli;
    
       // Langkah 4: Cek bentrokan jadwal dalam poli yang sama
        $this->db->select('jadwal_periksa.jam_mulai, jadwal_periksa.jam_selesai');
        $this->db->from('jadwal_periksa');
        $this->db->join('dokter', 'jadwal_periksa.id_dokter = dokter.id');
        $this->db->where('dokter.id_poli', $id_poli);
        $this->db->where('jadwal_periksa.hari', $data['hari']);
        $this->db->group_start(); // Grouping kondisi jam
            $this->db->where('jadwal_periksa.jam_mulai <', $data['jam_selesai']);
            $this->db->where('jadwal_periksa.jam_selesai >', $data['jam_mulai']);
        $this->db->group_end();
        $query_bentrok = $this->db->get();

        if ($query_bentrok->num_rows() > 0) {
            return [
                'status' => true,
                'pesan' => 'Jadwal bertabrakan dengan dokter lain dalam poli yang sama.'
            ];
        }
    
        // Tidak ada konflik
        return [
            'status' => false,
            'pesan' => 'Jadwal valid.'
        ];
    }
    
    
    
    public function cek_jadwal_bentrommmk($data) {
        // Langkah 1: Cek apakah id_dokter sudah memiliki hari yang sama
        if (strtotime($data['jam_mulai']) >= strtotime($data['jam_selesai'])) {
            return [
                'status' => true,
                'pesan' => 'Jam mulai harus lebih kecil dari jam selesai.'
            ];
        }
        
        $this->db->where('id_dokter', $data['id_dokter']);
        $this->db->where('hari', $data['hari']);
        $query_hari = $this->db->get('jadwal_periksa');
    
        // Jika ada data dengan hari yang sama, jadwal bentrok langsung
        if ($query_hari->num_rows() > 0) {
            return [
                'status' => true,
                'pesan' => 'Dokter sudah memiliki jadwal pada hari yang sama.'
            ];
        }
    
        // Langkah 2: Jika tidak ada hari yang sama, cek bentrok berdasarkan rentang waktu
        if ($query_hari->num_rows() > 0) {
            $this->db->where('id_dokter', $data['id_dokter']);
            $this->db->where('hari', $data['hari']);
            $this->db->group_start(); // Grup kondisi waktu
                $this->db->where('jam_mulai <', $data['jam_selesai']);
                $this->db->where('jam_selesai >', $data['jam_mulai']);
            $this->db->group_end();
            $query_waktu = $this->db->get('jadwal_periksa');
        
            // Jika ada data dengan rentang waktu bentrok, jadwal bentrok
            if ($query_waktu->num_rows() > 0) {
                return [
                    'status' => true,
                    'pesan' => 'Jadwal bentrok dengan rentang waktu yang ada.'
                ];
            }
        }
    
        
    }

    public function cek_jadwal_bentrok_edit($data, $id)
    {
        if (strtotime($data['jam_mulai']) >= strtotime($data['jam_selesai'])) {
            return [
                'status' => true,
                'pesan' => 'Jam mulai harus lebih kecil dari jam selesai.'
            ];
        }
        
        // Langkah 1: Cek apakah id_dokter sudah memiliki hari yang sama
        $this->db->where('id_dokter', $data['id_dokter']);
        $this->db->where('hari', $data['hari']);
        $this->db->where('id !=', $id); // Abaikan jadwal yang sedang diedit
        $query_hari = $this->db->get('jadwal_periksa');

        if ($query_hari->num_rows() > 0) {
            return [
                'status' => true,
                'pesan' => 'Dokter sudah memiliki jadwal pada hari yang sama.'
            ];
        }

        // Langkah 2: Cek rentang waktu bentrok
        $this->db->where('id_dokter', $data['id_dokter']);
        $this->db->group_start(); // Grup kondisi waktu
            $this->db->where('jam_mulai <', $data['jam_selesai']);
            $this->db->where('jam_selesai >', $data['jam_mulai']);
        $this->db->group_end();
        $this->db->where('id !=', $id); // Abaikan jadwal yang sedang diedit
        $query_waktu = $this->db->get('jadwal_periksa');

        if ($query_waktu->num_rows() > 0) {
            return [
                'status' => true,
                'pesan' => 'Jadwal bentrok dengan rentang waktu yang ada.'
            ];
        }
    }

    public function update_status($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('jadwal_periksa', $data);
    }
    public function get_jadwal_by_idA($id)
    {
        return $this->db->get_where('jadwal_periksa', ['id' => $id])->row();
    }
    public function get_active_jadwal_by_dokter($id_dokter)
    {
        $this->db->where('id_dokter', $id_dokter);
        $this->db->where('status', 'active');
        return $this->db->get('jadwal_periksa')->row();
    }


    public function get_active_jadwal($id_dokter)
    {
        $this->db->where('status', 'active');
        $this->db->where('id_dokter', $id_dokter);
        $query = $this->db->get('jadwal_periksa');
        return $query->num_rows() > 0;
    }

    public function set_inactive_after_week($id_dokter)
    {
        $one_week_ago = date('Y-m-d H:i:s', strtotime('-7 days'));
        $this->db->where('status', 'active');
        $this->db->where('id_dokter', $id_dokter);
        $this->db->where('updated_at <=', $one_week_ago);
        $this->db->update('jadwal_periksa', array('status' => 'inactive'));
    }
     // Fungsi untuk mengambil jadwal aktif selain id yang baru
     public function get_other_active_jadwal($id_dokter)
     {
         $this->db->select('id, status');
         $this->db->where('status', 'active');
         $this->db->where('id_dokter !=', $id_dokter);
         $query = $this->db->get('jadwal_periksa');
         return $query->result_array();
     }
    

    public function simpan_jadwal($data) {
        $this->db->insert('jadwal_periksa', $data);
    }


    // Ambil dokter berdasarkan id_poli
    public function getDokterByPoli($id_poli) {
        $this->db->where('id_poli', $id_poli);
        return $this->db->get('dokter')->result_array();
    }

    // Ambil jadwal berdasarkan id_dokter
    public function getJadwalByDokter($id_dokter) {
        $this->db->where('id_dokter', $id_dokter);
        $this->db->where('status', 'active');
        return $this->db->get('jadwal_periksa')->result_array();
    }

    public function getDokterJadwalByPoli($id_poli)
    {
        $this->db->select('dokter.id AS id_dokter, dokter.nama AS nama_dokter, jadwal.id AS id_jadwal, jadwal.hari, jadwal.jam_mulai, jadwal.jam_selesai');
        $this->db->from('dokter');
        $this->db->join('jadwal_periksa AS jadwal', 'jadwal.id_dokter = dokter.id');
        $this->db->where('dokter.id_poli', $id_poli);
        $this->db->where('jadwal.status', 'active');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getRiwayatByDoktemr($id_dokter) {
        $this->db->select('
            daftar_poli.*,
            pasien.no_rm,
            pasien.nama AS pasien_nama,
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
        $this->db->where('jadwal_periksa.id_dokter', $id_dokter); // Filter berdasarkan id_dokter
        return $this->db->get()->result_array();
    }
    public function getRiwayatByDokter($id_dokter) {
        $this->db->select('
            daftar_poli.*,
            pasien.no_rm,
            pasien.nama AS pasien_nama,
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
        $this->db->join('poli', 'dokter.id_poli = poli.id', 'inner');
        $this->db->where('jadwal_periksa.id_dokter', $id_dokter);
    
        // Dapatkan data pasien
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
    

     // Mengambil detail berdasarkan ID
     public function getDetailById($id) {
        $this->db->select('
            daftar_poli.*,
            pasien.no_rm,
            pasien.nama AS pasien_nama,
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
        $this->db->where('daftar_poli.id', $id); // Filter berdasarkan ID pemeriksaan
        return $this->db->get()->row_array();
    }
    
    // Simpan data pemeriksaan
    public function savePeriksa($data, $obat) {
        $this->db->insert('periksa', $data);
        $id_periksa = $this->db->insert_id();

        // Simpan detail obat jika ada obat yang dipilih
        if (!empty($obat)) {
            foreach ($obat as $obat_id) {
                $this->db->insert('detail_periksa', [
                    'id_periksa' => $id_periksa,
                    'id_obat' => $obat_id
                ]);
            }
        }
    }

    public function getPeriksaData() {
        $this->db->select('p.id, p.id_daftar_poli, p.tgl_periksa, p.catatan, p.biaya_periksa, d.id_pasien, d.nama as pasien_nama, d.tgl_lahir as pasien_tgl_lahir, dp.id_periksa, dp.id_obat, o.nama as obat_nama');
        $this->db->from('periksa p');
        $this->db->join('daftar_poli d', 'p.id_daftar_poli = d.id', 'inner');
        $this->db->join('pasien pa', 'd.id_pasien = pa.id', 'inner');
        $this->db->join('detail_periksa dp', 'p.id = dp.id_periksa', 'inner');
        $this->db->join('obat o', 'dp.id_obat = o.id', 'inner');
        $query = $this->db->get();
        return $query->result();
    }

    
    public function getRiwayatPeriksa($id_dokter) {
        $this->db->select('p.id AS id_periksa, 
        p.*,
       
        dp.id as dp_id,
        dp.id_pasien as dp_id_pasien,
        dp.id_jadwal as dp_id_jadwal,
        dp.keluhan as dp_keluhan,
        dp.no_antrian as dp_no_antrian,
        dp.status_periksa as dp_status_periksa,

        ps.id as pasien_id, 
        ps.nama as pasien_nama, 
        ps.alamat as pasien_alamat, 
        ps.no_ktp as pasien_no_ktp,
        ps.no_hp as pasien_no_hp,
        ps.no_rm as pasien_no_rm,
       
        GROUP_CONCAT(o.nama_obat SEPARATOR ", ") AS obat, 
        d.nama as dokter_nama'); // Menentukan kolom yang ingin diambil dan menggunakan GROUP_CONCAT untuk menggabungkan nama obat
        $this->db->from('periksa p');
        $this->db->join('daftar_poli dp', 'p.id_daftar_poli = dp.id');
        $this->db->join('pasien ps', 'dp.id_pasien = ps.id');
        $this->db->join('detail_periksa dpr', 'p.id = dpr.id_periksa');
        $this->db->join('obat o', 'dpr.id_obat = o.id');
        $this->db->join('jadwal_periksa jp', 'dp.id_jadwal = jp.id');
        $this->db->join('dokter d', 'jp.id_dokter = d.id');
        // $this->db->where('jp.id_dokter', $id_dokter); // Filter berdasarkan ID dokter yang sedang login

        $this->db->group_by('p.id'); // Melakukan grup berdasarkan `id_periksa` untuk menggabungkan obat dengan id yang sama

        $query = $this->db->get();
        return $query->result(); // Mengembalikan hasil query
    }

    public function getDetailPeriksaNById($id_periksa) {
        $this->db->select('p.id AS id_periksa, p.*, dp.*, ps.*, GROUP_CONCAT(o.nama_obat SEPARATOR ", ") AS obat');
        $this->db->from('periksa p');
        $this->db->join('daftar_poli dp', 'p.id_daftar_poli = dp.id');
        $this->db->join('pasien ps', 'dp.id_pasien = ps.id');
        $this->db->join('detail_periksa dpr', 'p.id = dpr.id_periksa');
        $this->db->join('obat o', 'dpr.id_obat = o.id');
        $this->db->join('jadwal_periksa jp', 'dp.id_jadwal = jp.id');
        $this->db->where('p.id', $id_periksa); // Filter berdasarkan ID periksa
        $this->db->group_by('p.id'); // Grupkan hasil berdasarkan ID periksa
    
        $query = $this->db->get();
        return $query->row(); // Mengembalikan satu baris data
    }
    public function getDetailPerikMsaById($id_periksa) {
        $this->db->select('
            p.id AS id_periksa, 
            p.*, 
            dp.*, 
            ps.*, 
            o.id AS id_obat,
            o.nama_obat,
            o.kemasan,
            o.harga,
        '); // Ambil semua kolom dari obat yang diperlukan
        $this->db->from('periksa p');
        $this->db->join('daftar_poli dp', 'p.id_daftar_poli = dp.id');
        $this->db->join('pasien ps', 'dp.id_pasien = ps.id');
        $this->db->join('detail_periksa dpr', 'p.id = dpr.id_periksa');
        $this->db->join('obat o', 'dpr.id_obat = o.id');
        $this->db->join('jadwal_periksa jp', 'dp.id_jadwal = jp.id');
        $this->db->where('p.id', $id_periksa); // Filter berdasarkan ID periksa
        $this->db->group_by('p.id'); // Grupkan hasil berdasarkan ID periksa
        
        $query = $this->db->get();
        return $query->result(); // Mengembalikan satu baris data
    }
    public function getDetailPeriksaNNById($id_periksa) {
        $this->db->select('
            p.id AS id_periksa, 
            p.*, 
            dp.*, 
            ps.*, 
            o.id AS id_obat,
            o.nama_obat,
            o.harga,
            o.kemasan
        '); // Ambil semua kolom obat yang diperlukan
        $this->db->from('periksa p');
        $this->db->join('daftar_poli dp', 'p.id_daftar_poli = dp.id');
        $this->db->join('pasien ps', 'dp.id_pasien = ps.id');
        $this->db->join('detail_periksa dpr', 'p.id = dpr.id_periksa');
        $this->db->join('obat o', 'dpr.id_obat = o.id');
        $this->db->join('jadwal_periksa jp', 'dp.id_jadwal = jp.id');
        $this->db->where('p.id', $id_periksa); // Filter berdasarkan ID periksa
        $this->db->order_by('o.id'); // Urutkan berdasarkan id_obat untuk memudahkan pengelompokkan
        
        $query = $this->db->get();
        return $query->result(); // Mengembalikan hasil query
    }
    
    public function getDetailPeriksaById($id_periksa) {
        $this->db->select('
            p.id AS id_periksa, 
            p.*, 
            dp.*, 
            ps.*, 
            o.id AS id_obat,
            o.nama_obat,
            o.harga
        ');
        $this->db->from('periksa p');
        $this->db->join('daftar_poli dp', 'p.id_daftar_poli = dp.id');
        $this->db->join('pasien ps', 'dp.id_pasien = ps.id');
        $this->db->join('detail_periksa dpr', 'p.id = dpr.id_periksa');
        $this->db->join('obat o', 'dpr.id_obat = o.id');
        $this->db->where('p.id', $id_periksa);
    
        $query = $this->db->get();
        $results = $query->result(); // Semua baris data
    
        // Mengelompokkan data menjadi satu objek dengan obat sebagai array
        if ($results) {
            $data = [
                'id_periksa' => $results[0]->id_periksa,
                'id_daftar_poli' => $results[0]->id_daftar_poli,
                'tgl_periksa' => $results[0]->tgl_periksa,
                'catatan' => $results[0]->catatan,
                'biaya_periksa' => $results[0]->biaya_periksa,
                'id_pasien' => $results[0]->id_pasien,
                'id_jadwal' => $results[0]->id_jadwal,
                'keluhan' => $results[0]->keluhan,
                'no_antrian' => $results[0]->no_antrian,
                'nama_pasien' => $results[0]->nama,
                'alamat_pasien' => $results[0]->alamat,
                'no_ktp' => $results[0]->no_ktp,
                'no_hp' => $results[0]->no_hp,
                'no_rm' => $results[0]->no_rm,
                'obat' => [] // Array untuk data obat
            ];
    
            foreach ($results as $row) {
                $data['obat'][] = [
                    'id_obat' => $row->id_obat,
                    'nama_obat' => $row->nama_obat,
                    'harga' => $row->harga
                ];
            }
    
            return $data;
        }
    
        return null; // Jika tidak ada data
    }
    
    
    // Fungsi untuk memperbarui data pemeriksaan
    public function updatePeriksa($id_periksa, $data) {
        $this->db->where('id', $id_periksa);
        return $this->db->update('periksa', $data);
    }

    // Fungsi untuk menghapus obat yang sudah dipilih sebelumnya
    public function deleteObat($id_periksa, $id_obat_lama) {
        if (!empty($id_obat_lama)) {
            $this->db->where('id_periksa', $id_periksa);
            $this->db->where_in('id_obat', $id_obat_lama);
            return $this->db->delete('detail_periksa');
        }
        return true; // Jika tidak ada obat lama untuk dihapus
    }
    public function deleteObqat($id_periksa, $id_obat_dihapus) {
        if (!empty($id_obat_dihapus)) {
            $this->db->where('id_periksa', $id_periksa);
            $this->db->where_in('id_obat', $id_obat_dihapus); // Menghapus banyak obat berdasarkan array ID
            return $this->db->delete('detail_periksa');
        }
        return true; // Jika tidak ada obat lama untuk dihapus
    }
    

    // Fungsi untuk menambahkan obat yang baru dipilih
    public function addObata($id_periksa, $id_obat_baru) {
        if (!empty($id_obat_baru)) {
            foreach ($id_obat_baru as $obat_id) {
                $this->db->insert('detail_periksa', [
                    'id_periksa' => $id_periksa,
                    'id_obat' => $obat_id
                ]);
            }
        }
        return true; // Jika tidak ada obat baru, tetap mengembalikan true
    }
    public function addObat($id_periksa, $id_obat) {
        $data = [
            'id_periksa' => $id_periksa,
            'id_obat' => $id_obat,
        ];
        return $this->db->insert('detail_periksa', $data);
    }
    

    public function get_daftar_pasien() {
        $this->db->select('daftar_poli.*, pasien.nama as nama_pasien, poli.nama_poli');
        $this->db->from('daftar_poli');
        $this->db->join('pasien', 'daftar_poli.id_pasien = pasien.id');
        $this->db->join('poli', 'daftar_poli.id_jadwal = poli.id');
        return $this->db->get()->result();
    }

    public function simpan_catatan($data) {
        $this->db->insert('periksa', $data);
    }                  



    public function get_pasien_by_id($id_pasien) {
        return $this->db->get_where('pasien', ['id' => $id_pasien])->row();
    }

    public function get_riwayat_pasien($id_pasien) {
        $this->db->select('periksa.*, poli.nama_poli');
        $this->db->from('periksa');
        $this->db->join('daftar_poli', 'periksa.id_daftar_poli = daftar_poli.id');
        $this->db->join('poli', 'daftar_poli.id_jadwal = poli.id');
        $this->db->where('daftar_poli.id_pasien', $id_pasien);
        return $this->db->get()->result();
    }

    //konsultasi
    public function getKonsulByDokter($id) {
        $this->db->select('
            konsultasi.*,
            konsultasi.id as id_konsul,
            pasien.*,
            dokter.nama AS dokter_nama,
            dokter.id_poli,
            poli.nama_poli AS poli_nama,
        '); 
        $this->db->from('konsultasi');
        $this->db->join('pasien', 'konsultasi.id_pasien = pasien.id', 'inner');
        $this->db->join('dokter', 'konsultasi.id_dokter = dokter.id', 'inner');
        $this->db->join('poli', 'dokter.id_poli = poli.id', 'inner'); // Join ke tabel poli
        $this->db->where('konsultasi.id_dokter', $id);
        return $this->db->get()->result_array();
        // $result = $this->db->get()->result_array();
        // return $result();
    }

    public function updateK($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('konsultasi', $data);
    }
} 