<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_tanggal.php";

$kodeedukasi	= $_POST['kodeedukasi'];
$judul			= $_POST['judul'];
$ket			= $_POST['ket'];
$foto_nama 		= "";
$sukses=0;
$userid 		= $_SESSION['namauser'];
$kodeuser = $_SESSION['kodeuser'];

// Proses Upload Foto
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto = $_FILES['foto'];
    $ext = pathinfo($foto['name'], PATHINFO_EXTENSION);
    $allowed_ext = ['jpg', 'jpeg', 'png'];

    if (!in_array(strtolower($ext), $allowed_ext)) {
        echo json_encode(["pesan" => "Format file tidak diizinkan!", "sukses" => false]);
		exit();
    }

    if ($foto['size'] > 5 * 1024 * 1024) {
        echo json_encode(["pesan" => "Ukuran file melebihi 5MB!", "sukses" => false]);
        exit();
    }

    $foto_nama = "EDK_" .$kodeuser."_". time() . "." . $ext;
    $foto_path = "../../img/edukasi/" . $foto_nama;
    
    if (!move_uploaded_file($foto['tmp_name'], $foto_path)) {
        echo json_encode(["pesan" => "Gagal mengupload gambar!", "sukses" => false]);
		exit();
    }
}

if(empty($kodeedukasi)){

	$text	= "SELECT MAX(RIGHT(kodeedukasi,4)) AS nourut FROM edukasi";
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

	$kodeedukasi = "EDK".sprintf("%04s",$nourut);		
		
	$input = "INSERT INTO edukasi(kodeedukasi,judul,ket,foto,tglentry,userid,tglupdate,userupdate,onview) 
          	VALUES('$kodeedukasi','$judul','$ket','$foto_nama',SYSDATE(),'$userid',SYSDATE(),'$userid',1)";
	mysqli_query($conn,$input);

	$text	= "SELECT kodeedukasi FROM edukasi WHERE kodeedukasi='$kodeedukasi' AND userid='$userid'";
	$sql 	= mysqli_query($conn,$text);
	$ada	= mysqli_num_rows($sql);	

	if($ada>0){
		$pesan= "<p><h4>Simpan Data Sukses</h4></p>";
	}else{
		$pesan= "<p><h4>Simpan Data Gagal</h4></p>";
	}	
	
}else{

	if(empty($foto_nama)){
		$update = "UPDATE edukasi SET judul='$judul',ket='$ket',
			tglupdate=SYSDATE(),userupdate='$userid' WHERE kodeedukasi='$kodeedukasi'";
	}else{
		$update = "UPDATE edukasi SET judul='$judul',ket='$ket', foto='$foto_nama',
			tglupdate=SYSDATE(),userupdate='$userid' WHERE kodeedukasi='$kodeedukasi'";
	}	mysqli_query($conn,$update);

	$text	= "SELECT kodeedukasi FROM edukasi WHERE kodeedukasi='$kodeedukasi' AND userupdate='$userid' AND judul='$judul'";
	$sql 	= mysqli_query($conn,$text);
	$ada	= mysqli_num_rows($sql);	

	if($ada>0){
		$pesan= "<p><h4>Update Data Sukses</h4></p>";
	}else{
		$pesan= "<p><h4>Update Data Gagal</h4></p>";
	}	
}
$data['pesan'] = $pesan;
$data['sukses'] = $sukses;

echo json_encode($data);
?>
