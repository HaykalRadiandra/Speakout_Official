<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_koma.php";
include "../../inc/fungsi_tanggal.php";

$cari = mysqli_real_escape_string($conn, $_GET['cari'] ?? '');
$urut = $_GET['urut'] ?? "1"; // default terbaru
$order = $urut == "1" ? "DESC" : "ASC";

function query($query){
	global $conn;
	$result = mysqli_query($conn,$query);
	$rows = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$rows[] = $row;
	}
	return $rows;
}

$educations = query("SELECT * FROM edukasi WHERE onview=1 AND judul LIKE '%$cari%' ORDER BY tglentry $order");
// echo "<pre>";
// var_dump($educations);	
// echo "</pre>";
// die;

?>
<?php foreach ($educations as $row) : ?>
<?php 
    $imgPath = !empty($row['foto']) ? "../../img/edukasi/" . $row['foto'] : "../../img/avatar5.png"; // default image
?>
<div class="card" style="width: 30.5rem;height: 20rem;">
	<img 
		src="<?= $imgPath; ?>" 
		data-img="<?= $imgPath; ?>" 
		class="card-img-top img-popup" 
		alt="Contoh Gambar" 
		data-bs-toggle="modal" 
		data-bs-target="#imageModal">

	<div class="card-body d-flex flex-column">
		<h5 class="card-title"><?= $row['judul']; ?></h5>
		<p class="card-text overflow-y-auto"><?= $row['ket']; ?></p>
	</div>
</div>
<?php endforeach; ?>
