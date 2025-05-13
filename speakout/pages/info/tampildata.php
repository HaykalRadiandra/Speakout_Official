<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_koma.php";
include "../../inc/fungsi_tanggal.php";

$cbotahun = $_POST['cbotahun'];
$cbojurusan = $_POST['cbojurusan'];
$cbokelas = $_POST['cbokelas'];

$filter = "AND DATE_FORMAT(a.tglentry, '%Y') = '$cbotahun' ";
if (!empty($cbojurusan)) {
    $filter .= "AND b.kodejurusan = '$cbojurusan' ";
}
if (!empty($cbokelas)) {
    $filter .= "AND b.kelas = '$cbokelas' ";
}

$text = "SELECT
    -- Total aduan = pelanggaran + kehilangan
    COALESCE(p.Total_Bermasalah, 0) + COALESCE(k.Total_Hilang, 0) AS Total_Aduan,

    -- Status pelanggaran
    COALESCE(p.Menunggu_Disetujui, 0) AS Menunggu_Disetujui,
    COALESCE(p.Disetujui, 0) AS Disetujui,
    COALESCE(p.Ditolak, 0) AS Ditolak,

    -- Total hukuman
    COALESCE(b.Total_Hukuman, 0) AS Total_Hukuman,

    -- Total ceritain
    COALESCE(c.Total_Ceritain, 0) AS Total_Ceritain

FROM
(
    SELECT
        SUM(a.onview = 1) AS Total_Bermasalah,
        SUM(a.STATUS = 1) AS Menunggu_Disetujui,
        SUM(a.STATUS = 2) AS Disetujui,
        SUM(a.STATUS = 3) AS Ditolak
    FROM pelanggaran a
    LEFT JOIN siswa b ON b.kodesiswa = a.kodeterlapor
    LEFT JOIN jurusan c ON c.kodejurusan = b.kodejurusan
    LEFT JOIN jenispelanggaran d ON d.kodepelanggaran = a.kodepelanggaran
    WHERE a.onview = 1
        $filter
) AS p,

(
    SELECT
        COUNT(*) AS Total_Hilang
    FROM kehilangan a
    LEFT JOIN siswa b ON b.kodesiswa = a.kodepelapor
    LEFT JOIN jurusan c ON c.kodejurusan = b.kodejurusan
    LEFT JOIN guru d ON d.kodeguru = a.kodepelapor
    WHERE a.onview = 1
        $filter
) AS k,

(
    SELECT
        COUNT(*) AS Total_Hukuman
    FROM pelanggaran a
    LEFT JOIN siswa b ON b.kodesiswa = a.kodeterlapor
    LEFT JOIN jurusan c ON c.kodejurusan = b.kodejurusan
    LEFT JOIN jenispelanggaran d ON d.kodepelanggaran = a.kodepelanggaran
    LEFT JOIN hukuman e ON e.kodeaduan = a.kodeaduan
    WHERE a.onview = 1
        AND a.status = 2
        $filter
) AS b,
(
    SELECT 
        COUNT(*) AS Total_Ceritain
    FROM cerita  a
    LEFT JOIN siswa b ON b.kodesiswa = a.kodesiswa
    LEFT JOIN jurusan c ON c.kodejurusan = b.kodejurusan
    WHERE a.onview = 1 AND a.kodesiswa NOT LIKE 'GURU%'
        $filter
) AS c
";

$sql = mysqli_query($conn,$text);
$rec = mysqli_fetch_assoc($sql);

$data['totaladuan'] = $rec['Total_Aduan'];
$data['totalhukuman'] = $rec['Total_Hukuman'];
$data['totalceritain'] = $rec['Total_Ceritain'];
$data['menunggudisetujui'] = $rec['Menunggu_Disetujui'];
$data['ditolak'] = $rec['Ditolak'];
$data['disetujui'] = $rec['Disetujui'];

echo json_encode($data);
?>