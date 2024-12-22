<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mruang extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->dbpinjam = $this->load->database('pinjam', TRUE);
	}

	function is_unique($tabel,$field, $value){
		
		$this->dbpinjam->where($field, $value);
        $query = $this->dbpinjam->get($tabel);
        return $query->num_rows() === 0;
		

	}
	function get_data_ruang(){
		$this->dbpinjam->order_by('id', 'desc');
		return $this->dbpinjam->get('ruangan');
	}
	function get_all_ruangan() {
        $this->dbpinjam->select('*');
        $this->dbpinjam->from('ruangan');
        $query = $this->dbpinjam->get();
        return $query->result();
    }

	function save_ruang($data){
		/*echo "<pre>";
		print_r($data);
		echo "</pre>";*/
		$tb='ruangan';
		$inn = array(
			'nmruang' => $data['narung'], 
			'lantai' => $data['lantai'], 
			'kapasi' => $data['kapsi'], 
			'status' => $data['stsru'], 
			'nmfile' => $data['nmfile'], 
			'ket' => $data['ket']
		);
		$dh=$this->dbpinjam->insert($tb,$inn);
		return $dh;	
	}
	function get_data_ruang_by_id($id){
		$this->dbpinjam->where('id', $id);
		return $this->dbpinjam->get('ruangan');

	}
	function save_e_ruang($data){
		$tb='ruangan';
		
		$inn = array(
			'nmruang' => $data['narung'], 
			'lantai' => $data['lantai'], 
			'kapasi' => $data['kapsi'], 
			'status' => $data['stsru'], 
			'nmfile' => $data['nmdoklama'], 
			'ket' => $data['ket']
		);
		$this->dbpinjam->set($inn);
		$this->dbpinjam->where('id', $data['id']);
		$dh=$this->dbpinjam->update($tb);
		
		return $dh;
	}
	function hapus($id){
		$this->dbpinjam->where('id', $id);
		return $this->dbpinjam->delete('ruangan');
	}

}