<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_tanggal.php";

$kodeinformasi	= $_POST['kodeinformasi'];
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

    $foto_nama = "INF_" .$kodeuser."_". time() . "." . $ext;
    $foto_path = "../../img/informasi/" . $foto_nama;
    
    if (!move_uploaded_file($foto['tmp_name'], $foto_path)) {
        echo json_encode(["pesan" => "Gagal mengupload gambar!", "sukses" => false]);
		exit();
    }
}

if(empty($kodeinformasi)){

	$text	= "SELECT MAX(RIGHT(kodeinformasi,4)) AS nourut FROM informasi";
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

	$kodeinformasi = "INF".sprintf("%04s",$nourut);		
		
	$input = "INSERT INTO informasi(kodeinformasi,judul,ket,foto,tglentry,userid,tglupdate,userupdate,onview) 
          	VALUES('$kodeinformasi','$judul','$ket','$foto_nama',SYSDATE(),'$userid',SYSDATE(),'$userid',1)";
	mysqli_query($conn,$input);

	$text	= "SELECT kodeinformasi FROM informasi WHERE kodeinformasi='$kodeinformasi' AND userid='$userid'";
	$sql 	= mysqli_query($conn,$text);
	$ada	= mysqli_num_rows($sql);	

	if($ada>0){
		$pesan= "<p><h4>Simpan Data Sukses</h4></p>";
	}else{
		$pesan= "<p><h4>Simpan Data Gagal</h4></p>";
	}	
	
}else{
	if(empty($foto_nama)){
		$update = "UPDATE informasi SET judul='$judul',ket='$ket',
			tglupdate=SYSDATE(),userupdate='$userid' WHERE kodeinformasi='$kodeinformasi'";
	}else{
		$update = "UPDATE informasi SET judul ='$judul',ket='$ket', foto='$foto_nama',
			tglupdate=SYSDATE(),userupdate='$userid' WHERE kodeinformasi='$kodeinformasi'";
	}
	mysqli_query($conn,$update);

	$text	= "SELECT kodeinformasi FROM informasi WHERE kodeinformasi='$kodeinformasi' AND userupdate='$userid' AND judul='$judul'";
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
