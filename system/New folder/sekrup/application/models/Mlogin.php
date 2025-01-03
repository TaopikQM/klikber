<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mlogin extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		//$this->load->library('encrypt');
		# code...
	}
	
	function fin($us,$pass,$ipk){
		$return=null;
		$nwpas=sha1($pass);
		$d="4dm1n_sekre"; // untuk sekretariat	
		$tpp=base64_encode($d);

		$q="select * from admin where st=1 AND nm=? AND pass=? AND tipe=? LIMIT 1";
		$dat = array($us ,$nwpas,$tpp);
		$qu=$this->db->query($q,$dat);


		if ($qu->num_rows()>0) {
			$row=$qu->row();
			$hk=$row->hk;
			$bv=explode("_", base64_decode($hk));

			$ihk=base64_encode('userxxx');
			$g=base64_encode('id');
			if($row->pass==$nwpas){
				$dt = array('user' =>$us ,$ihk=> TRUE, $g => base64_encode($row->id),'hk'=>base64_encode($bv[4]) );
				$this->session->set_userdata($dt);
				
				
				$qs="UPDATE admin SET ip=? WHERE id=? LIMIT 1";
				$sdat = array($ipk ,$row->id );
				$qus=$this->db->query($qs,$sdat);		
				if ($qus) {
					$return=TRUE;
				}else{
					$return=FALSE;
				}
				
			}else{
				$return=FALSE;
			}
		}
		else{
			$return=FALSE;
		}

		return $return;
	}

	function fin_penilai($us,$pass,$ipk){
		$return=null;
		$nwpas=sha1($pass);
		$c=$us."_penjajah_0"; //0 untuk super admin hak akses
		$d="4dm1n_penilai_1"; //1 untuk jenis prakom

	
		$tpp=base64_encode($d);

		$q="select * from admin where st=1 AND nm=? AND pass=? AND tipe=? LIMIT 1";
		$dat = array($us ,$nwpas,$tpp);
		$qu=$this->db->query($q,$dat);
		if ($qu->num_rows()>0) {
			
			$row=$qu->row();
			$row->pass;
			if($row->pass==$nwpas){
				$ihk=base64_encode('atijh');
				$g=base64_encode('id');
				$nwid=base64_encode($row->id);

				$dt = array('user' =>$us ,$ihk=> TRUE, $g => $nwid );
				$this->session->set_userdata($dt);
				
				
				
				$qs="UPDATE admin SET ip=? WHERE id=? LIMIT 1";
				$sdat = array($ipk ,$row->id );
				$qus=$this->db->query($qs,$sdat);		
				if ($qus) {
					$return=TRUE;
				}else{
					$return=FALSE;
				}
				
			}else{
				$return=FALSE;
			}
		}else{
			$return=FALSE;
		}

		return $return;
	}

}
