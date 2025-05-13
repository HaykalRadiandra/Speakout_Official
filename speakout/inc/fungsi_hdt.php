<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "inc.koneksi.php";

function sukses_masuk($username,$pass,$jenis_akses){
	global $conn;

	$text   = "SELECT namalengkap,username,jenisuser,kodeuser,foto FROM userapp WHERE username='$username' AND password='$pass' AND onview=1";
	$login  = mysqli_query($conn,$text);
	$ketemu = mysqli_num_rows($login);
	
	if ($ketemu > 0){
		$r=mysqli_fetch_array($login);
		
		$_SESSION['perusahaan'] 	= "Speakout";
		$_SESSION['idtrxprshn'] 	= "TA";
		$_SESSION['namalengkap']    = $r['namalengkap'];
		$_SESSION['namauser']     	= $r['username'];
		$_SESSION['jenisuser']  	= $r['jenisuser'];
		$_SESSION['kodeuser']    	= $r['kodeuser'];	
		
		// Cek foto profil
		$_SESSION['pathavatar'] = empty($r['foto']) ? "img/profil/avatar5.png" : "img/profil/{$r['foto']}";

		// Cek role user
		$_SESSION['role'] = ($r['jenisuser'] == 1) ? "siswa" : (($r['jenisuser'] == 9) ? "guru" : null);

		// Cek jenis akses
		$_SESSION['jenisakses'] = ($jenis_akses == 1) ? "kesiswaan" : (($jenis_akses == 2) ? "bk" : null);
		
		$_SESSION['login'] = 1;
		
		$sql	= "UPDATE userapp SET online=1,lastlogin=NOW() WHERE username='$_SESSION[namauser]' AND kodeuser='$_SESSION[kodeuser]'";
		mysqli_query($conn,$sql);
		
		if($_SESSION['jenisuser']==1){
			header('location:../media.php?mod=siswa');
		}elseif($_SESSION['jenisuser']==9){
			header('location:../media.php?mod=guru');
		}else{
				echo "<link href='../css/screen.css' rel='stylesheet' type='text/css'>
				<link href='../css/reset.css' rel='stylesheet' type='text/css'>
				<center><br><br><br><br><br><br>jenis user tidak dikenal<br><br>
				<div> <a href='../index.html'><img src='../img/kunci.png' height=176 width=143></a></div>
				<input type=button class='button buttonblue mediumbtn' value='KEMBALI' onclick=location.href='../index.html'></a></center>";
			return false;
		}
	}
	return false;
}

function msg(){
	echo "<link href='../css/screen.css' rel='stylesheet' type='text/css'>
		  <link href='../css/reset.css' rel='stylesheet' type='text/css'>
		  <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css' rel='stylesheet'>
		  <style>
		  		  	body {
    			background-color: #F7F5FB;
			}
			.modern-btn {
			  background-color: #007bff;
			  border: none;
			  color: white;
			  padding: 12px 24px;
			  font-size: 16px;
			  border-radius: 8px;
			  cursor: pointer;
			  transition: 0.3s;
			}
			.modern-btn:hover {
			  background-color: #0056b3;
			}
			.big-icon {
			  font-size: 96px;
			  color: #007bff;
			}
		  </style>
		  <center><br><br><br><br><br><br>
		  Maaf, silahkan cek kembali <b>User ID</b> dan <b>Password</b> Anda<br><br>
		  Kesalahan ".$_SESSION['salah']."<br>
		  <div><i class='bi bi-lock-fill big-icon'></i></div><br>
		  <button class='modern-btn' onclick=\"location.href='../index.html'\">KEMBALI</button>
		  </center>";
	return false;
  }  
  
  function salah_blokir($username){
	echo "<link href='../css/screen.css' rel='stylesheet' type='text/css'>
		  <link href='../css/reset.css' rel='stylesheet' type='text/css'>
		  <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css' rel='stylesheet'>
		  <style>
		  		  	body {
    			background-color: #F7F5FB;
			}
			.modern-btn {
			  background-color: #007bff;
			  border: none;
			  color: white;
			  padding: 12px 24px;
			  font-size: 16px;
			  border-radius: 8px;
			  cursor: pointer;
			  transition: 0.3s;
			}
			.modern-btn:hover {
			  background-color: #0056b3;
			}
			.big-icon {
			  font-size: 96px;
			  color: #007bff;
			}
		  </style>
		  <center><br><br><br><br><br><br>
		  User ID <b>$username</b> telah <b>TERBLOKIR</b><br><br>
		  <div><i class='bi bi-lock-fill big-icon'></i></div><br>
		  <button class='modern-btn' onclick=\"location.href='../index.html'\">KEMBALI</button>
		  </center>";
	return false;
  }
  
  
  function salah_username($username){
	echo "<link href='../css/screen.css' rel='stylesheet' type='text/css'>
		  <link href='../css/reset.css' rel='stylesheet' type='text/css'>
		  <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css' rel='stylesheet'>
		  <style>
		  		  	body {
    			background-color: #F7F5FB;
			}
			.modern-btn {
			  background-color: #007bff;
			  border: none;
			  color: white;
			  padding: 12px 24px;
			  font-size: 16px;
			  border-radius: 8px;
			  cursor: pointer;
			  transition: 0.3s;
			}
			.modern-btn:hover {
			  background-color: #0056b3;
			}
			.big-icon {
			  font-size: 96px;
			  color: #007bff;
			}
		  </style>
		  <center><br><br><br><br><br><br>
		  User ID <b>$username</b> tidak dikenal !!<br><br>
		  <div><i class='bi bi-person-x-fill big-icon'></i></div><br>
		  <button class='modern-btn' onclick=\"location.href='../index.html'\">KEMBALI</button>
		  </center>";
	return false;
  }
  
  
  function username_aktif($username){
	echo "<link href='../css/screen.css' rel='stylesheet' type='text/css'>
		  <link href='../css/reset.css' rel='stylesheet' type='text/css'>
		  <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css' rel='stylesheet'>
		  <style>
		  		  	body {
    			background-color: #F7F5FB;
			}
			.modern-btn {
			  background-color: #007bff;
			  border: none;
			  color: white;
			  padding: 12px 24px;
			  font-size: 16px;
			  border-radius: 8px;
			  cursor: pointer;
			  transition: 0.3s;
			}
			.modern-btn:hover {
			  background-color: #0056b3;
			}
			.big-icon {
			  font-size: 96px;
			  color: #007bff;
			}
		  </style>
		  <center><br><br><br><br><br><br>
		  Status User ID <b>$username</b> masih aktif digunakan!<br><br>
		  <div><i class='bi bi-person-fill-lock big-icon'></i></div><br>
		  <button class='modern-btn' onclick=\"location.href='../index.html'\">KEMBALI</button>
		  </center>";
	return false;
  }
  
  
  function salah_password(){
	echo "<link href='../css/screen.css' rel='stylesheet' type='text/css'>
		  <link href='../css/reset.css' rel='stylesheet' type='text/css'>
		  <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css' rel='stylesheet'>
		  <style>
		  	body {
    			background-color: #F7F5FB;
			}
			.modern-btn {
			  background-color: #007bff;
			  border: none;
			  color: white;
			  padding: 12px 24px;
			  font-size: 16px;
			  border-radius: 8px;
			  cursor: pointer;
			  transition: 0.3s;
			}
			.modern-btn:hover {
			  background-color: #0056b3;
			}
			.big-icon {
			  font-size: 96px;
			  color: #007bff;
			}
		  </style>
		  <center><br><br><br><br><br><br>
		  Maaf, silahkan cek kembali <b>Password</b> Anda<br><br>
		  <div><i class='bi bi-key-fill big-icon'></i></div><br>
		  <button class='modern-btn' onclick=\"location.href='../index.html'\">KEMBALI</button>
		  </center>";
	return false;
  }
  

/*
function blokir($username){
	$ipaddress = empty($_SERVER['HTTP_CLIENT_IP'])?(empty($_SERVER['HTTP_X_FORWARDED_FOR'])? $_SERVER['REMOTE_ADDR']:$_SERVER['HTTP_X_FORWARDED_FOR']):$_SERVER['HTTP_CLIENT_IP'];
	$sql	= "UPDATE userapp SET lastlogin=now(),ipaddress='$ipaddress',blokir='N' WHERE username='$username'";
	mysqli_query($conn,$sql);		
	session_start();
	session_destroy();
	return false;
}
*/
?>