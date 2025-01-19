 <?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Dokter extends CI_Controller {

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
		}elseif ($sec != 'Dokter' && $sec != 'Admin') {
            redirect('landing/menu');
        }
		date_default_timezone_set("Asia/Jakarta");
		$this->load->model('landing/mlogin');
		$this->load->model('Klinik/MDokter');
		$this->load->model('Klinik/MPasien');
		$this->load->model('Klinik/MObat');
        $this->load->model('Klinik/MPoli');
        $this->load->model('Klinik/MUsers');
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

    public function indexa() {
		$this->load->helper('pinjam');		
        $data['dokters'] = $this->MDokter->get_all();
        $ghj=$this->load->view('klinik/page/v_data_dokter', $data,TRUE);
		$this->konten($ghj);	//v_data_dokter
    }
    public function tabu() {
		$this->load->helper('pinjam');		
        $data['dokters'] = $this->MDokter->get_all();
        $ghj=$this->load->view('klinik/page/v_data_dokter', $data,TRUE);
		$this->konten($ghj);	//v_data_dokter
    }
    
    //dnegan username
    public function tab() {
        $this->load->model('MDokter');
    
        // Ambil data dokter dengan username
        $data['dokters'] = $this->MDokter->get_dokter_with_username();
    
        // Load view
        $ghj = $this->load->view('klinik/page/v_data_dokter', $data, TRUE);
        $this->konten($ghj);
    }
    
    public function create() {
        $data['polis'] = $this->MPoli->get_all();
    
        // Dapatkan ID terakhir dokter, tambahkan 1 untuk ID baru
        $last_id = $this->MDokter->get_last_id_dokter();
        $new_id = $last_id + 1;
    
        // Format username dan password dengan 3 angka
        $formatted_id = str_pad($new_id, 3, '0', STR_PAD_LEFT); // Contoh: '001', '002', '003'
        $username = 'D' . date('Y') . '-' . $formatted_id;
        $password = 'D' . date('Y') . '-' . $formatted_id;
    
        // Tambahkan username dan password ke data view
        $data['username'] = $username;
        $data['password'] = $password;
    
        // Muat view
        $ghj = $this->load->view('klinik/page/in-dokter', $data, TRUE);
        $this->konten($ghj);
    }
    
    public function store() {
        // Validasi input
        $this->form_validation->set_rules('nama', 'Nama Dokter', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('no_hp', 'No HP', 'required');
        $this->form_validation->set_rules('id_poli', 'Poli', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('notif', [
                'tipe' => 2, // alert-warning
                'isi' => 'Validasi gagal. Pastikan semua data diisi dengan benar.'
            ]);
            $this->create();
        } else {
            // Data untuk tabel dokter
            $data_dokter = [
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'no_hp' => $this->input->post('no_hp'),
                'id_poli' => $this->input->post('id_poli'),
            ];
    
            // Simpan data dokter ke tabel dokter
            $id_dokter = $this->MDokter->insert($data_dokter); // Ambil ID yang baru disimpan
    
            // Ambil username dan password dari input
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $data_user = [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'id_role' => 3, // Role default admin
            ];
            // Simpan data user ke tabel user
            $this->MUsers->insert($data_user);
            $this->session->set_flashdata('notif', [
                'tipe' => 1, // alert-primary
                'isi' => 'Data dokter berhasil ditambahkan.'
            ]);
    
            // Redirect setelah penyimpanan berhasil
            redirect('dokter/tab');
        }
    }
    

    public function edit($id) {
        
		$ida=str_replace(array('-','_','~'),array('+','/','='),$id);
		$d=base64_decode($this->encryption->decrypt($ida));
		$has['dokter']=$this->MDokter->get_by_id($d)->result();
        $has['polis'] = $this->MPoli->get_all();
		
		$ghj=$this->load->view('klinik/page/v-edit-dokter',$has,TRUE);
		$this->konten($ghj);
    }

	public function update() {
		// Validasi input
		$id = $this->input->post('id');
		$this->form_validation->set_rules('nama', 'Nama Dokter', 'required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required');
		$this->form_validation->set_rules('no_hp', 'No HP', 'required');
		$this->form_validation->set_rules('id_poli', 'ID Poli', 'required');
	
		if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('notif', [
                'tipe' => 2, // alert-warning
                'isi' => 'Validasi gagal. Pastikan semua data diisi dengan benar.'
            ]);
            
			// // Jika validasi gagal, tampilkan kembali form edit
			// $data['dokter'] = $this->MDokter->get_by_id($id);
            // $data['polis'] = $this->MPoli->get_all();
			// $this->load->view('klinik/page/v-edit-dokter', $data);
            $ida=str_replace(array('-','_','~'),array('+','/','='),$id);
            $d=base64_decode($this->encryption->decrypt($ida));
            $has['dokter']=$this->MDokter->get_by_id($d)->result();
            $has['polis'] = $this->MPoli->get_all();
            
            $ghj=$this->load->view('klinik/page/v-edit-dokter',$has);
		} else {
			// Jika validasi berhasil, lanjutkan dengan update
			$data = [
				'nama' => $this->input->post('nama'),
				'alamat' => $this->input->post('alamat'),
				'no_hp' => $this->input->post('no_hp'),
                'id_poli' => $this->input->post('id_poli')
			];
			$this->MDokter->update($id, $data);
            $this->session->set_flashdata('notif', [
                'tipe' => 1, // alert-primary
                'isi' => 'Data dokter berhasil diperbarui.'
            ]);
			redirect('dokter/profile');
		}
	}
    public function update_a() {
		// Validasi input
		$id = $this->input->post('id');
		$this->form_validation->set_rules('nama', 'Nama Dokter', 'required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required');
		$this->form_validation->set_rules('no_hp', 'No HP', 'required');
		$this->form_validation->set_rules('id_poli', 'ID Poli', 'required');
	
		if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('notif', [
                'tipe' => 2, // alert-warning
                'isi' => 'Validasi gagal. Pastikan semua data diisi dengan benar.'
            ]);
            
			// // Jika validasi gagal, tampilkan kembali form edit
			// $data['dokter'] = $this->MDokter->get_by_id($id);
            // $data['polis'] = $this->MPoli->get_all();
			// $this->load->view('klinik/page/v-edit-dokter', $data);
            $ida=str_replace(array('-','_','~'),array('+','/','='),$id);
            $d=base64_decode($this->encryption->decrypt($ida));
            $has['dokter']=$this->MDokter->get_by_id($d)->result();
            $has['polis'] = $this->MPoli->get_all();
            
            $ghj=$this->load->view('klinik/page/v-edit-dokter',$has);
		} else {
			// Jika validasi berhasil, lanjutkan dengan update
			$data = [
				'nama' => $this->input->post('nama'),
				'alamat' => $this->input->post('alamat'),
				'no_hp' => $this->input->post('no_hp'),
                'id_poli' => $this->input->post('id_poli')
			];
			$this->MDokter->update($id, $data);
            $this->session->set_flashdata('notif', [
                'tipe' => 1, // alert-primary
                'isi' => 'Data dokter berhasil diperbarui.'
            ]);
			redirect('dokter/tab');
		}
	}

    public function delete() {
		
		$id = $this->input->post('id');
        $this->MDokter->delete($id);
        redirect('dokter/tab');
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

    public function daftar_periksaa() {
        $data['jadwal'] = $this->MDokter->get_jadwal_periksa();
        $ghj=$this->load->view('klinik/page/v_data_jadwal', $data,true);
        $this->konten($ghj);
    }



    public function jadwal()
    {
        // Ambil data user dari session
        $user_data = $this->session->userdata('user_data');

        if ($user_data) {
            $id = $user_data->id;
        
        
            // Panggil model untuk mendapatkan jadwal periksa berdasarkan ID
            $data['jadwal'] = $this->MDokter->get_jadwal_by_user_id($id);

            // Kirim data ke view
            $ghj=$this->load->view('klinik/page/v_data_jadwal', $data,true);
            
        $this->konten($ghj);
        }
        else {
            // Jika user_data tidak ada, redirect ke halaman login
            redirect('landing');
        }
    }

    public function tambah_jadwal() {
      
       $user_data = $this->session->userdata('user_data');

		if ($user_data) {
            $data = [
				'id' => $user_data->id // Nama user
            ];

          	$ghj=$this->load->view('klinik/page/in-jadwal', $data,true);
			$this->konten($ghj);
        } else {
            // Jika user_data tidak ada, redirect ke halaman login
            redirect('landing');
        }
    
        
    }

    public function simpan_jadwal() {
        $data = [
            'id_dokter' => $this->input->post('id_dokter'),
            'hari' => $this->input->post('hari'),
            'jam_mulai' => $this->input->post('jam_mulai'),
            'jam_selesai' => $this->input->post('jam_selesai')
        ];

        // Validasi jadwal bentrok
        $result = $this->MDokter->cek_jadwal_bentrok($data);
        if ($result['status']) {
            $this->session->set_flashdata('notif', [
                'tipe' => 2,
                'isi' => $result['pesan']//'Jadwal bertabrakan dengan jadwal lainnya.'
            ]);
            redirect('dokter/jadwal');
            return;
        }
        $this->MDokter->simpan_jadwal($data);
        $this->session->set_flashdata('notif', [
            'tipe' => 1, // alert-primary
            'isi' => 'Jadwal berhasil ditambahkan.'
        ]);
        redirect('dokter/jadwal');
    }

    
    public function edit_jadwal($id) {
        
		$ida=str_replace(array('-','_','~'),array('+','/','='),$id);
		$d=base64_decode($this->encryption->decrypt($ida));
		$has['jadwal']=$this->MDokter->get_jadwal_by_id($d)->result();
		
		$ghj=$this->load->view('klinik/page/v-edit-jadwal',$has,TRUE);
		$this->konten($ghj);
    }
    

	public function update_jadwal() {
		$id = $this->input->post('id');
		// Validasi input
        $this->form_validation->set_rules('id_dokter', 'Dokter');
        $this->form_validation->set_rules('hari', 'Hari', 'required');
        $this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
        $this->form_validation->set_rules('jam_selesai', 'Jam Selesai', 'required');
	
		if ($this->form_validation->run() == FALSE) {
			$dxc=$this->encryption->encrypt( base64_encode($id) ); 
            $ff=str_replace( array('+','/','='),array('-','_','~'),$dxc ); 
                        
            redirect('dokter/edit_jadwal/'.$ff);
            $this->session->set_flashdata('notif', [
                'tipe' => 3,
                'isi' => 'Jadwal gagal diperbarui.'
            ]);

            return;
		} else {
			// Jika validasi berhasil, proses update
            $data = [
                'id_dokter' => $this->input->post('id_dokter'),
                'hari' => $this->input->post('hari'),
                'jam_mulai' => $this->input->post('jam_mulai'),
                'jam_selesai' => $this->input->post('jam_selesai')
            ];
    
            // Validasi jadwal bentrok
            $result = $this->MDokter->cek_jadwal_bentrok_edit($data,$id);
            if ($result['status']) {
                $this->session->set_flashdata('notif', [
                    'tipe' => 2,
                    'isi' => $result['pesan']//'Jadwal bertabrakan dengan jadwal lainnya.'
                ]);
                $dxc=$this->encryption->encrypt( base64_encode($id) ); 
				$ff=str_replace( array('+','/','='),array('-','_','~'),$dxc ); 
							
                redirect('dokter/edit_jadwal/'.$ff);
                return;
            }
    
            // Update data jadwal
            $this->MDokter->update_jadwal($id, $data);
    
            // Redirect dengan notifikasi sukses
            $this->session->set_flashdata('notif', [
                'tipe' => 1,
                'isi' => 'Jadwal berhasil diperbarui.'
            ]);
            redirect('dokter/daftar_periksa');
		}
	}

    public function delete_jadwal() {
        $id = $this->input->post('id');
        $this->MDokter->delete_jadwal($id);
        redirect('dokter/daftar_periksa');
    }
    public function update_statusu()
    {
        // Ambil data dari form
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $updated_at = date('Y-m-d H:i:s'); // Waktu update sekarang

        // Cek apakah ada jadwal aktif selain yang sedang diperbarui
        $other_active_jadwal = $this->MDokter->get_other_active_jadwal($id);

        if ($status == 'active' && !empty($other_active_jadwal)) {
            $this->session->set_flashdata('notif', array('tipe' => 2, 'isi' => 'Hanya satu jadwal yang bisa aktif.'));
            redirect('dokter/daftar_periksa'); // Kembali ke halaman sebelumnya
            return;
        }

        // Update status di database
        $data = array(
            'status' => $status,
            'last_reset_day' => $updated_at
        );

        // // Jika status menjadi aktif, pastikan hanya satu yang aktif
        // if ($status == 'active') {
        //     $this->MDokter->set_inactive_after_week($id); // Nonaktifkan jadwal lainnya untuk dokter tersebut
        // }

        $update_success = $this->MDokter->update_status($id, $data);
        
        $this->session->set_flashdata('notif', [
            'tipe' => 1, // alert-primary
            'isi' => 'Status jadwal berhasil diperbarui.'
        ]);

        redirect('dokter/daftar_periksa');
    }

    public function update_status()
    {
        // Ambil data dari form
        $id = $this->input->post('id'); // ID jadwal
        $status = $this->input->post('status'); // Status yang akan diperbarui ("active" atau "inactive")
        $updated_at = date('Y-m-d H:i:s'); // Waktu update

        // Ambil jadwal berdasarkan ID
        $jadwal = $this->MDokter->get_jadwal_by_id($id)->row(); // Mengambil satu row data

        if (!$jadwal) {
            $this->session->set_flashdata('notif', array(
                'tipe' => 2, 
                'isi' => 'Jadwal tidak ditemukan.'
            ));
            redirect('dokter/daftar_periksa');
            return;
        }

        $id_dokter = $jadwal->id_dokter;

        // Jika status akan diubah menjadi "active"
        if ($status === 'active') {
            // Cek apakah ada jadwal lain yang aktif untuk dokter yang sama
            $existing_active = $this->MDokter->get_active_jadwal_by_dokter($id_dokter);

            // Jika ada jadwal aktif lain dan ID-nya berbeda, tidak boleh aktifkan jadwal baru
            if ($existing_active && $existing_active->id != $id) {
                $this->session->set_flashdata('notif', [
                    'tipe' => 2, // alert-danger
                    'isi' => 'Hanya satu jadwal yang dapat aktif untuk dokter ini.'
                ]);
                redirect('dokter/daftar_periksa');
                return;
            }
        }

        // Update status untuk jadwal ini
        $data = array(
            'status' => $status,
            'last_reset_day' => $updated_at
        );

        $this->MDokter->update_status($id, $data);

        // Berikan notifikasi sukses
        $this->session->set_flashdata('notif', [
            'tipe' => 1,
            'isi' => 'Status jadwal berhasil diperbarui.'
        ]);

        redirect('dokter/daftar_periksa');
    }


    public function daftar_pasien() {
        $user_data = $this->session->userdata('user_data');

        if ($user_data) {
            $id_dokter = $user_data->id;

            // Ambil data pasien yang belum diperiksa
            $data['riwayat'] = $this->MDokter->getRiwayatByDokter($id_dokter);

            // Muat view dengan data pasien
            $konten = $this->load->view('klinik/page/v_daftar_pasien', $data, true);
            $this->konten($konten);
        } else {
            // Redirect ke halaman login jika user belum login
            redirect('landing');
        }
    }

    // Halaman pemeriksaan
    public function iPeriksa($id) {
            
        $ida=str_replace(array('-','_','~'),array('+','/','='),$id);
        $d=base64_decode($this->encryption->decrypt($ida));
        
        
        // Ambil data daftar_poli
        $has['daftar_poli'] = $this->MDokter->getDetailById($d);
        $has['obat'] = $this->MObat->get_all(); // Ambil obat untuk di pilih
        $ghj=$this->load->view('klinik/page/in-periksa',$has,TRUE);
        $this->konten($ghj);
    }

    public function simpanPeriksa() {
        $id_daftar = $this->input->post('id_daftar_poli');
        $tgl_periksa = $this->input->post('tgl_periksa');
        $catatan = $this->input->post('catatan');
        $biaya = $this->input->post('biaya');
        $obat = $this->input->post('obat'); // obat sebagai array

        $data_periksa = [
            'id_daftar_poli' => $id_daftar,
            'tgl_periksa' => $tgl_periksa,
            'catatan' => $catatan,
            'biaya_periksa' => $biaya,
        ];

        $this->MDokter->savePeriksa($data_periksa, $obat);

        $this->session->set_flashdata('notif', [
            'tipe' => 1, // alert-primary
            'isi' => 'Pemeriksaan berhasil disimpan!'
        ]);
        redirect('dokter/daftar_pasien');
    }

    public function riwayat_pasien() {
    
        $user_data = $this->session->userdata('user_data');

        if ($user_data) {
            $data = [
                'id' => $user_data->id // Nama user
            ];

            
                //  $data['riwayat'] = $this->MPasien->getRiwayatByPasien($user_data->id);
                $riwayat = $this->MDokter->getRiwayatPeriksa($user_data->id);
                foreach ($riwayat as &$r) {
                    $r->status = $this->MPasien->isSudahDiperiksa($r->dp_id_pasien) ? 'Sudah Diperiksa' : 'Belum Diperiksa';
                }
                $data['periksa'] = $riwayat;
            $ghj=$this->load->view('klinik/page/v_data_riwayat_pasien', $data,true);
            
            $this->konten($ghj);
        } else {
            // Jika user_data tidak ada, redirect ke halaman login
            redirect('landing');
        }
    
        
    }
    public function diperiksa() {
    
        $user_data = $this->session->userdata('user_data');

        if ($user_data) {
            $data = [
                'id' => $user_data->id // Nama user
            ];

            
                //  $data['riwayat'] = $this->MPasien->getRiwayatByPasien($user_data->id);
                $riwayat = $this->MDokter->getRiwayatPeriksa($user_data->id);
                // foreach ($riwayat as &$r) {
                //     $r->status = $this->MPasien->isSudahDiperiksa($r->id_pasien) ? 'Sudah Diperiksa' : 'Belum Diperiksa';
                // }
                $data['periksa'] = $riwayat;
            $ghj=$this->load->view('klinik/page/v_data_riwayat_pasien_bydokter', $data,true);
            
            $this->konten($ghj);
        } else {
            // Jika user_data tidak ada, redirect ke halaman login
            redirect('landing');
        }
    
        
    }

    public function edit_riwayat($id_periksa) {
        
		$ida=str_replace(array('-','_','~'),array('+','/','='),$id_periksa);
		$d=base64_decode($this->encryption->decrypt($ida));
        
        $has['daftar_poli'] = $this->MDokter->getDetailById($d);
		$has['periksa']=$this->MDokter->getDetailPeriksaById($d);
        
        $has['obat'] = $this->MObat->get_all(); // Ambil obat untuk di pilih
        // $has['polis'] = $this->MPoli->get_all();
        
		$ghj=$this->load->view('klinik/page/v-edit-riwayat-pasien',$has,TRUE);
		$this->konten($ghj);
    }

    public function update_rp() {
        // Ambil data dari form
        $id_periksa = $this->input->post('id_periksa'); // id_periksa yang ada di form
        $total_biaya = str_replace('.', '', $this->input->post('biaya_periksa')); // Menghapus titik dari format IDR
        $id_obat_lama = $this->input->post('id_obat_old'); // Obat yang sudah ada
        $id_obat_baru = $this->input->post('obat'); // Obat baru yang dipilih

        // Ambil ID obat yang dihapus
        $id_obat_dihapus = $this->input->post('id_obat_dihapus');
        $id_obat_dihapus = explode(',', $id_obat_dihapus); // Mengubah string menjadi array


        // Siapkan data yang akan disimpan
        $data_periksa = [
            'biaya_periksa' => $total_biaya,
            // data lainnya yang perlu diperbarui
        ];

        // Update data pemeriksaan
        $this->MDokter->updatePeriksa($id_periksa, $data_periksa);
        // Hapus obat yang dihapus
        if (!empty($id_obat_dihapus)) {
            $this->MDokter->deleteObat($id_periksa, $id_obat_dihapus); // Menghapus obat yang dihapus
        }
        // Tambahkan obat baru
        // if (!empty($id_obat_baru)) {
        //     $this->MDokter->addObat($id_periksa, $id_obat_baru);
        // }
        // Add the new obat
        if (!empty($id_obat_baru)) {
            foreach ($id_obat_baru as $id_obat) {
                $this->MDokter->addObat($id_periksa, $id_obat); // Add the new obat
            }
        }

        // Hapus obat yang dihapus
        // $this->MDokter->deleteObat($id_periksa, $id_obat_lama);

        // Tambahkan obat baru
        // $this->MDokter->addObat($id_periksa, $id_obat_baru);

        $this->session->set_flashdata('notif', [
            'tipe' => 1, // alert-primary
            'isi' => 'Pemeriksaan berhasil disimpan!'
        ]);
        redirect('dokter/diperiksa');
    }

    public function konsultasi() {
        $user_data = $this->session->userdata('user_data');

        if ($user_data) {
            $id_dokter = $user_data->id;

            // Ambil data pasien yang belum diperiksa
            $data['riwayat'] = $this->MDokter->getKonsulByDokter($id_dokter);
           

            // Muat view dengan data pasien
            $konten = $this->load->view('klinik/page/v_data_dokterK', $data, true);
            $this->konten($konten);
        } else {
            // Redirect ke halaman login jika user belum login
            redirect('landing');
        }
    }
    public function ekonsul($id) {
        
		$ida=str_replace(array('-','_','~'),array('+','/','='),$id);
		$d=base64_decode($this->encryption->decrypt($ida));
		$has['pasien']=$this->MPasien->getkonsul_by_id($d)->result();
       $ghj=$this->load->view('klinik/page/v-edit-konsulD',$has,TRUE);
		$this->konten($ghj);
    }
    public function updateKon() {
		// Validasi input
		$id = $this->input->post('id');
		$this->form_validation->set_rules('jawaban', 'jawaban', 'required');
		
		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal, tampilkan kembali form edit
		// 	$data['pasien'] = $this->MPasien->get_by_id($id);
        //    $this->load->view('klinik/page/v-edit-pasien', $data);
        $this->session->set_flashdata('notif', [
            'tipe' => 3, // alert-primary
            'isi' => 'Data Jawaban Konsul gagal diperbarui. '
        ]);
        redirect('dokter/konsultasi');
		} else {
			// Jika validasi berhasil, lanjutkan dengan update
			$data = [
				'jawaban' => $this->input->post('jawaban'),
			];
		
			$this->MDokter->updateK($id, $data);
          
            $this->session->set_flashdata('notif', [
                'tipe' => 1, // alert-primary
                'isi' => 'Data Jawaban Konsul berhasil diperbarui. '
            ]);
            // $this->load->view('pasien/tab');
			redirect('dokter/konsultasi');
		}
	}

}  