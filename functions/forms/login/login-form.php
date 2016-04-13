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
function mfthemes_login_form() {?>
	<form action="<?php echo site_url('wp-login.php'); ?>" method="post" class="account-form">
		<div class="form-item clearfix">
			<label class="form-label" for="login-username">邮箱地址</label>
			<input type="text" class="text" name="log" placeholder="邮箱地址" id="login-username" tabindex="1" value="<?php if (isset($_POST['log'])) echo $_POST['log']; ?>" />
			<div class="tips"></div>
		</div>	
		<div class="form-item clearfix">
			<label class="form-label" for="login-password">账号密码</label>
			<input type="password" class="text" name="pwd" id="login-password" tabindex="2" value="<?php if (isset($_POST['psw'])) echo $_POST['psw']; ?>" />
			<div class="tips"></div>
		</div>
		<div class="form-item form-blank">
			<input name="rememberme" type="checkbox" id="login-remember" value="forever" tabindex="3" checked="checked" />
			<label for="login-remember" class="remember">记住我的登录状态</label>
		</div>
		<div class="form-item form-blank">
			<input type="submit" id="form-submit" name="login" value="登陆" />
			<a href="<?php echo site_url('wp-login.php?action=lostpassword', 'login') ?>">忘记密码?</a>
		</div>
	</form>

<?php
}
?>