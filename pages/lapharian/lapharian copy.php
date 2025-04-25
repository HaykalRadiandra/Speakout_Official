<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Speakout | Laporan Harian</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />	
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />
		<link href="assets/global/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css"/>
    	<link href="img/favicon.png" rel="shortcut icon" type="image/png" />
    	<script language="JavaScript">
			document.addEventListener("contextmenu", function(e){
				e.preventDefault();
			}, false);
		</script>
    </head>
    <body class="skin-blue">
        <header class="header">
            <a href="#" class="logo">
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
										<small><?php //echo $_SESSION[tgljoin] ?></small>
                                    </p>
                                </li>
								
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="?mod=chpass" class="btn btn-default btn-flat">Change Password</a>
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
                    <form action="#" method="get" class="sidebar-form">                        
                    	<input type="text" class="form-control text-center" value="<?php echo $namaarea ?>" disabled="disabled"/>
                    </form>					
                    <ul class="sidebar-menu">
						<?php
							$idinduk = 8;
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
                        Laporan Harian
					</h1>
					<ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-files-o"></i>Laporan</a></li>
                        <li class="active">Laporan Harian</li>
                    </ol>
                </section>
				
                <section class="content">
                	<div class="box box-solid">
						<div class="box-header no-print" >							
							<div class="form-group"><br>
								<div class="col-lg-3">
									<div class="input-group input-medium date date-picker" data-date-format="dd-mm-yyyy" >
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>											
										<input type="text" id="tgltrx" style="background:#FFFFFF" class="form-control text-center" readonly />
									</div>	
								</div>
								<div class="col-lg-3">
									<div class="input-group input-medium date date-picker" data-date-format="dd-mm-yyyy" >
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>											
										<input type="text" id="tgltrx2" style="background:#FFFFFF" class="form-control text-center" readonly />				
									</div>	
								</div>
							</div>												
						</div><br>	
					</div>	
					<div class="box box-solid">	
						<div class="box-body"><br>
							<div class="row">								
								<div class="col-sm-12 invoice-col">
									<center>
										<strong>LAPORAN HARIAN</strong><br>
										<span id='lbltgltrx'>Tgl.</span>
									</center>
								</div>
							</div><br>
							<div class="table-responsive" id="tampildata"></div>
						</div><br>							
						<div class="box-footer no-print text-right">		
							<button type="button" class="btn btn-primary pull-left" onClick="window.location='?mod=home'">
								<i class="fa  fa-dot-circle-o"></i>&nbsp;Tutup</button>
								<!--
								<button class="btn btn-success" onClick="exportToExcel()"><i class="fa fa-cloud-download"></i>&nbsp; Export to Excel</button>
								<button class="btn btn-danger" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
								-->
								<button class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Export Excel Laporan Kas" onClick="printtoexcel()"><i class="fa fa-cloud-download"></i>&nbsp; Export to Excel</button>
								<button class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Print Laporan Kas" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
						</div>		
                </section>
            </aside>
        </div>  
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
		<script src="js/laphrn.js"></script>
        <script src="js/AdminLTE/app.js" type="text/javascript"></script>
		<script src="assets/admin/pages/scripts/components-pickers.js"></script>	
		<script src="assets/global/scripts/metronic.js" type="text/javascript"></script>
		<script src="js/jquery.idle.js" type="text/javascript"></script>	
		<script>
			jQuery(document).ready(function() { 
			   	Metronic.init(); 
			    ComponentsPickers.init();
			});

			// jika user tidak melakukan apapun selama 1 jam,maka set online=0
			$(document).idle({
				onIdle: function(){
					$.ajax({
						type: 'POST', 
						url: 'pages/info/offline.php',
						success: function(data) {}
					});		
				},
				idle: 3600000
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