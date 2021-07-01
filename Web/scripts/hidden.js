$(function () {
    var $produit = $('#RefProduit');
    var $hidden = $('#hidden');
    var $label = $('#label');
    $produit.on('click', function () {
        var val = $(this).val();
        $.ajax({
            url: '/config/checkhidden.php',
            data: 'produit=' + val,
            dataType: 'json',
            success: function (json) {
                $hidden.attr("style", "");
                if (json == 1) {
                    $label.text("Numéro de compte");
                }
                if (json == 2) {
                    $label.text("Téléphone");
                }
            }
        });
    })
});
