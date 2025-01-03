<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mriwayatservis extends CI_Model {

    public function __construct(){
        parent::__construct();
        // Load database connection for 'pinjam'
        $this->dbpinjam = $this->load->database('pinjam', TRUE);
    }

    // Ambil riwayat servis dengan data mobil
    public function get_servis_history_with_mobil() {
        $this->dbpinjam->select('riwayat_servis.*, mobil.nmfile, mobil.nopol, mobil.nmerk, mobil.jenis');
        $this->dbpinjam->from('riwayat_servis');
        $this->dbpinjam->join('mobil', 'riwayat_servis.idmobil = mobil.id'); // Bergantung pada nama kolom foreign key
        $query = $this->dbpinjam->get();
        return $query->result();
    }

    public function get_data_riwayat_servis() {
        $this->dbpinjam->select('*');
        $this->dbpinjam->from('riwayat_servis');
        $query = $this->dbpinjam->get();
        return $query->result();
    }

    // public function getAllRiwayat() {
    //     $this->dbpinjam->select('*'); // Pastikan semua kolom yang diperlukan dipilih
    //     $query = $this->dbpinjam->get('riwayat_servis');
    //     return $query->result();
    // }

    public function getAllRiwayat() {
        $this->dbpinjam->select('riwayat_servis.*, mobil.th_rakit, mobil.pjk');
        $this->dbpinjam->from('riwayat_servis');
        $this->dbpinjam->join('mobil', 'riwayat_servis.idmobil = mobil.id');
        $query = $this->dbpinjam->get();
        return $query->result();
    }

    // Ambil semua data mobil
    public function get_data_mobil() {
        $this->dbpinjam->order_by('id', 'desc');
        return $this->dbpinjam->get('mobil');
    }
    public function get_data_mobil_id($id) {
        $this->dbpinjam->where('id', $id);
        return $this->dbpinjam->get('mobil');
    }

    public function get_all_drivers() {
        $this->dbpinjam->order_by('id', 'asc');
        return $this->dbpinjam->get('driver_mobil');
    }

    // Ambil riwayat servis berdasarkan ID
    public function get_mobil_by_id($idmobil) {
        $this->dbpinjam->where('idmobil', $idmobil);
        $query = $this->dbpinjam->get('riwayat_servis');
        return $query->row(); // Mengembalikan satu baris data
    }
    

    // Simpan riwayat servis
    public function save_riwayat_servis($data) {
        $tb='riwayat_servis';
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
            'idmobil' => $data['idmobil'],
            'nm_pemegang' => $data['nm_pemegang'],
            'kilometer' => $data['kilometer'],
            'blnket' => $data['blnket'],
            'ket' => $data['ket']
        );
        $dh=$this->dbpinjam->insert($tb, $data);
        return $dh;
    }

    // Fungsi untuk memperbarui data riwayat servis
    public function update($id, $data) {
        $this->dbpinjam->where('id', $id);
        return $this->dbpinjam->update('riwayat_servis', $data);
    }

    // Fungsi untuk menghapus data riwayat servis
    public function delete($id) {
        $this->dbpinjam->where('id', $id);
        return $this->dbpinjam->delete('riwayat_servis');
    }

    // Ambil data riwayat servis berdasarkan ID (fungsi duplikat yang dihapus)
    public function getRiwayatById($id) {
        $this->dbpinjam->where('id', $id);
        $query = $this->dbpinjam->get('riwayat_servis');
        return $query->result();
    }
    
    // Fungsi untuk memperbarui riwayat servis berdasarkan ID (fungsi duplikat yang dihapus)
    public function updateRiwayat($id, $data) {
        $this->dbpinjam->where('id', $id);
        return $this->dbpinjam->update('riwayat_servis', $data);
    }

    public function insert($data) {
        $this->db->insert('riwayat_servis', $data);
    }

    // public function s_e_data_riwayat($data) {
    //     $inn = [
    //         'idmobil' => $data['idmobil'],
    //         'kilometer' => $data['kilometer'], 
    //         'nm_pemegang' => $data['nm_pemegang'], 
    //         'blnket' => $data['blnket'], 
    //         'ket' => $data['ket']
    //     ];
    //     $this->dbpinjam->set($inn);
    //     $this->dbpinjam->where('id', $data['id']);
    //     return $this->dbpinjam->update('riwayat_servis');
    // }
    
    public function s_e_data_riwayat($data, $id) {
        $this->dbpinjam->where('id', $id); // Gunakan id riwayat servis
        return $this->dbpinjam->update('riwayat_servis', $data);
    }
    
    

    public function getAllRiwayatByMobilId($idmobil) {
        $this->dbpinjam->where('idmobil', $idmobil);
        $query = $this->dbpinjam->get('riwayat_servis');
        return $query->result();
    }

    public function getMobilById($idmobil){
        $this->dbpinjam->where('id', $idmobil);
        $query = $this->dbpinjam->get('mobil');
        return $query->row();
    }

    public function getRiwayatByMobilId($idmobil){
        $this->dbpinjam->where('idmobil', $idmobil);
        return $this->dbpinjam->get('riwayat_servis');
    }

    public function getById($id) {
        // Query untuk mengambil data berdasarkan ID
        $this->dbpinjam->where('id', $id);
        $query = $this->dbpinjam->get('riwayat_servis'); // 
    
        
        if ($query->num_rows() > 0) {
            return $query->row_array(); // Mengembalikan hasil sebagai array
        } else {
            return false; // Mengembalikan false jika data tidak ditemukan
        }
    }


    public function getSPById($id)
{
    $this->dbpinjam->select('riwayat_servis.*, mobil.jenis, mobil.nopol, mobil.nmerk');
    $this->dbpinjam->from('riwayat_servis');
    $this->dbpinjam->join('mobil', 'riwayat_servis.idmobil = mobil.id');
    $this->dbpinjam->where('riwayat_servis.id', $id);
    $query = $this->dbpinjam->get();
    return $query->result();
}



    


    
}
?>
