<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_tanggal.php";

$cari = $_GET['cari'];
$tglawal = jin_date_sql($_GET['tglawal']);
$tglakhir =jin_date_sql($_GET['tglakhir']);
$kelas = $_GET['kelas'];
$jurusan = $_GET['jurusan'];
$indeks = $_GET['indeks'];
$jnspelanggaran = $_GET['jnspelanggaran'];
$status = $_GET['status'];

$username 	= $_SESSION['namauser'];
$kodeuser   = $_SESSION['kodeuser'];
$jenisuser  = $_SESSION['jenisuser'];


$dataPerPage = 5;
$noPage = max(1, (int)($_GET['page'] ?? 1));
$offset = ($noPage - 1) * $dataPerPage;

// Query jumlah data
$queryCount = "SELECT COUNT(*) AS jumData FROM pelanggaran a LEFT JOIN siswa b ON b.kodesiswa=a.kodeterlapor LEFT JOIN jurusan c ON c.kodejurusan=b.kodejurusan 
        LEFT JOIN jenispelanggaran d ON d.kodepelanggaran=a.kodepelanggaran WHERE a.onview=1 AND DATE_FORMAT(a.tglentry,'%Y-%m-%d')>='$tglawal' AND DATE_FORMAT(a.tglentry,'%Y-%m-%d')<='$tglakhir' ";

if ($jenisuser == 1) $queryCount .= "AND a.kodepelapor='$kodeuser' ";
if ($cari) $queryCount .= " AND (a.ket LIKE '%$cari%' OR b.nama LIKE '%$cari%' OR d.nama LIKE '%$cari%') ";
if ($kelas) $queryCount .= "AND b.kelas=$kelas ";
if ($jurusan) $queryCount .= "AND b.kodejurusan='$jurusan' ";
if ($indeks) $queryCount .= "AND b.indeks=$indeks ";
if ($jnspelanggaran) $queryCount .= "AND a.kodepelanggaran='$jnspelanggaran' ";
if ($status) $queryCount .= "AND a.status=$status ";

$result = mysqli_query($conn, $queryCount);
$jumData = mysqli_fetch_assoc($result)['jumData'];
$jumPage = max(1, ceil($jumData / $dataPerPage));

// Tampilkan pagination Bootstrap 5.3
echo "<nav><ul class='pagination justify-content-end'>";

// Tombol Previous
$prevDisabled = ($noPage == 1) ? "disabled" : "";
echo "<li class='page-item $prevDisabled'>
        <a class='page-link' href='javascript:void(0)' onClick=\"tampildata(" . ($noPage - 1) . ")\">&laquo; Previous</a>
      </li>";

// Halaman numerik
$range = 2;
for ($page = 1; $page <= $jumPage; $page++) {
    if ($page == 1 || $page == $jumPage || ($page >= $noPage - $range && $page <= $noPage + $range)) {
        if ($page == $noPage) {
            echo "<li class='page-item active'><a class='page-link' href='#'>$page</a></li>";
        } else {
            echo "<li class='page-item'><a class='page-link' href='javascript:void(0)' onClick=\"tampildata($page)\">$page</a></li>";
        }
    } elseif ($page == 2 || $page == $jumPage - 1) {
        echo "<li class='page-item disabled'><a class='page-link' href='#'>...</a></li>";
    }
}

// Tombol Next
$nextDisabled = ($noPage == $jumPage) ? "disabled" : "";
echo "<li class='page-item $nextDisabled'>
        <a class='page-link' href='javascript:void(0)' onClick=\"tampildata(" . ($noPage + 1) . ")\">Next &raquo;</a>
      </li>";

echo "</ul>
    </nav>";
?>
