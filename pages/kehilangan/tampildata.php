<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_koma.php";
include "../../inc/fungsi_tanggal.php";

$cari           = mysqli_real_escape_string($conn, $_GET['cari'] ?? '');
$tglawal        = mysqli_real_escape_string($conn, jin_date_sql($_GET['tglawal'])) ?? '';
$tglakhir       = mysqli_real_escape_string($conn, jin_date_sql($_GET['tglakhir'])) ?? '';
$kelas          = $_GET['kelas'] ?? '';
$jurusan        = $_GET['jurusan'] ?? '';
$indeks         = $_GET['indeks'] ?? '';
$filterstatus 	= $_GET['status'] ?? '';

$username 	= $_SESSION['namauser'];
$kodeuser   = $_SESSION['kodeuser'];
$jenisuser  = $_SESSION['jenisuser'];

$dataPerPage = 10;
if (isset($_GET['page'])) {
    $noPage = $_GET['page'];
} else {
    $noPage = 1;    
}

$offset = ($noPage - 1) * $dataPerPage;

$text 	= "SELECT a.kodekehilangan,a.kodepelapor,a.status,DATE_FORMAT(a.tglentry,'%d-%m-%Y') AS tglentry,b.nama AS namasiswa,d.nama AS namaguru,CONCAT(b.kelas,' ',c.nama,' ',b.indeks) AS kelas,a.ket,a.foto 
	FROM kehilangan a LEFT JOIN siswa b ON b.kodesiswa=a.kodepelapor LEFT JOIN jurusan c ON c.kodejurusan=b.kodejurusan LEFT JOIN guru d ON d.kodeguru=a.kodepelapor 
	WHERE a.onview=1 AND DATE_FORMAT(a.tglentry,'%Y-%m-%d')>='$tglawal' AND DATE_FORMAT(a.tglentry,'%Y-%m-%d')<='$tglakhir' ";
			
if(!empty($cari)) {
	$text .= " AND (a.ket LIKE '%$cari%' OR b.nama LIKE '%$cari%' OR c.nama LIKE '%$cari%' OR d.nama LIKE '%$cari%') ";
}

if(!empty($kelas)){
	$text 	= $text. "AND b.kelas=$kelas ";
}

if(!empty($jurusan)){
	$text 	= $text. "AND b.kodejurusan='$jurusan' ";
}

if(!empty($indeks)){
	$text 	= $text. "AND b.indeks=$indeks ";
}

if(!empty($filterstatus)){
	$text 	= $text. "AND a.status=$filterstatus ";
}

$text .= "ORDER BY a.tglentry desc LIMIT $offset, $dataPerPage";
$sql 	= mysqli_query($conn,$text);    
$jmlrec = mysqli_num_rows($sql);	

echo " 
	<table class='table table-hover'>
		<thead>
			<tr>
				<th style='width:10px;vertical-align:middle;' class='text-center third-bg'>NO</th>	
				<th style='width:100px;vertical-align:middle;' class='text-center third-bg'>TGL KEHILANGAN</th>
				<th style='width:150px;vertical-align:middle;' class='text-center third-bg'>NAMA PELAPOR</th>
				<th style='width:100px;vertical-align:middle;' class='text-center third-bg'>KELAS</th>
				<th style='vertical-align:middle;' class='text-center third-bg'>KETERANGAN</th>
				<th style='width:200px;vertical-align:middle;' class='text-center third-bg'>LAMPIRAN</th>
				<th style='width:100px;vertical-align:middle;' class='text-center third-bg'>STATUS</th>";
				if ($jenisuser == 9){
		echo "	<th style='width:100px;vertical-align:middle;' class='text-center third-bg'>AKSI</th>
				<th style='width:100px;vertical-align:middle;' class='text-center third-bg'>DITEMUKAN</th>";
				}
			echo "</tr>
		</thead>
		<tbody>";		
	
$no = 1 + $offset;
while ($rec = mysqli_fetch_array($sql)) {	
	$kodepelapor = $rec['kodepelapor'];
	$foto = $rec['foto'];
	$kelas = $rec['kelas'];

	// jika pelapor adalah guru
	if(substr($kodepelapor,0,4)=="GURU"){
		$nama = ucwords(strtolower($rec['namaguru']));
		$kelas = "Guru";
	// jika pelapor adalah siswa
	}elseif(substr($kodepelapor,0,4)=="SISW"){
		$nama = ucwords(strtolower($rec['namasiswa']));
	}

	$status = $rec['status'];
    if($status == 1){
        $statusnama = "Belum Ditemukan";
    }elseif($status == 2){
        $statusnama = "Sudah Ditemukan";
	}

	echo " 
		<tr>
			<td class='text-center'>$no.</td>  
			<td class='text-center'>$rec[tglentry]</td>  
			<td >$nama</td>
			<td class='text-center'>$kelas</td>    
			<td >$rec[ket]</td>";
			if(!empty($foto)){
	echo "	<td class='text-center'><img class='img-fluid object-fit-cover'  src='img/kehilangan/$foto' width='200'></td>"; 
			}else{
	echo "	<td class='text-center'></td>"; 
			}
			echo "<td class='text-center'>$statusnama</td>"; 
			// jika sudah belum ketemu bisa tekan sudah ketemu
		if ($jenisuser == 9){
			if($status==1){
				echo"
				<td class='text-center'>
					<a class='text-decoration-none' href='javascript:void(0)' onClick=\"edit('{$rec['kodekehilangan']}')\">Edit</a> &nbsp;&nbsp;| &nbsp;
					<a class='text-decoration-none' href='javascript:void(0)' onClick=\"del('{$rec['kodekehilangan']}')\">Hapus</a>
				</td>
				<td class='text-center'>
					<a class='text-decoration-none' href='javascript:void(0)' onClick=\"ketemu('{$rec['kodekehilangan']}')\">Sudah Ketemu</a>
				</td>                
				";
			}elseif($status==2){
				echo"
					<td class='text-center'>
						Edit&nbsp;&nbsp;| &nbsp;
						Hapus
					</td>
					<td class='text-center'>Sudah Ketemu</td>       
				";
			}
		}
	echo" 
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
				<td></td>";
		if($jenisuser == 9){
		echo "	<td></td>
				<td></td>";
				}
			echo "</tr>";	
		$no++;						
	}								
}

echo "	</tbody>
	</table>";
?>
