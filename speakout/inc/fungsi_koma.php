<?php
function format_komadua_str($angka){
	if(substr(number_format($angka,2),-2)>0){
		if(substr(number_format($angka,2),-1)>0){
			$hasil = number_format($angka,2);
		}else{
			$hasil = number_format($angka,1);
		}		
	}else{
		$hasil = number_format($angka,0);
	}	
	
	$panjang = strlen($hasil);
	$i=0;
	$jml_tmp='';
	while($i <= $panjang){
		$a = substr($hasil,$i,1);
		if($a=='.'){
			$a=',';		
		}elseif($a==','){	
			$a='.';		
		}	
		$jml_tmp = $jml_tmp . $a;
		$i++;
	}
	
	$hasil = $jml_tmp;
	
  	return $hasil;
}

function format_komadua_sql($angka){
	$panjang = strlen($angka);
	$i=0;
	$jml_tmp='';
	while($i <= $panjang){
		$a = substr($angka,$i,1);
		if($a=='.'){
			$a='';		
		}elseif($a==','){	
			$a='.';		
		}	
		$jml_tmp = $jml_tmp . $a;
		$i++;
	}
	
	$hasil = $jml_tmp;
	return $hasil;
}
	
?> 
