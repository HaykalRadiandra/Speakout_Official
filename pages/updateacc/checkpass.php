<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
$pass = md5($_POST['pass']);
$username  = $_SESSION['namauser'];
$kodeuser  = $_SESSION['kodeuser'];

$query = "SELECT password FROM userapp WHERE kodeuser='$kodeuser' AND username='$username' AND password='$pass'";
$sql = mysqli_query($conn,$query);
$row = mysqli_fetch_array($sql);

if ($row['password']==$pass) {
	echo 'ok';
}

 ?>