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

$filename = "Laporan Bermasalah_" . $tgllkp . ".xlsx";

$cari           = mysqli_real_escape_string($conn, $_GET['cari'] ?? '');
$tglawal        = mysqli_real_escape_string($conn, jin_date_sql($_GET['tglawal'])) ?? '';
$tglakhir       = mysqli_real_escape_string($conn, jin_date_sql($_GET['tglakhir'])) ?? '';
$kelas          = $_GET['kelas'] ?? '';
$jurusan        = $_GET['jurusan'] ?? '';
$indeks         = $_GET['indeks'] ?? '';
$jnspelanggaran = $_GET['jnspelanggaran'] ?? '';
$status         = $_GET['status'] ?? '';
$terlapor       = $_GET['terlapor'] ?? '';

$username 	= $_SESSION['namauser'];
$kodeuser   = $_SESSION['kodeuser'];
$jenisuser  = $_SESSION['jenisuser'];

// * 1. Buat spreadsheet dan ambil sheet aktif
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Laporan Bermasalah");

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
            ->setTitle("Data Laporan Pelanggaran")
            ->setSubject("Pelanggaran Siswa")
            ->setDescription("Data Pelanggaran Siswa SMK Negeri 7 Semarang")
            ->setKeywords("Pelanggaran");

// * 2. Buat header
$sheet->setCellValue('A1', 'LAPORAN BERMASALAH');
$sheet->setCellValue('A2', 'SMK NEGERI 7 SEMARANG');
$sheet->setCellValue('A3', $bulanindo);
$sheet->setCellValue('A5', 'No');
$sheet->setCellValue('B5', 'Tanggal Aduan');
$sheet->setCellValue('C5', 'Nama Terlapor');
$sheet->setCellValue('D5', 'Kelas');
$sheet->setCellValue('E5', 'Jenis Pelanggaran');
$sheet->setCellValue('F5', 'Status');

// * 3. Data dummy
$text 	= "SELECT a.kodeaduan,a.kodepelapor,a.ket,a.status,DATE_FORMAT(a.tglentry,'%d-%m-%Y') AS tglentry,b.nama AS namaterlapor,CONCAT(b.kelas,' ',c.nama,' ',b.indeks) AS kelas,d.nama AS jenispelanggaran
        FROM pelanggaran a LEFT JOIN siswa b ON b.kodesiswa=a.kodeterlapor LEFT JOIN jurusan c ON c.kodejurusan=b.kodejurusan 
        LEFT JOIN jenispelanggaran d ON d.kodepelanggaran=a.kodepelanggaran WHERE a.onview=1 AND DATE_FORMAT(a.tglentry,'%Y-%m-%d')>='$tglawal' AND DATE_FORMAT(a.tglentry,'%Y-%m-%d')<='$tglakhir' ";
// jika user seorang siswa maka hanya tampilkan data yang dilaporkan oleh user tersebut
if($jenisuser==1){
	$text 	= $text. "AND a.kodepelapor='$kodeuser' ";
}

if(!empty($cari)) {
	$text .= " AND (a.ket LIKE '%$cari%' OR b.nama LIKE '%$cari%' OR d.nama LIKE '%$cari%') ";
}

if(!empty($kelas)){
	$text 	= $text. "AND b.kelas=$kelas ";
}

if(!empty($jurusan)){
	$text 	= $text. "AND b.kodejurusan='$jurusan' ";
}

if(!empty($indeks)){
	$text 	= $text. "AND b.indeks=$indeks ";
}

if(!empty($jnspelanggaran)){
	$text 	= $text. "AND a.kodepelanggaran='$jnspelanggaran' ";
}

if(!empty($status)){
	$text 	= $text. "AND a.status='$status' ";
}

$text .= " ORDER BY a.tglentry desc";
$sql 	= mysqli_query($conn,$text);    

$rows 	= [];
while ($row = mysqli_fetch_assoc($sql)) {
    $rows[] = $row;
}

// * 4. Masukkan data mulai baris ke-2
$no = 1;
$nextrow = 6;
foreach ($rows as $data) {
    $status = $data['status'];
    if($status == 1){
        $statusnama = "Menunggu Persetujuan";
    }elseif($status == 2){
        $statusnama = "Disetujui";
    }elseif($status == 3){
        $statusnama = "Ditolak";
    }

    $sheet->setCellValue('A' . $nextrow, $no);
    $sheet->setCellValue('B' . $nextrow, $data['tglentry']);
    $sheet->setCellValue('C' . $nextrow, $data['namaterlapor']);
    $sheet->setCellValue('D' . $nextrow, $data['kelas']);
    $sheet->setCellValue('E' . $nextrow, $data['jenispelanggaran']);
    $sheet->setCellValue('F' . $nextrow, $statusnama);

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
