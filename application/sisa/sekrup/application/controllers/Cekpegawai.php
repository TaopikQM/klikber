<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cekpegawai extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('mcekpegawai');
		$this->load->library('form_validation');
		$this->load->library('encryption');
		
		$this->encryption->initialize(
	        array(
	                'cipher' => 'aes-128',
	                'mode' => 'ctr',
	                'key' => 'HJJHJKhahsgdgIYUGKHBJKH^&*^^%^&%^*988qw7e9'
	        )
		);
		date_default_timezone_set("Asia/Jakarta");
		
	}
	public function index(){
		$dat=$this->mcekpegawai->get_pegawai()->result();

/*	echo "<pre>";	
		print_r($dat);

	echo "</pre>";*/
		$gol = array(
			'I/a' => 1, 
			'I/b' => 2, 
			'I/c' => 3, 
			'I/d' => 4, 
			'II/a' => 5, 
			'II/b' => 6, 
			'II/c' => 7, 
			'II/d' => 8, 
			'III/a' => 9, 
			'III/b' => 10, 
			'III/c' => 11, 
			'III/d' => 12, 
			'IV/a' => 13, 
			'IV/b' => 14, 
			'IV/c' => 15, 
			'IV/d' => 16, 
			'IV/e' => 17,
			'-' => '-'
		);
		$r=0;	
		foreach($dat as $k){
			$in[$r]['nip']=$this->encryption->encrypt($k->nip);
			$in[$r]['nama']=base64_encode($k->nama);
			$in[$r]['jabatan']=$k->jabatan;
			$in[$r]['gol']=$gol[$k->gol];
			$in[$r]['sortir']=$k->sortir;
			$in[$r]['status']=$k->status;
			$in[$r]['id']=$k->id;
			$r++;
		}
		$has=$this->mcekpegawai->seve_pg($in);
			echo "<pre>";	
			print_r($has);
			echo "</pre>";

	}

	function get_pg_new()
	{
		$dat=$this->mcekpegawai->get_pegawai_nw()->result();
		echo "<pre>"; 	
			foreach ($dat as $key) {
				echo base64_encode($key->id)." * ";
				echo $this->encryption->decrypt($key->nip)." * ";
				echo base64_decode($key->nama)." * ";
				echo $key->jabatan." * ";
				echo $key->gol." * ";
				echo $key->sortir." * ";
				echo $key->status."<br>";
				
			}

			echo "</pre>";
	}
	function mksptformat(){
		$dat=$this->mcekpegawai->get_spt()->result();
			/*echo "<pre>";	
				print_r($dat);

			echo "</pre>";*/

		$r=0;	
		foreach($dat as $k){
			$in['id']=$k->id;




			$hh=explode("-",$k->tgldl);
			$has=mktime(0,0,0,$hh[1],$hh[2],$hh[0]);

			$hha=explode("-",$k->tglin);
			$hasa=mktime(0,0,0,$hha[1],$hha[2],$hha[0]);

			$hhb=explode("-",$k->tglot);
			$hasb=mktime(0,0,0,$hhb[1],$hhb[2],$hhb[0]);
			

			//$in[$r]['tgldl_asli']=$k->tgldl;
			$in['tgldl']=$has;
			///$in[$r]['tgldl_konvert']=date("Y-m-d",$has);


			//$in[$r]['tglin_asli']=$k->tglin;
			$in['tglin']=$hasa;

			//$in[$r]['tglot_asli']=$k->tglot;
			$in['tglot']=$hasb;
			$in['dasrut']=base64_encode($k->dasrut);
			$in['keperluan']=base64_encode($k->keperluan);

			$zh=explode("-",trim($k->nmdl,"-"));$tmpk="-";
			for ($i=0; $i <count($zh) ; $i++) { 
				$tmpk=$tmpk.base64_encode($zh[$i])."-";
			}
			$in['nmdl']=$tmpk;
			$hat=$this->mcekpegawai->save_spt($in);

			/*if ($in[$r]['tglin']==$in[$r]['tglot']) {
				$in[$r]['Hasil Banding']="SAMA";
			}elseif ($in[$r]['tglin']<=$in[$r]['tglot']) {
				$in[$r]['Hasil Banding']="DURASI BENAR";
			}
			elseif ($in[$r]['tglin']>=$in[$r]['tglot']) {
				$in[$r]['Hasil Banding']="GALAT GAAOR ERRROOOORRR";
			}else{
				$in[$r]['Hasil Banding']="EROR";
			}

			$gh=abs($in[$r]['tglot']-$in[$r]['tglin']);
			$in[$r]['Berapa Lama']=ceil($gh/86400); //harus plus 1 hari jika tidak nol
*/
			$r++;
		}
		echo $hat."<br>";
		/*echo "<pre>";
		print_r($in);
		echo "</pre>";*/
	}
	function updatejajahan()
	{
		$data['id_apli']="MV9tb3JzaXA=";
		$data['hk']="cfbc195b018fee2b27937947cb7471e1c9b2e5a17fef66a1aad6b2cae4857a800a6f188af2e43140743b226422c25638c7ca24c5f1c70e041868641f0b74ca8e6yL/wt+HSi+y/eeR6YrgPA3cwd3nBXrn3w5R6kCKi7CqqA==";
		
		$dat=$this->mcekpegawai->jajahan($data);

		echo "HASIL HALAMAN JAJAHAN _>".$dat;
	}












}

?>