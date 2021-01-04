<div class="row">
    <div class="col-md-12">

        <div class="white-box">
            <h3 class="box-title">Appro Caisse</h3>
            <button type="button" class="btn btn-primary" id="button" data-toggle="modal" data-target="#AddAppro"
                data-whatever="@mdo"><i class="fa fa-plus"> Initier</i></button> <br /> <br />
            <div class="table-responsive">
                <table id="dataTable" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="border-top-0">Caisse</th>
                            <th class="border-top-0">Montant</th>
                            <th class="border-top-0">Date</th>
                            <th class="border-top-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ListeAppro as $key => $value) { ?>
                        <tr>
                            <td>
                                <?= $value['NameAgency'] . "  " . $value['NameCaisse']; ?>
                            </td>
                            <td>
                                <?= $value['MontantAppro']; ?>
                            </td>
                            <td>
                                <?= $value['DateAppro']; ?>
                            </td>
                            <td>
                                <a href="/Caisse/Appro/delete/<?= $value['RefAppro']; ?>" class="btn btn-xs btn-danger"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');"><i
                                        class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="AddAppro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <form role="form" method="post" action="">
                <div class="modal-body">

                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Caisse</label>
                        <select class="form-control" name="RefCaisse">
                            <?php foreach ($ListeCaisse as $key => $value) { ?>
                            <option value="<?= $value['RefCaisse']; ?>">
                                <?= $value['NameAgency'] . " " . $value['NameCaisse']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Montant</label>
                        <input type="text" class="form-control" name="MontantAppro" id="recipient-name1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>