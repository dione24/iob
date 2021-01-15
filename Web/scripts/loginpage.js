$(function () {
    var $login = $('#login');
    var $statut = $('#statut');
    $login.on('change', function () {
        var val = $(this).val();
        if (val != null) $statut.empty();
        $.ajax({
            url: '/config/checklogin.php',
            data: { 'login': val },
            dataType: 'json',
            success: function (json) {
                if (json != null) {
                    $('#register').attr("disabled", false);
                } else {
                    $('#statut').html('<span class="text-danger">Compte Introuvable</span > ');
                    $('#register').attr("disabled", true);
                }
            }
        });
    })
});