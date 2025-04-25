<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";
include "../../inc/fungsi_hdt.php";

$kodeguru = $_POST['kodeguru'];

$text	= "SELECT a.kodeguru,a.nama,a.nip,a.alamat,a.notelp,b.username,b.password
			FROM guru a LEFT JOIN userapp b ON b.kodeuser=a.kodeguru WHERE a.kodeguru='$kodeguru' AND b.kodeuser='$kodeguru'";

$sql 	= mysqli_query($conn,$text);
$rec 	= mysqli_fetch_array($sql);

$kodeguru	= $rec['kodeguru'];
$nama		= $rec['nama'];
$nip 		= $rec['nip'];
$alamat 	= $rec['alamat'];
$notelp		= $rec['notelp'];
$username	= $rec['username'];
$password	= $rec['password'];


$data['kodeguru'] = $kodeguru;
$data['nama'] = $nama;
$data['nip'] = $nip;
$data['alamat'] = $alamat;
$data['notelp'] = $notelp;
$data['username'] = $username;
$data['password'] = $password;

echo json_encode($data);
	
?>