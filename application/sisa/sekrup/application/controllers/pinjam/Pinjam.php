<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pinjam extends CI_Controller {

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
		// $sec=$this->session->userdata('idus');
		// if ($sec == NULL) {
		// 	redirect('landing/menu');
		// }
		date_default_timezone_set("Asia/Jakarta");
		$this->load->model('pinjam/Mpinjam');
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
		
		$bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');
		$jnspinjam = $this->input->post('jnspinjam');

		//ini grafik
		$data['grafik_data'] = $this->Mpinjam->get_data_pinjam();
		//total data
		$data['status_summary'] = $this->Mpinjam->get_status_summary($bulan, $tahun);
		//ini kalender
		$data['events'] = $this->Mpinjam->get_peminjaman_events($jnspinjam);
		
		$dt=$this->load->view('pinjam/page/dashboard-up', $data, TRUE);
		
		$this->konten($dt); 
		
	}
	function konten($value=''){
		$data['konten']=$value;
		$this->load->view('pinjam/home',$data); 
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
/*
	function get_item_by_kode($kode){
		if ($kode=="a1") {
			$in=$this->mpinjam->get_mobil_redy()->result();
		}elseif ($kode=="a2") {
			$in=$this->mpinjam->get_ruangan_redy()->result();
		}elseif ($kode=="a3"){ //kode a3
			$in=$this->mpinjam->get_alat_redy()->result();
		}else{
			$in=NULL;
		}
		return $in;
	}*/
	function get_item_by_js($data){
		$kode=$data['kode'];
		if ($kode=="a1") {
			$in=$this->Mpinjam->get_mobil_redy($data)->result();
		}elseif ($kode=="a2") {
			$in=$this->Mpinjam->get_ruangan_redy($data)->result();
		}elseif ($kode=="a3"){ //kode a3
			$in=$this->Mpinjam->get_alat_redy($data)->result();
		}else{
			$in=NULL;
		}
		return $in;
	}
	function input(){
		$this->load->helper('spt');
		$data['namaspt']=getNamaSPT();
		// $data=NULL;
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
		//$in=$this->Mpinjam->get_mobil_redy_js($data);
		$folder = base_url()."harta/pinjam/datamobil/";
		
		$in=$this->Mpinjam->get_mobil_redy_js($data)->result();
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

	function item_ruang() // Renamed to item_ruang
    {
		$data = $this->input->post(NULL, TRUE);
		
		$folder = base_url()."harta/pinjam/dataruang/";
		$in = $this->Mpinjam->get_ruangan_redy_js($data)->result();
		
        $aw = 0;
		echo '<script src="'.base_url().'harta/morsip/assets/bundles/owlcarousel2/dist/owl.carousel.min.js"></script>';
  		echo '<script src="'.base_url().'harta/morsip/assets/js/page/owl-carousel.js"></script>';
        echo '<div class="row gutters-sm">';
		
        foreach ($in as $key) {
			$hbg=str_replace(" ","",$key->nmruang);
            $folder = "harta/pinjam/dataruang/".strtoupper($hbg);
            $handle = glob($folder . "/*.*");

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
							<img class="d-block w-100" src="'.base_url().$handle[$ib].'" alt="'.$key->nmruang.'">
									<div class="carousel-caption d-none d-md-block">
										<span class="badge badge-primary">'.$key->nmruang.'</span>
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

	function item_alat() // Renamed to item_alat
    {
		
		$data = $this->input->post(NULL, TRUE);
        
		$folder = base_url()."harta/pinjam/dataalat/";
		$in = $this->Mpinjam->get_alat_redy_js($data)->result();
        
        
        $aw = 0;
		echo '<script src="'.base_url().'harta/morsip/assets/bundles/owlcarousel2/dist/owl.carousel.min.js"></script>';
  		echo '<script src="'.base_url().'harta/morsip/assets/js/page/owl-carousel.js"></script>';
        echo '<div class="row gutters-sm">';
		
        foreach ($in as $key) {
			$hbg=str_replace(" ","",$key->nmbarang);
            $folder = "harta/pinjam/dataalat/".strtoupper($hbg);
            $handle = glob($folder . "/*.*");

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
							<img class="d-block w-100" src="'.base_url().$handle[$ib].'" alt="'.$key->nmbarang.'">
									<div class="carousel-caption d-none d-md-block">
										<span class="badge badge-primary">'.$key->nmbarang.'</span>
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
		$gh=$this->Mpinjam->get_max_nomor()->row_array();
		$nom=(int)$gh['nomor']+1;
		$cb = array('1' =>'I' ,'2' =>'II','3' =>'III','4' =>'IV','5' =>'V','6' =>'VI','7' =>'VII','8' =>'VIII','9' =>'IX','10' =>'X','11' =>'XI','12' =>'XII' );
		$data['nosur']=sprintf("%04d", $nom)."/PINJAM-ASET/".$cb[date('n')]."/".date('n')."-".date('j')."-".date('Y');
		$data['ip']=$this->get_client_ip();
		/*$kode=$data['jns'];
		if ($kode=="a1") { 
			$in=$this->Mpinjam->save_pinjam_mobil($data);
		}elseif ($kode=="a2") {
			$in=$this->Mpinjam->save_pinjam_ruangan($data);
		}elseif ($kode=="a3"){ //kode a3
			$in=$this->Mpinjam->save_pinjam_alat($data);
		}else{
			$in=NULL;
		}*/
		$in=$this->Mpinjam->save_peminjaman($data);
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
		$tipe=$this->Mpinjam->get_data_peminjaman_id($d)->row_array();
		if ($tipe['jnspinjam']=='a1') {
			$data['dataitem']=$this->Mpinjam->get_data_pinjam_mobil_by_id($d)->row_array();
		}
		else{
			$data['dataitem']=NULL;
		}

		$ghj=$this->load->view('pinjam/page/v_pdf_pinjam',$data);
		//$this->konten($ghj);

	}
	function cetak_pinjam_ruang($d){
		$this->load->helper('pinjam');
		$this->load->helper('spt');
		$this->load->library('fpdf');
		$ida=str_replace(array('-','_','~'),array('+','/','='),$d);
		$d=base64_decode($this->encryption->decrypt($ida));
		$tipe=$this->Mpinjam->get_data_peminjaman_id($d)->row_array();
		if ($tipe['jnspinjam']=='a2') {
			$data['dataitem']=$this->Mpinjam->get_data_pinjam_ruang_by_nopin($tipe['nopinjam'])->row_array();
		}else{
			$data['dataitem']=NULL;
		}
		$ghj=$this->load->view('pinjam/page/v_pdf_pinjam_ruang',$data);
		//$this->konten($ghj);

	}
	function cetak_pinjam_alat($d){
		$this->load->helper('pinjam');
		$this->load->helper('spt');
		$this->load->library('fpdf');
		$ida=str_replace(array('-','_','~'),array('+','/','='),$d);
		$d=base64_decode($this->encryption->decrypt($ida));
		$tipe=$this->Mpinjam->get_data_peminjaman_id($d)->row_array();
		if ($tipe['jnspinjam']=='a3') {
			$data['dataitem']=$this->Mpinjam->get_data_pinjam_alat_by_nopin($tipe['nopinjam'])->row_array();
		}else{
			$data['dataitem']=NULL;
		}
		$ghj=$this->load->view('pinjam/page/v_pdf_pinjam_alat',$data);
		//$this->konten($ghj);

	}

	function data_peminjaman(){
		
		$data['dataruangan']=$this->Mpinjam->get_data_pinjam_ruangan()->result();
		$data['dataalat']=$this->Mpinjam->get_data_pinjam_alat()->result();

		
	}
	function mobil(){ 
		$this->load->helper('pinjam');
		$this->load->helper('spt');

		$data['datamobil']=$this->Mpinjam->get_data_pinjam_mobil()->result();
		$ghj=$this->load->view('pinjam/page/v_data_pinjam_mobil',$data,TRUE);
		$this->konten($ghj);		
		# code...
	}
	function ruangan(){
		$this->load->helper('pinjam');
		$this->load->helper('spt');

		$data['dataruangan']=$this->Mpinjam->get_data_pinjam_ruang()->result();
		$ghj=$this->load->view('pinjam/page/v_data_pinjam_ruang',$data,TRUE);
		$this->konten($ghj);		
		# code...
	}
	function alat(){
		$this->load->helper('pinjam');
		$this->load->helper('spt');

		$data['dataalat']=$this->Mpinjam->get_data_pinjam_alat()->result();
		$ghj=$this->load->view('pinjam/page/v_data_pinjam_alat',$data,TRUE);
		$this->konten($ghj);		
		# code...
	}
	//Mobil
	function edit_pinjaman_mobil($d){
		$this->load->helper('spt');
		$data['namaspt']=getNamaSPT();

		$ida=str_replace(array('-','_','~'),array('+','/','='),$d);
		$d=base64_decode($this->encryption->decrypt($ida));
		
		$data['edit']=$this->Mpinjam->get_data_pinjam_mobil_by_id($d)->result();
		foreach ($data['edit'] as $key) {
			$rh['ini']=date('Y-m-d',$key->tglin);
			$rh['oto']=date('Y-m-d',$key->tglot);
			$rh['tjn']=$key->mbl_tujuan;
			$rh['prl']=$key->mbl_perlu;
		}
		$data['lismobil']=$this->Mpinjam->get_mobil_redy_js($rh)->result();
		$ghj=$this->load->view('pinjam/page/v_edit_pinjam_mobil',$data,TRUE);
		$this->konten($ghj);
	}
	
	function save_e_pinjam_mobil(){
		$data=$this->input->post(NULL,TRUE);
		$data['ip']=$this->get_client_ip();
		$in=$this->Mpinjam->s_e_pinjam_mobil($data);
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
        $in=$this->Mpinjam->hapus_pinjam_mobil($data['idpinjam']);
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

	//alat
	function edit_pinjaman_alat($d) {
        $this->load->helper('spt');
        $data['namaspt'] = getNamaSPT();

        $ida = str_replace(array('-', '_', '~'), array('+', '/', '='), $d);
        $d = base64_decode($this->encryption->decrypt($ida));
        
        $data['edit'] = $this->Mpinjam->get_data_pinjam_alat_by_id($d)->result();
        foreach ($data['edit'] as $key) {
            $rh['ini'] = date('Y-m-d', $key->tglin);
            $rh['oto'] = date('Y-m-d', $key->tglot);
			$rh['tmin']=$key->timein;
			$rh['tmot']=$key->timeot;
        }
        $data['lisalat'] = $this->Mpinjam->get_alat_redy_js($rh)->result();
        $ghj = $this->load->view('pinjam/page/v_edit_pinjam_alat', $data, TRUE);
        $this->konten($ghj);
    }
	function save_e_pinjam_alat(){
		$data=$this->input->post(NULL,TRUE);
		$data['ip']=$this->get_client_ip();
		$in=$this->Mpinjam->s_e_pinjam_alat($data);
		if($in){
			$not = array(
				'tipe' => 1,
				'isi' => " Edit Peminjaman Sukses"
				  );
				$this->session->set_flashdata('notif',$not );
				redirect('pinjam/alat');
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Edit Peminjaman Gagal"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('pinjam/alat');
		}
	}
	function hapus_pinjam_alat(){

		$nopin = $this->input->post('nopin');
		$result = $this->Mpinjam->hapus_pinjam_alat($nopin);

		if ($result) {
			$this->session->set_flashdata(
				'notif', array(
					'tipe' => 1, 
					'isi' => 'Data berhasil dihapus.')
				);
		} else {
			$this->session->set_flashdata(
				'notif', array(
					'tipe' => 3, 
					'isi' => 'Data gagal dihapus.')
				);
		}

		redirect('pinjam/alat');
	}
	


	//ruangan
	function edit_pinjaman_ruangan($d){
		$this->load->helper('spt');
		$data['namaspt']=getNamaSPT();

		$ida=str_replace(array('-','_','~'),array('+','/','='),$d);
		$d=base64_decode($this->encryption->decrypt($ida));
	
		$data['edit']=$this->Mpinjam->get_data_pinjam_ruang_by_id($d)->result();
		
		foreach ($data['edit'] as $key) {
			$rh['ini']=date('Y-m-d',$key->tglin);
			$rh['oto']=date('Y-m-d',$key->tglot);
			$rh['tmin']=$key->timein;
			$rh['tmot']=$key->timeot;

		}
		$data['lisruang']=$this->Mpinjam->get_ruangan_redy_js($rh)->result();
		$ghj=$this->load->view('pinjam/page/v_edit_pinjam_ruangan',$data,TRUE);
		$this->konten($ghj);
		
	}
	function save_e_pinjam_ruang(){
		$data=$this->input->post(NULL,TRUE);
		$data['ip']=$this->get_client_ip();
		$in=$this->Mpinjam->s_e_pinjam_ruangan($data);
		if($in){
			$not = array(
				'tipe' => 1,
				'isi' => " Edit Peminjaman Sukses"
				  );
				$this->session->set_flashdata('notif',$not );
				redirect('pinjam/ruangan');
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Edit Peminjaman Gagal"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('pinjam/ruangan');
		}
	}
	function hapus_pinjam_ruang(){

		$nopin = $this->input->post('nopin');
		$result = $this->Mpinjam->hapus_pinjam_ruang($nopin);

		if ($result) {
			$this->session->set_flashdata(
				'notif', array(
					'tipe' => 1, 
					'isi' => 'Data berhasil dihapus.')
				);
		} else {
			$this->session->set_flashdata(
				'notif', array(
					'tipe' => 3, 
					'isi' => 'Data gagal dihapus.')
				);
		}

		redirect('pinjam/ruangan');
	}
	
	
	function ubah_status_pinjam(){
		$data=$this->input->post(NULL,TRUE);
		$in=$this->Mpinjam->ubah_st_pinjam($data);
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
	function ubah_status_pinjam_ruang(){
		$nopin = $this->input->post('nopin');
		$status = $this->input->post('pilihstatus');
		
		if (isset($nopin) && !empty($nopin) && isset($status)) {
			$result = $this->Mpinjam->ubah_st_pinjam_ar($nopin, $status);
	
			if ($result) {
				$this->session->set_flashdata(
					'notif', array(
						'tipe' => 1, 
						'isi' => 'Status berhasil diubah.')
					);
			} else {
				$this->session->set_flashdata(
					'notif', array(
						'tipe' => 3, 
						'isi' => 'Status gagal diubah.')
					);
			}
		} else { 
			$this->session->set_flashdata(
				'notif', array(
					'tipe' => 3, 
					'isi' => 'Data tidak ditemukan.')
				);
		}
	
		redirect('pinjam/ruangan');
		
	}
	function ubah_status_pinjam_alat(){
		$nopin = $this->input->post('nopin');
		$status = $this->input->post('pilihstatus');
		
		if (isset($nopin) && !empty($nopin) && isset($status)) {
			$result = $this->Mpinjam->ubah_st_pinjam_ar($nopin, $status);
	
			if ($result) {
				$this->session->set_flashdata(
					'notif', array(
						'tipe' => 1, 
						'isi' => 'Status berhasil diubah.')
					);
			} else {
				$this->session->set_flashdata(
					'notif', array(
						'tipe' => 3, 
						'isi' => 'Status gagal diubah.')
					);
			}
		} else {
			$this->session->set_flashdata(
				'notif', array(
					'tipe' => 3, 
					'isi' => 'Data tidak ditemukan.')
				);
		}
	
		redirect('pinjam/alat');
		
	}





}