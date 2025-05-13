<?php
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";

// format filter tanggal default= hari pertama bulan ini sampai hari saat ini
// contoh: misal sekarang tanggal 25 juli 2024, default: 01-07-2024 sampai 25-07-2024
$text	= "SELECT DATE_FORMAT(MIN(datefield),'%d-%m-%Y') AS tglsatu,DATE_FORMAT(MAX(datefield),'%d-%m-%Y') AS tgltrx 
		FROM calendar WHERE DATE_FORMAT(datefield,'%Y%m')=DATE_FORMAT(CURRENT_DATE(),'%Y%m') AND datefield<=CURRENT_DATE()";
$sql 	= mysqli_query($conn,$text);
$r		= mysqli_fetch_array($sql);

$data['tgltrx']	= $r['tgltrx'];
$data['tglsatu'] = $r['tglsatu'];
echo json_encode($data);	

	
?>