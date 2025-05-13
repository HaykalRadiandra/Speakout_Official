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

    // ! Di Atas jangan diubah untuk sidebar
    
    $('#endata').hide();
    $('#vwdata').show();

    tampildata();

    $('#txtcari').trigger('focus');

    $('#entriguru').on('click', function() {
        $('#vwdata').hide();	
        $('#endata').show();
        $("#txtusername").focus();
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

    $("#btnconfirm").on("click", function(event) {
        event.preventDefault(); // Mencegah pengiriman form default

        $("#warningx").text('');

        let username = $("#txtusername").val();
        let password = $("#txtpassword").val();
        let nama = $("#txtnama").val();
        let nip = $("#txtnip").val();
        let alamat = $("#txtalamat").val();
        let notelp = $("#txtnotelp").val(); 

        if (username.length < 3) {
            $("#warningx").append("Username minimal 3 karakter");
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
        if (nip.length == 0) {
            $("#warningx").append("NIP masih kosong");
            $('#txtnip').focus();
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

        $("#confirmModal").modal("toggle");
        $("#confirmModal").modal("show");
    });

    $("#btnSave").on("click", function(event) {
        event.preventDefault(); // Mencegah pengiriman form default

        let kodeguru	= $("#txtkodeguru").val();
        let username	= $("#txtusername").val();
        let password	= $("#txtpassword").val();
        let nama 		= $("#txtnama").val();
        let nip 	 	= $("#txtnip").val();	
        let alamat 	 	= $("#txtalamat").val();	
        let notelp 	 	= $("#txtnotelp").val();	

        $.ajax({
            type: 'POST',
            url : "pages/guru/simpan.php",
            data: {
                kodeguru: kodeguru,
                username: username,
                password: password,
                nama: nama,
                nip: nip,
                alamat: alamat,
                notelp: notelp
            },
            dataType: 'json',
            timeout: 5000,
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

function tampildata(page) {
    let cari = $("#txtcari").val();

    $.ajax({
        type: "GET",
        url: "pages/guru/tampildata.php",
        data: {
            page: page,
            cari: cari
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
        url: "pages/guru/paging.php",
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

function edit(kodeguru){		
	$('#vwdata').hide();	
	$('#endata').show();
	
	$.ajax({
		type	: "POST",
		url		: "pages/guru/edit.php",
		data	: "kodeguru="+kodeguru,
		dataType: "json",
		success	: function(data){
			$("#txtkodeguru").val(data.kodeguru);
			$("#txtusername").val(data.username);
			$("#txtpassword").val(data.password).attr('disabled', true);
			$("#txtnama").val(data.nama);
			$("#txtnip").val(data.nip);
			$("#txtalamat").val(data.alamat);
			$("#txtnotelp").val(data.notelp);
			$("#txtnama").focus();
		}
	});
}

function del(kodeguru){	
	$("#deleteModal").modal('toggle');
	$("#deleteModal").modal('show');	
	$("#txtkodeguru").val(kodeguru);
}

$('#btnHapus').on('click', function() {
	let kodeguru = $("#txtkodeguru").val();	
	$.ajax({
		type	: "POST",
		url		: "pages/guru/hapus.php",
		data	: "kodeguru="+kodeguru,
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

function clearall(){	
	$("#txtkodeguru").val('');
	$("#txtusername").val('');
	$("#txtpassword").val('').attr('readonly', false);
	$("#txtalamat").val('');
	$("#txtnama").val('');
	$("#txtnip").val('');
	$("#txtnotelp").val('');
}

function printtoexcel() {
	let cari = $("#txtcari").val();	
	
    window.open('pages/guru/printtoexcel.php?cari='+cari,'_blank');
}