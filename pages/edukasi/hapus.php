<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

$username = $_SESSION['namauser'];
$kodeedukasi  = $_POST['kodeedukasi'];
 
$hapus = "UPDATE edukasi SET onview=0,tglupdate=SYSDATE(),userupdate='$username' WHERE kodeedukasi='$kodeedukasi'";
mysqli_query($conn,$hapus);

$text = "SELECT COUNT(*) AS ada FROM edukasi WHERE kodeedukasi='$kodeedukasi' AND onview=0";
$sql = mysqli_query($conn,$text);
$rec = mysqli_fetch_array($sql);
$ada = $rec['ada'];

if($ada>0){
    $pesan = "<h4>Hapus data Sukses.</h4>";
}else{
    $pesan = "<h4>Hapus data Gagal.</h4>";
}

$data['result'] = $ada;
$data['pesan'] = $pesan;
echo json_encode($data);
?>
