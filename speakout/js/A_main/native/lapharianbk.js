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
    
    $(".datepickerr").datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
        todayHighlight: true,
        language: "id"
    });

    settgldefa();

    $(document).on("change", "#tgltrx", "#tgltrx2", function() {
        setjdllap();
    })

});

function settgldefa(){
    $.ajax({
        type	: "POST",		
        url		: "pages/lapharianbk/tgldefa.php",
        dataType: "json",		
        success	: function(data){	
            $("#tgltrx").val(data.tglsatu);
            $("#tgltrx2").val(data.tgltrx);	
            setjdllap();	
        }
    });	
}

function setjdllap(){	
    let tgltrx = $("#tgltrx").val();
    let tgltrx2 = $("#tgltrx2").val();
    
    $.ajax({
        type	: "POST",		
        url		: "pages/lapharianbk/jdllap.php",
        data	: {
            tgltrx: tgltrx,
            tgltrx2: tgltrx2
        },
        dataType: "json",		
        success	: function(data){	
            //$("#lblalamat").text(data.alamat);
            $("#lbltgltrx").text(data.tgltrx);
            tampildata();	
        }
    });	
}

function tampildata(){	
    let tgltrx = $("#tgltrx").val();
    let tgltrx2 = $("#tgltrx2").val();
    
    $.ajax({
        type	: "GET",		
        url		: "pages/lapharianbk/tampildata.php",
        data	: {
            tgltrx: tgltrx,
            tgltrx2: tgltrx2
        },
        success	: function(data){	
            $("#tampildata").html(data);
        }
    });
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
    let tgltrx = $("#tgltrx").val();
    let tgltrx2 = $("#tgltrx2").val();

    if (!tgltrx || !tgltrx2) {
        appendAlert('<strong>Tanggal awal</strong> dan <strong>akhir</strong> tidak boleh kosong!', 'warning');
        return;
    }

    let url = 'pages/lapharianbk/exportexc.php?' +
            'tglawal=' + encodeURIComponent(tgltrx) + '&' +
            'tglakhir=' + encodeURIComponent(tgltrx2);

window.open(url, '_blank');
});
