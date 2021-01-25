  <div class="row">
      <div class="col-md-12">
          <form method="POST" action="" id="formulaire">
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
                  <div class="col-md-2">Total Encaissement
                      <input type="text" value="<?= number_format($sommeVersementPeriode, 0, '.', ','); ?>"
                          class="form-control" readonly>
                  </div>
                  <div class="col-md-2">Total Retrait
                      <input type="text" value="<?= number_format($sommeRetraitPeriode, 0, '.', ','); ?>"
                          class="form-control" readonly>
                  </div>
                  <div class="col-md-2">Solde
                      <input type="text"
                          value="<?= number_format($sommeVersementPeriode - $sommeRetraitPeriode, 0, '.', ','); ?>"
                          class="form-control" readonly>
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
                              <?php if ($_SESSION['statut'] == 'admin') { ?>
                              <th class="border-top-0">ID</th>
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
                              <?php if ($_SESSION['statut'] == 'admin') { ?>
                              <td><?= $value['RefOperations']; ?></td>
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
                              <td>
                                  <a href="/Journal/delete/<?= $value['RefOperations']; ?>"
                                      class="btn btn-xs btn-danger"
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