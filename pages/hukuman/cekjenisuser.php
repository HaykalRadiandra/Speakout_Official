<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";
include "../../inc/fungsi_hdt.php";

$jenisuser 		= $_SESSION['jenisuser'];

$data['jenisuser'] = $jenisuser;

echo json_encode($data);
	
?>