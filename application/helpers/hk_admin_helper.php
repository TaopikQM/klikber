<?php
function hk_admin($id){
	$gt=get_instance();
    $gt->db->where('idus', base64_encode($id));
    $data = $gt->db->get("hk_apli")-> result();

    foreach ($data as $key) {
		$hk=$gt->encryption->decrypt($key->hk);
		//$hk=$key->hk;
	}
	//print_r($hk);
	$fd=explode("_",$hk);
		if ($fd[1]==0) {
			$hz="Super Admin";
		}else{
			$hz="Admin";

		}
	echo $hz;
}
function getNmSPT($a,$th){
	$gt=get_instance();
	$tabel="pegawai".$th;
	/*echo "ini input a->";
	print_r($a);*/
	$q="SELECT p.*, j.nama_jabatan as jabatan, g.golru as golongan FROM $tabel p
			LEFT JOIN jabatan j
			ON p.jabatan=j.id 
			LEFT JOIN golruang g
			ON p.gol = g.id 
			WHERE p.id IN ?";
		$query =  $gt->db->query($q,array($a))->result();
	return $query;
}
function getNmTTD($a){
	$gt=get_instance();
	$gt->db->where('id', $a);
    $data = $gt->db->get("pejabat")->result();
    return $data;
}
?>