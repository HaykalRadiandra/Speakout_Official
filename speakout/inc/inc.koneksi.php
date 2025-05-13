<?php
$host = 'db';
$user = 'speakoutuser';
$pass = 'speakoutpass';
$dbname = 'dbspeakout';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

?>
