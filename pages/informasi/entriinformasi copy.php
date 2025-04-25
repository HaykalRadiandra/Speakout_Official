<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Speakout | Entri Informasi</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />
		<link href="js/assets/css/styles.css" rel="stylesheet" type="text/css" />
		<link href="assets/global/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css"/>
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
                    	<input type="text" class="form-control text-center" value="<?php echo $_SESSION['role'] ?>" disabled="disabled"/>
                    </form>					
                    <ul class="sidebar-menu">
						<?php
							$idinduk = 1;
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
                    <h1>Entri Informasi</h1>
					<ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-users"></i>Master Data</a></li>
                        <li class="active">Entri Informasi</li>
                    </ol>
                </section>				
                <section class="content">
					<div class="box box-solid" id="vwdata"><br>
						<div class="box-body">							
							<div class="form-group"><br>
								<div class="col-lg-6">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-search"></i>
										</div>
										<input type="text" class="form-control text-center" id="txtcari" placeholder="Search ..." />			
									</div>	
								</div>	
								<div class="col-lg-6 text-right">		
									<span class="help-block"><a href="#" id="entriplgn">[&nbsp; Entri Informasi Baru &nbsp;]</a></span>
								</div>
							</div>												
						</div>
						
						<br>

						<div class="box-body">							
							<div class="col-lg-12">					
								<div class="table-responsive" id="tampildata"></div>								
							</div>
							<div class="col-xs-12"></div>
							<div class="col-xs-12"></div>
							<div class="col-lg-12 text-right" id="paging"></div>		
						</div>
						<div class="row">
							<div class="col-sm-12 text-center" style="color:#f00;font-weight:bold;"></div>
						</div>
						<br>
						<div class="box-body">
							<div class="box-footer no-print text-right">		
								<button type="button" class="btn btn-primary pull-left" onClick="window.location='?mod=<?php echo $_SESSION['role'];?>'">
									<i class="fa  fa-dot-circle-o"></i>&nbsp;Tutup</button>
								<button class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Export Excel Data informasi" onClick="printtoexcel()"><i class="fa fa-cloud-download"></i>&nbsp; Export to Excel</button>
								<button class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Print Data informasi" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
							</div>
						</div>
					</div>
					
					<!-- Entri Data -->
					<div class="box box-solid" id="endata" style="display: none;"><br>
						<div class="box-header" >
							<h4 style="font-family: 'Kaushan Script', cursive;font-size:18px;color:#CCCCCC" class="box-title">&nbsp; Form Informasi Baru</h4>							
						</div><hr>
						<br>
						<div class="box-body">
							<form class="form-horizontal">	
								<div class="form-group">									
									<label class="col-sm-3 control-label">judul</label>
									<div class="col-sm-4">
										<input type="text" id="txtjudul" class="form-control text-center" placeholder="Tidak boleh spasi" maxlength="200" />
									</div>	
								</div>
								<div class="form-group">	
									<label for="txtket" class="col-sm-3 control-label">Keterangan</label>	
									<div class="col-sm-8">										
										<textarea rows='9' class="form-control" id='txtket' maxlength="1000" autocomplete="off"></textarea>
										<input type="hidden" class="form-control" id="txtkodeinformasi"/>																		
									</div>	
								</div>	
								<div class="form-group">	
									<label for="txtfoto" class="col-sm-3 control-label">Lampiran</label>	
									<div class="col-sm-3">										
										<input type="file" id="txtfoto" accept=".jpg, .jpeg, .png">
									</div>	
									<p id="notefoto" style="color: red;"></p>
								</div>
								<div class="form-group" id="vwfoto">	
									<label for="txtfotox" class="col-sm-3 control-label"></label>	
									<div class="col-sm-3">										
										<img id="txtfotox" src="" style="width:350px;height:350px">
									</div>								
								</div>
							</form>
						</div>						
						<br>						
						<div class="row">
							<div class="col-xs-11 text-center">
								<label id="warningx" style="color:#FF0000"></label>
							</div>
						</div>
						<div class="box-body">
							<div class="box-footer" >	
								<button type="button" class="btn btn-primary" onClick="window.location='?mod=entriinformasi'">
									<i class="fa fa-reply-all"></i>&nbsp;kembali</button>
								<button type="button" id="btnconfirm" class="btn btn-success pull-right">
									<i class="fa fa-save (alias)"></i>&nbsp; Simpan</button>							
							</div>
						</div>							
					</div>
                </section>
            </aside>
        </div>  

		<!-- Muncul Pop Up hapus dan simpan -->
		<div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Konfirmasi</h4>
					</div>
					<div class="modal-body text-center">
						<h3>Yakin akan disimpan ?</h3>											
					</div>
					<div class="modal-footer clearfix" id="btnexec">
						<button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Tidak</button>
						<button type="button" class="btn btn-primary" id="btnsave"><i class="fa fa-check"></i> Ya</button>
					</div>
					<div class="overlay" id="overlayx"></div>
					<div class="loading-img" id="loading-imgx"></div>						
                </div>
            </div>
        </div>
        <div class="modal fade" id="confirmhapus-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Konfirmasi</h4>
					</div>
					<input type="hidden" id="txtkodeinformasi"/>
					<div class="modal-body text-center">
						<h3>Yakin akan dihapus ?</h3>							
					</div>
					<div class="modal-footer clearfix" id="btnexec">						
						<button type="button" class="btn btn-primary" id="btnhapus"><i class="fa fa-check"></i> Ya</button>
						<button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Tidak</button>
					</div>					
                </div>
            </div>
        </div>
		
		 <div class="modal fade" id="info-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Informasi</h4>
					</div>
					<div class="modal-body text-center" id="infone"></div>
					<div class="modal-footer text-center">
						<button type="button" class="btn btn-primary" id="btnok" data-dismiss="modal"><i class="fa fa-check"></i> OK</button>
					</div>					
                </div>
            </div>
        </div> 
		
		
        <script src="js/jquery.min.js"></script>
		<script src="js/jquery-inputmask-326.js"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
		<script src="js/newinformasi.js" type="text/javascript"></script>	
		<script src="js/ribuan.js" type="text/javascript"></script>
        <script src="js/AdminLTE/app.js" type="text/javascript"></script>	
		<script src="assets/admin/pages/scripts/components-pickers.js"></script>	
		<script src="assets/global/plugins/select2/select2.min.js" type="text/javascript" ></script>
		<script src="assets/global/scripts/metronic.js" type="text/javascript"></script>
		<script src="js/components-dropdowns.js" type="text/javascript" ></script>	
		<script src="js/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
		<script src="js/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
		<script src="js/jquery.idle.js" type="text/javascript"></script>	
		<script>

			jQuery(document).ready(function() { 
			   	Metronic.init(); 
			    ComponentsPickers.init();
				ComponentsDropdowns.init();	
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