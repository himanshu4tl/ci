<form id="form" class="" method="post" action="<?= ADMIN_DIR;?>user/save" onsubmit="event.preventDefault();" data-redirect="<?= ADMIN_DIR;?>user/index">
    <?= csrf_field() ?>
    <?php if(isset($data['id'])){?>
    <input type='hidden' name='id' value='<?=$data['id'];?>'>
    <?php }?>
    <div class="form-group">
        <label >Name</label>
        <input required value="<?=(isset($data['name']))?$data['name']:'';?>" type="text" class="form-control" name="name" placeholder="Name">
    </div>
    <div class="form-group">
        <label >Email</label>
        <input value="<?=(isset($data['email']))?$data['email']:'';?>" type="email" class="form-control" name="email" placeholder="Email">
    </div>
    <div class="form-group">
        <label >Phone</label>
        <input  value="<?=(isset($data['phone']))?$data['phone']:'';?>" type="phone" class="form-control" name="phone" placeholder="Phone">
    </div>
    <div class="form-group">
        <label >Password</label>
        <input <?= isset($data['id'])?'':'required'?> value="" type="password" class="form-control" id="password"
            name="password" placeholder="Password">
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $('#form').validate({
            submitHandler: function () {
               ajaxForm(event,$('#form'));
            }
        });
    });
</script>