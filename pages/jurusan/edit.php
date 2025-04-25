<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";
include "../../inc/fungsi_hdt.php";

$kodejurusan = $_POST['kodejurusan'];

$text 	= "SELECT kodejurusan,nama FROM jurusan WHERE kodejurusan='$kodejurusan'";

$sql 	= mysqli_query($conn,$text);
$rec 	= mysqli_fetch_array($sql);

$kodejurusan  = $rec['kodejurusan'];
$namajurusan  = $rec['nama'];

$data['kodejurusan'] = $kodejurusan;
$data['namajurusan'] = $namajurusan;

echo json_encode($data);
	
?>