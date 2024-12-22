<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mlogin extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Jakarta");
		$this->db = $this->load->database('klinik', TRUE);
	
		# code...
	}
	 // Fungsi untuk memeriksa data pengguna berdasarkan username
	 public function check_user($username)
	 {
		 $this->db->select('id, username, password, id_role');
		 $this->db->from('users');
		 $this->db->where('username', $username);
		 return $this->db->get()->row();
	 }
	 public function get_role($id)
    {
        $this->db->select('nama_role');
        $this->db->from('role');
        $this->db->where('id', $id);
        return $this->db->get()->row();
    }
	public function record_login($id, $ip_address)
    {
        $data = [
            'id_user' => $id,
            'ip_address' => $ip_address,
            'login_time' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('login_history', $data);
        return $this->db->insert_id();
    }
	function get_by_id($id){
		$this->db->where('id', $id);
		return $this->db->get('users');
	}

    public function update_logout($id, $ip_address)
    {
        $data = [
            'logout_time' => date('Y-m-d H:i:s'),
            'ip_address' => $ip_address
        ];
        $this->db->where('id', $id);
        $this->db->update('login_history', $data);
    }
	 // Fungsi untuk menyimpan riwayat login
	 public function save_login_history($id_user, $ip_address, $waktu_login)
	 {
		 $data = [
			 'id_user' => $id_user,
			 'ip_address' => $ip_address,
			 'waktu_login' => $waktu_login,
		 ];
		 $this->db->insert('riwayat_login', $data);
	 }

	 public function get_user_datam($username)
    {
        $this->db->select('u.*, r.nama_role, d.nama as dokter_nama, d.alamat as dokter_alamat, 
                           p.nama_poli, p.keterangan as poli_keterangan');
        $this->db->from('users u');
        $this->db->join('role r', 'u.id_role = r.id', 'left');
        $this->db->join('dokter d', 'd.id = u.id', 'left');
        $this->db->join('poli p', 'p.id = d.id_poli', 'left');
        $this->db->where('u.username', $username);
        return $this->db->get()->row_array();
    }
	public function validate_password($user_id, $password)
    {
        $user = $this->db->get_where('users', ['id' => $user_id])->row();
        return $user && password_verify($password, $user->password);
    }

    public function update_passworda($idus, $new_password)
    {
        $data = ['password' => password_hash($new_password, PASSWORD_DEFAULT)];
        $this->db->update('users', $data, ['id' => $idus]);
    }
	public function update_passwords($user_id, $hashed_password)
{
    $this->db->set('password', $hashed_password);
    $this->db->where('id', $user_id);
    return $this->db->update('users');
}
public function update_passwordaaa($user_id, $new_password)
    {
        $data = ['password' => password_hash($new_password, PASSWORD_DEFAULT)];
        $this->db->update('users', $data, ['id' => $user_id]);
    }
	public function update_password($user_id, $hashed_password)
{
    $this->db->set('password', $hashed_password);
    $this->db->where('id', $user_id);
    return $this->db->update('users'); // Mengembalikan true jika berhasil
}


	
	function fin($us,$pass,$ipk){
		$return=null;
		$ps=md5($pass);
		$nwpas=sha1($ps);
		
		$q="SELECT u.id, u.pass, u.username, b.n_bid as nmbid, u.id FROM user u
			LEFT JOIN bidang b
			ON u.bid = b.id
			WHERE u.pass=? AND u.username=? LIMIT 1";
		
		$dat = array($nwpas,$us);
		$qu=$this->db->query($q,$dat);
		
		if ($qu->num_rows()>0) {
			$row=$qu->row();

			if($row->pass==$nwpas){
				$qa="SELECT id_apli, hk FROM hk_apli WHERE idus=?";
				
				$dta = array(base64_encode($row->id));
				$qua=$this->db->query($qa,$dta);
				$has=array();$c=0;
				$rowaa=$qua->row();
				
				foreach ($qua->result() as $k) {
					//echo $k->hk;
					$has[$c][base64_encode('apli')]=$k->id_apli;
					$has[$c][base64_encode('hk')]=$k->hk;
					$c++;
				}
				/*echo "<pre>";
				print_r($has);
				echo "</pre>";*/
				$dt = array('us3r' =>$row->username ,'useryyy'=> TRUE, 'idus' => base64_encode($row->id), base64_encode('jajahan')=>$has, 'tahun'=>date('Y') );
				$this->session->set_userdata($dt);
				
				$qs="UPDATE user SET pi=? WHERE id=? LIMIT 1";
				$sdat = array($ipk,$row->id);
				$qus=$this->db->query($qs,$sdat);		
				if ($qus) {
					$return=TRUE;
				}else{
					$return=FALSE;
				}
				
			}else{
				$return=FALSE;
			}
		}else{
			$return=FALSE;
		}

		return $return;
	}
	function fin_tte($us,$pass,$ipk){

		$return=null;
		$ps=md5($pass);
		$nwpas=sha1($ps);
		
		$q="SELECT u.id, u.pass, u.nama, u.userid, b.n_bid as nmbid, u.id FROM adminver u
			LEFT JOIN bidang b
			ON u.nama = b.kode
			WHERE u.pass=? AND u.userid=? LIMIT 1";
		
		$dat = array($nwpas,$us);
		$qu=$this->db->query($q,$dat);
		
		if ($qu->num_rows()>0) {
			$row=$qu->row();
			if($row->pass==$nwpas){
				
				$dt = array(base64_encode('idver') =>$this->encryption->encrypt($row->id)  ,base64_encode('kod') =>base64_encode($row->nama),'userver'=> TRUE,  'tahun'=>date('Y'), base64_encode('ttein')=>base64_encode($row->nama."||".$row->id) );
				$this->session->set_userdata($dt);
				
				$qs="UPDATE adminver SET ip=? WHERE id=? LIMIT 1";
				$sdat = array($ipk,$row->id);
				$qus=$this->db->query($qs,$sdat);		
				if ($qus) {
					$return=TRUE;
				}else{
					$return=FALSE;
				}
				
			}else{
				$return=FALSE;
			}
		}else{
			$return=FALSE;
		}

		return $return;
	}

	public function get_user_data($username)
    {
        $role_data = $this->db->select('r.nama_role')
            ->from('users u')
            ->join('role r', 'u.id_role = r.id')
            ->where('u.username', $username)
            ->get()->row();

        if ($role_data->nama_role == 'dokter') {
            return $this->db->select('d.*, p.nama_poli')
                ->from('dokter d')
                ->join('poli p', 'd.id_poli = p.id')
                ->where('d.id', substr($username, 0))
                ->get()->row_array();
        } elseif ($role_data->nama_role == 'pasien') {
            return $this->db->get_where('pasien', ['id' => substr($username, 4)])->row_array();
        } elseif ($role_data->nama_role == 'admin') {
            return $this->db->get_where('admin', ['id' => substr($username, 2)])->row_array();
        }
        return [];
    }
}
