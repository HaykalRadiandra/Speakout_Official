<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

$username = $_SESSION['namauser'];
$kodeaduan  = $_POST['kodeaduan'];

$result = 0; // Tambahkan di awal sebelum if

$update	= "UPDATE pelanggaran SET status=2,tglupdate=SYSDATE(),userupdate='$username' WHERE kodeaduan='$kodeaduan'";
mysqli_query($conn,$update);

$text = "SELECT COUNT(*) AS ada FROM pelanggaran WHERE kodeaduan='$kodeaduan' AND status=1";
$sql = mysqli_query($conn, $text);
$rec = mysqli_fetch_assoc($sql);
$ada = $rec['ada'];

$text2	= "SELECT MAX(RIGHT(kodehukuman,4)) AS nourut FROM hukuman";
	$sql2 	= mysqli_query($conn,$text2);
	$row	= mysqli_num_rows($sql2);
	if($row>0){
		$rec = mysqli_fetch_array($sql2);
		$nourut = $rec['nourut']+1;
		if($nourut>9999){
			$nourut=1;
		}	 	
	}else{
		$nourut=1;		
	}

	$kodehukuman = "HKMN".sprintf("%04s",$nourut);


	$insert = "INSERT INTO hukuman(kodehukuman,kodeaduan,status,tglentry,userid,tglupdate,userupdate,onview)
				VALUES('$kodehukuman','$kodeaduan',1,SYSDATE(),'$username',SYSDATE(),'$username',1)";
	mysqli_query($conn,$insert);

	$text3	= "SELECT kodehukuman FROM hukuman WHERE kodehukuman='$kodehukuman'";
	$sql3 	= mysqli_query($conn,$text3);
	$ada3	= mysqli_num_rows($sql3);	

	if($ada==0 && $ada3>0){
		$pesan = "<h4>Setujui laporan pelanggaran Sukses.</h4>";
		$result = 1;
	}else{
		$pesan = "<h4>Setujui laporan pelanggaran Gagal. (ada=$ada, ada3=$ada3)</h4>";
		$result = 0; // tambahkan ini juga
	}
	

$data['result'] = $result;
$data['pesan'] = $pesan;
echo json_encode($data);

?>