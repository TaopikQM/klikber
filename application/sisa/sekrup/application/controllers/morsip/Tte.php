<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tte extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$hjk=$this->session->userdata(base64_encode('ttein'));
		$kod=$this->session->userdata(base64_encode('kod'));
		$idvr=$this->session->userdata(base64_encode('idver'));
		$this->load->library('encryption');
		$this->encryption->initialize(
	        array(
	                'cipher' => 'aes-128',
	                'mode' => 'ctr',
	                'key' => 'HJJHJKhahsgdgIYUGKHBJKH^&*^^%^&%^*988qw7e9'
	        )
		);
		$sec=FALSE;
		$has=explode("||",base64_decode($hjk));
		if ($has[0] != null || $has[1] != null) {
			if ( $this->encryption->decrypt($idvr) == $has[1] && base64_decode($kod) == $has[0] ) {
				$sec=TRUE;
			}
			else{
			redirect('landing/tte');
				$sec=FALSE;
			}			# code...
		}else{
			redirect('landing/tte');
		}

		$this->load->model('morsip/mtte');
		$this->load->library('form_validation');
		date_default_timezone_set("Asia/Jakarta");
		if ($sec != TRUE) {
			redirect('landing/tte');
		}
	}
	
	public function index(){	
		
		/*echo "INI HALAMAN TTE<br>";
		echo "<pre>";
		print_r($this->session->userdata());
		echo "</pre>";*/
		
		$this->datatte();	

	}
	function gettahun(){
		$th=$this->session->userdata('tahun');
		if ($th == null) {
			$th=date('Y');
		}
		return $th;
	}

	function konten($value=''){
		$data['konten']=$value;
		$this->load->view('tte/home',$data);
	}
	function datatte($pg=''){
		$th=$this->gettahun();
		$this->load->helper('spt');
		$tbl="spt";
		$id=base64_decode( $this->session->userdata(base64_encode('kod')) );
		$bb=$this->mtte->get_c_ajuan($th,$tbl,$id)->row();
		$data['tahun']=$th;
		$data['jumdat']=$bb->hasnil;
		$fg=$data['jumdat']/1000;
		if (filter_var($pg, FILTER_VALIDATE_INT) !== false){
			$limit=$pg;
			if ($pg>=ceil($fg)) {
			$limit=ceil($fg);
			}
		}else{
			$limit=1;
		}
		
		$data['datasur'] = $this->mtte->get_data_spt($th,$id,$limit)->result();
		$data['kode'] = $id;

		$ghj=$this->load->view('tte/page/v_data_approve',$data,TRUE);
		$this->konten($ghj);
	}
	function preview_spt($id){
		$this->load->helper('hk_admin');
		$this->load->library('fpdf');
		$th=$this->gettahun();
		$ida=str_replace(array('-','_','~'),array('+','/','='),$id);
		$fgh=base64_decode($this->encryption->decrypt($ida));
		
		$has['spt']=$this->mtte->get_spt($fgh,$th)->result();
		$has['dasur']=$this->mtte->get_dasur()->result();
		$has['mperlu']=$this->mtte->get_untuk()->result();
		$has['tahun']=$th;
		$this->load->view('morsip/page/v_preview_spt',$has);
		
	}
	function tolak_spt(){
		$data=$this->input->post(NULL,TRUE);
		$data['tahun']=$this->gettahun();
		$w=$this->mtte->hapus_spt($data);
		if($w){
			$not = array(
				'tipe' => 1,
				'isi' => "Tolak SPT Sukses"
				  );
				$this->session->set_flashdata('notif',$not );
				redirect('tte');
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Gagal Tolak SPT"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('tte');
		}
	}
	function aprovespt(){
		$data=$this->input->post(NULL,TRUE);
		print_r($data);
		$fg=explode(",", $this->input->post('ckall') );
		$ps=md5($this->input->post('passs'));
		$nwpas=sha1($ps);

		echo "<br>Ini Hasil input Pass_> ".$nwpas."<br>";
		$idadmin=$this->encryption->decrypt( $this->session->userdata(base64_encode('idver')) );
		$has=	$this->mtte->getAdminByid($idadmin)->row();
		$fp=explode(",",$data['ckall'] ); // id
		echo "<br>ini hasil";
		print_r($fp);
		echo "<br>";
		if ($data['kdbid'] != 1) { //untuk yang non tte
			if (count($fp)>1) { // unutuk ceklist
				for ($i=0; $i <count($fp) ; $i++) { 
					# code...
				}
			}else{ // unutk satu
				$kdbid=explode(".",$data['kdbid']);
				switch($kdbid){
                        case count($kdbid)>1 : $v=$hh[0];break;
                        case 2 : $v=1;break;
                        case 1 : $v=5;break; //ibu kadis telah acc
                        default  : $v=2;break;
                    }
                if ($v==5) {
                    $gh=1;
                }else {
                    $gh=4;
                }
                $df['id']=$data['ckall'];
                $df['kdbid']=$v;
                $df['a']=$gh;
			}
		}
		
		print_r($kdbid);
		echo "<br>";
		

/*
		$kdbid=$this->input->post('kdbid');
		if ($kdbid != 1) {
			$this->mtte->acc_kadis($data);
		}else{
			$this->mtte->acc_tte($data);
			
		}*/
	}











}




