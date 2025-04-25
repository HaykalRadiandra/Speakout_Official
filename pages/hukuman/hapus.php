<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

$username = $_SESSION['namauser'];
$kodeaduan  = $_POST['kodeaduan'];
 
$hapus	= "UPDATE pelanggaran SET onview=0,tglupdate=SYSDATE(),userupdate='$username' WHERE kodeaduan='$kodeaduan'";
mysqli_query($conn,$hapus);

// $text	= "SELECT idkaryawan FROM karyawan WHERE idkaryawan='$idkaryawan'";
$text	= "SELECT COUNT(*) AS ada FROM pelanggaran WHERE kodeaduan='$kodeaduan' AND onview=0";
$sql 	= mysqli_query($conn,$text);
$jmlrec	= mysqli_num_rows($sql);
$ada	= $jmlrec['ada'];	

if($ada==0){
	$pesan = "<h4>Hapus data Sukses.</h4>";
}else{
	$pesan = "<h4>Hapus data Gagal.</h4>";
}

$data['result'] = $jmlrec;
$data['pesan'] = $pesan;
echo json_encode($data);

?>