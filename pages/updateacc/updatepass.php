<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

$oldpass = md5($_POST['oldpass']);
$newpass = md5($_POST['newpass']);
$repass = md5($_POST['repass']);

$username  = $_SESSION['namauser'];
$kodeuser  = $_SESSION['kodeuser'];

$query = "SELECT password FROM userapp WHERE kodeuser='$kodeuser' AND username='$username' AND password='$oldpass'";
$sql = mysqli_query($conn,$query);
$row = mysqli_fetch_array($sql);

$sukses=0;

if ($oldpass!=$row['password']) {
    $pesan= 'Update password gagal';
}elseif($newpass!=$repass){
    $pesan= 'Update password gagal';
}else{
    $query = "UPDATE userapp SET password='$newpass' WHERE kodeuser='$kodeuser' AND username='$username'";
    $sql = mysqli_query($conn,$query);

    if ($sql) {
        $pesan=  "Update password sukses";
        $sukses=1;
    } else {
        $pesan=  "Update password gagal";
        //debug
        // echo "Simpan data gagal: " . mysqli_error($conn);
    }

}

$data['sukses'] = $sukses;
$data['pesan'] = $pesan;

echo json_encode($data);

?>