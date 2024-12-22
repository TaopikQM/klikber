<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ruangan extends CI_Controller {

	public function __construct(){
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
		$this->load->model('pinjam/Mruang');
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
	public function indeax(){	
		//$this->konten();
		$this->ruangan();
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

	public function index(){

		$this->load->helper('pinjam');
		/*if ($gt=="tambah") {
			$ghj=$this->load->view('pinjam/page/in-mobil','',TRUE);
			$this->konten($ghj);
			echo "Halaman Tambah";	
		}else if($gt=='edit'){
			$ida=str_replace(array('-','_','~'),array('+','/','='),$wd);
			$d=base64_decode($this->encryption->decrypt($ida));
			$has['data']=$this->Mruang->get_data_mobil_by_id($d)->result();
			$ghj=$this->load->view('pinjam/page/v-edit-mobil',$has,TRUE);
			$this->konten($ghj);
		
		}else if($gt=='foto'){
			$ida=str_replace(array('-','_','~'),array('+','/','='),$wd);
			$d=base64_decode($this->encryption->decrypt($ida));
			$has['data']=$this->Mruang->get_data_mobil_by_id($d)->result();
			$ghj=$this->load->view('pinjam/page/v-edit-foto-mobil',$has,TRUE);
			$this->konten($ghj);
		}*/
		/*else{*/		
			$data['dataruang']=$this->Mruang->get_data_ruang()->result();
			$ghj=$this->load->view('pinjam/page/v_data_ruangan',$data,TRUE);
			$this->konten($ghj);			
		//}

	}
	function edit($wd){
		$ida=str_replace(array('-','_','~'),array('+','/','='),$wd);
		$d=base64_decode($this->encryption->decrypt($ida));
		
		$has['data']=$this->Mruang->get_data_ruang_by_id($d)->result();
		$ghj=$this->load->view('pinjam/page/v-edit-ruangan',$has,TRUE);
		$this->konten($ghj);
	}
	function tambah(){
		$ghj=$this->load->view('pinjam/page/in-ruang','',TRUE);
		$this->konten($ghj);
	}
	function is_uniq_ruang($value){ 
		//print_r($this->Mruang->is_unique('ruangan','nmruang', $value));
		if ($this->Mruang->is_unique('ruangan','nmruang', $value)) {
		    return TRUE;
		} else {
		    $this->form_validation->set_message('is_uniq_ruang', 'Inputan anda telah Terdaftar');
		    return FALSE;
		}
	}
	function simpan(){ //perlu dibkin hak akases input mobil
		$data=$this->input->post(NULL,TRUE);
		$hbg=str_replace(" ","",$data['narung']);
		$url='./harta/pinjam/dataruang/'.strtoupper($hbg);

		$a['allowed_types'] ='jpg|gif|png';
		$a['max_size'] =5048;
		$imgeror = array();
		$a['upload_path']=$url;
		if (!is_dir($url)) {
    		mkdir($url);
    		for ($i=0; $i < count($_FILES['foto']['name']); $i++) { 
				$a['file_name'] =substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ23456789'),0,10);
        		$this->load->library('upload', $a);
				$this->upload->initialize($a,TRUE);
				$_FILES['userup']['name'] = $_FILES['foto']['name'][$i];  
	            $_FILES['userup']['type'] = $_FILES['foto']['type'][$i];  
	            $_FILES['userup']['tmp_name'] = $_FILES['foto']['tmp_name'][$i];  
	            $_FILES['userup']['error'] = $_FILES['foto']['error'][$i];  
	            $_FILES['userup']['size'] = $_FILES['foto']['size'][$i]; 
	            $this->upload->do_upload('userup');
	            $imgeror=$this->upload->display_errors();
	            if ($imgeror!=NULL) {
	            	rmdir($url);
	            	$not = array(
						'tipe' => 3,
						'isi' => "Input Data Gagal ".$imgeror
					);
					$this->session->set_flashdata('notif',$not );
					redirect('ruangan/tambah');
					break;
	            }
	        }
	            	$nmfil=$data['nmfile']=substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ23456789'),0,12);
	            	$in=$this->Mruang->save_ruang($data);
	            	$b['upload_path']='./harta/pinjam/dataruang/';
					$b['allowed_types'] ='application|pdf';
					$b['file_name'] =$nmfil;
					$b['max_size'] =2048;
					$b['overwrite'] = TRUE;
					$this->load->library('upload', $b);
					$this->upload->initialize($b,TRUE);
					$un=$this->upload->do_upload('dokumen');
					$dokeror=$this->upload->display_errors();
					if($in&&$un){
							$not = array(
								'tipe' => 1,
								'isi' => "Input Ruangan Sukses"
								  );
								$this->session->set_flashdata('notif',$not );
								redirect('ruangan');
					}else if($in && ($dokeror != NULL)){
							$not = array(
								'tipe' => 2,
								'isi' => "Input Ruangan Sukses, upload Foto Gagal".$dokeror
								  );
								$this->session->set_flashdata('notif',$not );
								redirect('ruangan');
					}else{
							$not = array(
									'tipe' => 3,
									'isi' => "Input Ruangan Gagal"
									  );
								$this->session->set_flashdata('notif',$not );
								redirect('ruangan');
						}
	          
        	

    		
		}else{
			$not = array(
				'tipe' => 2,
				'isi' => "Nama Ruangan Telah Terdaftar"
				  );
			$this->session->set_flashdata('notif',$not );
			redirect('ruangan');
        	
		}

	}
	function simpan_edit(){
		
		$data=$this->input->post(NULL,TRUE);
		$this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
		$this->form_validation->set_rules('narung', 'Nama Ruangan', 'required|callback_is_uniq_ruang');
		
		if (  ($data['narung'] != $data['narungold'] && $this->form_validation->run() == true)||($data['narung'] == $data['narungold']) && ($this->form_validation->run() == FALSE) ) {
			if (!is_dir('./harta/pinjam/dataruang/'.$data['narungold'])) {
    			mkdir('./harta/pinjam/dataruang/'.$data['narungold']);
    		}
			if ($data['narung'] != $data['narungold']) {
				rename('./harta/pinjam/dataruang/'.$data['narungold'], './harta/pinjam/dataruang/'.$data['narung']);
			}
			
			if (isset($_FILES['dokumen']) && $_FILES['dokumen']['tmp_name'] != NULL) {
				if ($data['nmdoklama']!=NULL) {
					$nmmfil=$data['nmdoklama'];
				}else{
					$data['nmdoklama']=$nmmfil=substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ23456789'),0,12);
				}
				$b['upload_path']='./harta/pinjam/dataruang/';
				$b['allowed_types'] ='application|pdf';
				$b['file_name'] =$nmmfil;
				$b['max_size'] =2048;
				$b['overwrite'] = TRUE;
				$this->load->library('upload', $b);
				$this->upload->initialize($b,TRUE);
				$un=$this->upload->do_upload('dokumen');
				$dokeror=$this->upload->display_errors();
				if ($un && isset($_FILES['foto']) && $_FILES['foto']['tmp_name'][0] != NULL){
			        $hbg=str_replace(" ","",$data['narung']);
					$url='./harta/pinjam/dataruang/'.strtoupper($hbg);
					rename('./harta/pinjam/dataruang/'.strtoupper(str_replace(" ","",$data['narungold'])), $url);
			        $a['allowed_types'] ='jpg|gif|png';
					$a['max_size'] =5048;
					$imgeror = array();
					$a['upload_path']=$url;
			        for ($i=0; $i < count($_FILES['foto']['name']); $i++) { 
						$a['file_name'] =substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ23456789'),0,10);
		        		$this->load->library('upload', $a);
						$this->upload->initialize($a,TRUE);
						$_FILES['userup']['name'] = $_FILES['foto']['name'][$i];  
			            $_FILES['userup']['type'] = $_FILES['foto']['type'][$i];  
			            $_FILES['userup']['tmp_name'] = $_FILES['foto']['tmp_name'][$i];  
			            $_FILES['userup']['error'] = $_FILES['foto']['error'][$i];  
			            $_FILES['userup']['size'] = $_FILES['foto']['size'][$i]; 
			            $this->upload->do_upload('userup');
			            $imgeror=$this->upload->display_errors();
			            if ($imgeror!=NULL) {
			            	$not = array(
								'tipe' => 3,
								'isi' => "Edit Data dan Upload Foto Gagal ".$imgeror
							);
							
							$this->session->set_flashdata('notif',$not);
							
							$dxc=$this->encryption->encrypt( base64_encode($data['id']) ); 
							$ff=str_replace( array('+','/','='),array('-','_','~'),$dxc ); 
							redirect('ruangan/edit/'.$ff);
							break;
			            }
			        }
	            	
	            	$in=$this->Mruang->save_e_ruang($data);
					if($in){
						$not = array(
							'tipe' => 1,
							'isi' => "Edit Ruangan Sukses"
							  );
							$this->session->set_flashdata('notif',$not );
							redirect('ruangan');
					}else{
						$not = array(
								'tipe' => 3,
								'isi' => "Edit Kendaraan Gagal"
								  );
							$this->session->set_flashdata('notif',$not );
							redirect('ruangan');
					}
			            
		        	
				}else{
					$in=$this->Mruang->save_e_ruang($data);
						if($in){
							$not = array(
								'tipe' => 1,
								'isi' => "Edit Kendaraan Sukses"
								  );
								$this->session->set_flashdata('notif',$not );
								redirect('ruangan');
						}else{
							$not = array(
									'tipe' => 3,
									'isi' => "Edit Kendaraan Gagal"
									  );
								$this->session->set_flashdata('notif',$not );
								redirect('ruangan');
						}
				}
			}
			else if (isset($_FILES['foto']) && $_FILES['foto']['tmp_name'][0] != NULL){
			         $hbg=str_replace(" ","",$data['narung']);
					$url='./harta/pinjam/dataruang/'.strtoupper($hbg);
					rename('./harta/pinjam/dataruang/'.strtoupper(str_replace(" ","",$data['narungold'])), $url);
					$a['allowed_types'] ='jpg|gif|png';
					$a['max_size'] =5048;
					$imgeror = array();
					$a['upload_path']=$url;
			        for ($i=0; $i < count($_FILES['foto']['name']); $i++) { 
						$a['file_name'] =substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ23456789'),0,10);
		        		$this->load->library('upload', $a);
						$this->upload->initialize($a,TRUE);
						$_FILES['userup']['name'] = $_FILES['foto']['name'][$i];  
			            $_FILES['userup']['type'] = $_FILES['foto']['type'][$i];  
			            $_FILES['userup']['tmp_name'] = $_FILES['foto']['tmp_name'][$i];  
			            $_FILES['userup']['error'] = $_FILES['foto']['error'][$i];  
			            $_FILES['userup']['size'] = $_FILES['foto']['size'][$i]; 
			            $this->upload->do_upload('userup');
			            $imgeror=$this->upload->display_errors();
			            if ($imgeror!=NULL) {
			            	$not = array(
								'tipe' => 3,
								'isi' => "Edit Data dan Upload Foto Gagal ".$imgeror
							);
							$this->session->set_flashdata('notif',$not );
							$dxc=$this->encryption->encrypt(base64_encode($data['id'])); 
							$ff=str_replace(array('+','/','='),array('-','_','~'),$dxc); 
							redirect('ruangan/edit/'.$ff);
							break;
			            }
			        }
	            	$in=$this->Mruang->save_e_ruang($data);
					if($in){
						$not = array(
							'tipe' => 1,
							'isi' => "Edit Ruangan Sukses"
							  );
							$this->session->set_flashdata('notif',$not );
							redirect('ruangan');
					}else{
						$not = array(
								'tipe' => 3,
								'isi' => "Edit Ruangan Gagal"
								  );
							$this->session->set_flashdata('notif',$not );
							redirect('ruangan');
					}
			            
		        	
			}
			else{
				$in=$this->Mruang->save_e_ruang($data);
				if($in){
					$not = array(
						'tipe' => 1,
						'isi' => "Edit Ruangan Sukses"
					);
					$this->session->set_flashdata('notif',$not );
					redirect('ruangan');
				}else{
					$not = array(
						'tipe' => 3,
						'isi' => "Edit Ruangan Gagal"
					);
						$this->session->set_flashdata('notif',$not );
						redirect('ruangan');
				}
			}
		}
		else{
			$not = array(
					'tipe' => 3,
					'isi' => "Inputan Tidak Valid, Nama Ruangan Telah Terdaftar"
					  );
				$this->session->set_flashdata('notif',$not );
			$dxc=$this->encryption->encrypt( base64_encode($data['id']) ); 
			$ff=str_replace( array('+','/','='),array('-','_','~'),$dxc ); 
			redirect('ruangan/edit/'.$ff);
		}


	}
	function foto($wd){
		$ida=str_replace(array('-','_','~'),array('+','/','='),$wd);
		$d=base64_decode($this->encryption->decrypt($ida));
		$has['data']=$this->Mruang->get_data_ruang_by_id($d)->result();
		$ghj=$this->load->view('pinjam/page/v-edit-foto-ruang',$has,TRUE);
		$this->konten($ghj);
	}
	function ruang_del_foto(){
	 	$data=$this->input->post(NULL,TRUE);
	 	/*echo "<pre>";
	 	print_r($data);
	 	echo "</pre>";*/

 		$hbg=str_replace(" ","",$data['narung']);
		$url='./harta/pinjam/dataruang/'.strtoupper($hbg).'/';

	 	for ($i=0; $i < count($data['nmfoto']) ; $i++) { 
	 		$hmk=unlink($url.$data['nmfoto'][$i]);
	 	}
	 	if ($hmk) {
	 		$not = array(
				'tipe' => 1,
				'isi' => "Sukses Hapus Foto Ruangan"
			);
			$this->session->set_flashdata('notif',$not);
			redirect('ruangan/foto/'.$data['id']);
 		}else{
 			$not = array(
				'tipe' => 3,
				'isi' => "Foto Ruangan Gagal Hapus"
				  );
			$this->session->set_flashdata('notif',$not);
			redirect('ruangan/foto/'.$data['id']);
 		}
	}
	function ruang_up_foto(){
			$data=$this->input->post(NULL,TRUE);

			/*echo "<pre> mobil uP<br>";
		 	print_r($data);
		 	print_r($_FILES);

		 	echo "</pre>";*/

		 	$hbg=str_replace(" ","",$data['narung']);
			$url='./harta/pinjam/dataruang/'.strtoupper($hbg);
			if (!is_dir($url)) {
				mkdir($url);
			}
			$a['allowed_types'] ='jpg|gif|png';
			$a['max_size'] =5048;
			$imgeror = array();
			$a['upload_path']=$url;
	        for ($i=0; $i < count($_FILES['foto']['name']); $i++) { 
				$a['file_name'] =substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ23456789'),0,10);
	    		$this->load->library('upload', $a);
				$this->upload->initialize($a,TRUE);
				$_FILES['userup']['name'] = $_FILES['foto']['name'][$i];  
	            $_FILES['userup']['type'] = $_FILES['foto']['type'][$i];  
	            $_FILES['userup']['tmp_name'] = $_FILES['foto']['tmp_name'][$i];  
	            $_FILES['userup']['error'] = $_FILES['foto']['error'][$i];  
	            $_FILES['userup']['size'] = $_FILES['foto']['size'][$i]; 
	            $inp=$this->upload->do_upload('userup');
	            $imgeror=$this->upload->display_errors();
	            if ($imgeror!=NULL) {
	            	$not = array(
						'tipe' => 3,
						'isi' => "Upload Foto Kendaraan Gagal ".$imgeror
					);
					$this->session->set_flashdata('notif',$not );
					redirect('ruangan/foto/'.$data['id']);
					break;
	            }
			}
			if($inp){
	        	$not = array(
				'tipe' => 1,
				'isi' => "Upload Foto Kendaraan Sukses"
				  );
				$this->session->set_flashdata('notif',$not );
				redirect('ruangan/foto/'.$data['id']);
	        }
	        else{
				$not = array(
					'tipe' => 3,
					'isi' => "Upload Foto Kendaraan Gagal"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('ruangan/foto/'.$data['id']);
	        }
		}
	function hapus(){
		$this->load->helper('file');
		$data=$this->input->post(NULL,TRUE);

		/*echo "<pre>";
		print_r($data);
		echo "</pre><br>";

		$urla='./harta/pinjam/dataruang/'.$data['nmfile'].".pdf";
		$hmka=unlink($urla);
		print_r($hmka);*/
		$hbg=str_replace(" ","",$data['narungmod']);
		$url='./harta/pinjam/dataruang/'.strtoupper($hbg);
		$urla='./harta/pinjam/dataruang/'.$data['nmfile'].".pdf";
		$hmk=delete_files($url);
		$hmka=unlink($urla);
		if (!file_exists(FCPATH."harta/pinjam/dataruang/".$data['nmfile'].".pdf")){
			$hmka=TRUE;
		}
		$fgt=rmdir($url);
		if ($fgt && $hmka) {
            $in=$this->Mruang->hapus($data['id']);
            if($in){
        	$not = array(
				'tipe' => 1,
				'isi' => "Hapus Alat Sukses"
				  );
				$this->session->set_flashdata('notif',$not );
				redirect('ruangan');
	        }
	        else{
				$not = array(
					'tipe' => 3,
					'isi' => "Hapus Foto Alat Gagal"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('ruangan');
	        }
		}else{
			$not = array(
				'tipe' => 3,
				'isi' => "Hapus Foto Alat Gagal, Folder Foto Tidak Ditemukan"
				  );
			$this->session->set_flashdata('notif',$not );
			redirect('ruangan');	
		}
	}

}
