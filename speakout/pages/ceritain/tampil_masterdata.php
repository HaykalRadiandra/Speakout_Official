<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_koma.php";
include "../../inc/fungsi_pembulatan.php";

$cari  = $_GET['cari'];
$tglnow = date("d-m-Y");

$username = $_SESSION['namauser'];
$kodeuser = $_SESSION['kodeuser'];

$dataPerPage = 5;
if(isset($_GET['page'])) {
    $noPage = $_GET['page'];
} else {
    $noPage = 1;
}

$offset = ($noPage - 1) * $dataPerPage;

$text = "SELECT kodeguru,nama,nip,notelp FROM guru WHERE onview=1 AND kodeguru != '$kodeuser'";

if (!empty($cari)) {
	$text .= "AND (nama LIKE '%$cari%' OR nip LIKE '%$cari%' ) ";
}

$text .= "ORDER BY nama LIMIT $offset, $dataPerPage";
$sql = mysqli_query($conn,$text);
$jmlrec = mysqli_num_rows($sql);

echo " 
    <table class='table table-hover'>
        <thead>
            <tr>
                <th style='width:30px;' class='third-bg text-center'>No</th>
                <th class='third-bg text-center'>Nama Guru</th>
                <th class='third-bg text-center'>NIP</th>
            </tr>
        </thead>
        <tbody>";

$no = 1 + $offset;

while($rec = mysqli_fetch_array($sql)) {

    echo "
            <tr>
                <td class='text-center'>$no.</td>
                <td>
                    <a class='text-decoration-none' href='javascript:void(0)' onClick=\"isi_cboguru('$rec[kodeguru]','$rec[nama]','$rec[notelp]','$tglnow')\">$rec[nama]</a>
                </td>
                <td class='text-center'>$rec[nip]</td>
            </tr>";

    $no++;
}

if ($jmlrec < 5) {
    while ($no <= 5) {
        echo "
            <tr>
                <td>$no.</td>						
                <td></td>
                <td></td>
            </tr>";
        $no++;
    }
}

echo "  </tbody>
    </table>";
?>
