<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
		<link href="favicon/favicon.ico" rel="shortcut icon" type="image/png" />
		<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="css/bootstrap_v5.css">
		<link rel="stylesheet" href="css/bootstrap-icons/font/bootstrap-icons.css">
		<link rel="stylesheet" href="css/select2/select2.min.css" />
		<link rel="stylesheet" href="css/select2/select2-bootstrap-5-theme.min.css" />
		<link rel="stylesheet" href="css/datepicker/bootstrap-datepicker.min.css" />
		<link rel="stylesheet" href="css/native_styling/cerita_In.css">
		<title>SPEAKOUT | Cerita In</title>
    </head>
    <body>
        <div class="d-flex" id="wrapper">
        <!-- Sidebar starts here -->
			<div class="bg-white shadow d-print-none" style="--bs-box-shadow: -5px -15px 10px 0px rgba(0, 0, 0, 0.5);" id="sidebar-wrapper">
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
						$idinduk = 4;
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
									// Cek untuk akses bk: jangan tampilkan id_anak 104
									if ($_SESSION['jenisakses'] == 'bk' && $menu_a['id_anak'] == 104) {
										continue; // Lewatkan, tidak ditampilkan
									}
									
								echo "<a href='$menu_a[link]' class='hover d-flex px-3 align-items-center list-group-item list-group-item-action bg-transparent text-body-tertiary fs-6 active fw-semibold dropdown-item'>
										<div class='w-100 d-flex align-items-center py-1 px-3 rounded-3'>
											<span class='$menu_a[menu_class] ms-2 text-wrap' style='font-size:14px;'> $menu_a[menu_caption]</span>
										</div>
									</a>";   
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
				<nav class="navbar navbar-expand-lg navbar-light bg-transparent px-4 py-4 py-md-2 d-print-none">
					<div class="d-flex align-items-center">
						<i class="bi bi-text-left fs-2 me-3" id="menu-toggle"></i>
						<h2 class="fs-3 m-0  primary-text">CeritaIn</h2>
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
				<?php if($_SESSION['jenisuser'] == 1) {?>
					<div class="row m-0" id="endatasiswa">
						<div class="mb-2 p-0">
							<h4 class="text-secondary fs-5 fw-semibold" style="font-family: system-ui,'Kaushan Script', cursive;">
								&nbsp; Form CeritaIn Baru
							</h4>
						</div>
						<hr>

						<div class="container-fluid m-0 p-0 mt-3">
							<div class="mb-0 mb-sm-0 mb-md-3 d-flex justify-content-center align-items-center flex-wrap">
								<label for="cboguru" class="col-12 col-sm-3 col-md-3 col-lg-2 text-lg-start col-form-label">Nama Guru</label>
								<div class="col-12 col-sm-8 col-md-6 mb-sm-2 mb-lg-0">
									<input type="hidden" class="form-control" id="txtkodeguru" />
									<input type="hidden" class="form-control" id="txtnotelp" />
									<input type="hidden" class="form-control" id="txtkodecerita" />
									<input type="text" id="cboguru" class="form-control text-center input-field" placeholder="Klik form ini untuk memilih nama guru" autocomplete="off" readonly/>
								</div>
							</div>

							<div class="mb-0 mb-sm-0 mb-md-3 d-flex justify-content-center align-items-center flex-wrap">
								<label for="kategorixOptions" class="col-12 col-sm-3 col-md-3 col-lg-2 text-lg-start col-form-label">Kategori</label>
								<div class="col-12 col-sm-8 col-md-6 mb-sm-2 mb-lg-0 d-flex justify-content-evenly">
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="kategorixOptions" id="kategorix1" value="1">
										<label class="form-check-label" for="kategorix1">Konsultasi</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="kategorixOptions" id="kategorix2" value="2" checked>
										<label class="form-check-label" for="kategorix2">Konseling</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="kategorixOptions" id="kategorix3" value="3">
										<label class="form-check-label" for="kategorix3">Coaching</label>
									</div>
								</div>
							</div>

							<div class="mb-0 mb-sm-0 mb-md-3 d-flex justify-content-center align-items-center flex-wrap">
								<label for="metodexOptions" class="col-12 col-sm-3 col-md-3 col-lg-2 text-lg-start col-form-label">Metode</label>
								<div class="col-12 col-sm-8 col-md-6 mb-sm-2 mb-lg-0 d-flex justify-content-evenly">
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="metodexOptions" id="metodex1" value="1" checked>
										<label class="form-check-label" for="metodex1">Chat (Online)</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="metodexOptions" id="metodex2" value="2">
										<label class="form-check-label" for="metodex2">Temu (Offline)</label>
									</div>
								</div>
							</div>

							<div class="mb-0 mb-sm-0 mb-md-3 d-flex justify-content-center align-items-center flex-wrap">
								<label for="cbotopik" class="col-12 col-sm-3 col-md-3 col-lg-2 text-lg-start col-form-label">Topik</label>
								<div class="col-12 col-sm-8 col-md-6 mb-sm-2">
									<select id="cbotopik" class="form-select select2me input-field">
										<option value="" selected>- Silahkan Pilih -</option>
										<option value="1">Pribadi</option>
										<option value="2">Belajar</option>
										<option value="3">Sosial</option>
										<option value="4">Karir</option>
									</select>
								</div>
							</div>

							<div class="mb-0 mb-sm-0 mb-md-3 d-flex justify-content-center align-items-center flex-wrap">
								<label for="txttglajuan" class="col-12 col-sm-3 col-md-3 col-lg-2 text-lg-start col-form-label">Tanggal</label>
								<div class="col-12 col-sm-8 col-md-6 mb-sm-2">
									<div class="input-group">
										<span class="input-group-text" id="calendar-icon"><i class="bi bi-calendar-week"></i></span>
										<input type="text" style="background:#FFFFFF" id="txttglajuan" class="datepickerr form-control text-center input-field" readonly />
									</div>
								</div>
							</div>

							<div class="mb-0 mb-sm-0 mb-md-3 d-flex justify-content-center align-items-center flex-wrap">
								<label for="txtdes" class="col-12 col-sm-3 col-md-3 col-lg-2 text-lg-start col-form-label">Deskripsi</label>
								<div class="col-12 col-sm-8 col-md-6 mb-sm-2 mb-lg-0">
									<textarea rows="3" class="form-control input-field" id="txtdes" maxlength="100" autocomplete="off" placeholder="Apa yang ingin kamu ceritakan?"></textarea>
									<small id="counter" class="text-muted">0 / Maks 100 karakter</small>
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
								<button type="button" class="btn btn-primary me-2 w-auto" onClick="window.location='?mod=<?php echo $_SESSION['role'];?>'">
									<i class="bi bi-x-circle-fill"></i>&nbsp;Tutup
								</button>
								<button type="button" id="btnconfirm" class="btn btn-success">
									<i class="bi bi-floppy2"></i>&nbsp; Simpan
								</button>
							</div>
						</div>
					</div>
				<?php } else { ?>
					<div class="row m-0 mb-3" id="vwdata">
						<div class="d-flex p-0 mb-4 flex-wrap justify-content-between align-items-center gap-3 gap-sm-3 gap-lg-3">
							<div class="col-12 col-sm-12 col-md-12 col-lg-5">
								<div class="input-group">
									<span class="input-group-text">
										<i class="bi bi-search"></i>
									</span>
									<input type="text" class="form-control text-center fw-medium" id="txtcari" placeholder="Ketik kata kunci" autocomplete="off"/>
								</div>
							</div>
							<div class="col-12 col-sm-5 col-md-4 col-lg-2">
								<select class="form-select fw-medium" id="cbokategorix">
									<option value='' selected>Pilih Kategori</option>
									<option value="1">Konsultasi</option>
									<option value="2">Konseling</option>
									<option value="3">Coaching</option>
								</select>
							</div>
							<div class="col-12 col-sm-5 col-md-4 col-lg-2">
								<select class="form-select fw-medium" id="cbostatusx">
									<option value='' selected>Pilih Status</option>
									<option value='1' >Diproses</option>
									<option value='2' >Selesai</option>
								</select>
							</div>
							
							<div class="text-end col-12 col-sm-12 col-md-3 col-lg-2">
								<span class="d-block"><a href="#" class="text-decoration-none fw-medium" id="entriceritain">[&nbsp; Entri Ceritain Baru &nbsp;]</a></span>
							</div>
						</div>

						<div class="col-12 p-0 mb-3">
							<div class="table-responsive rounded-3" id="tampildata"></div>
						</div>

						<div class="d-flex flex-wrap p-0 mb-3">
							<div class="col-12 col-sm-2 col-md-2 col-lg-1">
								<select class="form-select fw-medium" id="cbopagesx">
									<option value='5' selected>5</option>
									<option value='10' >10</option>
									<option value='20' >20</option>
									<option value='50' >50</option>
									<option value='100' >100</option>
								</select>
							</div>
							<div class="text-end col-12 col-sm-10 col-md-10 col-lg-11" id="pages"></div>
						</div>

						<div class="col-12 p-0">
							<div class="text-center text-danger fw-medium" id="warning"></div>
						</div>

						<div class="mt-3 p-0 d-print-none">
							<div class="d-flex justify-content-between align-items-center">
								<button type="button" class="btn btn-primary me-2 w-auto" onClick="window.location='?mod=<?php echo $_SESSION['role'];?>'">
									<i class="bi bi-x-circle-fill"></i>&nbsp;Tutup
								</button>
								<div class="d-flex justify-content-between align-items-center">
									<button class="btn btn-success me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Export Excel CeritaIn" id="btnexport">
										<i class="bi bi-file-earmark-spreadsheet-fill"></i>&nbsp; Export <span class="d-none d-sm-inline-block">to Excel</span>
									</button>
									<button class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Print CeritaIn" onclick="window.print();">
										<i class="bi bi-printer-fill"></i> Print
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="row m-0" id="endata">
						<div class="mb-2 p-0">
							<h4 class="text-secondary fs-5 fw-semibold" style="font-family: system-ui,'Kaushan Script', cursive;">
								&nbsp; Form CeritaIn Baru
							</h4>
						</div>
						<hr>

						<div class="container-fluid m-0 p-0 mt-3">
							<div class="mb-0 mb-sm-0 mb-md-3 d-flex justify-content-center align-items-center flex-wrap">
								<label for="cboguru" class="col-12 col-sm-3 col-md-3 col-lg-2 text-lg-start col-form-label">Nama Guru</label>
								<div class="col-12 col-sm-8 col-md-6 mb-sm-2 mb-lg-0">
									<input type="hidden" class="form-control" id="txtkodeguru" />
									<input type="hidden" class="form-control" id="txtnotelp" />
									<input type="hidden" class="form-control" id="txtkodecerita" />
									<input type="text" id="cboguru" class="form-control text-center input-field" placeholder="Klik form ini untuk memilih nama guru" autocomplete="off" readonly/>
								</div>
							</div>

							<div class="mb-0 mb-sm-0 mb-md-3 d-flex justify-content-center align-items-center flex-wrap">
								<label for="kategorixOptions" class="col-12 col-sm-3 col-md-3 col-lg-2 text-lg-start col-form-label">Kategori</label>
								<div class="col-12 col-sm-8 col-md-6 mb-sm-2 mb-lg-0 d-flex justify-content-evenly">
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="kategorixOptions" id="kategorix1" value="1">
										<label class="form-check-label" for="kategorix1">Konsultasi</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="kategorixOptions" id="kategorix2" value="2" checked>
										<label class="form-check-label" for="kategorix2">Konseling</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="kategorixOptions" id="kategorix3" value="3">
										<label class="form-check-label" for="kategorix3">Coaching</label>
									</div>
								</div>
							</div>

							<div class="mb-0 mb-sm-0 mb-md-3 d-flex justify-content-center align-items-center flex-wrap">
								<label for="metodexOptions" class="col-12 col-sm-3 col-md-3 col-lg-2 text-lg-start col-form-label">Metode</label>
								<div class="col-12 col-sm-8 col-md-6 mb-sm-2 mb-lg-0 d-flex justify-content-evenly">
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="metodexOptions" id="metodex1" value="1" checked>
										<label class="form-check-label" for="metodex1">Chat (Online)</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="metodexOptions" id="metodex2" value="2">
										<label class="form-check-label" for="metodex2">Temu (Offline)</label>
									</div>
								</div>
							</div>

							<div class="mb-0 mb-sm-0 mb-md-3 d-flex justify-content-center align-items-center flex-wrap">
								<label for="cbotopik" class="col-12 col-sm-3 col-md-3 col-lg-2 text-lg-start col-form-label">Topik</label>
								<div class="col-12 col-sm-8 col-md-6 mb-sm-2">
									<select id="cbotopik" class="form-select select2me input-field">
										<option value="" selected>- Silahkan Pilih -</option>
										<option value="1">Pribadi</option>
										<option value="2">Belajar</option>
										<option value="3">Sosial</option>
										<option value="4">Karir</option>
									</select>
								</div>
							</div>

							<div class="mb-0 mb-sm-0 mb-md-3 d-flex justify-content-center align-items-center flex-wrap">
								<label for="txttglajuan" class="col-12 col-sm-3 col-md-3 col-lg-2 text-lg-start col-form-label">Tanggal</label>
								<div class="col-12 col-sm-8 col-md-6 mb-sm-2">
									<div class="input-group">
										<span class="input-group-text" id="calendar-icon"><i class="bi bi-calendar-week"></i></span>
										<input type="text" style="background:#FFFFFF" id="txttglajuan" class="datepickerr form-control text-center input-field" readonly />
									</div>
								</div>
							</div>

							<div class="mb-0 mb-sm-0 mb-md-3 d-flex justify-content-center align-items-center flex-wrap">
								<label for="txtdes" class="col-12 col-sm-3 col-md-3 col-lg-2 text-lg-start col-form-label">Deskripsi</label>
								<div class="col-12 col-sm-8 col-md-6 mb-sm-2 mb-lg-0">
									<textarea rows="3" class="form-control input-field" id="txtdes" maxlength="100" autocomplete="off" placeholder="Apa yang ingin kamu ceritakan?"></textarea>
									<small id="counter" class="text-muted">0 / Maks 100 karakter</small>
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
								<button type="button" class="btn btn-primary me-2" onClick="window.location='?mod=ceritain'">
									<i class="bi bi-reply-all-fill"></i>&nbsp; Kembali
								</button>
								<button type="button" id="btnconfirm" class="btn btn-success">
									<i class="bi bi-floppy2"></i>&nbsp; Simpan
								</button>
							</div>
						</div>
					</div>
					<div id="detail">
					</div>
				<?php } ?>
				</div>
			</div>
			<!-- Pop Up list data siswa -->
			<div class="modal fade" id="master-modal" tabindex="-1" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Daftar Guru</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="info-close"></button>
						</div>
						<div class="modal-body">
							<div class="input-group mb-3">
							<span class="input-group-text"><i class="bi bi-search"></i></span>
								<input type="text" class="form-control text-center" id="txtcarimaster" placeholder="Pencarian.." autocomplete="off">
							</div>

							<div class="table-responsive rounded-3" id="tblmaster"></div>
							<div class="row">
								<div class="col text-end" id="pagingmaster"></div>
							</div>
						</div>
						<div class="modal-footer justify-content-end">
							<button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="info-ok">
							<i class="bi bi-check-lg"></i> Ok
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal Confirm -->
		<div class="modal fade" id="confirmModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-4" id="confirmModalLabel">Konfirmasi</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
				<div class="modal-body text-center">
					<h4>Yakin akan disimpan?</h4>
				</div>
					<div class="modal-footer d-flex justify-content-between">
						<button type="button" class="btn btn-danger d-flex align-items-center gap-1 fw-medium" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i>Tidak</button>
						<button type="button" class="btn btn-success d-flex align-items-center gap-1 fw-medium" id="btnSave"><i class="bi bi-check-lg"></i>Ya</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal Info -->
		<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-4" id="infoModalLabel">Informasi</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body text-center" id="infomsg"></div>
					<div class="modal-footer d-flex justify-content-end">
						<button type="button" class="btn btn-success d-flex align-items-center gap-1 fw-medium" data-bs-dismiss="modal"><i class="bi bi-check-lg"></i>Ok</button>
					</div>
				</div>
			</div>
		</div>

		<script src="js/A_main/jquery/jquery.min_v3.js"></script>
		<script src="js/A_main/jquery/jquery.idle.js" type="text/javascript"></script>	
		<script src="js/A_main/bootstrap/bootstrap.bundle_v5.js"></script>
		<script src="js/A_main/library/select2.min.js"></script>
		<script src="js/datepicker/bootstrap-datepicker.min.js"></script>
		<script src="js/A_main/native/ceritaIn.js"></script>

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