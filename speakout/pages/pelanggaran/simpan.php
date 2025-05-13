<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_tanggal.php";

$kodeaduan = $_POST['kodeaduan'];
$kodeterlapor = $_POST['kodeterlapor'];
$jnspelanggaran = $_POST['jnspelanggaran'];
$ket = $_POST['ket'];
$foto_nama 		= "";
$sukses=0;

$username 	= $_SESSION['namauser'];
$kodeuser 	= $_SESSION['kodeuser'];

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

	$foto_nama = "ADN_" .$kodeuser."_". time() . "." . $ext;
    $foto_path = "../../img/pelanggaran/" . $foto_nama;
    
    if (!move_uploaded_file($foto['tmp_name'], $foto_path)) {
        echo json_encode(["pesan" => "Gagal mengupload gambar!", "sukses" => false]);
		exit();
    }
}
	
	$text	= "SELECT MAX(RIGHT(kodeaduan,5)) AS nourut FROM pelanggaran";
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

	$kodeaduan = "ADN".sprintf("%05s",$nourut);


	$insert = "INSERT INTO pelanggaran(kodeaduan,kodepelapor,kodeterlapor,kodepelanggaran,ket,foto,status,onview,tglentry,userid,tglupdate,userupdate)
				VALUES('$kodeaduan','$kodeuser','$kodeterlapor','$jnspelanggaran','$ket','$foto_nama',1,1,SYSDATE(),'$username',SYSDATE(),'$username')";
	mysqli_query($conn,$insert);

	$text	= "SELECT kodeaduan FROM pelanggaran WHERE kodeaduan='$kodeaduan'";
	$sql 	= mysqli_query($conn,$text);
	$ada	= mysqli_num_rows($sql);	

	if($ada>0){
		$pesan="<p><h4>Simpan Data Sukses</h4></p>";
	}else{
		$pesan="<p><h4>Simpan Data Gagal</h4></p>";
	}	

$data['pesan'] = $pesan;
$data['sukses'] = $sukses;

echo json_encode($data);

?>