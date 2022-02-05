<style>
section.panel.panel-info {
    width: 75%;
}

.card-body {
    flex: 1 1 auto;
    padding: 1.25rem;
    margin: 1px 95px 6px 53px;
}
</style>
<style>
.cropit-preview {
    background-color: #f8f8f8;
    background-size: cover;
    border: 5px solid #ccc;
    border-radius: 3px;
    margin-top: 7px;
    width: 250px;
    height: 250px;
}

.cropit-preview-image-container {
    cursor: move;
}

.cropit-preview-background {
    opacity: .2;
    cursor: auto;
}

.image-size-label {
    margin-top: 10px;
}


.image-editor {
    width: 240px;
    text-align: center;
    margin: 20px;
}
</style>
<style type="text/css">
.profile-left .pro-quote {
    padding: 20px 15px 15px;

    color: #748f9e;
}

.pro-quote img {
    width: 100px;
    height: 100px;
    border-radius: 100%;
    border: 1px solid #eee;
}

.pro-quote .author-name {
    margin: 10px 0 5px;
    font-weight: 500;
    color: #212529;
    font-size: 20px;
}

.pro-quote h4 {
    display: block;
    margin: 0 0 10px;
    font-size: 18px;
    color: #212529;
}

.pro-info {
    padding: 15px;
    color: #212529;
    font-weight: 700;

}

.edit-profile_wrapper label {
    font-weight: 500 !important;
}

.pro-info table {
    width: 100%;
}

.pro-info table tr td {
    text-align: left;
    font-size: 14px;
    padding: 7px 0;
    font-weight: 500;
}

.pro-info table tr td:last-child {
    text-align: right;
    font-weight: 500;
}

.profile-left {
    border-radius: 6px;
    background-color: #fff;
}

.edit_profile_wrapper {
    background-color: #fff;
    border-radius: 6px;
    text-align: center;
    padding: 10px 0px;
}

.editprofile .title {
    text-align: center;
    font-size: 19px;
    border-bottom: 2px dashed #dddddd;
    /* padding: 25px; */
}

.editprofile .title span {
    padding: 25px;
    /*border-bottom: 2px dashed #cc470c;*/
    display: inline-block;
}

.avatar-upload {
    position: relative;
    max-width: 115px;
    margin: 20px auto 20px;
}

.avatar-upload .avatar-edit {
    position: absolute;
    right: 9px;
    z-index: 1;
    top: 3px;
}

.avatar-upload .avatar-edit input {
    display: none;
}

.avatar-upload .avatar-edit input+label {
    display: inline-block;
    width: 30px;
    height: 30px;
    margin-bottom: 0;
    border-radius: 100%;
    background: #ffffff;
    border: 1px solid transparent;
    box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
    cursor: pointer;
    font-weight: normal;
    transition: all 0.2s ease-in-out;
}

.avatar-upload .avatar-edit input+label:hover {
    background: #f1f1f1;
    border-color: #d6d6d6;
}

.avatar-upload .avatar-edit input+label:after {
    content: "\f040";
    font-family: "FontAwesome";
    color: #757575;
    position: absolute;
    top: 4px;
    left: 0;
    right: 0;
    text-align: center;
    margin: auto;
}

.avatar-upload .avatar-preview {
    width: 100px;
    height: 100px;
    position: relative;
    border-radius: 100%;
    border: 6px solid #f8f8f8;
    box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
}

.avatar-upload .avatar-preview>div {
    width: 100%;
    height: 100%;
    border-radius: 100%;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
}

.input_wrapper {
    width: 45%;
    margin: 0 auto;
}

.button_wrapper {
    text-align: center;
    width: 100%;
    margin-bottom: 25px;
}

button.btn.btn-success.btn-update {
    background-color: #ae4416 !important;
    border-color: #ae4416 !important;
}

@media (max-width: 575px) {
    .input_wrapper {
        width: 70%;
        margin: 0 auto;
    }
}
</style>
<div class="profile-left">
    <div class="pro-quote text-center">
        <img src="<?=  getFileUrl((isset($data['image'])?$data['image']:''));?>" alt="user">
        <h4 class="author-name"><?= $data['name'];?></h4>
    </div>
    <div class="pro-info">
        <table>
            <tbody>
                <tr>
                    <td>Name</td>
                    <td><?= $data['name'];?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?= $data['email'];?></td>
                </tr>
                <tr></tr>
                <tr>
                    <td colspan="2">
                        <a class="btn btn-default" style="width:100%" href="site/profile">Profile</a>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <a href="site/password_change" style="width:100%" class="btn btn-default">Change Password</a>
                    </td>

                </tr>
            </tbody>
        </table>
    </div>
</div>