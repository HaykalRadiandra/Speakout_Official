<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

$text	="SELECT kodepelanggaran,nama FROM jenispelanggaran WHERE onview=1 ORDER BY nama";	
$tampil	= mysqli_query($conn,$text);

echo "<option value='' selected>Jenis Pelanggaran</option>";
while($r = mysqli_fetch_array($tampil)){
	echo "<option value='$r[kodepelanggaran]'>$r[nama]</option>";		 
}
?>


