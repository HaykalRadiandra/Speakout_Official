<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_tanggal.php";

$kodekehilangan	= $_POST['kodekehilangan'];
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

    $foto_nama = "HLG_" .$kodeuser."_". time() . "." . $ext;
    $foto_path = "../../img/kehilangan/" . $foto_nama;
    
    if (!move_uploaded_file($foto['tmp_name'], $foto_path)) {
        echo json_encode(["pesan" => "Gagal mengupload gambar!", "sukses" => false]);
		exit();
    }
}

if(empty($kodekehilangan)){

	$text	= "SELECT MAX(RIGHT(kodekehilangan,5)) AS nourut FROM kehilangan";
	$sql 	= mysqli_query($conn,$text);
	$row	= mysqli_num_rows($sql);
	if($row>0){
		$rec = mysqli_fetch_array($sql);
		$nourut = $rec['nourut']+1;
		if($nourut>99999){
			$nourut=1;
		}	 	
	}else{
		$nourut=1;		
	}

	$kodekehilangan = "HLG".sprintf("%05s",$nourut);		
		
	$input = "INSERT INTO kehilangan(kodekehilangan,kodepelapor,ket,foto,status,onview,tglentry,userid,tglupdate,userupdate) 
          	VALUES('$kodekehilangan','$kodeuser','$ket','$foto_nama',1,1,SYSDATE(),'$userid',SYSDATE(),'$userid')";
	mysqli_query($conn,$input);

	$text	= "SELECT kodekehilangan FROM kehilangan WHERE kodekehilangan='$kodekehilangan' AND userid='$userid'";
	$sql 	= mysqli_query($conn,$text);
	$ada	= mysqli_num_rows($sql);	

	if($ada>0){
		$pesan= "<p><h4>Simpan Data Sukses</h4></p>";
		$sukses=1;
	}else{
		$pesan= "<p><h4>Simpan Data Gagal</h4></p>";
	}	
	
}else{

	if(empty($foto_nama)){
		$update = "UPDATE kehilangan SET ket='$ket',
			tglupdate=SYSDATE(),userupdate='$userid' WHERE kodekehilangan='$kodekehilangan'";
	}else{
		$update = "UPDATE kehilangan SET ket='$ket', foto='$foto_nama',
			tglupdate=SYSDATE(),userupdate='$userid' WHERE kodekehilangan='$kodekehilangan'";
	}	mysqli_query($conn,$update);

	$text	= "SELECT kodekehilangan FROM kehilangan WHERE kodekehilangan='$kodekehilangan' AND userupdate='$userid'";
	$sql 	= mysqli_query($conn,$text);
	$ada	= mysqli_num_rows($sql);	

	if($ada>0){
		$pesan= "<p><h4>Update Data Sukses</h4></p>";
		$sukses=1;
	}else{
		$pesan= "<p><h4>Update Data Gagal</h4></p>";
	}	
}
$data['pesan'] = $pesan;
$data['sukses'] = $sukses;

echo json_encode($data);
?>
