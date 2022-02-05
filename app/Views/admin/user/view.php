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
                <tr><td><b>Name</b></td><td><?= $data['name']?></td></tr>
                <tr><td><b>Phone</b></td><td><?= $data['phone']?></td></tr>
                <tr><td><b>Email</b></td><td><?= $data['email']?></td></tr>
                <tr>
                  <td><b>Status</b></td>
                  <td>
                  <?php if($data['status']){
                    echo 'Active';
                    echo '&nbsp;&nbsp;<button class="btn btn-danger" data-action="'.ADMIN_DIR.'user/change_status" data-id="'.$data['id'].'" onclick="pjaxAction(this);">Mark Inacive</button>';
                    }else {
                    echo 'Inacive';
                    echo '&nbsp;&nbsp;<button class="btn btn-success" data-action="'.ADMIN_DIR.'user/change_status" data-id="'.$data['id'].'"  onclick="pjaxAction(this);">Mark Active</button>';
                  }
                  ?>
                </td>
                <tr><td><b>Created Date</b></td><td><?= date(DATETIME_FORMAT,$data['created_at']);?></td></tr>
                <tr><td><b>Updated Date</b></td><td><?= date(DATETIME_FORMAT,$data['updated_at']);?></td></tr>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>