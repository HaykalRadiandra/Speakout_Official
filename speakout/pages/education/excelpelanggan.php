<?php
include "inc/inc.koneksi.php";

$text = "SELECT DATE_FORMAT(SYSDATE(),'%Y%m%d%H%i%s') AS tgllkp FROM DUAL";
$q = mysql_query($text);
$rs = mysql_fetch_array($q);
$tgllkp = $rs[tgllkp];

$namaFile = "daftarpelanggan_".$tgllkp.".xls";
 
// Function penanda awal file (Begin Of File) Excel 
function xlsBOF() {
echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
return;
}
 
// Function penanda akhir file (End Of File) Excel 
function xlsEOF() {
echo pack("ss", 0x0A, 0x00);
return;
}
 
// Function untuk menulis data (angka) ke cell excel 
function xlsWriteNumber($Row, $Col, $Value) {
echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
echo pack("d", $Value);
return;
}
 
// Function untuk menulis data (text) ke cell excel 
function xlsWriteLabel($Row, $Col, $Value ) {
$L = strlen($Value);
echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
echo $Value;
return;
}
 
// header file excel 
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0,
        pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
 
// header untuk nama file
header("Content-Disposition: attachment;filename=".$namaFile.""); 
header("Content-Transfer-Encoding: binary ");
 
// memanggil function penanda awal file excel
xlsBOF();
 
// mengisi pada cell A1 (baris ke-0, kolom ke-..)
xlsWriteLabel(0,0,"NO");               
xlsWriteLabel(0,1,"ID PELANGGAN");    
xlsWriteLabel(0,2,"NO PELANGGAN");    
xlsWriteLabel(0,3,"NAMA PELANGGAN");
xlsWriteLabel(0,4,"ALAMAT");   
xlsWriteLabel(0,5,"KODEWILAYAH"); 
xlsWriteLabel(0,6,"NO TELPON");
xlsWriteLabel(0,7,"JENIS TAGIHAN"); 
xlsWriteLabel(0,8,"TANGGAL DAFTAR"); 
 
// query menampilkan semua data 
$query = "SELECT idpelanggan,nopelanggan,namapelanggan,CONCAT(alamat,', RT ',rt,' / RW ',rw,', No.',norumah) AS alamat,kodewilayah,notelp,jnstagihan,tglentry FROM pelanggan WHERE onview=1 order by namapelanggan ";
$hasil = mysql_query($query);
 
// nilai awal untuk baris cell
$noBarisCell = 1;
 
// nilai awal untuk nomor urut data
$noData = 1;

while ($data = mysql_fetch_array($hasil)){
        // ubah value jnstagihan dari angka ke text supaya lebih mudah dipahami
        if ($data[jnstagihan]==1){
                $data[jnstagihan]="Kolektif";
        }else{
                $data[jnstagihan]="Mandiri";
        }

   xlsWriteNumber($noBarisCell,0,$noData);
   xlsWriteLabel($noBarisCell,1,$data[idpelanggan]);
   xlsWriteLabel($noBarisCell,2,$data[nopelanggan]);
   xlsWriteLabel($noBarisCell,3,$data[namapelanggan]);
   xlsWriteLabel($noBarisCell,4,$data[alamat]);
   xlsWriteLabel($noBarisCell,5,$data[kodewilayah]);
   xlsWriteLabel($noBarisCell,6,$data[notelp]);
   xlsWriteLabel($noBarisCell,7,$data[jnstagihan]);
   xlsWriteLabel($noBarisCell,8,$data[tglentry]);
 
   // increment untuk no. baris cell dan no. urut data
   $noBarisCell++;
   $noData++;
}
 
// memanggil function penanda akhir file excel
xlsEOF();
exit();
 
?>