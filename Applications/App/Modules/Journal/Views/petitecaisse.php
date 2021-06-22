  <div class="row">
      <div class="col-md-12">
          <form method="POST">
              <div class="input-group">
                  <div class="col-md-3">Jour
                      <input type="date" id="jour" name="jour" value="<?= $jour; ?>" class="form-control">
                  </div>
                  <div class="col-md-1"></br>
                      <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                  </div>
              </div>
          </form><br />
          <div class="white-box">
              <h3 class="box-title">Petite Caisse</h3>
              <div class="table-responsive">
                  <table id="dataTable" class="display nowrap" cellspacing="0" width="100%">
                      <thead>
                          <tr>
                              <th class="border-top-0">Nom</th>
                              <th class="border-top-0">Caissier</th>
                              <th class="border-top-0">Solde Espces/Caissier</th>
                              <th class="border-top-0">Solde Especes Global(+Dernier Arreté)</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php foreach ($Caisse as $value) { ?>
                          <tr>
                              <td><?= $value['NameCaisse'] . '-' . $value['NameAgency']; ?></td>
                              <td>
                                  <ul>
                                      <?php foreach ($value['FullName'] as $key => $Caissier) { ?>
                                      <li><?= $Caissier['PrenomUsers'] . ' ' . $Caissier['NomUsers']; ?></li>
                                      <?php  } ?>
                                  </ul>
                              </td>
                              <td>
                                  <ul>
                                      <?php foreach ($value['FullName'] as $key => $Solde) {  ?>
                                      <li><?= $Solde['Solde']; ?></li>
                                      <?php  } ?>
                                  </ul>
                              </td>
                              <td><?= $value['SoldeGlobal']; ?></td>
                          </tr>
                          <?php } ?>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>