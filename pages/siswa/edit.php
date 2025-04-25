<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";
include "../../inc/fungsi_hdt.php";

$kodesiswa = $_POST['kodesiswa'];

$text	= "SELECT a.kodesiswa,a.nama,a.nis,a.nisn,a.masasekolah,a.kelas,a.kodejurusan,a.indeks,a.alamat,a.notelp,a.tgllahir,b.username,b.password
			FROM siswa a LEFT JOIN userapp b ON b.kodeuser=a.kodesiswa WHERE a.kodesiswa='$kodesiswa' AND b.kodeuser='$kodesiswa'";

$sql 	= mysqli_query($conn,$text);
$rec 	= mysqli_fetch_array($sql);

$kodesiswa	= $rec['kodesiswa'];
$nama	= $rec['nama'];
$nis	= $rec['nis'];
$nisn	= $rec['nisn'];
$masasekolah 	= $rec['masasekolah'];
$kelas 	= $rec['kelas'];
$kodejurusan 	= $rec['kodejurusan'];
$indeks 		= $rec['indeks'];
$alamat 		= $rec['alamat'];
$notelp			= $rec['notelp'];
$tgllahir		= $rec['tgllahir'];
$username		= $rec['username'];
$password		= $rec['password'];


$data['kodesiswa'] = $kodesiswa;
$data['nama'] = $nama;
$data['nis'] = $nis;
$data['nisn'] = $nisn;
$data['masasekolah'] = $masasekolah;
$data['kelas'] = $kelas;
$data['kodejurusan'] = $kodejurusan;
$data['indeks'] = $indeks;
$data['alamat'] = $alamat;
$data['notelp'] = $notelp;
$data['tgllahir'] = jin_date_str($tgllahir);
$data['username'] = $username;
$data['password'] = $password;


echo json_encode($data);
	
?>