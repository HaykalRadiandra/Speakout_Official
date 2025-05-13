<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";
include "../../inc/fungsi_hdt.php";

$kodeuser = $_SESSION['kodeuser'];
$userid   = $_SESSION['namauser'];
$role   = $_SESSION['role'];

$text	= "SELECT nama,nip,alamat,notelp FROM guru WHERE kodeguru='$kodeuser'";

$sql 	= mysqli_query($conn,$text);
$rec 	= mysqli_fetch_array($sql);

$nama		= $rec['nama'];
$nip 		= $rec['nip'];
$alamat 	= $rec['alamat'];
$notelp		= $rec['notelp'];
$username	= $userid;


$data['nama'] = $nama;
$data['nip'] = $nip;
$data['alamat'] = $alamat;
$data['notelp'] = $notelp;
$data['username'] = $username;
$data['role'] = $role;

echo json_encode($data);


	
?>