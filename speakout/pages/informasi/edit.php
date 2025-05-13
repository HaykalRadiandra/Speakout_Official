<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";
include "../../inc/fungsi_hdt.php";

$kodeinformasi = $_POST['kodeinformasi'];

$text	= "SELECT kodeinformasi,judul,ket,foto FROM informasi WHERE kodeinformasi='$kodeinformasi'";

$sql 	= mysqli_query($conn,$text);
$rec 	= mysqli_fetch_array($sql);

$kodeinformasi	= $rec['kodeinformasi'];
$judul 	= $rec['judul'];
$ket 	= $rec['ket'];
$foto	= $rec['foto'];

$data['kodeinformasi'] = $kodeinformasi;
$data['judul'] = $judul;
$data['ket'] = $ket;
$data['foto'] = $foto;

echo json_encode($data);
	
?>