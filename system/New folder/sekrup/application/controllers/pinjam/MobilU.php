<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mobil extends CI_Controller {

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
		$this->load->model('pinjam/Mmobil');
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
		
		$data['datamobil']=$this->Mmobil->get_data_mobil()->result();
		$ghj=$this->load->view('pinjam/page/v_data_mobil',$data,TRUE);
		$this->konten($ghj);		
		

	}
	function tambah(){
		$ghj=$this->load->view('pinjam/page/in-mobil','',TRUE);
		$this->konten($ghj);
	}
	function edit($wd)
	{
		$ida=str_replace(array('-','_','~'),array('+','/','='),$wd);
		$d=base64_decode($this->encryption->decrypt($ida));
		$has['data']=$this->Mmobil->get_data_mobil_by_id($d)->result();
		$ghj=$this->load->view('pinjam/page/v-edit-mobil',$has,TRUE);
		$this->konten($ghj);
	}
	function foto($wd)
	{
		$ida=str_replace(array('-','_','~'),array('+','/','='),$wd);
		$d=base64_decode($this->encryption->decrypt($ida));
		$has['data']=$this->Mmobil->get_data_mobil_by_id($d)->result();
		$ghj=$this->load->view('pinjam/page/v-edit-foto-mobil',$has,TRUE);
		$this->konten($ghj);
	}
	/*function mobil($gt='',$wd=''){

		$this->load->helper('pinjam');
		if ($gt=="tambah") {
			
			echo "Halaman Tambah";	
		}else if($gt=='edit'){

		
		}else if($gt=='foto'){
			
		}
		else{		
						
		}

	}*/
	function save_mobil(){ //perlu dibkin hak akases input mobil
		$data=$this->input->post(NULL,TRUE);
		$hbg=strtoupper(str_replace(" ","",$data['nopol']));
		$url='./harta/pinjam/datamobil/'.$hbg;
		$a['allowed_types'] ='jpg|jpeg|gif|png';
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
					redirect('mobil/tambah');
					break;
	            }
	         }
	        $nmfil=$data['nmfile']=substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ23456789'),0,12);
        	$in=$this->Mmobil->save_mobil($data);
        	$b['upload_path']='./harta/pinjam/datamobil/';
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
						'isi' => "Input Kendaraan Sukses"
						  );
						$this->session->set_flashdata('notif',$not );
						redirect('mobil');
			}else if($in && ($dokeror != NULL)){
					$not = array(
						'tipe' => 2,
						'isi' => "Input Kendaraan Sukses, upload Foto Gagal".$dokeror
						  );
						$this->session->set_flashdata('notif',$not );
						redirect('mobil');
			}else{
					$not = array(
							'tipe' => 3,
							'isi' => "Input Kendaraan Gagal"
							  );
						$this->session->set_flashdata('notif',$not );
						redirect('mobil');
				}
		}else{
			$not = array(
				'tipe' => 2,
				'isi' => "Nomor Plat Kendaraan Telah Terdaftar"
				  );
			$this->session->set_flashdata('notif',$not );
			redirect('mobil');
        	
		}

	}
	function is_uniq_nopol($value){ 
		//print_r($this->Mruang->is_unique('ruangan','nmruang', $value));
		if ($this->Mmobil->is_unique('mobil','nopol', $value)) {
		    return TRUE;
		} else {
		    $this->form_validation->set_message('is_uniq_ruang', 'Inputan anda telah Terdaftar');
		    return FALSE;
		}
	}

	function save_e_mobil(){ //perlu dibkin hak akases input mobil
		$data=$this->input->post(NULL,TRUE);
		$this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
		$this->form_validation->set_rules('nopol', 'Nopol', 'required|callback_is_uniq_nopol');
		
		if (  ($data['nopol'] != $data['nopollama'] && $this->form_validation->run() == true)||($data['nopol'] == $data['nopollama']) && ($this->form_validation->run() == FALSE) ) {
			if ($data['nopol'] != $data['nopollama']) {
				
				rename('./harta/pinjam/datamobil/'.strtoupper(str_replace(" ","",$data['nopollama'])), './harta/pinjam/datamobil/'.strtoupper(str_replace(" ","",$data['nopol'])) );
			}
			
			if (isset($_FILES['dokumen']) && $_FILES['dokumen']['tmp_name'] != NULL) {
				if ($data['nmdoklama']!=NULL) {
					$nmmfil=$data['nmdoklama'];
				}else{
					$data['nmdoklama']=$nmmfil=substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ23456789'),0,12);
				}
				$b['upload_path']='./harta/pinjam/datamobil/';
				$b['allowed_types'] ='application|pdf';
				$b['file_name'] =$nmmfil;
				$b['max_size'] =2048;
				$b['overwrite'] = TRUE;
				$this->load->library('upload', $b);
				$this->upload->initialize($b,TRUE);
				$un=$this->upload->do_upload('dokumen');
				$dokeror=$this->upload->display_errors();
				if ($un && isset($_FILES['foto']) && $_FILES['foto']['tmp_name'][0] != NULL){
			        $hbg=str_replace(" ","",$data['nopol']);
					$url='./harta/pinjam/datamobil/'.$hbg;
					$a['allowed_types'] ='jpg|jpeg|gif|png';
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
							redirect('mobil/edit/'.$ff);
							break;
			            }
			        }
	            	
	            	$in=$this->Mmobil->save_e_mobil($data);
					if($in){
						$not = array(
							'tipe' => 1,
							'isi' => "Edit Kendaraan Sukses"
							  );
							$this->session->set_flashdata('notif',$not );
							redirect('mobil');
					}else{
						$not = array(
								'tipe' => 3,
								'isi' => "Edit Kendaraan Gagal"
								  );
							$this->session->set_flashdata('notif',$not );
							redirect('mobil');
					}
			            
		        	
				}else{
					$in=$this->Mmobil->save_e_mobil($data);
						if($in){
							$not = array(
								'tipe' => 1,
								'isi' => "Edit Kendaraan Sukses"
								  );
								$this->session->set_flashdata('notif',$not );
								redirect('mobil');
						}else{
							$not = array(
									'tipe' => 3,
									'isi' => "Edit Kendaraan Gagal"
									  );
								$this->session->set_flashdata('notif',$not );
								redirect('mobil');
						}
				}
			}
			else if (isset($_FILES['foto']) && $_FILES['foto']['tmp_name'][0] != NULL){
			        $hbg=str_replace(" ","",$data['nopol']);
					$url='./harta/pinjam/datamobil/'.$hbg;
					$a['allowed_types'] ='jpg|jpeg|gif|png';
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
							redirect('mobil/edit/'.$ff);
							break;
			            }
			        }
	            	$in=$this->Mmobil->save_e_mobil($data);
					if($in){
						$not = array(
							'tipe' => 1,
							'isi' => "Edit Kendaraan Sukses"
							  );
							$this->session->set_flashdata('notif',$not );
							redirect('mobil');
					}else{
						$not = array(
								'tipe' => 3,
								'isi' => "Edit Kendaraan Gagal"
								  );
							$this->session->set_flashdata('notif',$not );
							redirect('mobil');
					}
			}
			else{
				$in=$this->Mmobil->save_e_mobil($data);
				if($in){
					$not = array(
						'tipe' => 1,
						'isi' => "Edit Kendaraan Sukses"
					);
					$this->session->set_flashdata('notif',$not );
					redirect('mobil');
				}else{
					$not = array(
						'tipe' => 3,
						'isi' => "Edit Kendaraan Gagal"
					);
						$this->session->set_flashdata('notif',$not );
						redirect('mobil');
				}
			}
		}
		else{
			$not = array(
					'tipe' => 3,
					'isi' => "Inputan Tidak Valid, Nama Ruangan Telah Terdaftar"
					  );
			$this->session->set_flashdata('notif',$not );
			$dxc=$this->encryption->encrypt(base64_encode($data['id'])); 
			$ff=str_replace(array('+','/','='),array('-','_','~'),$dxc); 
			redirect('mobil/edit/'.$ff);
		}
		/*echo "<pre>";
		print_r($data);
		echo "</pre><br>";

		echo "<pre>";
		print_r($_FILES['dokumen']);
		echo "</pre><br>";

		echo "<pre>";
		print_r($_FILES['foto']);
		echo "</pre><br>";*/
		
	}
	function mobil_del_foto(){
	 	$data=$this->input->post(NULL,TRUE);
	 	
	 	if ($data['tipe']=="mobil") {
	 		$hbg=str_replace(" ","",$data['nopol']);
			$url='./harta/pinjam/datamobil/'.$hbg.'/';

		 	for ($i=0; $i < count($data['nmfoto']) ; $i++) { 
		 		$hmk=unlink($url.$data['nmfoto'][$i]);
		 	}
		 	if ($hmk) {
		 		$not = array(
					'tipe' => 1,
					'isi' => "Sukses Hapus Foto Kendaraan"
				);
				$this->session->set_flashdata('notif',$not);
				redirect('mobil/foto/'.$data['id']);
	 		}else{
	 			$not = array(
					'tipe' => 3,
					'isi' => "Foto Kendaraan Gagal Hapus"
					  );
				$this->session->set_flashdata('notif',$not);
				redirect('mobil/foto/'.$data['id']);
	 		}
	 	}

	 	

	 
	}
	function mobil_up_foto(){
		$data=$this->input->post(NULL,TRUE);

		/*echo "<pre> mobil uP<br>";
	 	print_r($data);
	 	print_r($_FILES);

	 	echo "</pre>";*/

	 	$hbg=str_replace(" ","",$data['nopol']);
		$url='./harta/pinjam/datamobil/'.$hbg;
		if (!is_dir($url)) {
			mkdir($url);
		}
		$a['allowed_types'] ='jpg|jpeg|gif|png';
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
				redirect('mobil/foto/'.$data['id']);
				break;
            }
		}
		if($inp){
        	$not = array(
			'tipe' => 1,
			'isi' => "Upload Foto Kendaraan Sukses"
			  );
			$this->session->set_flashdata('notif',$not );
			redirect('mobil/foto/'.$data['id']);
        }
        else{
			$not = array(
				'tipe' => 3,
				'isi' => "Upload Foto Kendaraan Gagal"
				  );
			$this->session->set_flashdata('notif',$not );
			redirect('mobil/foto/'.$data['id']);
        }
	} 

	function hapus_mobil(){
		$this->load->helper('file');
		$data=$this->input->post(NULL,TRUE);

		$hbg=str_replace(" ","",$data['nopol']);
		$url='./harta/pinjam/datamobil/'.$hbg;

		$hbg=str_replace(" ","",$data['nopol']);
		$url='./harta/pinjam/datamobil/'.strtoupper($hbg);
		$urla='./harta/pinjam/datamobil/'.$data['nmfile'].".pdf";
		$hmk=delete_files($url);
		$hmka=unlink($urla);
		if (!file_exists(FCPATH."harta/pinjam/datamobil/".$data['nmfile'].".pdf")){
			$hmka=TRUE;
		}
		$fgt=rmdir($url);
		/*print_r($url);

		if ( is_dir($url) ) {
			echo "<br>ini direktori";
            delete_files($url);
        }
        else {
        	echo "<br>Bukan direktori";
        }*/

		
		if ($hmk) {
            $in=$this->Mmobil->hapus_mobil($data['id']);
            if($in){
        	$not = array(
				'tipe' => 1,
				'isi' => "Hapus Kendaraan Sukses"
				  );
				$this->session->set_flashdata('notif',$not );
				redirect('mobil');
	        }
	        else{
				$not = array(
					'tipe' => 3,
					'isi' => "Upload Foto Kendaraan Gagal"
					  );
				$this->session->set_flashdata('notif',$not );
				redirect('mobil');
	        }
		}else{
			$not = array(
				'tipe' => 3,
				'isi' => "Hapus Kendaraan Gagal, Folder Foto Tidak Ditemukan"
				  );
			$this->session->set_flashdata('notif',$not );
			redirect('mobil');	
		}
	}

	public function detail($id) {
        $data['id'] = $id;
        $this->load->view('pinjam/page/v_show_data_riwayat_servis', $data);
    }

}

