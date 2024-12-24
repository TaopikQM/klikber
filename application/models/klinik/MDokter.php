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
    public function get_by_id_dokter($id)
    {
        $this->db->select('dokter.*')
                ->from('dokter')
                ->where('dokter.id', $id);
        return $this->db->get()->row();
    }

    public function get_aset_id($id) {
        return $this->db->get_where('dokter', ['id' => $id])->row_array();
    }

    public function count_dokter() {
        return $this->db->count_all_results('dokter');
      }

    
} 