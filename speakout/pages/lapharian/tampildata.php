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
			COALESCE(a.totalpelanggaran, 0) AS totalpelanggaran,
			COALESCE(a.pelanggaranmenunggu, 0) AS pelanggaranmenunggu,
			COALESCE(a.pelanggarandisetujui, 0) AS pelanggarandisetujui,
			COALESCE(a.pelanggaranditolak, 0) AS pelanggaranditolak,
			COALESCE(b.totalkehilangan, 0) AS totalkehilangan,
			COALESCE(b.hilangbelum, 0) AS hilangbelum,
			COALESCE(b.hilangsudah, 0) AS hilangsudah,
			COALESCE(c.totalhukuman, 0) AS totalhukuman,
			COALESCE(c.hukumanbelum, 0) AS hukumanbelum,
			COALESCE(c.hukumansudah, 0) AS hukumansudah
		FROM 
			calendar xx
		LEFT JOIN (
			SELECT 
				DATE(tglentry) AS tglentry,
					SUM(IF(onview = 1, 1, 0)) AS totalpelanggaran,
					SUM(IF(STATUS = 1 AND onview = 1, 1, 0)) AS pelanggaranmenunggu,
					SUM(IF(STATUS = 2 AND onview = 1, 1, 0)) AS pelanggarandisetujui,
					SUM(IF(STATUS = 3 AND onview = 1, 1, 0)) AS pelanggaranditolak
			FROM pelanggaran 
			GROUP BY DATE(tglentry)
		) a ON a.tglentry = xx.datefield
		LEFT JOIN (
			SELECT 
				DATE(tglentry) AS tglentry,
					SUM(IF(onview = 1, 1, 0)) AS totalkehilangan,
					SUM(IF(STATUS = 1 AND onview = 1, 1, 0)) AS hilangbelum,
					SUM(IF(STATUS = 2 AND onview = 1, 1, 0)) AS hilangsudah
			FROM kehilangan 
			GROUP BY DATE(tglentry)
		) b ON b.tglentry = xx.datefield
		LEFT JOIN (
			SELECT 
				DATE(tglentry) AS tglentry,
					SUM(IF(onview = 1, 1, 0)) AS totalhukuman,
					SUM(IF(STATUS = 1 AND onview = 1, 1, 0)) AS hukumanbelum,
					SUM(IF(STATUS = 2 AND onview = 1, 1, 0)) AS hukumansudah
			FROM hukuman 
			GROUP BY DATE(tglentry)
		) c ON c.tglentry = xx.datefield
		WHERE 
			xx.datefield >= '$tgltrx1' AND xx.datefield <= '$tgltrx2'";

$text = $text." ORDER BY xx.datefield DESC";
$sql 	= mysqli_query($conn,$text);	
$jmlrec	= mysqli_num_rows($sql);

echo " 
	<table class='table table-hover'>
		<thead>
			<tr>
				<th rowspan=2 class='third-bg text-center align-middle'>No</th>
				<th rowspan=2 class='third-bg text-center align-middle'>Tanggal</th>
				<th colspan=4 class='third-bg text-center align-middle'>Pelanggaran</th>
				<th colspan=3 class='third-bg text-center align-middle'>Kehilangan</th>
				<th colspan=3 class='third-bg text-center align-middle'>Hukuman</th>	
			</tr>
			<tr>								
				<th class='third-bg text-center align-middle'>Menunggu Persetujuan</th>
				<th class='third-bg text-center align-middle'>Disetujui</th>
				<th class='third-bg text-center align-middle'>Ditolak</th>
				<th class='third-bg text-center align-middle'>Total</th>

				<th class='third-bg text-center align-middle'>Belum Ditemukan</th>
				<th class='third-bg text-center align-middle'>Sudah Ditemukan</th>
				<th class='third-bg text-center align-middle'>Total</th>

				<th class='third-bg text-center align-middle'>Belum Selesai</th>
				<th class='third-bg text-center align-middle'>Sudah Selesai</th>
				<th class='third-bg text-center align-middle'>Total</th>
			</tr>
		</thead>
		<tbody>";		
		
		$no=1;
		$saldoawal = 0;
		$grandpelanggaranmenunggu = 0;
		$grandpelanggarandisetujui = 0;
		$grandpelanggaranditolak = 0;
		$grandtotalpelanggaran = 0;

		$grandhilangbelum = 0;
		$grandhilangsudah = 0;
		$grandtotalkehilangan = 0;

		$grandhukumanbelum = 0;
		$grandhukumansudah = 0;
		$grandtotalhukuman = 0;

		while($rec = mysqli_fetch_array($sql)){		
			
			echo "
				<tr>
					<td class='text-center'>$no.</td>
					<td class='text-center'>$rec[tglview]</td>
					<td class='text-center'>$rec[pelanggaranmenunggu]</td>
					<td class='text-center'>$rec[pelanggarandisetujui]</td>
					<td class='text-center'>$rec[pelanggaranditolak]</td>
					<td class='text-center'>$rec[totalpelanggaran]</td>
					
					<td class='text-center'>$rec[hilangbelum]</td>
					<td class='text-center'>$rec[hilangsudah]</td>
					<td class='text-center'>$rec[totalkehilangan]</td>
				
					<td class='text-center'>$rec[hukumanbelum]</td>
					<td class='text-center'>$rec[hukumansudah]</td>
					<td class='text-center'>$rec[totalhukuman]</td>

				</tr>";	
				
			if($no==1){
				$totsaldoawal=$saldoawal;
			}	
			$grandpelanggaranmenunggu=$grandpelanggaranmenunggu+$rec['pelanggaranmenunggu'];
			$grandpelanggarandisetujui=$grandpelanggarandisetujui+$rec['pelanggarandisetujui'];	
			$grandpelanggaranditolak=$grandpelanggaranditolak+$rec['pelanggaranditolak'];
			$grandtotalpelanggaran=$grandtotalpelanggaran+$rec['totalpelanggaran'];

			$grandhilangbelum=$grandhilangbelum+$rec['hilangbelum'];
			$grandhilangsudah=$grandhilangsudah+$rec['hilangsudah'];			
			$grandtotalkehilangan=$grandtotalkehilangan+$rec['totalkehilangan'];	

			$grandhukumanbelum=$grandhukumanbelum+$rec['hukumanbelum'];
			$grandhukumansudah=$grandhukumansudah+$rec['hukumansudah'];			
			$grandtotalhukuman=$grandtotalhukuman+$rec['totalhukuman'];	

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
				<td class='text-center'><b>$grandpelanggaranmenunggu</b></td>
				<td class='text-center'><b>$grandpelanggarandisetujui</b></td>
				<td class='text-center'><b>$grandpelanggaranditolak</b></td>
				<td class='text-center'><b>$grandtotalpelanggaran</b></td>

				<td class='text-center'><b>$grandhilangbelum</b></td>
				<td class='text-center'><b>$grandhilangsudah</b></td>
				<td class='text-center'><b>$grandtotalkehilangan</b></td>

				<td class='text-center'><b>$grandhukumanbelum</b></td>
				<td class='text-center'><b>$grandhukumansudah</b></td>
				<td class='text-center'><b>$grandtotalhukuman</b></td>
			</tr>
		</tfoot>		
	</table>";	
?>