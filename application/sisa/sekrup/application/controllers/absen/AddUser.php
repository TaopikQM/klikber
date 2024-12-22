<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AddUser extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('absen/MAddUsers');
    }
    public function index() {
        
        // $this->load->view('absen/face-register');
        // $this->load->view('absen/userTabel');
        
        
        // $this->load->view('absen/VdataAttendances');
        $this->load->view('absen/data');
        
    } 

    public function add() {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $nim = $this->input->post('nim');
            $name = $this->input->post('name');

            if (empty($nim) || empty($name)) {
                $this->output->set_content_type('application/json')->set_output(json_encode(['message' => 'NIM and name are required.']), 400);
                return;
            }

            if ($this->MAddUsers->check_existing_name($name)) {
                $this->output->set_content_type('application/json')->set_output(json_encode(['message' => 'Name already exists.']), 400);
                return;
            }

            if ($this->MAddUsers->check_existing_nim($nim)) {
                $this->output->set_content_type('application/json')->set_output(json_encode(['message' => 'NIM already exists.']), 400);
                return;
            }

            $newId = $this->MAddUsers->generate_base64_id();

            $data = [
                'id' => $newId,
                'nim' => $nim,
                'name' => $name,
                'status' => 'inactive'
            ];

            if ($this->MAddUsers->add_user($data)) {
                $this->output->set_content_type('application/json')->set_output(json_encode(['message' => 'User added successfully!']), 200);
            } else {
                $this->output->set_content_type('application/json')->set_output(json_encode(['message' => 'Database error']), 500);
            }
        } else {
            $this->output->set_header('Allow: POST');
            $this->output->set_status_header(405)->set_output('Method Not Allowed');
        }
    }
}
