<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_tanggal.php";

$username  	= $_POST['username'];
$nama = ucwords(strtolower($_POST['nama'])); // Mengubah awalan menjadi kapital dan sisanya kecil
$nip 		= $_POST['nip'];
$alamat		= $_POST['alamat'];
$notelp		= $_POST['notelp'];

$userid 	= $_SESSION['namauser'];
$kodeuser 	= $_SESSION['kodeuser'];
$sukses=0;

$text3	= "SELECT username FROM userapp WHERE username='$username' AND kodeuser!='$kodeuser' AND onview=1";
$sql3 	= mysqli_query($conn,$text3);
$row	= mysqli_num_rows($sql3);
if($row>0){
	$pesan= "<p><h4>Username sudah digunakan</h4></p>";
	$sukses=0;
}else{
	$update = "UPDATE guru SET nama='$nama',nip='$nip',alamat='$alamat',notelp='$notelp',
		tglupdate=SYSDATE(),userupdate='$userid' WHERE kodeguru='$kodeuser'";
	mysqli_query($conn,$update);

	$text	= "SELECT kodeguru FROM guru WHERE kodeguru='$kodeuser' AND nama='$nama' AND userupdate='$userid'";
	$sql 	= mysqli_query($conn,$text);
	$ada	= mysqli_num_rows($sql);	

	$update2 = "UPDATE userapp SET namalengkap='$nama',username='$username',
			tglupdate=SYSDATE(),userupdate='$userid' WHERE kodeuser='$kodeuser'";
	mysqli_query($conn,$update2);

	$text2	= "SELECT kodeuser FROM userapp WHERE kodeuser='$kodeuser' AND username='$username' AND userupdate='$userid'";
	$sql2 	= mysqli_query($conn,$text2);
	$ada2	= mysqli_num_rows($sql2);

	if($ada>0 && $ada2>0){
		$pesan= "<p><h4>Update Data Sukses</h4></p>".$update.$update2;
		$sukses=1;
	}else{
		$pesan= "<p><h4>Update Data Gagal</h4></p>".$update.$update2;
		$sukses=0;
	}	
}

$data['pesan'] = $pesan;
$data['sukses'] = $sukses;

echo json_encode($data);
?>
