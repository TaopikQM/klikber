<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mpinjam extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->dbpinjam = $this->load->database('pinjam', TRUE);
	}
	
	function get_max_nomor(){
		$this->dbpinjam->select('MAX(nopinjam) nomor');
		$this->dbpinjam->from('peminjaman');
		$query = $this->dbpinjam->get();
		return $query;
	}
	function get_data_peminjaman_id($id){
		$this->dbpinjam->select('*');
		$this->dbpinjam->from('peminjaman p');
		$this->dbpinjam->where('id',$id);
		$dff = $this->dbpinjam->get();
		return $dff;
		
	}
	function get_data_peminjaman_id_ra($nopinjam){
		$this->dbpinjam->select('*');
		$this->dbpinjam->from('peminjaman p');
		$this->dbpinjam->where('nopinjam',$nopinjam);
		$dff = $this->dbpinjam->get();
		return $dff;
		
	}

	function get_data_mobil_id($id){
		$this->dbpinjam->select('*');
		$this->dbpinjam->from('mobil');
		$this->dbpinjam->where('id',$id);
		$dff = $this->dbpinjam->get();
		return $dff;
	}
	function get_data_ruang_id($id){
		$this->dbpinjam->select('*');
		$this->dbpinjam->from('ruangan');
		$this->dbpinjam->where('id',$id);
		$dff = $this->dbpinjam->get();
		return $dff; 
	}
	function get_data_alat_id($id){
		$this->dbpinjam->select('*');
		$this->dbpinjam->from('alat');
		$this->dbpinjam->where('id',$id);
		$dff = $this->dbpinjam->get();
		return $dff;
	}

	function get_data_pinjam_mobil(){
		$this->dbpinjam->select('p.*');
		$this->dbpinjam->select('p.id as id_pjm');
		$this->dbpinjam->select('p.status as sts_pjm');

		$this->dbpinjam->select('m.*');
		$this->dbpinjam->select('peminjaman_mobil.*');
		
		$this->dbpinjam->from('peminjaman p');
		$this->dbpinjam->join('peminjaman_mobil', 'p.id = peminjaman_mobil.id_pinjam','left');
		$this->dbpinjam->join('mobil m', 'p.itmpinjam = m.id','left');
		
		$this->dbpinjam->order_by('p.id', 'DESC');
		$this->dbpinjam->where('p.jnspinjam', 'a1');
		$dff = $this->dbpinjam->get();
		return $dff;
	}	
	function get_data_pinjam_ruang(){
		$this->dbpinjam->select('p.*');
		
		$this->dbpinjam->select('p.id as id_pjm');
		$this->dbpinjam->select('p.status as sts_pjm');
		
		$this->dbpinjam->select('r.*');
		$this->dbpinjam->from('peminjaman p');
		$this->dbpinjam->join('ruangan r', 'p.itmpinjam = r.id','left');
		
		$this->dbpinjam->order_by('p.id', 'DESC');
		$this->dbpinjam->where('p.jnspinjam', 'a2');
		$dff = $this->dbpinjam->get();
		return $dff;
	}
	function get_data_pinjam_alat(){
		$this->dbpinjam->select('p.*');
		
		$this->dbpinjam->select('p.id as id_pjm');
		$this->dbpinjam->select('p.status as sts_pjm');
		
		$this->dbpinjam->select('a.*');
		$this->dbpinjam->from('peminjaman p');
		$this->dbpinjam->join('alat a', 'p.itmpinjam = a.id','left');
		
		$this->dbpinjam->order_by('p.id', 'DESC');
		$this->dbpinjam->where('p.jnspinjam', 'a3');
		$dff = $this->dbpinjam->get();
		return $dff;
	}
 
	function get_data_pinjam_mobil_by_id($id){
		$this->dbpinjam->select('p.*');
		$this->dbpinjam->select('p.id as id_pjm');
		$this->dbpinjam->select('p.status as sts_pjm');

		$this->dbpinjam->select('m.nopol,m.nmerk');
		$this->dbpinjam->select('peminjaman_mobil.*');
		
		$this->dbpinjam->from('peminjaman p');
		$this->dbpinjam->join('peminjaman_mobil', 'p.id = peminjaman_mobil.id_pinjam','left');
		$this->dbpinjam->join('mobil m', 'p.itmpinjam = m.id','left');
	
		$this->dbpinjam->where('p.id',$id);
		$dff = $this->dbpinjam->get();
		return $dff;
		
	}
	function get_data_pinjam_ruang_by_id($id) {
		$this->dbpinjam->select('p.*');
		$this->dbpinjam->select('p.id as id_pjm');
		$this->dbpinjam->select('p.status as sts_pjm');
		
		$this->dbpinjam->select('r.nmruang as nmruang');

		$this->dbpinjam->from('peminjaman p');
		$this->dbpinjam->join('ruangan r', 'p.itmpinjam = r.id','left');
		
		$this->dbpinjam->where('p.id', $id);
		$dff = $this->dbpinjam->get();
		return $dff;
	}
	function get_data_pinjam_alat_by_id($id) {
        $this->dbpinjam->select('p.*');
        $this->dbpinjam->select('p.id as id_pjm');
        $this->dbpinjam->select('p.status as sts_pjm');

		$this->dbpinjam->select('a.nmbarang as nmbarang');
        
        $this->dbpinjam->from('peminjaman p');
		$this->dbpinjam->join('alat a', 'p.itmpinjam = a.id','left');
		
        $this->dbpinjam->where('p.id', $id);
        $dff = $this->dbpinjam->get();
        return $dff;
    }
	function get_data_pinjam_ruang_by_nopin($nopinjam) {
		$this->dbpinjam->select('p.*');
		$this->dbpinjam->select('p.id as id_pjm');
		$this->dbpinjam->select('p.status as sts_pjm');
		
		$this->dbpinjam->select('r.nmruang as nmruang');

		$this->dbpinjam->from('peminjaman p');
		$this->dbpinjam->join('ruangan r', 'p.itmpinjam = r.id','left');
		
		$this->dbpinjam->where('p.nopinjam', $nopinjam);
		$dff = $this->dbpinjam->get();
		return $dff;
	}
	function get_data_pinjam_alat_by_nopin($nopinjam) {
        $this->dbpinjam->select('p.*');
        $this->dbpinjam->select('p.id as id_pjm');
        $this->dbpinjam->select('p.status as sts_pjm');

		$this->dbpinjam->select('a.nmbarang as nmbarang');
        
        $this->dbpinjam->from('peminjaman p');
		$this->dbpinjam->join('alat a', 'p.itmpinjam = a.id','left');
		
        $this->dbpinjam->where('p.nopinjam', $nopinjam);
        $dff = $this->dbpinjam->get();
        return $dff;
    }
	
	function get_mobil_redy($data){
		$ti=explode("-", $data['ini']);
		$to=explode("-", $data['oto']);
		$tgin=mktime(0, 0, 0, $ti[1], $ti[2], $ti[0]);
		$tgot=mktime(0, 0, 0, $to[1], $to[2], $to[0]);
		$q="SELECT p.idpinjam from peminjaman p WHERE p.jnspinjam='a1' AND ((? BETWEEN tglin  AND tglot) OR ( ? BETWEEN tglin  AND tglot)) ";
		$df=$this->dbpinjam->query($q, array($tgin,$tgot));
		$dpj=$df->row_array();
		if (isset($dpj)) {	
			$qq="SELECT * from mobil WHERE id NOT IN ? AND status=0";
			$dff=$this->dbpinjam->query($qq, array($dpj));
		}else{
			$dff = $this->dbpinjam->get_where('mobil', array('status' => 0));
		}
		return $dff;

		/*$this->dbpinjam->select('*');
		$this->dbpinjam->from('peminjaman');
		$this->dbpinjam->leftjoin('mobil', 'peminjaman.idpinjam = mobil.id');
		$this->dbpinjam->where('peminjaman.jnspinjam', $data['kode']);

		$wh = "peminjaman.tglin NOT BETWEEN AND ";
		$this->dbpinjam->where($wh);
		$this->dbpinjam->where('peminjaman.tglin ', $data['kode']);
		$query = $this->db->get();
		$query = $this->dbpinjam->get_where('mobil', array('status' => 0));
		return $query;*/
	} 
	function get_ruangan_redy(){
		$this->dbpinjam->order_by('id', 'desc');
		return $this->dbpinjam->get('ruangan');
	}
	function get_alat_redy(){
		$this->dbpinjam->order_by('id', 'desc');
		return $this->dbpinjam->get('alat');
	}

	function get_mobil_redy_js($data){
		$ti=explode("-", $data['ini']);
		$to=explode("-", $data['oto']);
		$tgin=mktime(0, 0, 0, $ti[1], $ti[2], $ti[0]);
		$tgot=mktime(0, 0, 0, $to[1], $to[2], $to[0]);
		$tjn=$data['tjn'];
		$prl=$data['prl'];
		$q="SELECT p.itmpinjam from peminjaman p WHERE p.jnspinjam='a1' AND ((? BETWEEN tglin  AND tglot) OR ( ? BETWEEN tglin  AND tglot)) ";
		$df=$this->dbpinjam->query($q, array($tgin,$tgot));
		$dpj=$df->result();
		$temp=array();

		foreach ($dpj as $ke) {
			array_push($temp,$ke->itmpinjam); 
            //$temp[]=$d->itmpinjam;
        }
		if(isset($temp) && !empty($temp)) {
			$qq="SELECT * from mobil WHERE r_perlu like '%$prl%' AND r_tujuan like '%$tjn%' AND id NOT IN ? AND status=0 AND hak=7"; 
			//$dff=$this->dbpinjam->query($qq, array($dpj));
			$dff = $this->dbpinjam->get('mobil');
		}else{
			$array = array('r_perlu' => $prl, 'r_tujuan' => $tjn);
			$this->dbpinjam->like($array);
			$dff = $this->dbpinjam->get_where('mobil', array('status' => 0,'hak' => 7));
		}
		
		return $dff;
	}
	function get_ruangan_redy_js($data) {
		// Parse tanggal
		$ti = explode("-", $data['ini']);
		$to = explode("-", $data['oto']);
		// Mengambil waktu mulai dan waktu akhir
		$tmin = $data['tmin'];
		$tmot = $data['tmot'];
		$tgin = mktime(0, 0, 0, $ti[1], $ti[2], $ti[0]);
		$tgot = mktime(0, 0, 0, $to[1], $to[2], $to[0]);
	
		// Query untuk memeriksa apakah ada peminjaman dengan jnspinjam='a2'
		$q = "SELECT DISTINCT p.itmpinjam 
			  FROM peminjaman p 
			  WHERE p.jnspinjam = 'a2' 
			  AND (
				  (? BETWEEN p.tglin AND p.tglot)
				  OR (? BETWEEN p.tglin AND p.tglot)
			  )
			  AND ((? > p.timein AND  ?<p.timeot)
				OR (? > p.timein AND  ?<p.timeot)
				OR (? = p.timein AND  ?>=p.timeot)
				OR (? <= p.timein AND  ?>=p.timeot)			
				OR (?= p.timein AND ? = p.timeot )
				OR (?>= p.timein AND ? <=p.timeot )  )";
		 
		$df = $this->dbpinjam->query($q, array($tgin, $tgot,$tmin,$tmin,$tmot,$tmot,$tmin,$tmot,$tmin,$tmot,$tmin,$tmot,$tmin,$tmot));
		$dpj = $df->result(); // Menggunakan result_array() untuk mendapatkan semua ID yang relevan
		$temp=array();
		foreach ($dpj as $ke) {

			array_push($temp,$ke->itmpinjam);
            //$temp[]=$value->itmpinjam;
        }
		if (isset($temp) && $temp!=null){
			$qq="SELECT * FROM ruangan WHERE id NOT IN? AND status=0";
			$dff = $this->dbpinjam->query($qq, array($temp));
		}else{
			$this->dbpinjam->where('status','0');
			$dff = $this->dbpinjam->get('ruangan');
		}
		return $dff;
	}
	
	function get_alat_redy_js($data) {
		// Parse tanggal
		$ti = explode("-", $data['ini']);
		$to = explode("-", $data['oto']);
		// Mengambil waktu mulai dan waktu akhir
		$tmin = $data['tmin'];
		$tmot = $data['tmot'];
		$tgin = mktime(0, 0, 0, $ti[1], $ti[2], $ti[0]);
		$tgot = mktime(0, 0, 0, $to[1], $to[2], $to[0]);
	
		// Query untuk memeriksa apakah ada peminjaman dengan jnspinjam='a2'
		$q = "SELECT DISTINCT p.itmpinjam 
			  FROM peminjaman p 
			  WHERE p.jnspinjam = 'a3' 
			  AND (
				  (? BETWEEN p.tglin AND p.tglot)
				  OR (? BETWEEN p.tglin AND p.tglot)
			  )
			  AND ((? > p.timein AND  ?<p.timeot)
				OR (? > p.timein AND  ?<p.timeot)
				OR (? = p.timein AND  ?>=p.timeot)
				OR (? <= p.timein AND  ?>=p.timeot)			
				OR (?= p.timein AND ? = p.timeot )
				OR (?>= p.timein AND ? <=p.timeot )  )";
		 
		$df = $this->dbpinjam->query($q, array($tgin, $tgot,$tmin,$tmin,$tmot,$tmot,$tmin,$tmot,$tmin,$tmot,$tmin,$tmot,$tmin,$tmot));
		$dpj = $df->result(); // Menggunakan result_array() untuk mendapatkan semua ID yang relevan
		$temp=array();
		foreach ($dpj as $ke) { 

			array_push($temp,$ke->itmpinjam);
            //$temp[]=$value->itmpinjam;
        }
		if (isset($temp) && $temp!=null){
			$qq="SELECT * FROM alat WHERE id NOT IN? AND status=0";
			$dff = $this->dbpinjam->query($qq, array($temp));
		}else{
			$this->dbpinjam->where('status','0');
			$dff = $this->dbpinjam->get('alat');
		}
		return $dff;
	}

	function save_peminjaman($data){
		$tb='peminjaman'; 
		$ti=explode("-", $data['tglin']);
		$to=explode("-", $data['tglot']);
		$tgin=mktime(0, 0, 0, $ti[1], $ti[2], $ti[0]);
		$tgot=mktime(0, 0, 0, $to[1], $to[2], $to[0]);
 
		 // Default values for timein and timeot
		 $timein = 0;
		 $timeot = 24;
	 
		 // Set timein and timeot based on jenis pinjaman
		 if ($data['jns'] == 'a2' || $data['jns'] == 'a3') {
			 $timein = $data['timin'];
			 $timeot = $data['timot'];
		 }
		$inn = array(
			'nopinjam' => $data['nosur'], 
			'tglaju' => $data['tgl'], 
			'idadmin' => $data['idadm'], 
			'jnspinjam' => $data['jns'], 
			'itmpinjam' => $data['itm'], 
			'tglin' => $tgin, 
			'timein' => $timein,
			//'timein' => 0,  
			'tglot' => $tgot, 
			'timeot' => $timeot, 
			'status' => 0, 
			'ket' => $data['ket'], 
			'ip' => $data['ip']
		);
		$dh=$this->dbpinjam->insert($tb,$inn);
		$in=$this->dbpinjam->insert_id();
		if ($data['jns']=='a1') {
			$hrv="-";
			for ($iw=0; $iw < count($data['nmpenumpang']); $iw++) { 
				$hrv=$hrv.$data['nmpenumpang'][$iw]."-";
			}
			$ikd = array(
				'id_pinjam' => $in, 
				'mbl_tujuan' => $data['tjn'], 
				'mbl_perlu' => $data['mbl-perlu'], 
				'mbl_ket_perlu' => $data['ketperlu'],
				'drv' => $data['drv'],
				'penumpang' => $hrv
			);
			$ia=$this->dbpinjam->insert("peminjaman_mobil",$ikd);
			return $ia;

		}else{
			return $dh;
		}
	}
	
	function ubah_st_pinjam($data){
		$tb='peminjaman';
		$inn = array(
			'status' => $data['pilihstatus']
		);
		$this->dbpinjam->set($inn);
		$this->dbpinjam->where('id', $data['id']);
		return $dh=$this->dbpinjam->update($tb);
	}
	
	//mobil
	function s_e_pinjam_mobil($data){
		$tb='peminjaman';
		$ti=explode("-", $data['tglin']);
		$to=explode("-", $data['tglot']);
		$tgin=mktime(0, 0, 0, $ti[1], $ti[2], $ti[0]);
		$tgot=mktime(0, 0, 0, $to[1], $to[2], $to[0]);
		$inn = array(
			'itmpinjam' => $data['itm'], 
			'tglin' => $tgin, 
			'tglot' => $tgot, 
			'ket' => $data['ket'], 
			'ip' => $data['ip']
		);
		$this->dbpinjam->set($inn);
		$this->dbpinjam->where('id', $data['idpinjam']);
		$dh=$this->dbpinjam->update($tb);
		
		if ($dh) {
			$hrv="-";
			for ($iw=0; $iw < count($data['nmpenumpang']); $iw++) { 
				$hrv=$hrv.$data['nmpenumpang'][$iw]."-";
			}
			$ikd = array(
				
				'mbl_tujuan' => $data['tjn'], 
				'mbl_perlu' => $data['mbl-perlu'], 
				'mbl_ket_perlu' => $data['ketperlu'],
				'drv' => $data['drv'],
				'penumpang' => $hrv
			);
			$this->dbpinjam->set($ikd);
			$this->dbpinjam->where('id_pinjam', $data['idpinjam']);
			$ia=$this->dbpinjam->update("peminjaman_mobil");
			return $ia;
		}else{
			return FALSE;
		}
	}
	function hapus_pinjam_mobil($id){
		$this->dbpinjam->where('id', $id);
		$in=$this->dbpinjam->delete('peminjaman');

		$this->dbpinjam->where('id_pinjam', $id);
		$on=$this->dbpinjam->delete('peminjaman_mobil');
		if ($in && $on) {
			return TRUE;
		}else{
			return FALSE;
		}

	}

	//ruangan
	function s_e_pinjam_ruangan($data){
		$tb='peminjaman';
		$ti=explode("-", $data['tglin']);
		$to=explode("-", $data['tglot']);
		$tgin=mktime(0, 0, 0, $ti[1], $ti[2], $ti[0]);
		$tgot=mktime(0, 0, 0, $to[1], $to[2], $to[0]);
		$inn = array(
			'itmpinjam' => $data['itm'], 
			'tglin' => $tgin, 
			'tglot' => $tgot, 
			'timein' =>  $data['timin'], 
			'timeot' =>  $data['timot'],
			'ket' => $data['ket'], 
			'ip' => $data['ip']
		);
		$this->dbpinjam->set($inn);
		$this->dbpinjam->where('id', $data['idpinjam']);
		$dh=$this->dbpinjam->update($tb);
		return $dh;
		
	}
	function hapus_pinjam_ruang($nopin){
		$this->dbpinjam->where('nopinjam', $nopin);
		return $this->dbpinjam->delete('peminjaman');
	}
	
	//alat
	function s_e_pinjam_alat($data){
		$tb='peminjaman';
		$ti=explode("-", $data['tglin']);
		$to=explode("-", $data['tglot']);
		$tgin=mktime(0, 0, 0, $ti[1], $ti[2], $ti[0]);
		$tgot=mktime(0, 0, 0, $to[1], $to[2], $to[0]);
		$inn = array(
			'itmpinjam' => $data['itm'], 
			'tglin' => $tgin, 
			'tglot' => $tgot, 
			'timein' =>  $data['timin'], 
			'timeot' =>  $data['timot'],
			'ket' => $data['ket'], 
			'ip' => $data['ip']
		);
		$this->dbpinjam->set($inn);
		$this->dbpinjam->where('id', $data['idpinjam']);
		$dh=$this->dbpinjam->update($tb);
		return $dh;
		
	}
	function hapus_pinjam_alat($nopin){
		$this->dbpinjam->where('nopinjam', $nopin);
		return $this->dbpinjam->delete('peminjaman');
	}
	
	function ubah_st_pinjam_ar($nopin, $status){
		$this->dbpinjam->where('nopinjam', $nopin);
		return $this->dbpinjam->update('peminjaman', array('status' => $status));
	}
	function get_c_ajuan($th){
		$tabel="namb".$th;
		$query=  $this->db->query("SELECT COUNT(id) as hasnil from $tabel ");
		return $query;	
	}

	//dashboard
	function get_data_pinjam() {
        $this->dbpinjam->select('idadmin, jnspinjam, COUNT(jnspinjam) as total_pinjam');
        $this->dbpinjam->group_by(['idadmin', 'jnspinjam']);//,'tglaju'
		
        $query = $this->dbpinjam->get('peminjaman');

		 return $query->result();
    }

	function get_status_summary($bulan = null, $tahun = null) {
        $this->dbpinjam->select('
            COUNT(CASE WHEN status = 0 THEN 1 END) AS total_status_0,
            COUNT(CASE WHEN status = 1 THEN 1 END) AS total_status_1,
            COUNT(CASE WHEN status = 2 THEN 1 END) AS total_status_2,
            COUNT(*) AS total_keseluruhan,

			SUM(CASE WHEN jnspinjam = "a1" THEN 1 ELSE 0 END) as total_a1,
            SUM(CASE WHEN jnspinjam = "a2" THEN 1 ELSE 0 END) as total_a2,
            SUM(CASE WHEN jnspinjam = "a3" THEN 1 ELSE 0 END) as total_a3
        ');
		$this->dbpinjam->from('peminjaman');
		

		if ($bulan && $tahun) {	
            $this->dbpinjam->where('MONTH(tglaju)', $bulan);
            $this->dbpinjam->where('YEAR(tglaju)', $tahun);
        }

        return $this->dbpinjam->get()->row_array();
	}

	public function get_peminjaman_events($jnspinjam = null) {
        $this->dbpinjam->select('peminjaman.id, peminjaman.itmpinjam, peminjaman.tglin, peminjaman.tglot, peminjaman.timein, peminjaman.timeot, peminjaman.jnspinjam, peminjaman.ket, peminjaman.status,  mobil.nopol AS nopol, ruangan.nmruang AS nmruang, alat.nmbarang AS nmbarang');
        $this->dbpinjam->from('peminjaman');
        $this->dbpinjam->join('mobil', 'peminjaman.itmpinjam = mobil.id', 'left');
        $this->dbpinjam->join('ruangan', 'peminjaman.itmpinjam = ruangan.id', 'left');
        $this->dbpinjam->join('alat', 'peminjaman.itmpinjam = alat.id', 'left');
        
		/*if ($jnspinjam && $jnspinjam != 'all') {
			$this->dbpinjam->where('peminjaman.jnspinjam', $jnspinjam);
		}*/
		

        $query = $this->dbpinjam->get();
        $events = []; 
        
        foreach ($query->result() as $row) { 
			
			$title = '';
			if ($row->jnspinjam == 'a1') {
				$title = $row->nopol; // From mobil table
			} elseif ($row->jnspinjam == 'a2') {
				$title = $row->nmruang . ', ' . $row->timein.':'.'0'.'0'.  ' - ' .$row->timeot.':'.'0'.'0'; // From ruangan table
			} elseif ($row->jnspinjam == 'a3') {
				$title = $row->nmbarang . ', ' . $row->timein.':'.'0'.'0'.  ' - ' .$row->timeot.':'.'0'.'0'; // From alat table
			}

            // $start = date('Y-m-d ', $row->tglot);
            // $end = date('Y-m-d ', $row->tglot);
			
			 // Menggabungkan tglin dan timein dalam format Y-m-d H:i:s
			 $start = date('Y-m-d', $row->tglin) . ' ' . str_pad($row->timein, 2, '0', ) . ':00';
			 $end = date('Y-m-d', $row->tglot) . ' ' . str_pad($row->timeot, 2, '0', ) . ':00';
		 
			
	 
            $className = '';
            $description = $row->ket;
            
            if ($row->jnspinjam == 'a1') {
                $className = 'bg-warning';
            } elseif ($row->jnspinjam == 'a2') {
                $className = 'bg-info';
                $description;
            } elseif ($row->jnspinjam == 'a3') {
                $className = 'bg-primary';
                $description;
            }

			
			// Warna tambahan lingkaran berdasarkan status
			$statusIndicator = '';
			if ($row->status == 0) {
				$statusIndicator = 'bg-secondary';
			} elseif ($row->status == 1) {
				$statusIndicator = 'bg-success';
			} elseif ($row->status == 2) {
				$statusIndicator = 'bg-danger';
			}

            $events[] = [
                'id' => $row->id,
                'title' => $title,
                'start' => $start,
                'end' => $end,
                'className' => $className,
				'status' =>  $statusIndicator,
				'statusColor' => $statusIndicator,
                'description' => $description
            ];
        }
        
        return $events;
    }


	
}
