<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Landing extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('landing/mlogin');
		
		$this->load->model('klinik/MUsers');
		$this->load->model('klinik/MDokter');
		$this->load->model('klinik/MPasien');
		$this->load->model('klinik/MAdmin');
		$this->load->model('klinik/MKlinik');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('encryption');
		
		$this->encryption->initialize(
			array(
			        'cipher' => 'aes-128',
			        'mode' => 'ctr',
			        // 'key' => 'HJJHJKhahsgdgIYUGKHBJKH^&*^^%^&%^*988qw7e9'
					'key' => 'HJKHASJKD^**&&*(NJSHAHIDAsdfsa'
			)
		);
		date_default_timezone_set("Asia/Jakarta");
		# code...
	}
      
	function konten($value=''){
		$data['konten']=$value;
		$this->load->view('Klinik/home',$data);
	}

	public function index()
	{	
		//$uji="admin_0_superadmin";
		//echo $this->encryption->encrypt($uji);
		//echo "<br>";
		//echo $this->encryption->decrypt('62bd417b9140d3f1c58013876bd5f5159edd355c21444fe5f38f85a3cb7a553f443b0283b2cf47de5af7c70f71cc32ca5e0e27b0a1f6a8e126c2913e4325c773K5IVBx7dGZi7zzTMbRGxA4P5xbRnlF7x36PmPvVRfn7tow==');

		$data['captcha']=$this->create_captcha();
		$this->load->view('landing/awal',$data);
		//$this->session->sess_destroy();
		$this->session->unset_userdata('us3r');
		$this->session->unset_userdata('useryyy');
		$this->session->unset_userdata('idus');
		$this->session->unset_userdata('userver');
		$this->session->unset_userdata(base64_encode('jajahan'));
		$this->session->unset_userdata(base64_encode('idver'));
		$this->session->unset_userdata(base64_encode('kod'));
		
	}
	public function regis()
	{	
        $last_id_pasien = $this->MPasien->get_last_id_pasien();
    
        // Tentukan ID pasien baru (tambah 1 dari ID terakhir)
        $new_id_pasien = $last_id_pasien + 1;
    
        // Generate nomor RM berdasarkan ID pasien yang baru
		$formatted_id=str_pad($new_id_pasien, 3, '0', STR_PAD_LEFT);
		
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
		
    
		
		$data['captcha']=$this->create_captcha();
		$this->load->view('landing/regis',$data);
	}
	public function create() {
        // Ambil ID pasien terakhir
        $last_id_pasien = $this->MPasien->get_last_id_pasien();
    
        // Tentukan ID pasien baru (tambah 1 dari ID terakhir)
        $new_id_pasien = $last_id_pasien + 1;
    
        // Generate nomor RM berdasarkan ID pasien yang baru
        $formatted_id = str_pad($new_id, 3, '0', STR_PAD_LEFT);
        $tahun = date('Y');
        $bulan = date('m');
        $no_rm = $tahun . $bulan . '-' . $formatted_id;
    
        // Kirim data ke view
        $data = [
            'new_id_pasien' => $new_id_pasien, // ID pasien baru
            'no_rm' => $no_rm, // Nomor RM yang baru
        ];
    
        // Format username dan password dengan 3 angka
         // Contoh: '001', '002', '003'
        $username = 'P' . $tahun . '-' . $formatted_id;
        $password = 'P' . $tahun . '-' . $formatted_id;
    
        // Tambahkan username dan password ke data view
        $data['username'] = $username;
        $data['password'] = $password;

        // Load view dan tampilkan data
        $ghj = $this->load->view('klinik/page/in-pasien', $data, TRUE);
        $this->konten($ghj);
    }
	public function store() {
        // Validasi input
        $this->form_validation->set_rules('nama', 'Nama Pasien', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('no_ktp', 'No KTP', 'required');
        $this->form_validation->set_rules('no_hp', 'No HP', 'required');
		$this->form_validation->set_rules('no_rm', 'NO RM', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            // Menampilkan halaman jika validasi gagal
            $ghj = $this->load->view('klinik/page/in-pasien', '', TRUE);
            $this->konten($ghj);
        } else {
            
            // Data untuk tabel pasien
            $data_pasien = [
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'no_ktp' => $this->input->post('no_ktp'),
                'no_hp' => $this->input->post('no_hp'),
                'no_rm' => $this->input->post('no_rm'),
            ];
			
            // Simpan data pasien ke database
            $this->MPasien->insert($data_pasien);

            // Data untuk tabel user
            $username = $this->input->post('username'); // Gunakan no_rm sebagai username
            $password = $this->input->post('username');
            
            $data_user = [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'id_role' => 2,
            ];
    
            // Simpan data user ke tabel user
            $this->MUsers->insert($data_user);
    
            // Redirect setelah penyimpanan berhasil
            redirect('landing');
        }
    }
	public function tte()
	{	
		
		//$this->session->sess_destroy();
		$data['captcha']=$this->create_captcha_tte();
		$this->load->view('login/v_login_tte',$data);
		//$this->session->sess_destroy();
		$this->session->unset_userdata('us3r');
		$this->session->unset_userdata('useryyy');
		$this->session->unset_userdata('idus');
		$this->session->unset_userdata('userver');
		$this->session->unset_userdata(base64_encode('jajahan'));
		$this->session->unset_userdata(base64_encode('idver'));
		$this->session->unset_userdata(base64_encode('kod'));
		
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
				'grid' => array(224, 255, 255)
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
					'grid' => array(224, 255, 255)
			));
	        $captcha = create_captcha($data);
	        $this->session->unset_userdata(base64_encode('captchaword'));
	        $this->session->set_userdata(base64_encode('captchaword'), base64_encode($captcha['word']));
	        echo $captcha['image'];
    }
    function create_captcha_tte(){
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
				'grid' => array(224, 255, 255)
				//'grid' => array(185, 234, 237)
			)
	    );
	    
	    $captcha = create_captcha($data);
	    
	    $image = $captcha['image'];
		
	    $this->session->set_userdata(base64_encode('captchatte'), base64_encode($captcha['word']));

	    return $image;
	}
    function refresh_captcha_tte(){
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
					'grid' => array(224, 255, 255)
			));
	        $captcha = create_captcha($data);
	        $this->session->unset_userdata(base64_encode('captchatte'));
	        $this->session->set_userdata(base64_encode('captchatte'), base64_encode($captcha['word']));
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
	public function auth()
    {
        $this->load->library('auth');
        $this->input->post(NULL, TRUE);
        $username = $this->auth->filter($this->input->post('user'));
        $password = $this->auth->filter($this->input->post('pass'));
        $captcha = $this->auth->filter($this->input->post('capt'));
        $captcha_session = $this->session->userdata(base64_encode('captchaword'));
 
        if ($captcha == base64_decode($captcha_session)) {
            // Validasi input
            $this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
            $this->form_validation->set_rules('user', 'User', 'required', ['required' => 'Username Harus Diisi']);
            $this->form_validation->set_rules('pass', 'Password', 'required', ['required' => 'Password Harus Diisi']);
            $this->form_validation->set_rules('capt', 'Captcha', 'required', ['required' => 'Captcha Harus Diisi']);

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('notif_login', validation_errors());
                redirect('landing#form-section');
            } else {
                $user = $this->mlogin->check_user($username);

                if ($user && password_verify($password, $user->password)) {
                    // Simpan data login ke tabel `login_history`
                    $ip_address = $this->get_client_ip();
                    $login_id = $this->mlogin->record_login($user->id, $ip_address);

                    // Simpan data ke session
                    $role = $this->mlogin->get_role($user->id_role);
                    $this->session->set_userdata([
                        'useryyy' => $user->username,
                        'idus' => $user->id,
                        'role' => $role->nama_role,
                        'login_id' => $login_id
                    ]);

                    redirect('landing/menu');
                } else {
                    $this->session->set_flashdata('notif_login', 'Username atau Password salah');
                    redirect('landing#form-section');
                }
            }
        } else {
            $this->session->set_flashdata('notif_login', '<b>Captcha Tidak Sesuai!!</b>');
            redirect('landing#form-section');
        }
    }

	public function edit_pass($idus) {
        
		// $id = $this->session->userdata('idus');
		$ida=str_replace(array('-','_','~'),array('+','/','='),$idus);
		$d=base64_decode($this->encryption->decrypt($ida));
		
		$has['users']=$this->mlogin->get_by_id($d)->result();
		
		$ghj=$this->load->view('klinik/page/v-edit-pass',$has,TRUE);
		$this->konten($ghj);
    }

	public function change_password()
	{
		$username = $this->input->post('username');
		$user_id = $this->input->post('id');
		
		$password_lama = $this->input->post('password_lama'); // Password lama dari form
		$password_baru = $this->input->post('password_baru'); // Password baru dari form

		// Periksa pengguna berdasarkan username
		$user = $this->mlogin->check_user($username);

		if ($user && password_verify($password_lama, $user->password)) {
				// Jika password lama cocok, hash password baru
				$hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
		
			// Jika password lama cocok, ubah password
			$is_updated = $this->mlogin->update_password($user_id, $hashed_password);

			// Password berhasil diubah
			$this->session->set_flashdata('notif', [
				'tipe' => '1', // Notifikasi sukses
				'isi' => 'Password berhasil diubah.'
			]);
				
			redirect('dokter/profile');
			
		} else {
			$this->session->set_flashdata('notif', [
				'tipe' => '3',
				'isi' => 'Password tidak sama.'
			]);
			redirect('dokter/profile');
		}
	}
	public function change_password2()
{
    $username = $this->input->post('username');
    $user_id = $this->input->post('id');
    $role = $this->session->userdata('role'); // Ambil role dari session
    
    $password_lama = $this->input->post('password_lama'); // Password lama dari form
    $password_baru = $this->input->post('password_baru'); // Password baru dari form

    // Periksa pengguna berdasarkan username
    $user = $this->mlogin->check_user($username);

    if ($user && password_verify($password_lama, $user->password)) {
        // Jika password lama cocok, hash password baru
        $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
    
        // Jika password lama cocok, ubah password
        $is_updated = $this->mlogin->update_password($user_id, $hashed_password);

        // Password berhasil diubah
        $this->session->set_flashdata('notif', [
            'tipe' => '1', // Notifikasi sukses
            'isi' => 'Password berhasil diubah.'
        ]);
        
        // Arahkan ke halaman sesuai role
        if ($role === 'Dokter') {
            redirect('dokter/profile');
        } elseif ($role === 'Admin') {
            redirect('admin/profile');
        } elseif ($role === 'Pasien') {
            redirect('pasien/profile');
        } else {
            // Jika role tidak dikenali, arahkan ke halaman default
            redirect('auth/login');
        }

    } else {
        $this->session->set_flashdata('notif', [
            'tipe' => '3',
            'isi' => 'Password tidak sama.'
        ]);

        // Arahkan ke halaman sesuai role jika gagal
        if ($role === 'Dokter') {
            redirect('dokter/profile');
        } elseif ($role === 'Admin') {
            redirect('admin/profile');
        } elseif ($role === 'Pasien') {
            redirect('pasien/profile');
        } else {
            redirect('auth/login');
        }
    }
}

	
	public function change_passworad()
{
    $this->form_validation->set_rules('password_lama', 'Password Lama', 'required');
    $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[8]');

    if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata('notif', [
            'tipe' => '3',
            'isi' => validation_errors()
        ]);
        redirect('dokter/profile');
    } else {
        $username = $this->input->post('username');
        $user_id = $this->input->post('id');
        $password_lama = $this->input->post('password_lama');
        $password_baru = $this->input->post('password_baru');

        $user = $this->mlogin->check_user($user_id );

		// $user && password_verify($password, $user->password
        if ($user && password_verify($password_lama, $user->password)) {
            $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
            $is_updated = $this->mlogin->update_password($user_id, $hashed_password);

            if ($is_updated) {
                $this->session->set_flashdata('notif', [
                    'tipe' => '1',
                    'isi' => 'Password berhasil diubah.'
                ]);
                redirect('dokter/profile');
            } else {
                $this->session->set_flashdata('notif', [
                    'tipe' => '3',
                    'isi' => 'Gagal mengubah password.'
                ]);
                redirect('dokter/profil');
            }
        } else {
            $this->session->set_flashdata('notif', [
                'tipe' => '3',
                'isi' => 'Password lama tidak sesuai.'
            ]);
            redirect('dokter/profile');
        }
    }
}

	

	

	public function authg()
	{
		$this->load->library('auth');
		$this->input->post(NULL, TRUE);

		$username = $this->auth->filter($this->input->post('user'));
		$password = $this->auth->filter($this->input->post('pass'));
		$captcha = $this->auth->filter($this->input->post('capt'));
		$captcha_session = base64_decode($this->session->userdata(base64_encode('captchaword')));

		if ($captcha == $captcha_session) {
			$this->form_validation->set_rules('user', 'User', 'required', ['required' => 'Username Harus Diisi']);
			$this->form_validation->set_rules('pass', 'Password', 'required', ['required' => 'Password Harus Diisi']);
			$ip_address = $this->get_client_ip();

			$user = $this->mlogin->check_user($username); // Ambil user dari database

			if ($user && password_verify($password, $user->password)) {
				// Jika autentikasi berhasil
				$this->session->set_userdata('idus', $user->id_user);
				$this->session->set_userdata('useryyy', $user->username);
				$this->session->set_userdata(base64_encode('jajahan'), $user->id_role);

				// Simpan riwayat login
				$this->mlogin->save_login_history($user->id_user, $ip_address);

				redirect('landing/menu');
			} else {
				// Jika autentikasi gagal
				$this->session->set_flashdata('notif_login', 'Akses Ditolak!!<br>Cek Kembali User dan Password Anda');
				redirect('landing#form-section');
			}
		} else {
			$this->session->set_flashdata('notif_login', '<b>Captcha Tidak Sesuai!!</b>');
			redirect('landing#form-section');
		}
	}

	public function authKliber()
	{
		$this->load->library('auth');
		$this->input->post(NULL, TRUE);

		$us = $this->auth->filter($this->input->post('user'));
		$psw = $this->auth->filter($this->input->post('pass'));
		$cap = $this->auth->filter($this->input->post('capt'));
		$ff = $this->session->userdata(base64_encode('captchaword'));

		if ($cap == base64_decode($ff)) {
			// Validasi input
			$this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
			$this->form_validation->set_rules('user', 'User', 'required', ['required' => 'Username Harus Diisi']);
			$this->form_validation->set_rules('pass', 'Password', 'required', ['required' => 'Password Harus Diisi']);
			$this->form_validation->set_rules('capt', 'Captcha', 'required', ['required' => 'Captcha Harus Diisi']);

			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('notif_login', validation_errors());
				redirect('landing#form-section');
			}

			// Dapatkan data pengguna dari database
			$user = $this->mlogin->check_user($us);

			if ($user && password_verify($psw, $user->password)) {
				// Jika login berhasil
				$ip_address = $this->get_client_ip();
				$waktu_login = date('Y-m-d H:i:s');

				// Simpan riwayat login
				$this->mlogin->save_login_history($user->id, $ip_address, $waktu_login);

				// Set session data
				$this->session->set_userdata([
					'idus' => $user->id,
					'useryyy' => $user->username,
					base64_encode('jajahan') => $user->id_role,
				]);

				// Redirect ke halaman menu
				redirect('landing/menu');
			} else {
				// Login gagal
				$this->session->set_flashdata('notif_login', 'Akses Ditolak!<br>Cek kembali username dan password Anda.');
				redirect('landing#form-section');
			}
		} else {
			// Captcha tidak sesuai
			$this->session->set_flashdata('notif_login', '<b>Captcha Tidak Sesuai!!</b>');
			redirect('landing#form-section');
		}
	}


	public function menuberhasil() {
        if ($this->session->userdata('useryyy') && $this->session->userdata('idus')) {
            $username = $this->session->userdata('useryyy');
            $data = $this->MUsers->get_user_data($username); // Ambil data user berdasarkan username

            if ($data) {
                $this->load->view('landing/menu', $data);
            } else {
                $this->session->set_flashdata('notif_login', 'User tidak ditemukan.');
                redirect('landing');
            }
        } else {
            redirect('landing');
        }
    }
	public function menuujicoba() {
        if ($this->session->userdata('useryyy') && $this->session->userdata('idus')) {
            $username = $this->session->userdata('useryyy');
            $user_data = $this->MUsers->get_user_data($username);

            if ($user_data) {
                $data['username'] = $username;
                $data['role'] = $user_data['nama_role'];
                $data['details'] = $user_data; // Data lengkap dokter/pasien/admin
                $this->load->view('landing/menu', $data);
            } else {
                redirect('landing'); // Jika data tidak ditemukan
            }
        } else {
            redirect('landing');
        }
    }

    
	public function autdhdg() {
		// Mengambil input username dan password dari form
		$username = $this->input->post('user');
		$password = $this->input->post('pass');
	
		// Cek apakah username ada di database
		$user = $this->MUsers->get_user_by_username($username);
	
		if ($user) {
			// Verifikasi password
			if (password_verify($password, $user['password'])) {
				// Ambil role dari tabel roles berdasarkan id_role
				$role = $this->MUsers->get_role_by_id($user['id_role']);
	
				// Set session untuk login
				$this->session->set_userdata('username', $user['username']);
				$this->session->set_userdata('role', $role['nama_role']);
				$this->session->set_userdata('id', $user['id']);
	
				// Redirect berdasarkan role (misalnya ke halaman dashboard dokter)
				if ($role['nama_role'] == 'Dokter') {
					redirect('dokter/');
				} elseif ($role['nama_role'] == 'Pasien') {
					redirect('pasien/');
				}elseif ($role['nama_role'] == 'Admin') {
					redirect('admin/');
				}
			} else {
				// Jika password salah
				$this->session->set_flashdata('notif_login', 'Username atau Password salah');
				redirect('landing');
			}
		} else {
			// Jika username tidak ditemukan
			$this->session->set_flashdata('notif_login', 'Username tidak terdaftar');
			redirect('landing');
		}
	}
	public function authUUUUUU() {
		// Mengambil input username dan password dari form
		$username = $this->input->post('user');
		$password = $this->input->post('pass');
	
		// Cek apakah username ada di database
		$user = $this->MUsers->get_user_by_username($username);
	
		if ($user) {
			// Verifikasi password
			if (password_verify($password, $user['password'])) {
				// Ambil role dari tabel roles berdasarkan id_role
				$role = $this->MUsers->get_role_by_id($user['id_role']);
	
				// Set session untuk login
				$this->session->set_userdata('username', $user['username']);
				$this->session->set_userdata('role', $role['nama_role']);
				$this->session->set_userdata('id', $user['id']);
	
				// Redirect berdasarkan role (misalnya ke halaman dashboard dokter)
				if ($role['nama_role'] == 'Dokter') {
					redirect('dokter/');
				} elseif ($role['nama_role'] == 'Pasien') {
					redirect('pasien/');
				}elseif ($role['nama_role'] == 'Admin') {
					redirect('admin/');
				}
			} else {
				// Jika password salah
				$this->session->set_flashdata('notif_login', 'Username atau Password salah');
				redirect('landing');
			}
		} else {
			// Jika username tidak ditemukan
			$this->session->set_flashdata('notif_login', 'Username tidak terdaftar');
			redirect('landing');
		}
	}
	
	// Fungsi untuk autentikasi
    public function authhj() {
        // Mengambil input dan memfilter
		
		$this->load->library('auth');
        $this->input->post(NULL, TRUE);
        $us = $this->auth->filter($this->input->post('user'));
        $psw = $this->auth->filter($this->input->post('pass'));
        $cap = $this->auth->filter($this->input->post('capt'));
        $ff = $this->session->userdata(base64_encode('captchaword'));

        // Validasi captcha
        if ($cap == base64_decode($ff)) {
            // Set rules untuk validasi form
            $this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
            $this->form_validation->set_rules('user', 'User ', 'required', array('required' => 'Username Harus Diisi'));
            $this->form_validation->set_rules('pass', 'Password', 'required', array('required' => 'Password Harus Diisi'));
            $this->form_validation->set_rules('capt', 'Captcha', 'required', array('required' => 'Captcha Harus Diisi'));

            // Cek validasi
            if ($this->form_validation->run() == TRUE) {
                // Cek kredensial
                $in = $this->MKlinik->login($us, $psw); // Pastikan model login sesuai
                if ($in) {
                    // Set session data
                    $this->session->set_userdata('user_id', $in->id);
                    $this->session->set_userdata('username', $in->username);
                    $this->session->set_userdata('role_id', $in->id_role);

                    // Redirect berdasarkan role
                    if ($in->id_role == 1) {
                        redirect('admin/dashboard'); // Admin
                    } elseif ($in->id_role == 2) {
                        redirect('dokter/dashboard'); // Dokter
                    } elseif ($in->id_role == 3) {
                        redirect('pasien/dashboard'); // Pasien
                    }
                } else {
                    $this->session->set_flashdata('notif_login', 'Akses Ditolak!!<br>Cek Kembali User dan Password Anda');
                    redirect('landing#form-section');
                }
            } else {
                // Jika validasi gagal
                $this->session->set_flashdata('notif_login', validation_errors());
                redirect('landing#form-section');
            }
        } else {
            $this->session->set_flashdata('notif_login', '<b>Captcha Tidak Sesuai!!</b>');
            redirect('landing#form-section');
        }
    }

	function authUa(){
		
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
	function menuii() {
		if (($this->session->userdata('username') != null) && ($this->session->userdata('id_role') != null)) {
			$username = $this->session->userdata('username');
			$id_role = $this->session->userdata('id_role');
	
			$data = []; // Array untuk menampung data view
	
			if (strpos($username, 'D') === 0) { 
				// Format Dokter: D+tahun+id_dokter
				$id = substr($username, 5); // Ambil bagian id_dokter
				$this->load->model('Mdokter');
				$data['user_data'] = $this->MDokter->get_aset_id($id);
			} elseif (preg_match('/^\d{4}\d{2}\-\d{3}$/', $username)) { 
				// Format Pasien: tahun+bulan+(-)+id urutan
				$id = substr($username, 7); // Ambil bagian id pasien
				$this->load->model('MPasien');
				$data['user_data'] = $this->MPasien->get_aset_id($id);
			} elseif (strpos($username, 'A') === 0) { 
				// Format Admin: A+tahun+id_admin
				$id = substr($username, 5); // Ambil bagian id_admin
				$this->load->model('MAdmin');
				$data['user_data'] = $this->MAdmin->get_aset_id($id);
			}
	
			$data['username'] = $username;
			$data['role'] = $this->session->userdata('nama_role');
			$this->load->view('landing/menu', $data);
		} else {
			redirect('landing');
		}
	}
	
	function menuk()
	{
		if ($this->session->userdata('useryyy') != null && $this->session->userdata('idus') != null && $this->session->userdata(base64_encode('jajahan')) != null) {
			$username = $this->session->userdata('useryyy');
			$role = '';
			$details = [];
			
			// Cek format username
			if (preg_match('/^D(\d+)(\d+)$/', $username, $matches)) {
				// Format Dokter
				$id_dokter = $matches[2]; // Ambil id_dokter
				$query = $this->db->get_where('dokter', ['id' => $id_dokter]);
				if ($query->num_rows() > 0) {
					$details = $query->row_array();
					$role = 'Dokter';
				}
			} elseif (preg_match('/^(\d{4})(\d{2})-(\d{3})$/', $username, $matches)) {
				// Format Pasien
				$id_pasien = $matches[3]; // Ambil id pasien
				$query = $this->db->get_where('pasien', ['id' => $id_pasien]);
				if ($query->num_rows() > 0) {
					$details = $query->row_array();
					$role = 'Pasien';
				}
			} elseif (preg_match('/^A(\d+)(\d+)$/', $username, $matches)) {
				// Format Admin
				$id_admin = $matches[2]; // Ambil id_admin
				$query = $this->db->get_where('admin', ['id' => $id_admin]);
				if ($query->num_rows() > 0) {
					$details = $query->row_array();
					$role = 'Admin';
				}
			}

			// Load view dengan data
			$data['username'] = $details['nama'] ?? $username;
			$data['role'] = $role;
			$this->load->view('landing/menu', $data);
		} else {
			redirect('landing');
		}
	}

	public function menuj()
    {
        if ($this->session->userdata('useryyy') && $this->session->userdata('idus')) {
			$data['username'] = $this->session->userdata('useryyy');
            $data['role'] = $this->session->userdata('role');
            $username = $this->session->userdata('useryyy');
            // $data['user_data'] = $this->mlogin->get_user_data($username);
			$this->load->view('landing/menu', $data);
        } else {
            redirect('landing');
        }
    }
	public function menu()
	{
		if ($this->session->userdata('useryyy') && $this->session->userdata('idus')) {
			$username = $this->session->userdata('useryyy');
			$role = $this->session->userdata('role');
	
			// Filter ID berdasarkan format username
			// $id_filtered = '';
			// if (preg_match('/^D\d{4}(\d+)$/', $username, $matches)) {
			// 	// Format Dokter: D+tahun+iddokter
			// 	$id_filtered = $matches[1];
			// } elseif (preg_match('/^\d{6}-(\d{3})$/', $username, $matches)) {
			// 	// Format Pasien: username+tahun+bulan+(-)+3 angka id pasien
			// 	$id_filtered = $matches[1];
			// } elseif (preg_match('/^A\d{4}(\d+)$/', $username, $matches)) {
			// 	// Format Admin: A+tahun+id
			// 	$id_filtered = $matches[1];
			// }
			// Filter ID berdasarkan format username
			$id_filtered = '';
			if (preg_match('/^A\d{4}-(\d{3})$/', $username, $matches)) {
				// Format Dokter: D+tahun+(-)+id poli+(-)+3 angka id dokter
				error_log(print_r($matches, true)); // Debugging
				$id_filtered = $matches[1];
			} elseif (preg_match('/^P\d{4}-(\d{3})$/', $username, $matches)) {
				// Format Pasien: username+tahun+(-)+3 angka id pasien
				$id_filtered = $matches[1];
			} elseif (preg_match('/^D\d{4}-(\d{3})$/', $username, $matches)) {
				// Format Admin: A+tahun+3 angka id admin
				$id_filtered = $matches[1];
			}

			// Ambil data berdasarkan role
			$user_data = null;
			if ($role == 'Dokter') {
				$this->load->model('MDokter');
				$user_data = $this->MDokter->get_by_id_dokter($id_filtered);
				
				// Simpan user_data ke session
				$this->session->set_userdata('user_data', $user_data);
			} elseif ($role == 'Pasien') {
				$this->load->model('MPasien');
				$user_data = $this->MPasien->get_by_id_pasien($id_filtered);


				// Simpan user_data ke session
				$this->session->set_userdata('user_data', $user_data);
			} elseif ($role == 'Admin') {
				$this->load->model('MAdmin');
				$user_data = $this->MAdmin->get_by_id_admin($id_filtered);


				// Simpan user_data ke session
				$this->session->set_userdata('user_data', $user_data);
			}
			
	
			$data = [
				'username' => $username,
				'role' => $role,
				'id_filtered' => $id_filtered,
				'user_data' => $user_data,
				'name' => $user_data->nama
			];
			
	
			$this->load->view('landing/menu', $data);
			
		} else {
			redirect('landing');
		}
	}
	
	public function menummm()
    {
        if ($this->session->userdata('useryyy') && $this->session->userdata('idus')) {
            $username = $this->session->userdata('useryyy');
            $role = $this->session->userdata('role');
            $data = [];

            if (preg_match('/^D\d{4}-\d+$/', $username)) {
                // Username format Dokter
                $id_dokter = explode('-', $username)[1];
                $data['dokter'] = $this->MDokter->get_by_id($id);
            } elseif (preg_match('/^\d{4}\d{2}-\d{3}$/', $username)) {
                // Username format Pasien
                $id_pasien = explode('-', $username)[1];
                $data['pasien'] = $this->MPasien->get_by_id($id);
            } elseif (preg_match('/^A\d{4}\d+$/', $username)) {
                // Username format Admin
                $id_admin = substr($username, 5);
                $data['admin'] = $this->MAdmin->get_by_id($id);
            }

            $data['username'] = $username;
            $data['role'] = $role;
            $this->load->view('landing/menu', $data);
        } else {
            redirect('landing');
        }
    }
	public function menuaaaa() {
        if ($this->session->userdata('useryyy') && $this->session->userdata('idus')) {
            $data['username'] = $this->session->userdata('useryyy');
            $data['role'] = $this->session->userdata('role');
            $username = $this->session->userdata('useryyy');
            // $this->load->model('MUsers');
            $user_data = $this->MUsers->get_user_data($username);

            if ($user_data) {
                $data['user_data'] = $user_data;
                $this->load->view('landing/menu', $data);
            } else {
                $this->session->set_flashdata('notif_login', 'Data tidak ditemukan!');
                redirect('landing');
            }
        } else {
            redirect('landing');
        }
    }
	public function menuaa()
    {
        if ($this->session->userdata('useryyy') && $this->session->userdata('idus')) {
            $data['username'] = $this->session->userdata('useryyy');
            $data['role'] = $this->session->userdata('role');
            $this->load->view('landing/menu', $data);
        } else {
            redirect('landing');
        }
    }
	public function out()
    {
        $login_id = $this->session->userdata('login_id');
        $ip_address = $this->get_client_ip();
        $this->mlogin->update_logout($login_id, $ip_address);

        $this->session->unset_userdata(['useryyy', 'idus', 'role', 'login_id']);
        $this->session->sess_destroy();
        redirect('landing');
    }
	function menua(){	
		if(($this->session->userdata('useryyy') !=null) && ($this->session->userdata('idus')!= null) && ($this->session->userdata(base64_encode('jajahan')) != null) ){
		$this->load->view('landing/menu');

		}else{
			redirect('landing');
		}
		
	}
	public function outa()
	{
		$user_id = $this->session->userdata('idus');
		$ip_address = $this->get_client_ip();

		// Update waktu logout
		$this->mlogin->update_logout_time($user_id, $ip_address);

		// Hapus session
		$this->session->unset_userdata(['useryyy', 'idus', base64_encode('jajahan')]);
		$this->session->sess_destroy();
		redirect('landing');
	}

	function outU(){
		$dt = array('us3r' =>'' ,'useryyy'=> '', 'idus' => '' ,base64_encode('jajahan')=>'' );
		$this->session->unset_userdata($dt);
		$this->session->sess_destroy();
		redirect('landing');
	}

	function auth_tte(){
		
		$this->load->library('auth');
		$this->input->post(NULL,TRUE);
		$us=$this->auth->filter($this->input->post('user'));
		$psw=$this->auth->filter($this->input->post('pass'));
		$cap=$this->auth->filter($this->input->post('capt'));
		$ff=$this->session->userdata(base64_encode('captchatte'));

		if ($cap == base64_decode($ff)) {

			$this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
			$this->form_validation->set_rules('user', 'User', 'required',array('required' => 'Username Harus Diisi') );
			$this->form_validation->set_rules('pass', 'Password', 'required',array('required' => 'Password Harus Diisi') );
			$this->form_validation->set_rules('capt', 'Captcha', 'required',array('required' => 'Password Harus Diisi') );
			$ipk=$this->get_client_ip();

			$in=$this->mlogin->fin_tte($us,$psw,$ipk);
			if ($in==true) {
				redirect('tte');
			}else{
				$this->session->set_flashdata('notif_login','Akses Ditolak!!<br>Cek Kembali User dan Password Anda');
				redirect('landing/tte#form-section');
			}	
		}else{
			
			$this->session->set_flashdata('notif_login','<b>Captcha Tidak Sesuai!!</b>');
			redirect('landing/tte#form-section');
		}
		 

	}

	public function change_passwordgagal() {
        $this->form_validation->set_rules('old_password', 'Password Lama', 'required');
        $this->form_validation->set_rules('new_password', 'Password Baru', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password Baru', 'required|matches[new_password]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('notif_password', validation_errors());
            redirect('landing/menu');
        } else {
            $username = $this->session->userdata('useryyy');
            $old_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');

            if ($this->mlogin->update_password($username, $old_password, $new_password)) {
                $this->session->set_flashdata('notif_password', 'Password berhasil diubah');
            } else {
                $this->session->set_flashdata('notif_password', 'Password lama tidak sesuai');
            }
            redirect('landing/menu');
        }
    }






















	function addnew(){
		$data['jabatan'] = $this->muser->get_jbt()->result();
		$data['pjabatan'] = $this->muser->get_jbt_fungsi()->result();
		$data['kabkot'] = $this->muser->get_kab()->result();
		$data['pend'] = $this->muser->get_pend()->result();
		$data['gol'] = $this->muser->get_gol()->result();
		$data['captcha']=$this->create_captcha();
		//print_r($data);
		$this->load->view('user/page/v_injabfung',$data);
	}

	function save_jabfung(){

		$data=$this->input->post(NULL,TRUE);
		//print_r($data);
		$this->load->library('auth');
		$cap=$this->auth->filter($this->input->post('capca'));
		$ff=$this->session->userdata(base64_encode('captchaword'));
		/*echo $cap."<br>ADSAD ".$ff."<br><br>";
		print_r($this->session->userdata());*/
		if ($cap == base64_decode($ff)) {

			$this->form_validation->set_error_delimiters('<font color="red"><b>', '</b></font>');
			$this->form_validation->set_rules('nip', 'NIP',
				'required|max_length[21]|is_unique[nmjabfung.nip]',
				array(	'required' => 'NIP Harus Terisi', 
						'is_unique' => 'NIP Anda Telah Terdaftar'
				)
			);
			$this->form_validation->set_rules('nm', 'Nama', 'required',array('required' => 'Nama Harus Diisi') );
			$this->form_validation->set_rules('jankel', 'Jenis Kelamin', 'required',array('required' => 'Silahkan Pilih Jenis Kelamin Anda'));
			$this->form_validation->set_rules('tmplhr', 'Tempat lahir Lahir', 'required',array('required' => 'Silahkan Isi Tempat Lahir'));
			$this->form_validation->set_rules('tgllhr', 'Tanggal Lahir', 'required|regex_match["[0-9][0-9]/[0-9][0-9]/[0-9][0-9][0-9][0-9]"]',array('required' => 'Silahkan Pilih Tanggal Lahir'));
			$this->form_validation->set_rules('jbt', 'Jabatan', 'required',array('required' => 'Silahkan Pilih Jabatan'));
			$this->form_validation->set_rules('pjbt', 'Pangkat Jabatan', 'required',array('required' => 'Silahkan Pilih Pangkat'));
			$this->form_validation->set_rules('kabkot', 'KAB/Kota', 'required',array('required' => 'Silahkan Pilih Kab/Kota'));
			$this->form_validation->set_rules('almt', 'Alamat', 'required',array('required' => 'Silahkan Isi Alamat'));
			$this->form_validation->set_rules('nohp', 'Nomor HP', 'required|numeric',
				array(	'numeric' => 'Silahkan Masukan Angka Saja Tanpa Karakter')
			);
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[nmjabfung.email]',
				array(	'required' => 'Silahkan Masukan Email Anda',
						'valid_email' => 'Silahkan Masukan Email Dengan Benar',
						'is_unique' => 'Email Anda Telah Terdaftar! Gunakan Email Lainnya'
				 )
			);
			$this->form_validation->set_rules('untkjr', 'Unit Kerja', 'required',array('required' => 'Silahkan Isi Unit Kerja'));
			$this->form_validation->set_rules('pend', 'Pendidikan', 'required',array('required' => 'Silahkan Pilih Pendidikan'));
			$this->form_validation->set_rules('jurpen', 'Jurusan Pendidikan', 'required',array('required' => 'Silahkan Isi Pendidikan'));
			$this->form_validation->set_rules('nokarpeg', 'Nomor Kartu Pegawai', 'required',array('required' => 'Silahkan Isi Nomor Kartu Pegawai'));
			$this->form_validation->set_rules('gol', 'Golongan', 'required',array('required' => 'Silahkan Pilih Golongan'));
			$this->form_validation->set_rules('tmtgol', 'TMT Golongan', 'required',array('required' => 'Silahkan Isi TMT Golongan'));
			$this->form_validation->set_rules('tglmasker', 'Tanggal CPNS', 'required',array('required' => 'Silahkan Pilih Tanggal CPNS'));
			$this->form_validation->set_rules('nosk', 'Nomor SK Pegawai', 'required',array('required' => 'Silahkan Isi nomor SK Pegawai'));
			$this->form_validation->set_rules('pilecpns', 'File PDF', 'callback_cek_file_pdf');
			$this->form_validation->set_rules('pilepns', 'File PDF', 'callback_cek_file_pdf1');
			$this->form_validation->set_rules('pilekp', 'File PDF', 'callback_cek_file_pdf2');

			if($this->form_validation->run() == true){
							
				$nmfil = array('0' => 'pilecpns', '1'=> 'pilepns', '2'=>'pilekp' );
				$nmfol = array('0' => "./skcpns", '1'=> "./skpns", '2'=>"./skkp" );
				$nwus=str_replace(" ","",$data['nip']);

				for ($i=0; $i < 3; $i++) { 
					$_FILES['fila']['name'] = $_FILES[$nmfil[$i]]['name'];
					$_FILES['fila']['type'] = $_FILES[$nmfil[$i]]['type'];
					$_FILES['fila']['tmp_name'] = $_FILES[$nmfil[$i]]['tmp_name'];
					$_FILES['fila']['error'] = $_FILES[$nmfil[$i]]['error'];
					$_FILES['fila']['size'] = $_FILES[$nmfil[$i]]['size'];

					$a['upload_path']=$nmfol[$i];
					$a['allowed_types'] ='application|pdf';
					$a['file_name'] =$nwus;
					$a['max_size'] =1024;
					$this->load->library('upload', $a);
					$this->upload->initialize($a,TRUE);
					$up=$this->upload->do_upload('fila');
				}
				if ($up) {
					

					// konfigurasi akun email 
					$config = array();
					$config['protocol']= 'smtp'; // protokol kirim emailnya
					$config['smtp_host']= 'ssl://smtp.gmail.com'; // host gmail
					$config['smtp_user']= 'sepakmailkominfo@gmail.com'; // email kalian
					//$config['smtp_pass']= '@123sepak123@'; // hasil generate password ditaruh disini
					$config['smtp_pass'] = 'fqoiydcnmvzvidcd'; // hasil generate password ditaruh disini
					//$config['smtp_crypto']= 'ssl'; // jenis sertifikasi 
					$config['smtp_port']= 465; // port
					$config['charset']= 'utf-8'; // charset
					//$config['wordwrap']= TRUE; // wordwrap
					$config['mailtype']= 'html'; // type html, text 

					$config['newline']= "\r\n"; // newline
					$this->email->initialize($config);

					// konfigurasi tujuan, isi pesan dan pengirim
					$this->email->from('sepakmailkominfo@gmail.com', 'Aktivasi Akun SePAK');
					$this->email->to($data['email']);
					$this->email->subject('Password Awal');
					$this->email->set_newline("\r\n");

					// isi pesan 
					$pssnw=substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);
					$pesan = "<h1>Aktivasi Akun</h1><br>Berikut kami sampaikan Password anda sebagai berikut <b>".$pssnw."</b>";
					$this->email->message($pesan);
					$ag=$this->email->send();
					$data['passnw']=$pssnw;
					if ($ag) {
						$in=$this->muser->s_nmjabfung($data);
						
						if ($in) {
							$not = array(
								'tipe' => 1, 
								'isi'=>'Daftar Peserta Jabfung Berhasil Diinput'
							  );
							$this->session->set_flashdata('notif_surat',$not );
							redirect('adduser/addnew');
						}
						else{
							$not = array(
								'tipe' => 3,
								'isi' => "Input GAGAL"
								  );
							$this->session->set_flashdata('notif_surat',$not );
							redirect('adduser/addnew');
						}
					}
					else{
						$not = array(
							'tipe' => 3,
							'isi' => "Gagal Verivikasi Email"
							  );
						$this->session->set_flashdata('notif_surat',$not );
						redirect('adduser/addnew');
					}

				}

			}
			else{
				$this->addnew();
				
			 	
			 }
		}else{
			$not = array(
				'tipe' => 3,
				'isi' => "<b>Captcha Tidak Sesuai!!</b>"
				  );
			$this->session->set_flashdata('notif_surat',$not );
				redirect('adduser/addnew');
		}
		 
		
	}
	
		

}
