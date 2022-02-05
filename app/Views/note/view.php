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
                <li class="breadcrumb-item active">View</li>
            </ol>
            </div>
        </div>
    </div>
</section>

<div class="card" id="ajax-container">
    <div class="card-header">
        <h3 class="card-title"><?= $title;?></h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body p-0" id="ajax-content">
        <table class="table table-striped">
            <tbody>
                <tr><td><b>Title</b></td><td><?= $data['title']?></td></tr>
                <tr><td><b>Date</b></td><td><?= $data['date']?></td></tr>
                <tr><td><b>Note</b></td><td><?= $data['note']?></td></tr>
                <tr><td><b>Created Date</b></td><td><?= date(DATETIME_FORMAT,$data['created_at']);?></td></tr>
                <tr><td><b>Updated Date</b></td><td><?= date(DATETIME_FORMAT,$data['updated_at']);?></td></tr>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>