<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

$username = $_SESSION['namauser'];

$upd	= "UPDATE userapp SET online=0 WHERE username='$username'";
mysqli_query($conn,$upd);
	
?>