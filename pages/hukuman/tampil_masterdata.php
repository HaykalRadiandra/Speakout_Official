<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_koma.php";
include "../../inc/fungsi_pembulatan.php";

$cari  = $_GET['cari'];
$kelas = $_GET['kelas'];
$jurusan = $_GET['jurusan'];
$indeks = $_GET['indeks'];

$username = $_SESSION['namauser'];
$kodeuser = $_SESSION['kodeuser'];

$dataPerPage = 5;
if(isset($_GET['page'])) {
    $noPage = $_GET['page'];
} else {
    $noPage = 1;
}

$offset = ($noPage - 1) * $dataPerPage;

$text = "SELECT a.kodesiswa,a.nama,CONCAT(a.kelas,' ',b.nama,' ',a.indeks) AS kelas
FROM siswa a LEFT JOIN jurusan b ON b.kodejurusan=a.kodejurusan WHERE a.onview=1 AND kodesiswa!='$kodeuser' ";

if (!empty($cari)) {
	$text .= "AND (a.nama LIKE '%$cari%' OR a.alamat LIKE '%$cari%' OR a.notelp LIKE '%$cari%') ";
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
$sql = mysqli_query($conn,$text);
$jmlrec = mysqli_num_rows($sql);

echo " 
    <table class='table table-bordered'>
        <tr style='background-color:#f9f9f9'>
            <th style='width:30px;vertical-align:middle;' class='text-center'>No</th>
            <th style='vertical-align:middle;' class='text-center'>Nama Siswa</th>
            <th style='vertical-align:middle;' class='text-center'>Kelas</th>
        </tr>";

$no = 1 + $offset;

while($rec = mysqli_fetch_array($sql)) {

    echo "
        <tr>
            <td class='text-center'>$no.</td>
            <td>
				<a href='javascript:void(0)' onClick=\"isi_cbosiswa('$rec[kodesiswa]','$rec[nama]','$rec[kelas]')\">$rec[nama]</a>
            </td>
            <td class='text-center'>$rec[kelas]</td>
        </tr>";

    $no++;
}

if ($jmlrec < 5) {
    while ($no <= 5) {
        echo "
            <tr>
                <td style='color:#FFFFFF'>$no.</td>						
                <td></td>
                <td></td>
            </tr>";
        $no++;
    }
}

echo "</table>";
?>
