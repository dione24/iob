 <?php if ($user->hasFlash()) { ?>
     <p><?= $user->getFlash(); ?></p>
 <?php }  ?>
 <div class="card mb-4">
     <div class="card-header">
         <i class="fas fa-table mr-1"></i>
         Liste des Utilisateurs

     </div>
     <div class="card-body">
         <a href="/addUsers" class="btn btn-primary"><i class="fas fa-plus"></i> Add</a><br><br>
         <div class="table-responsive">
             <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                 <thead>
                     <tr>
                         <th>ID</th>
                         <th>Login</th>
                         <th>Actions</th>
                     </tr>
                 </thead>
                 <tbody>
                     <?php foreach ($ListeUsers as $key => $value) { ?>
                         <tr>
                             <td><?= $value->id(); ?></td>
                             <td><?= $value->Login(); ?></td>
                             <td> <a href="/modUsers/<?= $value->id(); ?>" class="btn btn-primary"><i class="fas fa-edit"> </i> Modifier</a> <a href="/supUsers/<?= $value->id(); ?>" class="btn btn-danger"><i class="fas fa-trash"></i> Sup</a></td>
                         </tr>
                     <?php } ?>
                 </tbody>
             </table>
         </div>
     </div>
 </div>