<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "inc.koneksi.php";
include "fungsi_hdt.php";

function anti_injection($conn, $data) {
    $filter = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($data), ENT_QUOTES));
    return $filter;
}

$username = anti_injection($conn, $_POST['userid']);
$pass = anti_injection($conn, md5($_POST['password']));
$jenis_akses = isset($_POST['jenisaksesxOptions']) ? anti_injection($conn, $_POST['jenisaksesxOptions']) : '';

// Validasi nilai jenis akses (hanya izinkan 1 atau 2)
if (!in_array($jenis_akses, ['1', '2'])) {
    header("Location: ../index.html");
    exit;
}

// Cek apakah input hanya huruf/angka
if (!ctype_alnum($username) OR !ctype_alnum($pass)){
	header("Location: ../index.html");
} else {
	$login = mysqli_query($conn, "SELECT * FROM userapp WHERE username='$username'");
	$ketemu = mysqli_num_rows($login);

	if ($ketemu > 0){
		$r = mysqli_fetch_array($login);
		$pwd = $r['password'];

		if ($r['onview'] == 0){
			salah_blokir($username);
			return false;
		}

		if ($pwd == $pass){
			// Reset jumlah salah login
			$_SESSION['salah'] = 0;
			sukses_masuk($username, $pass, $jenis_akses);
		} else {
			// Cek dulu kalau belum ada, inisialisasi
			if (!isset($_SESSION['salah'])) {
				$_SESSION['salah'] = 0;
			}
			$_SESSION['salah'] += 1;
			salah_password();
		}
	} else {
		salah_username($username);
	}
}
?>
