<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";
include "../../inc/fungsi_hdt.php";

$kodeaduan = $_POST['kodeaduan'];

$text	= "SELECT a.ket,a.kodepelanggaran AS jnspelanggaran,a.foto,b.nama AS namaterlapor,CONCAT(b.kelas,' ',c.nama,' ',b.indeks) AS kelas
        FROM pelanggaran a LEFT JOIN siswa b ON b.kodesiswa=a.kodeterlapor LEFT JOIN jurusan c ON c.kodejurusan=b.kodejurusan 
        WHERE a.kodeaduan='$kodeaduan'";
$sql 	= mysqli_query($conn,$text);
$rec 	= mysqli_fetch_array($sql);

$ket	= $rec['ket'];
$foto	= $rec['foto'];
$namaterlapor	= $rec['namaterlapor'];
$kelas 	= $rec['kelas'];
$jnspelanggaran 	= $rec['jnspelanggaran'];


$data['ket'] = $ket;
$data['foto'] = $foto;
$data['namaterlapor'] = $namaterlapor;
$data['kelas'] = $kelas;
$data['jnspelanggaran'] = $jnspelanggaran;

echo json_encode($data);
	
?>