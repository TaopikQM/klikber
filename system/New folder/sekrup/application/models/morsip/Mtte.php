<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mtte extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}
	function get_c_ajuan($th,$tbl,$id){
		$tabel=$tbl.$th;
		$query=  $this->db->query("SELECT COUNT(id) as hasnil from $tabel WHERE kdbid = ? AND a != 3",array($id));
		return $query;	
	}
	function get_data_spt($th,$kdbid,$limit){
		$tabel="spt".$th;
		if ($limit<=1) {
			$lim=1000;
			$of=0;
		}else{
			$lim=$limit*1000;
			$of=$lim-999;
		}
		$q="SELECT n.*, b.n_bid as bidang FROM $tabel n
				LEFT JOIN bidang b
				ON n.kdbid=b.kode 		
				WHERE n.kdbid = ? AND n.a != 3
				ORDER BY n.id DESC LIMIT ? OFFSET ?";
			$query =  $this->db->query($q,array($kdbid,$lim,$of));
		return $query;	
	}
	function get_spt($id,$tahun){
		$tabel="spt".$tahun;
		$this->db->where('id',$id);
		$in = $this->db->get($tabel);
		return $in;
	}
	function get_dasur(){
		$this->db->where('sts','1');
		$in = $this->db->get('mdasrut');
		return $in;
	}
	function get_untuk(){
		$this->db->where('status','1');
		$in = $this->db->get('untuk');
		return $in;
	}
	function hapus_spt($data){
		
		$tb='spt'.$data['tahun'];
		$inn = array(
			'notif' => 0,
		    'a' => 3
		);
		$this->db->set($inn);
		$this->db->where('id',$data['id']);
		$in=$this->db->update($tb);


		$tb1='logver'.$data['tahun'];
		$in1 = array(
			'id_spt' => $data['id'],
			'kdbid' => $data['kdbid'],
			'status' => 3
		);
		$in1=$this->db->insert($tb1,$in1);
		$tb2='tolak'.$data['tahun'];


		$in2 = array(
			'id_s' => $data['id'],
		    'kdbid' => $data['kdbid'],
		    'isi' => $data['altolak']
		);
		$in2=$this->db->insert($tb2,$in2);
		
		if ($in && $in1 && $in2) {
			return TRUE;
		}else{
			return FALSE;
		}

	}
	function getAdminByid($id){
		$tabel="adminver";
		$this->db->select('pass');
		$this->db->where('id',$id);
		$in = $this->db->get($tabel);
		return $in;
	}
	function acc_tte($data){
		
	}





}
