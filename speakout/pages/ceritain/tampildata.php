<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_koma.php";
include "../../inc/fungsi_tanggal.php";

$cari = mysqli_real_escape_string($conn, $_GET['cari'] ?? '');
$kategori = $_GET['kategori'] ?? '';
$status = $_GET['status'] ?? '';
$noPage = (int)$_GET['nopage'] ?? 5;

$dataPerPage = $noPage;
$noPage = max(1, (int)($_GET['page'] ?? 1));
$offset = ($noPage - 1) * $dataPerPage;

$text 	= "SELECT a.kodesiswa,a.kodecerita,a.tglajuan,a.kategori,b.nama AS namasiswa,d.nama AS namaguru,a.descr,a.kategori,a.metode,a.topik,CONCAT(b.kelas,' ',c.nama,' ',b.indeks) AS kelas,a.status FROM cerita a LEFT JOIN siswa b ON a.kodesiswa=b.kodesiswa LEFT JOIN jurusan c 
			ON b.kodejurusan=c.kodejurusan LEFT JOIN guru d ON a.kodeguru=d.kodeguru WHERE a.onview=1 ";
			
if(!empty($cari)) {
	$text .= "AND (b.nama LIKE '%$cari%' OR d.nama LIKE '%$cari%' ) ";
}
if(!empty($kategori)) {
	$text .= " AND a.kategori='$kategori' ";
}
if(!empty($status)) {
	$text .= " AND a.status='$status' ";
}

$text .= "ORDER BY a.tglentry DESC, a.tglajuan DESC LIMIT $offset, $dataPerPage";
$sql 	= mysqli_query($conn,$text);    
$jmlrec = mysqli_num_rows($sql);	

echo " 
	<table class='table table-hover'>
		<thead>
			<tr>
				<th style='width: 10px;' class='third-bg text-center align-middle'>No</th>	
				<th style='width: 120px;' class='third-bg text-center align-middle'>Tanggal</th>
				<th class='third-bg text-center align-middle'>Siswa</th>			
				<th class='third-bg text-center align-middle'>Kategori</th>
				<th class='third-bg text-center align-middle'>Guru</th>		
				<th class='third-bg text-center align-middle'>Status</th>
				<th style='width: 210px;' class='third-bg text-center align-middle'>Aksi</th>
			</tr>
		</thead>
		<tbody>";		
	
$no = 1 + $offset;
while ($rec = mysqli_fetch_array($sql)) {
	$txt = "SELECT kodeguru,nama AS namaguru FROM guru WHERE kodeguru = '{$rec['kodesiswa']}'";
	$sql1 = mysqli_query($conn,$txt);
	$rec1 = mysqli_fetch_array($sql1);

	$namasiswa = substr($rec['kodesiswa'], 0, 4) == "SISW" ? $rec['namasiswa'] : $rec1['namaguru'];

	$kategoriMap = [
		'1' => 'Konsultasi',
		'2' => 'Konseling',
		'3' => 'Coaching'
	];
	$kategori = $kategoriMap[$rec['kategori']] ?? '-';

	$topikMap = [
		'1' => 'Pribadi',
		'2' => 'Belajar',
		'3' => 'Sosial',
		'4' => 'Karir'
	];
	$topik = isset($topikMap[$rec['topik']]) ? $topikMap[$rec['topik']] : '-';

	$metode = ($rec['metode']==1) ? "Chat" : "Temu";
    $guru = isset($rec['namaguru']) ? ucwords(strtolower($rec['namaguru'])) : '';
	$color = $rec['status'] == 1 ? "primary-subtle" : "success-subtle px-3";
	$status = $rec['status'] == 1 ? "Diproses" : "Selesai";
	
	echo "
		<tr>
			<td class='text-center'>$no.</td>  
			<td class='text-center'>{$rec['tglajuan']}</td>               	
			<td class='text-center'>$namasiswa</td>
			<td class='text-center'>$kategori $metode ($topik)</td>
			<td class='text-center'>{$guru}</td>
			<td class='text-center'>
				<button type='button' class='btn btn-sm bg-$color'>$status</button>
			</td>
			<td class='text-center'>";
			if ($rec['status'] == 1) {
			echo "
				<a class='text-decoration-none' href='javascript:void(0)' onClick=\"selesai('{$rec['kodecerita']}')\">Selesai</a> &nbsp;&nbsp;| &nbsp;";
			}
			echo "	
				<a class='text-decoration-none' href='javascript:void(0)' onClick=\"detail('{$rec['kodecerita']}')\">Detail</a> &nbsp;&nbsp;| &nbsp;
				<a class='text-decoration-none' href='javascript:void(0)' onClick=\"del('{$rec['kodecerita']}')\">Hapus</a>
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
			</tr>";	
		$no++;						
	}								
}

echo " </tbody>
	</table>";
?>
