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

    // ! BATASAN

    $('.select2me').each(function () {
        $(this).select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder')
        });
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
        language: "id",
        endDate: "0d"
    });

    $('label[for="cbotopik"]').on('click', function () {
        $('#cbotopik').select2('open');
    });   

    $('#calendar-icon').on('click', function() {
        $('#txttglajuan').focus();
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

    tampildata();

    $("#endata").hide();
    $("#detail").addClass('d-none');
    $("#vwdata").show();

    $("#txtcari").on("keyup", function() {
        tampildata();
    })

    $(document).on('keyup', '#txtcarimaster', function(){
        tampil_masterdata();
    });

    $(document).on('change', '#cbokategori, #cbokategorix, #cbotype, #cbostatusx, #cbopagesx', function(){
        tampildata();
    })

    $("#txtdes").on("input", function(){
        const maxChars = 100;
        if ($(this).val().length > maxChars) {
            $(this).val($(this).val().substring(0, maxChars));
        }
    })

    $("#txtdes").on("input", function(){
        const maxChars = 100;
        let val = $(this).val();
        if (val.length > maxChars) {
            val = val.substring(0, maxChars);
            $(this).val(val);
        }
        $("#counter").text(`${val.length} / Maks ${maxChars} karakter`);
    })

});

function tampildata(page) {
    let cari = $("#txtcari").val();
    let kategori = $("#cbokategorix").val();
    let status = $("#cbostatusx").val();
    let nopage = $("#cbopagesx").val();

    console.log(cari, kategori, status, nopage, page);

    $.ajax({
        type: "GET",
        url: "pages/ceritain/tampildata.php",
        data: {
            cari: cari,
            kategori: kategori,
            status: status,
            nopage: nopage,
            page: page
        },
        success: function (data) {
            $("#tampildata").html(data);
            pages(page);
        }
    });
}

function pages(page) {
    let cari = $("#txtcari").val();
    let kategori = $("#cbokategorix").val();
    let status = $("#cbostatusx").val();
    let nopage = $("#cbopagesx").val();

    $.ajax({
        type: "GET",
        url: "pages/ceritain/paging.php",
        data: {
            cari: cari,
            kategori: kategori,
            status: status,
            nopage: nopage,
            page: page
        },
        success: function (data) {
            $("#pages").html(data);
        }
    });
}

function selesai(kodecerita){
    $("#confirmModal").modal('toggle');
    $("#confirmModal").modal('show');
    $("#confirmModal .modal-body h4").text("Yakin akan menyelesaikan cerita ini?");

    $("#btnSave").on('click', function(){
        $.ajax({
            type: "POST",
            url: "pages/ceritain/selesai.php",
            data: {
                kodecerita: kodecerita
            },
            dataType: "json",
            timeout: 3000,
            success: function (data) {
                $("#confirmModal").modal('hide');
                $("#infoModal").modal('toggle');
                $("#infoModal").modal('show');
                $("#infomsg").html(data.pesan);
                if(data.result == 1){
                    tampildata();
                }
            }
        });
    });
}

function del(kodecerita){
    $("#confirmModal").modal('toggle');
    $("#confirmModal").modal('show');
    $("#confirmModal .modal-body h4").text("Yakin akan menghapus cerita ini?");

    $("#btnSave").on('click', function(){
        $.ajax({
            type: "POST",
            url: "pages/ceritain/hapus.php",
            data: {
                kodecerita: kodecerita
            },
            dataType: "json",
            timeout: 3000,
            success: function (data) {
                $("#confirmModal").modal('hide');
                $("#infoModal").modal('toggle');
                $("#infoModal").modal('show');
                $("#infomsg").html(data.pesan);
                if(data.result == 1){
                    tampildata();
                }
            }
        });
    });
}

function detail(kodecerita){
    $("#vwdata").addClass('d-none');
    $("#detail").removeClass('d-none').addClass('d-block');

    $.ajax({
        type	: "POST",
        url		: "pages/ceritain/detail.php",
        data	: {
            kodecerita: kodecerita
        },
        success: function (data) {
            $("#detail").html(data);
        }
    });
}

$("#entriceritain").on('click', function() {
    $("#vwdata").addClass('d-none');
    $("#endata").removeClass('d-none').addClass('d-block'); 
});

$('#cboguru').on('click', function() {
    $('#warningx').html('');
    $("#master-modal").modal('toggle');
    $("#master-modal").modal('show');
    tampil_masterdata();
});

function tampil_masterdata(pages) {
    let cari = $("#txtcarimaster").val();

    console.log(cari, pages);

    $.ajax({
        type: "GET",
        url: "pages/ceritain/tampil_masterdata.php",
        data: {
            cari: cari,
            page: pages
        },
        success: function (data) {
            $('#tblmaster').html(data);
            pageguru(pages);
        }
    });
}

function pageguru(pages) {
    let cari = $("#txtcarimaster").val();
    $.ajax({
        type	: "GET",		
        url		: "pages/ceritain/pagingmasterdata.php",
        data	: {
            cari: cari,
            page: pages
        },
        success	: function(data){
            $("#pagingmaster").html(data);
            $('#master-modal').on('shown.bs.modal', function(){
                $(this).find('#txtcarimaster').trigger('focus');
            });
        }
    });
}

function isi_cboguru(kodeguru,nama,notelp,tglnow) {
    $("#txtkodeguru").val(kodeguru);
    $("#cboguru").val(nama);
    $("#txtnotelp").val(notelp);
    $("#txttglajuan").val(tglnow);
    $("#master-modal").modal('hide');
}

$("#btnconfirm").on('click', function(){
    $("#warningx").text('');

    let cboguru = $("#cboguru").val();
    let kategori = $('input[name="kategorixOptions"]:checked').val(); // ambil radio kategori yg dipilih
    let metode = $('input[name="metodexOptions"]:checked').val();     // ambil radio metode yg dipilih
    let topik = $('#cbotopik').val();
    let tglajuan = $("#txttglajuan").val();
    let des = $("#txtdes").val();

    if (cboguru.length == 0) {
        $("#warningx").text('Nama Guru belum dipilih!');
        return;
    }

    if (kategori.length == 0) {
        $("#warningx").text('Kategori belum dipilih');
        return;
    }

    if (metode.length == 0) {
        $("#warningx").text('Metode belum dipilih');
        return;
    }

    if (topik.length == 0) {
        $("#warningx").text('Topik belum dipilih');
        return;
    }

    if (tglajuan.length == 0) {
        $("#warningx").text('Tanggal Ajuan masih kosong!');
        return;
    }

    if (des.length == 0) {
        $("#warningx").text('Deskripsi masih kosong!');
        return;
    }

    $("#confirmModal").modal("toggle");
    $("#confirmModal").modal("show");
})

$("#btnSave").on('click', function () {
    let kodeguru = $("#txtkodeguru").val();
    let kodecerita = $("#txtkodecerita").val();
    let notelp = $("#txtnotelp").val();
    let kategori = $('input[name="kategorixOptions"]:checked').val(); // ambil radio kategori yg dipilih
    let metode = $('input[name="metodexOptions"]:checked').val();     // ambil radio metode yg dipilih
    let topik = $('#cbotopik').val();
    let tglajuan = $("#txttglajuan").val();
    let des = $("#txtdes").val();

    console.log(kodeguru, kategori, metode, tglajuan,topik, des);

    $.ajax({
        type: "POST",
        url: "pages/ceritain/simpan.php",
        data: {
            kodeguru: kodeguru,
            kodecerita: kodecerita,
            notelp: notelp,
            kategori: kategori,
            metode: metode, // diperbaiki dari "type"
            tglajuan: tglajuan,
            topik: topik,
            des: des
        },
        dataType: "json",
        timeout: 3000,
        success: function (data) {
            $("#confirmModal").modal("hide");
            $("#infoModal").modal("toggle");
            $("#infoModal").modal("show");
            $("#infomsg").html(data.pesan);

            if (data.sukses == 1) {
                tampildata();
                clearall();

                let linkWA = "https://wa.me/" + data.notelp + "?text=" + encodeURIComponent(`[${data.kategori} ${data.type}]\n[${data.topik}],\n\nPermisi Bapak/Ibu Guru,\nSaya ${data.nama} dari kelas ${data.kelas}\n\nIngin berkonsultasi mengenai\n[Deskripsi] :\n${data.desc}\n\nKetik pesan cerita yang ingin kamu ceritakan .....`);
                setTimeout(() => {
                    window.open(linkWA, '_blank');
                    $("#infoModal").modal("hide");
                }, 1000);
            }
        }
    });
});



function clearall(){
    $("#txtkodeguru").val('');
    $("#txtnotelp").val('');
    $("#cboguru").val('');
    $("#cbokategori").val('').trigger('change');
    $("#cbotype").val('').trigger('change');
    $("#txttglajuan").val('');
    $("#txtdes").val('');
	$("#warningx").text('');
	$("#warning").text('');
    $("#counter").text('0 / Maks 100 karakter');
}

$("#btnexport").on('click', function() {
    let cari = $("#txtcari").val();
    let kategori = $("#cbokategorix").val();
    let status = $("#cbostatusx").val();

    let url = 'pages/ceritain/exportexc.php?' +
            'cari=' + encodeURIComponent(cari) + '&' +
            'kategori=' + encodeURIComponent(kategori) + '&' +
            'status=' + encodeURIComponent(status);

window.open(url, '_blank');
});