<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MObat extends CI_Model {
    public function __construct()
    {
		parent::__construct();
		$this->db = $this->load->database('klinik', TRUE);
	}


    public function get_all() {
        return $this->db->get('obat')->result();
    }

    public function insert($data) {
        return $this->db->insert('obat', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id); 
        return $this->db->update('obat', $data);
    }
    

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('obat');
    } 

    function get_by_id($id){
		$this->db->where('id', $id);
		return $this->db->get('obat');

	}
    public function count_obat() {
        return $this->db->count_all_results('obat');
      }
} 