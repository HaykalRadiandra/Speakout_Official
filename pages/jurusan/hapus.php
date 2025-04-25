<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

$kodejurusan	= $_POST['kodejurusan'];
$username 	= $_SESSION['namauser'];

$del	= "UPDATE jurusan SET onview=0,tglupdate=SYSDATE(),userupdate='$username' WHERE kodejurusan='$kodejurusan'";
mysqli_query($conn,$del);

$text = "SELECT COUNT(*) AS ada FROM jurusan WHERE kodejurusan='$kodejurusan' AND onview=0";
$sql = mysqli_query($conn,$text);
$rec = mysqli_fetch_array($sql);
$ada = $rec['ada'];

if($ada > 0){
    $pesan = "<h4>Hapus data Sukses.</h4>";
}else{
    $pesan = "<h4>Hapus data Gagal.</h4>";
}

$data['pesan'] = $pesan;
echo json_encode($data);	
?>