 <?php if ($user->hasFlash()) { ?>
     <p><?= $user->getFlash(); ?></p>
 <?php }  ?>
 <div class="card mb-4">
     <div class="card-header">
         <i class="fas fa-table mr-1"></i>
         Liste des Articles

     </div>
     <div class="card-body">
         <div class="table-responsive">
             <a href="/addCategories" class="btn btn-primary"><i class="fas fa-plus"></i> Add</a><br><br>

             <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                 <thead>
                     <tr>
                         <th>ID</th>
                         <th>Name</th>
                         <th>Group</th>
                         <th>Actions</th>
                     </tr>
                 </thead>
                 <tbody>
                     <?php foreach ($ListeCategories as $key => $value) { ?>
                         <tr>
                             <td><?= $value->id(); ?></td>
                             <td><?= $value->name(); ?></td>
                             <td><?= $value->groupe(); ?></td>
                             <td> <a href="/modCategories/<?= $value->id(); ?>" class="btn btn-primary"><i class="fas fa-edit"> </i> Modifier</a> <a href="/supCategories/<?= $value->id(); ?>" class="btn btn-danger"><i class="fas fa-trash"></i> Sup</a></td>
                         </tr>
                     <?php } ?>
                 </tbody>
             </table>
         </div>
     </div>
 </div>