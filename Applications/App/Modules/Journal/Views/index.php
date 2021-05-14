  <div class="row">
      <div class="col-md-12">
          <form method="POST" action="/Journal/index" id="formulaire">
              <div class="input-group">
                  <div class="col-md-2">Caisse
                      <select class="form-control" name="RefCaisse" tabindex="1" required="">
                          <?php foreach ($UserCaisse as $key => $Caisse) {
                            ?>
                          <option value="<?= $Caisse['RefCaisse']; ?>" <?php if ($Caisse['RefCaisse'] == $Value) { ?>
                              selected="" <?php } ?>>
                              <?= $Caisse['NameCaisse'] . " " . $Caisse['NameAgency']; ?></option>
                          <?php }   ?>
                      </select>
                  </div>
                  <div class="col-md-2">Du
                      <input type="date" id="Debut" name="Debut" value="<?= $Debut; ?>" class="form-control ">
                  </div>
                  <div class="col-md-2">Au
                      <input type="date" id="Fin" name="Fin" value="<?= $Fin; ?>" class="form-control"
                          onchange="document.getElementById('formulaire').submit();">
                  </div>
                  <div class="col-md-2">Total Versement
                      <input type="text" value="<?= number_format($sommeVersementPeriode, 0, '.', ','); ?>"
                          class="form-control" readonly>
                  </div>
                  <div class="col-md-2">Total Retrait
                      <input type="text" value="<?= number_format($sommeRetraitPeriode, 0, '.', ','); ?>"
                          class="form-control" readonly>
                  </div>
                  <div class="col-md-2">Solde Especes
                      <input type="text" value="<?= number_format($Solde, 0, '.', ','); ?>" class="form-control"
                          readonly>
                  </div>
              </div>
          </form>
          <br />
          <div class="white-box">
              <h3 class="box-title">Journal de Caisse </h3>
              <div class="table-responsive">
                  <table id="dataTable" class="display nowrap" cellspacing="0" width="100%">
                      <thead>
                          <tr>

                              <th class="border-top-0">ID</th>
                              <?php if ($_SESSION['statut'] == 'admin' or $_SESSION['statut'] == 'Control') { ?>
                              <th class="border-top-0">Statut</th>
                              <?php } ?>
                              <th class="border-top-0">Agence</th>
                              <th class="border-top-0">Operation</th>
                              <th class="border-top-0">Client</th>
                              <th class="border-top-0">Numero de Compte</th>
                              <th class="border-top-0">Montant</th>
                              <th class="border-top-0">Remarque</th>
                              <th class="border-top-0">Date</th>
                              <th class="border-top-0">Caissier</th>
                              <th class="border-top-0">RECU</th>
                              <?php if ($_SESSION['statut'] == 'admin') { ?>
                              <th class="border-top-0">Action</th>
                              <?php } ?>
                          </tr>
                      </thead>
                      <tbody>
                          <?php foreach ($Operations as $key => $value) { ?>
                          <tr>

                              <td
                                  style="<?php if ($value['Validate'] == 2 && ($_SESSION['statut'] == 'admin' or $_SESSION['statut'] == 'Niveau1')) { ?> background-color:#7ace4c;  <?php } elseif ($value['Validate'] == 1 && ($_SESSION['statut'] == 'admin' or $_SESSION['statut'] == 'Niveau1')) { ?> background-color: #f33155; <?php   } ?>">
                                  <?= $value['RefOperations']; ?></td>
                              <?php if ($_SESSION['statut'] == 'admin' or $_SESSION['statut'] == 'Control') { ?>
                              <td> <?php if ($value['Validate'] == 1) { ?> <button class="btn btn-danger"
                                      data-toggle="modal" data-target="#modal-<?= $value['RefOperations']; ?>">Non
                                      Vérifiée </button> <?php } else { ?> <a
                                      href="/Journal/cancelvalidate/<?= $value['RefOperations']; ?>"
                                      class="btn btn-success"
                                      onclick="return confirm('Êtes-vous sûr de vouloir annuler cette vérifcation ?');">
                                      Verifiée le <span><?= $value['DateValidate']; ?></span></a>
                                  <?php   } ?>
                              </td>
                              <?php } ?>
                              <td><?= $value['NameAgency']; ?></td>
                              <td><?= $value['NameType']; ?></td>
                              <td><?= $value['NameClient']; ?></td>
                              <td><?= $value['NumCompte']; ?></td>
                              <td><?= $value['MontantVersement']; ?></td>
                              <td><?= $value['Remarque']; ?></td>
                              <td><?= date('d/m/Y', strtotime($value['Approve2_Time'])); ?></td>
                              <td><?= $value['login']; ?></td>
                              <td><a href="/bordereau/<?= $value['RefOperations']; ?>" target="_blank"
                                      class="btn btn-secondary"><i class="fa fa-print"> Reçu</i> </td>
                              <?php if ($_SESSION['statut'] == 'admin') { ?>
                              <td><a href="/Journal/delete/<?= $value['RefOperations']; ?>"
                                      class="btn btn-xs btn-danger"
                                      onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');"><i
                                          class="fa fa-trash"></i></a>
                              </td>
                              <?php } ?>
                          </tr>
                          <!--modalStatut-->
                          <div class="modal fade" id="modal-<?= $value['RefOperations']; ?>" tabindex="-1" role="dialog"
                              aria-labelledby="modalStatut" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title" id="exampleModalLabel">Confirmation de l'Opération
                                          </h5>
                                      </div>
                                      <form role="form" method="post" action="/Journal/validate">
                                          <div class=" modal-body">
                                              <div class="modal-body">
                                                  <input type="hidden" class="form-control" name="RefOperations"
                                                      value="<?= $value['RefOperations']; ?>">
                                                  <div class="form-group">
                                                      <input type="date" class="form-control" name="DateValidate">
                                                  </div>
                                                  <input type="hidden" id="Debut" name="Debut" value="<?= $Debut; ?>"
                                                      class="form-control ">
                                                  <input type="hidden" id="Fin" name="Fin" value="<?= $Fin; ?>"
                                                      class="form-control ">
                                                  <input type="hidden" id="RefCaisse" name="RefCaisse"
                                                      value="<?= $value['RefCaisse']; ?>" class="form-control ">
                                              </div>
                                          </div>
                                          <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary"
                                                  data-dismiss="modal">Fermer</button>
                                              <button type="submit" class="btn btn-primary">Confirmer</button>
                                          </div>
                                      </form>
                                  </div>
                              </div>
                          </div>
                          <!--modalStatut-->
                          <?php } ?>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="card">
              <div class="card-heading">
                  Bielletage
              </div>
              <div class="card-body">
                  <table class="table">
                      <tbody>
                          <tr>
                              <td>10.000</td>
                              <td class="counter text-danger"><?= $Biellet['dixmille'] ?></td>
                              <td>250</td>
                              <td class="counter text-danger"><?= $Biellet['deuxcentcinq']; ?></td>
                          </tr>
                          <tr>
                              <td>5.000</td>
                              <td class="counter text-danger"><?= $Biellet['cinqmille']; ?></td>
                              <td>200</td>
                              <td class="counter text-danger"><?= $Biellet['deuxcent']; ?></td>
                          </tr>
                          <tr>
                              <td>2.000</td>
                              <td class="counter text-danger"><?= $Biellet['deuxmille']; ?></td>
                              <td>100</td>
                              <td class="counter text-danger"><?= $Biellet['cent']; ?></td>
                          </tr>
                          <tr>
                              <td>1.000</td>
                              <td class="counter text-danger"><?= $Biellet['mille']; ?></td>
                              <td>50</td>
                              <td class="counter text-danger"><?= $Biellet['cinquante']; ?></td>
                          </tr>
                          <tr>
                              <td>500</td>
                              <td class="counter text-danger"><?= $Biellet['cinqcent']; ?></td>
                              <td>25</td>
                              <td class="counter text-danger"><?= $Biellet['vingtcinq']; ?></td>
                          </tr>
                          <tr>
                              <td>10</td>
                              <td class="counter text-danger"><?= $Biellet['dix']; ?></td>
                              <td>5</td>
                              <td class="counter text-danger"><?= $Biellet['cinq']; ?></td>
                          </tr>
                          <tr>
                              <td style="border: none !important;"></td>
                              <td style="border: none !important;"></td>
                              <td>1</td>
                              <td class="counter text-danger"><?= $Biellet['un']; ?></td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>