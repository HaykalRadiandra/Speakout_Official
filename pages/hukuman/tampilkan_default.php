<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

$username = $_SESSION['namauser'];

$text 	= "SELECT CONCAT('01-',DATE_FORMAT(CURRENT_DATE(),'%m-%Y')) AS tglawal, DATE_FORMAT(CURRENT_DATE(),'%d-%m-%Y') AS tglskg FROM DUAL";
$sql 	= mysqli_query($conn,$text);
$rec 	= mysqli_fetch_array($sql);

$data['tglawal']   = $rec['tglawal'];
$data['tglakhir']   = $rec['tglskg'];

echo json_encode($data);
	
?>      