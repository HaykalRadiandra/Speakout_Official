<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

$username = $_SESSION[namauser];

$upd	= "UPDATE userapp SET lastupdate=SYSDATE() WHERE username='$username'";
mysql_query($upd);
	
?>