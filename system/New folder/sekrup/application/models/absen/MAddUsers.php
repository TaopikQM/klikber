<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MAddUsers extends CI_Model {

    public function __construct() {
        parent::__construct();
        // $this->load->database();
        $this->dbfaces = $this->load->database('absenm', TRUE);
    
    }

    public function check_existing_name($name) {
        $this->dbfaces->where('name', $name);
        $query = $this->dbfaces->get('users');
        return $query->num_rows() > 0;
    }

    public function check_existing_nim($nim) {
        $this->dbfaces->where('nim', $nim);
        $query = $this->dbfaces->get('users');
        return $query->num_rows() > 0;
    }

    public function add_user($data) {
        return $this->dbfaces->insert('users', $data);
    }

    
    public function generate_base64_id() {
        $randomBytes = random_bytes(16); 
        return base64_encode($randomBytes); 
    }
}
