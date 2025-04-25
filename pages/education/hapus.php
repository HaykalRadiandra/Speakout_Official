<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

$username = $_SESSION['namauser'];
$kodeguru  = $_POST['kodeguru'];
 
$hapus = "UPDATE guru SET onview=0,tglupdate=SYSDATE(),userupdate='$username' WHERE kodeguru='$kodeguru'";
mysqli_query($conn,$hapus);

$text = "SELECT COUNT(*) AS ada FROM guru WHERE kodeguru='$kodeguru' AND onview=0";
$sql = mysqli_query($conn,$text);
$rec = mysqli_fetch_array($sql);
$ada = $rec['ada'];

$hapus2 = "UPDATE userapp SET online=0,onview=0,tglupdate=SYSDATE(),userupdate='$username' WHERE kodeuser='$kodeguru'";
mysqli_query($conn,$hapus2);

$text2 = "SELECT COUNT(*) AS ada FROM userapp WHERE kodeuser='$kodeguru' AND onview=0";
$sql2 = mysqli_query($conn,$text2);
$rec2 = mysqli_fetch_array($sql2);
$ada2 = $rec2['ada'];

if($ada>0 && $ada2>0){
    $pesan = "<h4>Hapus data Sukses.</h4>";
}else{
    $pesan = "<h4>Hapus data Gagal.</h4>";
}

$data['result'] = $ada;
$data['pesan'] = $pesan;
echo json_encode($data);
?>
