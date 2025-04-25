<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Speakout | Change Password</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="css/ionicons.min.css" rel="stylesheet" type="text/css" />
		<link href="css/toastr.min.css" rel="stylesheet" type="text/css">
		<link href="js/assets/css/styles.css" rel="stylesheet" type="text/css" />
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />
		<link href="assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css"/>
		<link href="assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
		<link href="assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>		
		<link href="img/favicon.png" rel="shortcut icon" type="image/png" />
		<script language="JavaScript">
			document.addEventListener("contextmenu", function(e){
				e.preventDefault();
			}, false);
		</script>
    </head>
    <body class="skin-blue">
        <header class="header">
            <a href="index.html" class="logo">
			<?php				
					if (session_status() == PHP_SESSION_NONE) {
						session_start();
					}
					include __DIR__ . "/../../inc/inc.koneksi.php";
					include __DIR__ . "/../../inc/fungsi_hdt.php";
					
					echo $_SESSION['perusahaan'];
				?>	            
			</a>
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
									<?php echo $_SESSION['namalengkap'];?>
								<i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header bg-light-blue">
                                    <img src="<?php echo $_SESSION['pathavatar'];?>" class="img-circle" alt="User Image" />
                                    <p>									
										<?php echo $_SESSION['role'];?>
                                    </p>
                                </li>
								
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="?mod=changepass" class="btn btn-default btn-flat">Change Password</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="?mod=exit" class="btn btn-default btn-flat">Sign out</a>
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
                    <!-- search form -->
                    <form action="#" method="get" class="sidebar-form">                        
                    	<input type="text" class="form-control text-center" value="<?php echo $_SESSION['role'] ?>" disabled="disabled"/>
                    </form>
					
                    <ul class="sidebar-menu">
					<?php
							$idinduk = 9;
							$queryx = "SELECT id_induk, link, menu_class, menu_caption FROM menu_induk 
									   WHERE jenisuser = $_SESSION[jenisuser] OR jenisuser = 3 
									   GROUP BY id_induk 
									   ORDER BY id_induk";                                                                      
							
							$sql_ = mysqli_query($conn, $queryx);
							while ($menu = mysqli_fetch_array($sql_)) {
								$text2 = "SELECT id_anak FROM menu_anak 
										  WHERE id_induk = $menu[id_induk] 
										  AND (jenisuser = $_SESSION[jenisuser] OR jenisuser = 3) 
										  ORDER BY id_anak";
								$sql2 = mysqli_query($conn, $text2);
								$r = mysqli_fetch_array($sql2);
							
								// Perbaikan dengan isset()
								$id_anak = isset($r['id_anak']) ? $r['id_anak'] : 0;
							
								if ($menu['id_induk'] == $idinduk) {
									echo "<li class='treeview active'>";
								} else {     
									if ($id_anak > 0) {
										echo "<li class='treeview'>";
									} else {
										echo "<li>";
									}            
								}
							
								echo "                                    
									<a href='$menu[link]'>
										<i class='$menu[menu_class]'></i><span> $menu[menu_caption]</span>";
										if ($id_anak > 0) {
											echo "<i class='fa fa-angle-left pull-right'></i>";
										}
								echo "</a>";
							
								echo "<ul class='treeview-menu'>";                    
								$textx = "SELECT b.link, b.menu_class, b.menu_caption FROM menu_anak b 
										  WHERE b.id_induk = $menu[id_induk] 
										  AND (b.jenisuser = $_SESSION[jenisuser] OR b.jenisuser = 3) 
										  ORDER BY b.id_anak";
								$sqlx = mysqli_query($conn, $textx);
								while ($menu_a = mysqli_fetch_array($sqlx)) {
									echo "<li><a href='$menu_a[link]'><i class='$menu_a[menu_class]'></i> $menu_a[menu_caption]</a></li>";                            
								}
								
								echo "</ul>";
								echo "</li>";    
							}							
						?>				
						<li>
							<a href='?mod=exit'><i class='fa fa-sign-out'></i><span> Sign Out</span></a>	
						</li>		
                    </ul>
                </section>
            </aside>
			
            <aside class="right-side">
                <section class="content-header">
                    <h1>
                        User Profile
                        <small>Change Password</small>
                    </h1>
                </section>				
                <section class="content">
					<div class="box box-solid"><br>
						<div class="box-header" >
							<h4 style="font-family: 'Kaushan Script', cursive;font-size:18px;color:#CCCCCC" class="box-title">&nbsp; 
							Form change password</h4>							
						</div><hr>
						
						<div class="box-body">
							<form class="form-horizontal">
								<div class="form-group">									
									<label for="txtoldpassword" class="col-sm-2 control-label">Password lama</label>
									<div class="col-sm-6">
										<input type="password" id="txtoldpassword" class="form-control pull-right" value="" />
									</div>
									<div class="col-sm-2" id="keterangan">
										
									</div>	
								</div>
								<div class="form-group">									
									<label for="txtnewpassword" class="col-sm-2 control-label">Password baru</label>
									<div class="col-sm-6">
										<input type="password" id="txtnewpassword" class="form-control pull-right" value=""/>
									</div>	
								</div>
								<div class="form-group">									
									<label for="txtrepassword" class="col-sm-2 control-label">Ulangi Password</label>
									<div class="col-sm-6">
										<input type="password" id="txtrepassword" class="form-control pull-right" value=""/>
									</div>
									<div class="col-sm-2" id="ketrepassword">
										
									</div>	
								</div>							
								 <br>
							</form>	
						</div>
						<div class="box-footer text-right">
							<button type="button" class="btn btn-primary" onClick="window.location='?mod=<?php echo $_SESSION['role'];?>'">
								<i class="fa fa-reply-all"></i>&nbsp;kembali</button>
							<button type="button" class="btn btn-success pull-right" id="btnsubmit">
								<i class="fa fa-save (alias)"></i>&nbsp; Simpan</button>							
						</div>			
						<br>	
						<p id="infone" style="text-align:center"></p>		
						<br>	
       
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
		<script src="js/toastr.min.js"></script>
		<script src="js/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
		<script src="js/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
		<script src="js/changepass.js" type="text/javascript"></script> 
		<script src="js/ribuan.js" type="text/javascript"></script>
        <script src="js/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
		<script src="js/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
		<script src="js/assets/js/jquery.filedrop.js"></script>
        <script src="js/assets/js/script.js"></script>
        <script src="js/AdminLTE/app.js" type="text/javascript"></script>
		<script src="assets/global/plugins/select2/select2.min.js" type="text/javascript" ></script>
		<script src="assets/global/scripts/metronic.js" type="text/javascript"></script>
		<script src="js/components-dropdowns.js" type="text/javascript" ></script>
		<script src="js/jquery.idle.js" type="text/javascript"></script>	
		<script>
			jQuery(document).ready(function() { 
			   	Metronic.init(); 
			    ComponentsPickers.init();
			});

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