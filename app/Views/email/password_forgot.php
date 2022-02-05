<p>Hello <?= $userData['name'];?><br>
<a target="_blank" href="<?= base_url('site/password_reset/'.$userData['password_reset_token'])?>">Click here</a> for reset password