<div class="login-box">
    <div class="login-logo">
        <b><?= APP_NAME;?> | Admin</b>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Reset Password Request</p>
            <?= alertWidget($auth->session->getFlashData('success'), 'success') ?>
            <?= alertWidget($auth->session->getFlashData('error'), 'error') ?>

            <form action="<?= ADMIN_DIR;?>site/password_reset/<?= $password_reset_token;?>" method="post">
                <?= csrf_field() ?>
                <div class="input-group mb-3">
                    <input type="hidden" name="token" value="<?= $password_reset_token;?>">
                    <input type="password" name="password" class="form-control" placeholder="New Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password_confirm" class="form-control" placeholder="Confirm Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <p class="mb-1">
                <a href="<?= ADMIN_LOGIN_URL;?>">Login</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->