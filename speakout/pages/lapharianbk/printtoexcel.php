<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_tanggal.php";
// include "../../inc/fungsi_indotgl.php";

require_once '../../PHPExcel/PHPExcel.php';

$text1 = "SELECT DATE_FORMAT(SYSDATE(),'%Y-%m-%d_%H:%i:%s') AS tgllkp FROM DUAL";
$q = mysql_query($text1);
$rs = mysql_fetch_array($q);
$tgllkp = $rs['tgllkp'];

// Menyiapkan nama file untuk ekspor Excel
$namaFile = "DataLaporanKas_" . $tgllkp . ".xls";

$tgltrx1 = jin_date_sql($_GET[tgltrx]);
$tgltrx2 = jin_date_sql($_GET[tgltrx2]);

$userid	= $_SESSION[user];


function bln_indo($tanggal) {
// Memecah tanggal menjadi array [YYYY, MM, DD]
$bulan_array = array(
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
);

// Memecah tanggal menjadi array [YYYY, MM, DD]
$tanggal_split = explode('-', $tanggal);
$tahun = $tanggal_split[0];  // Mengambil tahun
$bulan = $tanggal_split[1];  // Mengambil bulan

// Format bulan dan tahun, lalu ubah bulan menjadi uppercase
return strtoupper($bulan_array[$bulan]) . ' ' . $tahun;
}
// Konversi tanggal menjadi nama bulan Indonesia
$bulanindo1 = bln_indo($tgltrx1);
$bulanindo2 = bln_indo($tgltrx2);
  
// // Untuk Menampilkan hasil namaarea
// $text3	    ="SELECT kodearea,namaperusahaan FROM perusahaan WHERE kodearea='$kodearea' ORDER BY index_perusahaan";
// $tampil3    = mysql_query($text3);
// $r3         = mysql_fetch_array($tampil3);

// Untuk menambahkan tanggal pada Muka Excel
$text2 = "SELECT DATE_FORMAT(CURRENT_DATE(),'%d-%M-%Y') AS tglskg FROM DUAL";
$q2 = mysql_query($text2);
$rs2 = mysql_fetch_array($q2);

$excel = new PHPExcel();

// Penjelasan Tujuan dari Export Excel yang dibuat
$excel->getProperties()->setCreator('PAB Prima Lestari')
             ->setLastModifiedBy('CV Inti Ahsan')
             ->setTitle("Data Laporan Kas")
             ->setSubject("Data Laporan Kas")
             ->setDescription("Data Laporan Kas PAB Prima Lestari")
             ->setKeywords("Laporan Kas");

$style_col = array(
  'font' => array('bold' => true), // Set font nya jadi bold
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
  ),
  'borders' => array(
    'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
    'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
    'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
    'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
  )
);

$style_row = array(
  'alignment' => array(
    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
  ),
  'borders' => array(
    'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
    'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
    'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
    'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
  )
);

$styleBorder = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN
        )
      )
    );

// Mengatur ukuran lebar kolom Excel
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);

// Mengatur ukuran lebar kolom secara otomatis sesuai isi content
// $excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

// Mengatur ukuran tinggi kolom Excel
// $excel->getActiveSheet()->getColumnDimension('B')->setHeight(15);
// $excel->getActiveSheet()->getColumnDimension('C')->setHeight(20);

// $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(25); // Atur tinggi semua baris menjadi 25
// $excel->getActiveSheet()->getRowDimension(1)->setRowHeight(25); // Atur tinggi baris 1
// $excel->getActiveSheet()->getRowDimension(2)->setRowHeight(30); // Atur tinggi baris 2


$excel->setActiveSheetIndex(0)->setCellValue('A1', "PAB PRIMA LESTARI"); // Set kolom A1 dengan tulisan "PAB PRIMA LESTARI"
$excel->getActiveSheet()->mergeCells('A1:H1'); // Set Merge Cell pada kolom A1 sampai F1
$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(11); // Set font size 15 untuk kolom A1
$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$excel->setActiveSheetIndex(0)->setCellValue('A2', "DATA PEMBAYARAN MANDIRI"); // Set kolom A2 dengan tulisan "DATA SISWA"
$excel->getActiveSheet()->mergeCells('A2:H2'); // Set Merge Cell pada kolom A2 sampai F1
$excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE); // Set bold kolom A2
$excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11); // Set font size 15 untuk kolom A2
$excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$excel->setActiveSheetIndex(0)->setCellValue('A3', "WILAYAH TUGUREJO SEMARANG"); // Set kolom A1 dengan tulisan "DATA SISWA"
$excel->getActiveSheet()->mergeCells('A3:H3'); // Set Merge Cell pada kolom A1 sampai F1
$excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE); // Set bold kolom A1
$excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(11); // Set font size 15 untuk kolom A1
$excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// $excel->setActiveSheetIndex(0)->setCellValue('A4', $rs2['tglskg']); // Set kolom A1 dengan tulisan "DATA SISWA"
// $excel->getActiveSheet()->mergeCells('A4:H4'); // Set Merge Cell pada kolom A1 sampai F1
// $excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(TRUE); // Set bold kolom A1
// $excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(11); // Set font size 15 untuk kolom A1
// $excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


if (empty($tgltrx1) || date('m', strtotime($tgltrx1)) != date('m', strtotime($tgltrx2))) {
// Jika $tgltrx1 kosong atau bulannya tidak sama dengan $tgltrx2
if (empty($tgltrx1)) {
        $bulanindo1 = '-'; // Mengganti $tgltrx1 dengan strip jika kosong
}
        $excel->setActiveSheetIndex(0)->setCellValue('A4', "DARI ".$bulanindo1." - ".$bulanindo2); 
        $excel->getActiveSheet()->mergeCells('A4:H4'); 
        $excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(TRUE); 
        $excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(11); 
        $excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
} else {
// Jika bulan $tgltrx1 sama dengan $tgltrx2
$excel->setActiveSheetIndex(0)->setCellValue('A4', "PER ".$bulanindo2); 
$excel->getActiveSheet()->mergeCells('A4:F4'); 
$excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(TRUE); 
$excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(11);
$excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
}
  

$excel->setActiveSheetIndex(0)->setCellValue('A6', "NO"); 
$excel->getActiveSheet()->mergeCells('A6:A7'); // Set Merge Cell pada kolom A1 sampai F1
$excel->getActiveSheet()->getStyle('A6')->getFont()->setBold(TRUE);
$excel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->setActiveSheetIndex(0)->setCellValue('B6', "TANGGAL"); 
$excel->getActiveSheet()->mergeCells('B6:B7'); // Set Merge Cell pada kolom A1 sampai F1
$excel->getActiveSheet()->getStyle('B6')->getFont()->setBold(TRUE);
$excel->getActiveSheet()->getStyle('B6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->setActiveSheetIndex(0)->setCellValue('C6', "SALDO AWAL	"); 
$excel->getActiveSheet()->mergeCells('C6:C7'); // Set Merge Cell pada kolom A1 sampai F1
$excel->getActiveSheet()->getStyle('C6')->getFont()->setBold(TRUE);
$excel->getActiveSheet()->getStyle('C6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->setActiveSheetIndex(0)->setCellValue('D6', "PENERIMAAN	"); 
$excel->getActiveSheet()->mergeCells('D6:E6'); // Set Merge Cell pada kolom A1 sampai F1
$excel->getActiveSheet()->getStyle('D6')->getFont()->setBold(TRUE);
$excel->getActiveSheet()->getStyle('D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->setActiveSheetIndex(0)->setCellValue('F6', "PENGELUARAN"); 
$excel->getActiveSheet()->mergeCells('F6:G6'); // Set Merge Cell pada kolom A1 sampai F1
$excel->getActiveSheet()->getStyle('F6')->getFont()->setBold(TRUE);
$excel->getActiveSheet()->getStyle('F6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->setActiveSheetIndex(0)->setCellValue('H6', "SALDO AKHIR"); 
$excel->getActiveSheet()->mergeCells('H6:H7'); // Set Merge Cell pada kolom A1 sampai F1
$excel->getActiveSheet()->getStyle('H6')->getFont()->setBold(TRUE);
$excel->getActiveSheet()->getStyle('H6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$excel->setActiveSheetIndex(0)->setCellValue('D7', "KOLEKTIF"); 
$excel->getActiveSheet()->mergeCells('D7:D7'); // Set Merge Cell pada kolom A1 sampai F1
$excel->getActiveSheet()->getStyle('D7')->getFont()->setBold(TRUE);
$excel->getActiveSheet()->getStyle('D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->setActiveSheetIndex(0)->setCellValue('E7', "MANDIRI"); 
$excel->getActiveSheet()->mergeCells('E7:E7'); // Set Merge Cell pada kolom A1 sampai F1
$excel->getActiveSheet()->getStyle('E7')->getFont()->setBold(TRUE);
$excel->getActiveSheet()->getStyle('E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->setActiveSheetIndex(0)->setCellValue('F7', "PEMBAYARAN GAJI"); 
$excel->getActiveSheet()->mergeCells('F7:F7'); // Set Merge Cell pada kolom A1 sampai F1
$excel->getActiveSheet()->getStyle('F7')->getFont()->setBold(TRUE);
$excel->getActiveSheet()->getStyle('F7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->setActiveSheetIndex(0)->setCellValue('G7', "PENGELUARAN LAIN"); 
$excel->getActiveSheet()->mergeCells('G7:G7'); // Set Merge Cell pada kolom A1 sampai F1
$excel->getActiveSheet()->getStyle('G7')->getFont()->setBold(TRUE);
$excel->getActiveSheet()->getStyle('G7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$excel->getActiveSheet()->getStyle('A6:H7')->getAlignment()->setWrapText(true);
$excel->setActiveSheetIndex(0)->getStyle('A6:H7')->applyFromArray($styleBorder);
$excel->getActiveSheet()->getStyle('A6:H7')->applyFromArray($style_col);

$tgltrx1 = jin_date_sql($_GET[tgltrx]);
$tgltrx2 = jin_date_sql($_GET[tgltrx2]);

$userid	= $_SESSION[user];

$textx = "SELECT cutoff FROM setaplikasi";
$sql 	= mysql_query($textx);
$r 		= mysql_fetch_array($sql);
$cutoff = $r[cutoff]; // value= 2023-12-31

//Jika tanggal transaksi pertama ($tgltrx1) berada sebelum atau pada tanggal cutoff, maka saldo awal dihitung berdasarkan semua transaksi hingga tanggal cutoff/2023-12-31.
if($tgltrx1<=$cutoff){
	$textx = "SELECT COALESCE(SUM(jmlrp),0) AS saldoawal FROM historykas WHERE tgltrx<='$cutoff' ";
}else{
//Jika tanggal transaksi pertama ($tgltrx1) berada setelah tanggal cutoff, maka saldo awal dihitung berdasarkan semua transaksi sebelum tanggal transaksi pertama ($tgltrx1).
	$textx = "SELECT COALESCE(SUM(jmlrp),0) AS saldoawal FROM historykas WHERE tgltrx<'$tgltrx1' ";
}

$sql 	= mysql_query($textx);
$r 		= mysql_fetch_array($sql);
$saldoawal = $r[saldoawal];

$text = "SELECT xx.datefield,DATE_FORMAT(xx.datefield,'%d/%m/%Y') AS tglbayarview,
    	 COALESCE(a.saldoawal, 0) AS saldoawal,
    	 COALESCE(b.pembayarankolektif, 0) AS pembayarankolektif,
    	 COALESCE(b.pembayaranmandiri, 0) AS pembayaranmandiri,
		 COALESCE(c.biayagaji, 0) AS biayagaji,
    	 COALESCE(d.pengeluaranlain, 0) AS pengeluaranlain
		 FROM calendar xx 
		 LEFT JOIN 
		 	(SELECT tgltrx, SUM(IF(SUBSTR(kodetrx, 5, 2)='SA', jmlrp, 0)) AS saldoawal FROM historykas GROUP BY tgltrx) a ON a.tgltrx = xx.datefield
		 LEFT JOIN 
		 	(SELECT tglbayar, SUM(IF(jnstagihan = 1, rpbayar, 0)) AS pembayarankolektif, SUM(IF(jnstagihan = 2, rpbayar, 0)) AS pembayaranmandiri 
		 	FROM pembayaran_detail GROUP BY tglbayar) b ON b.tglbayar = xx.datefield
		 LEFT JOIN 
		 	(SELECT tglgaji, SUM(totalgaji) AS biayagaji FROM gaji GROUP BY tglgaji) c ON c.tglgaji = xx.datefield
		 LEFT JOIN 
		 	(SELECT tgltrx, SUM(jmlrp) AS pengeluaranlain FROM pengeluaranlain GROUP BY tgltrx) d ON d.tgltrx = xx.datefield
		 WHERE xx.datefield>='$tgltrx1' AND xx.datefield<='$tgltrx2' ";


$text = $text."GROUP BY xx.datefield ORDER BY xx.datefield";
$sql = mysql_query($text);	
$jmlrec = mysql_num_rows($sql);

$no = 1;
$nextrow = 8;

while ($rec = mysql_fetch_array($sql)){

  if($no!=1){
    $saldoawal=$saldoakhir;	
  }
  
  $totalpenerimaan  = $rec[pembayarankolektif]+$rec[pembayaranmandiri];
  $totalpengeluaran = $rec[biayagaji]+$rec[pengeluaranlain];
  $saldoakhir = $saldoawal+$totalpenerimaan-$totalpengeluaran;
    
  if($saldoawal==0){	
    $saldoawalx="";
  }else{
    $saldoawalx=number_format($saldoawal,2,",",".");
  }
  
  if($rec[pembayarankolektif]==0){	
    $pembayarankolektifx="";
  }else{
    $pembayarankolektifx=number_format($rec[pembayarankolektif],2,",",".");
  }
  
  if($rec[pembayaranmandiri]==0){	
    $pembayaranmandirix="";
  }else{
    $pembayaranmandirix=number_format($rec[pembayaranmandiri],2,",",".");
  }
  
  if($rec[biayagaji]==0){	
    $biayagajix="";
  }else{
    $biayagajix=number_format($rec[biayagaji],2,",",".");
  }
  
  if($rec[pengeluaranlain]==0){	
    $pengeluaranlainx="";
  }else{
    $pengeluaranlainx=number_format($rec[pengeluaranlain],2,",",".");
  }
  
  if($saldoakhir==0){	
    $saldoakhirx="";
  }else{
    $saldoakhirx=number_format($saldoakhir,2,",",".");
  }

  $excel->setActiveSheetIndex(0)->getStyle('A'.$nextrow.':H'.$nextrow)->applyFromArray($styleBorder);
  //$excel->getActiveSheet()->getStyle('C'.$nextrow.':H'.$nextrow)->getNumberFormat()->setFormatCode('#,##0');				
  //$excel->getActiveSheet()->getStyle('B'.$nextrow)->getNumberFormat()->setFormatCode('#,##0.00');
  $excel->getActiveSheet()->getStyle('C'.$nextrow.':H'.$nextrow)->getNumberFormat()->setFormatCode('#,##0.00');

  $excel->setActiveSheetIndex(0)->setCellValue('A'.$nextrow, $no.'.');
  $excel->getActiveSheet()->getStyle('A'.$nextrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
  $excel->getActiveSheet()->getStyle('A'.$nextrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  
  $excel->setActiveSheetIndex(0)->setCellValue('B'.$nextrow, $rec[tglbayarview]);
  $excel->getActiveSheet()->getStyle('B'.$nextrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
  $excel->getActiveSheet()->getStyle('B'.$nextrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  $excel->setActiveSheetIndex(0)->setCellValue('C'.$nextrow, $saldoawalx);
  $excel->getActiveSheet()->getStyle('C'.$nextrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
  $excel->getActiveSheet()->getStyle('C'.$nextrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
  $excel->setActiveSheetIndex(0)->setCellValue('D'.$nextrow, $pembayarankolektifx);
  $excel->getActiveSheet()->getStyle('D'.$nextrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
  $excel->getActiveSheet()->getStyle('D'.$nextrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
  $excel->setActiveSheetIndex(0)->setCellValue('E'.$nextrow, $pembayaranmandirix);
  $excel->getActiveSheet()->getStyle('E'.$nextrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
  $excel->getActiveSheet()->getStyle('E'.$nextrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
  $excel->setActiveSheetIndex(0)->setCellValue('F'.$nextrow, $biayagajix);
  $excel->getActiveSheet()->getStyle('F'.$nextrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
  $excel->getActiveSheet()->getStyle('F'.$nextrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
  $excel->setActiveSheetIndex(0)->setCellValue('G'.$nextrow, $pengeluaranlainx);
  $excel->getActiveSheet()->getStyle('G'.$nextrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
  $excel->getActiveSheet()->getStyle('G'.$nextrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
  $excel->setActiveSheetIndex(0)->setCellValue('H'.$nextrow, $saldoakhirx);
  $excel->getActiveSheet()->getStyle('H'.$nextrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
  $excel->getActiveSheet()->getStyle('H'.$nextrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); 

  if($no==1){
    $totsaldoawal=$saldoawal;
  }	
  $totpembayarankolektif=$totpembayarankolektif+$rec[pembayarankolektif];
  $totpembayaranmandiri=$totpembayaranmandiri+$rec[pembayaranmandiri];	
  $totbiayagaji=$totbiayagaji+$rec[biayagaji];
  $totpembeliantunai=$totpembeliantunai+$rec[pembeliantunai];
  $totpembayaranhutang=$totpembayaranhutang+$rec[pembayaranhutang];
  $totpembayarangaji=$totpembayarangaji+$rec[pembayarangaji];			
  $totpengeluaranlain=$totpengeluaranlain+$rec[pengeluaranlain];	
  $totsaldoakhir=$saldoakhir;	

  $no++;
  $nextrow++; 
}

// Code dibawah untuk footer table jika ada total dan sebagainya
$excel->setActiveSheetIndex(0)->getStyle('A'.$nextrow.':H'.$nextrow)->applyFromArray($styleBorder);
$excel->getActiveSheet()->getStyle('A'.$nextrow.':H'.$nextrow)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$excel->getActiveSheet()->getStyle('A'.$nextrow.':H'.$nextrow)->getFill()->getStartColor()->setARGB('afd2e3');
// $excel->getActiveSheet()->getStyle('I'.$nextrow)->getNumberFormat()->setFormatCode('#,##0,00');
$excel->getActiveSheet()->getStyle('A'.$nextrow.':H'.$nextrow)->getNumberFormat()->setFormatCode('#,##0.00');
// $excel->getActiveSheet()->getStyle('I'.$nextrow)->getNumberFormat()->setFormatCode('#,##0');				
$excel->getActiveSheet()->getStyle('A'.$nextrow.':H'.$nextrow)->getFont()->setBold(TRUE);
$excel->setActiveSheetIndex(0)->setCellValue('A'.$nextrow, "TOTAL"); 
$excel->getActiveSheet()->mergeCells('A'.$nextrow.':B'.$nextrow); 
$excel->getActiveSheet()->getStyle('A'.$nextrow)->getFont()->setBold(TRUE);
$excel->getActiveSheet()->getStyle('A'.$nextrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$nextrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

// isi untuk total di footer
$excel->setActiveSheetIndex(0)->setCellValue('C'.$nextrow, $totsaldoawal);
$excel->getActiveSheet()->getStyle('C'.$nextrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$excel->getActiveSheet()->getStyle('C'.$nextrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$excel->setActiveSheetIndex(0)->setCellValue('D'.$nextrow, $totpembayarankolektif);
$excel->getActiveSheet()->getStyle('D'.$nextrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$excel->getActiveSheet()->getStyle('D'.$nextrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$excel->setActiveSheetIndex(0)->setCellValue('E'.$nextrow, $totpembayaranmandiri);
$excel->getActiveSheet()->getStyle('E'.$nextrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$excel->getActiveSheet()->getStyle('E'.$nextrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$excel->setActiveSheetIndex(0)->setCellValue('F'.$nextrow, $totbiayagaji);
$excel->getActiveSheet()->getStyle('F'.$nextrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$excel->getActiveSheet()->getStyle('F'.$nextrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$excel->setActiveSheetIndex(0)->setCellValue('G'.$nextrow, $totpengeluaranlain);
$excel->getActiveSheet()->getStyle('G'.$nextrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$excel->getActiveSheet()->getStyle('G'.$nextrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$excel->setActiveSheetIndex(0)->setCellValue('H'.$nextrow, $totsaldoakhir);
$excel->getActiveSheet()->getStyle('H'.$nextrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$excel->getActiveSheet()->getStyle('H'.$nextrow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=".$namaFile.""); // Set nama file excel nya
header('Cache-Control: max-age=0');
$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$write->save('php://output');

?>