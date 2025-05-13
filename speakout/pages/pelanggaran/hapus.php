<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

$username = $_SESSION['namauser'];
$kodeaduan  = $_POST['kodeaduan'];

$result = 0;

$hapus	= "UPDATE pelanggaran SET onview=0,tglupdate=SYSDATE(),userupdate='$username' WHERE kodeaduan='$kodeaduan'";
mysqli_query($conn,$hapus);

$text	= "SELECT COUNT(*) AS ada FROM pelanggaran WHERE kodeaduan='$kodeaduan' AND onview=0";
$sql = mysqli_query($conn, $text);
$rec = mysqli_fetch_assoc($sql);
$ada = $rec['ada'];

if($ada==0){
	$pesan = "<h4>Hapus data Sukses.</h4>";
	$result = 1;
}else{
	$pesan = "<h4>Hapus data Gagal.</h4>";
}

$data['result'] = $result;
$data['pesan'] = $pesan;
echo json_encode($data);

?>