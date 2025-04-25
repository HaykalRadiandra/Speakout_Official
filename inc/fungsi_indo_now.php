<?php 
function tanggal_indo_huruf_besar($tgl = null) {
    $bulanIndo = [
        '01' => 'JANUARI',
        '02' => 'FEBRUARI',
        '03' => 'MARET',
        '04' => 'APRIL',
        '05' => 'MEI',
        '06' => 'JUNI',
        '07' => 'JULI',
        '08' => 'AGUSTUS',
        '09' => 'SEPTEMBER',
        '10' => 'OKTOBER',
        '11' => 'NOVEMBER',
        '12' => 'DESEMBER'
    ];

    if ($tgl === null) $tgl = date('Y-m-d');

    $pecah = explode('-', $tgl);
    $tahun = $pecah[0];
    $bulan = $pecah[1];
    $tanggal = $pecah[2];

    return $tanggal . ' ' . $bulanIndo[$bulan] . ' ' . $tahun;
}

// Contoh pemakaian
// echo tanggal_indo_huruf_besar(); // 21 APRIL 2025


?>