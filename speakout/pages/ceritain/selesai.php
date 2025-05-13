<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

header('Content-Type: application/json'); // Biar response JSON dikenali

if (!isset($_SESSION['namauser']) || !isset($_POST['kodecerita'])) {
    $data['pesan'] = "Data tidak lengkap.";
    echo json_encode($data);
    exit;
}

$username = $_SESSION['namauser'];
$kodecerita = mysqli_real_escape_string($conn, $_POST['kodecerita']);

// Update status cerita
$update = "UPDATE cerita SET status=2, tglupdate=SYSDATE(), userupdate='$username' WHERE kodecerita='$kodecerita'";
mysqli_query($conn, $update);

// Cek apakah berhasil diupdate
$cek = "SELECT COUNT(*) AS ada FROM cerita WHERE kodecerita='$kodecerita' AND status=2";
$res = mysqli_query($conn, $cek);
$row = mysqli_fetch_assoc($res);

if ($row['ada'] > 0) {
    $data['result'] = 1;
    $data['pesan'] = "<h4>Cerita berhasil diselesaikan.</h4>";
} else {
	$data['result'] = 0;
	$data['pesan'] = "<h4>Cerita gagal diselesaikan.</h4>";
}

echo json_encode($data);
?>
