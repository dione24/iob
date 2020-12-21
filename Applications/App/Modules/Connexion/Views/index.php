<?php if ($user->hasFlash()) { ?>
    <p><?= $user->getFlash(); ?></p>
<?php }  ?>
<form method="post">
    <div class="form-group">
        <label class="small mb-1" for="inputEmailAddress">Login</label>
        <input class="form-control py-4" id="inputEmailAddress" type="text" name="login" />
    </div>
    <div class="form-group">
        <label class="small mb-1" for="inputPassword">Password</label>
        <input class="form-control py-4" id="inputPassword" type="password" name="password" />
    </div>

    <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
        <button class="btn btn-primary" type="submit">Login</button>
    </div>
</form>