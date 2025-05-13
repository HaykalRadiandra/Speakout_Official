<?php
session_start();

require '../../inc/inc.koneksi.php';
require '../../inc/fungsi_hdt.php';
require '../../inc/fungsi_tanggal.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$text = "SELECT DATE_FORMAT(SYSDATE(),'%d-%m-%Y_%H.%i.%s') AS tgllkp FROM DUAL";
$tgl = mysqli_fetch_assoc(mysqli_query($conn, $text));
$tgllkp = $tgl['tgllkp'];

$filename = "Laporan Harian_" . $tgllkp . ".xlsx";

$cari           = mysqli_real_escape_string($conn, $_GET['cari'] ?? '');
$tglawal        = mysqli_real_escape_string($conn, jin_date_sql($_GET['tglawal'])) ?? '';
$tglakhir       = mysqli_real_escape_string($conn, jin_date_sql($_GET['tglakhir'])) ?? '';

$kodeuser   = $_SESSION['kodeuser'];

// * 1. Buat spreadsheet dan ambil sheet aktif
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Laporan Harian");

// Memecah tanggal menjadi array [YYYY, MM, DD]
function bln_indo($tanggal) {
    $bulan_array = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];
    
    $tanggal_split = explode('-', $tanggal);
    $tahun = $tanggal_split[0];  // Mengambil tahun
    $bulan = $tanggal_split[1];  // Mengambil bulan
    
    // Format bulan dan tahun, lalu ubah bulan menjadi uppercase
    return strtoupper($bulan_array[$bulan]) . ' ' . $tahun;
}

$bulanindo1 = bln_indo($tglawal);
$bulanindo2 = bln_indo($tglakhir);
$periodeawal = date('m-Y',strtotime($tglawal));
$periodeakhir = date('m-Y',strtotime($tglakhir));
$bulanindo = $periodeawal == $periodeakhir ? $bulanindo2 : $bulanindo1." - ".$bulanindo2;

// Penjelasan Tujuan dari Export Excel yang dibuat
$spreadsheet->getProperties()->setCreator('SMK Negeri 7 Semarang')
            ->setLastModifiedBy('STM Pembangunan')
            ->setTitle("Data Laporan Harian")
            ->setSubject("Harian Siswa")
            ->setDescription("Data Harian Siswa SMK Negeri 7 Semarang")
            ->setKeywords("Harian");

// * 2. Buat header
$sheet->setCellValue('A1', 'LAPORAN HARIAN SISWA');
$sheet->setCellValue('A2', 'SMK NEGERI 7 SEMARANG');
$sheet->setCellValue('A3', $bulanindo);

$sheet->setCellValue('A5', 'No');
$sheet->setCellValue('B5', 'Tanggal Ajuan');
$sheet->setCellValue('C5', 'Kategori');
$sheet->setCellValue('C6', 'Konsultasi');
$sheet->setCellValue('D6', 'Konseling');
$sheet->setCellValue('E6', 'Coaching');
$sheet->setCellValue('F6', 'Total');
$sheet->setCellValue('G5', 'Metode');
$sheet->setCellValue('G6', 'Chat (Online)');
$sheet->setCellValue('H6', 'Temu (Offline)');
$sheet->setCellValue('I6', 'Total');
$sheet->setCellValue('J5', 'Topik');
$sheet->setCellValue('J6', 'Pribadi');
$sheet->setCellValue('K6', 'Belajar');
$sheet->setCellValue('L6', 'Sosial');
$sheet->setCellValue('M6', 'Karir');
$sheet->setCellValue('N6', 'Total');

// * 3. Data dummy
$text = "SELECT 
			xx.datefield,
			DATE_FORMAT(xx.datefield, '%d/%m/%Y') AS tglview,

			SUM(IF(a.kategori IS NOT NULL AND a.onview = 1 AND a.kodesiswa = '$kodeuser', 1, 0)) AS totalkategori,
			SUM(IF(a.kategori = 1 AND a.onview = 1 AND a.kodesiswa = '$kodeuser', 1, 0)) AS konsultasi,
			SUM(IF(a.kategori = 2 AND a.onview = 1 AND a.kodesiswa = '$kodeuser', 1, 0)) AS konseling,
			SUM(IF(a.kategori = 3 AND a.onview = 1 AND a.kodesiswa = '$kodeuser', 1, 0)) AS coaching,

			SUM(IF(a.metode IS NOT NULL AND a.onview = 1 AND a.kodesiswa = '$kodeuser', 1, 0)) AS totalmetode,
			SUM(IF(a.metode = 1 AND a.onview = 1 AND a.kodesiswa = '$kodeuser', 1, 0)) AS chat,
			SUM(IF(a.metode = 2 AND a.onview = 1 AND a.kodesiswa = '$kodeuser', 1, 0)) AS temu,

			SUM(IF(a.topik IS NOT NULL AND a.onview = 1 AND a.kodesiswa = '$kodeuser', 1, 0)) AS totaltopik,
			SUM(IF(a.topik = 1 AND a.onview = 1 AND a.kodesiswa = '$kodeuser', 1, 0)) AS pribadi,
			SUM(IF(a.topik = 2 AND a.onview = 1 AND a.kodesiswa = '$kodeuser', 1, 0)) AS belajar,
			SUM(IF(a.topik = 3 AND a.onview = 1 AND a.kodesiswa = '$kodeuser', 1, 0)) AS sosial,
			SUM(IF(a.topik = 4 AND a.onview = 1 AND a.kodesiswa = '$kodeuser', 1, 0)) AS karir

		FROM calendar xx
		LEFT JOIN cerita a ON a.tglajuan = xx.datefield
		WHERE 
			xx.datefield >= '$tglawal' AND xx.datefield <= '$tglakhir'";

$text .= " GROUP BY xx.datefield ORDER BY xx.datefield DESC";
$sql 	= mysqli_query($conn,$text);	

$rows 	= [];
while ($row = mysqli_fetch_assoc($sql)) {
    $rows[] = $row;
}

// * 4. Masukkan data mulai baris ke-2
$no = 1;
$nextrow = 7;

$grandkonsultasi = 0;
$grandkonseling = 0;
$grandcoaching = 0;
$grandtotalkategori = 0;

$grandchat = 0;
$grandtemu = 0;
$grandtotalmetode = 0;

$grandpribadi = 0;
$grandbelajar = 0;
$grandsosial = 0;
$grandkarir = 0;
$grandtotaltopik = 0;

foreach ($rows as $data) {
    $sheet->setCellValue('A' . $nextrow, $no);
    $sheet->setCellValue('B' . $nextrow, $data['tglview']);
    $sheet->setCellValue('C' . $nextrow, $data['konsultasi']);
    $sheet->setCellValue('D' . $nextrow, $data['konseling']);
    $sheet->setCellValue('E' . $nextrow, $data['coaching']);
    $sheet->setCellValue('F' . $nextrow, $data['totalkategori']);
    $sheet->setCellValue('G' . $nextrow, $data['chat']);
    $sheet->setCellValue('H' . $nextrow, $data['temu']);
    $sheet->setCellValue('I' . $nextrow, $data['totalmetode']);
    $sheet->setCellValue('J' . $nextrow, $data['pribadi']);
    $sheet->setCellValue('K' . $nextrow, $data['belajar']);
    $sheet->setCellValue('L' . $nextrow, $data['sosial']);
    $sheet->setCellValue('M' . $nextrow, $data['karir']);
    $sheet->setCellValue('N' . $nextrow, $data['totaltopik']);

    $grandkonsultasi += $data['konsultasi'];
    $grandkonseling += $data['konseling'];
    $grandcoaching += $data['coaching'];
    $grandtotalkategori += $data['totalkategori'];

    $grandchat += $data['chat'];
    $grandtemu += $data['temu'];
    $grandtotalmetode += $data['totalmetode'];

    $grandpribadi += $data['pribadi'];
    $grandbelajar += $data['belajar'];
    $grandsosial += $data['sosial'];
    $grandkarir += $data['karir'];
    $grandtotaltopik += $data['totaltopik'];

    $no++;
    $nextrow++;
}

// Tambahkan baris Grand Total
$sheet->setCellValue('A' . $nextrow, 'Grand Total');
$sheet->setCellValue('B' . $nextrow, '');
$sheet->setCellValue('C' . $nextrow, $grandkonsultasi);
$sheet->setCellValue('D' . $nextrow, $grandkonseling);
$sheet->setCellValue('E' . $nextrow, $grandcoaching);
$sheet->setCellValue('F' . $nextrow, $grandtotalkategori);
$sheet->setCellValue('G' . $nextrow, $grandchat);
$sheet->setCellValue('H' . $nextrow, $grandtemu);
$sheet->setCellValue('I' . $nextrow, $grandtotalmetode);
$sheet->setCellValue('J' . $nextrow, $grandpribadi);
$sheet->setCellValue('K' . $nextrow, $grandbelajar);
$sheet->setCellValue('L' . $nextrow, $grandsosial);
$sheet->setCellValue('M' . $nextrow, $grandkarir);
$sheet->setCellValue('N' . $nextrow, $grandtotaltopik);

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

$styleTfoot = [
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

// Terapkan style ke seluruh tabel (header + data + footer)
$lastRow = $nextrow - 1;
$sheet->getStyle("A5:N$lastRow")->applyFromArray($styleArray);
$sheet->getStyle("A1:N1")->applyFromArray($styleHeaderMerge);
$sheet->getStyle("A2:N2")->applyFromArray($styleHeaderMerge);
$sheet->getStyle("A3:N3")->applyFromArray($styleHeaderMerge);
$sheet->getStyle("A4:N4")->applyFromArray($styleHeaderMerge);
$sheet->getStyle("A$nextrow:N$nextrow")->applyFromArray($styleTfoot);

// Header bold
$sheet->getStyle('A1:N1')->getFont()->setBold(true);
$sheet->getStyle('A2:N2')->getFont()->setBold(true);
$sheet->getStyle('A3:N3')->getFont()->setBold(true);
$sheet->getStyle('A4:N4')->getFont()->setBold(true);
$sheet->getStyle('A5:N6')->getFont()->setBold(true);


// Merge Cell
$sheet->mergeCells('A1:N1');
$sheet->mergeCells('A2:N2');
$sheet->mergeCells('A3:N3');
$sheet->mergeCells('A4:N4');
$sheet->mergeCells('A5:A6');
$sheet->mergeCells('B5:B6');
$sheet->mergeCells('C5:F5');
$sheet->mergeCells('G5:I5');
$sheet->mergeCells('J5:N5');
// $sheet->mergeCells("A{$nextrow}:B{$nextrow}");
$sheet->mergeCells('A' . $nextrow . ':B' . $nextrow);


// Auto-size Kolom
foreach (range('A', 'N') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// * 6. Simpan dan kirim ke browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
