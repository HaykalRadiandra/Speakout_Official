<?php
include "inc/inc.koneksi.php";

	function tgl_indo($tgl){
			$tanggal = substr($tgl,8,2);
			$bulan = getBulan(substr($tgl,5,2));
			$tahun = substr($tgl,0,4);
			return $tanggal.' '.$bulan.' '.$tahun;		 
	}	
	
	function bln_indo($tgl){			
			$bulan = getBulan(substr($tgl,5,2));
			$tahun = substr($tgl,0,4);
			return $bulan.' '.$tahun;		 
	}	

	function getBulan($bln){
			switch ($bln){
				case 1: 
					return "Januari";
					break;
				case 2:
					return "Februari";
					break;
				case 3:
					return "Maret";
					break;
				case 4:
					return "April";
					break;
				case 5:
					return "Mei";
					break;
				case 6:
					return "Juni";
					break;
				case 7:
					return "Juli";
					break;
				case 8:
					return "Agustus";
					break;
				case 9:
					return "September";
					break;
				case 10:
					return "Oktober";
					break;
				case 11:
					return "November";
					break;
				case 12:
					return "Desember";
					break;
			}
	} 
	
	function getHari($namahari){
	 // format : yyyy-mm-dd
	 
		$q	="SELECT DATE_FORMAT('$namahari','%w') AS harike FROM DUAL";
		$s 	= mysql_query($q);
		$r	= mysql_fetch_array($s);
		
		$harike = $r[harike];
	
		switch($harike){      
        	case 0 : {
                    $namahari='Ahad';					
                	}break;
        	case 1 : {
                    $namahari='Senin';
                	}break;
        	case 2 : {
                    $namahari='Selasa';
               		}break;
        	case 3 : {
                    $namahari='Rabu';
                	}break;
       		case 4 : {
                    $namahari='Kamis';
                	}break;
        	case 5 : {
                    $namahari="Jumat";
                	}break;
        	case 6 : {
                    $namahari='Sabtu';
                	}break;
        	default: {
                    $namahari='UnKnown';
              		}break;
    	}
		return $namahari;
	} 
	
	function getNamaHari($harix){
	
		switch($harix){      
        	case 1 : {
                    $getNamaHari='Ahad';					
                	}break;
        	case 2 : {
                    $getNamaHari='Senin';
                	}break;
        	case 3 : {
                    $getNamaHari='Selasa';
               		}break;
        	case 4 : {
                    $getNamaHari='Rabu';
                	}break;
       		case 5 : {
                    $getNamaHari='Kamis';
                	}break;
        	case 6 : {
                    $getNamaHari="Jumat";
                	}break;
        	case 7 : {
                    $getNamaHari='Sabtu';
                	}break;
        	default: {
                    $getNamaHari='';
              		}break;
    	}
		return $getNamaHari;
	} 

?>
