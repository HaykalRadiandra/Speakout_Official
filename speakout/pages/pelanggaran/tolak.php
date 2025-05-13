<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

$username = $_SESSION['namauser'];
$kodeaduan  = $_POST['kodeaduan'];

$result = 0;

$Tolak	= "UPDATE pelanggaran SET status=3,tglupdate=SYSDATE(),userupdate='$username' WHERE kodeaduan='$kodeaduan'";
mysqli_query($conn,$Tolak);

$text	= "SELECT COUNT(*) AS ada FROM pelanggaran WHERE kodeaduan='$kodeaduan' AND status=2";
$sql = mysqli_query($conn, $text);
$rec = mysqli_fetch_assoc($sql);
$ada = $rec['ada'];	

if($ada==0){
	$pesan = "<h4>Tolak laporan pelanggaran Sukses.</h4>";
	$result = 1;
}else{
	$pesan = "<h4>Tolak laporan pelanggaran Gagal.</h4>";
}

$data['result'] = $result;
$data['pesan'] = $pesan;
echo json_encode($data);

?>