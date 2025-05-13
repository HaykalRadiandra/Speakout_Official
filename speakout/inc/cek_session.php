<?php
session_start();
error_reporting(0);
//include "timeout.php";

if($_SESSION['login']==1){
	if(!'cek_login()'){
		$_SESSION['login'] = 0;
	}
}else{
  header('location:logout.php');
}
if (empty($_SESSION['username']) AND empty($_SESSION['passuser']) AND $_SESSION['login']==0){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses aplikasi ini, Anda harus login dahulu<br>";
  echo "<a href=../index.html><b>LOGIN</b></a></center>";
}
?>