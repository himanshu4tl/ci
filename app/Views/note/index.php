<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1><?= $title;?></h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= DASHBOARD_URL;?>">Dashboard</a></li>
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
        <a href="note/create" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> create</a>
    </div>
    <div class="card-body">
        <table style="width: 100%;" id="data-table" class="table table-hover table-striped table-bordered">
            <thead>
            <tr>
                <th>Id</th>
                <th>Title</th>
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
                url:'note/list',
                dataSrc:'data'
            },
            columns:[
                {data: "id",responsivePriority: 3},//,visible:false
                {data: "title",responsivePriority: 1},//,visible:false
                {data: "action",bSortable: false,responsivePriority: 2}
            ],
            responsive: true,
            serverSide:true,
            "order": [[ 0, "desc" ]]
        });
    });
</script>
