<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";
include "../../inc/fungsi_tanggal.php";

$text = "SELECT kodeguru,nama,nip FROM guru WHERE onview=1 ";

$query  = mysqli_query($conn,$text);      
$jmlrec = mysqli_num_rows($query);

echo " 
	<table class='table table-bordered' color>
		<tr style='background-color:#f9f9f9'>
			<th style='width:10px;vertical-align:middle;' class='text-center'>NO</th>	
			<th style='width:80px;vertical-align:middle;' class='text-center'>NAMA</th>
			<th style='vertical-align:middle;' class='text-center'>NIP</th>	
			<th style='width:120px;vertical-align:middle;' class='text-center'>AKSI</th>
		</tr>";		

$no = 1;
while($rec = mysqli_fetch_array($query)){

    echo "
		<tr>
			<td class='text-center'>$no.</td>  
			<td class='text-center'>{$rec['nama']}</td> 
            <td>{$rec['nip']}</td>									              	
			<td class='text-center'>
				<a href='javascript:void(0)' onClick=\"edit('{$rec['kodeguru']}')\">Edit</a> &nbsp;&nbsp;| &nbsp;
				<a href='javascript:void(0)' onClick=\"del('{$rec['kodeguru']}')\">Hapus</a>
			</td>
		</tr>";	

        $no++;						
}
  
if ($jmlrec < 5) {
	while ($no <= 5) {
		echo "
			<tr>
				<td style='color:#FFFFFF' class='text-center'>$no.</td>
				<td></td>												
				<td></td>
				<td></td>
			</tr>";	
		$no++;						
	}								
}

echo "</table>";
// Tutup koneksi
mysqli_close($conn);

?>