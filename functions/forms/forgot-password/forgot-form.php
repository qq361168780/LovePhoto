<?php
/**
 * LovePhoto Login Part
 * Hooks into various actions in the theme.
 *
 *
 * @version 1.0
 * @author Mufeng
 * @package LovePhoto
 * @copyright 2012 all rights reserved
 *
 */
function mfthemes_forgot_form() {
	$redirect =  lp_page_link( "user");
	?>
	<form action="<?php echo site_url('wp-login.php'); ?>?action=lostpassword" method="post" id="forgot-password" class="account-form">
		<p>请输入您的电子邮箱地址。您会收到一封包含创建新密码链接的电子邮件。</p>
		<div class="form-item clearfix">
			<label class="form-label" for="login-username">邮箱地址</label>
			<input type="text" class="text" name="user_login" placeholder="邮箱地址" id="login-username" tabindex="1" value="<?php if (isset($_POST['log'])) echo $_POST['log']; ?>" />
			<span class="form-tips"></span>
		</div>
		<div class="form-item form-blank">
			<input type="submit" id="register-submit" value="确认重置密码" />
			<input type="hidden" name="redirect_to" value="<?php echo $redirect; ?>" />
		</div>
	</form>
	<?php
}