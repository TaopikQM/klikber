<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DoctorModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_doctors() {
        return $this->db->get('doctors')->result();
    }

    public function add_doctor($data) {
        return $this->db->insert('doctors', $data);
    }

    public function update_doctor($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('doctors', $data);
    }

    public function delete_doctor($id) {
        return $this->db->delete('doctors', array('id' => $id));
    }
}
