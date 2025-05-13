<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_tanggal.php";

$kodeguru	= $_POST['kodeguru'];
$username  	= $_POST['username'];
$password  	= md5($_POST['password']);
$nama = ucwords(strtolower($_POST['nama'])); // Mengubah awalan menjadi kapital dan sisanya kecil
$nip 		= $_POST['nip'];
$alamat		= $_POST['alamat'];
$notelp		= $_POST['notelp'];

$userid 		= $_SESSION['namauser'];

$sukses=0;
if(empty($kodeguru)){

	$text	= "SELECT MAX(RIGHT(kodeguru,4)) AS nourut FROM guru";
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

	$kodeguru = "GURU".sprintf("%04s",$nourut);		
		

	$text3	= "SELECT username FROM userapp WHERE username='$username' AND onview=1";
	$sql3 	= mysqli_query($conn,$text3);
	$row	= mysqli_num_rows($sql3);
	if($row>0){
		$pesan= "<p><h4>Username sudah digunakan</h4></p>";
		$sukses=0;
	}else{
		$input = "INSERT INTO guru(kodeguru,nama,nip,alamat,notelp,tglentry,userid,tglupdate,userupdate,onview) 
    	      	  VALUES('$kodeguru','$nama','$nip','$alamat','$notelp',SYSDATE(),'$userid',SYSDATE(),'$userid',1)";
		mysqli_query($conn,$input);

		$text	= "SELECT kodeguru FROM guru WHERE kodeguru='$kodeguru' AND userid='$userid'";
		$sql 	= mysqli_query($conn,$text);
		$ada	= mysqli_num_rows($sql);	


		$input2 = "INSERT INTO userapp(namalengkap,username,password,jenisuser,kodeuser,tglentry,userid,tglupdate,userupdate,onview) 
    	      	  VALUES('$nama','$username','$password',9,'$kodeguru',SYSDATE(),'$userid',SYSDATE(),'$userid',1)";
		mysqli_query($conn,$input2);

		$text2	= "SELECT kodeuser FROM userapp WHERE kodeuser='$kodeguru' AND userid='$userid'";
		$sql2 	= mysqli_query($conn,$text2);
		$ada2	= mysqli_num_rows($sql2);	

		if($ada>0 && $ada2>0){
			$pesan= "<p><h4>Simpan Data Sukses</h4></p>";
			$sukses=1;
		}else{
			$pesan= "<p><h4>Simpan Data Gagal</h4></p>";
			$sukses=0;
		}	
	}
	
}else{
	$text3	= "SELECT username FROM userapp WHERE username='$username' AND kodeuser!='$kodeguru' AND onview=1";
	$sql3 	= mysqli_query($conn,$text3);
	$row	= mysqli_num_rows($sql3);
	if($row>0){
		$pesan= "<p><h4>Username sudah digunakan</h4></p>";
		$sukses=0;
	}else{
		$update = "UPDATE guru SET nama='$nama',nip='$nip',alamat='$alamat',notelp='$notelp',
			tglupdate=SYSDATE(),userupdate='$userid' WHERE kodeguru='$kodeguru'";
		mysqli_query($conn,$update);

		$text	= "SELECT kodeguru FROM guru WHERE kodeguru='$kodeguru' AND nama='$nama' AND userupdate='$userid'";
		$sql 	= mysqli_query($conn,$text);
		$ada	= mysqli_num_rows($sql);	

		$update2 = "UPDATE userapp SET namalengkap='$nama',username='$username',
				tglupdate=SYSDATE(),userupdate='$userid' WHERE kodeuser='$kodeguru'";
		mysqli_query($conn,$update2);

		$text2	= "SELECT kodeuser FROM userapp WHERE kodeuser='$kodeguru' AND username='$username' AND userupdate='$userid'";
		$sql2 	= mysqli_query($conn,$text2);
		$ada2	= mysqli_num_rows($sql2);

		if($ada>0 && $ada2>0){
			$pesan= "<p><h4>Update Data Sukses</h4></p>";
			$sukses=1;
		}else{
			$pesan= "<p><h4>Update Data Gagal</h4></p>";
			$sukses=0;
		}	
	}
}
$data['pesan'] = $pesan;
$data['sukses'] = $sukses;

echo json_encode($data);
?>
