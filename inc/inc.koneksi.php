<?php
$server = "";
$username = "";  
$password = "";
$database = "dbspeakout";

$conn = mysqli_connect($server, $username, $password, $database);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

?>
