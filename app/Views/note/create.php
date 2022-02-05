<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1><?= $title;?></h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= DASHBOARD_URL;?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="note/index">Notes</a></li>
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
        <?= view('note/_form',['data'=>[]]);?>
    </div>
</div>