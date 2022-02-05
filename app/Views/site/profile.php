
<div class="app-page-title app-page-title-simple">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div>
                <div class="page-title-head center-elem">
                    <span class="d-inline-block pr-2">
                        <i class="lnr-apartment opacity-6"></i>
                    </span>

                </div>
                <div class="page-title-subheading opacity-10">
                    <nav class="" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a>
                                    <i aria-hidden="true" class="fa fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a>Profile</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-xl-3">
        <?= view('site/profile_block',['data'=>$data]);?>
    </div>
    <div class="col-lg-9 col-xl-9">
        <div class="edit-profile_wrapper">

            <div class="main-card mb-3 card">
                <div class="card-header">Profile</div>
                <div class="card-body">
                    <form method="post" action="site/profile">
                        <?= csrf_field() ?>
                        <div class="position-relative row form-group">
                            <label for="" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input name="name" id="name" type="text" class="form-control"
                                    value="<?= $data['name'];?>">
                            </div>
                        </div>
                        <div class="position-relative row form-group">
                            <label for="" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input name="email" id="email" type="email" class="form-control"
                                    value="<?= $data['email'];?>">
                            </div>
                        </div>

                        <div class="position-relative row form-check">
                            <div class="col-sm-10 offset-sm-2">
                                <button class="btn btn-primary" name="button">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
</div>