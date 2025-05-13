<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

$text	="SELECT kodejurusan,nama FROM jurusan WHERE onview=1 ORDER BY nama";	
$tampil	= mysqli_query($conn,$text);

echo "<option value='' selected>Pilih Jurusan</option>";
while($r = mysqli_fetch_array($tampil)){
	echo "<option value='$r[kodejurusan]'>$r[nama]</option>";		 
}
?>


