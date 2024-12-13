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

} 