<?php

session_start();
error_reporting(0);
include "inc.koneksi.php";
include "fungsi_hdt.php";

$data['user'] = $_SESSION[namauser];
echo json_encode($data);

?>