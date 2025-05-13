<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";
include "../../inc/fungsi_hdt.php";

$kodecerita = $_POST['kodecerita'] ?? '';

function query($query){
        global $conn;

        $result = mysqli_query($conn,$query);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
        }
        return $rows;
}

$detail = query("SELECT a.kodecerita,a.tglajuan,a.kategori,b.nama AS namasiswa,d.nama AS namaguru,a.descr,a.kategori,a.metode,a.topik,b.kelas AS kelas,CONCAT(c.nama,' ',b.indeks) AS jurusan,a.status FROM cerita a LEFT JOIN siswa b ON a.kodesiswa=b.kodesiswa LEFT JOIN jurusan c 
	ON b.kodejurusan=c.kodejurusan LEFT JOIN guru d ON a.kodeguru=d.kodeguru WHERE a.onview=1 AND a.kodecerita='$kodecerita'");
?>

<div class="mb-2 p-0 d-print-none">
        <h4 class="text-secondary fs-5 fw-semibold" style="font-family: system-ui,'Kaushan Script', cursive;">
                &nbsp; Detail CeritaIn
        </h4>
</div>
<hr class="mb-3 d-print-none">
<?php foreach ($detail as $row) : 
$via = $row['metode'] == 1 ? "Chat / Online" : "Temu / Offline";
$bg = $row['status'] == 1 ? "primary-subtle" : "success-subtle"; 

?>
<div class="row m-0 mb-5 px-1 py-3 bg-white shadow rounded-3">
        <div class="row mb-2">
                <h4 class="text-secondary">Data Siswa</h4>
                <h5 class="text-secondary">Informasi Siswa</h5>
                <div class="col-lg-3">
                        <span class="fw-medium text-secondary">Nama</span>
                        <p><?= $row['namasiswa']; ?></p>
                </div>
                <div class="col-lg-3">
                        <span class="fw-medium text-secondary">Kelas</span>
                        <p><?= $row['kelas']; ?></p>
                </div>
                <div class="col-lg-3">
                        <span class="fw-medium text-secondary">Jurusan</span>
                        <p><?= $row['jurusan']; ?></p>
                </div>
        </div>
        <div class="row">
                <h4 class="text-secondary">Data Konsultasi</h4>
                <div class="col-lg-3">
                        <span class="fw-medium text-secondary">Tanggal Ajuan</span>
                        <p><?= $row['tglajuan']; ?></p>
                </div>
                <div class="col-lg-3">
                        <span class="fw-medium text-secondary">Guru</span>
                        <p><?= $row['namaguru']; ?></p>
                </div>
                <div class="col-lg-3">
                        <span class="fw-medium text-secondary">Status</span>
                        <select class="form-select form-select-sm bg-<?= $bg ?> w-50" aria-label="Small select example">
                        <?php if ($row['status'] == 1) {?>
                                <option value="1" class="bg-primary-subtle" selected>Diproses</option>
                                <option value="2" class="bg-success-subtle" disabled>Selesai</option>
                        <?php } else { ?>
                                <option value="1" class="bg-primary-subtle" disabled>Diproses</option>
                                <option value="2" class="bg-success-subtle" selected>Selesai</option>
                        <?php }; ?>
                        </select>
                </div>
                <div class="col-lg-3">
                        <span class="fw-medium text-secondary">Via</span>
                        <p><?= $via ?></p>
                </div>
                <div class="col-lg-12">
                        <span class="fw-medium text-secondary">Deskripsi</span>
                        <p class="mb-0"><?= $row['descr']; ?></p>
                </div>
        </div>
</div>
<?php endforeach; ?>
<hr class="d-print-none">
<div class="p-0 d-print-none">
        <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-primary me-2" onClick="window.location='?mod=ceritain'">
                        <i class="bi bi-reply-all-fill"></i>&nbsp; Kembali
                </button>
                <button class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Data Bermasalah" onclick="window.print();">
                        <i class="bi bi-printer-fill"></i> Print
                </button>
        </div>
</div>