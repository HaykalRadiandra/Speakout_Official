<?php
/*
include "inc.koneksi.php";

session_start();
function timer(){
	$time=1000;
	$_SESSION[timeout]=time()+$time;
}
function cek_login(){
	$timeout=$_SESSION[timeout];
	if(time()<$timeout){
		$sql	= "UPDATE userapp SET online=1,lastupdate=SYSDATE() WHERE username='$_SESSION[namauser]'";
  		mysql_query($sql);
		timer();		
		return true;
	}else{
		$sql	= "UPDATE userapp SET online=0 WHERE username='$_SESSION[namauser]'";
  		mysql_query($sql);
		unset($_SESSION[timeout]);				
		return false;
	}
}
?>
