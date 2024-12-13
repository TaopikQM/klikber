<?php
function getAdmin($a){
	$gt=get_instance();
	
	/*echo "ini input a->";
	print_r($a);*/
	$q="SELECT a.username, b.n_bid as nm_bid FROM user a
			LEFT JOIN bidang b
			ON a.nama=b.kode 
			WHERE a.id=?";
		$query = $gt->db->query($q,$a)->row();
	return $query;
}
?>