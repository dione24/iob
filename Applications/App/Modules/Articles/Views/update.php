<form method="post">
    <div class="form-group">
        <label for="exampleInputEmail1">Titre</label>
        <input type="text" class="form-control col-md-8" id="exampleInputEmail1" name="Title" value="<?= $Article->Title(); ?>" aria-describedby="emailHelp">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Content</label>
        <input type="text" class="form-control col-md-8" name="Content" value="<?= $Article->Content(); ?>" id="exampleInputPassword1">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Date</label>
        <input type="date" class="form-control col-md-8" name="Date" value="<?= date('Y-m-d', strtotime($Article->Date())); ?>" id="exampleInputPassword1">
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
</form>