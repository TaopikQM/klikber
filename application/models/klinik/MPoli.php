<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MPoli extends CI_Model {

    public function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('klinik', TRUE);
	}

    public function get_all() {
        return $this->db->get('poli')->result();
    }

    public function insert($data) {
        return $this->db->insert('poli', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('poli', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('poli');
    }

    function get_by_id($id){
		$this->db->where('id', $id);
		return $this->db->get('poli');

	}
    public function count_poli() {
        return $this->db->count_all_results('poli');
      }
}