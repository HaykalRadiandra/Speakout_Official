<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_tanggal.php";

$kodejurusan = $_POST['kodejurusan'];
$namajurusan = $_POST['namajurusan'];

$username 	= $_SESSION['namauser'];

if(empty($kodejurusan)){	
	
	$text	= "SELECT MAX(RIGHT(kodejurusan,4)) AS nourut FROM jurusan";
	$sql 	= mysqli_query($conn,$text);
	$row	= mysqli_num_rows($sql);
	if($row>0){
		$rec = mysqli_fetch_array($sql);
		$nourut = $rec['nourut']+1;
		if($nourut>9999){
			$nourut=1;
		}	 	
	}else{
		$nourut=1;		
	}

	$kodejurusan = "JURU".sprintf("%04s",$nourut);

	$text3	= "SELECT nama FROM jurusan WHERE nama='$namajurusan' AND onview=1";
	$sql3 	= mysqli_query($conn,$text3);
	$row	= mysqli_num_rows($sql3);
	if($row>0){
		$pesan= "<p><h4>Jurusan sudah ada</h4></p>";
		$sukses=0;
	}else{
		$insert = "INSERT INTO jurusan(kodejurusan,nama,onview,tglentry,userid,tglupdate,userupdate)
					VALUES('$kodejurusan','$namajurusan',1,SYSDATE(),'$username',SYSDATE(),'$username')";
		mysqli_query($conn,$insert);

		$text	= "SELECT kodejurusan FROM jurusan WHERE kodejurusan='$kodejurusan'";
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
	$text3	= "SELECT nama FROM jurusan WHERE nama='$namajurusan' AND kodejurusan!='$kodejurusan' AND onview=1";
	$sql3 	= mysqli_query($conn,$text3);
	$row	= mysqli_num_rows($sql3);
	if($row>0){
		$pesan= "<p><h4>Jurusan sudah ada</h4></p>";
		$sukses=0;
	}else{
		$upd = "UPDATE jurusan SET nama='$namajurusan',tglupdate= SYSDATE(),userupdate='$username' WHERE kodejurusan='$kodejurusan'";
		mysqli_query($conn,$upd);

		$text	= "SELECT kodejurusan FROM jurusan WHERE kodejurusan='$kodejurusan' AND nama='$namajurusan'";
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