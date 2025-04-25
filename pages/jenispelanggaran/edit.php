<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";
include "../../inc/fungsi_hdt.php";

$kodepelanggaran = $_POST['kodepelanggaran'];

$text 	= "SELECT kodepelanggaran,nama,tingkat FROM jenispelanggaran WHERE kodepelanggaran='$kodepelanggaran'";

$sql 	= mysqli_query($conn,$text);
$rec 	= mysqli_fetch_array($sql);

$kodepelanggaran  = $rec['kodepelanggaran'];
$nama  = $rec['nama'];
$tingkat  = $rec['tingkat'];

$data['kodepelanggaran'] = $kodepelanggaran;
$data['nama'] = $nama;
$data['tingkat'] = $tingkat;

echo json_encode($data);
	
?>