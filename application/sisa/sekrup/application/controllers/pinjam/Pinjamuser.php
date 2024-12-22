<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pinjamuser extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		/*$hjk=$this->session->userdata(base64_encode('jajahan'));
		$sec=FALSE;
		foreach ($hjk as $kaa) {
			if ($kaa[base64_encode('apli')]==base64_encode('1_morsip')) {
				$sec=TRUE;
			}
		}*/
		$sec=$this->session->userdata('idus');
		if ($sec == NULL) {
			redirect('landing/menu');
		}
		date_default_timezone_set("Asia/Jakarta");
		$this->load->model('pinjam/Muser');
		$this->load->library('form_validation');
		$this->load->library('encryption');
		
		$this->encryption->initialize(
	        array(
	                'cipher' => 'aes-128',
	                'mode' => 'ctr',
	                'key' => 'HJKHASJKD^**&&*(NJSHAHIDAsdfsa'
	        )
		);
		date_default_timezone_set("Asia/Jakarta");
		
	}
	public function index(){	
		//$this->konten();
		$ghj=$this->load->view('pinjam/page/user/awal','',TRUE);
		$this->konten($ghj);
	}
	function konten($value=''){
		$data['konten']=$value;
		$this->load->view('pinjam/homeuser',$data);
	}
	function get_client_ip() {
	    $ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	       $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}
	function daftarmobil(){
		$data['datamobil']=$this->Muser->get_data_mobil()->result();
		$ghj=$this->load->view('pinjam/page/user/v_daftarmobil',$data,TRUE);
		$this->konten($ghj);
	}

/*
	function get_item_by_kode($kode){
		if ($kode=="a1") {
			$in=$this->Muser->get_mobil_redy()->result();
		}elseif ($kode=="a2") {
			$in=$this->Muser->get_ruangan_redy()->result();
		}elseif ($kode=="a3"){ //kode a3
			$in=$this->Muser->get_alat_redy()->result();
		}else{
			$in=NULL;
		}
		return $in;
	}*/
	function get_item_by_js($data){
		$kode=$data['kode'];
		if ($kode=="a1") {
			$in=$this->Muser->get_mobil_redy($data)->result();
		}elseif ($kode=="a2") {
			$in=$this->Muser->get_ruangan_redy($data)->result();
		}elseif ($kode=="a3"){ //kode a3
			$in=$this->Muser->get_alat_redy($data)->result();
		}else{
			$in=NULL;
		}
		return $in;
	}
	function input(){
		$this->load->helper('spt');
		$data['namaspt']=getNamaSPT();
		//$data=NULL;
		$ghj=$this->load->view('pinjam/page/in-pinjam',$data,TRUE);
		$this->konten($ghj);
	}
	function item(){ // bata; dipakai
		$data=$this->input->post(NULL,TRUE);
		$hh=$this->get_item_by_js($data);
		echo "<option value=''>Plih Item</option>";
		if ($data['kode']=="a1") {	
			foreach ($hh as $key ) { 
				echo "<option value=".$key->id.">".$key->nmerk."-".$key->nopol."</option>";
			}
		}elseif ($data['kode']=="a2") {
			foreach ($hh as $key ) { 
				echo "<option value=".$key->id.">".$key->nmruang."(".$key->kapasi." orang)</option>";
			}
		}elseif ($data['kode']=="a3"){ //kode a3
			foreach ($hh as $key ) { 
				echo "<option value=".$key->id.">".$key->nmbarang."</option>";
			}
		}else{
				echo "<option value=NULL>Data Tidak Ditemukan</option>";
		}
		//print_r($data);
	}
	function item_mobil(){
		$data=$this->input->post(NULL,TRUE);
		//$in=$this->Muser->get_mobil_redy_js($data);
		$folder = base_url()."harta/pinjam/datamobil/";
		
		$in=$this->Muser->get_mobil_redy_js($data)->result();
		/*echo "<pre>";
		print_r($in);
		echo "</pre>";*/
		$aw=0;
		echo '<script src="'.base_url().'harta/morsip/assets/bundles/owlcarousel2/dist/owl.carousel.min.js"></script>';
  		echo '<script src="'.base_url().'harta/morsip/assets/js/page/owl-carousel.js"></script>';
        echo '<div class="row gutters-sm">';
		foreach ($in as $key){
			$hbg=str_replace(" ","",$key->nopol);
			$folder = "harta/pinjam/datamobil/".$hbg;
        	$handle =  glob($folder."/*.*");
        	echo '<div class="col-4 col-sm-2">
	        <label class="imagecheck mb-4">
	        			<input type="radio" name="itm" value="'.$key->id.'" class="imagecheck-input" required="required">
	                    
	                    <span class="imagecheck-figure">
	        		<div id="carouselExampleIndicators'.$aw.'" class="carousel slide" data-ride="carousel">            
	                      <div class="carousel-inner">';
	                      	for ($ib=0; $ib < count($handle) ; $ib++) { 
	                      		$drw ="";
		                        if ($ib==0) {
		                        	$drw="active";
		                        }
		                        echo '<div class="carousel-item '.$drw.'">
		                          <img class="d-block w-100" src="'.base_url().$handle[$ib].'" alt="'.$key->nopol.'">
		                          		<div class="carousel-caption d-none d-md-block">
		                          			<span class="badge badge-primary">'.$key->nopol.'</span>
							           	</div>
		                        </div>';
	                      	}
	                      echo '</div>
	                      <a class="carousel-control-prev" href="#carouselExampleIndicators'.$aw.'" role="button"
	                        data-slide="prev">
	                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
	                        <span class="sr-only">Previous</span>
	                      </a>
	                      <a class="carousel-control-next" href="#carouselExampleIndicators'.$aw.'" role="button"
	                        data-slide="next">
	                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
	                        <span class="sr-only">Next</span>
	                      </a>
	                </div>
	                </span>
	              </label>
	            </div>';
	          
        	$aw++;
		}
        
        

	}
	function save_pinjam(){
		$data=$this->input->post(NULL,TRUE);
		$data['idadm']=base64_decode($this->session->userdata('idus'));
		$gh=$this->Muser->get_max_nomor()->row_array();
		$nom=(int)$gh['nomor']+1;
		$cb = array('1' =>'I' ,'2' =>'II','3' =>'III','4' =>'IV','5' =>'V','6' =>'VI','7' =>'VII','8' =>'VIII','9' =>'IX','10' =>'X','11' =>'XI','12' =>'XII' );
		$data['nosur']=sprintf("%04d", $nom)."/PINJAM-ASET/".$cb[date('n')]."/".date('n')."-".date('j')."-".date('Y');
		$data['ip']=$this->get_client_ip();


		/*$kode=$data['jns'];
		if ($kode=="a1") {
			$in=$this->Muser->save_pinjam_mobil($data);
		}elseif ($kode=="a2") {
			$in=$this->Muser->save_pinjam_ruangan($data);
		}elseif ($kode=="a3"){ //kode a3
			$in=$this->Muser->save_pinjam_alat($data);
		}else{
			$in=NULL;
		}*/
		$in=$this->Muser->save_peminjaman($data);
		if($in){
				$not = array(
					'tipe' => 1,
					'isi' => " Ajuan Peminjaman Sukses"
					  );
					$this->session->set_flashdata('notif',$not );
					redirect('pinjam/input');
			}else{
				$not = array(
						'tipe' => 3,
						'isi' => "Ajuan Peminjaman Gagal"
						  );
					$this->session->set_flashdata('notif',$not );
					redirect('pinjam/input');
			}

		/*echo "<pre>";
		print_r($data);
		echo "</pre>";
*/
	}
	function cetak_pinjam($d){
		$this->load->helper('pinjam');
		$this->load->helper('spt');
		$this->load->library('fpdf');
		$ida=str_replace(array('-','_','~'),array('+','/','='),$d);
		$d=base64_decode($this->encryption->decrypt($ida));
		$tipe=$this->Muser->get_data_peminjaman_id($d)->row_array();
		if ($tipe['jnspinjam']=='a1') {
			$data['dataitem']=$this->Muser->get_data_pinjam_mobil_by_id($d)->row_array();
		}
		else if ($tipe['jnspinjam']=='a2') {
			$data['dataitem']=$this->Muser->get_data_ruang_id($tipe['itmpinjam'])->row_array();
		}
		else if ($tipe['jnspinjam']=='a3') {
			$data['dataitem']=$this->Muser->get_data_alat_id($tipe['itmpinjam'])->row_array();
		}else{
			$data['dataitem']=NULL;
		}


		$ghj=$this->load->view('pinjam/page/v_pdf_pinjam',$data);
		$this->konten($ghj);

	}
	function data_peminjaman(){
		
		$data['dataruangan']=$this->Muser->get_data_pinjam_ruangan()->result();
		$data['dataalat']=$this->Muser->get_data_pinjam_alat()->result();

		
	}
	function mobil(){
		$this->load->helper('pinjam');
		$this->load->helper('spt');

		$data['datamobil']=$this->Muser->get_data_pinjam_mobil()->result();
		$ghj=$this->load->view('pinjam/page/v_data_pinjam_mobil',$data,TRUE);
		$this->konten($ghj);		
		# code...
	}
	function edit_pinjaman_mobil($d){
		$this->load->helper('spt');
		$data['namaspt']=getNamaSPT();

		$ida=str_replace(array('-','_','~'),array('+','/','='),$d);
		$d=base64_decode($this->encryption->decrypt($ida));
		
		$data['edit']=$this->Muser->get_data_pinjam_mobil_by_id($d)->result();
		foreach ($data['edit'] as $key) {
			$rh['ini']=date('Y-m-d',$key->tglin);
			$rh['oto']=date('Y-m-d',$key->tglot);
			$rh['tjn']=$key->mbl_tujuan;
			$rh['prl']=$key->mbl_perlu;
		}
		$data['lismobil']=$this->Muser->get_mobil_redy_js($rh)->result();
		$ghj=$this->load->view('pinjam/page/v_edit_pinjam_mobil',$data,TRUE);
		$this->konten($ghj);
		/*echo "<pre>";
		print_r($data['lismobil']);
		echo "</pre>";*/
	}
	function save_e_pinjam_mobil(){
		$data=$this->input->post(NULL,TRUE);
		$data['ip']=$this->get_client_ip();
		$in=$this->Muser->s_e_pinjam_mobil($data);
		if($in){
			$not = array(
				'tipe' => 1,
				'isi' => " Edit Peminjaman Sukses"
				  );
				$this->session->set_flashdata('notif',$not );
				redirect('pinjam');
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Edit Peminjaman Gagal"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('pinjam');
		}
	}
	function hapus_pinjam_mobil(){

		$data=$this->input->post(NULL,TRUE);
        $in=$this->Muser->hapus_pinjam_mobil($data['id']);
        if($in){
    	$not = array(
			'tipe' => 1,
			'isi' => "Hapus Pinjam Mobil Sukses"
			  );
			$this->session->set_flashdata('notif',$not );
			redirect('pinjam');
        }
        else{
			$not = array(
				'tipe' => 3,
				'isi' => "Hapus Pinjam Mobil Gagal"
				  );
			$this->session->set_flashdata('notif',$not );
			redirect('pinjam');
	    }
	    /*echo "<pre>";
		print_r($data);
		echo "</pre>";*/
	}
	function ubah_status_pinjam(){
		$data=$this->input->post(NULL,TRUE);
		$in=$this->Muser->ubah_st_pinjam($data);
        if($in){
    	$not = array(
			'tipe' => 1,
			'isi' => "Ubah Status Pinjam Sukses"
			  );
			$this->session->set_flashdata('notif',$not );
			redirect('pinjam');
        }
        else{
			$not = array(
				'tipe' => 3,
				'isi' => "Ubah Status Pinjam Gagal"
				  );
			$this->session->set_flashdata('notif',$not );
			redirect('pinjam');
	    }
		
	}
	











}