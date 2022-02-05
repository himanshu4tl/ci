<p>Hello <?= $adminData['name'];?><br>
<a target="_blank" href="<?= base_url(ADMIN_DIR.'site/password_reset/'.$adminData['password_reset_token'])?>">Click here</a> for reset password