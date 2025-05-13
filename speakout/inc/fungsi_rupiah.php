<?php
function format_rupiah($angka){
  $rupiah=number_format($angka,2,',','.');
  return $rupiah;
}

function pembulatan_rupiah($angka){

	$angka = round($angka);
	$puluhan = substr($angka,-2);
	
	if($puluhan>50){
		$angka = $angka-$puluhan+100;
	}else{
		if($puluhan<=50 and $puluhan>0){
			$angka = $angka-$puluhan+50;
		}	
	}		

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

?> 
