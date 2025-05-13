// Tunggu sampai DOM sepenuhnya dimuat
$(function() {    
    $(".menu-collapse").on("show.bs.collapse", function () {
        // Tutup semua menu lain yang sedang terbuka
        $(".menu-collapse").not(this).collapse("hide");
        // Hapus kelas 'rotated' dari semua ikon lainnya
        $(".toggle-icon").removeClass("rotated");
        // Tambahkan kelas 'rotated' hanya pada ikon di dalam menu yang sedang dibuka
        $(this).prev("[data-bs-toggle='collapse']").find(".toggle-icon").addClass("rotated");
    });

    $(".menu-collapse").on("hide.bs.collapse", function () {
        // Hapus kelas 'rotated' saat menu ditutup
        $(this).prev("[data-bs-toggle='collapse']").find(".toggle-icon").removeClass("rotated");
    });

    // Toggle sidebar
    $("#menu-toggle").on("click", function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });

    // Toggle ikon panah pada dropdown saat diklik
    $("[data-bs-toggle='collapse']").on("click", function() {
        var $icon = $(this).find(".toggle-icon");
        // Jika submenu sedang terbuka, biarkan ikon tetap di posisi rotated
        if ($(this).attr("aria-expanded") === "true") {
            $icon.removeClass("rotated");
        } else {
            $icon.addClass("rotated");
        }
    });

    // Di Atas jangan diubah untuk sidebar
    isi_cbojurusan();
    isi_cbojurusanx();
    tampildata();
    
    $('#txtcari').trigger('focus');

    $('#vwdata').show();
    $('#endata').hide();

    $('#entrisswa').on('click', function() {
        $('#vwdata').hide();	
        $('#endata').show();
        $("#txtusername").focus();
    });

    $( '.select2me' ).select2( {
        theme: "bootstrap-5",
        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
        placeholder: $( this ).data( 'placeholder' ),
    });

    $(".datepickerr").datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
        todayHighlight: true,
        language: "id"
    });

    $(".input-field").on("keydown", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            let inputs = $(".input-field");
            let index = inputs.index(this);
            let nextInput = inputs.eq(index + 1);
            if (nextInput.length) {
                nextInput.trigger("focus");
            }
        }
    });

    $(document).on("keyup", "#txtcari", function() {
        tampildata();
    });
    
    $(document).on("change", "#cbokelasx, #cbojurusanx, #cboindeksx", function() {
        tampildata();
    });    
    
    $("#btnconfirm").on("click", function(event) {
        event.preventDefault(); // Mencegah pengiriman form default

        $("#warningx").text('');

        let username = $("#txtusername").val();
        let password = $("#txtpassword").val();
        let nama = $("#txtnama").val();
        let nis = $("#txtnis").val();
        let nisn = $("#txtnisn").val();
        let jurusan = $("#cbojurusan").val();
        let alamat = $("#txtalamat").val();
        let notelp = $("#txtnotelp").val(); 
        let tgllahir = $("#txttgllahir").val();

        if (username.length < 5) {
            $("#warningx").append("Username minimal 5 karakter");
            $('#txtusername').focus();
            return;
        }
        if (password.length < 5) {
            $("#warningx").append("Password minimal 5 karakter");
            $('#txtpassword').focus();
            return;
        }
        if (nama.length == 0) {
            $("#warningx").append("Nama masih kosong");
            $('#txtnama').focus();
            return;
        }
        if (nis.length == 0) {
            $("#warningx").append("NIS masih kosong");
            $('#txtnis').focus();
            return;
        }
        if (nisn.length == 0) {
            $("#warningx").append("NISN masih kosong");
            $('#txtnisn').focus();
            return;
        }
        if (jurusan.length == 0) {
            $("#warningx").append("Jurusan masih kosong");
            $('#cbojurusan').focus();
            return;
        }
        if (alamat.length == 0) {
            $("#warningx").append("Alamat masih kosong");
            $('#txtalamat').focus();
            return;
        }
        if (notelp.length == 0) {
            $("#warningx").append("No Telepon masih kosong");
            $('#txtnotelp').focus();
            return;
        }
        if (tgllahir.length == 0) {
            $("#warningx").append("Tanggal Lahir masih kosong");
            $('#txttgllahir').focus();
            return;
        }

        $("#confirmModal").modal("toggle");
        $("#confirmModal").modal("show");

    });

    $("#btnSave").on("click", function(event) {
        event.preventDefault(); // Mencegah pengiriman form default

        let kodesiswa   = $("#txtkodesiswa").val();
        let username    = $("#txtusername").val();
        let password    = $("#txtpassword").val();
        let nama        = $("#txtnama").val();
        let nis         = $("#txtnis").val();
        let nisn        = $("#txtnisn").val();
        let masasekolah = $("#cbomasasekolah").val();
        let kelas 	 	= $("#cbokelas").val();
        let jurusan     = $("#cbojurusan").val();
        let indeks 	 	= $("#cboindeks").val();
        let alamat      = $("#txtalamat").val();
        let notelp      = $("#txtnotelp").val(); 
        let tgllahir    = $("#txttgllahir").val();

        $.ajax({
            type: 'POST',
            url: 'pages/siswa/simpan.php',
            data: {
                kodesiswa: kodesiswa,
                username: username,
                password: password,
                nama: nama,
                nis: nis,
                nisn: nisn,
                masasekolah: masasekolah,
                kelas: kelas,
                jurusan: jurusan,
                indeks: indeks,
                alamat: alamat,
                notelp: notelp,
                tgllahir: tgllahir
            },
            dataType: 'json',
            timeout: 2000,
            success: function(data) {
                $("#confirmModal").modal("hide");	
                $("#infoModal").modal("toggle");
                $("#infoModal").modal("show");
                $("#infomsg").html(data.pesan);
                tampildata();
                if(data.sukses==1){
                    clearall();
                }
            }
        });
    });
    
});

function edit(kodesiswa){		
	$('#vwdata').hide();	
	$('#endata').show();
	
	$.ajax({
		type	: "POST",
		url		: "pages/siswa/edit.php",
		data	: "kodesiswa="+kodesiswa,
		dataType: "json",
		success	: function(data){
			$("#txtkodesiswa").val(data.kodesiswa);
			$("#txtusername").val(data.username);
			$("#txtpassword").val(data.password).attr('disabled', true);
			$("#txtnama").val(data.nama);
			$("#txtnis").val(data.nis);
			$("#txtnisn").val(data.nisn);
			$('#cbomasasekolah').val(data.masasekolah);
			$("#cbokelas").val(data.kelas);
			$('#cbojurusan').val(data.kodejurusan).trigger('change');
			$("#cboindeks").val(data.indeks);
			$("#txtalamat").val(data.alamat);
			$("#txtnotelp").val(data.notelp);
			$("#txttgllahir").val(data.tgllahir);
			$("#txtnama").focus();
		}
	});
}

function del(kodesiswa){	
	$("#deleteModal").modal('toggle');
	$("#deleteModal").modal('show');	
	$("#txtkodesiswa").val(kodesiswa);
}

$('#btnHapus').on('click', function() {
	let kodesiswa = $("#txtkodesiswa").val();	
	$.ajax({
		type	: "POST",
		url		: "pages/siswa/hapus.php",
		data	: "kodesiswa="+kodesiswa,
		dataType: "json",
		success	: function(data){
			$("#deleteModal").modal('hide');
			$("#infoModal").modal('toggle');
			$("#infoModal").modal('show');
			$("#infomsg").html(data.pesan);
			if(data.result==1){				
				tampildata(1);	
			}			
		}
	});
});


function isi_cbojurusan(cbojurusan) {
    $.ajax({
        type: 'POST', 
        url: 'pages/siswa/tampilkan_jurusan.php',
        success: function(response) {
            $('#cbojurusan').html(response);
            if (cbojurusan.length > 0) {
                $('#cbojurusan').val(cbojurusan).trigger('change');
                // $('#cbojurusan').select2('val',cbojurusan);
            }   
        }
    });
}

function isi_cbojurusanx(cbojurusanx){
	$.ajax({
		type: 'POST', 
		url: 'pages/siswa/tampilkan_jurusanx.php',
		success: function(response) {
			$('#cbojurusanx').html(response); 	
			if(cbojurusanx.length > 0){
                $('#cbojurusanx').val(cbojurusanx).trigger('change');
				// $('#cbojurusanx').select2('val',cbojurusanx);
			}	
		}
	});
}

function tampildata(page) {
    let cari = $("#txtcari").val();
    let kelas = $("#cbokelasx").val();
    let jurusan = $("#cbojurusanx").val();
    let indeks = $("#cboindeksx").val();

    $.ajax({
        type: "GET",
        url: "pages/siswa/tampildata.php",
        data: {
            page: page,
            cari: cari,
            kelas: kelas,
            jurusan: jurusan,
            indeks: indeks
        },
        success: function(response) {
            $("#tampildata").html(response);
            pages(page);
        }
    });
}

function pages(page) {
    let cari = $("#txtcari").val();
    let kelas = $("#cbokelasx").val();
    let jurusan = $("#cbojurusanx").val();
    let indeks = $("#cboindeksx").val();

    $.ajax({
        type: "GET",
        url: "pages/siswa/paging.php",
        data: {
            page: page,
            cari: cari,
            kelas: kelas,
            jurusan: jurusan,
            indeks: indeks
        },
        success: function(response) {
            $("#pages").html(response);
        }
    });
}

function clearall(){	
	$("#txtkodesiswa").val('');
	$("#txtusername").val('');
	$("#txtpassword").val('').attr('readonly', false);
	$("#txtalamat").val('');
	$("#txtnama").val('');
	$("#txtnis").val('');
	$("#txtnisn").val('');
	$("#txtnotelp").val('');
	$("#txtalamat").val('');
	$("#txttgllahir").val('');
	$('#cbojurusan').val('').trigger('change');
}