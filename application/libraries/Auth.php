<?php
class auth{
	function filter($word) {
		$word = stripslashes(trim($word));
	  	$word = nl2br($word);
	  	$word = htmlentities($word);
	  	return $word ;
	}
}
?>