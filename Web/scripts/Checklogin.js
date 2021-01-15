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
                    $('#statut').html('<span class="text-danger">Login non disponinble</span>');
                    $('#register').attr("disabled", true);
                } else {
                    $('#statut').html('<span class="text-success">Login disponible</span>');
                    $('#register').attr("disabled", false);
                }
            }
        });
    })
});