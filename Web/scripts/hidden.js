$(function () {
    var $produit = $('#RefProduit');
    var $hidden = $('#hidden');
    $produit.on('click', function () {
        var val = $(this).val();
        $.ajax({
            url: '/config/checkhidden.php',
            data: 'produit=' + val,
            dataType: 'json',
            success: function (json) {
                if (json == 1) {
                    $hidden.html('<div class="form-group has-error"><label class="control-label">Numéro de compte</label><input type="int" id="NumCompte" class="form-control" name="NumCompte" required=""></div>');
                }
                if (json == 2) {
                    $hidden.html('<div class="form-group has-error"><label class="control-label">Télephone</label><input type="int" id="NumCompte" class="form-control" name="NumCompte" required=""></div>');

                }
            }
        });
    })
});
