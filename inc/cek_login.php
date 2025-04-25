<?php
session_start();
include "inc.koneksi.php";
include "fungsi_hdt.php";

function anti_injection($conn, $data) {
    $filter = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($data), ENT_QUOTES));
    return $filter;
}

$username = anti_injection($conn, $_POST['userid']);
$pass = anti_injection($conn, md5($_POST['password']));

// Cek apakah input hanya huruf/angka
if (!ctype_alnum($username) OR !ctype_alnum($pass)){
?>
<script>
	//alert('Sekarang loginnya tidak bisa di injeksi lho.');
	//window.location.href='../index.html';
	//window.location.href='../media.php?mod=home';
</script>
<?php
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
			sukses_masuk($username, $pass);
		} else {
			// Cek dulu kalau belum ada, inisialisasi
			if (!isset($_SESSION['salah'])) {
				$_SESSION['salah'] = 0;
			}
			$_SESSION['salah'] += 1;

			/*
			// Kalau mau blokir setelah 3x salah, aktifkan ini:
			if ($_SESSION['salah'] >= 3) {
				blokir($username);
			}
			*/

			salah_password();
		}
	} else {
		salah_username($username);
	}
}
?>
