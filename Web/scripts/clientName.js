$(function () {
    var $NumCompte = $('#NumCompte');
    var $NameClient = $('#NameClient');
    $NumCompte.on('change', function () {
        var val = $(this).val();
        if (val != null) $NameClient.empty();
        $.ajax({
            url: '/config/client.php',
            data: 'NumCompte=' + val,
            dataType: 'json',
            success: function (json) {
                if (json != null) {
                    $NameClient.val(json);
                } else {
                    $NameClient.val('');
                }
            }
        });
    })
});