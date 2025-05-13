<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_koma.php";
include "../../inc/fungsi_tanggal.php";

$cari = $_GET['cari'];

$dataPerPage = 10;
if (isset($_GET['page'])) {
    $noPage = $_GET['page'];
} else {
    $noPage = 1;    
}

$offset = ($noPage - 1) * $dataPerPage;

$text 	= "SELECT kodeguru,nama,nip,alamat,notelp FROM guru WHERE onview=1 ";
			
if(!empty($cari)) {
	$text .= "AND (nama LIKE '%$cari%' OR nip LIKE '%$cari%' OR alamat LIKE '%$cari%' OR notelp LIKE '%$cari%') ";
}


$text .= "ORDER BY nama LIMIT $offset, $dataPerPage";
$sql 	= mysqli_query($conn,$text);    
$jmlrec = mysqli_num_rows($sql);	

echo " 
	<table class='table table-bordered'>
		<tr style='background-color:#f9f9f9'>
			<th style='width:10px;vertical-align:middle;' class='text-center'>NO</th>	
			<th style='width:200px;vertical-align:middle;' class='text-center'>NAMA</th>
			<th style='width:50px;vertical-align:middle;' class='text-center'>NIP</th>
			<th style='vertical-align:middle;' class='text-center'>ALAMAT</th>			
			<th style='width:100px;vertical-align:middle;' class='text-center'>NO TELP</th>		
			<th style='width:100px;vertical-align:middle;' class='text-center'>AKSI</th>
		</tr>";		
	
$no = 1 + $offset;
while ($rec = mysqli_fetch_array($sql)) {	
    $nama = ucwords(strtolower($rec['nama']));
	
	echo "
		<tr>
			<td class='text-center'>$no.</td>  
			<td >$nama</td>               	
			<td class='text-center'>{$rec['nip']}</td>
			<td class='text-center'>{$rec['alamat']}</td>
			<td class='text-center'>{$rec['notelp']}</td>
			<td class='text-center'>
				<a href='javascript:void(0)' onClick=\"edit('{$rec['kodeguru']}')\">Edit</a> &nbsp;&nbsp;| &nbsp;
				<a href='javascript:void(0)' onClick=\"del('{$rec['kodeguru']}')\">Hapus</a>
			</td>
		</tr>";	
	
	$no++;						
}

if ($jmlrec < 5) {
	while ($no <= 5) {
		echo "
			<tr>
				<td style='color:#FFFFFF' class='text-center'>$no.</td>
				<td></td>
				<td></td>												
				<td></td>
				<td></td>
				<td></td>
			</tr>";	
		$no++;						
	}								
}

echo "
	</table>";
?>
