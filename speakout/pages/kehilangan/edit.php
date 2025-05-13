<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";
include "../../inc/fungsi_hdt.php";

$kodekehilangan = $_POST['kodekehilangan'];

$text	= "SELECT kodekehilangan,ket,foto FROM kehilangan WHERE kodekehilangan='$kodekehilangan'";

$sql 	= mysqli_query($conn,$text);
$rec 	= mysqli_fetch_array($sql);

$kodekehilangan	= $rec['kodekehilangan'];
$ket 	= $rec['ket'];
$foto	= $rec['foto'];

$data['kodekehilangan'] = $kodekehilangan;
$data['ket'] = $ket;
$data['foto'] = $foto;

echo json_encode($data);
	
?>