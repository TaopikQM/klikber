<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MRole extends CI_Model {
    
    public function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('klinik', TRUE);
	}

    public function get_all() {
        return $this->db->get('role')->result();
    }

    public function insert($data) {
        return $this->db->insert('role', $data);
    } 

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('role', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('role');
    }

    function get_by_id($id){
		$this->db->where('id', $id);
		return $this->db->get('role');

	}
    public function count_role() {
        return $this->db->count_all_results('role');
      }
}