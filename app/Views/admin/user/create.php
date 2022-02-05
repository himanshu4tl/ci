<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1><?= $title;?></h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= ADMIN_DASHBOARD_URL;?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= ADMIN_DIR;?>user/index">Users</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
            </div>
        </div>
    </div>
</section>

<div class="card card-default color-palette-box">
          <div class="card-header">
            <h3 class="card-title">
              <?= $title;?>
            </h3>
          </div>
          <div class="card-body">
        <?= view(ADMIN_DIR.'user/_form',['data'=>[]]);?>
    </div>
</div>