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
    
    let delay = (function(){
        let timer = 0;
        return function(callback, ms){
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();
    $('#txtoldpassword').on('keyup',function(){
        $('#infone').html('');
        delay(function(){
            pass = $('#txtoldpassword').val();
            $.ajax({
                type:'post',
                url:'pages/updateacc/checkpass.php',
                data:{pass:pass},
                success:function(result) {
                    if (result=='ok') {
                        $('#keterangan').css('color','green').html('Password benar');
                    }else{
                        $('#keterangan').css('color','red').html('Password salah');
                    }
                }
            });
        },100);
    })

    $('#txtrepassword').on('keyup',function(){
        $('#infone').html('');
        delay(function(){
            newpass = $('#txtnewpassword').val();
            repass = $('#txtrepassword').val();
            if (newpass!==repass) {
                $('#ketrepassword').css('color','red').html('Tidak sama dengan Password Baru');
            }else{
                $('#ketrepassword').css('color','red').html('');
            }
        },100);
    })

    $('#btnconfirm').on('click',function(){
        $('#infone').html('');

        oldpass = $('#txtoldpassword').val();
        newpass = $('#txtnewpassword').val();
        repass = $('#txtrepassword').val();

        $.ajax({
            type:'post',
            url:'pages/updateacc/updatepass.php',
            data:{
                oldpass:oldpass,
                newpass:newpass,
                repass:repass
            },
            dataType:'json',
            success:function(result) {
                if (result.sukses==1) {
                    $('#infone').css('color','green').html(result.pesan);
                    clearall();
                }else{
                    $('#infone').css('color','red').html(result.pesan);
                }
            }
        });
    });
});

function clearall(){	
    $("#txtoldpassword").val('');
    $("#txtnewpassword").val('');
    $("#txtrepassword").val('');
    $('#keterangan,#ketrepassword').html('');
}
