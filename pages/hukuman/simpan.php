<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_tanggal.php";

$kodehukuman = $_POST['kodehukuman'];
$kethukuman = $_POST['kethukuman'];
$result = 0;
$username 	= $_SESSION['namauser'];

$txt = "SELECT status FROM hukuman WHERE kodehukuman='$kodehukuman'";
$sql = mysqli_query($conn,$txt);
$rec = mysqli_fetch_array($sql);

if ($rec['status'] == 3) {
	$update = "UPDATE hukuman SET ket='$kethukuman',status=3,tglupdate=SYSDATE(),userupdate='$username' WHERE kodehukuman='$kodehukuman'";
	mysqli_query($conn,$update);
} else {
	$update = "UPDATE hukuman SET ket='$kethukuman',status=2,tglupdate=SYSDATE(),userupdate='$username' WHERE kodehukuman='$kodehukuman'";
	mysqli_query($conn,$update);
}

$text	= "SELECT kodehukuman FROM hukuman WHERE kodehukuman='$kodehukuman' AND userupdate='$username'";
$sql 	= mysqli_query($conn,$text);
$ada	= mysqli_num_rows($sql);	

if($ada > 0){
	$pesan="<p><h4>Simpan Data Sukses</h4></p>";
	$result = 1;
}else{
	$pesan="<p><h4>Simpan Data Gagal</h4></p>";
}	

$data['pesan'] = $pesan;
$data['result'] = $result;

echo json_encode($data);

?>