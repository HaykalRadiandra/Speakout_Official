<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_tanggal.php";

$kodesiswa	= $_POST['kodesiswa'];
$username  	= $_POST['username'];
$password  	= md5($_POST['password']);
$nama = ucwords(strtolower($_POST['nama'])); // Mengubah awalan menjadi kapital dan sisanya kecil
$nis		= $_POST['nis'];
$nisn		= $_POST['nisn'];
$alamat		= $_POST['alamat'];
$masasekolah = $_POST['masasekolah'];
$kelas 		= $_POST['kelas'];
$jurusan	= $_POST['jurusan'];
$indeks		= $_POST['indeks'];
$notelp		= $_POST['notelp'];
$tgllahir	= jin_date_sql($_POST['tgllahir']);

$userid     = $_SESSION['namauser'];

$sukses=0;
if(empty($kodesiswa)){

	$text	= "SELECT MAX(RIGHT(kodesiswa,4)) AS nourut FROM siswa";
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

	$kodesiswa = "SISW".sprintf("%04s",$nourut);		
		

	$text3	= "SELECT username FROM userapp WHERE username='$username' AND onview=1";
	$sql3 	= mysqli_query($conn,$text3);
	$row	= mysqli_num_rows($sql3);
	if($row>0){
		$pesan= "<p><h4>Username sudah digunakan</h4></p>";
		$sukses=0;
	}else{
		$input = "INSERT INTO siswa(kodesiswa,nama,nis,nisn,masasekolah,kelas,kodejurusan,indeks,alamat,notelp,tgllahir,tglentry,userid,tglupdate,userupdate,onview) 
    	      	  VALUES('$kodesiswa','$nama','$nis','$nisn',$masasekolah,$kelas,'$jurusan',$indeks,'$alamat','$notelp','$tgllahir',SYSDATE(),'$userid',SYSDATE(),'$userid',1)";
		mysqli_query($conn,$input);

		$text	= "SELECT kodesiswa FROM siswa WHERE kodesiswa='$kodesiswa' AND userid='$userid'";
		$sql 	= mysqli_query($conn,$text);
		$ada	= mysqli_num_rows($sql);	

		if($ada>0){
			$input2 = "INSERT INTO userapp(namalengkap,username,password,jenisuser,kodeuser,tglentry,userid,tglupdate,userupdate,onview) 
    	      	  VALUES('$nama','$username','$password',1,'$kodesiswa',SYSDATE(),'$userid',SYSDATE(),'$userid',1)";
			mysqli_query($conn,$input2);

			$text2	= "SELECT kodeuser FROM userapp WHERE kodeuser='$kodesiswa' AND userid='$userid'";
			$sql2 	= mysqli_query($conn,$text2);
			$ada2	= mysqli_num_rows($sql2);

			if($ada2>0){
				$pesan= "<p><h4>Simpan Data Sukses</h4></p>";
				$sukses=1;
			}else{
				$pesan= "<p><h4>Simpan Data Gagal</h4></p>";
				$sukses=0;
			}	
		}else{
			$pesan= "<p><h4>Simpan Data Gagal</h4></p>".$input;
			$sukses=0;
		}		
	}
}else{
	$text3	= "SELECT username FROM userapp WHERE username='$username' AND kodeuser!='$kodesiswa' AND onview=1";
	$sql3 	= mysqli_query($conn,$text3);
	$row	= mysqli_num_rows($sql3);
	if($row>0){
		$pesan= "<p><h4>Username sudah digunakan</h4></p>";
		$sukses=0;
	}else{
		$update = "UPDATE siswa SET nama='$nama',nis='$nis',nisn='$nisn',masasekolah=$masasekolah,kelas=$kelas,kodejurusan='$jurusan',indeks=$indeks,alamat='$alamat',
				notelp='$notelp',tgllahir='$tgllahir',tglupdate=SYSDATE(),userupdate='$userid' WHERE kodesiswa='$kodesiswa'";
		mysqli_query($conn,$update);

		$text	= "SELECT kodesiswa FROM siswa WHERE kodesiswa='$kodesiswa' AND nama='$nama' AND userupdate='$userid'";
		$sql 	= mysqli_query($conn,$text);
		$ada	= mysqli_num_rows($sql);	

		if($ada>0){
			$update2 = "UPDATE userapp SET namalengkap='$nama',username='$username',
				tglupdate=SYSDATE(),userupdate='$userid' WHERE kodeuser='$kodesiswa'";
			mysqli_query($conn,$update2);

			$text2	= "SELECT kodeuser FROM userapp WHERE kodeuser='$kodesiswa' AND username='$username' AND userupdate='$userid'";
			$sql2 	= mysqli_query($conn,$text2);
			$ada2	= mysqli_num_rows($sql2);
			if($ada2>0){
				$pesan= "<p><h4>Update Data Sukses</h4></p>";
				$sukses=1;
			}else{
				$pesan= "<p><h4>Update Data Gagal</h4></p>";
				$sukses=0;
			}	
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
