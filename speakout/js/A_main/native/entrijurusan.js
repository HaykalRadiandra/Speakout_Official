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

    $('#btnaddapprover').on('click', function() {	
        $("#warningx").text('');		
    
        let namajurusan 	= $("#txtnamajurusan").val();
    
        if(namajurusan.length==0){
            $('#warningx').append('Nama Jurusan masih kosong');
            $('#txtnamajurusan').focus();
            return;
        }
    
        simpandata();
    });
    
});

function tampildata(){
	let kode = $("#txtcari").val();

	$.ajax({
		type	: "GET",		
		url		: "pages/jurusan/tampildata.php",
		data	: "kode="+kode,
		success	: function(data){
			$("#tblapprover").html(data);
		}
	});
}

function simpandata(){
	$('#warningx').text('');

	let kodejurusan = $("#txtkodejurusan").val();	
	let namajurusan = $("#txtnamajurusan").val();		

		$.ajax({
			type	: "POST", 
			url		: "pages/jurusan/simpan.php",
			data	: "kodejurusan="+kodejurusan+
					"&namajurusan="+namajurusan,	
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

function edit(kodejurusan){	
	$.ajax({
		type	: "POST", 
		url		: "pages/jurusan/edit.php",
		data	: "kodejurusan="+kodejurusan,
        dataType: "json",
		success	: function(data){	
            $("#txtkodejurusan").val(data.kodejurusan);
			$("#txtnamajurusan").val(data.namajurusan);
		}	
	});
}

function del(kodejurusan){	
	$("#deleteModal").modal('toggle');
	$("#deleteModal").modal('show');	
	$("#txtkodejurusan").val(kodejurusan);
}


$('#btnHapus').on('click', function(){
	let kodejurusan = $("#txtkodejurusan").val();	

	$.ajax({
		type	: "POST",
		url		: "pages/jurusan/hapus.php",
		data	: "kodejurusan="+kodejurusan,
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
	$("#txtkodejurusan").val('')
	$("#txtnamajurusan").val('');
}