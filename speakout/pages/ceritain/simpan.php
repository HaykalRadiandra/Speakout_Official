<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_tanggal.php";

$kodecerita = $_POST['kodecerita'];
$kodeguru	= $_POST['kodeguru'];
$notelp		= $_POST['notelp'];
$kategori	= $_POST['kategori'];
$metode		= $_POST['metode'];
$tglajuan	= jin_date_sql($_POST['tglajuan']);
$topik		= $_POST['topik'];
$des		= $_POST['des'];

$username 	= $_SESSION['namauser'];
$kodeuser   = $_SESSION['kodeuser'];

$sukses=0;
if(empty($kodecerita)){

	$text	= "SELECT MAX(RIGHT(kodecerita,4)) AS nourut FROM cerita";
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

	$kodecerita = "CRTA".sprintf("%04s",$nourut);		
	
	$input = "INSERT INTO cerita (
				kodecerita,
				kodeguru,
				kodesiswa,
				kategori,
				metode,
				topik,
				tglajuan,
				descr,
				status,
				onview,
				tglentry,
				userid,
				tglupdate,
				userupdate
			) VALUES (
				'$kodecerita',       -- kodecerita
				'$kodeguru',       -- kodeguru
				'$kodeuser',       -- kodesiswa
				'$kategori',                -- kategori
				'$metode',                -- metode
				'$topik',                -- topik
				'$tglajuan', -- tglajuan
				'$des', -- desc
				1,                -- status
				1,                -- onview
				SYSDATE(), -- tglentry
				'$username',          -- userid
				SYSDATE(), -- tglupdate
				'$username'           -- userupdate
			)";
	mysqli_query($conn,$input);

	$text	= "SELECT kodecerita FROM cerita WHERE kodecerita='$kodecerita' AND userid='$username'";
	$sql 	= mysqli_query($conn,$text);
	$ada	= mysqli_num_rows($sql);		

	if($ada > 0){
		$txt = "SELECT b.nama,a.descr,a.kategori,a.metode,a.topik,CONCAT(b.kelas,' ',c.nama,' ',b.indeks) AS kelas FROM cerita a LEFT JOIN siswa b ON a.kodesiswa=b.kodesiswa 
				LEFT JOIN jurusan c ON b.kodejurusan=c.kodejurusan WHERE a.kodecerita = '$kodecerita' AND a.userid='$username'";
		$sql = mysqli_query($conn,$txt);
		$rec = mysqli_fetch_array($sql);
		$kategori = $rec['kategori'];
		$tipe = $rec['metode'];
		$topik = $rec['topik'];

		$pesan= "<p><h4>Simpan Data Sukses</h4></p>";
		$sukses=1;
	}else{
		$pesan= "<p><h4>Simpan Data Gagal</h4></p>";
	}	
	
}
// else{
// 	$update = "UPDATE guru SET nama='$nama',nip='$nip',alamat='$alamat',notelp='$notelp',
// 		tglupdate=SYSDATE(),userupdate='$userid' WHERE kodeguru='$kodeguru'";
// 	mysqli_query($conn,$update);

// 	$text	= "SELECT kodeguru FROM guru WHERE kodeguru='$kodeguru' AND nama='$nama' AND userupdate='$userid'";
// 	$sql 	= mysqli_query($conn,$text);
// 	$ada	= mysqli_num_rows($sql);

// 	if($ada > 0){
// 		$pesan= "<p><h4>Update Data Sukses</h4></p>";
// 		$sukses=1;
// 	}else{
// 		$pesan= "<p><h4>Update Data Gagal</h4></p>";
// 	}	
// }

if(substr($notelp, 0, 1) == "0") {
    $notelp = "62" . substr($notelp, 1);
}else {
	$notelp = "62".$notelp;
}

$kategori = ($kategori==1) ? "Konsultasi" : "Konseling";
$tipe = ($tipe==1) ? "Chat" : "Temu";
$topikMap = [
    '1' => 'Pribadi',
    '2' => 'Belajar',
    '3' => 'Sosial',
    '4' => 'Karir'
];
$topik = isset($topikMap[$topik]) ? $topikMap[$topik] : '-';

$data['pesan'] = $pesan;
$data['sukses'] = $sukses;
$data['nama'] = $rec['nama'] ?? '-';
$data['kelas'] = $rec['kelas'] ?? '-';
$data['desc'] = $rec['descr'] ?? '-';
$data['kategori'] = strtoupper($kategori);
$data['type'] = strtoupper($tipe);
$data['topik'] = strtoupper($topik);
$data['notelp'] = $notelp;

echo json_encode($data);
?>
