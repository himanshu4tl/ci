<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1><?= $title;?></h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= ADMIN_DASHBOARD_URL;?>">Dashboard</a></li>
                <li class="breadcrumb-item active"><?= $title;?></li>
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
        <a href="<?= ADMIN_DIR;?>user/create" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> create</a>
    </div>
    <div class="card-body">
        <table style="width: 100%;" id="data-table" class="table table-hover table-striped table-bordered">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Status</th>
                <th>Created at</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<!--TABLES -->

<!-- DataTables -->
<link rel="stylesheet" href="theme/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="theme/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  
<script src="theme/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="theme/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="theme/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="theme/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!--Bootstrap Tables-->

<script>
    $( document ).ready(function() {
        datatableObj=$('#data-table').DataTable({
            stateSave : true,
            ajax: {
                url:'<?= ADMIN_DIR;?>user/list',
                dataSrc:'data'
            },
            columns:[
                {data: "id",responsivePriority: 6},//,visible:false
                {data: "name",responsivePriority: 1},//,visible:false
                {data: "phone",responsivePriority: 3},
                {data: "email",responsivePriority: 4},
                {data: "status",responsivePriority: 4},
                {data: "created_at",responsivePriority: 5},
                {data: "action",bSortable: false,responsivePriority: 2}
            ],
            responsive: true,
            serverSide:true,
            "order": [[ 0, "desc" ]]
        });
    });
</script>
