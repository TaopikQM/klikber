<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mcekpegawai extends CI_Model {

	function get_pegawai(){
		$query = $this->db->get('pegawai23');
		return $query;	
	}
	function get_spt(){
		$query = $this->db->get('spt23');
		return $query;	
	}
	function get_pegawai_nw(){
		$query = $this->db->get('pegawai2023');
		return $query;	
	}
	function seve_pg($data){
		for ($i=0; $i <count($data) ; $i++) { 
			$dat = array(
	        'id' => $data[$i]['id'],
	        'nip' => $data[$i]['nip'],
	        'nama' => $data[$i]['nama'],
	        'jabatan' => $data[$i]['jabatan'],
	        'gol' => $data[$i]['gol'],
	        'sortir' => $data[$i]['sortir'],
	        'status' => $data[$i]['status']
			);
			$hs=$this->db->insert('pegawai2023', $dat);			
		}
		return $hs;


	}
	function save_spt($v)
	{
		$inn = array(
			'tgldl' => $v['tgldl'], 
			'tglin' => $v['tglin'],
			'tglot' => $v['tglot'],
			'dasrut' => $v['dasrut'],
			'keperluan' => $v['keperluan'],
			'nmdl' => $v['nmdl']
		);
		$this->db->set($inn);
		$this->db->where('id',$v['id']);
		$in=$this->db->update('spt2023');
		return $in;

		// code...
	}
	function jajahan($data){
		for ($i=1; $i <31 ; $i++) { 
			$inn = array(
				'id' => $i, 
				'id_apli' => $data['id_apli'],
				'hk' =>$this->encryption->encrypt("admin_1_adminbiasa"),
				'idus' =>base64_encode($i)
				);
			$in=$this->db->insert('hk_apli', $inn);
		}

		return $in;
	}
}
?>