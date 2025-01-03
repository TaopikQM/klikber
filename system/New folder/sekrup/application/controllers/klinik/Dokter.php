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
		
		// $sec=$this->session->userdata('role');
		// if ($sec == NULL) {
		// 	redirect('landing/menu');
		// }elseif ($sec != 'Dokter' && $sec != 'Admin') {
        //     redirect('landing/menu');
        // }
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

    // public function index() {
	// 	$this->load->helper('pinjam');		
    //     $data['dokters'] = $this->MDokter->get_all();
    //     $ghj=$this->load->view('klinik/page/v_data_dokter', $data,TRUE);
	// 	$this->konten($ghj);	//v_data_dokter
    // }
    public function tab() {
		$this->load->helper('pinjam');		
        $data['dokters'] = $this->MDokter->get_all();
        $ghj=$this->load->view('klinik/page/v_data_dokter', $data,TRUE);
		$this->konten($ghj);	//v_data_dokter
    }

    public function create1() {
        $data['polis'] = $this->MPoli->get_all();
        $ghj = $this->load->view('klinik/page/in-dokter', $data, TRUE);
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
    

    public function storeq() {
        // Validasi input
        $this->form_validation->set_rules('nama', 'Nama Dokter', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('no_hp', 'No HP', 'required');
        $this->form_validation->set_rules('id_poli', 'Poli', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            $data['polis'] = $this->MPoli->get_all();
            $ghj = $this->load->view('klinik/page/in-dokter', $data, TRUE);
            $this->konten($ghj);
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
            // Dapatkan ID terakhir dan tambahkan 1
            $last_id = $this->MDokter->get_last_id_dokter();
            $new_id = $last_id;

            // Format username dengan 3 angka
            $formatted_id = str_pad($new_id, 3, '0', STR_PAD_LEFT); // Contoh: '001', '002', '003'
    
            if ($id_dokter) {
                // Data untuk tabel user
                $username = 'D' . date('Y')  . $formatted_id;
                $password = 'D' . date('Y')  . $formatted_id;
                $data_user = [
                    'username' => $username,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'id_role' => 3, // Role default dokter
                ];
    
                // Simpan data user ke tabel user
                $this->MUsers->insert($data_user);
            }
    
            // Redirect setelah penyimpanan berhasil
            redirect('dokter');
        }
    }
    
    public function store() {
        // Validasi input
        $this->form_validation->set_rules('nama', 'Nama Dokter', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('no_hp', 'No HP', 'required');
        $this->form_validation->set_rules('id_poli', 'Poli', 'required');
    
        if ($this->form_validation->run() == FALSE) {
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
			// Jika validasi gagal, tampilkan kembali form edit
			$data['dokter'] = $this->MDokter->get_by_id($id);
            $data['polis'] = $this->MPoli->get_all();
			$this->load->view('klinik/page/v-edit-dokter', $data);
		} else {
			// Jika validasi berhasil, lanjutkan dengan update
			$data = [
				'nama' => $this->input->post('nama'),
				'alamat' => $this->input->post('alamat'),
				'no_hp' => $this->input->post('no_hp'),
                'id_poli' => $this->input->post('id_poli')
			];
			$this->MDokter->update($id, $data);
			redirect('dokter/profile');
		}
	}

    
    public function update_statusa()
    {
        // Ambil data dari form
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $updated_at = date('Y-m-d H:i:s'); // Waktu update sekarang

        // Cek apakah ada jadwal aktif untuk dokter ini
        $active_jadwal = $this->MDokter->get_active_jadwal($id);

        if ($status == 'active' && $active_jadwal) {
            $this->session->set_flashdata('notif', array('tipe' => 2, 'isi' => 'Hanya satu jadwal yang bisa aktif.'));
            redirect('dokter/daftar_periksa'); // Kembali ke halaman sebelumnya
            return;
        }

        // Update status di database
        $data = array(
            'status' => $status,
            'last_reset_day' => $updated_at
        );
        
        $this->MDokter->update_status($id, $data);

        // Set pemberitahuan sukses
        $this->session->set_flashdata('notif', array('tipe' => 1, 'isi' => 'Status jadwal berhasil diperbarui.'));
        redirect('dokter/daftar_periksa');
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
                'idus' => $this->session->userdata('idus'),
                'role' => $this->session->userdata('role'),
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
            redirect('dokter/tambah_jadwal');
            return;
        }
        $this->MDokter->simpan_jadwal($data);
        $this->session->set_flashdata('notif', [
            'tipe' => 1, // alert-primary
            'isi' => 'Jadwal berhasil ditambahkan.'
        ]);
        redirect('dokter/daftar_periksa');
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
    public function update_status()
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

    if ($update_success) {
        // Set pemberitahuan sukses
        $this->session->set_flashdata('notif', array('tipe' => 1, 'isi' => 'Status jadwal berhasil diperbarui.'));
    } else {
        // Set pemberitahuan gagal
        $this->session->set_flashdata('notif', array('tipe' => 3, 'isi' => 'Gagal memperbarui status jadwal.'));
    }
    redirect('dokter/daftar_periksa');
}


    public function update_status2()
    {
        // Ambil data dari form
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $updated_at = date('Y-m-d H:i:s'); // Waktu update sekarang

        // Cek apakah ada jadwal aktif untuk dokter ini
        $active_jadwal = $this->MDokter->get_active_jadwal($id);

        if ($status == 'active' && $active_jadwal) {
            $this->session->set_flashdata('notif', array('tipe' => 2, 'isi' => 'Hanya satu jadwal yang bisa aktif.'));
            redirect('dokter/daftar_periksa'); // Kembali ke halaman sebelumnya
            return;
        }

        // Update status di database
        $data = array(
            'status' => $status,
            'last_reset_day' => $updated_at
        );
        
        // Jika status menjadi aktif, pastikan hanya satu yang aktif
        if ($status == 'active') {
            $this->MDokter->set_inactive_after_week($id); // Nonaktifkan jadwal lainnya untuk dokter tersebut
        }

        $update_success = $this->MDokter->update_status($id, $data);

        if ($update_success) {
            // Set pemberitahuan sukses
            $this->session->set_flashdata('notif', array('tipe' => 1, 'isi' => 'Status jadwal berhasil diperbarui.'));
            
        } else {
            // Set pemberitahuan gagal
            $this->session->set_flashdata('notif', array('tipe' => 3, 'isi' => 'Gagal memperbarui status jadwal.'));
        }
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

    
    public function daftar_pasien7() {
      
        $user_data = $this->session->userdata('user_data');
 
         if ($user_data) {
             $data = [
                 'id' => $user_data->id // Nama user
             ];
 
             
                //  $data['riwayat'] = $this->MPasien->getRiwayatByPasien($user_data->id);
                $riwayat = $this->MDokter->getRiwayatByDokter($user_data->id);
                foreach ($riwayat as &$r) {
                    $r['status'] = $this->MPasien->isSudahDiperiksa($r['id']) ? 'Sudah Diperiksa' : 'Belum Diperiksa';
                }
                $data['riwayat'] = $riwayat;
               $ghj=$this->load->view('klinik/page/v_daftar_pasien', $data,true);
               
             $this->konten($ghj);
         } else {
             // Jika user_data tidak ada, redirect ke halaman login
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
                    $r->status = $this->MPasien->isSudahDiperiksa($r->id_pasien) ? 'Sudah Diperiksa' : 'Belum Diperiksa';
                }
                $data['periksa'] = $riwayat;
               $ghj=$this->load->view('klinik/page/v_data_riwayat_pasien', $data,true);
               
             $this->konten($ghj);
         } else {
             // Jika user_data tidak ada, redirect ke halaman login
             redirect('landing');
         }
     
         
    }



    public function daftar_pasiemn() {
        $data['pasien'] = $this->MDokter->get_daftar_pasien();
        $this->load->view('klinik/page/v_data_daftar_pasien', $data);
    }
    

    public function detail_pasien($id_pasien) {
        $data['pasien'] = $this->MDokter->get_pasien_by_id($id_pasien);
        $data['riwayat'] = $this->MDokter->get_riwayat_pasien($id_pasien);
        $this->load->view('klinik/page/v_detail_pasien', $data);
    }

    public function catatan_kesehatan($id_pasien) {
        $this->load->view('klinik/page/i-catatan', ['id_pasien' => $id_pasien]);
    }

    public function simpan_catatan() {
        $data = [
            'id_daftar_poli' => $this->input->post('id_daftar_poli'),
            'catatan' => $this->input->post('catatan'),
            'biaya_periksa' => $this->input->post('biaya_periksa')
        ];

        $this->Dokter_model->simpan_catatan($data);
        $this->session->set_flashdata('success', 'Catatan berhasil disimpan.');
        redirect('klinik/page/v_data_daftar_pasien');
    }


}