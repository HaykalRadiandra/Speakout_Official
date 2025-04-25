<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

$username = $_SESSION['namauser'];
$kodekehilangan  = $_POST['kodekehilangan'];

$update	= "UPDATE kehilangan SET status=2,tglupdate=SYSDATE(),userupdate='$username' WHERE kodekehilangan='$kodekehilangan'";
mysqli_query($conn,$update);

$text	= "SELECT COUNT(*) AS ada FROM kehilangan WHERE kodekehilangan='$kodekehilangan' AND status=2";
$sql = mysqli_query($conn, $text);
$row = mysqli_fetch_assoc($sql);
$ada = $row['ada'];

if($ada == 1){
    $pesan = "<h4>Update Sukses.</h4>";
} else {
    $pesan = "<h4>Update Gagal.</h4>";
}

$data['result'] = $ada;
$data['pesan'] = $pesan;
echo json_encode($data);


?>