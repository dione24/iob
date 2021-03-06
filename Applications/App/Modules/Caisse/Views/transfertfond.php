<div class="row">
    <div class="col-md-12">

        <div class="white-box">
            <h3 class="box-title">Sortie de Fond</h3>
            <?php if ($_SESSION['statut'] == 'admin' or $_SESSION['statut'] == 'ChefCaisse' or $_SESSION['statut'] == 'Caissier') { ?>
            <a href="/bielletage/4" class="btn btn-primary"><i class="fa fa-plus"> Transferer</i></a> <br /> <br />
            <?php } ?>

            <div class="table-responsive">
                <table id="dataTable" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="border-top-0">ID</th>
                            <th class="border-top-0">Caisse</th>
                            <th class="border-top-0">Montant</th>
                            <th class="border-top-0">Date</th>
                            <th class="border-top-0">RECU</th>
                            <?php if ($_SESSION['statut'] == 'admin') { ?>
                            <th class="border-top-0">Actions</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ListeFond as $key => $value) { ?>
                        <tr>
                            <td>
                                <?= $value['RefOperations']; ?>
                            </td>
                            <td>
                                <?= $value['NameAgency'] . "  " . $value['NameCaisse']; ?>
                            </td>
                            <td>
                                <?= $value['MontantVersement']; ?>
                            </td>
                            <td>
                                <?= $value['Approve2_Time']; ?>
                            </td>
                            <td><a href="/bordereau/<?= $value['RefOperations']; ?>" target="_blank"
                                    class="btn btn-secondary"><i class="fa fa-print"> Reçu</i> </td>
                            <?php if ($_SESSION['statut'] == 'admin') { ?>
                            <td>
                                <a href="/Journal/delete/<?= $value['RefOperations']; ?>" class="btn btn-xs btn-danger"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');"><i
                                        class="fa fa-trash"></i></a>
                            </td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="AddTransfert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
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
                        <input type="text" class="form-control" name="MontantTransfert" id="recipient-name1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Transferer</button>
                </div>
            </form>
        </div>
    </div>
</div>