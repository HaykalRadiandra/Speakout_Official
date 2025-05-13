<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_koma.php";
include "../../inc/fungsi_tanggal.php";

$cari = $_GET['cari'];

$dataPerPage = 10;
$noPage = max(1, (int)($_GET['page'] ?? 1));
$offset = ($noPage - 1) * $dataPerPage;

$text 	= "SELECT kodeguru,nama,nip,alamat,notelp FROM guru WHERE onview=1 ";
			
if(!empty($cari)) {
	$text .= "AND (nama LIKE '%$cari%' OR nip LIKE '%$cari%' OR alamat LIKE '%$cari%' OR notelp LIKE '%$cari%') ";
}

$text .= "ORDER BY nama LIMIT $offset, $dataPerPage";
$sql 	= mysqli_query($conn,$text);    
$jmlrec = mysqli_num_rows($sql);	

echo " 
	<div class='mt-2 table-responsive p-0 rounded-3'>
		<table class='table table-hover'>
			<thead>
                <tr>
                    <th class='third-bg text-center'>No</th>
                    <th class='third-bg text-center'>Nama</th>
                    <th class='third-bg text-center'>NIP</th>
                    <th class='third-bg text-center'>Alamat</th>
                    <th class='third-bg text-center'>No Telp</th>
                    <th class='third-bg text-center d-print-none'>Aksi</th>
                </tr>
            </thead>
			<tbody>";	
	
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
			<td class='text-center d-print-none'>
				<a class='text-decoration-none' href='javascript:void(0)' onClick=\"edit('{$rec['kodeguru']}')\">Edit</a> &nbsp;&nbsp;| &nbsp;
				<a class='text-decoration-none' href='javascript:void(0)' onClick=\"del('{$rec['kodeguru']}')\">Hapus</a>
			</td>
		</tr>";	
	
	$no++;						
}

if ($jmlrec < 5) {
	while ($no <= 5) {
		echo "
				<tr>
					<td class='text-center'>$no.</td>
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
			</tbody>
		</table>
	</div>";
?>
