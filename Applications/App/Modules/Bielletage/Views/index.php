        <div class="row justify-content-center">
            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="white-box analytics-info">
                    <h3 class="box-title">VERSEMENT</h3>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">
                        <li>
                            <div id="sparklinedash"><canvas width="67" height="30"
                                    style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                            </div>
                        </li>
                        <li class="ml-auto"><span class="counter text-success">659</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="white-box analytics-info">
                    <h3 class="box-title">RETRAIT</h3>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">
                        <li>

                        </li>
                        <li class="ml-auto"><span class="counter text-purple">869</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="white-box analytics-info">
                    <h3 class="box-title">SOLDE</h3>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">
                        <li>

                        </li>
                        <li class="ml-auto"><span class="counter text-info">911</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="white-box">
                    <div class="d-md-flex mb-3">
                        <h3 class="box-title mb-0">OPERATIONS</h3>
                        <?php if (!empty($CheckOuverture)) { ?>
                        <div class="col-md-6 col-sm-4 col-xs-6 ml-auto">
                            <a href="/bielletage/1" class="btn btn-primary"><i class="fa fa-plus"></i>
                                VERSEMENT</a>
                            <a href="/bielletage/2" class="btn btn-warning"><i class="fa fa-plus"></i>
                                RETRAIT</a>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="table-responsive">
                        <table class="table no-wrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">AGENCE</th>
                                    <th class="border-top-0">OPERATION</th>
                                    <th class="border-top-0">CLIENT</th>
                                    <th class="border-top-0">N°COMPTE</th>
                                    <th class="border-top-0">MONTANT</th>
                                    <th class="border-top-0">REMARQUE</th>
                                    <th class="border-top-0">RECU</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($Operation as $key => $value) { ?>
                                <tr class="advance-table-row">
                                    <td> <?= $value['NameAgency']; ?></td>
                                    <td><?= $value['NameType']; ?></td>
                                    <td><?= $value['NameClient']; ?></td>
                                    <td><?= $value['NumCompte']; ?></td>
                                    <td><?= $value['MontantVersement']; ?></td>
                                    <td><?= $value['Remarque']; ?></td>
                                    <td><a href="/bordereau/<?= $value['RefOperations']; ?>" target="_blank"
                                            class="btn btn-secondary"><i class="fa fa-print"> Reçu</i> </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>