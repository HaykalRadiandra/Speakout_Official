<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_tanggal.php";

$input = "INSERT INTO pelanggan(idpelanggan,nopelanggan,namapelanggan,alamat,rt,rw,kodewilayah,norumah,notelp,jnstagihan,userid,tglentry,onview) 
          	  VALUES('PLG009','001','tes user','jalan riyadi','01','02','03','04','05','2','admin',SYSDATE(),1)";

$query = mysqli_query($conn,$input);  
//$rec = mysqli_fetch_array($query);

// Periksa hasil query
if ($query) {
    echo "Simpan data sukses";
} else {
    echo "Simpan data gagal";
	//debug
    // echo "Simpan data gagal: " . mysqli_error($conn);
}
// Tutup koneksi
mysqli_close($conn);

?>