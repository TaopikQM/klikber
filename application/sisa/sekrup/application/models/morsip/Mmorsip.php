<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mmorsip extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	function get_c_ajuan($th){
		$tabel="namb".$th;
		$query=  $this->db->query("SELECT COUNT(id) as hasnil from $tabel ");
		return $query;	
	}
	function get_c_ajuan_spt($th){
		$tabel="spt".$th;
		$query=  $this->db->query("SELECT COUNT(id) as hasnil from $tabel ");
		return $query;	
	}
	function get_c_ajuan_pegawai($th){
		$tabel="pegawai".$th;
		$query=  $this->db->query("SELECT COUNT(id) as hasnil from $tabel ");
		return $query;	
	}
	function get_c_ajuan_jabatan(){
		$tabel="jabatan";
		$query=  $this->db->query("SELECT COUNT(id) as hasnil from $tabel ");
		return $query;	
	}
	function get_c_ajuan_dasrut(){
		$tabel="mdasrut";
		$query=  $this->db->query("SELECT COUNT(id) as hasnil from $tabel ");
		return $query;	
	}
	function get_c_ajuan_perlu(){
		$tabel="untuk";
		$query=  $this->db->query("SELECT COUNT(id) as hasnil from $tabel ");
		return $query;	
	}
	function get_c_ajuan_datir($th){
		$tabel="nambdatir".$th;
		$query=  $this->db->query("SELECT COUNT(id) as hasnil from $tabel ");
		return $query;	
	}
	function get_kodesur(){
		$query = $this->db->get('kodesur');
		return $query;	
	}
	function get_jabatan(){
		$query = $this->db->get('jabatan');
		return $query;	
	}
	function get_golongan(){
		$query = $this->db->get('golruang');
		return $query;	
	}

	function get_pejabat_1(){

		$query = $this->db->get_where('pejabat', array('status' => 1));
		
		return $query;	
	}
	function get_adm($id){
		$q="SELECT u.*, b.n_bid as bidang FROM user u
				LEFT JOIN bidang b
				ON u.bid=b.id
				WHERE u.id=$id";
		$query =  $this->db->query($q);
		return $query;	
	}
	function s_admin($data){
		$inn = array(
			'bid' => $data['bid'], 
			'username' => $data['user'], 
			'pass' => sha1(md5($data['passcom'])), 
			'st' => 1 
		);

		$this->db->insert('user',$inn);
		$in=$this->db->insert_id();
		return $in;
	}
	function s_e_admin($data){
		
		if ($data['pass']!= null && ($data['pass']==$data['passcom'])) {
			$inn = array(
			'bid' => $data['bid'], 
			'username' => $data['user'], 
			'pass' => sha1(md5($data['passcom'])), 
			'st' => 1 
			);			
		}else{
			$inn = array(
				'bid' => $data['bid'], 
				'username' => $data['user'], 
				'st' => 1 
			);			
		}

		$this->db->set($inn);
		$this->db->where('id',$data['id']);
		$in=$this->db->update('user');
		return $in;
	}
	function chg_st_admin($data){
		
		print_r($data);
		$cg = array('1' => 0,'0' => 1, );
		$dt = array(
			'st' => $cg[$data['status']] );

		$this->db->set($dt);
		$this->db->where('id',$data['id']);
		$in=$this->db->update('user');
		return $in;
	}
	function s_hk($id,$hk){
		if ($hk==0) {
			$hv=$this->encryption->encrypt("admin_0_superadmin");
		}elseif($hk==1){
			$hv=$this->encryption->encrypt("admin_1_adminbiasa");
		}
		$inn = array(
			'id_apli' => base64_encode('1_morsip'), 
			'hk' => $hv, 
			'idus' => base64_encode($id)
		);
		$in=$this->db->insert('hk_apli',$inn);
		return $in;
	}
	function s_u_hk($id,$hk){
		if ($hk==0) {
			$hv=$this->encryption->encrypt("admin_0_superadmin");
		}elseif($hk==1){
			$hv=$this->encryption->encrypt("admin_1_adminbiasa");
		}
		$inn = array(
			'hk' => $hv 
		);
		$this->db->set($inn);
		$this->db->where('idus',base64_encode($id));
		$this->db->where('id_apli',base64_encode('1_morsip'));
		$in=$this->db->update('hk_apli');
		return $in;
	}
	function get_adm_all(){
		$q="SELECT u.*, b.n_bid as bidang FROM user u
				LEFT JOIN bidang b
				ON u.bid=b.id";
		$query =  $this->db->query($q);
		return $query;	
	}
	function get_hk_admin($id){
		$q="SELECT * FROM hk_apli WHERE idus=?";
		$query =  $this->db->query($q,array($id));
		return $query;	
	}
	function del_admin($id,$ap){
		$this->db->where('id',$id);
		$has=$this->db->delete('user');
		$q="DELETE FROM hk_apli WHERE idus=? AND id_apli=?";
		$query =  $this->db->query($q,array(base64_encode($id),$ap));
		return $query;
	}
	function get_nom($th){
		$tabel="namb".$th;
		$q="SELECT max(nosur) as num FROM $tabel";
		$query =  $this->db->query($q);
		return $query;	
	}
	function get_data($th,$limit){
		$tabel="namb".$th;
		if ($limit<=1) {
			$lim=1000;
			$of=0;
		}else{
			$lim=$limit*1000;
			$of=$lim-999;
		}

		$q="SELECT n.*, b.n_bid as bidang, k.kode as kodesur FROM $tabel n
				LEFT JOIN bidang b
				ON n.b=b.id 
				INNER JOIN kodesur k
				ON n.k = k.id ORDER BY n.id DESC LIMIT ? OFFSET ?";
			$query =  $this->db->query($q,array($lim,$of));
		return $query;	
	}
	function get_data_spt($th,$limit){
		$tabel="spt".$th;
		if ($limit<=1) {
			$lim=1000;
			$of=0;
		}else{
			$lim=$limit*1000;
			$of=$lim-999;
		}

		$q="SELECT n.*, b.n_bid as bidang FROM $tabel n
				LEFT JOIN user u
				ON n.id_adm=u.id
				LEFT JOIN bidang b
				ON u.bid=b.id 
				ORDER BY n.id DESC LIMIT ? OFFSET ?";
			$query =  $this->db->query($q,array($lim,$of));
		return $query;	
	}
	function get_data_pegawai($th,$limit){
		$tabel="pegawai".$th;
		if ($limit<=1) {
			$lim=1000;
			$of=0;
		}else{
			$lim=$limit*1000;
			$of=$lim-999;
		}

		$q="SELECT n.*, j.nama_jabatan, g.golru FROM $tabel n
				LEFT JOIN jabatan j
				ON n.jabatan=j.id
				LEFT JOIN golruang g
				ON n.gol=g.id 
				ORDER BY n.id LIMIT ? OFFSET ?";
			$query =  $this->db->query($q,array($lim,$of));
		return $query;	
	}
	function get_data_jabatan($limit){
		$tabel="jabatan";
		if ($limit<=1) {
			$lim=1000;
			$of=0;
		}else{
			$lim=$limit*1000;
			$of=$lim-999;
		}

		$q="SELECT * FROM $tabel ORDER BY id LIMIT ? OFFSET ?";
		$query =  $this->db->query($q,array($lim,$of));
		return $query;	
	}
	function get_data_nmdl($data,$th){
		$mn=array();
		$has=explode("-",trim($data,"-"));
		$mn=null;
		for ($i=0; $i <count($has) ; $i++) { 
			$tabel="pegawai".$th;
			$id=base64_decode($has[$i]);
			
			$query=  $this->db->query("SELECT nama from $tabel WHERE id=$id ");
			$row = $query->row();
			if ($mn==null) {
				$mn=base64_decode($row->nama);
			}else{
				$mn=$mn."-".base64_decode($row->nama);
			}	
		}
		return $mn;
	}
	function get_nomor_by_id($th,$id){
		$tabel="namb".$th;
		$q="SELECT n.*, b.n_bid as bidang, k.kode as kodesur FROM $tabel n
				LEFT JOIN bidang b
				ON n.b=b.id 
				INNER JOIN kodesur k
				ON n.k = k.id 
				WHERE n.id=? limit 1";
			$query =  $this->db->query($q,array($id));
		return $query;	
	}
	function get_nomor_by_nomor($th,$mo){
		$tabel="namb".$th;
		$q="SELECT n.*, b.n_bid as bidang, k.kode as kodesur FROM $tabel n
				LEFT JOIN bidang b
				ON n.b=b.id 
				INNER JOIN kodesur k
				ON n.k = k.id 
				WHERE n.nosur=? limit 1";
			$query =  $this->db->query($q,array($mo));
		return $query;	
	}
	function s_nomor($data){
		$tb='namb'.$data['tahun'];
		$inn = array(
			'idad' => $data['idad'], 
			'k' => $data['kodesur'], 
			'nosur' => $data['nomor'], 
			'tgl' => $data['tgl'], 
			'perihal' => strip_tags($data['perihal'],"<b><i><u>"), 
			'kpd' => $data['kpd'], 
			'b' => $data['bidang'], 
			'nmf' => $data['nmf'], 
			'sts' => 1 
		);
		//print_r($inn);
		$this->db->insert($tb,$inn);
		$in=$this->db->insert_id();
		return $in;
	}
	function s_nomor_datir($data){
		$tb='nambdatir'.$data['tahun'];
		$inn = array(
			'idad' => $data['idad'], 
			'k' => $data['kodesur'], 
			'nosur' => $data['nomor'], 
			'tgl' => $data['tgl'], 
			'perihal' => strip_tags($data['perihal'],"<b><i><u>"), 
			'kpd' => $data['kpd'], 
			'nmf' => $data['nmf'], 
			'b' => $data['bidang'], 
			'sts' => 1 
		);
		//print_r($inn);
		$this->db->insert($tb,$inn);
		$in=$this->db->insert_id();
		return $in;
	}
	function s_jabatan($data){
		$tb='jabatan';
		$inn = array('nama_jabatan' => $data['nama']);
		$in=$this->db->insert($tb,$inn);
		return $in;
	}
	function s_dasrut($data){
		$tb='mdasrut';
		$inn = array('isi' => $data['nama'],'sts' => 1);
		$in=$this->db->insert($tb,$inn);
		return $in;
	}
	function s_perlu($data){
		$tb='untuk';
		$inn = array('isi' => $data['nama'],'status' => 1);
		$in=$this->db->insert($tb,$inn);
		return $in;
	}
	function s_e_dasrut($data){
		$tb='mdasrut';
		$nama=$data['nama'];
		$id=$data['id'];
	
		$inn = array(
			'isi' => $nama
		);
		
		$this->db->set($inn);
		$this->db->where('id',$data['id']);
		$in=$this->db->update($tb);
		return $in;
	}
	function s_e_perlu($data){
		$tb='untuk';
		$nama=$data['nama'];
		$id=$data['id'];
	
		$inn = array(
			'isi' => $nama
		);
		
		$this->db->set($inn);
		$this->db->where('id',$data['id']);
		$in=$this->db->update($tb);
		return $in;
	}
	function s_e_jabatan($data){
		$tb='jabatan';
		$nama=$data['nama'];
		$id=$data['id'];
	
		$inn = array(
			'nama_jabatan' => $nama
		);
		
		$this->db->set($inn);
		$this->db->where('id',$data['id']);
		$in=$this->db->update($tb);
		return $in;
	}
	function s_pegawai($data){
		$tb='pegawai'.$data['tahun'];
		$nama=$data['nama'];
		/*if ($data['glrd'] != NULL && $data['glrb'] != NULL) {
			$nama=$data['glrd'].", ".strtoupper($data['nama']).", ".$data['glrb'];
		}elseif ($data['glrd'] != NULL && $data['glrb'] == NULL) {
			$nama=$data['glrd'].", ".strtoupper($data['nama']);
			
		}elseif ($data['glrd'] == NULL && $data['glrb'] != NULL) {
			$nama=strtoupper($data['nama']).", ".$data['glrb'];
		}else{
			$nama=strtoupper($data['nama']);			
		}*/
	
		if ($data['nip']=="00000000 000000 0 000") {
			$nip="-";
		}else{
			$nip=$data['nip'];
		}
		
		$tb='pegawai'.$data['tahun'];
		$inn = array(
			'nip' =>$this->encryption->encrypt($nip), 
			'nama' => base64_encode(trim($nama)), 
			'jabatan' => $data['jabatan'], 
			'gol' => $data['golongan'], 
			'sortir' => $data['hk'], 
			'status' => 1 
		);
		
		$this->db->insert($tb,$inn);
		$in=$this->db->insert_id();
		return $in;
	}
	function s_e_pegawai($data){
		$tb='pegawai'.$data['tahun'];
		$nama=$data['nama'];
	
		if ($data['nip']=="00000000 000000 0 000") {
			$nip="-";
		}else{
			$nip=$data['nip'];
		}
		
		$tb='pegawai'.$data['tahun'];
		$inn = array(
			'nip' =>$this->encryption->encrypt($nip), 
			'nama' => base64_encode(trim($nama)), 
			'jabatan' => $data['jabatan'], 
			'gol' => $data['golongan'], 
			'sortir' => $data['hk'], 
			'status' => 1 
		);
		
		$this->db->set($inn);
		$this->db->where('id',$data['id']);
		$in=$this->db->update($tb);
		return $in;
	}
	function get_pegawai_by_id($data){
		$tabel="pegawai".$data['tahun'];
		$this->db->where('id',$data['id']);
		$query = $this->db->get($tabel);
		return $query;		
	}
	function get_jabatan_by_id($data){
		$tabel="jabatan";
		$this->db->where('id',$data['id']);
		$query = $this->db->get($tabel);
		return $query;		
	}
	function get_dasrut_by_id($data){
		$tabel="mdasrut";
		$this->db->where('id',$data['id']);
		$query = $this->db->get($tabel);
		return $query;		
	}
	function get_perlu_by_id($data){
		$tabel="untuk";
		$this->db->where('id',$data['id']);
		$query = $this->db->get($tabel);
		return $query;		
	}
	function s_e_nomor($data){
		$tabel="namb".$data['tahun'];
		$inn = array(
			'tgl' => $data['tgl'],  
			'k' => $data['kodesur'], 
			'perihal' => strip_tags($data['perihal'],"<br><b><i><u>"),  
			'kpd' => $data['kpd'],  
			'nmf' => $data['nmf']  
		);
		$this->db->set($inn);
		$this->db->where('id',$data['id']);
		$in=$this->db->update($tabel);
		return $in;
	}
	function s_e_nomor_datir($data){
		$tabel="nambdatir".$data['tahun'];
		$inn = array(
			'b' => $data['idbid'], 
			'k' => $data['kodesur'], 
			'perihal' => strip_tags($data['perihal'],"<br><b><i><u>"),  
			'kpd' => $data['kpd'],  
			'nmf' => $data['nmf']  
		);
		/*echo "<pre>";
		print_r($data);
		print_r($inn);
		echo "</pre>";*/
		$this->db->set($inn);
		$this->db->where('id',$data['id']);
		$in=$this->db->update($tabel);
		return $in;
	}
	function s_e_doc($data){
		$tabel="namb".$data['tahun'];
		$inn = array(
			'nmf' => $data['nmf']  
		);
		$this->db->set($inn);
		$this->db->where('id',$data['id']);
		$in=$this->db->update($tabel);
		return $in;
	}
	function s_e_tte($data){
		$tabel="namb".$data['tahun'];
		$inn = array(
			'a' => 1  
		);
		$this->db->set($inn);
		$this->db->where('id',$data['id']);
		$in=$this->db->update($tabel);
		return $in;
	}
	function del_nomor($id,$tahun){
		$tabel="namb".$tahun;
		$inn = array(  
			'k' => 1, 
			'perihal' => "TIDAK JADI DIPAKAI",  
			'kpd' => "TIDAK JADI DIPAKAI",  
			'nmf' => NULL,  
			'isu' => 1  
		);
		$this->db->set($inn);
		$this->db->where('id',$id);
		$in=$this->db->update($tabel);
		return $in;
	}
	function del_nomor_datir($id,$tahun){
		$tabel="nambdatir".$tahun;
		$inn = array(  
			'k' => 1, 
			'perihal' => "TIDAK JADI DIPAKAI",  
			'kpd' => "TIDAK JADI DIPAKAI",  
			'nmf' => NULL
		);
		$this->db->set($inn);
		$this->db->where('id',$id);
		$in=$this->db->update($tabel);
		return $in;
	}
	function del_spt($id,$tahun,$idno){
		$tabel="namb".$tahun;
		$inn = array(  
			'k' => 1, 
			'perihal' => "TIDAK JADI DIPAKAI",  
			'kpd' => "TIDAK JADI DIPAKAI",  
			'nmf' => NULL,  
			'isu' => 1  
		);
		$this->db->set($inn);
		$this->db->where('id',$idno);
		$in=$this->db->update($tabel);

		$tab="spt".$tahun;
		$dt=array('id' => $id);
		$in=$this->db->delete($tab, $dt);

		return $in;
	}
	function del_jabatan($data){
		$tab="jabatan";
		$dt=array('id' => $data['id']);
		$in=$this->db->delete($tab, $dt);

		return $in;
	}
	function del_pegawai($data){
		$tabel="pegawai".$data['tahun'];
		$sdf = array('0' => 1, '1' => 0 );
		$inn = array(  
			'status' => $sdf[$data['status']]  
		);
		$this->db->set($inn);
		$this->db->where('id',$data['id']);
		$in=$this->db->update($tabel);
		return $in;
	}
	function del_dasrut($data){
		$tabel="mdasrut";
		$sdf = array('0' => 1, '1' => 0 );
		$inn = array(  
			'sts' => $sdf[$data['status']]  
		);
		$this->db->set($inn);
		$this->db->where('id',$data['id']);
		$in=$this->db->update($tabel);
		return $in;
	}
	function del_perlu($data){
		$tabel="untuk";
		$sdf = array('0' => 1, '1' => 0 );
		$inn = array(  
			'status' => $sdf[$data['status']]  
		);
		$this->db->set($inn);
		$this->db->where('id',$data['id']);
		$in=$this->db->update($tabel);
		return $in;
	}
	function get_bidang(){
		$query = $this->db->get('bidang');
		return $query;	
	}
	function get_ready_nmdl($in,$ot,$th){
		$tabel="spt".$th;
		$q="SELECT nmdl from $tabel WHERE (? BETWEEN tglin  AND tglot) OR ( ? BETWEEN tglin  AND tglot)";
		$df=$this->db->query($q, array($in,$ot))->result();
		
		$fix=array();
		foreach ($df as $key) {
			$has=explode("-", trim( $key->nmdl,"-"));
			for ($i=0; $i <count($has) ; $i++) { 
				array_push($fix,base64_decode($has[$i]));
			}
			
		}
		$pg="pegawai".$th;
		if ($fix != null) {
			$qq="SELECT * from $pg WHERE id NOT IN ? AND status=1";
			$dff=$this->db->query($qq, array($fix))->result();
		}else{
			$qq="SELECT * from $pg WHERE status=1";
			$dff=$this->db->query($qq)->result();
		}

		return $dff;
	}
	function get_nmdl_array($dat,$th){
		$pg="pegawai".$th;
		$qq="SELECT * from $pg WHERE id IN ? AND status=1";
		$dff=$this->db->query($qq, array($dat))->result();
		return $dff;
	}	
	function s_spt($data){
		$hss=$this->get_adm($data['idadm']);
		foreach ($hss->result_array() as $row){
			$ua=$row['nama'];
		}

		$gf=explode(".", $ua);
		if ($gf[0]==2) {
			$xc=$ua;
		}else{
			$xc=$gf[0];
		}
		$tb='spt'.$data['tahun'];
		$g="-";
		for ($i=0; $i <count($data['nmdl']) ; $i++) { 
			$g=$g.base64_encode($data['nmdl'][$i])."-";	
		}
		
		$tgld=explode("-",$data['tgl']);
		$nwtgl=mktime(0,0,0,$tgld[1],$tgld[2],$tgld[0]);

		$tglin=explode("-",$data['tglin']);
		$nwtglin=mktime(0,0,0,$tglin[1],$tglin[2],$tglin[0]);

		$tglot=explode("-",$data['tglot']);
		$nwtglot=mktime(0,0,0,$tglot[1],$tglot[2],$tglot[0]);


		$inn = array(
			'id_adm' => $data['idadm'], 
			'nosur' => $data['nomor'], 
			'dasrut' => base64_encode( strip_tags($data['dasrut'],"\n") ), 
			'nmdl' => $g, 
			'keperluan' => base64_encode( strip_tags($data['perlu'],"\n") ), 
			'tgldl' => $nwtgl, 
			'tglin' => $nwtglin, 
			'tglot' => $nwtglot,
			'nmttd' => $data['nmttd'],
			'kdbid' => $xc //untuk langsung ke kabid soal nya hilang esselon 4
		);
		$this->db->insert($tb,$inn);
		$in=$this->db->insert_id();
		$retur['id']=$in;
		$retur['nomor']=$data['nomor'];
		$retur['tahun']=$data['tahun'];
		if ($in != null ) {
			$tbb='namb'.$data['tahun'];
			$inna = array(
				'idad' => $data['idadm'], 
				'k' => '212', 
				'nosur' => $data['nomor'], 
				'tgl' => $data['tgl'], 
				'perihal' => strip_tags($data['perlu'],"\n"), 
				'kpd' => "YBS Bertugas", 
				'nmf' => $in, 
				'sts' => 1 
			);
			$ina=$this->db->insert($tbb,$inna);
		}else{
			$ina=null;
		}
/*		echo "<pre>";
		print_r($inn);
		echo "</pre>";*/
		if ($ina) {
			return $retur;
		}else{
			return null;
		}
		
	}
	function s_e_spt($data){
		
		$tb='spt'.$data['tahun'];
		$g="-";
		for ($i=0; $i <count($data['nmdl']) ; $i++) { 
			$g=$g.base64_encode($data['nmdl'][$i])."-";	
		}
		
		$tgld=explode("-",$data['tgl']);
		$nwtgl=mktime(0,0,0,$tgld[1],$tgld[2],$tgld[0]);

		$tglin=explode("-",$data['tglin']);
		$nwtglin=mktime(0,0,0,$tglin[1],$tglin[2],$tglin[0]);

		$tglot=explode("-",$data['tglot']);
		$nwtglot=mktime(0,0,0,$tglot[1],$tglot[2],$tglot[0]);


		$inn = array(
			'dasrut' => base64_encode( strip_tags($data['dasrut'],"\n") ), 
			'nmdl' => $g, 
			'keperluan' => base64_encode( strip_tags($data['perlu'],"\n") ), 
			'tgldl' => $nwtgl, 
			'tglin' => $nwtglin, 
			'tglot' => $nwtglot,
			'nmttd' => $data['nmttd'],
			 //untuk langsung ke kabid soal nya hilang esselon 4
		);
		$this->db->set($inn);
		$this->db->where('id',$data['idspt']);
		$in=$this->db->update($tb);
/*		echo "<pre>";
		print_r($inn);
		echo "</pre>";*/
		return $in;
		
	}
	function get_spt($id,$tahun){
		$tabel="spt".$tahun;
		$this->db->where('id',$id);
		$in = $this->db->get($tabel);
		
		return $in;
	}
	function get_dasur(){
		$this->db->where('sts','1');
		$in = $this->db->get('mdasrut');
		return $in;
	}
	function get_data_dasrut(){
		$in = $this->db->get('mdasrut');
		return $in;
	}
	function get_data_perlu(){
		$in = $this->db->get('untuk');
		return $in;
	}
	function get_untuk(){
		$this->db->where('status','1');
		$in = $this->db->get('untuk');
		return $in;
	}
	function get_data_datir($th,$limit){
		$tabel="nambdatir".$th;
		if ($limit<=1) {
			$lim=1000;
			$of=0;
		}else{
			$lim=$limit*1000;
			$of=$lim-999;
		}
		$q="SELECT n.*, b.n_bid as bidang, k.kode as kodesur FROM $tabel n
				LEFT JOIN bidang b
				ON n.b=b.id 
				INNER JOIN kodesur k
				ON n.k = k.id ORDER BY n.id DESC LIMIT ? OFFSET ?";
			$query =  $this->db->query($q,array($lim,$of));
		return $query;	
	}
	function get_ready_datir($in,$th){
		$tabel="namb".$th;
		$q="SELECT n.nosur, b.n_bid from $tabel n  
			LEFT JOIN bidang b
			ON n.b=b.id WHERE n.tgl LIKE '%$in%' ";
		$df=$this->db->query($q)->result();
		return $df;
	}
	function get_like_datir($in,$th){
		$tabel="nambdatir".$th;
		$q="SELECT n.nosur from $tabel n WHERE n.nosur LIKE '%$in%' ";
		$df=$this->db->query($q)->result();
		return $df;
	}
	function get_nomor_datir_by_id($th,$id){
		$tabel="nambdatir".$th;
		$q="SELECT n.*, b.n_bid as bidang, k.kode as kodesur FROM $tabel n
				LEFT JOIN bidang b
				ON n.b=b.id 
				INNER JOIN kodesur k
				ON n.k = k.id 
				WHERE n.id=? limit 1";
			$query =  $this->db->query($q,array($id));
		return $query;	
	}





















	
	

















	function get_jbt($j){
		$sql = "SELECT * FROM jb WHERE id IN ?";
		return $this->db->query($sql, array($j));
	}
	function get_jbt_fungsi(){
		$query = $this->db->get('jabatan');
		return $query;	
	}
	function get_pend(){
		$query = $this->db->get('pend');
		return $query;	
	}
	function get_gol(){
		$query = $this->db->get('golongan');
		return $query;	
	}
	function get_c_surat(){
		$query = $this->db->count_all('surat');
		return $query;	
	}
	function get_c_nmjabfung($ja){
		$q="SELECT count(id) FROM nmjabfung WHERE jbt IN ?";
		$query=$this->db->query($q,array($ja));
		return $query;	
	}
	
	function get_c_notif(){
		
		$q="SELECT surat.id,surat.tgl,surat.nosur,surat.st_trm from surat LEFT JOIN nmjabfung
			ON surat.nip=nmjabfung.id
			WHERE st_vw=0 AND st_trm=0";

			$query=$this->db->query($q);
		
		return $query;	
	}
	function get_link_terbit($id){
		$q="SELECT link FROM nil 
			WHERE id_sur=?";
			$query=$this->db->query($q,array($id));
		return $query;	
	}
	function ubah_st_vw_user($id){
		$kl=str_repeat("?,", count($id)-1) . "?";
		$query="UPDATE surat SET st_vw=1 WHERE id IN($kl)";
		
		$query=  $this->db->query($query,$id);
		return $query;
		
	}
	function t_surat($data){
		$jum=$data['jum'];
		$kab=$data['kab'];
		$jab=$data['jab'];
		$jah=$data['jah'];
		$status=$data['status'];
		$st=array($status);
		if ($status==99) {
			$st=array(0,1,2,3,4);
		}
		


		if ($kab==0 && $jab !=0) {
			$q="SELECT s.*, k.nama as nmkab, j.nama as nmjb,j.nama as jbt, n.nm, n.nip as nippeg FROM surat s, kabupaten k, jb j, nmjabfung n WHERE s.nip=n.id  AND n.jbt=j.id AND n.kab = k.id_kab AND j.id=? AND n.jbt IN ? AND s.st_trm IN ? ORDER BY s.id DESC LIMIT $jum";
			$query =  $this->db->query($q,array($jab,$jah,$st));
		}elseif ($jab==0 && $kab != 0) {
			$q="SELECT s.*, k.nama as nmkab, j.nama as nmjb,j.nama as jbt,n.nm, n.nip as nippeg FROM surat s, kabupaten k, jb j, nmjabfung n WHERE n.jbt=j.id AND n.kab = k.id_kab AND s.nip=n.id AND k.id_kab=? AND n.jbt IN ? AND s.st_trm IN ? ORDER BY s.id DESC LIMIT $jum";
			$query =  $this->db->query($q,array($kab,$jah,$st));
		}elseif ($kab!= 0 && $kab !=0){
			$q="SELECT s.*, k.nama as nmkab, j.nama as nmjb,j.nama as jbt,n.nm, n.nip as nippeg FROM surat s, kabupaten k, jb j, nmjabfung n WHERE n.jbt=j.id AND n.kab = k.id_kab AND s.nip=n.id AND k.id_kab=? AND j.id=? AND n.jbt IN ? AND s.st_trm IN ? ORDER BY s.id DESC LIMIT $jum";
			$query =  $this->db->query($q,array($kab,$jab,$jah,$st ));			
		}else{
			$q="SELECT s.*, k.nama as nmkab, j.nama as nmjb,j.nama as jbt,n.nm, n.nip as nippeg FROM surat s, kabupaten k, jb j, nmjabfung n WHERE n.jbt=j.id AND n.kab = k.id_kab AND s.nip=n.id AND n.jbt IN ? AND s.st_trm IN ? ORDER BY s.id DESC LIMIT $jum";
			$query =  $this->db->query($q,array($jah,$st));			

			
		}
		return $query;	
	}
	function t_surat_time($data){
		$jum=$data['jum'];
		$kab=$data['kab'];
		$jab=$data['jab'];
		$jah=$data['jah'];
		$status=$data['status'];
		$st=array($status);
		if ($status==99) {
			$st=array(0,1,2,3,4);
		}

		
		$b=explode("-", $data['tglin']);
		$c=$b[1]."-".$b[0];

		$d=explode("-", $data['tglot']);
		$e=$d[1]."-".$d[0];
		$tglin=$c;
		$tglot=$e;
		
		if ($kab==0 && $jab !=0) {
			$q="SELECT s.*, k.nama as nmkab, j.nama as nmjb,j.id as jbt, n.nm, n.nip as nippeg FROM surat s
				LEFT JOIN nmjabfung n
				ON s.nip=n.id 
				INNER JOIN kabupaten k
				ON n.kab = k.id_kab
				INNER JOIN jb j
				ON n.jbt=j.id AND j.id=?
				WHERE n.jbt IN ? AND s.st_trm IN ? AND s.tglin BETWEEN '$tglin' AND '$tglot' OR s.tglot BETWEEN '$tglin' AND '$tglot'
				ORDER BY s.id DESC LIMIT $jum";
			$query =  $this->db->query($q,array($jab,$jah,$st));
		}elseif ($jab==0 && $kab != 0) {
			$q="SELECT s.*, k.nama as nmkab, j.nama as nmjb,j.id as jbt, n.nm, n.nip as nippeg FROM surat s
				LEFT JOIN nmjabfung n
				ON s.nip=n.id 
				INNER JOIN kabupaten k
				ON n.kab = k.id_kab AND k.id_kab=?
				INNER JOIN jb j
				ON n.jbt=j.id 
				WHERE n.jbt IN ? AND s.st_trm IN ? AND s.tglin BETWEEN '$tglin' AND '$tglot' OR s.tglot BETWEEN '$tglin' AND '$tglot'
				ORDER BY s.id DESC LIMIT $jum";
			$query =  $this->db->query($q,array($kab,$jah,$st));
		}elseif ($kab!= 0 && $kab !=0){
			$q="SELECT s.*, k.nama as nmkab, j.nama as nmjb,j.id as jbt, n.nm, n.nip as nippeg FROM surat s
				LEFT JOIN nmjabfung n
				ON s.nip=n.id 
				INNER JOIN kabupaten k
				ON n.kab = k.id_kab AND k.id_kab=?
				INNER JOIN jb j 
				ON n.jbt=j.id AND j.id=?
				WHERE n.jbt IN ? AND s.st_trm IN ? AND s.tglin BETWEEN '$tglin' AND '$tglot' OR s.tglot BETWEEN '$tglin' AND '$tglot'
				ORDER BY s.id DESC LIMIT $jum";
			$query =  $this->db->query($q,array($kab,$jab,$jah,$st));			
		}else{
			$q="SELECT s.*, k.nama as nmkab, j.nama as nmjb,j.id as jbt, n.nm, n.nip as nippeg FROM surat s
				LEFT JOIN nmjabfung n
				ON s.nip=n.id 
				INNER JOIN kabupaten k
				ON n.kab = k.id_kab
				INNER JOIN jb j 
				ON n.jbt=j.id
				WHERE n.jbt IN ? AND s.st_trm IN ? AND s.tglin BETWEEN '$tglin' AND '$tglot' OR s.tglot BETWEEN '$tglin' AND '$tglot'
				ORDER BY s.id DESC LIMIT $jum";
			$query =  $this->db->query($q,array($jah,$st));			
			
		}
		return $query;
	}
	function t_nmjabfung($data){
		$jum=$data['jum'];
		$kab=$data['kab'];
		$jab=$data['jab'];
		$jah=$data['jah'];
		
		if ($kab==0 && $jab !=0) {
			$q="SELECT d.*, k.nama as nmkab, j.nama as nmjb, p.pend as pend, g.nmpangkat as nmpg ,g.gol as gg, n.nmjbt as nmahli, n.sortir  FROM nmjabfung d
					INNER JOIN jb j
					ON d.jbt=j.id AND j.id=?
					INNER JOIN kabupaten k
					ON d.kab=k.id_kab
					INNER JOIN pend p
					ON d.pdk = p.id
					INNER JOIN golongan g
					ON d.gol = g.id
					INNER JOIN jabatan n
					ON d.pjbt=n.id WHERE d.jbt IN ? ORDER BY j.nama LIMIT $jum";
			$query =  $this->db->query($q,array($jab,$jah));
		}elseif ($jab==0 && $kab != 0) {
			$q="SELECT d.*, k.nama as nmkab, j.nama as nmjb, p.pend as pend, g.nmpangkat as nmpg ,g.gol as gg, n.nmjbt as nmahli, n.sortir  FROM nmjabfung d
					INNER JOIN jb j
					ON d.jbt=j.id 
					INNER JOIN kabupaten k
					ON d.kab=k.id_kab AND k.id_kab=?
					INNER JOIN pend p
					ON d.pdk = p.id
					INNER JOIN golongan g
					ON d.gol = g.id
					INNER JOIN jabatan n
					ON d.pjbt=n.id WHERE d.jbt IN ? ORDER BY j.nama LIMIT $jum";
			$query =  $this->db->query($q,array($kab,$jah));
		}elseif ($kab!= 0 && $kab !=0){
			$q="SELECT d.*, k.nama as nmkab, j.nama as nmjb, p.pend as pend, g.nmpangkat as nmpg ,g.gol as gg, n.nmjbt as nmahli, n.sortir  FROM nmjabfung d
					INNER JOIN jb j
					ON d.jbt=j.id AND j.id=?
					INNER JOIN kabupaten k
					ON d.kab=k.id_kab AND k.id_kab=?
					INNER JOIN pend p
					ON d.pdk = p.id
					INNER JOIN golongan g
					ON d.gol = g.id
					INNER JOIN jabatan n
					ON d.pjbt=n.id WHERE d.jbt IN ? ORDER BY j.nama LIMIT $jum";
			$query =  $this->db->query($q,array($jab,$kab,$jah));			
		}else{
			$q="SELECT d.*, k.nama as nmkab, j.nama as nmjb, p.pend as pend, g.nmpangkat as nmpg ,g.gol as gg, n.nmjbt as nmahli, n.sortir  FROM nmjabfung d
					INNER JOIN jb j
					ON d.jbt=j.id
					INNER JOIN kabupaten k
					ON d.kab=k.id_kab 
					INNER JOIN pend p
					ON d.pdk = p.id
					INNER JOIN golongan g
					ON d.gol = g.id
					INNER JOIN jabatan n
					ON d.pjbt=n.id WHERE d.jbt IN ? ORDER BY j.nama LIMIT $jum";
			$query =  $this->db->query($q,array($jah));
		}
		return $query;	
	}
	function t_nil($data){
		$jum=$data['jum'];
		$kab=$data['kab'];
		$jab=$data['jab'];
		
		if ($kab==0 && $jab !=0) {
			$q="SELECT surat.id as idsur,nmjabfung.id as idjf, nmjabfung.nip,nmjabfung.nm,nmjabfung.glrd,nmjabfung.glrb,kabupaten.nama as nmkab, jb.nama as nmjb, jabatan.nmjbt as nmahli, golongan.nmpangkat as nmpg, golongan.gol as gg, nil.id as idnil , nil.nilai , nil.tgl_nilai, nil.tglin,nil.tglot,nil.ajkd FROM nil 
			LEFT JOIN surat
            ON nil.id_sur = surat.id
            LEFT JOIN nmjabfung
			ON surat.nip=nmjabfung.id
			LEFT JOIN jabatan
			ON nil.jbt_nilai=jabatan.id
			LEFT JOIN kabupaten
			ON nmjabfung.kab=kabupaten.id_kab
			LEFT JOIN jb
			ON nmjabfung.jbt=jb.id
			LEFT JOIN golongan
			ON nmjabfung.gol=golongan.id
			where jb.id=?
			 ORDER BY nil.id DESC LIMIT $jum";
			$query =  $this->db->query($q,array($jab));
		}elseif ($jab==0 && $kab != 0) {
			$q="SELECT surat.id as idsur,nmjabfung.id as idjf, nmjabfung.nip,nmjabfung.nm,nmjabfung.glrd,nmjabfung.glrb,kabupaten.nama as nmkab, jb.nama as nmjb, jabatan.nmjbt as nmahli, golongan.nmpangkat as nmpg, golongan.gol as gg, nil.id as idnil , nil.nilai , nil.tgl_nilai, nil.tglin,nil.tglot,nil.ajkd FROM nil 
			LEFT JOIN surat
            ON nil.id_sur = surat.id
            LEFT JOIN nmjabfung
			ON surat.nip=nmjabfung.id
			LEFT JOIN jabatan
			ON nil.jbt_nilai=jabatan.id
			LEFT JOIN kabupaten
			ON nmjabfung.kab=kabupaten.id_kab
			LEFT JOIN jb
			ON nmjabfung.jbt=jb.id
			LEFT JOIN golongan
			ON nmjabfung.gol=golongan.id
			where kabupaten.id=?
			 ORDER BY nil.id DESC LIMIT $jum";
			$query =  $this->db->query($q,array($kab));
		}elseif ($kab!= 0 && $kab !=0){
			$q="SELECT surat.id as idsur,nmjabfung.id as idjf, nmjabfung.nip,nmjabfung.nm,nmjabfung.glrd,nmjabfung.glrb,kabupaten.nama as nmkab, jb.nama as nmjb, jabatan.nmjbt as nmahli, golongan.nmpangkat as nmpg, golongan.gol as gg, nil.id as idnil , nil.nilai , nil.tgl_nilai, nil.tglin,nil.tglot,nil.ajkd FROM nil 
			LEFT JOIN surat
            ON nil.id_sur = surat.id
            LEFT JOIN nmjabfung
			ON surat.nip=nmjabfung.id
			LEFT JOIN jabatan
			ON nil.jbt_nilai=jabatan.id
			LEFT JOIN kabupaten
			ON nmjabfung.kab=kabupaten.id_kab
			LEFT JOIN jb
			ON nmjabfung.jbt=jb.id
			LEFT JOIN golongan
			ON nmjabfung.gol=golongan.id
			where kabupaten.id=? AND jb.id=?
			ORDER BY nil.id DESC LIMIT $jum";
			$query =  $this->db->query($q,array($kab,$jab ));			
		}else{
			$query =  $this->db->query("SELECT surat.id as idsur,nmjabfung.id as idjf, nmjabfung.nip,nmjabfung.nm,nmjabfung.glrd,nmjabfung.glrb,kabupaten.nama as nmkab, jb.nama as nmjb, jabatan.nmjbt as nmahli, golongan.nmpangkat as nmpg, golongan.gol as gg, nil.id as idnil , nil.nilai , nil.tgl_nilai, nil.tglin,nil.tglot,nil.ajkd FROM nil
			LEFT JOIN surat
            ON nil.id_sur = surat.id
            LEFT JOIN nmjabfung
			ON surat.nip=nmjabfung.id
			LEFT JOIN jabatan
			ON nil.jbt_nilai=jabatan.id
			LEFT JOIN kabupaten
			ON nmjabfung.kab=kabupaten.id_kab
			LEFT JOIN jb
			ON nmjabfung.jbt=jb.id
			LEFT JOIN golongan
			ON nmjabfung.gol=golongan.id
			 ORDER BY nil.id DESC LIMIT $jum");
		}
		return $query;	
	}
	function t_nil_per($data){
		$jum=$data['jum'];
		$kab=$data['kab'];
		$jab=$data['jab'];

		$b=explode("-", $data['tglin']);
		$c=$b[1]."-".$b[0];

		$d=explode("-", $data['tglot']);
		$e=$d[1]."-".$d[0];
		$tglin=$c;
		$tglot=$e;
		
		if ($kab==0 && $jab !=0) {
			$q="SELECT surat.id as idsur,nmjabfung.id as idjf, nmjabfung.nip,nmjabfung.nm,nmjabfung.glrd,nmjabfung.glrb,kabupaten.nama as nmkab, jb.nama as nmjb, jabatan.nmjbt as nmahli, golongan.nmpangkat as nmpg, golongan.gol as gg, nil.id as idnil , nil.nilai , nil.tgl_nilai, nil.tglin,nil.tglot,nil.ajkd FROM nil 
			LEFT JOIN surat
            ON nil.id_sur = surat.id
            LEFT JOIN nmjabfung
			ON surat.nip=nmjabfung.id
			LEFT JOIN jabatan
			ON nil.jbt_nilai=jabatan.id
			LEFT JOIN kabupaten
			ON nmjabfung.kab=kabupaten.id_kab
			LEFT JOIN jb
			ON nmjabfung.jbt=jb.id AND jb.id=?
			LEFT JOIN golongan
			ON nmjabfung.gol=golongan.id
			WHERE nil.tglin BETWEEN '$tglin' AND '$tglot' OR nil.tglot BETWEEN '$tglin' AND '$tglot' 
			 ORDER BY nil.id DESC LIMIT $jum";
			$query =  $this->db->query($q,array($jab));
		}elseif ($jab==0 && $kab != 0) {
			$q="SELECT surat.id as idsur,nmjabfung.id as idjf, nmjabfung.nip,nmjabfung.nm,nmjabfung.glrd,nmjabfung.glrb,kabupaten.nama as nmkab, jb.nama as nmjb, jabatan.nmjbt as nmahli, golongan.nmpangkat as nmpg, golongan.gol as gg, nil.id as idnil , nil.nilai , nil.tgl_nilai, nil.tglin,nil.tglot,nil.ajkd FROM nil 
			LEFT JOIN surat
            ON nil.id_sur = surat.id
            LEFT JOIN nmjabfung
			ON surat.nip=nmjabfung.id
			LEFT JOIN jabatan
			ON nil.jbt_nilai=jabatan.id
			LEFT JOIN kabupaten
			ON nmjabfung.kab=kabupaten.id_kab AND kabupaten.id=?
			LEFT JOIN jb
			ON nmjabfung.jbt=jb.id
			LEFT JOIN golongan
			ON nmjabfung.gol=golongan.id
			WHERE nil.tglin BETWEEN '$tglin' AND '$tglot' OR nil.tglot BETWEEN '$tglin' AND '$tglot' 
			 ORDER BY nil.id DESC LIMIT $jum";
			$query =  $this->db->query($q,array($kab));
		}elseif ($kab!= 0 && $kab !=0){
			$q="SELECT surat.id as idsur,nmjabfung.id as idjf, nmjabfung.nip,nmjabfung.nm,nmjabfung.glrd,nmjabfung.glrb,kabupaten.nama as nmkab, jb.nama as nmjb, jabatan.nmjbt as nmahli, golongan.nmpangkat as nmpg, golongan.gol as gg, nil.id as idnil , nil.nilai , nil.tgl_nilai, nil.tglin,nil.tglot,nil.ajkd FROM nil 
			LEFT JOIN surat
            ON nil.id_sur = surat.id
            LEFT JOIN nmjabfung
			ON surat.nip=nmjabfung.id
			LEFT JOIN jabatan
			ON nil.jbt_nilai=jabatan.id
			LEFT JOIN kabupaten
			ON nmjabfung.kab=kabupaten.id_kab AND kabupaten.id=?
			LEFT JOIN jb
			ON nmjabfung.jbt=jb.id AND jb.id=?
			LEFT JOIN golongan
			ON nmjabfung.gol=golongan.id
			WHERE nil.tglin BETWEEN '$tglin' AND '$tglot' OR nil.tglot BETWEEN '$tglin' AND '$tglot' 
			ORDER BY nil.id DESC LIMIT $jum";
			$query =  $this->db->query($q,array($kab,$jab ));			
		}else{
			$query =  $this->db->query("SELECT surat.id as idsur,nmjabfung.id as idjf, nmjabfung.nip,nmjabfung.nm,nmjabfung.glrd,nmjabfung.glrb,kabupaten.nama as nmkab, jb.nama as nmjb, jabatan.nmjbt as nmahli, golongan.nmpangkat as nmpg, golongan.gol as gg, nil.id as idnil , nil.nilai , nil.tgl_nilai, nil.tglin,nil.tglot,nil.ajkd FROM nil
			LEFT JOIN surat
            ON nil.id_sur = surat.id
            LEFT JOIN nmjabfung
			ON surat.nip=nmjabfung.id
			LEFT JOIN jabatan
			ON nil.jbt_nilai=jabatan.id
			LEFT JOIN kabupaten
			ON nmjabfung.kab=kabupaten.id_kab
			LEFT JOIN jb
			ON nmjabfung.jbt=jb.id
			LEFT JOIN golongan
			ON nmjabfung.gol=golongan.id
			WHERE nil.tglin BETWEEN '$tglin' AND '$tglot' OR nil.tglot BETWEEN '$tglin' AND '$tglot'
			 ORDER BY nil.id DESC LIMIT $jum");
		}
		return $query;	
	}
	function get_surat($id){
		$query ='SELECT s.*, k.nama as nmkab, j.nama as nmjb, j.id as jbt, n.nm, n.nip as nippeg,n.jbt FROM surat s, kabupaten k, jb j, nmjabfung n WHERE n.jbt=j.id AND n.kab = k.id_kab AND s.nip=n.id AND n.jbt IN ? ORDER BY s.id DESC LIMIT 100 ';
		return $quer=$this->db->query($query, array($id));	
	}
	function get_surat_id($id){

		$query = "SELECT s.*, k.nama as nmkab, j.nama as nmjb,j.id as idjft, n.nm, n.nip as nippeg, c.ck0,c.ck1,c.ck2,c.ck3,c.ck4,c.ck5,c.ck6,c.ck7,c.ck8,c.ck9,c.ck10,c.ck11  FROM surat s
				LEFT JOIN nmjabfung n 
				ON s.nip=n.id
				INNER JOIN kabupaten k
				ON n.kab = k.id_kab
				INNER JOIN jb j
				ON n.jbt=j.id
				LEFT JOIN ck_ajuan c
				ON s.id = c.n_id
				WHERE s.id=? LIMIT 1";
		$quer=$this->db->query($query, array($id));
		return $quer->result();	
	}
	function get_ajn_sr_byid($id){

		$query = "SELECT s.*, k.nama as nmkab, j.nama as nmjb,n.nm, n.nip as nippeg  FROM surat s
				LEFT JOIN nmjabfung n 
				ON s.nip=n.id
				INNER JOIN kabupaten k
				ON n.kab = k.id_kab
				INNER JOIN jb j
				ON n.jbt=j.id
				WHERE s.nip=? AND s.st_trm=1";
		$quer=$this->db->query($query, array($id));
		return $quer->result();	
	}
	function get_ajn_sr_byid_nst($id){

		$query = "SELECT s.*, k.nama as nmkab, j.nama as nmjb,n.nm, n.nip as nippeg  FROM surat s
				LEFT JOIN nmjabfung n 
				ON s.nip=n.id
				INNER JOIN kabupaten k
				ON n.kab = k.id_kab
				INNER JOIN jb j
				ON n.jbt=j.id
				WHERE s.nip=?";
		$quer=$this->db->query($query, array($id));
		return $quer->result();	
	}
	function get_cetak_ex($data){
		
		if ($data['exdata']==00){
			$dt = array(1,2,3,4);
		}else{
   			$dt = array($data['exdata']);
		}

		if ($data['exstatus']==00) {
		  	$dtst = array(0,1,2,3);
		}else{
   			$dtst = array($data['exstatus']);
		}

		
		if (count($data['exkabko'])==1 && $data['exkabko'][0]==00) {
			$q="SELECT s.*, k.nama as nmkab, j.nama as nmjb,n.*,c.*,g.* 
				FROM surat s
				LEFT JOIN nmjabfung n
				ON s.nip=n.id
				INNER JOIN golongan g
				ON n.gol = g.id
				INNER JOIN kabupaten k
				ON n.kab = k.id_kab
				INNER JOIN jb j
				ON n.jbt=j.id
				INNER JOIN ck_ajuan c
				ON s.id = c.n_id
				WHERE n.jbt IN ? AND s.st_trm IN ?
				ORDER BY s.id DESC";
			$has=$this->db->query($q, array($dt,$dtst));
		}else{
			$q="SELECT s.*, k.nama as nmkab, j.nama as nmjb,n.*,c.*,g.* 
				FROM surat s
				LEFT JOIN nmjabfung n
				ON s.nip=n.id
				INNER JOIN golongan g
				ON n.gol = g.id
				INNER JOIN kabupaten k
				ON n.kab = k.id_kab
				INNER JOIN jb j
				ON n.jbt=j.id
				INNER JOIN ck_ajuan c
				ON s.id = c.n_id
				WHERE n.jbt IN ? AND s.st_trm IN ? AND n.kab IN ?
				ORDER BY s.id DESC";
			$has=$this->db->query($q, array($dt,$dtst,$data['exkabko']));
		}
		return $has;
	}
	function get_cetak_ex_nm($data){
		
/*		bila ada pilihan fungsional bisa pakai ini
		if ($data['exdata']==00){
			$dt = array(1,2,3,4);
		}else{
   			$dt = array($data['exdata']);
		}*/
		$dt=$data['jah'];

		if ($data['exkabko'][0] == 00){
			$q="SELECT d.*, k.nama as nmkab, j.nama as nmjb, p.pend as pend, g.nmpangkat as nmpg ,g.gol as gg, n.nmjbt as nmahli, n.sortir  FROM nmjabfung d
				INNER JOIN jb j
				ON d.jbt=j.id
				INNER JOIN kabupaten k
				ON d.kab=k.id_kab 
				INNER JOIN pend p
				ON d.pdk = p.id
				INNER JOIN golongan g
				ON d.gol = g.id
				INNER JOIN jabatan n
				ON d.pjbt=n.id 
				WHERE d.jbt IN ? 
				ORDER BY j.nama";
				$has=$this->db->query($q, array($dt));
		}else{
			$arr = $data['exkabko'];
   			$q="SELECT d.*, k.nama as nmkab, j.nama as nmjb, p.pend as pend, g.nmpangkat as nmpg ,g.gol as gg, n.nmjbt as nmahli, n.sortir  FROM nmjabfung d
				INNER JOIN jb j
				ON d.jbt=j.id
				INNER JOIN kabupaten k
				ON d.kab=k.id_kab 
				INNER JOIN pend p
				ON d.pdk = p.id
				INNER JOIN golongan g
				ON d.gol = g.id
				INNER JOIN jabatan n
				ON d.pjbt=n.id 
				WHERE d.jbt IN ? AND d.kab IN ?
				ORDER BY j.nama";
				$has=$this->db->query($q, array($dt,$arr));

		}
		return $has;
	}
	function get_ajn_sr(){

		$query = "SELECT s.*, k.nama as nmkab, j.nama as nmjb,n.nm, n.nip as nippeg  FROM surat s
				LEFT JOIN nmjabfung n 
				ON s.nip=n.id
				INNER JOIN kabupaten k
				ON n.kab = k.id_kab
				INNER JOIN jb j
				ON n.jbt=j.id
				WHERE  s.st_trm !=0";
		$quer=$this->db->query($query);
		return $quer;	
	}
	function get_nmjabfungall($ja){
		$q =  'SELECT d.*, k.nama as nmkab, j.nama as nmjb, p.pend as pend, g.nmpangkat as nmpg ,g.gol as gg, n.nmjbt as nmahli, n.sortir  FROM nmjabfung d
					LEFT JOIN jb j
					ON d.jbt=j.id
					LEFT JOIN kabupaten k
					ON d.kab=k.id_kab
					LEFT JOIN pend p
					ON d.pdk = p.id
					LEFT JOIN golongan g
					ON d.gol = g.id
					LEFT JOIN jabatan n
					ON d.pjbt=n.id
					WHERE d.jbt IN ?
					ORDER BY d.id DESC LIMIT 100';
		return $this->db->query($q,array($ja));
			
	}
	function get_nmjabfungallnil(){
		$query =  $this->db->query('SELECT surat.id as idsur,nmjabfung.id as idjf, nmjabfung.nip,nmjabfung.nm,nmjabfung.glrd,nmjabfung.glrb,kabupaten.nama as nmkab, jb.nama as nmjb, jabatan.nmjbt as nmahli, golongan.nmpangkat as nmpg, golongan.gol as gg, nil.id as idnil , nil.nilai , nil.tgl_nilai, nil.tglin,nil.tglot,nil.ajkd FROM nil 
			LEFT JOIN surat
            ON nil.id_sur = surat.id
            LEFT JOIN nmjabfung
			ON surat.nip=nmjabfung.id
			LEFT JOIN jabatan
			ON nil.jbt_nilai=jabatan.id
			LEFT JOIN kabupaten
			ON nmjabfung.kab=kabupaten.id_kab
			LEFT JOIN jb
			ON nmjabfung.jbt=jb.id
			LEFT JOIN golongan
			ON nmjabfung.gol=golongan.id
			
			 ORDER BY nmjabfung.id DESC LIMIT 100');
		return $query;	
	}
	function get_nmjabfungallnil_byid($id){
		$query =  'SELECT surat.id as idsur,nmjabfung.id as idjf, nmjabfung.nip,nmjabfung.nm,nmjabfung.glrd,nmjabfung.glrb,kabupaten.nama as nmkab, jb.nama as nmjb, jabatan.nmjbt as nmahli, golongan.nmpangkat as nmpg, golongan.gol as gg, nil.id as idnil , nil.nilai , nil.tgl_nilai, nil.tglin,nil.tglot,nil.jbt_nilai, nil.nilai2, nil.nil_akhir, nil.nil_tug_utm, nil.nil_penj FROM nil 
			LEFT JOIN surat
            ON nil.id_sur = surat.id
            LEFT JOIN nmjabfung
			ON surat.nip=nmjabfung.id
			LEFT JOIN jabatan
			ON nil.jbt_nilai=jabatan.id
			LEFT JOIN kabupaten
			ON nmjabfung.kab=kabupaten.id_kab
			LEFT JOIN jb
			ON nmjabfung.jbt=jb.id
			LEFT JOIN golongan
			ON nmjabfung.gol=golongan.id
			WHERE nil.id=?
			 ORDER BY nmjabfung.id DESC LIMIT 1';
		
		return $this->db->query($query, array($id));	
	}
	function get_nmjabfung_not_nil(){
		$query =  $this->db->query('SELECT DISTINCT(nmjabfung.id),nmjabfung.nip, nmjabfung.glrd, nmjabfung.nm, nmjabfung.glrb, nmjabfung.pjbt, kabupaten.nama as nmkab, jabatan.nmjbt as nmahli FROM nmjabfung
			INNER JOIN surat
			ON nmjabfung.id=surat.nip
			LEFT JOIN nil
			ON surat.id=nil.id_sur
			LEFT JOIN kabupaten
			ON nmjabfung.kab=kabupaten.id_kab
			LEFT JOIN jabatan
			ON nmjabfung.pjbt=jabatan.id
			WHERE nil.nilai IS null AND surat.st_trm=1
			 ORDER BY nmjabfung.id');
		return $query;	
	}
	function get_nmjabfung_id($id){

		$query = "SELECT * FROM nmjabfung WHERE id=? LIMIT 1";
		$quer=$this->db->query($query, array($id));
		return $quer->result();	
	}
	function get_nmjabfung_id_all($id){

		$query = "SELECT d.*, k.nama as nmkab, j.nama as nmjb, p.pend as pend, g.nmpangkat as nmpg ,g.gol as gg, n.nmjbt as nmahli, n.sortir  FROM nmjabfung d, kabupaten k, jb j, pend p,golongan g, jabatan n WHERE d.jbt=j.id AND d.kab = k.id_kab AND d.pdk = p.id AND d.gol = g.id AND d.pjbt=n.id AND d.id=? LIMIT 1";
		$quer=$this->db->query($query, array($id));
		return $quer->result();	
	}
	function get_admin(){
		$d="4dm1n_sekre";
		$kg=base64_encode($d);
		$this->db->select('admin.*');
		$this->db->from('admin');
		$this->db->like('tipe', $kg);
		$query = $this->db->get();
		return $query;	
	}
	function get_admin_id($id){
		$inn = array($id);
		$query = "SELECT * FROM admin WHERE id= ? ";
		$quer=$this->db->query($query,$inn);
		return $quer->result();	
	}
	function get_rinci_ck_byid($id){
		$inn = array($id);
		$query = "SELECT * FROM ck_ajuan WHERE n_id= ? ";
		$quer=$this->db->query($query,$inn);
		return $quer->result();	
	}
	
	function get_sub_pjbt($id){
		$inn = array($id);
		$query = "SELECT * FROM jabatan WHERE sortir >= ? ";
		$quer=$this->db->query($query,$inn);
		return $quer->result();	

	}
	
	function s_surat($data){
		if ($data['jns_jbt']==1) {
			$temp=$data['ni1-1']."-".$data['ni1-2']."-".$data['ni1-3']."-".$data['nil2']."-".$data['nil3'];			
		}elseif($data['jns_jbt']==2){
			$temp=$data['ni1-1']."-".$data['ni1-2']."-".$data['ni1-3']."-".$data['ni1-4']."-".$data['ni1-5'];			
		}elseif($data['jns_jbt']==3){
			$temp=$data['ni1-1']."-".$data['ni1-2']."-".$data['ni1-3']."-".$data['ni1-4']."-".$data['ni1-5']."-".$data['ni1-6']."-".$data['ni1-7'];			
		}elseif($data['jns_jbt']==4){
			$temp=$data['ni1-1']."-".$data['ni1-2']."-".$data['ni1-3']."-".$data['ni1-4']."-".$data['ni1-5']."-".$data['ni1-6'];			
		}
		
		$dft= base64_encode($temp);

		$b=explode("-", $data['tglpein']);
		$c=$b[1]."-".$b[0];

		$d=explode("-", $data['tglpeot']);
		$e=$d[1]."-".$d[0];

		$inn = array(
			'tgl' => $data['tgl'], 
			'tglin' => $c, 
			'tglot' => $e, 
			'nosur' => $data['nosur'], 
			'nip' => $data['nip'], 
			'link' => $data['link'], 
			'aj_akd' => $dft, 
			'ket' => $data['ket'] 
		);

		$this->db->insert('surat',$inn);
		$in=$this->db->insert_id();

		for ($i=0; $i<12; $i++) { 
			if (isset($data['ck'][$i])) {
				$fkj['ck'.$i]=$data['ck'][$i];
			}
		}

		$fkj['n_id']=$in;
		$elp=$this->db->insert('ck_ajuan',$fkj);
		if ($elp) {
			return $in;
		}

	}
	function s_nilai($data){
		$nnn1=$this->encryption->encrypt(base64_encode($data['nilai1']));
		$nnn2=$this->encryption->encrypt(base64_encode($data['nilai2']));
		$nnn3=$this->encryption->encrypt(base64_encode($data['nilai3']));
		$nnnu=$this->encryption->encrypt(base64_encode($data['nilaiu']));
		$nnnp=$this->encryption->encrypt(base64_encode($data['nilaip']));
		$inn = array(
			'tgl_nilai' => $data['tgl'],  
			'jbt_nilai' => $data['jbt'], 
			'nilai' => $nnn1,  
			'nilai2' => $nnn2,  
			'nil_akhir' => $nnn3,  
			'nil_tug_utm' => $nnnu,  
			'nil_penj' => $nnnp,  
		);

		$this->db->set($inn);
		$this->db->where('id_sur',$data['ajn']);
		$in=$this->db->update('nil');




		$df = array('st_trm' =>2,'st_vw' =>0 );
		
		$this->db->set($df);
		$this->db->where('id',$data['ajn']);
		$ind=$this->db->update('surat');
		if ($in AND $df) {
			
			$isn = array($data['ajn']);
			$qu = "SELECT id FROM nil WHERE id_sur=?";
			$quer=$this->db->query($qu,$isn);
			return $quer->result();;
		}else{
			return FALSE;
		}
	}
	function s_e_nilai($data){
		$nnn1=$this->encryption->encrypt(base64_encode($data['nilai1']));
		$nnn2=$this->encryption->encrypt(base64_encode($data['nilai2']));
		$nnn3=$this->encryption->encrypt(base64_encode($data['nilai3']));
		$nnnu=$this->encryption->encrypt(base64_encode($data['nilaiu']));
		$nnnp=$this->encryption->encrypt(base64_encode($data['nilaip']));
		$inn = array(
			'tgl_nilai' => $data['tgl'],  
			'jbt_nilai' => $data['jbt'], 
			'nilai' => $nnn1,  
			'nilai2' => $nnn2,  
			'nil_akhir' => $nnn3,  
			'nil_tug_utm' => $nnnu,  
			'nil_penj' => $nnnp
		);

		$this->db->set($inn);
		$this->db->where('id',$data['idd']);
		$in=$this->db->update('nil');
		return $in;

	}
	function s_nmjabfung($data){
		$inn = array(
			'nip' => str_replace(" ","",$data['nip']), 
			'nm' => $data['nm'], //nama
			'glrd' => $data['glrd'], //gelar depan
			'glrb' => $data['glrb'], //gelar belakang
			'jankel' => $data['jankel'], //jenis kelamin
			'tmplhr' => $data['tmplhr'], //tempat lahir
			'tgllhr' => $data['tgllhr'], //tempat lahir
			'jbt' => $data['jbt'], //jenis jabatan fungsional
			'pjbt' => $data['pjbt'], //jenis jabatan fungsional
			'kab' => $data['kabkot'], //kab kota instansi
			'almt' => $data['almt'], //kab kota instansi
			'nohp' => $data['nohp'], //email jabfung
			'email' => $data['email'], //email jabfung
			'untkjr' => $data['untkjr'], //email jabfung
			'pdk' => $data['pend'], //email jabfung
			'jurpdk' => $data['jurpen'], //Jurusan Pendidikan
			'nokerpeg' => $data['nokarpeg'], //Nomor Kartu Pegawai
			'gol' => $data['gol'], //Pangkat / Golongan
			'tmtgol' => $data['tmtgol'], //Nomor Kartu Pegawai
			'mskerthn' => $data['tglmasker'], //Masa Kerja Tanggal CPNS
			'nosk' => $data['nosk'], //Nomor SK pengangkatan	
		);

		$in=$this->db->insert('nmjabfung',$inn);
		$hk=$this->db->insert_id();
		$gh=FALSE;
		if ($in==TRUE) {
			$ps=md5($data['nohp']);
			$nwpas=sha1($ps);
			$af = array(
				'idd' => $hk,
				'pas' => $nwpas,
			 );
			$gh=$this->db->insert('uspas',$af);
			# code...
		}
		return $gh;
	}
	/*function s_admin($data){
		//print_r($data['fungsi']);
		$p=sha1($data['pass']);$temp=null;
		
		for ($i=0; $i <count($data['fungsi']) ; $i++) { 
			$temp=$temp."-".$data['fungsi'][$i];
		}
		

		$hk=base64_encode($data['nm']."_penjajah_".$data['hk']."_fungsi_".$temp);
		$inn = array(
			'id_nm' => $data['id_nama'], 
			'nm' => $data['nm'], 
			'hk' => $hk, 
			'pass' => $p,
			'tipe' => base64_encode('4dm1n_sekre'),
			'st' => 1 
		);

		$in=$this->db->insert('admin',$inn);
		return $in;
	}*/
	function s_terbit($data){
		
		$hk=base64_encode($data['link']);
		$inn = array(
			'link' => $hk
		);

		$this->db->set($inn);
		$this->db->where('id_sur',$data['idsur']);
		$in=$this->db->update('nil');

		$innt = array(
			'st_trm' => 4,
			'st_vw' => 0
		);

		$this->db->set($innt);
		$this->db->where('id',$data['idsur']);
		$in=$this->db->update('surat');
		return $in;

	}
	function s_e_surat($data){
		
		if ($data['idjft']==1) {
			$temp=$data['ni1-1']."-".$data['ni1-2']."-".$data['ni1-3']."-".$data['nil2']."-".$data['nil3'];			
		}
		elseif ($data['idjft']==2) {
			$temp=$data['ni1-1']."-".$data['ni1-2']."-".$data['ni1-3']."-".$data['ni1-4']."-".$data['ni1-5'];			
			
		}
		elseif ($data['idjft']==3) {
			$temp=$data['ni1-1']."-".$data['ni1-2']."-".$data['ni1-3']."-".$data['ni1-4']."-".$data['ni1-5']."-".$data['ni1-6']."-".$data['ni1-7'];			
			
		}elseif ($data['idjft']==4) {
			$temp=$data['ni1-1']."-".$data['ni1-2']."-".$data['ni1-3']."-".$data['ni1-4']."-".$data['ni1-5']."-".$data['ni1-6'];			
			
		}
		
		$dft= base64_encode($temp);
		$b=explode("-", $data['tglpein']);
		$c=$b[1]."-".$b[0];

		$d=explode("-", $data['tglpeot']);
		$e=$d[1]."-".$d[0];

		$inn = array(
			'tgl' => $data['tgl'], 
			'tglin' => $c, 
			'tglot' => $e, 
			'nosur' => $data['nosur'], 
			'nip' => $data['nip'], 
			'link' => $data['link'], 
			'aj_akd' => $dft, 
			'ket' => $data['ket'] 
		);

		$this->db->set($inn);
		$this->db->where('id',$data['id']);
		$in=$this->db->update('surat');

		for ($i=0; $i<12; $i++) { 
			if (isset($data['ck'][$i])) {
				$fkj['ck'.$i]=$data['ck'][$i];
			}else{
				$fkj['ck'.$i]=0;
			}
		}
		
		$this->db->set($fkj);
		$this->db->where('n_id',$data['id']);
		$ina=$this->db->update('ck_ajuan');
		

		$ink = array(
			'tglin' => $c, 
			'tglot' => $e, 
			 
			'ajkd' => $dft 
		);
		$this->db->set($ink);
		$this->db->where('id_sur',$data['id']);
		$inp=$this->db->update('nil');

		if ($in AND $ina AND $inp) {
			
		return TRUE;
		}
		else{
			return FALSE;
		}

	}
	function s_e_nmjabfung($data){
		$inn = array(
			'nip' => str_replace(" ","",$data['nip']), 
			'nm' => $data['nm'], //nama
			'glrd' => $data['glrd'], //gelar depan
			'glrb' => $data['glrb'], //gelar belakang
			'jankel' => $data['jankel'], //jenis kelamin
			'tmplhr' => $data['tmplhr'], //tempat lahir
			'tgllhr' => $data['tgllhr'], //tempat lahir
			'pjbt' => $data['pjbt'], //jenis jabatan fungsional
			'jbt' => $data['jbt'], //jenis jabatan fungsional
			'kab' => $data['kabkot'], //kab kota instansi
			'almt' => $data['almt'], //kab kota instansi
			'nohp' => $data['nohp'], //email jabfung
			'email' => $data['email'], //email jabfung
			'untkjr' => $data['untkjr'],
			'pdk' => $data['pend'], //email jabfung
			'jurpdk' => $data['jurpen'], //Jurusan Pendidikan
			'nokerpeg' => $data['nokarpeg'], //Nomor Kartu Pegawai
			'gol' => $data['gol'], //Pangkat / Golongan
			'tmtgol' => $data['tmtgol'], //Nomor Kartu Pegawai
			'mskerthn' => $data['tglmasker'], //JADI TANGGAL CPNS
			'nosk' => $data['nosk'], //Nomor SK pengangkatan	 
		);
		
		$this->db->set($inn);
		$this->db->where('id',$data['id']);
		$in=$this->db->update('nmjabfung');
		return $in;
	}
	function s_e_admina($data){
		$tx="_penjajah_";
		$temp=null;
		for ($i=0; $i <count($data['fungsi']) ; $i++) { 
			$temp=$temp."-".$data['fungsi'][$i];
		}
		$hx=$data['nm'].$tx.$data['hk']."_fungsi_".$temp;
		if ($data['pss'] != NULL) {
			$p=sha1($data['pss']);
			$inn = array(
				'id_nm' => $data['id_nama'], 
				'nm' => $data['nm'], 
				'hk' => base64_encode($hx), 
				'pass' => $p, 
				 
			);
		}else{
			$inn = array(
				'id_nm' => $data['id_nama'], 
				'nm' => $data['nm'], 
				'hk' => base64_encode($hx), 
				 
			);			
		}
		$this->db->set($inn);
		$this->db->where('id',$data['id']);
		$in=$this->db->update('admin');
		return $in;
	}
	function m_del_surat($id,$nip)
	{	
		$dt=array('id' => $id);
		$in=$this->db->delete('surat', $dt);

		$dt=array('id_sur' => $id);
		$in=$this->db->delete('nil', $dt);

		$dt=array('n_id' => $id);
		$in=$this->db->delete('ck_ajuan', $dt);


		$path="./docu/".str_replace(array(' '),array('_'),$nip).".pdf";
		if ($in) {
			return unlink($path);
		}
		return $in;
	}
	function m_ubah_status_surat($id,$nip)
	{	
		$stat = array("0" => "1","1" =>"2","4" =>"2", );
		$dt=array(
			'st_trm' => $stat[$nip],
			'st_vw' => 0
			);
		$this->db->set($dt);
		$this->db->where('id',$id);
		$in=$this->db->update('surat');
		if ($in && $nip==4) {
			$t=array(
			'nilai' => NULL,
			'link' => NULL
			);
		$this->db->set($t);
		$this->db->where('id_sur',$id);
		$in=$this->db->update('nil');
		}
		
		return $in;
	}
	
	function m_del_nmjabfung($id,$nip)
	{	
		$stat = array("1" => "0","0" =>"1" );
		$dt=array(
			'st' => $stat[$nip]
			);
		$this->db->set($dt);
		$this->db->where('id',$id);
		$in=$this->db->update('nmjabfung');

		return $in;
	}
	function m_del_nil($data)
	{	

		$dt=array(
			'nilai' => NULL,
			'jbt_nilai' => 0,
			'tgl_nilai' => NULL
			);
		$this->db->set($dt);
		$this->db->where('id',$data['idnil']);
		$in=$this->db->update('nil');
		
		$d=array('st_trm' => 1);
		$this->db->set($d);
		$this->db->where('id',$data['idsur']);
		$on=$this->db->update('surat');
		if ($in && $on) {
			return TRUE;
		}else{
			return FALSE;
		}
	}
	function m_del_admin($id)
	{	
		$dt=array('id' => $id);
		$in=$this->db->delete('admin', $dt);
		return $in;
	}
	function m_ubah_status_admin($data){
		$gan  = array(1=>0,0=>1);
		$inn = array(
			'st' => $gan[$data['st']] 
		);
		
		$this->db->set($inn);
		$this->db->where('id',$data['id']);
		$in=$this->db->update('admin');
		return $in;
	}
	function update_pass($id,$npad){
		$inn = array(
			'pass' => $npad  
		);

		$this->db->set($inn);
		$this->db->where('id',$id);
		$in=$this->db->update('admin');
		return $in;
	}
	function update_pass_user($id,$npad){
		$inn = array(
			'pas' => $npad  
		);

		$this->db->set($inn);
		$this->db->where('idd',$id);
		$in=$this->db->update('uspas');
		return $in;
	}
	function get_cetak_nil($id){
		$this->db->where_in('idsur', $id);
		$query = $this->db->get('cetak');
		return $query;	
	}
}
