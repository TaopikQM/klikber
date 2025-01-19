<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Pasien extends CI_Controller {
 
	public function __construct(){
		parent::__construct();
		/*$hjk=$this->session->userdata(base64_encode('jajahan'));
		$sec=FALSE;
		foreach ($hjk as $kaa) {
			if ($kaa[base64_encode('apli')]==base64_encode('1_morsip')) {
				$sec=TRUE;
			}
		}*/
		// $sec=$this->session->userdata('idus');
        
		$sec=$this->session->userdata('role');
		if ($sec == NULL) {
			redirect('landing/menu');
		}elseif ($sec != 'Pasien' && $sec != 'Admin') {
            redirect('landing/menu');
        }
		date_default_timezone_set("Asia/Jakarta");
		$this->load->model('Klinik/MPasien');
        $this->load->model('Klinik/MUsers');
        $this->load->model('Klinik/MPoli');
        $this->load->model('Klinik/MDokter');
        $this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('encryption');
		
		$this->encryption->initialize(
	        array(
	                'cipher' => 'aes-128',
	                'mode' => 'ctr',
	                'key' => 'HJKHASJKD^**&&*(NJSHAHIDAsdfsa'
	        )
		);
		// date_default_timezone_set("Asia/Jakarta");
	}
	
	function konten($value=''){
		$data['konten']=$value;
		$this->load->view('Klinik/home',$data);
	}

    public function indexx() {
		$this->load->helper('pinjam');		
        $data['pasiens'] = $this->MPasien->get_all();
        $ghj=$this->load->view('klinik/page/v_data_pasien', $data,TRUE);
		$this->konten($ghj);	//v_data_pasien
    }
    public function tabu() {
		$this->load->helper('pinjam');		
        $data['pasiens'] = $this->MPasien->get_all();
        $ghj=$this->load->view('klinik/page/v_data_pasien', $data,TRUE);
		$this->konten($ghj);	//v_data_pasien
    }
    //dengan username
    public function tab() {
		$this->load->helper('pinjam');		
        $data['pasiens'] = $this->MPasien->get_all_with_username();
        $ghj=$this->load->view('klinik/page/v_data_pasien', $data,TRUE);
		$this->konten($ghj);	//v_data_pasien
    }

   
    public function create() {
        // Ambil ID pasien terakhir
        $last_id_pasien = $this->MPasien->get_last_id_pasien();
    
        // Tentukan ID pasien baru (tambah 1 dari ID terakhir)
        $new_id_pasien = $last_id_pasien + 1;
    
        // Generate nomor RM berdasarkan ID pasien yang baru
        $formatted_id = str_pad($new_id_pasien, 3, '0', STR_PAD_LEFT);
        $tahun = date('Y');
        $bulan = date('m');
        $no_rm = $tahun . $bulan . '-' . $formatted_id;
        $username = 'P' . $tahun . '-' . $formatted_id;
        $password = 'P' . $tahun . '-' . $formatted_id;
    
        // Kirim data ke view
        $data = [
            'new_id_pasien' => $new_id_pasien, // ID pasien baru
            'no_rm' => $no_rm, // Nomor RM yang baru
            'username' => $username, // Username yang baru
			'password' => $password,
        ];
    
        // Load view dan tampilkan data
        $ghj = $this->load->view('klinik/page/in-pasien', $data, TRUE);
        $this->konten($ghj);
    }
    
    public function storee() {
        // Validasi input
        $this->form_validation->set_rules('nama', 'Nama Pasien', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('no_ktp', 'No KTP', 'required');
        $this->form_validation->set_rules('no_hp', 'No HP', 'required');
		$this->form_validation->set_rules('no_rm', 'NO RM', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            $this->create();
            $this->session->set_flashdata('notif', [
                'tipe' => 3, // alert-primary
                'isi' => 'Data Pasien gagal ditambah.'
            ]);
        } else {
            // Generate nomor RM berdasarkan ID pasien
            // $tahun = date('Y');
            // $bulan = date('m');
            
            // Data untuk tabel pasien
            
            $data_pasien = [
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'no_ktp' => $this->input->post('no_ktp'),
                'no_hp' => $this->input->post('no_hp'),
                'no_rm' => $this->input->post('no_rm')
            ];
            // Simpan data pasien ke database
            $this->MPasien->insert($data_pasien);

           // Data untuk tabel user
           $username = $this->input->post('username'); // Gunakan no_rm sebagai username
           $password = $this->input->post('username');
            
            $data_user = [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'id_role' => 2, // Role default dokter
            ];
    
            // Simpan data user ke tabel user
            $this->MUsers->insert($data_user);
            $this->session->set_flashdata('notif', [
                'tipe' => 1, // alert-primary
                'isi' => 'Data Pasien berhasil ditambah. '
            ]);
    
            // Redirect setelah penyimpanan berhasil
            redirect('pasien/tab');
        }
    }

    public function store() {
        // Validasi input
        $this->form_validation->set_rules('nama', 'Nama Pasien', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('no_ktp', 'No KTP', 'required');
        $this->form_validation->set_rules('no_hp', 'No HP', 'required');
        $this->form_validation->set_rules('no_rm', 'NO RM', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('notif', [
                'tipe' => 3, // alert-primary
                'isi' => 'Data Pasien gagal ditambah. Periksa kembali inputan Anda.'
            ]);
            redirect('pasien/create');
        } else {
            // Periksa apakah no_ktp sudah ada di database
            $no_ktp = $this->input->post('no_ktp');
            $existing_pasien = $this->MPasien->get_by_ktp($no_ktp);
            
            if ($existing_pasien) {
                // Jika no_ktp sudah ada, beri notifikasi dan kembalikan ke form
                $this->session->set_flashdata('notif', [
                    'tipe' => 3, // alert-danger
                    'isi' => 'Data Pasien gagal ditambah. No KTP sudah terdaftar.'
                ]);
                redirect('pasien/create');
            } else {
                // Data untuk tabel pasien
                $data_pasien = [
                    'nama' => $this->input->post('nama'),
                    'alamat' => $this->input->post('alamat'),
                    'no_ktp' => $no_ktp,
                    'no_hp' => $this->input->post('no_hp'),
                    'no_rm' => $this->input->post('no_rm')
                ];
                $this->MPasien->insert($data_pasien);
    
                // Data untuk tabel user
                $username = $this->input->post('username');//$this->input->post('no_rm'); // Gunakan no_rm sebagai username
                $password = $this->input->post('username');//$this->input->post('no_rm');
                
                $data_user = [
                    'username' => $username,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'id_role' => 2, // Role default pasien
                ];
                $this->MUsers->insert($data_user);
    
                $this->session->set_flashdata('notif', [
                    'tipe' => 1, // alert-success
                    'isi' => 'Data Pasien berhasil ditambah.'
                ]);
                redirect('pasien/tab');
            }
        }
    }
    
    
    

    public function edit($id) {
        
		$ida=str_replace(array('-','_','~'),array('+','/','='),$id);
		$d=base64_decode($this->encryption->decrypt($ida));
		$has['pasien']=$this->MPasien->get_by_id($d)->result();
       $ghj=$this->load->view('klinik/page/v-edit-pasien',$has,TRUE);
		$this->konten($ghj);
    }

	public function update() {
		// Validasi input
		$id = $this->input->post('id');
		$this->form_validation->set_rules('nama', 'Nama Pasien', 'required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required');
		$this->form_validation->set_rules('no_ktp', 'No KTP', 'required');
		$this->form_validation->set_rules('no_hp', 'No HP', 'required');
		$this->form_validation->set_rules('no_rm', 'NO RM', 'required');
	
		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal, tampilkan kembali form edit
		// 	$data['pasien'] = $this->MPasien->get_by_id($id);
        //    $this->load->view('klinik/page/v-edit-pasien', $data);
        $this->session->set_flashdata('notif', [
            'tipe' => 1, // alert-primary
            'isi' => 'Data Pasien berhasil diperbarui. '
        ]);
        redirect('pasien/profile');
		} else {
			// Jika validasi berhasil, lanjutkan dengan update
			$data = [
				'nama' => $this->input->post('nama'),
				'alamat' => $this->input->post('alamat'),
				'no_ktp' => $this->input->post('no_ktp'),
                'no_hp' => $this->input->post('no_hp'),
                'no_rm' => $this->input->post('no_rm')
			];
			$this->MPasien->update($id, $data);
            // Ambil data pasien yang baru setelah update
            $updated_pasien = $this->MPasien->get_by_id($id);
            // Simpan data pasien terbaru di session jika perlu
            $this->session->set_userdata('user_data', $updated_pasien);

            $this->session->set_flashdata('notif', [
                'tipe' => 1, // alert-primary
                'isi' => 'Data Pasien berhasil diperbarui. '
            ]);
			redirect('pasien/profile');
            
            // $this->load->view('pasien/profile');
		}
	}
   
	public function update_a() {
		// Validasi input
		$id = $this->input->post('id');
		$this->form_validation->set_rules('nama', 'Nama Pasien', 'required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required');
		$this->form_validation->set_rules('no_ktp', 'No KTP', 'required');
		$this->form_validation->set_rules('no_hp', 'No HP', 'required');
		$this->form_validation->set_rules('no_rm', 'NO RM', 'required');
	
		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal, tampilkan kembali form edit
		// 	$data['pasien'] = $this->MPasien->get_by_id($id);
        //    $this->load->view('klinik/page/v-edit-pasien', $data);
        $this->session->set_flashdata('notif', [
            'tipe' => 3, // alert-primary
            'isi' => 'Data Pasien gagal diperbarui. '
        ]);
        redirect('pasien/tab');
		} else {
			// Jika validasi berhasil, lanjutkan dengan update
			$data = [
				'nama' => $this->input->post('nama'),
				'alamat' => $this->input->post('alamat'),
				'no_ktp' => $this->input->post('no_ktp'),
                'no_hp' => $this->input->post('no_hp'),
                'no_rm' => $this->input->post('no_rm')
			];
			$this->MPasien->update($id, $data);
            // Ambil data pasien yang baru setelah update
            $updated_pasien = $this->MPasien->get_by_id($id);
            // Simpan data pasien terbaru di session jika perlu
            $this->session->set_userdata('user_data', $updated_pasien);

            $this->session->set_flashdata('notif', [
                'tipe' => 1, // alert-primary
                'isi' => 'Data Pasien berhasil diperbarui. '
            ]);
            // $this->load->view('pasien/tab');
			redirect('pasien/tab');
		}
	}

    public function delete() {
		
		$id = $this->input->post('id');
        $this->MPasien->delete($id);
        redirect('pasien/tab');
    }

    public function profile() {
        $user_data = $this->session->userdata('user_data');

		if ($user_data) {
            $data = [
                'username' => $this->session->userdata('useryyy'),
                'role' => $this->session->userdata('role'),
                'idus' => $this->session->userdata('idus'),
                'id_filtered' => $this->session->userdata('id_filtered'),
                'user_data' => $user_data,
                'name' => $user_data->nama,
				'id' => $user_data->id // Nama user
            ];

          	$ghj=$this->load->view('klinik/page/profile', $data, true);
			$this->konten($ghj);
        } else {
            // Jika user_data tidak ada, redirect ke halaman login
            redirect('landing');
        }
    }

    public function editUs($id) {
        $ida=str_replace(array('-','_','~'),array('+','/','='),$id);
		$d=base64_decode($this->encryption->decrypt($ida));
		$has['users']=$this->MUsers->get_by_id($d)->result();
		$ghj=$this->load->view('klinik/page/v-edit-users',$has,TRUE);
		$this->konten($ghj);
    }

    public function updateUs() {
        
		$idus = $this->input->post('idus');
        
		// $data = $this->input->post('data');
        // $this->db->where('id', $id);
        // return $this->db->update('users', $data);
         // Set aturan validasi untuk perubahan password
         $this->form_validation->set_rules('old_password', 'Password Lama', 'required');
         $this->form_validation->set_rules('new_password', 'Password Baru', 'required|min_length[8]');
         $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password Baru', 'required|matches[new_password]');
         if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, set flashdata dengan pesan error dan redirect
            $this->session->set_flashdata('notif_password', validation_errors());
            // redirect('klinik/page/v-edit-users');
            // redirect(strtolower($this->session->userdata('role')) . '/editUs/' . $idus);
             // Buat URL aman untuk redirect
            $encrypted_idus = $this->encryption->encrypt(base64_encode($idus));
            $secure_id = str_replace(array('+', '/', '='), array('-', '_', '~'), $encrypted_idus);
            $redirect_url = base_url() . $role . '/editUs/' . $secure_id;

            redirect($redirect_url);
        } else {
            // $user = $this->mlogin->check_user($username);
             // Ambil data pengguna berdasarkan ID
            $user = $this->MUsers->get_user_by_id($idus);
        
            $old_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');
            if ($user && password_verify($old_password, $user->password)) {
                // Jika password lama cocok, update password
                if ($this->MUsers->update_password($idus, $new_password)) {
                    $this->session->set_flashdata('notif_password', 'Password berhasil diubah');
                } else {
                    $this->session->set_flashdata('notif_password', 'Gagal mengubah password');
                }
                redirect('landing/menu');
            } else {
                $this->session->set_flashdata('notif_password', 'Password salah');
                redirect('landing#form-section');
            }
            
            // redirect('landing/menu');
        }
    }
    // Halaman daftar poli
    public function daftarPoli() {
        $user_data = $this->session->userdata('user_data');
 
         if ($user_data) {
             $data = [
                 'id' => $user_data->id // Nama user
             ];
             $data['poli'] = $this->MPoli->get_all();
               $ghj=$this->load->view('klinik/page/in-daftarpoli', $data,true);
             $this->konten($ghj);
         } else {
             // Jika user_data tidak ada, redirect ke halaman login
             redirect('landing');
         }
       
    }

    // Ambil dokter berdasarkan poli
    public function getDokter() {
        $id_poli = $this->input->post('id_poli');
        $dokter = $this->MDokter->getDokterByPoli($id_poli);
        echo json_encode($dokter);
    }

    // Ambil jadwal berdasarkan dokter
    public function getJadwal() {
        $id_dokter = $this->input->post('id_dokter');
        $jadwal = $this->MDokter->getJadwalByDokter($id_dokter);
        echo json_encode($jadwal);
    }

    public function getDokterJadwal()
    {
        $id_poli = $this->input->post('id_poli');

        if ($id_poli) {
            $data = $this->MDokter->getDokterJadwalByPoli($id_poli);
            echo json_encode($data); 
        } else {
            echo json_encode([]);
        }
    }


    public function riwayat() {
      
        $user_data = $this->session->userdata('user_data');
 
         if ($user_data) {
             $data = [
                 'id' => $user_data->id // Nama user
             ];
 
             
                //  $data['riwayat'] = $this->MPasien->getRiwayatByPasien($user_data->id);
                $riwayat = $this->MPasien->getRiwayatByPasien($user_data->id);
                // foreach ($riwayat as &$r) {
                //     $r['status'] = $this->MPasien->isSudahDiperiksa($r['id']) ? 'Sudah Diperiksa' : 'Belum Diperiksa';
                // }
                $data['riwayat'] = $riwayat;
               $ghj=$this->load->view('klinik/page/v_data_riwayat', $data,true);
               
             $this->konten($ghj);
         } else {
             // Jika user_data tidak ada, redirect ke halaman login
             redirect('landing');
         }
     
         
     }
     

    public function mendaftar() {
        // $id_jadwal = $this->input->post('id_jadwal');
        $id_jadwal = $this->input->post('id_jadwal');
        $id_pasien = $this->input->post('id_pasien');
        $keluhan = $this->input->post('keluhan');

        // Generate nomor antrian
        $no_antrian = $this->MPasien->generateNomorAntrian($id_jadwal);

        // Simpan ke database
        $data = [
            'id_jadwal' => $id_jadwal,
            'id_pasien' => $id_pasien,
            'keluhan' => $keluhan,
            'no_antrian' => $no_antrian
        ];
        $this->MPasien->saveDaftarPoli($data);
        $this->session->set_flashdata('notif', [
            'tipe' => 1, // alert-primary
            'isi' => 'Pendaftaran berhasil! Nomor antrian Anda: ' . $no_antrian
        ]);
        redirect('pasien/riwayat');
    }

    public function daftar_riwayat($id_pasien) {
        $data['riwayat'] = $this->MPasien->getRiwayatByPasien($id_pasien);
        $ghj=$this->load->view('klinik/page/v_data_riwayat', $data,TRUE);
		$this->konten($ghj);
    }
    
    public function riwayat_pasien() {
      
        $user_data = $this->session->userdata('user_data');
 
         if ($user_data) {
             $data = [
                 'id' => $user_data->id // Nama user
             ];
 
             
                //  $data['riwayat'] = $this->MPasien->getRiwayatByPasien($user_data->id);
                $riwayat = $this->MPasien->getRiwayatPeriksa($user_data->id);
                foreach ($riwayat as &$r) {
                    $r->status = $this->MPasien->isSudahDiperiksa($r->id) ? 'Sudah Diperiksa' : 'Belum Diperiksa';
                }
                $data['periksa'] = $riwayat;
               $ghj=$this->load->view('klinik/page/v_data_riwayat_periksa', $data,true);
               
             $this->konten($ghj);
         } else {
             // Jika user_data tidak ada, redirect ke halaman login
             redirect('landing');
         }
     
         
    }

    //konsultasi
    public function konsultasi() {		
       
        $user_data = $this->session->userdata('user_data');
 
         if ($user_data) {
             $data = [
                 'id' => $user_data->id // Nama user
             ];
 
             
                //  $data['riwayat'] = $this->MPasien->getRiwayatByPasien($user_data->id);
                $pasiens = $this->MPasien->getKonsultasi($user_data->id);
                // foreach ($riwayat as &$r) {
                //     $r['status'] = $this->MPasien->isSudahDiperiksa($r['id']) ? 'Sudah Diperiksa' : 'Belum Diperiksa';
                // }
                $data['pasiens'] = $pasiens;
               $ghj=$this->load->view('klinik/page/v_data_konsultasi', $data,true);
               
             $this->konten($ghj);
         } else {
             // Jika user_data tidak ada, redirect ke halaman login
             redirect('landing');
         }
    }

    public function Skonsultasi() {
        $user_data = $this->session->userdata('user_data');
        // $id_jadwal = $this->input->post('id_jadwal');
        $id_dokter = $this->input->post('id_dokter');
        $id_pasien = $user_data->id;
        $subject = $this->input->post('subject');
        $pertanyaan = $this->input->post('pertanyaan');


        $data = [
            
            'subject' => $subject,
            'pertanyaan' => $pertanyaan,
            'tgl_konsultasi' =>  date('Y-m-d H:i:s'),
            'id_pasien' => $id_pasien,
            'id_dokter' => $id_dokter,
        ];
        $this->MPasien->saveDaftarKonsul($data);
        $this->session->set_flashdata('notif', [
            'tipe' => 1, // alert-primary
            'isi' => 'Konsultasi berhasil! '
        ]);
        redirect('pasien/konsultasi');
    }
    public function addKonsutasi() {
        $user_data = $this->session->userdata('user_data');
 
         if ($user_data) {
            //  $data = [
            //      'id_pasien' => $user_data->id // Nama user
            //  ];
             $data['dokter'] = $this->MDokter->get_all();
             $data['poli'] = $this->MPoli->get_all();
               $ghj=$this->load->view('klinik/page/in-konsultasi', $data,true);
             $this->konten($ghj);
         } else {
             // Jika user_data tidak ada, redirect ke halaman login
             redirect('landing');
         }
       
    }
    public function ekonsul($id) {
        
		$ida=str_replace(array('-','_','~'),array('+','/','='),$id);
		$d=base64_decode($this->encryption->decrypt($ida));
		$has['pasien']=$this->MPasien->getkonsul_by_id($d);
       $ghj=$this->load->view('klinik/page/v-edit-konsul',$has,TRUE);
		$this->konten($ghj);
    }

    public function updateKon() {
		// Validasi input
		$id = $this->input->post('idk');
		$this->form_validation->set_rules('subject', 'subject', 'required');
		$this->form_validation->set_rules('pertanyaan', 'pertanyaan', 'required');
		
		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal, tampilkan kembali form edit
		// 	$data['pasien'] = $this->MPasien->get_by_id($id);
        //    $this->load->view('klinik/page/v-edit-pasien', $data);
        $this->session->set_flashdata('notif', [
            'tipe' => 3, // alert-primary
            'isi' => 'Data Konsul gagal diperbarui. '
        ]);
        redirect('pasien/konsultasi');
		} else {
			// Jika validasi berhasil, lanjutkan dengan update
			$data = [
				'subject' => $this->input->post('subject'),
				'pertanyaan' => $this->input->post('pertanyaan'),
			];
		
			$this->MPasien->updateK($id, $data);
          
            $this->session->set_flashdata('notif', [
                'tipe' => 1, // alert-primary
                'isi' => 'Data Konsul berhasil diperbarui. '
            ]);
            // $this->load->view('pasien/tab');
			redirect('pasien/konsultasi');
		}
	}

    public function dkonsul() {
        $id = $this->input->post('id');
        $this->MPasien->deleteK($id);
        redirect('pasien/konsultasi');
    }

    public function getDokterK() {
        $id_poli = $this->input->post('id_poli');
        $dokter = $this->MDokter->getDokterByPoliK($id_poli);
        echo json_encode($dokter);
    }

}  