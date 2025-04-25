<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_koma.php";
include "../../inc/fungsi_tanggal.php";

$cari = $_GET['cari'];
$kelas = $_GET['kelas'];
$jurusan = $_GET['jurusan'];
$indeks = $_GET['indeks'];

$dataPerPage = 5;
if (isset($_GET['page'])) {
    $noPage = $_GET['page'];
} else {
    $noPage = 1;    
}

$offset = ($noPage - 1) * $dataPerPage;

$text 	= "SELECT a.kodesiswa,a.nama,a.nis,a.nisn,CONCAT(a.kelas,' ',b.nama,' ',a.indeks) AS kelas,a.alamat,a.notelp
FROM siswa a LEFT JOIN jurusan b ON b.kodejurusan=a.kodejurusan WHERE a.onview=1 ";
			
if(!empty($cari)) {
	$text .= "AND (a.nama LIKE '%$cari%' OR a.nis LIKE '%$cari%' OR a.nisn LIKE '%$cari%' OR a.alamat LIKE '%$cari%' OR a.notelp LIKE '%$cari%') ";
}

if(!empty($kelas)){
	$text 	= $text. "AND a.kelas=$kelas ";
}

if(!empty($jurusan)){
	$text 	= $text. "AND a.kodejurusan='$jurusan' ";
}

if(!empty($indeks)){
	$text 	= $text. "AND a.indeks=$indeks ";
}


$text .= "ORDER BY a.nama LIMIT $offset, $dataPerPage";
$sql 	= mysqli_query($conn,$text);    
$jmlrec = mysqli_num_rows($sql);	

echo " 
	<div class='mt-2 table-responsive p-0 rounded-3'>
		<table class='table table-hover'>
			<thead>
                <tr>
                    <th class='third-bg text-center'>No</th>
                    <th class='third-bg text-center'>Nama</th>
                    <th class='third-bg text-center'>Kelas</th>
                    <th class='third-bg text-center'>NIS</th>
                    <th class='third-bg text-center'>NISN</th>
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
					<td class='text-center'>{$rec['kelas']}</td>
					<td class='text-center'>{$rec['nis']}</td>
					<td class='text-center'>{$rec['nisn']}</td>
					<td >{$rec['alamat']}</td>
					<td class='text-center'>{$rec['notelp']}</td>
					<td class='text-center d-print-none'>
						<a class='text-decoration-none' href='javascript:void(0)' onClick=\"edit('{$rec['kodesiswa']}')\">Edit</a> &nbsp;&nbsp;| &nbsp;
						<a class='text-decoration-none' href='javascript:void(0)' onClick=\"del('{$rec['kodesiswa']}')\">Hapus</a>
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
