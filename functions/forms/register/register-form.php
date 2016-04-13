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

function mfthemes_register_form() {
    if ( get_option('users_can_register') ) :
    ?>
	<form action="<?php echo site_url('wp-login.php?action=register'); ?>" method="post" class="account-form">
		<div class="form-item clearfix">
			<label class="form-label" for="login-username">邮箱地址</label>
			<input type="text" class="text" name="your_email" id="your_email" placeholder="请输入您的常用邮箱" tabindex="1" value="<?php if (isset($_POST['your_email'])) echo $_POST['your_email']; ?>" />
			<div class="tips"></div>
		</div>	
		<div class="form-item clearfix">
			<label class="form-label" for="your_username">用户昵称</label>
			<input type="text" class="text" name="your_username" id="your_username" tabindex="2" value="<?php if (isset($_POST['your_username'])) echo $_POST['your_username']; ?>" />
			<div class="tips"></div>
		</div>
		<div class="form-item clearfix">
			<label class="form-label" for="your_password">输入密码</label>
			<input type="password" class="text" name="your_password" id="your_password" tabindex="3" value="<?php if (isset($_POST['your_password'])) echo $_POST['your_password']; ?>" />
			<div class="tips"></div>
		</div>
		<div class="form-item clearfix">
			<label class="form-label" for="your_password2">确认密码</label>
			<input type="password" class="text" name="your_password2" id="your_password2" tabindex="3" value="<?php if (isset($_POST['your_password2'])) echo $_POST['your_password2']; ?>" />
			<div class="tips"></div>
		</div>		
		<div class="form-item clearfix">
			<div class="clearfix">
				<label class="form-label form-captcha" for="your_captcha">验证码</label>
				<img id="captcha_img" src="<?php echo TPLDIR;?>/functions/forms/register/captcha/captcha.php" title="看不清？换一张！" alt="看不清？换一张！" onclick="document.getElementById('captcha_img').src='<?php echo TPLDIR;?>/functions/forms/register/captcha/captcha.php?'+Math.random();document.getElementById('your_captcha').focus();return false;" />
				<a href="javascript:void(0)" onclick="document.getElementById('captcha_img').src='<?php echo TPLDIR;?>/functions/forms/register/captcha/captcha.php?'+Math.random();document.getElementById('your_captcha').focus();return false;"><span>看不清？换一张！</span></a>
			</div>
			<br />
			<div class="form-blank clearfix">
				<input type="text" class="text" name="your_captcha" id="your_captcha" tabindex="4" value="<?php if (isset($_POST['your_captcha'])) echo $_POST['your_captcha'];?>" />
			</div>
			<div class="tips"></div>
		</div>		
		<div class="form-item form-blank">
			<input type="submit" id="register-submit" name="register" value="立即注册" />
		</div>
    </form>
<?php endif; ?>

<?php } ?>