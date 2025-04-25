<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_tanggal.php";

$kodepelanggaran = $_POST['kodepelanggaran'];
$namapelanggaran = $_POST['namapelanggaran'];
$tingkat = $_POST['tingkat'];

$username 	= $_SESSION['namauser'];

if(empty($kodepelanggaran)){	
	
	$text	= "SELECT MAX(RIGHT(kodepelanggaran,3)) AS nourut FROM jenispelanggaran";
	$sql 	= mysqli_query($conn,$text);
	$row	= mysqli_num_rows($sql);
	if($row>0){
		$rec = mysqli_fetch_array($sql);
		$nourut = $rec['nourut']+1;
		if($nourut>999){
			$nourut=1;
		}	 	
	}else{
		$nourut=1;		
	}

	$kodepelanggaran = "PLGRN".sprintf("%03s",$nourut);

	$text3	= "SELECT nama FROM jenispelanggaran WHERE nama='$namapelanggaran' AND onview=1";
	$sql3 	= mysqli_query($conn,$text3);
	$row	= mysqli_num_rows($sql3);
	if($row>0){
		$pesan= "<p><h4>Jenis Pelanggaran sudah ada</h4></p>";
		$sukses=0;
	}else{
		$insert = "INSERT INTO jenispelanggaran(kodepelanggaran,nama,tingkat,onview,tglentry,userid,tglupdate,userupdate)
					VALUES('$kodepelanggaran','$namapelanggaran',$tingkat,1,SYSDATE(),'$username',SYSDATE(),'$username')";
		mysqli_query($conn,$insert);

		$text	= "SELECT kodepelanggaran FROM jenispelanggaran WHERE kodepelanggaran='$kodepelanggaran'";
		$sql 	= mysqli_query($conn,$text);
		$ada	= mysqli_num_rows($sql);	

		if($ada>0){
			$pesan="<p><h4>Simpan Data Sukses</h4></p>";
			$sukses=1;
		}else{
			$pesan="<p><h4>Simpan Data Gagal</h4></p>";
			$sukses=0;
		}	
	}
	
}else{
	$text3	= "SELECT nama FROM jenispelanggaran WHERE nama='$namapelanggaran' AND kodepelanggaran!='$kodepelanggaran' AND onview=1";
	$sql3 	= mysqli_query($conn,$text3);
	$row	= mysqli_num_rows($sql3);
	if($row>0){
		$pesan= "<p><h4>Jenis Pelanggaran sudah ada</h4></p>";
		$sukses=0;
	}else{
		$upd = "UPDATE jenispelanggaran SET nama='$namapelanggaran',tingkat=$tingkat,tglupdate= SYSDATE(),userupdate='$username' WHERE kodepelanggaran='$kodepelanggaran'";
		mysqli_query($conn,$upd);

		$text	= "SELECT kodepelanggaran FROM jenispelanggaran WHERE kodepelanggaran='$kodepelanggaran' AND nama='$namapelanggaran'";
		$sql 	= mysqli_query($conn,$text);
		$ada	= mysqli_num_rows($sql);

		if($ada>0){
			$pesan="<p><h4>Update Data Sukses</h4></p>";
			$sukses=1;
		}else{
			$pesan="<p><h4>Update Data Gagal</h4></p>";
			$sukses=0;
		}
	}
		
}

$data['pesan'] = $pesan;
$data['sukses'] = $sukses;

echo json_encode($data);

?>