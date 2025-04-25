<?php
session_start();
if ($_SESSION['login']!= 1){
	header("location:index.html");

}
include 'content.php';
?>
