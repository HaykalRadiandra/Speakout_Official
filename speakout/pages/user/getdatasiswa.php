<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";
include "../../inc/fungsi_hdt.php";

$kodeuser = $_SESSION['kodeuser'];
$userid   = $_SESSION['namauser'];
$role   = $_SESSION['role'];

$text	= "SELECT a.nama,a.kelas,CONCAT(a.kelas,' ',c.nama,' ',a.indeks) AS kelaslengkap,c.nama AS kodejurusan,a.indeks,a.alamat,a.notelp,DATE_FORMAT(a.tgllahir,'%d-%m-%y') AS tgllahir
			FROM siswa a LEFT JOIN jurusan c ON c.kodejurusan=a.kodejurusan WHERE a.kodesiswa='$kodeuser'";

$sql 	= mysqli_query($conn,$text);
$rec 	= mysqli_fetch_array($sql);

$nama	= $rec['nama'];
$kelaslengkap 	= $rec['kelaslengkap'];
$kelas 	= $rec['kelas'];
$kodejurusan 	= $rec['kodejurusan'];
$indeks 		= $rec['indeks'];
$alamat 		= $rec['alamat'];
$notelp			= $rec['notelp'];
$tgllahir		= $rec['tgllahir'];
$username		= $userid;

$data['nama'] = $nama;
$data['kelaslengkap'] = $kelaslengkap;
$data['kelas'] = $kelas;
$data['kodejurusan'] = $kodejurusan;
$data['indeks'] = $indeks;
$data['alamat'] = $alamat;
$data['notelp'] = $notelp;
$data['tgllahir'] = $tgllahir;
$data['username'] = $username;
$data['role'] = $role;

echo json_encode($data);
	
?>