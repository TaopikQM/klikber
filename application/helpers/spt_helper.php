<?php
function namaSPT($id,$th){
	$tabel="pegawai".$th;
	$gt=get_instance();
    $gt->db->where('id', $id);
    $data = $gt->db->get($tabel)->row();
	return base64_decode($data->nama);
}
function getNamaSPT(){
	$tabel="pegawai".date('Y');
	$gt=get_instance();
    $data = $gt->db->get($tabel)->result();
	return $data;
}
?>