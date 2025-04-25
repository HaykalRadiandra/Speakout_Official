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
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

$text = "SELECT DATE_FORMAT(SYSDATE(),'%d-%m-%Y_%H.%i.%s') AS tgllkp FROM DUAL";
$tgl = mysqli_fetch_assoc(mysqli_query($conn, $text));
$tgllkp = $tgl['tgllkp'];

$filename = "Laporan Kehilangan_" . $tgllkp . ".xlsx";

$cari           = mysqli_real_escape_string($conn, $_GET['cari'] ?? '');
$tglawal        = mysqli_real_escape_string($conn, jin_date_sql($_GET['tglawal'])) ?? '';
$tglakhir       = mysqli_real_escape_string($conn, jin_date_sql($_GET['tglakhir'])) ?? '';
$kelas          = $_GET['kelas'] ?? '';
$jurusan        = $_GET['jurusan'] ?? '';
$indeks         = $_GET['indeks'] ?? '';
$filterstatus 	= $_GET['status'] ?? '';

$username 	= $_SESSION['namauser'];
$kodeuser   = $_SESSION['kodeuser'];
$jenisuser  = $_SESSION['jenisuser'];

// * 1. Buat spreadsheet dan ambil sheet aktif
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Laporan Kehilangan");

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
            ->setTitle("Data Laporan Kehilangan")
            ->setSubject("Kehilangan Barang")
            ->setDescription("Data Kehilangan Barang SMK Negeri 7 Semarang")
            ->setKeywords("Kehilangan Barang");

// * 2. Buat header
$sheet->setCellValue('A1', 'LAPORAN KEHILANGAN');
$sheet->setCellValue('A2', 'SMK NEGERI 7 SEMARANG');
$sheet->setCellValue('A3', $bulanindo);
$sheet->setCellValue('A5', 'No');
$sheet->setCellValue('B5', 'Tanggal Kehilangan');
$sheet->setCellValue('C5', 'Nama Pelapor');
$sheet->setCellValue('D5', 'Kelas');
$sheet->setCellValue('E5', 'Keterangan');
$sheet->setCellValue('F5', 'Lampiran');
$sheet->setCellValue('G5', 'Status');

// * 3. Data dummy
$text 	= "SELECT a.kodekehilangan,a.kodepelapor,a.status,DATE_FORMAT(a.tglentry,'%d-%m-%Y') AS tglentry,b.nama AS namasiswa,d.nama AS namaguru,CONCAT(b.kelas,' ',c.nama,' ',b.indeks) AS kelas,a.ket,a.foto 
	FROM kehilangan a LEFT JOIN siswa b ON b.kodesiswa=a.kodepelapor LEFT JOIN jurusan c ON c.kodejurusan=b.kodejurusan LEFT JOIN guru d ON d.kodeguru=a.kodepelapor 
	WHERE a.onview=1 AND DATE_FORMAT(a.tglentry,'%Y-%m-%d')>='$tglawal' AND DATE_FORMAT(a.tglentry,'%Y-%m-%d')<='$tglakhir' ";
			
if(!empty($cari)) {
	$text .= " AND (a.ket LIKE '%$cari%' OR b.nama LIKE '%$cari%' OR c.nama LIKE '%$cari%' OR d.nama LIKE '%$cari%') ";
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

if(!empty($filterstatus)){
	$text 	= $text. "AND a.status=$filterstatus ";
}

$text .= "ORDER BY a.tglentry desc";
$sql 	= mysqli_query($conn,$text);   

$rows 	= [];
while ($row = mysqli_fetch_assoc($sql)) {
    $rows[] = $row;
}

// * 4. Masukkan data mulai baris ke-2
$no = 1;
$nextrow = 6;
foreach ($rows as $data) {
    $kodepelapor = $data['kodepelapor'];
	$foto = $data['foto'];
	$kelas = $data['kelas'];

	// jika pelapor adalah guru
	if(substr($kodepelapor,0,4)=="GURU"){
		$nama = ucwords(strtolower($data['namaguru']));
		$kelas = "Guru";
	// jika pelapor adalah siswa
	}elseif(substr($kodepelapor,0,4)=="SISW"){
		$nama = ucwords(strtolower($data['namasiswa']));
	}

	$status = $data['status'];
    if($status == 1){
        $statusnama = "Belum Ditemukan";
    }elseif($status == 2){
        $statusnama = "Sudah Ditemukan";
	}
    
    $sheet->setCellValue('A' . $nextrow, $no);
    $sheet->setCellValue('B' . $nextrow, $data['tglentry']);
    $sheet->setCellValue('C' . $nextrow, $nama);
    $sheet->setCellValue('D' . $nextrow, $kelas);
    $sheet->setCellValue('E' . $nextrow, $data['ket']);
    $path = realpath('../../img/kehilangan/' . $foto);
    if (!empty($foto) && $path && file_exists($path)) {
        $drawing = new Drawing();
        $drawing->setName('Foto');
        $drawing->setDescription('Lampiran Foto');
        $drawing->setPath($path); // path ke gambar
        $drawing->setHeight(80); // tinggi gambar
        $drawing->setHeight(80);
        $drawing->setCoordinates('F' . $nextrow); // posisi cell
        $drawing->setOffsetX(30);
        $drawing->setOffsetY(12);
        $drawing->setWorksheet($sheet);

        // ✅ Atur tinggi baris agar muat gambar
        $sheet->getRowDimension($nextrow)->setRowHeight(80);
        
    } else {
        $sheet->setCellValue('F' . $nextrow, 'Tidak ada');
    }
    $sheet->setCellValue('G' . $nextrow, $statusnama);

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
$sheet->getStyle("A5:G$lastRow")->applyFromArray($styleArray);
$sheet->getStyle("A1:G1")->applyFromArray($styleHeaderMerge);
$sheet->getStyle("A2:G2")->applyFromArray($styleHeaderMerge);
$sheet->getStyle("A3:G3")->applyFromArray($styleHeaderMerge);
$sheet->getStyle("A4:G4")->applyFromArray($styleHeaderMerge);

// Header bold
$sheet->getStyle('A1:G1')->getFont()->setBold(true);
$sheet->getStyle('A2:G2')->getFont()->setBold(true);
$sheet->getStyle('A3:G3')->getFont()->setBold(true);
$sheet->getStyle('A4:G4')->getFont()->setBold(true);
$sheet->getStyle('A5:G5')->getFont()->setBold(true);


// Merge Cell
$sheet->mergeCells('A1:G1');
$sheet->mergeCells('A2:G2');
$sheet->mergeCells('A3:G3');
$sheet->mergeCells('A4:G4');


// Auto-size kolom
$colsAuto = ['A', 'B', 'C', 'D', 'E', 'G'];
foreach ($colsAuto as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}
$sheet->getColumnDimension('F')->setWidth(25); // tetap manual

// * 6. Simpan dan kirim ke browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
