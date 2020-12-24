  <div class="row">
      <div class="col-md-12">
          <div class="white-box">
              <h3 class="box-title">Arreter de Caisse </h3>
              <div class="table-responsive">
                  <table id="dataTable" class="display nowrap" cellspacing="0" width="100%">
                      <thead>
                          <tr>
                              <th class="border-top-0">Nom</th>
                              <th class="border-top-0">Agence</th>
                              <th class="border-top-0">Actions</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php foreach ($Caisse as $key => $value) { ?>
                          <tr>
                              <td><?= $value['NameCaisse']; ?></td>
                              <td><?= $value['NameAgency']; ?></td>
                              <td><?php if (!empty($value['Valide'])) { ?><button class="btn btn-success"><i
                                          class="fa  fa-lock"></i></button> <?php } else { ?><a
                                      href="/Arreter/close/<?= $value['RefCaisse']; ?>" class="btn btn-danger"><i
                                          class="fa fa-unlock"></i></a><?php } ?>
                                  <a href="/Arreter/View/<?= $value['RefCaisse']; ?>" class="btn btn-primary"><i
                                          class="fa fa-eye"></i></a>
                              </td>
                              </td>
                          </tr>
                          <?php } ?>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>