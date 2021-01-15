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
                    $('#enable').html('<div class="form-group"><label class="small mb-1" for="inputPassword">Password</label ><input class="form-control py-4" id="inputPassword" type="password" name="password"/></div >');
                    $('#register').attr("disabled", false);
                } else {
                    $('#statut').html('<span class="text-danger">Compte Introuvable</span > ');
                    $('#register').attr("disabled", true);
                }
            }
        });
    })
});