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
$sheet->setCellValue('A1', 'LAPORAN HARIAN KESISWAAN');
$sheet->setCellValue('A2', 'SMK NEGERI 7 SEMARANG');
$sheet->setCellValue('A3', $bulanindo);

$sheet->setCellValue('A5', 'No');
$sheet->setCellValue('B5', 'Tanggal');
$sheet->setCellValue('C5', 'Pelanggaran');
$sheet->setCellValue('C6', 'Menunggu Persetujuan');
$sheet->setCellValue('D6', 'Disetujui');
$sheet->setCellValue('E6', 'Ditolak');
$sheet->setCellValue('F6', 'Total');
$sheet->setCellValue('G5', 'Kehilangan');
$sheet->setCellValue('G6', 'Belum Ditemukan');
$sheet->setCellValue('H6', 'Sudah Ditemukan');
$sheet->setCellValue('I6', 'Total');
$sheet->setCellValue('J5', 'Hukuman');
$sheet->setCellValue('J6', 'Belum Selesai');
$sheet->setCellValue('K6', 'Sudah Selesai');
$sheet->setCellValue('L6', 'Total');

// * 3. Data dummy
$text = "SELECT 
			xx.datefield,
			DATE_FORMAT(xx.datefield, '%d/%m/%Y') AS tglview,
			COALESCE(a.totalpelanggaran, 0) AS totalpelanggaran,
			COALESCE(a.pelanggaranmenunggu, 0) AS pelanggaranmenunggu,
			COALESCE(a.pelanggarandisetujui, 0) AS pelanggarandisetujui,
			COALESCE(a.pelanggaranditolak, 0) AS pelanggaranditolak,
			COALESCE(b.totalkehilangan, 0) AS totalkehilangan,
			COALESCE(b.hilangbelum, 0) AS hilangbelum,
			COALESCE(b.hilangsudah, 0) AS hilangsudah,
			COALESCE(c.totalhukuman, 0) AS totalhukuman,
			COALESCE(c.hukumanbelum, 0) AS hukumanbelum,
			COALESCE(c.hukumansudah, 0) AS hukumansudah
		FROM 
			calendar xx
		LEFT JOIN (
			SELECT 
				DATE(tglentry) AS tglentry,
					SUM(IF(onview = 1, 1, 0)) AS totalpelanggaran,
					SUM(IF(STATUS = 1 AND onview = 1, 1, 0)) AS pelanggaranmenunggu,
					SUM(IF(STATUS = 2 AND onview = 1, 1, 0)) AS pelanggarandisetujui,
					SUM(IF(STATUS = 3 AND onview = 1, 1, 0)) AS pelanggaranditolak
			FROM pelanggaran 
			GROUP BY DATE(tglentry)
		) a ON a.tglentry = xx.datefield
		LEFT JOIN (
			SELECT 
				DATE(tglentry) AS tglentry,
					SUM(IF(onview = 1, 1, 0)) AS totalkehilangan,
					SUM(IF(STATUS = 1 AND onview = 1, 1, 0)) AS hilangbelum,
					SUM(IF(STATUS = 2 AND onview = 1, 1, 0)) AS hilangsudah
			FROM kehilangan 
			GROUP BY DATE(tglentry)
		) b ON b.tglentry = xx.datefield
		LEFT JOIN (
			SELECT 
				DATE(tglentry) AS tglentry,
					SUM(IF(onview = 1, 1, 0)) AS totalhukuman,
					SUM(IF(STATUS = 1 AND onview = 1, 1, 0)) AS hukumanbelum,
					SUM(IF(STATUS = 2 AND onview = 1, 1, 0)) AS hukumansudah
			FROM hukuman 
			GROUP BY DATE(tglentry)
		) c ON c.tglentry = xx.datefield
		WHERE 
			xx.datefield >= '$tglawal' AND xx.datefield <= '$tglakhir'";

$text = $text." ORDER BY xx.datefield DESC";
$sql 	= mysqli_query($conn,$text);

$rows 	= [];
while ($row = mysqli_fetch_assoc($sql)) {
    $rows[] = $row;
}

// * 4. Masukkan data mulai baris ke-2
$no = 1;
$nextrow = 7;

$grandpelanggaranmenunggu = 0;
		$grandpelanggarandisetujui = 0;
		$grandpelanggaranditolak = 0;
		$grandtotalpelanggaran = 0;

		$grandhilangbelum = 0;
		$grandhilangsudah = 0;
		$grandtotalkehilangan = 0;

		$grandhukumanbelum = 0;
		$grandhukumansudah = 0;
		$grandtotalhukuman = 0;
foreach ($rows as $data) {
    $sheet->setCellValue('A' . $nextrow, $no);
    $sheet->setCellValue('B' . $nextrow, $data['tglview']);
    $sheet->setCellValue('C' . $nextrow, $data['pelanggaranmenunggu']);
    $sheet->setCellValue('D' . $nextrow, $data['pelanggarandisetujui']);
    $sheet->setCellValue('E' . $nextrow, $data['pelanggaranditolak']);
    $sheet->setCellValue('F' . $nextrow, $data['totalpelanggaran']);
    $sheet->setCellValue('G' . $nextrow, $data['hilangbelum']);
    $sheet->setCellValue('H' . $nextrow, $data['hilangsudah']);
    $sheet->setCellValue('I' . $nextrow, $data['totalkehilangan']);
    $sheet->setCellValue('J' . $nextrow, $data['hukumanbelum']);
    $sheet->setCellValue('K' . $nextrow, $data['hukumansudah']);
    $sheet->setCellValue('L' . $nextrow, $data['totalhukuman']);

    $grandpelanggaranmenunggu=$grandpelanggaranmenunggu+$data['pelanggaranmenunggu'];
    $grandpelanggarandisetujui=$grandpelanggarandisetujui+$data['pelanggarandisetujui'];	
    $grandpelanggaranditolak=$grandpelanggaranditolak+$data['pelanggaranditolak'];
    $grandtotalpelanggaran=$grandtotalpelanggaran+$data['totalpelanggaran'];

    $grandhilangbelum=$grandhilangbelum+$data['hilangbelum'];
    $grandhilangsudah=$grandhilangsudah+$data['hilangsudah'];			
    $grandtotalkehilangan=$grandtotalkehilangan+$data['totalkehilangan'];	

    $grandhukumanbelum=$grandhukumanbelum+$data['hukumanbelum'];
    $grandhukumansudah=$grandhukumansudah+$data['hukumansudah'];			
    $grandtotalhukuman=$grandtotalhukuman+$data['totalhukuman'];	

    $no++;
    $nextrow++;
}

// Tambahkan baris Grand Total
$sheet->setCellValue('A' . $nextrow, 'Grand Total');
$sheet->setCellValue('B' . $nextrow, '');
$sheet->setCellValue('C' . $nextrow, $grandpelanggaranmenunggu);
$sheet->setCellValue('D' . $nextrow, $grandpelanggarandisetujui);
$sheet->setCellValue('E' . $nextrow, $grandpelanggaranditolak);
$sheet->setCellValue('F' . $nextrow, $grandtotalpelanggaran);
$sheet->setCellValue('G' . $nextrow, $grandhilangbelum);
$sheet->setCellValue('H' . $nextrow, $grandhilangsudah);
$sheet->setCellValue('I' . $nextrow, $grandtotalkehilangan);
$sheet->setCellValue('J' . $nextrow, $grandhukumanbelum);
$sheet->setCellValue('K' . $nextrow, $grandhukumansudah);
$sheet->setCellValue('L' . $nextrow, $grandtotalhukuman);

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
$sheet->getStyle("A5:L$lastRow")->applyFromArray($styleArray);
$sheet->getStyle("A1:L1")->applyFromArray($styleHeaderMerge);
$sheet->getStyle("A2:L2")->applyFromArray($styleHeaderMerge);
$sheet->getStyle("A3:L3")->applyFromArray($styleHeaderMerge);
$sheet->getStyle("A4:L4")->applyFromArray($styleHeaderMerge);
$sheet->getStyle("A$nextrow:L$nextrow")->applyFromArray($styleTfoot);

// Header bold
$sheet->getStyle('A1:L1')->getFont()->setBold(true);
$sheet->getStyle('A2:L2')->getFont()->setBold(true);
$sheet->getStyle('A3:L3')->getFont()->setBold(true);
$sheet->getStyle('A4:L4')->getFont()->setBold(true);
$sheet->getStyle('A5:L6')->getFont()->setBold(true);


// Merge Cell
$sheet->mergeCells('A1:L1');
$sheet->mergeCells('A2:L2');
$sheet->mergeCells('A3:L3');
$sheet->mergeCells('A4:L4');
$sheet->mergeCells('A5:A6');
$sheet->mergeCells('B5:B6');
$sheet->mergeCells('C5:F5');
$sheet->mergeCells('G5:I5');
$sheet->mergeCells('J5:L5');
// $sheet->mergeCells("A{$nextrow}:B{$nextrow}");
$sheet->mergeCells('A' . $nextrow . ':B' . $nextrow);


// Auto-size Kolom
foreach (range('A', 'L') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// * 6. Simpan dan kirim ke browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
