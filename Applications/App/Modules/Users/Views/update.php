<form method="post">
    <div class="form-group">
        <label for="exampleInputEmail1">Login</label>
        <input type="text" class="form-control col-md-8" id="exampleInputEmail1" name="login" value="<?= $User->login(); ?>" aria-describedby="emailHelp">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" class="form-control col-md-8" name="password" value="<?= $User->password(); ?>" id="exampleInputPassword1">
    </div>
    <button type="submit" class="btn btn-primary">Créer</button>
</form>