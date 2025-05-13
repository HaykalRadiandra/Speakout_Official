<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_koma.php";
include "../../inc/fungsi_tanggal.php";

$cari           = mysqli_real_escape_string($conn, $_GET['cari'] ?? '');
$tglawal        = mysqli_real_escape_string($conn, jin_date_sql($_GET['tglawal'])) ?? '';
$tglakhir       = mysqli_real_escape_string($conn, jin_date_sql($_GET['tglakhir'])) ?? '';
$kelas          = $_GET['kelas'] ?? '';
$jurusan        = $_GET['jurusan'] ?? '';
$indeks         = $_GET['indeks'] ?? '';
$jnspelanggaran = $_GET['jnspelanggaran'] ?? '';
$status         = $_GET['status'] ?? '';

$username 	= $_SESSION['namauser'];
$kodeuser   = $_SESSION['kodeuser'];
$jenisuser  = $_SESSION['jenisuser'];

$dataPerPage = 5;
if (isset($_GET['page'])) {
    $noPage = $_GET['page'];
} else {
    $noPage = 1;    
}

$offset = ($noPage - 1) * $dataPerPage;

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

$text .= " ORDER BY a.tglentry desc LIMIT $offset, $dataPerPage";
$sql 	= mysqli_query($conn,$text);    
$jmlrec = mysqli_num_rows($sql);	

echo " 
    <table class='table table-hover'>
        <thead>
            <tr>
                <th style='width:50px;' class='third-bg align-middle text-center'>NO</th>   
                <th class='third-bg align-middle text-center'>TGL ADUAN</th> 
                <th class='third-bg align-middle text-center'>NAMA TERLAPOR</th>
                <th class='third-bg align-middle text-center'>KELAS</th>    
                <th class='third-bg align-middle text-center'>JENIS PELANGGARAN</th>    
                <th class='third-bg align-middle text-center'>STATUS</th>        
                <th class='third-bg align-middle text-center'>AKSI</th>";
        if($jenisuser==9){
            echo"
                <th style='width:150px;' class='third-bg align-middle text-center'>PERSETUJUAN</th>";
        }
    echo"
            </tr>
        </thead>
        <tbody>";        
    
$no = 1 + $offset;
while($rec = mysqli_fetch_array($sql)){    
    $kodepelapor = $rec['kodepelapor'];
    $status = $rec['status'];
    if($status == 1){
        $statusnama = "Menunggu Persetujuan";
    }elseif($status == 2){
        $statusnama = "Disetujui";
    }elseif($status == 3){
        $statusnama = "Ditolak";
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
            <td class='text-center'>$statusnama</td>";
        // jika user adalah pelapor dan seorang siswa maka user dapat melakukan hapus tetapi tidak bisa menyetujui
        if($kodeuser==$kodepelapor && $jenisuser==1){
            if($status==1){
            echo"                   
                <td class='text-center'>
                    &nbsp;&nbsp;<a class='text-decoration-none' href='javascript:void(0)' onClick=\"del('$rec[kodeaduan]')\" >Hapus</a>&nbsp;&nbsp;
                </td> ";
            }else{
                echo "
                    <td class='text-center'>
                        &nbsp;&nbsp;Hapus</a> &nbsp;&nbsp;
                    </td>";
            }
            echo "</tr>";
        // jika user adalah pelapor dan guru maka user dapat hapus serta melakukan persetujuan
        }elseif($kodeuser==$kodepelapor && $jenisuser==9){
                // jika status masih menunggu persetujuan maka dapat melakukan persetujuan
                if($status==1){
                    echo "
                    <td class='text-center'>
                        &nbsp;&nbsp;<a class='text-decoration-none' href='javascript:void(0)' onClick=\"del('$rec[kodeaduan]')\" >Hapus</a>&nbsp;&nbsp;
                    </td> 
                    <td class='text-center'>
                        &nbsp;&nbsp;<a class='text-decoration-none' href='javascript:void(0)' onClick=\"setujui('$rec[kodeaduan]')\" >Setujui</a> &nbsp; &nbsp;| &nbsp;
                        <a class='text-decoration-none' href='javascript:void(0)' onClick=\"tolak('$rec[kodeaduan]')\" >Tolak</a>&nbsp;&nbsp;
                    </td>";
                // jika status sudah disetujui atau ditolak maka tidak dapat melakukan persetujuan
                }else{
                    echo"    
                        <td class='text-center'>
                            &nbsp;&nbsp;Hapus</a> &nbsp;&nbsp;
                        </td>           
                        <td class='text-center'>
                            &nbsp;&nbsp;Setujui &nbsp;&nbsp;| &nbsp;
                            Tolak&nbsp;&nbsp;
                        </td>";
                } 
            echo "</tr>";
        // jika user bukan pelapor dan user seorang guru maka user hanya dapat melakukan persetujuan dan tidak bisa edit hapus
        }elseif($kodeuser!==$kodepelapor && $jenisuser==9){
            echo"   
                <td class='text-center'>
                    &nbsp;&nbsp;Hapus</a> &nbsp;&nbsp;
                </td>";
                // jika status masih menunggu persetujuan maka dapat melakukan persetujuan
                if($status==1){
                    echo "
                    <td class='text-center'>
                        &nbsp;&nbsp;<a class='text-decoration-none' href='javascript:void(0)' onClick=\"setujui('$rec[kodeaduan]')\" >Setujui</a> &nbsp;&nbsp;| &nbsp;
                        <a class='text-decoration-none' href='javascript:void(0)' onClick=\"tolak('$rec[kodeaduan]')\" >Tolak</a>&nbsp;&nbsp;
                    </td>";
                // jika status sudah disetujui atau ditolak maka tidak dapat melakukan persetujuan
                }else{
                    echo"                
                        <td class='text-center'>
                            &nbsp;&nbsp;Setujui &nbsp;&nbsp;| &nbsp;
                            Tolak&nbsp;&nbsp;
                        </td>";
                }
            echo "</tr>";
        }

    
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

echo "
    </table>";
?>