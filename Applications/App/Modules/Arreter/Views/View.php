  <div class="row">
      <div class="col-md-12">
          <div class="white-box">
              <h3 class="box-title">Arreter de Caisse </h3>
              <div class="table-responsive">
                  <table id="dataTable" class="display nowrap" cellspacing="0" width="100%">
                      <thead>
                          <tr>
                              <th class="border-top-0">Date</th>
                              <th class="border-top-0">Ouv-Especes</th>
                              <th class="border-top-0">Ouv-Omni</th>
                              <th class="border-top-0">Versement</th>
                              <th class="border-top-0">Retrait</th>
                              <th class="border-top-0">Appro</th>
                              <th class="border-top-0">Fr-Espces</th>
                              <th class="border-top-0">Fr-Omni</th>
                              <th class="border-top-0">Bielletage</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php foreach ($GetRapports as $key => $value) { ?>
                              <tr>
                                  <td><?= date('d/m/Y', strtotime($value['Date'])); ?></td>
                                  <td><?= $value['SoldeOvEspeces']; ?></td>
                                  <td><?= $value['SoldeOvOmni']; ?></td>
                                  <td><?= $value['Versement']; ?></td>
                                  <td><?= $value['Retrait']; ?></td>
                                  <td><?= $value['ApproOmni']; ?></td>
                                  <td><?= $value['SoldeFrEspeces']; ?></td>
                                  <td><?= $value['SoldeFrOmni']; ?> </td>
                                  <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Bielletage-<?= $value['RefRapport']; ?>" data-whatever="@mdo"><i class="fa fa-eye"></i></button>
                                  </td>

                              </tr>
                              <div class="modal fade" id="Bielletage-<?= $value['RefRapport']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                                  <div class="modal-dialog" role="document">
                                      <div class="modal-content">

                                          <div class="modal-body">
                                              <div style="float:right;">
                                                  <button onclick="window.print()" class="btn btn-secondary"><i class="fa fa-print">

                                                      </i>
                                                  </button>
                                              </div>
                                              <div class="row"></div>
                                              <div class="form-group">
                                                  <p>10.000 ----<?= $Biellet['dixmille'] ?>
                                                  </p>
                                                  <p>5.000 ----<?= $Biellet['cinqmille']; ?></p>
                                                  <p>2.000 ----<?= $Biellet['deuxmille']; ?></p>
                                                  <p>1.000 ----<?= $Biellet['mille']; ?> </p>
                                                  <p>500 ----<?= $Biellet['cinqcent']; ?></p>
                                              </div>
                                              <div class="form-group">
                                                  <p>250 ----<?= $Biellet['deuxcentcinq']; ?></p>
                                                  <p>200 ----<?= $Biellet['deuxcent']; ?></p>
                                                  <p>100 ----<?= $Biellet['cent']; ?></p>
                                                  <p>50 ----<?= $Biellet['cinquante']; ?></p>
                                                  <p>25 ----<?= $Biellet['vingtcinq']; ?></p>
                                                  <p>10 ----<?= $Biellet['dix']; ?></p>
                                                  <p>5 ----<?= $Biellet['cinq']; ?></p>
                                                  <p>1 ----<?= $Biellet['un']; ?></p>
                                              </div>
                                              <div style="float:right;">
                                                  <button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>
                                              </div>
                                          </div>
                                      </div>

                                  </div>

                              </div>
              </div>
          <?php } ?>
          </tbody>
          </table>
          </div>
      </div>
  </div>
  </div>