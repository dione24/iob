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
                if ($NumCompte.val().length != 12) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000
                    });
                    Toast.fire({
                        type: 'warning',
                        title: 'Le numero compte est incorrect, Veuillez Réessayer.',
                        number: 2
                    });
                }

            }
        });
    })
});