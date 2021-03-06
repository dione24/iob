  <?php if ($_SESSION['statut'] == 'ChefCaisse' or $_SESSION['statut'] == 'Caissier') { ?>
  <div class="col-lg-12 col-sm-12 col-xs-12">
      <?php foreach ($Agence as $key => $value) {
                if ($value['SommeDepot'] ==  0) { ?>
      <div class="alert alert-danger" role="alert">
          <span class="badge badge-danger"><?= $value['NameAgency']; ?> | Appro</span> Le solde
          de la reserve est : <?= number_format($value['YesterdayReserve'], 0, '.', ','); ?> | Merci d'Approvisonner
          l'Agence
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <?php  }
            } ?>
  </div>
  <?php } ?>
  <div class="row justify-content-center">
      <div class="col-lg-3 col-sm-6 col-xs-12">
          <div class="white-box analytics-info">
              <h3 class="box-title">VERSEMENT</h3>
              <ul class="list-inline two-part d-flex align-items-center mb-0">
                  <li class="ml-auto"><span
                          class="counter text-danger"><?= number_format($SommeVersement, 0, '.', ','); ?></span>
                  </li>
              </ul>
              <span>CAISSE</span>
          </div>
      </div>
      <div class="col-lg-3 col-sm-6 col-xs-12">
          <div class="white-box analytics-info">
              <h3 class="box-title">RETRAIT</h3>
              <ul class="list-inline two-part d-flex align-items-center mb-0">
                  <li class="ml-auto"><span
                          class="counter text-purple"><?= number_format($SommeRetrait, 0, '.', ','); ?></span>
                  </li>
              </ul>
              <span>CAISSE</span>
          </div>
      </div>
      <div class="col-lg-3 col-sm-6 col-xs-12">
          <div class="white-box analytics-info">
              <h5 class="box-title">SOLDE ESPECES</h5>
              <ul class="list-inline two-part d-flex align-items-center mb-0">
                  <li class="ml-auto"><span class="counter text-info">
                          <?= number_format($Solde, 0, '.', ','); ?>
                      </span>
                  </li>
              </ul>
              <span>CAISSE</span>
          </div>
      </div>
      <div class="col-lg-3 col-sm-6 col-xs-12">
          <div class="white-box analytics-info">
              <iframe
                  src="https://www.zeitverschiebung.net/clock-widget-iframe-v2?language=fr&size=small&timezone=Africa%2FBamako"
                  width="100%" height="90" frameborder="0" seamless></iframe>
          </div>
      </div>
  </div>
  <div class="row">
      <div class="col-md-12 col-lg-12 col-sm-12">
          <div class="white-box">
              <div class="d-md-flex mb-3">
                  <h3 class="box-title mb-0">OPERATIONS DU <?= date('d-m-Y'); ?></h3>
              </div>
              <div class="table-responsive">
                  <table class="table no-wrap">
                      <thead>
                          <tr>
                              <th class="border-top-0">AGENCE</th>
                              <th class="border-top-0">CAISSE</th>
                              <th class="border-top-0">PRODUIT</th>
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
                              <td> <?= $value['NameCaisse']; ?></td>
                              <td> <?= $value['NameProduit']; ?></td>
                              <td><?= $value['NameType']; ?></td>
                              <td><?= $value['NameClient']; ?></td>
                              <td><?= $value['NumCompte']; ?></td>
                              <td class="counter text-danger">
                                  <?= number_format($value['MontantVersement'], 0, '.', ','); ?></td>
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
  <div class="row">
      <div class="col-lg-4 col-md-12 col-sm-12">
          <div class="card">
              <div class="card-heading">
                  Bielletage du Jour
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
      <div class="col-lg-4 col-md-12 col-sm-12">
          <div class="card">
              <div class="card-heading">
                  Solde du Jour
              </div>
              <div class="card-body">
                  <table class="table">
                      <tbody>
                          <tr>
                              <td>Versement</td>
                              <td><span class="counter text-danger">
                                      <?= number_format($SommeVersement, 0, '.', ','); ?>
                                  </span>
                              </td>
                          </tr>
                          <tr>
                              <td>Retrait</td>
                              <td><span class="counter text-danger">
                                      <?= number_format($SommeRetrait, 0, '.', ','); ?>
                                  </span>
                              </td>
                          </tr>
                          <tr>
                              <td>Solde Especes</td>
                              <td><span class="counter text-danger">
                                      <?= number_format($Solde, 0, '.', ','); ?>
                                  </span>
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
      <div class="col-lg-4 col-md-12 col-sm-12">
          <div class="card">
              <div class="card-heading">
                  Info Caisse
              </div>
              <div class="card-body">
                  <table class="table">
                      <tbody>
                          <tr>
                              <td>Lundi</td>
                              <td>Versement</td>
                              <td>Retrait</td>
                          </tr>
                          <tr>
                              <td></td>
                              <td class="counter text-danger">
                                  <?= number_format($DailyVersement['LundiVersement'], 0, '.', ','); ?></td>
                              <td class="counter text-danger">
                                  <?= number_format($DailyVersement['LundiRetrait'], 0, '.', ','); ?></td>
                          </tr>
                          <tr>
                              <td>Mardi</td>
                              <td>Versement</td>
                              <td>Retrait
                              </td>
                          </tr>
                          <tr>
                              <td></td>
                              <td class="counter text-danger">
                                  <?= number_format($DailyVersement['MardiVersement'], '0', '.', ','); ?></td>
                              <td class="counter text-danger">
                                  <?= number_format($DailyVersement['MardiRetrait'], 0, '.', ','); ?></td>
                          </tr>
                          <tr>
                              <td>Mercredi</td>
                              <td>Versement</td>
                              <td>Retrait
                              </td>
                          </tr>
                          <tr>
                              <td></td>
                              <td class="counter text-danger">
                                  <?= number_format($DailyVersement['MercrediVersement'], 0, '.', ','); ?></td>
                              <td class="counter text-danger">
                                  <?= number_format($DailyVersement['MercrediRetrait'], 0, '.', ','); ?></td>
                          </tr>
                          <tr>
                              <td>Jeudi</td>
                              <td>Versement</td>
                              <td>Retrait
                              </td>
                          </tr>
                          <tr>
                              <td></td>
                              <td class="counter text-danger">
                                  <?= number_format($DailyVersement['JeudiVersement'], 0, '.', ','); ?></td>
                              <td class="counter text-danger">
                                  <?= number_format($DailyVersement['JeudiRetrait'], 0, '.', ','); ?></td>
                          </tr>
                          <tr>
                              <td>Vendredi</td>
                              <td>Versement</td>
                              <td>Retrait
                              </td>
                          </tr>
                          <tr>
                              <td></td>
                              <td class="counter text-danger">
                                  <?= number_format($DailyVersement['VendrediVersement'], 0, '.', ','); ?></td>
                              <td class="counter text-danger">
                                  <?= number_format($DailyVersement['VendrediRetrait'], 0, '.', ','); ?></td>
                          </tr>
                          <tr>
                              <td>Samedi</td>
                              <td>Versement</td>
                              <td>Retrait
                              </td>
                          </tr>
                          <tr>
                              <td></td>
                              <td class="counter text-danger">
                                  <?= number_format($DailyVersement['SamediVersement'], 0, '.', ','); ?></td>
                              <td class="counter text-danger">
                                  <?= number_format($DailyVersement['SamediRetrait'], 0, '.', ','); ?></td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>