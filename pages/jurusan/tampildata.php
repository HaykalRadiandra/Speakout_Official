<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_hdt.php";

$kode = $_GET['kode'] ?? '';

$text 	= "SELECT kodejurusan,nama FROM jurusan WHERE onview=1 ";				
if ($kode) $text .= "AND nama LIKE '%$kode%' ";
$text 	= $text. "ORDER BY tglupdate desc";					

$sql 	= mysqli_query($conn,$text);	
$jmlrec	= mysqli_num_rows($sql);

echo "
	<table class='table table-hover table-bordered'>
		<tbody>";
			
		$no=1;
		while($rec = mysqli_fetch_array($sql)){		
					
			echo"
				<tr >
					<td style='width:50px'class='text-center'>$no</td>                 	
					<td class='text-decoration-none'>					
						<a class='text-decoration-none' href='javascript:void(0)' onClick=\"edit('$rec[kodejurusan]')\">$rec[nama]</a>	
					</td>
					<td style='width:100px' class='text-center d-print-none'>
						<a class='text-decoration-none' href='javascript:void(0)' onClick=\"del('$rec[kodejurusan]')\">[x]</a>
					</td>
                </tr>";	
				
			$no++;						
		}	
		
		if($jmlrec<5){
			while($no<=5){			
				echo "
					<tr>
						<td style='width:50px' class='text-center'>$no.</td>					
						<td ></td>	
						<td style='width:100px'></td>		
					</tr>";		
				$no++;									
			}								
		}
		
		
		
echo "
		</tbody>
	</table>
	";

?>