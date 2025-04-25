<?php

function pembulatan_ratusan($kode) {
	$exp = explode(".", $kode);

	$c = substr($exp[0], (strlen($exp[0])-2), strlen($exp[0]));

	if($c<50) {
		$r = substr($exp[0], 0, (strlen($exp[0])-2)) * 100;
	}
	else {
		$r = (substr($exp[0], 0, (strlen($exp[0])-2))+1) * 100;
	}
	return $r;
}

?> 
