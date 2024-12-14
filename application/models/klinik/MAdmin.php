<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MAdmin extends CI_Model {
    public function __construct()
    {
		parent::__construct();
		$this->db = $this->load->database('klinik', TRUE);
	}


    public function get_all() {
        return $this->db->get('admin')->result();
    }

    public function inserta($data) {
        return $this->db->insert('admin', $data);
    }
    public function insert($data) {
        $this->db->insert('admin', $data);
        return $this->db->insert_id(); // Mengembalikan ID terakhir
    }
    

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('admin', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('admin');
    }
    public function get_last_id_admin() {
        // Ambil ID pasien terakhir berdasarkan ID yang terbesar
        $this->db->select_max('id');  // Ambil nilai terbesar dari kolom id_pasien
        $query = $this->db->get('admin');
        
        // Ambil hasil query
        $result = $query->row();
        return $result ? $result->id : 0;  // Jika tidak ada data, return 0
    }
    

    function get_by_id($id){
		$this->db->where('id', $id);
		return $this->db->get('admin');

	}
     public function get_by_id_admin($id)
    {
        $this->db->select('admin.*')
                ->from('admin')
                ->where('admin.id', $id);
        return $this->db->get()->row();
    }
    
    public function get_aset_id($id) {
        return $this->db->get_where('admin', ['id' => $id])->row_array();
    }
    public function count_admin() {
        return $this->db->count_all_results('admin');
    }

}
