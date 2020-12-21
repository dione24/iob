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
             <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                 <thead>
                     <tr>
                         <th>ID</th>
                         <th>Title</th>
                         <th>Content</th>
                         <th>Date</th>
                         <th>Actions</th>
                     </tr>
                 </thead>
                 <tbody>
                     <?php foreach ($ListeArticle as $key => $value) { ?>
                         <tr>
                             <td><?= $value->id(); ?></td>
                             <td><?= $value->Title(); ?></td>
                             <td><?= $value->Content(); ?></td>
                             <td><?= $value->Date(); ?></td>
                             <td> <a href="/modArticle/<?= $value->id(); ?>" class="btn btn-primary"><i class="fas fa-edit"> </i> Modifier</a> <a href="/supArticle/<?= $value->id(); ?>" class="btn btn-danger"><i class="fas fa-trash"></i> Sup</a></td>
                         </tr>
                     <?php } ?>
                 </tbody>
             </table>
         </div>
     </div>
 </div>