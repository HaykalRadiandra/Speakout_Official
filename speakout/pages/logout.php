<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "inc/inc.koneksi.php";
include "inc/fungsi_hdt.php";

$sql = "UPDATE userapp SET online=0,lastlogin=NOW() WHERE username='$_SESSION[namauser]'";
mysqli_query($conn,$sql);

session_destroy();
header("location:index.html");

?>