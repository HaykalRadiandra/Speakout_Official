<?php
session_start();
include "inc.koneksi.php";

function sukses_masuk($username,$pass){
	
	$login=mysqli_query($conn,"SELECT username,namalengkap,level,jabatan,kodearea,jnskelamin,DATE_FORMAT(tglgabung,'%m') AS blngabung,
						DATE_FORMAT(tglgabung,'%Y') AS thngabung 
						FROM userapp WHERE username='$username' AND password='$pass' AND blokir='N'");
	$ketemu=mysqli_num_rows($login);	
	if ($ketemu > 0){
		session_start();
		//include "timeout.php";		
		
		$r=mysqli_fetch_array($login);
		
		$_SESSION[perusahaan] 	= "Speakout";
		$_SESSION[idtrxprshn] 	= "TA";
		
		$_SESSION[namauser]     = $r[username];
		$_SESSION[namalengkap]  = $r[namalengkap];
		$_SESSION[leveluser]    = $r[level];	
		$_SESSION[posisi]		= $r[jabatan];		
		$_SESSION[kodearea]		= $r[kodearea];	
		
		if($r[blngabung]=='01'){
			$_SESSION[tgljoin]	= "Jan ".$r[thngabung];
		}else if($r[blngabung]=='02'){
			$_SESSION[tgljoin]	= "Peb ".$r[thngabung];
		}else if($r[blngabung]=='03'){		
			$_SESSION[tgljoin]	= "Mar ".$r[thngabung];
		}else if($r[blngabung]=='04'){
			$_SESSION[tgljoin]	= "Apr ".$r[thngabung];
		}else if($r[blngabung]=='05'){
			$_SESSION[tgljoin]	= "Mei ".$r[thngabung];
		}else if($r[blngabung]=='06'){
			$_SESSION[tgljoin]	= "Jun ".$r[thngabung];
		}else if($r[blngabung]=='07'){
			$_SESSION[tgljoin]	= "Jul ".$r[thngabung];
		}else if($r[blngabung]=='08'){
			$_SESSION[tgljoin]	= "Aug ".$r[thngabung];
		}else if($r[blngabung]=='09'){
			$_SESSION[tgljoin]	= "Sep ".$r[thngabung];
		}else if($r[blngabung]=='10'){	
			$_SESSION[tgljoin]	= "Okt ".$r[thngabung];
		}else if($r[blngabung]=='11'){
			$_SESSION[tgljoin]	= "Nop ".$r[thngabung];
		}else if($r[blngabung]=='12'){	
			$_SESSION[tgljoin]	= "Des ".$r[thngabung];
		}
		
		if($r[jnskelamin]==1){
			$_SESSION[pathavatar]   = "img/avatar5.png";
		}else{
			$_SESSION[pathavatar]   = "img/avatar6.jpg";
		}	

		// session timeout
		$_SESSION[login] = 1;
		timer();
			
		$ipaddress = 
		empty($_SERVER['HTTP_CLIENT_IP'])?(empty($_SERVER['HTTP_X_FORWARDED_FOR'])? $_SERVER['REMOTE_ADDR']:$_SERVER['HTTP_X_FORWARDED_FOR']):$_SERVER['HTTP_CLIENT_IP'];
		
		$sql	= "UPDATE userapp SET lastupdate=now(),lastlogin=now(),ipaddress='$ipaddress',online=0  WHERE username='$_SESSION[namauser]'";
		mysqli_query($conn,$sql);
		
		header('location:../media.php?mod=home');
	}
	return false;
}

function msg(){
  echo "<link href='../css/screen.css' rel='stylesheet' type='text/css'>
	  <link href='../css/reset.css' rel='stylesheet' type='text/css'>
	  <center><br><br><br><br><br><br>Maaf, silahkan cek kembali <b>User ID</b> dan <b>Password</b> Anda<br><br>Kesalahan $_SESSION[salah]<br>
	  <div> <a href='../index.html'><img src='../img/kunci.png' height=176 width=143></a></div>
	  <input type=button class='button buttonblue mediumbtn' value='KEMBALI' onclick=location.href='../index.html'></a></center>";
  return false;
}

function salah_blokir($username){
  echo "<link href='../css/screen.css' rel='stylesheet' type='text/css'>
	  <link href='../css/reset.css' rel='stylesheet' type='text/css'>
	  <center><br><br><br><br><br><br>User ID <b>$username</b> telah <b>TERBLOKIR</b><br><br>
	  <div> <a href='../index.html'><img src='../img/kunci.png'  height=176 width=143></a></div>
	  <input type=button class='button buttonblue mediumbtn' value='KEMBALI' onclick=location.href='../index.html'></a></center>";
  return false;
}

function salah_username($username){
  echo "<link href='../css/screen.css' rel='stylesheet' type='text/css'>
	  <link href='../css/reset.css' rel='stylesheet' type='text/css'>
	  <center><br><br><br><br><br><br>User ID <b>$username</b> tidak dikenal !!<br><br>
	  <div> <a href='../index.html'><img src='../img/kunci.png'  height=176 width=143></a></div>
	  <input type=button class='button buttonblue mediumbtn' value='KEMBALI' onclick=location.href='../index.html'></a></center>";	
  return false;
}

function username_aktif($username){
  echo "<link href='../css/screen.css' rel='stylesheet' type='text/css'>
	  <link href='../css/reset.css' rel='stylesheet' type='text/css'>
	  <center><br><br><br><br><br><br>Status User ID <b>$username</b> masih aktif digunakan!
	  <div> <a href='../index.html'><img src='../img/kunci.png'  height=176 width=143></a></div>
	  <input type=button class='button buttonblue mediumbtn' value='KEMBALI' onclick=location.href='../index.html'></a></center>";	
  return false;
}

function salah_password(){
  echo "<link href='../css/screen.css' rel='stylesheet' type='text/css'>
	  <link href='../css/reset.css' rel='stylesheet' type='text/css'>
	  <center><br><br><br><br><br><br>Maaf, silahkan cek kembali <b>Password</b> Anda<br><br>Kesalahan $_SESSION[salah]
	  <div> <a href='../index.html'><img src='../img/kunci.png'  height=176 width=143></a></div>
	  <input type=button class='button buttonblue mediumbtn' value='KEMBALI' onclick=location.href='../index.html'></a></center>";
   return false;
}

function blokir($username){
	$ipaddress = empty($_SERVER['HTTP_CLIENT_IP'])?(empty($_SERVER['HTTP_X_FORWARDED_FOR'])? $_SERVER['REMOTE_ADDR']:$_SERVER['HTTP_X_FORWARDED_FOR']):$_SERVER['HTTP_CLIENT_IP'];
	$sql	= "UPDATE userapp SET lastlogin=now(),ipaddress='$ipaddress',blokir='N' WHERE username='$username'";
	mysqli_query($conn,$sql);		
	session_start();
	session_destroy();
	return false;
}

?>