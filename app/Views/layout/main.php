<?php
$user=$auth->identity();
$currentUrl=uri_string().'?'.$_SERVER['QUERY_STRING'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <base href="<?= BASE_URL;?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <?= csrf_meta(); ?>
    <title><?= $title;?> | <?= APP_NAME;?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="theme/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="theme/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="theme/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- jQuery -->
    <script src="theme/plugins/jquery/jquery.min.js"></script>
    <script>
        var datatableObj=false;
    </script>
    <style>
        .pull-right{
            float:right;
        }
        label.error{
            color:red;
        }
        .form-control.error{
            border-color:red;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed ">

<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
<!--            <li class="nav-item d-none d-sm-inline-block">-->
<!--                <a href="dashboard" class="nav-link">Dashboard</a>-->
<!--            </li>-->
<!--            <li class="nav-item d-none d-sm-inline-block">-->
<!--                <a href="ledger" class="nav-link">Ledger</a>-->
<!--            </li>-->
        </ul>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
        </ul>
    </nav>
    <!-- /.navbar -->


    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary">
        <!-- Brand Logo -->
        <a href="index3.html" class="brand-link">
            <img src="<?= APP_LOGO;?>" class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light"><?= APP_NAME;?></span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="<?=  getFileUrl((isset($user['image'])?$user['image']:''));?>" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="site/profile" class="d-block"><?= $user['name'];?></a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <li class="nav-item"><a href="site/dashboard" class="nav-link"><i class="fa fa-tachometer-alt nav-icon"></i><p> Dashboard</p></a></li>
                    <li class="nav-item"><a href="note" class="nav-link"><i class="fa fa-users nav-icon"></i><p> Notes</p></a></li>
                    <li class="nav-item"><a href="site/logout" class="nav-link"><i class="fa fa-sign-out-alt nav-icon"></i><p> Logout</p></a></li>

                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <div class="content-wrapper">
    <?= alertWidget($auth->session->getFlashData('success'), 'success') ?>
    <?= alertWidget($auth->session->getFlashData('error'), 'error') ?>
    <?= alertWidget($auth->session->getFlashData('info'), 'info') ?>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->

                <?= $content?>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; <?= date('Y')?> <a href="https://lembits.com">Lembits Technolab Pvt. Ltd.</a></strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>
    <!--SCRIPTS INCLUDES-->
<!-- Bootstrap 4 -->
<script src="theme/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="theme/plugins/jquery-validation/jquery.validate.min.js"></script>

<!-- AdminLTE App -->
<script src="theme/dist/js/adminlte.js"></script>
<!--SweetAlert2-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

<script>
    function datatableDeleteAction(obj){
        var $obj=$(obj);
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes'
            }).then((result) => {
            if (result.value) {
                $.ajax({
                    url:$obj.data('action'),
                    method:'post',
                    data:{id:$obj.data('id'),'<?= csrf_token();?>':'<?= csrf_hash();?>'},
                    dataType:'json',
                    success:function(response){
                        if(response.status){
                            Swal.fire('Success',response.message,'success')
                            .then(function(result){
                                datatableObj.ajax.reload();
                            })          
                        }else{
                            Swal.fire('Warning',response.message,'warning')
                        }
                    },
                    error:function(e){
                        console.log(e);
                    }
                });
            }
        })        
    }

    function pjaxAction(obj){
        var $obj=$(obj);
        Swal.fire({
            title: 'Are you sure?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes'
            }).then((result) => {
            if (result.value) {
                $.ajax({
                    url:$obj.data('action'),
                    method:'post',
                    data:{id:$obj.data('id'),'<?= csrf_token();?>':'<?= csrf_hash();?>'},
                    dataType:'json',
                    success:function(response){
                        if(response.status){
                            Swal.fire('Success',response.message,'success')
                            .then(function(result){
                                $('#ajax-container').load('<?= $currentUrl;?> #ajax-content');
                            })          
                        }else{
                            Swal.fire('Warning',response.message,'warning')
                        }
                    },
                    error:function(e){
                        console.log(e);
                    }
                });
            }
        })        
    }
    
    function ajaxForm(event,form){
        console.log(form);
        form=$(form);
        event.preventDefault();
        $.ajax({
            url:form.attr('action'),
            method:'post',
            data:form.serialize(),
            dataType:'json',
            success:function(response){
                if(response.status){
                    Swal.fire('Success',response.message,'success')
                    .then(function(result){
                        window.location=form.data('redirect');
                    })          
                }else{
                    Swal.fire('Warning',response.message,'warning')
                }
            },
            error:function(e){
                console.log(e);
            }
        });
    }

    String.prototype.replaceAll = function (search, replacement) {
        return this.replace(new RegExp(search, 'g'), replacement);
    };
    var app = {
        dataToHtml: function (html, object) {
            $.each(object, function (index, value) {
                html = html.replaceAll('{{' + index + '}}', value);
            });
            return html.replaceAll(/\{{(.+?)\}}/g, '');
        },
        renderHtmldata: function (template, data) {
            var html = '';
            if (template) {
                $.each(data, function (index, value) {
                    html = html + app.dataToHtml(template, value);
                });
            }
            return html;
        },
    };

</script>

</body>
</html>
