<form id="form" class="" method="post" action="note/save" onsubmit="ajaxForm(event,this);" data-redirect="note/index">
    <?= csrf_field() ?>
    <?php if(isset($data['id'])){?>
    <input type='hidden' name='id' value='<?=$data['id'];?>'>
    <?php }?>
    <div class="form-group">
        <label >Title</label>
        <input required value="<?=(isset($data['title']))?$data['title']:'';?>" type="text" class="form-control" name="title" placeholder="Title">
    </div>
    <div class="form-group">
        <label >Date</label>
        <input required value="<?=(isset($data['date']))?$data['date']:date('Y-m-d');?>" type="date" class="form-control" name="date" placeholder="Date">
    </div>
    <div class="form-group">
        <label >Note</label>
        <textarea id="summernote" class="form-control" name="note" placeholder="Note"><?=(isset($data['note']))?$data['note']:'';?></textarea>
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