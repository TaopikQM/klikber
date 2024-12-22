<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Muser extends CI_Model {

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
	function get_data_mobil_id($id){
		$this->dbpinjam->select('*');
		$this->dbpinjam->from('mobil');
		$this->dbpinjam->where('id',$id);
		$dff = $this->dbpinjam->get();
		return $dff;
	}

	function get_data_mobil(){
		$this->dbpinjam->order_by('id', 'desc');
		return $this->dbpinjam->get('mobil');
	}
	function get_data_mobil_by_id($id){
		$this->dbpinjam->where('id', $id);
		return $this->dbpinjam->get('mobil');
	}
	function save_mobil($data){
		$tb='mobil';
		$hg ="-";
		for ($i=1; $i <5 ; $i++) { 
			if (isset($data['perlu'][$i]) && $data['perlu'][$i]!=NULL) {
				$hg=$hg.$data['perlu'][$i]."-";
			}
		}
		
		$ht ="-";
		for ($ia=1; $ia <5 ; $ia++) { 
			if (isset($data['tjn'][$ia]) && $data['tjn'][$ia]!=NULL) {
				$ht=$ht.$data['tjn'][$ia]."-";
			}
		}
		$inn = array(
			'nopol' => $data['nopol'], 
			'nmerk' => $data['merk'], 
			'pjk' => $data['blnpjk']."/".$data['tglpjk'], 
			'status' => $data['stsken'], 
			'nmfile' => $data['nmfile'], 
			'r_perlu' => $hg, 
			'r_tujuan' => $ht, 
			'hak' => $data['hak'], 
			'ket' => $data['ket']
		);
		$dh=$this->dbpinjam->insert($tb,$inn);
		return $dh;	
	}
	function save_e_mobil($data){
		$tb='mobil';
		$hg ="-";
		for ($i=1; $i <5 ; $i++) { 
			if (isset($data['perlu'][$i]) && $data['perlu'][$i]!=NULL) {
				$hg=$hg.$data['perlu'][$i]."-";
			}
		}
		
		$ht ="-";
		for ($ia=1; $ia <5 ; $ia++) { 
			if (isset($data['tjn'][$ia]) && $data['tjn'][$ia]!=NULL) {
				$ht=$ht.$data['tjn'][$ia]."-";
			}
		}
		$inn = array(
			'nopol' => $data['nopol'], 
			'nmerk' => $data['merk'], 
			'pjk' => $data['blnpjk']."/".$data['tglpjk'], 
			'status' => $data['stsken'], 
			'nmfile' => $data['nmdoklama'], 
			'r_perlu' => $hg, 
			'r_tujuan' => $ht, 
			'hak' => $data['hak'], 
			'ket' => $data['ket']
		);
		$this->dbpinjam->set($inn);
		$this->dbpinjam->where('id', $data['id']);
		$dh=$this->dbpinjam->update($tb); 
		return $dh;	
	}
	function hapus_mobil($id){
		$this->dbpinjam->where('id', $id);
		return $this->dbpinjam->delete('mobil');

	}


}