<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
       	<title>Etoya | Welcome</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
        <link href="css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="css/coba/style.css">		
		<link href="img/etoya1.png" rel="shortcut icon" type="image/png" />
		<script language="JavaScript">
			document.addEventListener("contextmenu", function(e){
				e.preventDefault();
			}, false);
		</script>
    </head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="#" class="logo">
                <?php				
					session_start();
					include "inc/inc.koneksi.php";
					include "inc/fungsi_hdt.php";
							
					$text = "SELECT UPPER(namaperusahaan) AS nama FROM perusahaan WHERE kodearea='$_SESSION[kodearea]'";																		
					$sql = mysql_query($text);
					$rec = mysql_fetch_array($sql);
					$namaarea = $rec['nama'];							
					
					echo $_SESSION[perusahaan];
				?>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
					
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span>
									<?php echo $_SESSION[namalengkap];?>
								<i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header bg-light-blue ">
                                    <img src="<?php echo $_SESSION[pathavatar];?>" class="img-circle" alt="User Image" />
                                    <p>									
										<?php 
										if(strtolower($_SESSION[namalengkap])=='administrator'){
											echo "Programmer";
										}else{	
											echo $_SESSION[posisi];
										}?>
                                        <small><?php echo $_SESSION[tgljoin] ?></small>
                                    </p>
                                </li>
								
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="?mod=chpass" class="btn btn-default btn-flat">Ganti Password</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="?mod=exit" class="btn btn-default btn-flat">Sign Out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <aside class="left-side sidebar-offcanvas">
                <section class="sidebar">
                    <form action="#" method="get" class="sidebar-form">                        
                    	<input type="text" class="form-control text-center" value="<?php echo $namaarea ?>" disabled="disabled"/>
                    </form>
					
                    <ul class="sidebar-menu">
						<?php
							$idinduk = "0";
							$queryx="SELECT b.link,b.menu_class,b.menu_caption,a.id_induk,a.id_anak FROM aksesmenu a 
									LEFT JOIN menu_induk b ON b.id_induk=a.id_induk 
									WHERE a.username='$_SESSION[namauser]' GROUP BY a.id_induk ORDER BY a.id_induk";																		
							$sql_ = mysql_query($queryx);
							while($menu = mysql_fetch_array($sql_)){
								if($menu[id_induk]==$idinduk){
									if($menu[id_anak]>0){
										 echo "
										 <li class='treeview active'>";
									}else{
										echo "
										 <li class='active'>";
									}	 									 
								}else{	 
									if($menu[id_anak]>0){
										echo "
										 <li class='treeview'>";
									}else{
										echo "<li>";
									}	 			
								}
								
								echo "									
									<a href='$menu[link]'>
										<i class='$menu[menu_class]'></i><span> $menu[menu_caption]</span>";
										if($menu[id_anak]>0){
											echo "
												<i class='fa fa-angle-left pull-right'></i>";
										}
								echo "</a>";
								
								if($menu[id_anak]>0){
									echo "
										<ul class='treeview-menu'>";					
										
									$sqlx = mysql_query("SELECT b.link,b.menu_class,b.menu_caption FROM aksesmenu a LEFT JOIN menu_anak b 
														ON b.id_anak=a.id_anak WHERE a.id_induk=$menu[id_induk] 
														AND a.username='$_SESSION[namauser]' ORDER BY a.id_anak");
									while($menu_a = mysql_fetch_array($sqlx)){
										echo "
											<li><a href='$menu_a[link]'><i class='$menu_a[menu_class]'></i> $menu_a[menu_caption]</a></li>";							
									}
									
									echo "</ul>";
								}
								echo " 									
								</li>";	
							}	
						?>						
						<li>
							<a href='?mod=exit'><i class='fa fa-sign-out'></i><span> Sign Out</span></a>
						</li>
                    </ul>
                </section>
            </aside>
			
            <aside class="right-side">               
                <!-- Main content -->
                <section class="content text-center">  
					<?php //echo '<img src="img/tulisan2.png"/>'?>
					<img src="img/fix.png"/>
					<!-- <img src="img/opsi_2.png"/>  -->
					<!-- <img src="img/opsi_3.png"/>  -->
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
			
        </div>

        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/AdminLTE/app.js" type="text/javascript"></script>  
		<script src="js/jquery.idle.js" type="text/javascript"></script>	
		<script>
			$(document).idle({
				onIdle: function(){
					window.location.assign('?mod=exit');	
				},
				idle: 720000
			});
			
			window.setTimeout("waktu()", 1000);
		
			function waktu() {
				var waktu = new Date();
				setTimeout("waktu()", 1000);
				var jam = waktu.getHours();
				var menit = waktu.getMinutes();
				var detik = waktu.getSeconds();
				
				if(detik==59){
					$.ajax({
						type: 'POST', 
						url: 'pages/info/xxxxx.php',
						success: function(data) {}
					});						
				}	
			}						
		</script>	

    </body>
</html>