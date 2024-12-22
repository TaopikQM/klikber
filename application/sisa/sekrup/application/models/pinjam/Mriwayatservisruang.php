<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mriwayatservisruang extends CI_Model {

    public function __construct(){
        parent::__construct();
        // Load database connection for 'pinjam'
        $this->dbpinjam = $this->load->database('pinjam', TRUE);
    }

    // Ambil riwayat servis dengan data mobil
        public function get_servis_history_with_ruang() {
            $this->dbpinjam->select('rs_ruang.*, ruangan.nmfile, ruangan.nmruang, ruangan.ket');
            $this->dbpinjam->from('rs_ruang');
            $this->dbpinjam->join('ruangan', 'rs_ruang.id_ruang = ruangan.id'); // Bergantung pada nama kolom foreign key
            $query = $this->dbpinjam->get();
            return $query->result();
        }

        public function getAllRiwayatByRuangId($id_ruang) {
            $this->dbpinjam->where('id_ruang', $id_ruang);
            $query = $this->dbpinjam->get('rs_ruang');
            return $query->result();
        }

        public function getRuangById($id_ruang){
            $this->dbpinjam->where('id', $id_ruang);
            $query = $this->dbpinjam->get('ruangan');
            return $query->row();
        }

        public function get_data_ruang() {
            $this->dbpinjam->order_by('id', 'desc');
            return $this->dbpinjam->get('ruangan');
        }
        public function get_data_ruang_id($id) {
            $this->dbpinjam->where('id', $id);
            return $this->dbpinjam->get('ruangan');
        }

        public function save_rs_ruang($data) {
            $tb='rs_ruang';
            $hg ="-";
            $inn = array(
                'id_ruang' => $data['id_ruang'],
                'tgl_servis' => $data['tgl_servis'],
                'ket' => $data['ket']
            );
            $dh=$this->dbpinjam->insert($tb, $data);
            return $dh;
        }

        public function insert_riwayat($data) {
            return $this->dbpinjam->insert('rs_ruang', $data);
        }

        public function getById($id) {
            // Query untuk mengambil data berdasarkan ID
            $this->dbpinjam->where('id', $id);
            $query = $this->dbpinjam->get('rs_ruang');
            
            if ($query->num_rows() > 0) {
                return $query->row_array();
            } else { 
                return false;// 
            }
    }

    public function delete($id) {
        $this->dbpinjam->where('id', $id);
        return $this->dbpinjam->delete('rs_ruang');
    }

    public function s_e_data_riwayat_ruang($data, $id) {
        // Update data berdasarkan ID mobil
        $this->dbpinjam->where('id', $id);
        return $this->dbpinjam->update('rs_ruang', $data);
    }

    public function get_riwayat_ruang_by_id($id_ruang) {
        $this->dbpinjam->where('id_ruang', $id_ruang);
        $query = $this->dbpinjam->get('rs_ruang');
        return $query->result(); // Mengembalikan hasil query sebagai array objek
    }

    public function getRiwayatById($id) {
        $this->dbpinjam->where('id', $id);
        $query = $this->dbpinjam->get('rs_ruang');
        return $query->result();
    }
    

}

