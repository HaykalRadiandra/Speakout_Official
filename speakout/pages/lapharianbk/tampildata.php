<?php
session_start();
include "../../inc/inc.koneksi.php";
include "../../inc/fungsi_tanggal.php";
include "../../inc/fungsi_koma.php";
include '../../inc/fungsi_hdt.php';

$tgltrx1 = jin_date_sql($_GET['tgltrx']);
$tgltrx2 = jin_date_sql($_GET['tgltrx2']);

$text = "SELECT 
			xx.datefield,
			DATE_FORMAT(xx.datefield, '%d/%m/%Y') AS tglview,
			SUM(IF(a.kategori IS NOT NULL AND a.onview = 1, 1, 0)) AS totalkategori,
			SUM(IF(a.kategori = 1 AND a.onview = 1, 1, 0)) AS konsultasi,
			SUM(IF(a.kategori = 2 AND a.onview = 1, 1, 0)) AS konseling,
			SUM(IF(a.kategori = 3 AND a.onview = 1, 1, 0)) AS coaching,
			
			SUM(IF(a.metode IS NOT NULL AND a.onview = 1, 1, 0)) AS totalmetode,
			SUM(IF(a.metode = 1 AND a.onview = 1, 1, 0)) AS chat,
			SUM(IF(a.metode = 2 AND a.onview = 1, 1, 0)) AS temu,
			
			SUM(IF(a.status IS NOT NULL AND a.onview = 1, 1, 0)) AS totalstatus,
			SUM(IF(a.status = 1 AND a.onview = 1, 1, 0)) AS diproses,
			SUM(IF(a.status = 2 AND a.onview = 1, 1, 0)) AS selesai

		FROM calendar xx
		LEFT JOIN cerita a ON a.tglajuan = xx.datefield
		WHERE 
			xx.datefield >= '$tgltrx1' AND xx.datefield <= '$tgltrx2'";

$text = $text." GROUP BY xx.datefield ORDER BY xx.datefield DESC";
$sql 	= mysqli_query($conn,$text);	
$jmlrec	= mysqli_num_rows($sql);

echo " 
	<table class='table table-hover'>
		<thead>
			<tr>
				<th rowspan=2 class='third-bg text-center align-middle'>No</th>
				<th rowspan=2 class='third-bg text-center align-middle'>Tanggal Ajuan</th>
				<th colspan=4 class='third-bg text-center align-middle'>Kategori</th>
				<th colspan=3 class='third-bg text-center align-middle'>Metode</th>
				<th colspan=3 class='third-bg text-center align-middle'>Status</th>	
			</tr>
			<tr>								
				<th class='third-bg text-center align-middle'>Konsultasi</th>
				<th class='third-bg text-center align-middle'>Konseling</th>
				<th class='third-bg text-center align-middle'>Coaching</th>
				<th class='third-bg text-center align-middle'>Total</th>

				<th class='third-bg text-center align-middle'>Chat (online)</th>
				<th class='third-bg text-center align-middle'>Temu (Offline)</th>
				<th class='third-bg text-center align-middle'>Total</th>

				<th class='third-bg text-center align-middle'>Diproses</th>
				<th class='third-bg text-center align-middle'>Selesai</th>
				<th class='third-bg text-center align-middle'>Total</th>
			</tr>
		</thead>
		<tbody>";		
		
		$no=1;
		$grandkonsultasi = 0;
		$grandkonseling = 0;
		$grandcoaching = 0;
		$grandtotalkategori = 0;

		$grandchat = 0;
		$grandtemu = 0;
		$grandtotalmetode = 0;

		$granddiproses = 0;
		$grandselesai = 0;
		$grandtotalstatus = 0;

		while($rec = mysqli_fetch_array($sql)){		
			
			echo "
				<tr>
					<td class='text-center'>$no.</td>
					<td class='text-center'>$rec[tglview]</td>
					<td class='text-center'>$rec[konsultasi]</td>
					<td class='text-center'>$rec[konseling]</td>
					<td class='text-center'>$rec[coaching]</td>
					<td class='text-center'>$rec[totalkategori]</td>
					
					<td class='text-center'>$rec[chat]</td>
					<td class='text-center'>$rec[temu]</td>
					<td class='text-center'>$rec[totalmetode]</td>
				
					<td class='text-center'>$rec[diproses]</td>
					<td class='text-center'>$rec[selesai]</td>
					<td class='text-center'>$rec[totalstatus]</td>

				</tr>";	
				
			$grandkonsultasi += $rec['konsultasi'];
			$grandkonseling += $rec['konseling'];
			$grandcoaching += $rec['coaching'];
			$grandtotalkategori += $rec['totalkategori'];

			$grandchat += $rec['chat'];
			$grandtemu += $rec['temu'];
			$grandtotalmetode += $rec['totalmetode'];

			$granddiproses += $rec['diproses'];
			$grandselesai += $rec['selesai'];
			$grandtotalstatus += $rec['totalstatus'];

			$no++;
		}	
		
		if($jmlrec<1){
			while($no<1){
				echo "
					<tr>
						<td class='text-center'>$no.</td>											
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>												
						<td ></td>
						<td ></td>			
						<td ></td>
						<td ></td>												
						<td ></td>
						<td ></td>				
					</tr>";	
				$no++;						
			}								
		}	
		
echo "	</tbody>
		<tfoot>	
			<tr>
				<td colspan='2' style='vertical-align:middle;' class='text-center'><b>Grand Total</b></td>
				<td class='text-center'><b>$grandkonsultasi</b></td>
				<td class='text-center'><b>$grandkonseling</b></td>
				<td class='text-center'><b>$grandcoaching</b></td>
				<td class='text-center'><b>$grandtotalkategori</b></td>

				<td class='text-center'><b>$grandchat</b></td>
				<td class='text-center'><b>$grandtemu</b></td>
				<td class='text-center'><b>$grandtotalmetode</b></td>

				<td class='text-center'><b>$granddiproses</b></td>
				<td class='text-center'><b>$grandselesai</b></td>
				<td class='text-center'><b>$grandtotalstatus</b></td>
			</tr>
		</tfoot>		
	</table>";	
?>