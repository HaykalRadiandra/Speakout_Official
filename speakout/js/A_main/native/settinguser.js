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

    cekjenisuser();
});

function cekjenisuser(){	
    $.ajax({
        type	: "POST",
        url		: "pages/user/cekjenisuser.php",
        dataType: "json",
        success	: function(data){
            if(data.jenisuser==1){
                $('#vwguru').hide();
                $('#vwsiswa').show();
                getdatasiswa();
            }else if(data.jenisuser==9){
                $('#vwguru').show();
                $('#vwsiswa').hide();
                getdataguru();
            }
        }
    });
}

function getdataguru(){		
    $.ajax({
        type	: "POST",
        url		: "pages/user/getdataguru.php",
        dataType: "json",
        success	: function(data){
            $("#txtusernameguru").val(data.username).attr('readonly', true);
            $("#txtroleguru").val(data.role).attr('readonly', true);
            $("#txtnamaguru").val(data.nama).attr('readonly', true);
            $("#txtnip").val(data.nip).attr('readonly', true);
            $("#txtalamatguru").val(data.alamat).attr('readonly', true);
            $("#txtnotelpguru").val(data.notelp).attr('readonly', true);
        }
    });
}

function getdatasiswa(){		
    $.ajax({
        type	: "POST",
        url		: "pages/user/getdatasiswa.php",
        dataType: "json",
        success	: function(data){
            $("#txtusernamesiswa").val(data.username).attr('readonly', true);
            $("#txtrolesiswa").val(data.role).attr('readonly', true);
            $("#txtnamasiswa").val(data.nama).attr('readonly', true);
            $("#cbokelas").val(data.kelas).attr('readonly', true);
            $("#txtkelaslengkap").val(data.kelaslengkap).attr('readonly', true);
            $('#cbojurusan').val(data.kodejurusan).attr('readonly', true);
            $("#cboindeks").val(data.indeks).attr('readonly', true);
            $("#txtalamatsiswa").val(data.alamat).attr('readonly', true);
            $("#txtnotelpsiswa").val(data.notelp).attr('readonly', true);
            $("#txttgllahir").val(data.tgllahir).attr('readonly', true);
        }
    });
}
