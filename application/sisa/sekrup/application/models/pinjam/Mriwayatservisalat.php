<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mriwayatservisalat extends CI_Model {

    public function __construct(){
        parent::__construct();
        // Load database connection for 'pinjam'
        $this->dbpinjam = $this->load->database('pinjam', TRUE);
    }

    // Ambil riwayat servis dengan data mobil
        public function get_servis_history_with_alat() {
            $this->dbpinjam->select('rs_alat.*, alat.nmfile, alat.nmbarang, alat.ket');
            $this->dbpinjam->from('rs_alat');
            $this->dbpinjam->join('alat', 'rs_alat.id_alat = alat.id'); // Bergantung pada nama kolom foreign key
            $query = $this->dbpinjam->get();
            return $query->result();
        }

        public function get_data_rs_alat() {
            $this->dbpinjam->select('*');
            $this->dbpinjam->from('rs_alat');
            $query = $this->dbpinjam->get();
            return $query->result();
        }

        public function get_data_alat() {
            $this->dbpinjam->order_by('id', 'desc');
            return $this->dbpinjam->get('alat');
        }

        public function get_data_alat_id($id) {
            $this->dbpinjam->where('id', $id);
            return $this->dbpinjam->get('alat');
        }

        public function get_by_id($id_alat) {
            $this->dbpinjam->where('id_alat', $id_alat);
            $query = $this->dbpinjam->get('rs_alat');
            return $query->row(); // Mengembalikan satu baris data
        }

        public function save_rs_alat($data) {
            $tb='rs_alat';
            $hg ="-";
            for ($i=1; $i <5 ; $i++) { 
                if (isset($data['perlu'][$i]) && $data['perlu'][$i]!=NULL) {
                    $hg=$hg.$data['perlu'][$i]."-";
                }
            }
            
            $ht ="-";
            for ($ia=1; $ia <5 ; $ia++) { 
                if (isset($data['tjn'][$ia]) && $data['tjn'][$ia]!=NULL) {
                    $ht=$ht.$data['tjn'][$ia]."-";
                }
            }
            $inn = array(
                'id_alat' => $data['id_alat'],
                'tgl_servis' => $data['tgl_servis'],
                'ket' => $data['ket']
            );
            $dh=$this->dbpinjam->insert($tb, $data);
            return $dh;
        }

        public function update($id, $data) {
            $this->dbpinjam->where('id', $id);
            return $this->dbpinjam->update('rs_alat', $data);
        }
    
        // Fungsi untuk menghapus data riwayat servis
        public function delete($id) {
            $this->dbpinjam->where('id', $id);
            return $this->dbpinjam->delete('rs_alat');
        }

        // public function getAlatById($id) {
        //     $this->dbpinjam->where('id', $id);
        //     $query = $this->dbpinjam->get('rs_alat');
        //     return $query->result();
        // }
        
        // Fungsi untuk memperbarui riwayat servis berdasarkan ID (fungsi duplikat yang dihapus)
        public function updateAlat($id, $data) {
            $this->dbpinjam->where('id', $id);
            return $this->dbpinjam->update('rs_alat', $data);
        }
    
        public function insert($data) {
            $this->db->insert('rs_alat', $data);
        }

        public function s_e_data_alat($data, $id) {
            // Update data berdasarkan id
            $this->dbpinjam->where('id', $id);
            return $this->dbpinjam->update('rs_alat', $data);
        }
        
        public function getAllRiwayatByAlatId($id_alat) {
            $this->dbpinjam->where('id_alat', $id_alat);
            $query = $this->dbpinjam->get('rs_alat');
            return $query->result();
        }

        public function getAlatById($id_alat){
            $this->dbpinjam->where('id', $id_alat);
            $query = $this->dbpinjam->get('alat');
            return $query->row();
        }
    
        public function getRiwayatByAlatId($id_alat){
            $this->dbpinjam->where('id_alat', $id_alat);
            return $this->dbpinjam->get('rs_alat');
        }
    
        public function getById($id) {
            // Query untuk mengambil data berdasarkan ID
            $this->dbpinjam->where('id', $id);
            $query = $this->dbpinjam->get('rs_alat'); // 
    
            if ($query->num_rows() > 0) {
                return $query->row_array(); // Mengembalikan hasil sebagai array
            } else {
                return false; // Mengembalikan false jika data tidak ditemukan
            }
        }

        public function getRiwayatById($id) {
            $this->dbpinjam->where('id', $id);
            $query = $this->dbpinjam->get('rs_alat');
            return $query->result();
        }

}
?>
