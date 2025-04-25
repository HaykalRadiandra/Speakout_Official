<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";
include "../../inc/fungsi_hdt.php";

$username = $_SESSION['namauser'];
$kodeaduan = $_POST['kodeaduan'];

$text	= "UPDATE hukuman SET status=3,tglupdate=SYSDATE(),userupdate='$username' WHERE kodeaduan='$kodeaduan'";
mysqli_query($conn,$text);

$text	= "SELECT COUNT(*) AS ada FROM hukuman WHERE kodeaduan='$kodeaduan' AND status=3";
$sql = mysqli_query($conn, $text);
$rec = mysqli_fetch_array($sql);

if($rec['ada'] > 0){
        $result = 1;
}else{
        $result = 0;
}

$data['result'] = $result;

echo json_encode($data);
	
?>