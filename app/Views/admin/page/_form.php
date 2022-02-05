<form id="form" class="" method="post" action="<?= ADMIN_DIR;?>page/save" onsubmit="ajaxForm(event,this);" data-redirect="<?= ADMIN_DIR;?>page/index">
    <?= csrf_field() ?>
    <?php if(isset($data['id'])){?>
    <input type='hidden' name='id' value='<?=$data['id'];?>'>
    <?php }?>
    <div class="form-group">
        <label >Title</label>
        <input required value="<?=(isset($data['title']))?$data['title']:'';?>" type="text" class="form-control" name="title" placeholder="Title">
    </div>
    <div class="form-group">
        <label >Body</label>
        <textarea id="summernote" class="form-control" name="body" placeholder="Body"><?=(isset($data['body']))?$data['body']:'';?></textarea>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
<link rel="stylesheet" href="theme/plugins/summernote/summernote-bs4.min.css">
<script src="theme/plugins/summernote/summernote-bs4.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#summernote').summernote()
        $('#form').validate({
            submitHandler: function () {
               ajaxForm(event,$('#form'));
            }
        });
    });
</script>