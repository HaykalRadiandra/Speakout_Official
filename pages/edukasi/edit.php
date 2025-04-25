<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";
include "../../inc/fungsi_hdt.php";

$kodeedukasi = $_POST['kodeedukasi'];

$text	= "SELECT kodeedukasi,judul,ket,foto FROM edukasi WHERE kodeedukasi='$kodeedukasi'";

$sql 	= mysqli_query($conn,$text);
$rec 	= mysqli_fetch_array($sql);

$kodeedukasi	= $rec['kodeedukasi'];
$judul 	= $rec['judul'];
$ket 	= $rec['ket'];
$foto	= $rec['foto'];

$data['kodeedukasi'] = $kodeedukasi;
$data['judul'] = $judul;
$data['ket'] = $ket;
$data['foto'] = $foto;

echo json_encode($data);
	
?>