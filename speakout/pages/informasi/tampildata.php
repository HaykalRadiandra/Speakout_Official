<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_koma.php";
include "../../inc/fungsi_tanggal.php";

$cari = $_GET['cari'];

$dataPerPage = 5;
if (isset($_GET['page'])) {
    $noPage = $_GET['page'];
} else {
    $noPage = 1;    
}

$offset = ($noPage - 1) * $dataPerPage;

$text 	= "SELECT kodeinformasi,judul,ket,foto FROM informasi WHERE onview=1 ";
			
if(!empty($cari)) {
	$text .= "AND (judul LIKE '%$cari%' OR ket LIKE '%$cari%') ";
}

$text .= "ORDER BY tglupdate desc LIMIT $offset, $dataPerPage";
$sql 	= mysqli_query($conn,$text);    
$jmlrec = mysqli_num_rows($sql);	

echo " 
	<table class='table table-hover'>
		<thead>
			<tr>
				<th class='third-bg text-center'>No</th>
				<th class='third-bg text-center'>Judul</th>	
				<th class='third-bg text-center'>Keterangan</th>
				<th class='third-bg text-center'>Foto</th>
				<th class='third-bg text-center'>Aksi</th>
			</tr>
		</thead>
		<tbody>";		
	
$no = 1 + $offset;
while ($rec = mysqli_fetch_array($sql)) {	
	$foto = $rec['foto'];
	echo " 
		<tr>
			<td class='text-center'>$no.</td>  
			<td >{$rec['judul']}</td>
			<td >{$rec['ket']}</td>";
	if(!empty($foto)){
		echo "<td class='text-center' style='width:250px;'><img class='img-fluid object-fit-cover' src='img/informasi/$foto' ></td>"; 
	}else{
		echo "<td class='text-center'></td>"; 
	}
	echo"
			<td class='text-center'>
				<a class='text-decoration-none' href='javascript:void(0)' onClick=\"edit('{$rec['kodeinformasi']}')\">Edit</a> &nbsp;&nbsp;| &nbsp;
				<a class='text-decoration-none' href='javascript:void(0)' onClick=\"del('{$rec['kodeinformasi']}')\">Hapus</a>
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
			</tr>";	
		$no++;						
	}								
}

echo "
		</tbody>
	</table>";
?>
