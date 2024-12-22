<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Morsip extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// $hjk=$this->session->userdata(base64_encode('jajahan'));
		// $sec=FALSE;
		// foreach ($hjk as $kaa) {
		// 	if ($kaa[base64_encode('apli')]==base64_encode('1_morsip')) {
		// 		$sec=TRUE;
		// 	}
		// }
		// if ($sec != TRUE) {
		// 	redirect('landing/menu');
		// }
		$this->load->model('morsip/mmorsip');
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
	public function coba($value='')
	{
		$this->load->library('qr/Ciqrcode');
		$this->load->helper('qr');
		echo makeQR(1,23242,2023);
		/*cho $fg=$this->encryption->encrypt("admin_0_superadmin");
		echo "<br>";
		echo $this->encryption->decrypt($fg);
		echo "<br>";
		echo $this->encryption->decrypt("60198d263417406476c3dca9b7a2c7c6b6356e7a43d576325c106da68ae94caad66338cae2f70e240e8f92285e256478e7cde60d3137d1011975aa07a373c23cQ1fCKIHIFbvITKCzUTKvPc6rdj0r46O84fUwwY7GxmWQ3w==");*/
		
	}
	public function index(){	
		
		$this->datanomor();
	}
	function konten($value=''){
		$data['konten']=$value;
		$this->load->view('morsip/home',$data);
	}

	public function cek_file_pdf(){
		$this->load->helper('file');
		$allowed_type = array('application/pdf');
       	$extn = get_mime_by_extension($_FILES['pile']['name']);
        if(isset($_FILES['pile']['name']) && $_FILES['pile']['name']!=""){
            if(in_array($extn, $allowed_type)){
                return true;
            }else{
                $this->form_validation->set_message('File PDF Tidak Valid', 'Tolong Pilih File Tipe PDF!!!');
                return false;
            }
        }else{
            $this->form_validation->set_message('File PDF Tidak Valid', 'Silahkan Pilih File PDF');
            return false;
        }
	}
	function gettahun(){
		$th=$this->session->userdata('tahun');
		if ($th == null) {
			$th=date('Y');
		}
		return $th;
	}
	function c_ajuan(){
		$th=$this->gettahun();
		$in=$this->mmorsip->get_c_ajuan($th)->result();
		foreach ($in as $row){
		        $idd= $row->hasnil;
		}
		return $idd;
	}
	function c_ajuan_spt(){
		$th=$this->gettahun();
		$in=$this->mmorsip->get_c_ajuan_spt($th)->result();
		foreach ($in as $row){
		        $idd= $row->hasnil;
		}
		return $idd;
	}
	function c_ajuan_pegawai(){
		$th=$this->gettahun();
		$in=$this->mmorsip->get_c_ajuan_pegawai($th)->result();
		foreach ($in as $row){
		        $idd= $row->hasnil;
		}
		return $idd;
	}
	function c_ajuan_jabatan(){
		$in=$this->mmorsip->get_c_ajuan_jabatan()->result();
		foreach ($in as $row){
		        $idd= $row->hasnil;
		}
		return $idd;
	}
	function c_ajuan_dasrut(){
		$in=$this->mmorsip->get_c_ajuan_dasrut()->result();
		foreach ($in as $row){
		        $idd= $row->hasnil;
		}
		return $idd;
	}
	function c_ajuan_datir($th){
		$in=$this->mmorsip->get_c_ajuan_datir($th)->result();
		foreach ($in as $row){
		        $idd= $row->hasnil;
		}
		return $idd;
	}
	function c_ajuan_perlu(){
		$in=$this->mmorsip->get_c_ajuan_perlu()->result();
		foreach ($in as $row){
		        $idd= $row->hasnil;
		}
		return $idd;
	}
	function datanomor($pg=''){
		$th=$this->gettahun();
		//$pga=base64_decode($pdfa);
		$data['jumdat']=$this->c_ajuan();
		$fg=$data['jumdat']/1000;
		if (filter_var($pg, FILTER_VALIDATE_INT) !== false){
			$limit=$pg;
			if ($pg>=ceil($fg)) {
			$limit=ceil($fg);
			}
		}else{
			$limit=1;
		}
		$data['datasur'] = $this->mmorsip->get_data($th,$limit)->result();
		$ghj=$this->load->view('morsip/page/v_nosur',$data,TRUE);
		$this->konten($ghj);// code...
	}
	function input_nomor(){
		$th=$this->gettahun();
		$data['tahun'] = $th;
		$data['kodesur'] = $this->mmorsip->get_kodesur()->result();

		$ghj=$this->load->view('morsip/page/v_in_nosur',$data,TRUE);
		$this->konten($ghj);// code...
	}
	
	function tahun($th){	
		if ($th>date('Y')) {
			$df=array('tahun' =>date('Y') );
		}
		elseif ($th<2020) {
			$df=array('tahun' =>2019 );
		}else{
			$df=array('tahun' =>$th );
		}
		$this->session->set_userdata($df);
		redirect('morsip');
	}
	function view_doc($v){
		$ida=str_replace(array('-','_','~'),array('+','/','='),$v);
		$data['link']=base64_decode($this->encryption->decrypt($ida));
		$ghj=$this->load->view('morsip/page/v_doc',$data);
		
	}
	function get_number_nw(){
		$th=$this->gettahun();
		$in=$this->mmorsip->get_nom($th)->result();
		foreach ($in as $row){
		        $idd= $row->num;
		}
		return $idd+1;
		
	}
	function get_admin_bid($id){
		$in=$this->mmorsip->get_adm($id)->result();
		return $in;
		
	}
	function save_nomor(){
		$this->load->library('qr/Ciqrcode');
		$this->load->helper('qr');
		$th=$this->gettahun();
		$idd=base64_decode($this->session->userdata('idus'));
		$bg=$this->get_admin_bid($idd);
		foreach ($bg as $row){
		        $bd= $row->bid;
		        $nmbd= $row->bidang;
		}
		$data=$this->input->post(NULL,TRUE);
		
		$this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
		$this->form_validation->set_rules('tgl', 'Tanggal Surat', 'required');
		$this->form_validation->set_rules('kodesur', 'Kode Surat', 'required');
		$this->form_validation->set_rules('perihal', 'Perihal', 'required');
		$this->form_validation->set_rules('kpd', 'Kepada', 'required');
		$this->form_validation->set_rules('pile', 'Cek_PDF', 'callback_cek_file_pdf');

		if ($this->form_validation->run() == true) {
			$data['tahun']=$th;
			$data['idad']=$idd;
			$data['bidang']=$bd;
			$data['nomor']=$this->get_number_nw();
			$nosur=$data['nomor'];
			$kodsur=$this->input->post('kodesur');

			$nmfil=$nosur."_".$kodsur."-".str_replace(" ","_",$nmbd);
			$data['nmf']=$nmfil;
			$ino=$this->mmorsip->s_nomor($data);
			$isin=makeQRNamb($ino,$nosur,date('Y'));
			if ($isin && $ino > 0) {
				$a['upload_path']='./harta/morsip/doc/dokumen'.$th;
				$a['allowed_types'] ='application|pdf';
				$a['file_name'] =$nmfil;
				$a['max_size'] =2048;
				$a['overwrite'] = TRUE;
				$this->load->library('upload', $a);
				$this->upload->initialize($a,TRUE);
				$up=$this->upload->do_upload('pile');
				if ($up) {
					$not = array(
						'tipe' => 1,
						'isi' => "Sukses Input dan Unggah Dokumen"
						  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/datanomor');
				}else{
					$not = array(
						'tipe' => 2,
						'isi' => "Sukses Input tapi GAGAL Unggah Dokumen"
						  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/datanomor');
				}
			}
			else{
				$not = array(
					'tipe' => 3,
					'isi' => "Gagal Input Nomor"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/datanomor');
			}
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Inputan Tidak Valid"
					  );
				$this->session->set_flashdata('notif',$not );
			$this->input_nomor();
		}

	}
	function hapus_nomor()
	{
		$data=$this->input->post(NULL,TRUE);
		$id=$data['id'];
		$tahun=$this->gettahun();

		$has=$this->mmorsip->get_nomor_by_id($tahun,$id)->result();
		foreach ($has as $e) {
			$file=$e->nmf;
		}
		$in=$this->mmorsip->del_nomor($id,$tahun);
		$in=true;
		if ($in) {
			$this->load->helper("file");
			$path='./harta/morsip/doc/dokumen'.$tahun.'/'.$file.'.pdf';
			$has=unlink($path);
			if ($has) {
				$not = array(
					'tipe' => 1,
					'isi' => "Sukses Hapus Nomor"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/datanomor');
			}else{
				$not = array(
					'tipe' => 2,
					'isi' => "Sukses Hapus Nomor. Gagal Harus Dokumen"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/datanomor');
			}


		}else{
			$not = array(
				'tipe' => 3,
				'isi' => "Gagal Hapus Nomor"
				  );
			$this->session->set_flashdata('notif',$not );
			redirect('morsip/datanomor');
		}
	}
	function hapus_spt()
	{
		$data=$this->input->post(NULL,TRUE);
		//print_r($data);
		$id=$data['id'];
		$no=$data['nosur'];
		$tahun=$this->gettahun();
		$has=$this->mmorsip->get_nomor_by_nomor($tahun,$no)->result();
		foreach ($has as $e) {
			$idno=$e->id;
		}
		//echo $idno;
		$in=$this->mmorsip->del_spt($id,$tahun,$idno);
		$in=true;
		if ($in) {
			$not = array(
				'tipe' => 1,
				'isi' => "Sukses Hapus SPT"
				  );
			$this->session->set_flashdata('notif',$not );
			redirect('morsip/dataspt');
		}else{
			$not = array(
				'tipe' => 3,
				'isi' => "Gagal Hapus SPT"
				  );
			$this->session->set_flashdata('notif',$not );
			redirect('morsip/dataspt');
		}
	}
	function downloadqr($v){
		$th=$this->gettahun();
		$this->load->helper('download');  
		$ida=str_replace(array('-','_','~'),array('+','/','='),$v);
		$d=base64_decode($this->encryption->decrypt($ida));		
		force_download("./harta/morsip/qr/".$th."/".$d.".png",null);
		
	}
	function edit_nomor($v){
		$th=$this->gettahun();
		$ida=str_replace(array('-','_','~'),array('+','/','='),$v);
		$d=base64_decode($this->encryption->decrypt($ida));

		$data['tahun'] = $th;
		$data['kodesur'] = $this->mmorsip->get_kodesur()->result();
		$data['data']=$this->mmorsip->get_nomor_by_id($th,$d)->result();
		//print_r($dd);
		$ghj=$this->load->view('morsip/page/v_e_nomor',$data,TRUE);
		$this->konten($ghj);
	}
	function up_doc($v){ // apabila file tidak ditemukan
		$th=$this->gettahun();
		$ida=str_replace(array('-','_','~'),array('+','/','='),$v);
		$d=base64_decode($this->encryption->decrypt($ida));

		$data['tahun'] = $th;
		$data['kodesur'] = $this->mmorsip->get_kodesur()->result();
		$data['data']=$this->mmorsip->get_nomor_by_id($th,$d)->result();
		//print_r($dd);
		$ghj=$this->load->view('morsip/page/v_up_nomor',$data,TRUE);
		$this->konten($ghj);
	}
	function up_tte($v){
		$th=$this->gettahun();
		$ida=str_replace(array('-','_','~'),array('+','/','='),$v);
		$d=base64_decode($this->encryption->decrypt($ida));

		$data['tahun'] = $th;
		$data['kodesur'] = $this->mmorsip->get_kodesur()->result();
		$data['data']=$this->mmorsip->get_nomor_by_id($th,$d)->result();
		//print_r($dd);
		$ghj=$this->load->view('morsip/page/v_up_tte',$data,TRUE);
		$this->konten($ghj);
	}
	function save_e_nomor(){ //seting maksimal edit dan hak edit
		
		$data=$this->input->post(NULL,TRUE);
		$axf=explode("-",$data['link']);
		$rpk=explode("_",$axf[0]);
		$data['tahun']=$this->gettahun();
		
		
		$in=$this->get_admin_bid($data['idad']);
		$uss=$this->session->userdata('us3r');
		$hk=$this->session->userdata(base64_encode('jajahan'));
		for ($i=0; $i <count($hk) ; $i++) {
			if (base64_decode($hk[$i][base64_encode('apli')])=="1_morsip") {
				$gethk=$this->encryption->decrypt($hk[$i][base64_encode('hk')]);
			}
		}
		$nwhk=explode("_",$gethk);
		foreach ($in as $ke) {
			$usdb=$ke->username;
			$bidang=$ke->bidang;
		}
		$nmfil=$data['nosur']."_".$data['kodesur']."-".str_replace(" ","_",$bidang);
		$data['nmf']=$nmfil;

		if ($rpk[1] != $data['kodesur'] && $_FILES["pile"]["size"] != 0) {
			$path='./harta/morsip/doc/dokumen'.$data['tahun'].'/'.$data['link'].'.pdf';
			$has=unlink($path);
		}elseif($rpk[1] != $data['kodesur']){
			$oldpt='./harta/morsip/doc/dokumen'.$data['tahun'].'/'.$data['link'].'.pdf';
			$target_file='./harta/morsip/doc/dokumen'.$data['tahun'].'/'.$nmfil.'.pdf';
			rename($oldpt, $target_file );
		}
		if ($data['isu']==1 &&($uss==$usdb) && ($nwhk[1]==0) ) { //verifikasi super admin
			$hs=$this->mmorsip->s_e_nomor($data);
			if ($hs) {
				$a['upload_path']='./harta/morsip/doc/dokumen'.$data['tahun'];
				$a['allowed_types'] ='application|pdf';
				$a['file_name'] =$nmfil;
				$a['max_size'] =2048;
				$a['overwrite'] = TRUE;
				$this->load->library('upload', $a);
				$this->upload->initialize($a,TRUE);
				$up=$this->upload->do_upload('pile');
				if ($up) {
					$not = array(
					'tipe' => 1,
					'isi' => "Edit Nomor Sukses & Upload Berkas Sukses"
					  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/datanomor');
				}else{
					$not = array(
					'tipe' => 1,
					'isi' => "Edit Nomor Sukses & Tanpa Upload Berkas"
					  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/datanomor');
				}
			}else{
				$not = array(
					'tipe' => 3,
					'isi' => "Gagal edit Nomor"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/datanomor');
			}

		}
		elseif ($data['isu']==0 && ($uss==$usdb)){
			$hs=$this->mmorsip->s_e_nomor($data);
			if ($hs) {
				$not = array(
					'tipe' => 1,
					'isi' => "Edit Nomor Sukses"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/datanomor');
			}else{
				$not = array(
					'tipe' => 3,
					'isi' => "Gagal edit Nomor"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/datanomor');
			}
		}
		elseif ($data['isu']==1 && ($uss==$usdb) && ($nwhk[1]==1) ){
			$not = array(
				'tipe' => 2,
				'isi' => "Gagal edit Nomor. Maaf Anda Telah Melebihi Batas Edit"
				  );
			$this->session->set_flashdata('notif',$not );
			redirect('morsip/datanomor');
		}
		else{
			$not = array(
				'tipe' => 3,
				'isi' => "Error Kacau Galat"
				  );
			$this->session->set_flashdata('notif',$not );
			redirect('morsip/datanomor');
		}
	}
	function save_up_doc(){ 
		
		$data=$this->input->post(NULL,TRUE);
		$data['tahun']=$this->gettahun();
		
		$in=$this->get_admin_bid($data['idad']);
		foreach ($in as $ke) {
			$usdb=$ke->username;
			$bidang=$ke->bidang;
		}
		$nmfil=$data['nosur']."_".$data['kodesur']."-".str_replace(" ","_",$bidang);
		$data['nmf']=$nmfil;
		$a['upload_path']='./harta/morsip/doc/dokumen'.$data['tahun'];
		$a['allowed_types'] ='application|pdf';
		$a['file_name'] =$nmfil;
		$a['max_size'] =2048;
		$a['overwrite'] = TRUE;
		$this->load->library('upload', $a);
		$this->upload->initialize($a,TRUE);
		$up=$this->upload->do_upload('pile');
		if($up){
			$drw=$this->mmorsip->s_e_doc($data);
			$not = array(
				'tipe' => 1,
				'isi' => " Upload Berkas Sukses"
				  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/datanomor');
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Gagal Upload Berkas"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/datanomor');
		}
		
	}
	function save_up_tte(){ 
		
		$data=$this->input->post(NULL,TRUE);
		$data['tahun']=$this->gettahun();
		$nmfil=$data['link'];
		$a['upload_path']='./harta/morsip/doc/dokumen'.$data['tahun'];
		$a['allowed_types'] ='application|pdf';
		$a['file_name'] =$nmfil;
		$a['max_size'] =2048;
		$a['overwrite'] = TRUE;
		$this->load->library('upload', $a);
		$this->upload->initialize($a,TRUE);
		$up=$this->upload->do_upload('pile');
		if($up){
			$drw=$this->mmorsip->s_e_tte($data);
			$not = array(
				'tipe' => 1,
				'isi' => " Upload Berkas Sukses"
				  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/datanomor');
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Gagal Upload Berkas"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/datanomor');
		}
		
	}
	function daftar_admin(){
		$this->load->helper('hk_admin');
		$hk=$this->session->userdata(base64_encode('jajahan'));
		for ($i=0; $i <count($hk) ; $i++) {
			if (base64_decode($hk[$i][base64_encode('apli')])=="1_morsip") {
				$gethk=$this->encryption->decrypt($hk[$i][base64_encode('hk')]);
			}
		}
		$nwhk=explode("_",$gethk);
		if ($nwhk[1]=='0') {
			$data['datasur'] = $this->mmorsip->get_adm_all()->result();
			$ghj=$this->load->view('morsip/page/v_admin',$data,TRUE);
			$this->konten($ghj);
											
		}else{
			redirect('morsip');
		}
		
	}
	function edit_admin($v){
		
		$ida=str_replace(array('-','_','~'),array('+','/','='),$v);
		$d=base64_decode($this->encryption->decrypt($ida));

		
		$data['bidang'] = $this->mmorsip->get_bidang()->result();
		$data['data']=$this->mmorsip->get_adm($d)->result();
		$da=$this->mmorsip->get_hk_admin(base64_encode($d))->result();
		foreach($da as $vy){
			if (base64_decode($vy->id_apli)=="1_morsip") {
				$hakk=$this->encryption->decrypt($vy->hk);
			}
		}
		
		$data['hak']=$hakk;
		
		//print_r($dd);
		$ghj=$this->load->view('morsip/page/v_e_admin',$data,TRUE);
		$this->konten($ghj);
	}
	function input_admin(){
		$hk=$this->session->userdata(base64_encode('jajahan'));
		for ($i=0; $i <count($hk) ; $i++) {
			if (base64_decode($hk[$i][base64_encode('apli')])=="1_morsip") {
				$gethk=$this->encryption->decrypt($hk[$i][base64_encode('hk')]);
			}
		}
		$nwhk=explode("_",$gethk);
		if ($nwhk[1]=='0') {
			$data['bidang'] = $this->mmorsip->get_bidang()->result();
			$ghj=$this->load->view('morsip/page/v_in_admin',$data,TRUE);
			$this->konten($ghj);
											
		}else{
			redirect('morsip');
		}
		$data['bidang'] = $this->mmorsip->get_bidang()->result();

		$ghj=$this->load->view('morsip/page/v_in_admin',$data,TRUE);
		$this->konten($ghj);// code...
	}
	function save_admin(){ 
		
		$data=$this->input->post(NULL,TRUE);
		$this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
		$this->form_validation->set_rules('user', 'Username', 'required|is_unique[user.username]');
		$this->form_validation->set_rules('bid', 'Subbag / Bidang', 'required');
		$this->form_validation->set_rules('hk', 'Hak Akses', 'required');
		$this->form_validation->set_rules('pass', 'Password', 'required');
		$this->form_validation->set_rules('passcom', 'Password Konfirmasi', 'required|matches[pass]');
		
		if ($this->form_validation->run() == true) {
			$up=$this->mmorsip->s_admin($data);
			$drw=$this->mmorsip->s_hk($up,$data['hk']);
			if($drw){
				
				$not = array(
					'tipe' => 1,
					'isi' => " Submit Admin Sukses"
					  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/daftar_admin');
			}else{
				$not = array(
						'tipe' => 3,
						'isi' => "Submit Admin Gagal"
						  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/daftar_admin');
			}
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Inputan Tidak Valid"
					  );
				$this->session->set_flashdata('notif',$not );
			$this->input_admin();
		}
	}
	function save_e_admin(){ 
		
		$data=$this->input->post(NULL,TRUE);
		print_r($data);
		$this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
		if ($data['user'] != $data['masuser']) {
				$this->form_validation->set_rules('user', 'Username', 'required|is_unique[user.username]');
		}
		$this->form_validation->set_rules('bid', 'Subbag / Bidang', 'required');
		$this->form_validation->set_rules('hk', 'Hak Akses', 'required');
		if ($data['pass'] != NULL) {
			echo "bisa";
			$this->form_validation->set_rules('pass', 'Password', 'required');
			$this->form_validation->set_rules('passcom', 'Password Konfirmasi', 'required|matches[pass]');	
		}
		$daa=$this->encryption->encrypt(base64_encode($data['id'])); 
        $ff=str_replace(array('+','/','='),array('-','_','~'),$daa);
		
		if ($this->form_validation->run() == true) {
			$up=$this->mmorsip->s_e_admin($data);
			$drw=$this->mmorsip->s_u_hk($data['id'],$data['hk']);
			
			if($up && $drw){
				
				$not = array(
					'tipe' => 1,
					'isi' => " Upload Berkas Sukses"
					  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/daftar_admin');
			}else{
				$not = array(
						'tipe' => 3,
						'isi' => "Gagal Upload Berkas"
						  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/daftar_admin');
			}
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Inputan Tidak Valid"
					  );
				$this->session->set_flashdata('notif',$not );
			$this->edit_admin($ff);
		}
	}
	function ambil_hak_admin(){
		$th=$this->gettahun();
		$data=$this->input->post(NULL,TRUE);
		$dt= $this->mmorsip->get_hk_admin($data['id'])->result();
		foreach ($dt as $key) {
			if (base64_decode($key->id_apli)=='1_morsip') {
				$hk=$this->encryption->decrypt($key->hk);
			}
		}
		$fd=explode("_",$hk);
		if ($fd[1]==0) {
			$hz="Aplkasi Morsip Dengan Hak Akses Super Admin";
		}else{
			$hz="Aplkasi Morsip Dengan Hak Akses Admin Biasa";

		}
		echo json_encode($hz);
	}
	function chgstatusadm(){
		$data=$this->input->post(NULL,TRUE);
		$dt= $this->mmorsip->chg_st_admin($data);
		return $dt;
	}
	function hapus_admin($v){
		$th=$this->gettahun();
		$ida=str_replace(array('-','_','~'),array('+','/','='),$v);
		$d=base64_decode($this->encryption->decrypt($ida));

		$w=$this->mmorsip->del_admin($d,base64_encode('1_morsip'));
		if($w){
			$not = array(
				'tipe' => 1,
				'isi' => "Hapus Admin Sukses"
				  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/daftar_admin');
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Gagal Hapus Admin"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/daftar_admin');
		}
	}
	function dataspt($pg=''){
		$th=$this->gettahun();
		//$pga=base64_decode($pdfa);
		$data['jumdat']=$this->c_ajuan_spt();
		$fg=$data['jumdat']/1000;
		if (filter_var($pg, FILTER_VALIDATE_INT) !== false){
			$limit=$pg;
			if ($pg>=ceil($fg)) {
			$limit=ceil($fg);
			}
		}else{
			$limit=1;
		}
		$dtnw=array();$d=0;
				 $sdh= $this->mmorsip->get_data_spt($th,$limit)->result();
		foreach ($sdh as $key) {
			$dtnw[$d]['id']=$key->id;
			$dtnw[$d]['id_adm']=$key->id_adm;
			$dtnw[$d]['nosur']=$key->nosur;
			$dtnw[$d]['dasrut']=$key->dasrut;


			$dtnw[$d]['nmdl']=$this->mmorsip->get_data_nmdl($key->nmdl,$th);
			$dtnw[$d]['keperluan']=$key->keperluan;
			$dtnw[$d]['tgldl']=$key->tgldl;
			$dtnw[$d]['tglin']=$key->tglin;
			$dtnw[$d]['tglot']=$key->tglot;
			$dtnw[$d]['nmttd']=$key->nmttd;
			$dtnw[$d]['datemake']=$key->datemake;
			$dtnw[$d]['isupdate']=$key->isupdate;
			$dtnw[$d]['a']=$key->a;
			$dtnw[$d]['kdbid']=$key->kdbid;
			$dtnw[$d]['notif']=$key->notif;
			$dtnw[$d]['bidang']=$key->bidang; $d++;
/*			echo "<pre>";
			print_r($key);
			echo "<br>";
			echo "</pre>";
*/
		}
		$hh=json_encode($dtnw);
		$data['datasur'] = json_decode($hh);

		/*echo "<pre>";
		print_r($data);
		echo "</pre>";*/

		$ghj=$this->load->view('morsip/page/v_spt',$data,TRUE);
		$this->konten($ghj);
	}
	function input_spt(){
		$th=$this->gettahun();
		$data['tahun'] = $th;
		$data['kodesur'] = $this->mmorsip->get_kodesur()->result();
		$data['pejabat'] = $this->mmorsip->get_pejabat_1()->result();

		$ghj=$this->load->view('morsip/page/v_in_spt',$data,TRUE);
		$this->konten($ghj);
	}
	function getnmdl(){
		$th=$this->gettahun();
		$data=$this->input->post(NULL,TRUE);
		//print_r($data);
		$extglin=explode("-", $data['in']);
		$extglot=explode("-", $data['ot']);
		//mktime(0,0,0, month, day, year);
		$hasin=mktime(0,0,0,$extglin[1],$extglin[2],$extglin[0]);
		$hasot=mktime(0,0,0,$extglot[1],$extglot[2],$extglot[0]);
		
		echo "Tanggal in -> ".$hasin;
		echo "<br>Tanggal ot -> ".$hasot;
		$hh=$this->mmorsip->get_ready_nmdl($hasin,$hasot,$th);
		echo "<option value=''>Plih Nama Pegawai</option>";
		foreach ($hh as $key ) { 

			echo "<option value=".$key->id.">".base64_decode($key->nama)."</option>";
		}
	}
	function getorangdl(){
		$th=$this->gettahun();
		$data=$this->input->post(NULL,TRUE);
		$hh=$this->mmorsip->get_data_nmdl($data['or'],$th);
		$en=explode("-", $hh);
		echo "Nama Orang DL Sebelum tanggal berubah<br>";
		for ($i=0; $i < count($en); $i++) {
			echo "<button class='btn btn-warning'>".$en[$i]."</button>&nbsp "; 
			
		}
	}
	function save_spt(){
		$this->load->library('qr/Ciqrcode');
		$this->load->helper('qr');
		$this->load->helper('hk_admin');
		$this->load->library('fpdf');
		$th=$this->gettahun(); 	
		$data=$this->input->post(NULL,TRUE);
		$data['tahun']=$th;
		$data['idadm']=base64_decode($this->session->userdata('idus'));
		$data['nomor']=$this->get_number_nw();;
		$w=$this->mmorsip->s_spt($data);
		
		
		$d=makeQR($w['id'],$w['nomor'],$w['tahun']);
		$has['spt']=$this->mmorsip->get_spt($w['id'],$th)->result();
		$has['dasur']=$this->mmorsip->get_dasur()->result();
		$has['mperlu']=$this->mmorsip->get_untuk()->result();
		$has['tahun']=$th;
		$this->load->view('morsip/page/v_mkspt',$has);
		if($d){
			$not = array(
				'tipe' => 1,
				'isi' => "Input SPT Sukses"
				  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/dataspt');
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Gagal Input Admin"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/dataspt');
		}
	}

	function coba_spt(){
		$this->load->helper('hk_admin');
		$this->load->library('fpdf');
		$th=$this->gettahun();
		
		$has['spt']=$this->mmorsip->get_spt('724',$th)->result();
		$has['dasur']=$this->mmorsip->get_dasur()->result();
		$has['mperlu']=$this->mmorsip->get_untuk()->result();
		$has['tahun']=$th;
		$this->load->view('morsip/page/v_mkspt',$has);
		
	}
	function preview_spt($id){
		$this->load->helper('hk_admin');
		$this->load->library('fpdf');
		$th=$this->gettahun();
		$ida=str_replace(array('-','_','~'),array('+','/','='),$id);
		$fgh=base64_decode($this->encryption->decrypt($ida));
		
		$has['spt']=$this->mmorsip->get_spt($fgh,$th)->result();
		$has['dasur']=$this->mmorsip->get_dasur()->result();
		$has['mperlu']=$this->mmorsip->get_untuk()->result();
		$has['tahun']=$th;
		$this->load->view('morsip/page/v_preview_spt',$has);
		
	}
	function edit_spt($v){
		$th=$this->gettahun();
		$ida=str_replace(array('-','_','~'),array('+','/','='),$v);
		$d=base64_decode($this->encryption->decrypt($ida));

		$data['tahun'] = $th;
		$data['kodesur'] = $this->mmorsip->get_kodesur()->result();
		$data['data']=$this->mmorsip->get_spt($d,$th)->result();
		foreach ($data['data'] as $key) {
			$hin=$key->tglin;
			$hon=$key->tglot;
			$nmd=$key->nmdl;
		}
		$td= explode("-",trim($nmd,"-"));
		for ($ia=0; $ia < count($td); $ia++) { 
			$fx[$ia]=base64_decode($td[$ia]);
		}
		$data['dataspt']=$this->mmorsip->get_ready_nmdl($hin,$hon,$th);
		$data['namaspt']=$this->mmorsip->get_nmdl_array($fx,$th);
		//print_r($fx);
		//print_r($data['data']);
		$data['pejabat'] = $this->mmorsip->get_pejabat_1()->result();
		$data['madmin']=$this->mmorsip->get_adm_all()->result();
		//print_r($dd);
		$ghj=$this->load->view('morsip/page/v_e_spt',$data,TRUE);
		$this->konten($ghj);
	}

	function save_e_spt(){
		$this->load->helper('hk_admin');
		$this->load->library('fpdf');
		$th=$this->gettahun(); 	
		$data=$this->input->post(NULL,TRUE);
		

		$idd=$data['idspt'];
		$data['tahun']=$th;
		$data['idadm']=base64_decode($this->session->userdata('idus'));

		//print_r($data);
		//$data['nomor']=$this->get_number_nw();;
		$w=$this->mmorsip->s_e_spt($data);

		$has['spt']=$this->mmorsip->get_spt($idd,$th)->result();
		$has['dasur']=$this->mmorsip->get_dasur()->result();
		$has['mperlu']=$this->mmorsip->get_untuk()->result();
		$has['tahun']=$th;
		$this->load->view('morsip/page/v_mkspt',$has);
		
		if($w){
			$not = array(
				'tipe' => 1,
				'isi' => "Edit SPT Sukses"
				  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/dataspt');
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Gagal Edit SPT"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/dataspt');
		}
	}
	function datapegawai($pg=''){
		$th=$this->gettahun();
		//$pga=base64_decode($pdfa);
		$data['jumdat']=$this->c_ajuan_pegawai($th);
		$fg=$data['jumdat']/1000;
		;
		if (filter_var($pg, FILTER_VALIDATE_INT) !== false){
			$limit=$pg;
			if ($pg>=ceil($fg)) {
			$limit=ceil($fg);
			}
		}else{
			$limit=1;
		}
		$data['datasur'] = $this->mmorsip->get_data_pegawai($th,$limit)->result();
		$ghj=$this->load->view('morsip/page/v_pegawai',$data,TRUE);
		$this->konten($ghj);// code...
	}
	function input_pegawai(){
		$th=$this->gettahun();
		$data['tahun'] = $th;
		$data['jabatan'] = $this->mmorsip->get_jabatan()->result();
		$data['golongan'] = $this->mmorsip->get_golongan()->result();
		
		$ghj=$this->load->view('morsip/page/v_in_pegawai',$data,TRUE);
		$this->konten($ghj);// code...
	}
	function save_pegawai(){
		$data=$this->input->post(NULL,TRUE);
		$th=$this->gettahun();
		$data['tahun']=$th;

		/*echo "<pre>";
		print_r($data);
		echo "</pre><br>";*/
		$this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
		$this->form_validation->set_rules('nip', 'NIP', 'required|min_length[21]|max_length[21]');
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
		$this->form_validation->set_rules('golongan', 'Pangkat Golongan Ruang', 'required');
		$this->form_validation->set_rules('hk', 'Sortir Pegawai', 'required');
		
		if ($this->form_validation->run() == true) {
			$up=$this->mmorsip->s_pegawai($data);
			if($up){
				
				$not = array(
					'tipe' => 1,
					'isi' => " Submit Pegawai Sukses"
					  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/datapegawai');
			}else{
				$not = array(
						'tipe' => 3,
						'isi' => "Submit Pegawai Gagal"
						  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/datapegawai');
			}
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Inputan Tidak Valid"
					  );
			$this->session->set_flashdata('notif',$not );
			$this->input_pegawai();
		}
	}
	function hapus_pegawai(){
		$data=$this->input->post(NULL,TRUE);
		$th=$this->gettahun();
		$data['tahun']=$th;
		$w=$this->mmorsip->del_pegawai($data);
		if($w){
			$not = array(
				'tipe' => 1,
				'isi' => "Ubah Status Pegawai Sukses"
				  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/datapegawai');
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Gagal Ubah Status Pegawai"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/datapegawai');
		}
	}
	function edit_pegawai($v){
		$th=$this->gettahun();
		$ida=str_replace(array('-','_','~'),array('+','/','='),$v);
		$d=base64_decode($this->encryption->decrypt($ida));
		$da['id']=$d; $da['tahun']=$th;
		$data['pegawai']=$this->mmorsip->get_pegawai_by_id($da)->result();
		$data['tahun'] = $th;
		$data['jabatan'] = $this->mmorsip->get_jabatan()->result();
		$data['golongan'] = $this->mmorsip->get_golongan()->result();

		/*echo "<pre>";
		print_r($data['pegawai']);
		echo "</pre>";*/
		$ghj=$this->load->view('morsip/page/v_e_pegawai',$data,TRUE);
		$this->konten($ghj);
	}
	function save_e_pegawai(){
		$th=$this->gettahun();
		$data=$this->input->post(NULL,TRUE);
		$data['tahun']=$th;
		
		$this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
		$this->form_validation->set_rules('nip', 'NIP', 'required|min_length[21]|max_length[21]');
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
		$this->form_validation->set_rules('golongan', 'Pangkat Golongan Ruang', 'required');
		$this->form_validation->set_rules('hk', 'Sortir Pegawai', 'required');
		
		if ($this->form_validation->run() == true) {
			$up=$this->mmorsip->s_e_pegawai($data);
			if($up){
				
				$not = array(
					'tipe' => 1,
					'isi' => " Submit Pegawai Sukses"
					  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/datapegawai');
			}else{
				$not = array(
						'tipe' => 3,
						'isi' => "Submit Pegawai Gagal"
						  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/datapegawai');
			}
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Inputan Tidak Valid"
					  );
			$this->session->set_flashdata('notif',$not );
			$this->datapegawai();
		}
	}
	function datajabatan($pg=''){
		//$pga=base64_decode($pdfa);
		$data['jumdat']=$this->c_ajuan_jabatan();
		$fg=$data['jumdat']/1000;
		;
		if (filter_var($pg, FILTER_VALIDATE_INT) !== false){
			$limit=$pg;
			if ($pg>=ceil($fg)) {
			$limit=ceil($fg);
			}
		}else{
			$limit=1;
		}
		$data['datasur'] = $this->mmorsip->get_data_jabatan($limit)->result();
		
		$ghj=$this->load->view('morsip/page/v_jabatan',$data,TRUE);
		$this->konten($ghj);// code...
	}
	
	function input_jabatan(){
		$ghj=$this->load->view('morsip/page/v_in_jabatan',NULL,TRUE);
		$this->konten($ghj);
	}
	function save_jabatan(){
		$data=$this->input->post(NULL,TRUE);
		$this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		if ($this->form_validation->run() == true) {
			$up=$this->mmorsip->s_jabatan($data);
			if($up){
				$not = array(
					'tipe' => 1,
					'isi' => " Submit Jabatan Sukses"
					  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/datajabatan');
			}else{
				$not = array(
						'tipe' => 3,
						'isi' => "Submit Jabatan Gagal"
						  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/datajabatan');
			}
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Inputan Tidak Valid"
					  );
			$this->session->set_flashdata('notif',$not );
			$this->input_jabatan();
		}
	}
	function save_e_jabatan(){
		$data=$this->input->post(NULL,TRUE);
		
		$this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		if ($this->form_validation->run() == true) {
			$up=$this->mmorsip->s_e_jabatan($data);
			if($up){
				$not = array(
					'tipe' => 1,
					'isi' => " Submit Jabatan Sukses"
					  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/datajabatan');
			}else{
				$not = array(
						'tipe' => 3,
						'isi' => "Submit Jabatan Gagal"
						  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/datajabatan');
			}
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Inputan Tidak Valid"
					  );
			$this->session->set_flashdata('notif',$not );
			$dap=$this->encryption->encrypt(base64_encode($data['id'])); $ff=str_replace(array('+','/','='),array('-','_','~'),$dap);
			$this->edit_jabatan($dap);
		}
	}
	function edit_jabatan($v){
		$th=$this->gettahun();
		$ida=str_replace(array('-','_','~'),array('+','/','='),$v);
		$d=base64_decode($this->encryption->decrypt($ida));
		
		$da['id']=$d;
		$data['jabatan']=$this->mmorsip->get_jabatan_by_id($da)->result();

		$ghj=$this->load->view('morsip/page/v_e_jabatan',$data,TRUE);
		$this->konten($ghj);
	}
	function hapus_jabatan(){
		$data=$this->input->post(NULL,TRUE);
		//print_r($data);
		$w=$this->mmorsip->del_jabatan($data);
		if($w){
			$not = array(
				'tipe' => 1,
				'isi' => "Hapus Jabatan"
				  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/datajabatan');
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Gagal Hapus Jabatan"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/datajabatan');
		}
	}
	function dasarsurat($pg=''){
		//$pga=base64_decode($pdfa);
		$data['jumdat']=$this->c_ajuan_dasrut();
		$fg=$data['jumdat']/1000;
		
		if (filter_var($pg, FILTER_VALIDATE_INT) !== false){
			$limit=$pg;
			if ($pg>=ceil($fg)) {
			$limit=ceil($fg);
			}
		}else{
			$limit=1;
		}
		$data['datasur'] = $this->mmorsip->get_data_dasrut($limit)->result();
		
		$ghj=$this->load->view('morsip/page/v_dasrut',$data,TRUE);
		$this->konten($ghj);// code...
	}
	function hapus_dasrut(){
		$data=$this->input->post(NULL,TRUE);
		
		$w=$this->mmorsip->del_dasrut($data);
		if($w){
			$not = array(
				'tipe' => 1,
				'isi' => "Sukses Ubah Status Dasar Surat"
				  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/dasarsurat');
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Gagal Ubah Status Dasar Surat"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/dasarsurat');
		}
	}
	function edit_dasrut($v){
		$th=$this->gettahun();
		$ida=str_replace(array('-','_','~'),array('+','/','='),$v);
		$d=base64_decode($this->encryption->decrypt($ida));
		
		$da['id']=$d;
		$data['dasrut']=$this->mmorsip->get_dasrut_by_id($da)->result();

		$ghj=$this->load->view('morsip/page/v_e_dasrut',$data,TRUE);
		$this->konten($ghj);
	}
	function input_dasrut(){
		$ghj=$this->load->view('morsip/page/v_in_dasrut',NULL,TRUE);
		$this->konten($ghj);
	}
	function save_dasrut(){
		$data=$this->input->post(NULL,TRUE);
		//print_r($data);
		$this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		if ($this->form_validation->run() == true) {
			$up=$this->mmorsip->s_dasrut($data);
			if($up){
				$not = array(
					'tipe' => 1,
					'isi' => " Simpan Dasar Surat Sukses"
					  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/dasarsurat');
			}else{
				$not = array(
						'tipe' => 3,
						'isi' => "Simpan Dasar Surat Gagal"
						  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/dasarsurat');
			}
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Inputan Tidak Valid"
					  );
			$this->session->set_flashdata('notif',$not );
			$this->input_jabatan();
		}
	}
	function save_e_dasrut(){
		$data=$this->input->post(NULL,TRUE);
		//print_r($data);
		$this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		if ($this->form_validation->run() == true) {
			$up=$this->mmorsip->s_e_dasrut($data);
			if($up){
				$not = array(
					'tipe' => 1,
					'isi' => "Edit Dasar Surat Sukses"
					  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/dasarsurat');
			}else{
				$not = array(
						'tipe' => 3,
						'isi' => "Edit Dasar Surat Gagal"
						  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/dasarsurat');
			}
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Inputan Tidak Valid"
					  );
			$this->session->set_flashdata('notif',$not );
			$dap=$this->encryption->encrypt(base64_encode($data['id'])); $ff=str_replace(array('+','/','='),array('-','_','~'),$dap);
			$this->edit_dasrut($dap);
		}
	}
	function keperluan($pg=''){
		//$pga=base64_decode($pdfa);
		$data['jumdat']=$this->c_ajuan_perlu();
		$fg=$data['jumdat']/1000;
		
		if (filter_var($pg, FILTER_VALIDATE_INT) !== false){
			$limit=$pg;
			if ($pg>=ceil($fg)) {
			$limit=ceil($fg);
			}
		}else{
			$limit=1;
		}
		$data['datasur'] = $this->mmorsip->get_data_perlu($limit)->result();
		
		$ghj=$this->load->view('morsip/page/v_perlu',$data,TRUE);
		$this->konten($ghj);
	}
	function hapus_perlu(){
		$data=$this->input->post(NULL,TRUE);
		print_r($data);
		$w=$this->mmorsip->del_perlu($data);
		if($w){
			$not = array(
				'tipe' => 1,
				'isi' => "Sukses Ubah Status Keperluan"
				  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/keperluan');
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Gagal Ubah Status Keperluan"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/keperluan');
		}
	}
	function input_perlu(){
		$ghj=$this->load->view('morsip/page/v_in_perlu',NULL,TRUE);
		$this->konten($ghj);
	}
	function save_perlu(){
		$data=$this->input->post(NULL,TRUE);
		print_r($data);
		$this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		if ($this->form_validation->run() == true) {
			$up=$this->mmorsip->s_perlu($data);
			if($up){
				$not = array(
					'tipe' => 1,
					'isi' => " Simpan Dasar Surat Sukses"
					  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/keperluan');
			}else{
				$not = array(
						'tipe' => 3,
						'isi' => "Simpan Dasar Surat Gagal"
						  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/keperluan');
			}
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Inputan Tidak Valid"
					  );
			$this->session->set_flashdata('notif',$not );
			$this->input_perlu();
		}
	}
	function save_e_perlu(){
		$data=$this->input->post(NULL,TRUE);
		//print_r($data);
		$this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		if ($this->form_validation->run() == true) {
			$up=$this->mmorsip->s_e_perlu($data);
			if($up){
				$not = array(
					'tipe' => 1,
					'isi' => "Edit Keperluan Sukses"
					  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/keperluan');
			}else{
				$not = array(
						'tipe' => 3,
						'isi' => "Edit Keperluan Gagal"
						  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/keperluan');
			}
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Inputan Tidak Valid"
					  );
			$this->session->set_flashdata('notif',$not );
			$dap=$this->encryption->encrypt(base64_encode($data['id'])); $ff=str_replace(array('+','/','='),array('-','_','~'),$dap);
			$this->edit_perlu($dap);
		}
	}
	function edit_perlu($v){
		$th=$this->gettahun();
		$ida=str_replace(array('-','_','~'),array('+','/','='),$v);
		$d=base64_decode($this->encryption->decrypt($ida));
		
		$da['id']=$d;
		$data['data']=$this->mmorsip->get_perlu_by_id($da)->result();

		$ghj=$this->load->view('morsip/page/v_e_perlu',$data,TRUE);
		$this->konten($ghj);
	}

	function datadatir($pg=''){
		$th=$this->gettahun();
		$data['jumdat']=$this->c_ajuan_datir($th);
		$fg=$data['jumdat']/1000;
		if (filter_var($pg, FILTER_VALIDATE_INT) !== false){
			$limit=$pg;
			if ($pg>=ceil($fg)) {
			$limit=ceil($fg);
			}
		}else{
			$limit=1;
		}
		$data['datasur'] = $this->mmorsip->get_data_datir($th,$limit)->result();
		$ghj=$this->load->view('morsip/page/v_datir',$data,TRUE);
		$this->konten($ghj);// code...
	}
	function input_datir(){
		$th=$this->gettahun();
		$data['tahun'] = $th;
		$data['kodesur'] = $this->mmorsip->get_kodesur()->result();

		$ghj=$this->load->view('morsip/page/v_in_datir',$data,TRUE);
		$this->konten($ghj);// code...
	}
	function get_no_datir(){
		$th=$this->gettahun();
		$data=$this->input->post(NULL,TRUE);
		
		$hh=$this->mmorsip->get_ready_datir($data['in'],$th);
		/*echo "<br>bawah hasil controler<br> ";
		print_r($hh);*/
		
		if ($hh != NULL) {
			echo "<option value=''>Plih Nomor Antidatir</option>";
			foreach ($hh as $key) {
				echo "<option value=".$key->nosur.">".$key->nosur." - ".$key->n_bid."</option>";
			}
		}else{
			echo "<option value=''>Plih Nomor Antidatir</option>";

		}
	}
	function get_add_datir(){
		$th=$this->gettahun();
		$data=$this->input->post(NULL,TRUE);
		//print_r($data);
		$hh=$this->mmorsip->get_like_datir($data['in'],$th);
		//print_r($hh); 
		$bt=0;
		foreach ($hh as $ky) {
			$ft=explode(".", $ky->nosur);
			if ($ft[1]>$bt) {
				$bt=$ft[1];
			}else{
				$bt=$bt;
			}
		}
		$fty=$bt+1;
		echo "<input type='text' class='form-control' value='".$fty."' name='tambahan' id='tambahan' required='required' disabled='isabled'>";
		echo "<input type='hidden' class='form-control' value='".$fty."' name='tmb' id='tmb' >";
	}
	function save_nomor_datir(){
		$this->load->library('qr/Ciqrcode');
		$this->load->helper('qr');
		$th=$this->gettahun();
		$idd=base64_decode($this->session->userdata('idus'));
		$bg=$this->get_admin_bid($idd);
		foreach ($bg as $row){
		        $bd= $row->bid;
		        $nmbd= $row->bidang;
		}
		$data=$this->input->post(NULL,TRUE);
		
		/*echo "<pre>";
		print_r($data);
		echo "</pre>";*/

		$this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
		$this->form_validation->set_rules('tgl', 'Tanggal Surat', 'required');
		$this->form_validation->set_rules('kodesur', 'Kode Surat', 'required');
		$this->form_validation->set_rules('perihal', 'Perihal', 'required');
		$this->form_validation->set_rules('kpd', 'Kepada', 'required');
		$this->form_validation->set_rules('pile', 'Cek_PDF', 'callback_cek_file_pdf');

		if ($this->form_validation->run() == true) {
			$data['tahun']=$th;
			$data['idad']=$idd;
			$data['bidang']=$bd;
			$data['nomor']=$data['nodatir'].".".$data['tmb'];
			$nosur=$data['nodatir']."-".$data['tmb'];
			$kodsur=$data['kodesur'];

			$nmfil=$nosur."_".$kodsur."-".str_replace(" ","_",$nmbd);
			$data['nmf']=$nmfil;
			$ino=$this->mmorsip->s_nomor_datir($data);
			$isin=makeQRNamb($ino,$nosur,date('Y'));
			if ($isin && $ino > 0) {
				$a['upload_path']='./harta/morsip/doc/dokumen'.$th;
				$a['allowed_types'] ='application|pdf';
				$a['file_name'] =$nmfil;
				$a['max_size'] =2048;
				$a['overwrite'] = TRUE;
				$this->load->library('upload', $a);
				$this->upload->initialize($a,TRUE);
				$up=$this->upload->do_upload('pile');
				$err=$this->upload->display_errors();
				if ($up) {
					$not = array(
						'tipe' => 1,
						'isi' => "Sukses Input dan Unggah Dokumen"
						  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/datadatir');
				}else{
					$not = array(
						'tipe' => 2,
						'isi' => "Sukses Input tapi GAGAL Unggah Dokumen".$err
						  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/datadatir');
				}
			}
			else{
				$not = array(
					'tipe' => 3,
					'isi' => "Gagal Input Nomor"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/datadatir');
			}
		}else{
			$not = array(
					'tipe' => 3,
					'isi' => "Inputan Tidak Valid"
					  );
				$this->session->set_flashdata('notif',$not );
			$this->input_datir();
		}

	}
	function edit_nomor_datir($v){
		$th=$this->gettahun();
		$ida=str_replace(array('-','_','~'),array('+','/','='),$v);
		$d=base64_decode($this->encryption->decrypt($ida));

		$data['tahun'] = $th;
		$data['bidang'] = $this->mmorsip->get_bidang()->result();
		$data['kodesur'] = $this->mmorsip->get_kodesur()->result();
		$data['data']=$this->mmorsip->get_nomor_datir_by_id($th,$d)->result();
		//print_r($dd);
		$ghj=$this->load->view('morsip/page/v_e_nomor_datir',$data,TRUE);
		$this->konten($ghj);
	}
	function save_e_nomor_datir(){ //seting maksimal edit dan hak edit
		
		$data=$this->input->post(NULL,TRUE);
		
		$axf=explode("-",$data['link']);
		$rpk=explode("_",$axf[1]);
		$data['tahun']=$this->gettahun();
		
		
		$in=$this->get_admin_bid($data['idad']);
		$uss=$this->session->userdata('us3r');
		$hk=$this->session->userdata(base64_encode('jajahan'));
		for ($i=0; $i <count($hk) ; $i++) {
			if (base64_decode($hk[$i][base64_encode('apli')])=="1_morsip") {
				$gethk=$this->encryption->decrypt($hk[$i][base64_encode('hk')]);
			}
		}
		$nwhk=explode("_",$gethk);
		foreach ($in as $ke) {
			$usdb=$ke->username;
			$bidang=$ke->bidang;
		}
		$tyu=explode(".",$data['nosur']);

		$nmfil=$tyu[0]."-".$tyu[1]."_".$data['kodesur']."-".str_replace(" ","_",$bidang);
		$data['nmf']=$nmfil;
		//print_r($data);
		if ($rpk[1] != $data['kodesur'] && $_FILES["pile"]["size"] != 0) {
			$path='./harta/morsip/doc/dokumen'.$data['tahun'].'/'.$data['link'].'.pdf';
			$has=unlink($path);
		}elseif($rpk[1] != $data['kodesur']){
			$oldpt='./harta/morsip/doc/dokumen'.$data['tahun'].'/'.$data['link'].'.pdf';
			$target_file='./harta/morsip/doc/dokumen'.$data['tahun'].'/'.$nmfil.'.pdf';
			rename($oldpt, $target_file );
		}
		if (($uss==$usdb) && ($nwhk[1]==0) ) { //verifikasi super admin
			$hs=$this->mmorsip->s_e_nomor_datir($data);
			if ($hs) {
				$a['upload_path']='./harta/morsip/doc/dokumen'.$data['tahun'];
				$a['allowed_types'] ='application|pdf';
				$a['file_name'] =$nmfil;
				$a['max_size'] =2048;
				$a['overwrite'] = TRUE;
				$this->load->library('upload', $a);
				$this->upload->initialize($a,TRUE);
				$up=$this->upload->do_upload('pile');
				if ($up) {
					$not = array(
					'tipe' => 1,
					'isi' => "Edit Nomor Sukses & Upload Berkas Sukses"
					  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/datadatir');
				}else{
					$not = array(
					'tipe' => 1,
					'isi' => "Edit Nomor Sukses & Tanpa Upload Berkas"
					  );
					$this->session->set_flashdata('notif',$not );
					redirect('morsip/datadatir');
				}
			}else{
				$not = array(
					'tipe' => 3,
					'isi' => "Gagal edit Nomor"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/datadatir');
			}

		}
		
		else{
			$not = array(
				'tipe' => 3,
				'isi' => "Error Kacau Galat"
				  );
			$this->session->set_flashdata('notif',$not );
			//redirect('morsip/datadatir');
		}
	}
	function hapus_nomor_datir(){
		$data=$this->input->post(NULL,TRUE);
		print_r($data);
		$id=$data['id'];
		$tahun=$this->gettahun();

		$has=$this->mmorsip->get_nomor_datir_by_id($tahun,$id)->result();
		foreach ($has as $e) {
			$file=$e->nmf;
		}
		echo $file;
		$in=$this->mmorsip->del_nomor_datir($id,$tahun);
		$in=true;
		if ($in) {
			$this->load->helper("file");
			$path='./harta/morsip/doc/dokumen'.$tahun.'/'.$file.'.pdf';
			$has=unlink($path);
			if ($has) {
				$not = array(
					'tipe' => 1,
					'isi' => "Sukses Hapus Nomor"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/datadatir');
			}else{
				$not = array(
					'tipe' => 2,
					'isi' => "Sukses Hapus Nomor. Gagal Harus Dokumen"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('morsip/datadatir');
			}


		}else{
			$not = array(
				'tipe' => 3,
				'isi' => "Gagal Hapus Nomor"
				  );
			$this->session->set_flashdata('notif',$not );
			redirect('morsip/datadatir');
		}
	}



























































































	function create_captcha(){
		    $data = array(
		    	'word'=>substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6),
		        'img_path' => 'cc/',
		        'img_url' => base_url('cc'),
		        'font_path' => base_url('system/font/texb.ttf'),
		        'expiration' => 30,
		        'font_size'     => 30,
		        'word_length'   => 6,
		        'img_width' => 150,
    			'img_height' => 40,
				'colors'=> array(
					'background' => array(192, 192, 192 ),
					'border' => array(0, 0, 25),
					'text' => array(0, 0, 0),
					'grid' => array(30, 144, 255)
					//'grid' => array(185, 234, 237)
				)
		    );
		    
		    $captcha = create_captcha($data);
		    
		    $image = $captcha['image'];
			
		    $this->session->set_userdata(base64_encode('captchaword'), base64_encode($captcha['word']));

		    return $image;
	}
	function refresh_captcha(){
	            $data = array(   
	               'word'=>substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6),
			        'img_path' => 'cc/',
			        'img_url' => base_url('cc'),
			        /*'font_path' => base_url('c0r3/fonts/fontawesome-webfont.ttf'),*/
			        'font_path' => base_url('system/font/texb.ttf'),
			        'img_width' => 150,
			        'img_height' => 40,
			        'expiration' => 30,
			        'font_size'     => 30,
			        'word_length'   => 6,
					'colors'=> array(
						'background' => array(192, 192, 192 ),
						'border' => array(0, 0, 25),
						'text' => array(0, 0, 0),
						'grid' => array(30, 144, 255)
				));
                $captcha = create_captcha($data);
                $this->session->unset_userdata(base64_encode('captchaword'));
                $this->session->set_userdata(base64_encode('captchaword'), base64_encode($captcha['word']));
                echo $captcha['image'];
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
	function auth(){
		
		$this->load->library('auth');
		$this->input->post(NULL,TRUE);
		$us=$this->auth->filter($this->input->post('user'));
		$psw=$this->auth->filter($this->input->post('pass'));
		$cap=$this->auth->filter($this->input->post('capt'));
		$ff=$this->session->userdata(base64_encode('captchaword'));
		/*echo $cap."<br>ADSAD ".$ff."<br><br>";
		print_r($this->session->userdata());*/
		if ($cap == base64_decode($ff)) {

			$this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
			$this->form_validation->set_rules('user', 'User', 'required',array('required' => 'Username Harus Diisi') );
			$this->form_validation->set_rules('pass', 'Password', 'required',array('required' => 'Password Harus Diisi') );
			$this->form_validation->set_rules('capt', 'Captcha', 'required',array('required' => 'Password Harus Diisi') );
			$ipk=$this->get_client_ip();

			$in=$this->mlogin->fin($us,$psw,$ipk);
			if ($in==true) {
				redirect('landing/menu');
				/*echo "<pre>";
				print_r($this->session->userdata());
				echo "</pre>";*/
			}else{
				$this->session->set_flashdata('notif_login','Akses Ditolak!!<br>Cek Kembali User dan Password Anda');
				redirect('landing#form-section');
			}	
		}else{
			$this->session->set_flashdata('notif_login','<b>Captcha Tidak Sesuai!!</b>');
			
				redirect('landing#form-section');
		}
		 

	}
	function menu(){	
		if(($this->session->userdata('useryyy') !=null) && ($this->session->userdata('idus')!= null) && ($this->session->userdata(base64_encode('jajahan')) != null) ){
		$this->load->view('landing/menu');

		}else{
			redirect('landing');
		}
		
	}
	function out(){
		$dt = array('us3r' =>'' ,'useryyy'=> '', 'idus' => '' ,base64_encode('jajahan')=>'' );
		$this->session->unset_userdata($dt);
		$this->session->sess_destroy();
		redirect('landing');
	}














}




