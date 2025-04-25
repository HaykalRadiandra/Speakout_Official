<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

$kode = $_GET['kode'];
$tingkatx = $_GET['tingkatx'];

$text 	= "SELECT kodepelanggaran,nama,tingkat FROM jenispelanggaran WHERE onview=1 ";				

if(!empty($kode)) {
	$text 	= $text. "AND nama LIKE '%$kode%' ";
}

if(!empty($tingkatx)) {
	$text 	= $text. "AND tingkat=$tingkatx ";
}

$text 	= $text. "ORDER BY tglupdate desc";					

$sql 	= mysqli_query($conn,$text);	
$jmlrec	= mysqli_num_rows($sql);

echo "
	<table class='table table-hover table-bordered'>
		<tbody>";	
			
		$no=1;
		while($rec = mysqli_fetch_array($sql)){	
			$tingkat=$rec['tingkat'];

			if($tingkat==1){
				$tingkat="ringan";
			}else if($tingkat==2){
				$tingkat="sedang";
			}else if($tingkat==3){
				$tingkat="berat";
			}else{
				$tingkat="";
			}
					
			echo "
				<tr >
					<td style='width:50px'class='text-center satu'>$no</td>                 	
					<td class='satu'>					
						<a href='javascript:void(0)' class='text-decoration-none' onClick=\"edit('$rec[kodepelanggaran]')\">$rec[nama]</a>	
					</td>
					<td style='width:200px'class='text-center satu '>$tingkat</td>                 	
					<td style='width:100px' class='text-center satu d-print-none'>
						<a href='javascript:void(0)' class='text-decoration-none' onClick=\"del('$rec[kodepelanggaran]')\">[x]</a>
					</td>
                </tr>";	
				
			$no++;						
		}	
		
		if($jmlrec<5){
			while($no<=5){			
				echo "
					<tr>
						<td style='width:50px' class='text-center'>$no.</td>					
						<td class='d-print-none'></td>	
						<td style='width:200px;'></td>	
						<td style='width:100px;'></td>			
					</tr>";		
				$no++;									
			}								
		}
		
		
		
echo "
		</tbody>
	</table>
	";

?>