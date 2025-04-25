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

    $( '.select2me' ).select2( {
        theme: "bootstrap-5",
        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
        placeholder: $( this ).data( 'placeholder' ),
    });

    $('#cbojurusanmaster').select2({
        theme: "bootstrap-5",
        dropdownParent: $('#master-modal'),
        width: '100%',
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
    
    // ! BATASAN
    tampildata();
    tgldefault();
    isi_cbojurusanx();
    
    $("#endata").hide();
    $("#vwdata").show();
    
    $('#txtcari').trigger('focus');

    $(document).on("keyup", "#txtcari", function() {
        tampildata();
    });

    $(document).on("change", "#txttglawal, #txttglakhir, #cbokelasx, #cbojurusanx, #cboindeksx, #cbostatusx", function() {
        tampildata();
    });

});

function tgldefault() {
    $.ajax({
        type: 'POST', 
        url: 'pages/kehilangan/tampilkan_default.php',
        dataType: "json",
        success	: function(data){
            $("#txttglawal").val(data.tglawal);
            $("#txttglakhir").val(data.tglakhir);
            tampildata();
        }
    });
}

function isi_cbojurusanx(cbojurusanx){
    $.ajax({
        type: 'POST', 
        url: 'pages/kehilangan/tampilkan_jurusanx.php',
        success: function(response) {
            $('#cbojurusanx').html(response); 	
            if(cbojurusanx && cbojurusanx.length > 0){
                $('#cbojurusanx').val(cbojurusanx).trigger('change');
            }	
        }
    });
}

function tampildata(page){
    let cari = $("#txtcari").val();	
    let tglawal = $("#txttglawal").val();		
    let tglakhir = $("#txttglakhir").val();	
    let kelas = $("#cbokelasx").val();
    let jurusan = $("#cbojurusanx").val();
    let indeks = $("#cboindeksx").val();
    let status = $("#cbostatusx").val();

    $.ajax({
        type	: "GET",		
        url		: "pages/kehilangan/tampildata.php",
        data	: {
            cari : cari,
            tglawal : tglawal,
            tglakhir : tglakhir,
            kelas : kelas,
            jurusan : jurusan,
            indeks : indeks,
            status : status,
            page : page
        },
        success	: function(data){				
            $("#tampildata").html(data);
            pages(page);
        }
    });
}

function pages(page) {
    let cari = $("#txtcari").val();	
    let tglawal = $("#txttglawal").val();		
    let tglakhir = $("#txttglakhir").val();	
    let kelas = $("#cbokelasx").val();
    let jurusan = $("#cbojurusanx").val();
    let indeks = $("#cboindeksx").val();
    let status = $("#cbostatusx").val();

    $.ajax({
        type	: "GET",		
        url		: "pages/kehilangan/paging.php",
        data	: {
            cari : cari,
            tglawal : tglawal,
            tglakhir : tglakhir,
            kelas : kelas,
            jurusan : jurusan,
            indeks : indeks,
            status : status,
            page : page
        },
        success	: function(data){				
            $("#pages").html(data);
        }
    });
}

function edit(kodekehilangan){		
    $('#vwdata').hide();	
    $('#endata').show();
    $('#vwfoto').show();		
    
    $.ajax({
        type	: "POST",
        url		: "pages/kehilangan/edit.php",
        data	: {
            kodekehilangan : kodekehilangan
        },
        dataType: "json",
        success	: function(data){
            $("#txtkodekehilangan").val(data.kodekehilangan);
            $("#txtket").val(data.ket);
            if (data.foto) {
                $('#txtfotox').attr('src', 'img/kehilangan/' + data.foto);
            }else {
                $('#vwfoto').hide();		
            }
        }
    });
}

function del(kodekehilangan){	
    $("#deleteModal").modal('toggle');
    $("#deleteModal").modal('show');	
    $("#txtkodekehilangan").val(kodekehilangan);
}

$('#btnHapus').on('click', function() {
    let kodekehilangan = $("#txtkodekehilangan").val();
    $.ajax({
        type	: "POST",
        url		: "pages/kehilangan/hapus.php",
        data	: "kodekehilangan="+kodekehilangan,
        dataType: "json",
        success	: function(data){
            $("#deleteModal").modal('hide');
            $("#infoModal").modal('toggle');
            $("#infoModal").modal('show');
            $("#infomsg").html(data.pesan);
            tampildata();
        }
    });
});

function ketemu(kodekehilangan){	
    $("#confirmKetemu").modal('toggle');
    $("#confirmKetemu").modal('show');	
    $("#txtkodekehilangan").val(kodekehilangan);
}

$('#btnKetemu').on('click', function() {
    let kodekehilangan = $("#txtkodekehilangan").val();
    console.log(kodekehilangan);
    $.ajax({
        type	: "POST",
        url		: "pages/kehilangan/ketemu.php",
        data	: "kodekehilangan="+kodekehilangan,
        dataType: "json",
        success	: function(data){
            $("#confirmKetemu").modal('hide');
            $("#infoModal").modal('toggle');
            $("#infoModal").modal('show');
            $("#infomsg").html(data.pesan);
            if (data.result==1) {
                tampildata();
            }
        }
    });
});

$('#entrikehilangan').on('click', function() {
    $('#vwdata').hide();	
    $('#endata').show();
    $('#vwfoto').hide();		
    $('#txtket').trigger('focus');
});

$("#notefoto").append('NOTE : Maksimal 5MB, berformat JPG atau PNG')

$("#btnconfirm").on('click', function(){
    $("#warningx").text('');

    let ket = $("#txtket").val();
    let gambar = upload(); // pakai let, jangan $gambar, biar konsisten JS bukan PHP 😄

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

    // ✅ Semua valid, buka modal
    $("#confirmModal").modal("toggle");
    $("#confirmModal").modal("show");
});


$("#btnSave").on('click', function(){
    let kodekehilangan	= $("#txtkodekehilangan").val();
    let ket = $("#txtket").val();
    let foto = $("#txtfoto")[0].files[0];

    let formData = new FormData();
    formData.append("kodekehilangan", kodekehilangan);
    formData.append("ket", ket);
    if (foto) {
        formData.append("foto", foto);
    }

    $.ajax({
        type: "POST",
        url: "pages/kehilangan/simpan.php",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        timeout: 3000,
        success: function (data) {
            $("#confirmModal").modal("hide");
            $("#infoModal").modal("toggle");
            $("#infoModal").modal("show");
            $("#infomsg").html(data.pesan);
            if(data.sukses==1){
                tampildata();
                clearall();
            }
        }
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

function clearall(){	
	$("#txtkodekehilangan").val('');
	$("#txtket").val('');
	$("#txtfoto").wrap('<form>').closest('form').get(0).reset();
	$("#txtfoto").unwrap();
	$('#vwfoto').hide();		
}

const appendAlert = (message, type) => {
    const alertHTML = `
    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-1"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`;

    $("#warning").html(alertHTML);
};

$("#btnexport").on('click', function() {
    let cari = $("#txtcari").val();	
    let tglawal = $("#txttglawal").val();		
    let tglakhir = $("#txttglakhir").val();	
    let kelas = $("#cbokelasx").val();
    let jurusan = $("#cbojurusanx").val();
    let indeks = $("#cboindeksx").val();
    let status = $("#cbostatusx").val();

    if (!tglawal || !tglakhir) {
        appendAlert('<strong>Tanggal awal</strong> dan <strong>akhir</strong> tidak boleh kosong!', 'warning');
        return;
    }

    let url = 'pages/kehilangan/exportexc.php?' +
            'cari=' + encodeURIComponent(cari) + '&' +
            'kelas=' + encodeURIComponent(kelas) + '&' +
            'jurusan=' + encodeURIComponent(jurusan) + '&' +
            'indeks=' + encodeURIComponent(indeks) + '&' +
            'status=' + encodeURIComponent(status) + '&' +
            'tglawal=' + encodeURIComponent(tglawal) + '&' +
            'tglakhir=' + encodeURIComponent(tglakhir);

window.open(url, '_blank');
});