<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
		<link href="favicon/favicon.ico" rel="shortcut icon" type="image/png" />
		<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="css/bootstrap_v5.css">
		<link rel="stylesheet" href="css/bootstrap-icons/font/bootstrap-icons.css">
		<link rel="stylesheet" href="css/native_styling/setting_user.css">
		<title>SPEAKOUT | Setting User</title>
    </head>
    <body>
        <div class="d-flex" id="wrapper">
        <!-- Sidebar starts here -->
        <div class="bg-white shadow" style="--bs-box-shadow: -5px -15px 10px 0px rgba(0, 0, 0, 0.5);" id="sidebar-wrapper">
            <div class="d-flex align-items-center justify-content-center sidebar-heading text-center py-3 primary-text fw-bold text-uppercase border-bottom">
                <!-- <span class="bi fs-1 bi-speedometer me-2"></span> -->
				<?php				
					if (session_status() == PHP_SESSION_NONE) {
						session_start();
					}
					include __DIR__ . "/../../inc/inc.koneksi.php";
					include __DIR__ . "/../../inc/fungsi_hdt.php";
				?>
                <p class="m-0 fs-4"><?php echo $_SESSION['perusahaan'];?></p>
            </div>

            <div class="list-group list-group-flush my-2">
			<?php
					$idinduk = 9;
					$queryx = "SELECT id_induk, link, menu_class, menu_caption FROM menu_induk 
							WHERE jenisuser = $_SESSION[jenisuser] OR jenisuser = 3 
							GROUP BY id_induk 
							ORDER BY id_induk";       
					$sql_ = mysqli_query($conn, $queryx);
					
					while ($menu = mysqli_fetch_array($sql_)) {
						// Filter berdasarkan jenisakses
						if (
							($_SESSION['jenisakses'] == 'bk' && in_array($menu['id_induk'], [2, 3])) ||
							($_SESSION['jenisakses'] == 'kesiswaan' && $menu['id_induk'] == 4)
						) {
							continue; // Skip menu ini
						}
						
						$text2 = "SELECT id_anak FROM menu_anak 
								WHERE id_induk = $menu[id_induk] 
								AND (jenisuser = $_SESSION[jenisuser] OR jenisuser = 3) 
								ORDER BY id_anak";
						$sql2 = mysqli_query($conn, $text2);
						$r = mysqli_fetch_array($sql2);
						
						$id_anak = isset($r['id_anak']) ? $r['id_anak'] : 0;

						if ($id_anak > 0) {
								echo"	<div class='d-flex px-3 align-items-center list-group-item list-group-item-action bg-transparent text-body-tertiary fs-6 fw-semibold sidebar-active'>";
										if ($menu['id_induk'] == $idinduk) {
									echo "	<div class='w-100 d-flex align-items-center justify-content-between py-1 px-3 rounded-3 hoverr activedd' data-bs-toggle='collapse' data-bs-target='#collapse-$menu[id_induk]' role='button' aria-expanded='true'>";
										} else {
									echo "	<div class='w-100 d-flex align-items-center justify-content-between py-1 px-3 rounded-3 hoverr sidebar-item' data-bs-toggle='collapse' data-bs-target='#collapse-$menu[id_induk]' role='button' aria-expanded='false'>";
										}
										echo "	<span class='d-flex align-items-center'><i class='$menu[menu_class] fs-4 me-2'></i>$menu[menu_caption]</span>
												<i class='bi bi-chevron-down toggle-icon'></i>
											</div>
										</div>";
							
							if ($menu['id_induk'] == $idinduk) {
								echo "<div id='collapse-$menu[id_induk]' class='collapse show menu-collapse'>";
							} else {
							echo "<div id='collapse-$menu[id_induk]' class='collapse menu-collapse'>";
							}
							
							$textx = "SELECT b.id_anak, b.link, b.menu_class, b.menu_caption FROM menu_anak b 
									WHERE b.id_induk = $menu[id_induk] 
									AND (b.jenisuser = $_SESSION[jenisuser] OR b.jenisuser = 3) 
									ORDER BY b.id_anak";
							$sqlx = mysqli_query($conn, $textx);
							while ($menu_a = mysqli_fetch_array($sqlx)) {
								if ($_SESSION['jenisakses'] == 'bk' && $menu_a['id_anak'] == 104) {
									continue; // Lewatkan, tidak ditampilkan
								}
								if ($menu_a['id_anak'] == 901) {
									echo "<a href='$menu_a[link]' class='d-flex px-3 align-items-center list-group-item list-group-item-action bg-transparent text-body-tertiary fs-6 active fw-semibold dropdown-item'>
											<div class='w-100 d-flex align-items-center py-1 px-3 rounded-3'>
												<span class='$menu_a[menu_class] ms-2 text-wrap dropdown-active' style='font-size:14px;'> $menu_a[menu_caption]</span>
											</div>
										</a>";   
								} else {
									echo "<a href='$menu_a[link]' class='hover d-flex px-3 align-items-center list-group-item list-group-item-action bg-transparent text-body-tertiary fs-6 active fw-semibold dropdown-item'>
											<div class='w-100 d-flex align-items-center py-1 px-3 rounded-3'>
												<span class='$menu_a[menu_class] ms-2 text-wrap' style='font-size:14px;'> $menu_a[menu_caption]</span>
											</div>
										</a>";   
								}                             
							}
							echo "</div>";
						} else {
							echo" <a href='$menu[link]' class='d-flex px-3 align-items-center list-group-item list-group-item-action bg-transparent text-body-tertiary fs-6 fw-semibold'>";
							if ($menu['id_induk'] == $idinduk) {
								echo "<div class='w-100 d-flex align-items-center py-1 px-3 rounded-3 activedd'>";
							} else {
								echo "<div class='w-100 d-flex align-items-center py-1 px-3 rounded-3 hoverr'>";
							}
								echo " <span class='$menu[menu_class] fs-4 me-2'></span>$menu[menu_caption]
									</div>
								</a>";
						}
					}
				?>
			</div>

         </div>
         <!-- Sidebar ends here -->

        <!-- Page nav content starts here -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent px-4 py-4 py-md-2">
                <div class="d-flex align-items-center">
                    <i class="bi bi-text-left fs-2 me-3" id="menu-toggle"></i>
                    <h2 class="fs-3 m-0  primary-text">Setting User</h2>
                </div>

                <button class="navbar-toggler border-0 p-1 navbar-custom rounded-1 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<i class="bi bi-caret-up-square lh-1 fs-3"></i>
				</button>

                <div class="collapse navbar-collapse gap-2" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="d-flex gap-1 align-items-center nav-link text-dark fs-6 fw-bold" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="bi bi-person-circle fs-4 me-2"></span>
                                <p class="mb-0 fw-normal"><?php echo $_SESSION['namauser'] ?></p>
                            </a>
                            <!-- <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                            </ul> -->
                        </li>
                    </ul>
                    <a href="?mod=exit"><span class="bi bi-box-arrow-right fs-4 text-danger fw-bolder"></span></a>
                </div>
            </nav>
            <!-- Page nav content ends here -->

            <!-- Page main content starts here -->
            <div class="container-fluid p-4">
                <div class="row m-0 mb-3" id="vwguru">
					<div class="d-flex p-0 justify-content-evenly mb-4 flex-wrap">
						<div class="col-12 col-sm-5">
							<label for="txtusernameguru" class="form-label">Username</label>
							<input type="text" class="form-control" id="txtusernameguru">
						</div>
						<div class="col-12 col-sm-5">
							<label for="txtroleguru" class="form-label">Role</label>
							<input type="text" class="form-control" id="txtroleguru">
						</div>
					</div>
					<div class="d-flex p-0 justify-content-evenly mb-4 flex-wrap">
						<div class="col-12 col-sm-5">
							<label for="txtnamaguru" class="form-label">Nama Guru</label>
							<input type="text" class="form-control" id="txtnamaguru">
						</div>
						<div class="col-12 col-sm-5">
							<label for="txtnip" class="form-label">NIP</label>
							<input type="text" class="form-control" id="txtnip">
						</div>
					</div>
					<div class="d-flex p-0 justify-content-evenly mb-4 flex-wrap">
						<div class="col-12 col-sm-5">
							<label for="txtalamatguru" class="form-label">Alamat</label>
							<textarea rows="1" class="form-control input-field" id="txtalamatguru" maxlength="200" autocomplete="off"></textarea>
						</div>
						<div class="col-12 col-sm-5">
							<label for="txtnotelpguru" class="form-label">No Telp</label>
							<div class="input-group">
								<span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
								<input type="text" class="form-control" id="txtnotelpguru">
							</div>
						</div>
					</div>

					<div class="row mt-3">
						<div class="col-12 text-center mb-3 p-0">
							<label id="warningx" class="text-danger fw-medium"></label>
						</div>
					</div>

					<hr>
					<div class="p-0">
						<div class="d-flex justify-content-between">
							<button type="button" class="btn btn-primary me-2" onClick="window.location='?mod=<?php echo $_SESSION['role'];?>'">
								<i class="bi bi-x-circle-fill"></i>&nbsp;Tutup
							</button>
							<button type="button" class="btn btn-success" onClick="window.location='?mod=chpass'">
								<i class="bi bi-key-fill"></i>&nbsp; Ubah Password
							</button>
						</div>
					</div>
                </div>

                <div class="row m-0 mb-3" id="vwsiswa">
					<div class="d-flex p-0 justify-content-evenly mb-4 flex-wrap">
						<div class="col-12 col-sm-5">
							<label for="txtusernamesiswa" class="form-label">Username</label>
							<input type="text" class="form-control" id="txtusernamesiswa">
						</div>
						<div class="col-12 col-sm-5">
							<label for="txtrolesiswa" class="form-label">Role</label>
							<input type="text" class="form-control" id="txtrolesiswa">
						</div>
					</div>
					<div class="d-flex p-0 justify-content-evenly mb-4 flex-wrap">
						<div class="col-12 col-sm-5">
							<label for="txtnamasiswa" class="form-label">Nama Siswa</label>
							<input type="text" class="form-control" id="txtnamasiswa">
						</div>
						<div class="col-12 col-sm-5">
							<label for="txtkelaslengkap" class="form-label">Kelas Lengkap</label>
							<input type="text" class="form-control" id="txtkelaslengkap">
						</div>
					</div>
					<div class="d-flex p-0 justify-content-evenly mb-4 flex-wrap">
						<div class="col-12 col-sm-5">
							<label for="cbokelas" class="form-label">Kelas</label>
							<input type="text" class="form-control" id="cbokelas">
						</div>
						<div class="col-12 col-sm-5">
							<label for="cbojurusan" class="form-label">Jurusan</label>
							<input type="text" class="form-control" id="cbojurusan">
						</div>
					</div>
					<div class="d-flex p-0 justify-content-evenly mb-4 flex-wrap">
						<div class="col-12 col-sm-5">
							<label for="cboindeks" class="form-label">Indeks</label>
							<input type="text" class="form-control" id="cboindeks">
						</div>
						<div class="col-12 col-sm-5">
							<label for="txtnotelpsiswa" class="form-label">No Telp</label>
							<div class="input-group">
								<span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
								<input type="text" class="form-control" id="txtnotelpsiswa">
							</div>
						</div>	
					</div>
					<div class="d-flex p-0 justify-content-evenly mb-4 flex-wrap">
						<div class="col-12 col-sm-5">
							<label for="txtalamatsiswa" class="form-label">Alamat</label>
							<textarea rows="1" class="form-control" id="txtalamatsiswa" maxlength="200" autocomplete="off"></textarea>
						</div>
						<div class="col-12 col-sm-5">
							<label for="txttgllahir" class="form-label">Tanggal Lahir</label>
							<div class="input-group">
								<span class="input-group-text"><i class="bi bi-calendar-week"></i></span>
								<input type="text" class="form-control" id="txttgllahir">
							</div>
						</div>
					</div>

					<div class="row mt-3">
						<div class="col-12 text-center mb-3 p-0">
							<label id="warningx" class="text-danger fw-medium"></label>
						</div>
					</div>

					<hr>
					<div class="p-0">
						<div class="d-flex justify-content-between">
							<button type="button" class="btn btn-primary me-2" onClick="window.location='?mod=<?php echo $_SESSION['role'];?>'">
								<i class="bi bi-x-circle-fill"></i>&nbsp;Tutup
							</button>
							<button type="button" class="btn btn-success" onClick="window.location='?mod=chpass'">
								<i class="bi bi-key-fill"></i>&nbsp; Ubah Password
							</button>
						</div>
					</div>
                </div>
            </div>
            <!-- Page main content ends here -->
    	</div>

		<script src="js/A_main/bootstrap/bootstrap.bundle_v5.js"></script>
		<script src="js/A_main/jquery/jquery.idle.js" type="text/javascript"></script>	
		<script src="js/A_main/jquery/jquery.min_v3.js"></script>
		<script src="js/A_main/native/settinguser.js"></script>

		<script>
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