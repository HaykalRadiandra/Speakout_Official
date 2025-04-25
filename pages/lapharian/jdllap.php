<?php
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_indotgl2.php";
include "../../inc/fungsi_tanggal.php";

// Judul laporan
// contoh: LAPORAN KAS DAN SETARA KAS Tgl. 01-01-2024 s/d 31-01-2024
//$perusahaan = $_SESSION['perusahaan'];
$tgltrx = $_POST['tgltrx'];
$tgltrx2 = $_POST['tgltrx2'];
	
	//$data['area']	= '-';
	//$data['alamat']	= 'Jl. ';
	$data['tgltrx']	= 'Tgl. '.$tgltrx.' s/d '.$tgltrx2;
	echo json_encode($data);	

?>