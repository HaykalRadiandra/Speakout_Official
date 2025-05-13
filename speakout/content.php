<?php
include "inc/inc.koneksi.php";

$mod 		= $_GET['mod'];
$jenisuser 	= $_SESSION['jenisuser'];
$username 	= $_SESSION['namauser'];
$kodeuser  = $_SESSION['kodeuser'];
$jenisAkses = $_SESSION['jenisakses'];

if(empty($jenisuser) or empty($username) or empty($kodeuser)){
	header('location:index.html');
}

// Beranda
if ($jenisAkses == "kesiswaan" ){
	if($mod=='home'){
		include "pages/info/info.php";
	}elseif ($mod=='guru' && $jenisuser==9){	
		include "pages/info/guru.php"; // beranda guru
	}elseif ($mod=='siswa' && $jenisuser==1){ 
		include "pages/info/siswa.php"; // beranda siswa
	
	// Data Master
	}elseif ($mod=='jurusan' && $jenisuser==9){ 
		include "pages/jurusan/entrijurusan.php"; 	// entri jurusan
	}elseif ($mod=='entrisiswa' && $jenisuser==9){  
		include "pages/siswa/entrisiswa.php"; 		// entri siswa
	}elseif ($mod=='entriguru' && $jenisuser==9){ 
		include "pages/guru/entriguru.php"; 		// entri guru
	}elseif ($mod=='jnspelanggaran' && $jenisuser==9){ 
		include "pages/jenispelanggaran/entrijenispelanggaran.php"; 	// entri jenis pelanggaran
	}elseif ($mod=='entriinformasi' && $jenisuser==9){  
		include "pages/informasi/entriinformasi.php"; 		// entri informasi
	}elseif ($mod=='entriedukasi' && $jenisuser==9){  
		include "pages/edukasi/entriedukasi.php"; 		// entri edukasi
	
	// Aduan
	}elseif ($mod=='aduanpelanggaran'){
		include "pages/pelanggaran/entribermasalah.php"; 	// aduan pelanggran
	}elseif ($mod=='aduankehilangan'){
		include "pages/kehilangan/entrikehilangan.php"; 	// aduan kehilangan  
	
	// Hukuman
	}elseif ($mod=='hukuman'){
		include "pages/hukuman/entrihukuman.php"; 	// hukuman
	
	// CeritaIn
	// }elseif ($mod=='ceritain'){
	// 	include "pages/ceritain/ceritain.php"; // ceritain
	
	// Education
	}elseif ($mod=='edukasi'){
		include "pages/education/education.php"; // education
	
	// Laporan
	}elseif ($mod=='lapharian' && $jenisuser==9){  
		include "pages/lapharian/lapharian.php"; 		// Laporan Harian Guru
	}elseif ($mod=='laphariansiswa' && $jenisuser==1){  
		include "pages/laphariansiswa/laphariansiswa.php"; 		// Laporan Harian Siswa
	
	// Pengaturan
	}elseif ($mod=='setuser'){
		include "pages/user/settinguser.php"; // setting user
	}elseif ($mod=='chpass'){
		include "pages/updateacc/changepass.php"; // change password
	}elseif ($mod=='exit'){
		include "pages/logout.php";	//	Exit
	
	}else{
		header('location:index.html');
	}
	
} else if ($jenisAkses == "bk"){
	if($mod=='home'){
		include "pages/info/info.php";
	}elseif ($mod=='guru' && $jenisuser==9){	
		include "pages/info/guru.php"; // beranda guru
	}elseif ($mod=='siswa' && $jenisuser==1){ 
		include "pages/info/siswa.php"; // beranda siswa
	
	// Data Master
	}elseif ($mod=='jurusan' && $jenisuser==9){ 
		include "pages/jurusan/entrijurusan.php"; 	// entri jurusan
	}elseif ($mod=='entrisiswa' && $jenisuser==9){  
		include "pages/siswa/entrisiswa.php"; 		// entri siswa
	}elseif ($mod=='entriguru' && $jenisuser==9){ 
		include "pages/guru/entriguru.php"; 		// entri guru
	}elseif ($mod=='jnspelanggaran' && $jenisuser==9){ 
		include "pages/jenispelanggaran/entrijenispelanggaran.php"; 	// entri jenis pelanggaran
	}elseif ($mod=='entriinformasi' && $jenisuser==9){  
		include "pages/informasi/entriinformasi.php"; 		// entri informasi
	}elseif ($mod=='entriedukasi' && $jenisuser==9){  
		include "pages/edukasi/entriedukasi.php"; 		// entri edukasi
	
	// CeritaIn
	}elseif ($mod=='ceritain'){
		include "pages/ceritain/ceritain.php"; // ceritain
	
	// Education
	}elseif ($mod=='edukasi'){
		include "pages/education/education.php"; // education
	
	// Laporan
	}elseif ($mod=='lapharian' && $jenisuser==9){  
		include "pages/lapharianbk/lapharianbk.php"; 		// Laporan Harian Guru
	}elseif ($mod=='laphariansiswa' && $jenisuser==1){  
		include "pages/laphariansiswabk/laphariansiswabk.php"; 		// Laporan Harian Siswa
	
	// Pengaturan
	}elseif ($mod=='setuser'){
		include "pages/user/settinguser.php"; // setting user
	}elseif ($mod=='chpass'){
		include "pages/updateacc/changepass.php"; // change password
	}elseif ($mod=='exit'){
		include "pages/logout.php";	//	Exit
	
	}else{
		header('location:index.html');
	}	
}
?>
