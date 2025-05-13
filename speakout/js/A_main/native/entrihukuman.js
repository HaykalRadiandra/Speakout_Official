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
    cekjenisuser();
    isi_cbojnspelanggaran();
    isi_cbojnspelanggaranx();
    isi_cbojurusan();
    // isi_cbojurusanmaster();
    // isi_cbosiswa();

    $('#endata').hide();
    $('#vwdata').show();	
    
    $(document).on("keyup", "#txtcari", function() {
        tampildata();
    });

    $(document).on("change", "#txttglawal, #txttglakhir, #cbokelasx, #cbojurusanx, #cboindeksx, #cbojnspelanggaranx, #cbostatusx, #cboterlapor", function() {
        tampildata();
    });        
    
    $(document).on('keyup', '#txtcarimaster', function(){
        tampil_masterdata();
    });

    $(document).on('change', '#cbokelasmaster, #cbojurusanmaster, #cboindeksmaster', function(){
        tampil_masterdata();
    });

    $("#notefoto").append('NOTE : Maksimal 5MB, berformat JPG atau PNG')

});

function cekjenisuser(){	
    $.ajax({
        type	: "POST",
        url		: "pages/hukuman/cekjenisuser.php",
        dataType: "json",
        success	: function(data){
            if(data.jenisuser==1){
                $('#cbokelasx').addClass('d-none');
                $('#cbojurusanx').addClass('d-none');
                $('#cboindeksx').addClass('d-none');
            } else {
                $('#cboterlapor').addClass('d-none');
            }
        }
    });
}

function tgldefault(){
    $.ajax({
        type: 'POST', 
        url: 'pages/hukuman/tampilkan_default.php',
        dataType: "json",
        success	: function(data){
            $("#txttglawal").val(data.tglawal);
            $("#txttglakhir").val(data.tglakhir);
            tampildata();
        }
    });
}

function isi_cbojnspelanggaran(cbojnspelanggaran) {
    $.ajax({
        type: 'POST',
        url: 'pages/hukuman/tampilkan_jenispelanggaran.php',
        success: function(response) {
            $('#cbojnspelanggaran').html(response);
            if (cbojnspelanggaran && cbojnspelanggaran.length > 0) {
                $('#cbojnspelanggaran').val(cbojnspelanggaran).trigger('change').attr('disabled', true);
            }
        }
    });
}

function isi_cbojnspelanggaranx(cbojnspelanggaranx){
    $.ajax({
        type: 'POST', 
        url: 'pages/hukuman/tampilkan_jenispelanggaranx.php',
        success: function(response) {
			$('#cbojnspelanggaranx').html(response); 
			if(cbojnspelanggaranx.length>0){
                $('#cbojnspelanggaranx').val(cbojnspelanggaranx).trigger('change');
			}	
		}
    });
}

function isi_cbojurusan(cbojurusan){
    $.ajax({
        type: 'POST', 
        url: 'pages/hukuman/tampilkan_jurusan.php',
        success: function(response) {
			$('#cbojurusan').html(response); 
			$('#cbojurusanx').html(response); 	
			if(cbojurusan.length>0){
                $('#cbojurusan').val(cbojurusan).trigger('change');
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
    let jnspelanggaran = $("#cbojnspelanggaranx").val();
    let status = $("#cbostatusx").val();
    let terlapor = $("#cboterlapor").val();

    $.ajax({
        type	: "GET",		
        url		: "pages/hukuman/tampildata.php",
        data	: {
            page: page,
            cari: cari,
            tglawal: tglawal,
            tglakhir: tglakhir,
            kelas: kelas,
            jurusan: jurusan,
            indeks: indeks,
            jnspelanggaran: jnspelanggaran,
            status: status,
            terlapor: terlapor 
        },
        success: function(response) {
            $("#tampildata").html(response);
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
	let jnspelanggaran = $("#cbojnspelanggaranx").val();
	let status = $("#cbostatusx").val();
    let terlapor = $("#cboterlapor").val();

    $.ajax({
        type: "GET",
        url: "pages/hukuman/paging.php",
        data: {
            page: page,
            cari: cari,
            tglawal: tglawal,
            tglakhir: tglakhir,
            kelas: kelas,
            jurusan: jurusan,
            indeks: indeks,
            jnspelanggaran: jnspelanggaran,
            status: status,
            terlapor: terlapor
        },
        success: function(response) {
            $("#pages").html(response);
        }
    });
}

function detail(kodeaduan){		
    $('#vwdata, #txtfoto, #notefoto, #btnconfirm, #labelhukuman').hide();
    $('#endata').show();
    $('#vwfoto').show();		

    $.ajax({
        type	: "POST",
        url		: "pages/hukuman/detail.php",
        data	: "kodeaduan=" + kodeaduan,
        dataType: "json",
        success	: function(data){
            $("#cbosiswa").val(data.namaterlapor).attr('disabled', true);
            $("#txtkelas").val(data.kelas).attr('disabled', true);
            isi_cbojnspelanggaran(data.jnspelanggaran);
            $("#txtket").val(data.ket).attr('disabled', true);
            if(data.kethukuman){
                $("#txtkethukuman").val(data.kethukuman).attr('disabled', true);
            } else {
                $("#txtkethukuman").val('Belum Ada Hukuman').attr('disabled', true);
            }
            if (data.foto) {
                $('#txtfotox').attr('src', 'img/pelanggaran/' + data.foto);
                $('#vwfoto').show();
                $('#notefoto').hide();
            } else {
                $('#vwfoto').hide(); 
                $('#notefoto').show().html('Belum Ada Foto');
            }
        }
    });
}

function berihukuman(kodeaduan){
    $('#vwdata, #vwfoto, #notefoto, #txtfotox, #txtlampiran, #txtfoto').hide();	
    $('#endata').show();
    
    $.ajax({
        type	: "POST",
        url		: "pages/hukuman/berihukuman.php",
        data	: "kodeaduan="+kodeaduan,
        dataType: "json",
        success	: function(data){
            $("#txtkodehukuman").val(data.kodehukuman);
            $("#cbosiswa").val(data.namaterlapor).attr('disabled', true);
            $("#txtkelas").val(data.kelas).attr('disabled', true);
            isi_cbojnspelanggaran(data.jnspelanggaran);
            $("#txtket").val(data.ket).attr('disabled', true);
            $("#txtkethukuman").val(data.kethukuman);
            $("#txtkethukuman").trigger('focus');
        }
    });
}

function selesaihukuman(kodeaduan){
    $.ajax({
        type	: "POST",
        url		: "pages/hukuman/selesaihukuman.php",
        data	: "kodeaduan="+kodeaduan,
        dataType: "json",
        success	: function(data){
            if(data.result == 1){
                tampildata();
            }
        }
    })
}

$('#btnconfirm').on('click', function(){
    $("#warningx").text('');

    let kethukuman = $("#txtkethukuman").val();

    if(kethukuman.length==0){
        $("#warningx").text('Hukuman masih kosong!');
        return;
    }

    $("#confirmModal").modal('toggle');
    $("#confirmModal").modal('show');
});

$('#btnSave').on('click', function(){
    let kodehukuman = $("#txtkodehukuman").val();
    let kethukuman = $("#txtkethukuman").val();

    $.ajax({
        type	: "POST", 
        url		: "pages/hukuman/simpan.php",
        data	: {
            kodehukuman: kodehukuman,
            kethukuman: kethukuman
        },
        dataType: "json",		
        timeout	: 3000,
        success	: function(data){
            $("#confirmModal").modal("hide");
            $("#infoModal").modal("toggle");
            $("#infoModal").modal("show");
            $("#infomsg").html(data.pesan);
            if (data.result == 1) {
                tampildata();
                clearall();
            }
        }	
    });
});

function setujui(kodeaduan){	
    $("#confirmsetujui-modal").modal('toggle');
	$("#confirmsetujui-modal").modal('show');
    $("#txtkodeaduan").val(kodeaduan);
}

$('#btnSetuju').on('click', function() {
	let kodeaduan = $("#txtkodeaduan").val();
	$.ajax({
		type	: "POST",
		url		: "pages/hukuman/setujui.php",
		data	: {
            kodeaduan: kodeaduan
        },
		dataType: "json",
		success	: function(data){
			$("#confirmsetujui-modal").modal('hide');
			$("#infoModal").modal('toggle');
			$("#infoModal").modal('show');
			$("#infomsg").html(data.pesan);
			if(data.result==1){				
				tampildata();	
			}			
		}
	});
});

function tolak(kodeaduan){	
    $("#confirmtolak-modal").modal('toggle');
    $("#confirmtolak-modal").modal('show');
    $("#txtkodeaduan").val(kodeaduan);
}

$('#btnTolak').on('click', function() {
    let kodeaduan = $("#txtkodeaduan").val();
    $.ajax({
        type	: "POST",
        url		: "pages/hukuman/tolak.php",
        data	: {
            kodeaduan: kodeaduan
        },
        dataType: "json",
        success	: function(data){
            $("#confirmtolak-modal").modal('hide');
            $("#infoModal").modal('toggle');
            $("#infoModal").modal('show');
            $("#infomsg").html(data.pesan);
            if(data.result==1){				
                tampildata();	
            }			
        }
    });
});

function del(kodeaduan){	
    $("#deleteModal").modal('toggle');
	$("#deleteModal").modal('show');
    $("#txtkodeaduan").val(kodeaduan);
}

$('#btnHapus').on('click', function() {
	let kodeaduan = $("#txtkodeaduan").val();	
	$.ajax({
		type	: "POST",
		url		: "pages/hukuman/hapus.php",
		data	: "kodeaduan="+kodeaduan,
		dataType: "json",
		success	: function(data){
			$("#deleteModal").modal('hide');
			$("#infoModal").modal('toggle');
			$("#infoModal").modal('show');
			$("#infomsg").html(data.pesan);
			if(data.result==1){				
				tampildata();	
			}			
		}
	});
});

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
    let kelas = $("#cbokelasx").val() || "";
    let jurusan = $("#cbojurusanx").val();
    let indeks = $("#cboindeksx").val() || "";
    let jnspelanggaran = $("#cbojnspelanggaranx").val();
    let status = $("#cbostatusx").val();
    let terlapor = $("#cboterlapor").val();

    if (!tglawal || !tglakhir) {
        appendAlert('<strong>Tanggal awal</strong> dan <strong>akhir</strong> tidak boleh kosong!', 'warning');
        return;
    }

    let url = 'pages/hukuman/exportexc.php?' +
            'cari=' + encodeURIComponent(cari) + '&' +
            'kelas=' + encodeURIComponent(kelas) + '&' +
            'jurusan=' + encodeURIComponent(jurusan) + '&' +
            'indeks=' + encodeURIComponent(indeks) + '&' +
            'jnspelanggaran=' + encodeURIComponent(jnspelanggaran) + '&' +
            'status=' + encodeURIComponent(status) + '&' +
            'terlapor=' + encodeURIComponent(terlapor) + '&' +
            'tglawal=' + encodeURIComponent(tglawal) + '&' +
            'tglakhir=' + encodeURIComponent(tglakhir);

window.open(url, '_blank');
});

// $('#entrimasalah').on('click', function() {
//     $('#vwfoto, #vwdata').hide();
//     $('#endata').show();
// });

// $('#cbosiswa').on('click', function() {
//     $('#warningx').html('');
//     $("#master-modal").modal('toggle');
//     $("#master-modal").modal('show');
//     tampil_masterdata();
// });

// function tampil_masterdata(pages){
//     let cari = $("#txtcarimaster").val();
//     let kelas = $("#cbokelasmaster").val();
//     let jurusan = $("#cbojurusanmaster").val();
//     let indeks = $("#cboindeksmaster").val();

//     console.log(cari, kelas, jurusan, indeks, pages);

//     $.ajax({
//         type	: "GET",		
//         url		: "pages/hukuman/tampil_masterdata.php",
//         data	: {
//             cari: cari,
//             kelas: kelas,
//             jurusan: jurusan,
//             indeks: indeks,
//             page: pages
//         },
//         success	: function(data){
//             $('#tblmaster').html(data);
//             pagesiswa(pages);
//         }
//     });
// }

// function pagesiswa(pages){
//     let cari = $("#txtcarimaster").val();
//     let kelas = $("#cbokelasmaster").val();
//     let jurusan = $("#cbojurusanmaster").val();
//     let indeks = $("#cboindeksmaster").val();

//     $.ajax({
//         type	: "GET",		
//         url		: "pages/hukuman/pagingmasterdata.php",
//         data	: {
//             cari: cari,
//             kelas: kelas,
//             jurusan: jurusan,
//             indeks: indeks,
//             page: pages
//         },
//         success	: function(data){
//             $("#pagingmaster").html(data);
//             $('#master-modal').on('shown.bs.modal', function(){
//                 $(this).find('#txtcarimaster').trigger('focus');
//             });
//         }
//     });
// }

// function isi_cbojurusanmaster(cbojurusanmaster){
// 	$.ajax({
// 		type: 'POST', 
// 		url: 'pages/hukuman/tampilkan_jurusanmaster.php',
// 		success: function(response) {
// 			$('#cbojurusanmaster').html(response); 	
// 			if(cbojurusanmaster && cbojurusanmaster.length > 0){
// 				$('#cbojurusanmaster').val(cbojurusanmaster).trigger('change');
// 			}	
// 		}
// 	});
// }

// function isi_cbosiswa(kodesiswa,nama,kelas){
//     $('#txtkodeterlapor').val(kodesiswa);
//     $('#cbosiswa').val(nama);
//     $('#txtkelas').val(kelas);
//     $("#master-modal").modal('hide'); 
// }

// $("#btnconfirm").on('click', function(){
//     $("#warningx").text('');

//     let cbosiswa = $("#cbosiswa").val();
//     let jnspelanggaran = $("#cbojnspelanggaran").val();
//     let ket = $("#txtket").val();

//     $gambar = upload();
//     if (!$gambar) {
//         return false;
//     }

//     if (cbosiswa.length == 0) {
//         $("#warningx").text('Nama Pelanggar belum dipilih!');
//         return;
//     }

//     if (jnspelanggaran.length == 0) {
//         $("#warningx").text('Jenis Pelanggaran belum dipilih');
//         return;
//     }

//     if (ket.length == 0) {
//         $("#warningx").text('Keterangan masih kosong!');
//         return;
//     }

//     $("#confirmModal").modal("toggle");
//     $("#confirmModal").modal("show");
// })

// $("#btnSave").on('click', function(){
//     let kodeaduan	 = $("#txtkodeaduan").val();
// 	let kodeterlapor = $("#txtkodeterlapor").val();
// 	let jnspelanggaran 	 = $("#cbojnspelanggaran").val();
//     let ket = $("#txtket").val();
//     let foto = $("#txtfoto")[0].files[0];

//     let formData = new FormData();
//     formData.append("kodeaduan", kodeaduan);
//     formData.append("kodeterlapor", kodeterlapor);
//     formData.append("jnspelanggaran", jnspelanggaran);
//     formData.append("ket", ket);
//     if (foto) {
//         formData.append("foto", foto);
//     }

//     $.ajax({
//         type: "POST",
//         url: "pages/hukuman/simpan.php",
//         data: formData,
//         processData: false,
//         contentType: false,
//         dataType: "json",
//         timeout: 3000,
//         success: function (data) {
//             $("#confirmModal").modal("hide");
//             $("#infoModal").modal("toggle");
//             $("#infoModal").modal("show");
//             $("#infomsg").html(data.pesan);
//             tampildata();
//             clearall();
//         }
//     });
// });

// function upload() {
//     let fileInput = document.getElementById('txtfoto');

//     if (fileInput.files.length > 0) {
//         let file = fileInput.files[0];
//         let allowedExtensions = /(\.jpg|\.png|\.jpeg)$/i; // Ekstensi file yang diizinkan
//         let maxSize = 5 * 1024 * 1024; // 5MB dalam byte

//         if (!allowedExtensions.test(file.name)) {
//             $("#warningx").text("Hanya boleh file .jpg, .jpeg atau .png");
//             fileInput.value = ""; // Reset input
//             return false;
//         } else if (file.size > maxSize) {
//             $("#warningx").text("Ukuran file maksimal 5MB!");
//             fileInput.value = ""; // Reset input
//             return false;
//         } else {
//             $("#warningx").text(""); // Bersihkan pesan error
//             return true;
//         }
//     }
// }

function clearall(){
    $("#txtkodeaduan").val('');
	$("#txtkodeterlapor").val('');
	$("#cbosiswa").val('');
	$("#txtkelas").val('');	
	$('#cbojnspelanggaran').val('').trigger('change');
	$("#txtket").val('');
    $('#txtkethukuman').val('');
	$("#txtfoto").wrap('<form>').closest('form').get(0).reset();
	$("#txtfoto").unwrap();
	$("#warningx").text('');
}