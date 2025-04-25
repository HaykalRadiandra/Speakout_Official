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

    $('#tampildata').on('click', '.img-popup', function() {
        const imageSrc = $(this).data('img');
        console.log("Clicked image path:", imageSrc);
        $('#modalImage').attr('src', imageSrc);
        $('#imageModal').modal('show');
    });

    // $('.img-popup').on('click', function() {
    //     const imageSrc = $(this).data('img'); 
    //     console.log("Image source: ", imageSrc); // Tambahkan ini
    //     $('#modalImage').attr('src', imageSrc);
    //     $('#imageModal').modal('show');
    // });
    
    tampildata();
    $("#txtcari").on("keyup", function() {
        tampildata();
    })

    $(".btn-outline-primary").on("click", function () {
        $(".btn-outline-primary").removeClass("active");
        $(this).addClass("active");
        tampildata(); // baru di sini panggil AJAX-nya
    });
    
    
});

function tampildata() {
    let cari = $("#txtcari").val();
    let urut = $(".btn-outline-primary.active").val() || "1"; // default ke '1' jika belum ada yg aktif

    $.ajax({
        type: "GET",
        url: "pages/education/tampildata.php",
        data: {
            cari: cari,
            urut: urut
        },
        success: function (data) {
            $("#tampildata").html(data);
        }
    });
}
