<?php
session_start();

require '../../inc/inc.koneksi.php';
require '../../inc/fungsi_hdt.php';
require '../../inc/fungsi_tanggal.php';
require '../../inc/fungsi_indo_now.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$text = "SELECT DATE_FORMAT(SYSDATE(),'%d-%m-%Y_%H.%i.%s') AS tgllkp FROM DUAL";
$tgl = mysqli_fetch_assoc(mysqli_query($conn, $text));
$tgllkp = $tgl['tgllkp'];

$filename = "Laporan CeritaIn_" . $tgllkp . ".xlsx";

$cari = mysqli_real_escape_string($conn, $_GET['cari'] ?? '');
$kategori = $_GET['kategori'] ?? '';
$status = $_GET['status'] ?? '';

// * 1. Buat spreadsheet dan ambil sheet aktif
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Laporan CeritaIn");

// Penjelasan Tujuan dari Export Excel yang dibuat
$spreadsheet->getProperties()->setCreator('SMK Negeri 7 Semarang')
            ->setLastModifiedBy('STM Pembangunan')
            ->setTitle("Data Laporan CeritaIn")
            ->setSubject("CeritaIn Siswa")
            ->setDescription("Data CeritaIn Siswa SMK Negeri 7 Semarang")
            ->setKeywords("CeritaIn");

// * 2. Buat header
$sheet->setCellValue('A1', 'DATA CERITAIN SISWA');
$sheet->setCellValue('A2', 'SMK NEGERI 7 SEMARANG');
$sheet->setCellValue('A3', tanggal_indo_huruf_besar());
$sheet->setCellValue('A5', 'No');
$sheet->setCellValue('B5', 'Tanggal');
$sheet->setCellValue('C5', 'Siswa');
$sheet->setCellValue('D5', 'Kategori');
$sheet->setCellValue('E5', 'Guru');
$sheet->setCellValue('F5', 'Status');

// * 3. Data dummy
$text 	= "SELECT a.kodecerita,a.tglajuan,a.kategori,b.nama AS namasiswa,d.nama AS namaguru,a.descr,a.kategori,a.tipe,CONCAT(b.kelas,' ',c.nama,' ',b.indeks) AS kelas,a.status FROM cerita a LEFT JOIN siswa b ON a.kodesiswa=b.kodesiswa LEFT JOIN jurusan c 
			ON b.kodejurusan=c.kodejurusan LEFT JOIN guru d ON a.kodeguru=d.kodeguru WHERE a.onview=1 ";
			
if(!empty($cari)) {
	$text .= "AND (b.nama LIKE '%$cari%' OR d.nama LIKE '%$cari%' ) ";
}
if(!empty($kategori)) {
	$text .= " AND a.kategori='$kategori' ";
}
if(!empty($status)) {
	$text .= " AND a.status='$status' ";
}

$text .= "ORDER BY b.nama, a.tglajuan DESC";
$sql 	= mysqli_query($conn,$text);  

$rows 	= [];
while ($row = mysqli_fetch_assoc($sql)) {
    $rows[] = $row;
}

// * 4. Masukkan data mulai baris ke-2
$no = 1;
$nextrow = 6;
foreach ($rows as $data) {
    $kategori = $data['kategori'] == 1 ? "Konsultasi" : "Konseling";
    $siswa = ucwords(strtolower($data['namasiswa']));
    $guru = ucwords(strtolower($data['namaguru']));
	$status = $data['status'] == 1 ? "Diproses" : "Selesai";

    // Set warna latar cell status
    $statusCell = 'F' . $nextrow;
    $sheet->setCellValue($statusCell, $status);

    // Warna berdasarkan status
    $fillColor = $data['status'] == 1 ? 'FFFF00' : '92D050'; // Kuning untuk 'Diproses', Hijau untuk 'Selesai'

    $sheet->getStyle($statusCell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $sheet->getStyle($statusCell)->getFill()->getStartColor()->setARGB($fillColor);


    $sheet->setCellValue('A' . $nextrow, $no);
    $sheet->setCellValue('B' . $nextrow, $data['tglajuan']);
    $sheet->setCellValue('C' . $nextrow, $siswa);
    $sheet->setCellValue('D' . $nextrow, $kategori);
    $sheet->setCellValue('E' . $nextrow, $guru);
    $sheet->setCellValue('F' . $nextrow, $status);

    $no++;
    $nextrow++;
}

// * 5. Styling
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

$styleHeaderMerge = [
    'font' => [
        'bold' => true
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ]
];

$styleCol = [
    'font' => [
        'bold' => true
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]
];

$styleRow = [
    'alignment' => [
        'vertical' => Alignment::VERTICAL_CENTER
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]
];

// Terapkan style ke seluruh tabel (header + data)
$lastRow = $nextrow - 1;
$sheet->getStyle("A5:F$lastRow")->applyFromArray($styleArray);
$sheet->getStyle("A1:F1")->applyFromArray($styleHeaderMerge);
$sheet->getStyle("A2:F2")->applyFromArray($styleHeaderMerge);
$sheet->getStyle("A3:F3")->applyFromArray($styleHeaderMerge);
$sheet->getStyle("A4:F4")->applyFromArray($styleHeaderMerge);

// Header bold
$sheet->getStyle('A1:F1')->getFont()->setBold(true);
$sheet->getStyle('A2:F2')->getFont()->setBold(true);
$sheet->getStyle('A3:F3')->getFont()->setBold(true);
$sheet->getStyle('A4:F4')->getFont()->setBold(true);
$sheet->getStyle('A5:F5')->getFont()->setBold(true);


// Merge Cell
$sheet->mergeCells('A1:F1');
$sheet->mergeCells('A2:F2');
$sheet->mergeCells('A3:F3');
$sheet->mergeCells('A4:F4');


// Auto-size kolom
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// * 6. Simpan dan kirim ke browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
