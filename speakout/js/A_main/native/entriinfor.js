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
    
    $('#endata').hide();
    $('#vwdata').show();

    tampildata();

    $('#txtcari').trigger('focus');

    $(document).on("keyup", "#txtcari", function() {
        tampildata();
    });

    $('#entriinfor').on('click', function() {
        $('#vwdata').hide();	
        $('#endata').show();
        $('#vwfoto').hide();
        $("#txtjudul").trigger("focus");
    });

    $('.input-field').on("keydown", function (event) {
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

    $("#notefoto").append('NOTE : Maksimal 5MB, berformat JPG atau PNG')

    $("#btnconfirm").on('click', function(){
        $("#warningx").text('');

        let judul = $("#txtjudul").val();
        let ket = $("#txtket").val();
        let gambar = upload(); // pakai let, jangan $gambar, biar konsisten JS bukan PHP 😄

        if (judul.length == 0) {
            $("#warningx").text('Judul masih kosong!');
            $('#txtjudul').trigger('focus');
            return;
        }

        if (ket.length == 0) {
            $("#warningx").text('Keterangan masih kosong!');
            $('#txtket').trigger('focus');
            return;
        }

        // ❌ Upload gagal (file salah format / size)
        if (gambar === false) return;

        // ❗Tidak upload dan juga tidak ada gambar yang tampil sebelumnya
        let imgSrc = $("#txtfotox").attr("src");
        if (gambar === null && (!imgSrc || imgSrc.trim() === "")) {
            $("#warningx").text("Lampiran masih kosong!");
            return;
        }

        $("#confirmModal").modal("toggle");
        $("#confirmModal").modal("show");
    })

    $("#btnSave").on('click', function(){
        let kodeinformasi = $("#txtkodeinformasi").val();
        let judul = $("#txtjudul").val();
        let ket = $("#txtket").val();
        let foto = $("#txtfoto")[0].files[0];

        let formData = new FormData();
        formData.append("kodeinformasi", kodeinformasi);
        formData.append("judul", judul);
        formData.append("ket", ket);
        if (foto) {
            formData.append("foto", foto);
        }

        $.ajax({
            type: "POST",
            url: "pages/informasi/simpan.php",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (data) {
                $("#confirmModal").modal("hide");
                $("#infoModal").modal("toggle");
                $("#infoModal").modal("show");
                $("#infomsg").html(data.pesan);
                tampildata();
                clearall();
            }
        });
    });
});

function upload() {
    let fileInput = document.getElementById('txtfoto');

    // Kalau user memilih file baru
    if (fileInput.files.length > 0) {
        let file = fileInput.files[0];
        let allowedExtensions = /(\.jpg|\.png|\.jpeg)$/i;
        let maxSize = 5 * 1024 * 1024;

        if (!allowedExtensions.test(file.name)) {
            $("#warningx").text("Hanya boleh file .jpg, .jpeg atau .png");
            fileInput.value = "";
            return false;
        } else if (file.size > maxSize) {
            $("#warningx").text("Ukuran file maksimal 5MB!");
            fileInput.value = "";
            return false;
        } else {
            return true;
        }
    }

    // Kalau user nggak upload file baru, kembalikan null
    return null;
}

function tampildata(page){
    let cari = $("#txtcari").val();

    $.ajax({
        type: "GET",
        url: "pages/informasi/tampildata.php",
        data: {
            cari: cari,
            page: page
        },
        success: function(data){
            $("#tampildata").html(data);
            pages(page);
        }
    });
}

function pages(page){
    let cari = $("#txtcari").val();

    $.ajax({
        type: "GET",
        url: "pages/informasi/paging.php",
        data: {
            cari: cari,
            page: page
        },
        success: function(data){
            $("#pages").html(data);
        }
    });
}

function edit(kodeinformasi){
    $('#vwdata').hide();	
    $('#endata').show();
    $('#vwfoto').show();

    $.ajax({
        type: "POST",
        url: "pages/informasi/edit.php",
        data: "kodeinformasi="+kodeinformasi,
        dataType: "json",
        success: function(data){
            $("#txtkodeinformasi").val(data.kodeinformasi);
			$("#txtjudul").val(data.judul);
			$("#txtket").val(data.ket);
			if (data.foto) {
				$('#txtfotox').attr('src', 'img/informasi/' + data.foto);
			}else {
				$('#vwfoto').hide();		
			}
        }
    });
}

function del(kodeinformasi){
    $("#deleteModal").modal('toggle');
    $("#deleteModal").modal('show');
    $("#txtkodeinformasi").val(kodeinformasi);
}

$('#btnHapus').on('click', function(){
    let kodeinformasi = $("#txtkodeinformasi").val();

    $.ajax({
        type    : "POST",
        url     : "pages/informasi/hapus.php",
        data    : {
            kodeinformasi : kodeinformasi
        },
        dataType: "json",
        success : function(data){
            $("#deleteModal").modal('hide');
			$("#infoModal").modal('toggle');
			$("#infoModal").modal('show');
			$("#infomsg").html(data.pesan);
			if(data.result==1){				
				tampildata(1);	
			}	
        }
    });
})

function clearall(){	
	$("#txtkodeinformasi").val('');
	$("#txtjudul").val('');
	$("#txtket").val('');
	$("#txtfoto").wrap('<form>').closest('form').get(0).reset();
	$("#txtfoto").unwrap();
	$('#vwfoto').hide();		
}