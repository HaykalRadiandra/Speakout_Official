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

    // ! BATASAN

    tampildata();
    tgldefault();
    isi_cbojurusan();

    $(document).on('change', '#cbothun, #cbojurusan, #cbokelas',  function() {
        tampildata();
    })
});

function tgldefault(){
    $.ajax({
        type: 'POST',
        url: 'pages/info/tampilkan_default.php',
        success: function(response) {
            $('#cbothun').html(response);
            tampildata();
        }
    });
}


function isi_cbojurusan(cbojurusan){
    $.ajax({
        type: 'POST', 
        url: 'pages/info/tampilkan_jurusan.php',
        success: function(response) {
			$('#cbojurusan').html(response); 
			if(cbojurusan.length>0){
                $('#cbojurusan').val(cbojurusan).trigger('change');
			}	
		}
    });
}

function tampildata(){
    let cbothun = $('#cbothun').val();
    let cbojurusan = $('#cbojurusan').val();
    let cbokelas = $('#cbokelas').val();
    $.ajax({
        type: 'POST',
        url: 'pages/info/tampildata.php',
        data: {
            cbotahun: cbothun,
            cbojurusan: cbojurusan,
            cbokelas: cbokelas
        },
        dataType: 'json',
        success: function(data) {
            console.log(data);
            $('#txtaduan').html(data.totaladuan);
            $('#txthukuman').html(data.totalhukuman);
            $('#txtceritain').html(data.totalceritain || 0);
            $('#txtpersetujuan').html(data.menunggudisetujui);
            $('#txttolak').html(data.ditolak);
            $('#txtsetuju').html(data.disetujui);
        }
    });    
}