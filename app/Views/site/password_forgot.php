<div class="login-box">
  <div class="login-logo">
    <b><?= APP_NAME;?></b>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Forgot Password Request</p>
      <?= alertWidget($auth->session->getFlashData('success'), 'success') ?>
      <?= alertWidget($auth->session->getFlashData('error'), 'error') ?>
    
      <form action="site/password_forgot" method="post">
        <?= csrf_field() ?>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
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
        <a href="<?= LOGIN_URL;?>">Login</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->