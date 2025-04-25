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

    tampildata();

    $(document).on("keyup", "#txtcari", function() {
        tampildata();
    });

    $(document).on("change", "#cbotingkatx", function() {
        tampildata();
    });

    $('#btnaddapprover').on('click', function() {	
        $("#warningx").text('');		

        let namapelanggaran	= $("#txtnamapelanggaran").val();

        if(namapelanggaran.length==0){
            $('#warningx').append('Nama Pelanggaran masih kosong');
            $('#txtnamapelanggaran').focus();
            return;
        }
        simpandata();
    });

});

function simpandata(){
    $('#warningx').text('');
    
    let kodepelanggaran = $("#txtkodepelanggaran").val();	
	let namapelanggaran = $("#txtnamapelanggaran").val();		
	let tingkat = $("#cbotingkat").val();
    
    $.ajax({
        type	: "POST", 
        url		: "pages/jenispelanggaran/simpan.php",
        data	: {
            kodepelanggaran: kodepelanggaran,
            namapelanggaran: namapelanggaran,
            tingkat: tingkat
        },
        dataType: "json",
        success	: function(data){
            tampildata();	
            $('#warningx').html(data.pesan);
            if(data.sukses==1){
                clearall();
            }			
        }	
    });
}

function tampildata(){
let kode = $("#txtcari").val();
let tingkatx = $("#cbotingkatx").val();

$.ajax({
    type	: "GET",		
    url		: "pages/jenispelanggaran/tampildata.php",
    data	: {kode: kode, tingkatx: tingkatx},
    success	: function(data){
        $("#tblapprover").html(data);
    }
});
}

function edit(kodepelanggaran){	
	$.ajax({
		type	: "POST", 
		url		: "pages/jenispelanggaran/edit.php",
		data	: "kodepelanggaran="+kodepelanggaran,
        dataType: "json",
		success	: function(data){	
            $("#txtkodepelanggaran").val(data.kodepelanggaran);
			$("#txtnamapelanggaran").val(data.nama);
			$("#cbotingkat").val(data.tingkat);
		}	
	});
}

function del(kodepelanggaran){	
$("#deleteModal").modal('toggle');
$("#deleteModal").modal('show');	
$("#txtkodepelanggaran").val(kodepelanggaran);
}


$('#btnHapus').on('click', function(){
let kodepelanggaran = $("#txtkodepelanggaran").val();	

$.ajax({
    type	: "POST",
    url		: "pages/jenispelanggaran/hapus.php",
    data	: "kodepelanggaran="+kodepelanggaran,
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

function clearall(){
	$("#txtkodepelanggaran").val('')
	$("#txtnamapelanggaran").val('');
	$("#cbotingkat").val(1);
}