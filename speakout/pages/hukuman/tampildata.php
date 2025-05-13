<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_koma.php";
include "../../inc/fungsi_tanggal.php";

$cari           = mysqli_real_escape_string($conn, $_GET['cari'] ?? '');
$tglawal        = jin_date_sql($_GET['tglawal'] ?? '');
$tglakhir       = jin_date_sql($_GET['tglakhir'] ?? '');
$kelas          = $_GET['kelas'] ?? '';
$jurusan        = $_GET['jurusan'] ?? '';
$indeks         = $_GET['indeks'] ?? '';
$jnspelanggaran = $_GET['jnspelanggaran'] ?? '';
$status         = $_GET['status'] ?? '';
$terlapor       = $_GET['terlapor'] ?? '';

$username 	= $_SESSION['namauser'];
$kodeuser   = $_SESSION['kodeuser'];
$jenisuser  = $_SESSION['jenisuser'];

$dataPerPage = 10;
if (isset($_GET['page'])) {
    $noPage = $_GET['page'];
} else {
    $noPage = 1;    
}

$offset = ($noPage - 1) * $dataPerPage;

$text 	= "SELECT a.kodeaduan,a.kodepelapor,DATE_FORMAT(a.tglentry,'%d-%m-%Y') AS tglentry,b.nama AS namaterlapor,
        CONCAT(b.kelas,' ',c.nama,' ',b.indeks) AS kelas,d.nama AS jenispelanggaran,e.status,e.ket
        FROM pelanggaran a LEFT JOIN siswa b ON b.kodesiswa=a.kodeterlapor LEFT JOIN jurusan c ON c.kodejurusan=b.kodejurusan 
        LEFT JOIN jenispelanggaran d ON d.kodepelanggaran=a.kodepelanggaran LEFT JOIN hukuman e ON e.kodeaduan=a.kodeaduan WHERE a.onview=1 AND a.status=2 AND DATE_FORMAT(a.tglentry,'%Y-%m-%d')>='$tglawal' AND DATE_FORMAT(a.tglentry,'%Y-%m-%d')<='$tglakhir' ";

// jika user seorang siswa maka hanya tampilkan data yang dilaporkan oleh user tersebut
if($jenisuser==1 && $terlapor==1){
	$text 	= $text. "AND a.kodeterlapor='$kodeuser' ";
} elseif ($jenisuser==1 && $terlapor==2) {
    $text 	= $text. "AND a.kodepelapor='$kodeuser' ";
}

if(!empty($cari)) {
	$text .= " AND (e.ket LIKE '%$cari%' OR b.nama LIKE '%$cari%' OR d.nama LIKE '%$cari%') ";
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
	$text 	= $text. "AND e.status=$status ";
}

$text .= " ORDER BY a.tglentry desc LIMIT $offset, $dataPerPage";
$sql 	= mysqli_query($conn,$text);    
$jmlrec = mysqli_num_rows($sql);	

echo " 
    <table class='table table-hover'>
        <thead>
            <tr>
                <th style='width:10px;vertical-align:middle;' class='text-center third-bg'>NO</th>   
                <th style='width:120px;vertical-align:middle;' class='text-center third-bg'>TGL ADUAN</th> 
                <th style='width:150px;vertical-align:middle;' class='text-center third-bg'>NAMA PELANGGAR   </th>
                <th style='width:80px;vertical-align:middle;' class='text-center third-bg'>KELAS</th>    
                <th style='width:100px;vertical-align:middle;' class='text-center third-bg'>JENIS PELANGGARAN</th>    
                <th style='width:200px;vertical-align:middle;' class='text-center third-bg'>HUKUMAN</th>            
                <th style='width:100px;vertical-align:middle;' class='text-center third-bg'>STATUS</th>";        
                if($jenisuser==9){
                    echo"
                        <th style='width:180px;vertical-align:middle;' class='text-center third-bg'>AKSI</th>
                    ";
                }
    echo"
            </tr>
        </thead>
        <tbody>";
    
$no = 1 + $offset;
while($rec = mysqli_fetch_array($sql)){    
    $kodepelapor = $rec['kodepelapor'];
    $status = $rec['status'];
    
    if (empty($status)) {
        $status = 1;
    }

    if($status == 1){
        $statusnama = "Belum menerima hukuman";
    }elseif($status == 2){
        $statusnama = "Sudah menerima hukuman";
    }else {
        $statusnama = "Selesai hukuman";
    }
    
    echo " 
        <tr>
            <td class='text-center'>$no.</td> 
            <td class='text-center'>$rec[tglentry]</td>                 
            <td >
                <a class='text-decoration-none' href='javascript:void(0)' onClick=\"detail('{$rec['kodeaduan']}')\">$rec[namaterlapor]</a>
            </td>                
            <td class='text-center'>$rec[kelas]</td>                    
            <td >$rec[jenispelanggaran]</td> 
            <td >$rec[ket]</td>
            <td class='text-center'>$statusnama</td>";       
        // jika user guru maka user dapat memberikan hukuman
        if($jenisuser==9){
                    echo "
                    <td class='text-center'>";
                        if($status == 1){
                    echo "<a class='text-decoration-none' href='javascript:void(0)' onClick=\"berihukuman('$rec[kodeaduan]')\" >Beri Hukuman</a>";  
                        } else {
                    echo    "&nbsp;&nbsp;<a class='text-decoration-none' href='javascript:void(0)' onClick=\"berihukuman('$rec[kodeaduan]')\" >Edit Hukuman</a>  &nbsp; &nbsp;| &nbsp;
                            <a class='text-decoration-none' href='javascript:void(0)' onClick=\"selesaihukuman('$rec[kodeaduan]')\" >Selesai</a>&nbsp;&nbsp;";
                        }
            echo    "</td>";
        }
        echo "</tr>";

    $no++;                        
}
        
if($jmlrec < 5){
    while($no <= 5){
        echo "
            <tr>
                <td class='text-center'>$no.</td>
                <td ></td>
                <td ></td>
                <td ></td>                                                
                <td ></td>                                                
                <td ></td>
                <td ></td>";
            if($jenisuser==9){
                echo "<td ></td>";
            }
            echo"
            </tr>";    
        $no++;                        
    }                                
}

echo "   </tbody>
    </table>";
?>