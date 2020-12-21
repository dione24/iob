<form method="post">
    <div class="form-group">
        <label for="exampleInputEmail1">Name</label>
        <input type="text" class="form-control col-md-8" id="exampleInputEmail1" name="name" value="<?= $Categories->name(); ?>" aria-describedby="emailHelp">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Group</label>
        <input type="text" class="form-control col-md-8" name="groupe" value="<?= $Categories->groupe(); ?>" id="exampleInputPassword1">
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
</form>