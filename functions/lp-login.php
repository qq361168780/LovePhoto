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
global $pagenow;

// what you want login or not
$theaction = isset($_GET['action']) ? $_GET['action'] : '';

// if the user is on the login page, then let the games begin
if ($pagenow == 'wp-login.php' && $theaction != 'logout' && !isset($_GET['key'])) add_action('init', 'mfthemes_login_init', 98);

// main function that routes the request
function mfthemes_login_init() {

	nocache_headers();

	if( is_user_logged_in() ){
		$redirect = home_url();
		wp_redirect($redirect);	
		die();
	}
	
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'login';

	if( $action == "register" || $action == "login"){session_start();}
    switch($action) :
        case 'lostpassword' :
        case 'retrievepassword' :
            mfthemes_password();
        break;
        case 'register':
			mfthemes_register();
		break;
        case 'login':
        default:
            mfthemes_login();
        break;
    endswitch;
    exit;
}

// Show login forms
function mfthemes_login() {
	global $posted;
	
	$errors = mfthemes_process_login_form();

	// Clear errors if loggedout is set.
	if ( !empty($_GET['loggedout']) ) $errors = new WP_Error();
            
	if ( isset($_POST['testcookie']) && empty($_COOKIE[TEST_COOKIE]) )
			$errors->add('test_cookie', "Cookies 被禁止或者浏览器不支持，你必须开启Cookies.");
	
	if ( isset($_GET['loggedout']) && TRUE == $_GET['loggedout'] )
			$message = "你已经登出网站!";

	elseif	( isset($_GET['registration']) && 'disabled' == $_GET['registration'] )	
			$errors->add('registerdisabled', __('User registration is currently not allowed.','lovephoto'));

	elseif	( isset($_GET['checkemail']) && 'confirm' == $_GET['checkemail'] )	
			$message = __('Check your email for the confirmation link.','lovephoto');

	elseif	( isset($_GET['checkemail']) && 'newpass' == $_GET['checkemail'] )	
			$message = __('Check your email for your new password.','lovephoto');

	elseif	( isset($_GET['checkemail']) && 'registered' == $_GET['checkemail'] )
			$message = __('Registration complete. Please check your e-mail.','lovephoto');

	get_template_part('header');
	
	//var_dump($_SERVER['HTTP_REFERER']);
	
	?>
	<div id="content">
		<div id="signin" class="section">
			<div class="container clearfix">
				<div class="main">
					<h1 class="section-title">用户登录</h1>
					<?php 
						if (isset($message) && !empty($message)) echo '<div class="form-msg">'.$message.'</div>';
						if (isset($errors) && sizeof($errors)>0 && $errors->get_error_code()) :
							echo '<div class="form-msg">';
							foreach ($errors->errors as $error) {
								echo '<p>'.$error[0].'</p>';
							}
							echo '</div>';
						endif;
						mfthemes_login_form(); 
					?>
					<div class="login-sns form-blank">
						<div>可以使用以下方式登录</div>
						<div>
							<?php lp_login_weibo();?>
						</div>						
					</div>
				</div>
				<div class="aside">
					<div class="login-reg">
						<h1 class="section-title">注册</h1>
						<div class="form-item">还没有账号?</div>
						<div class="form-item"><a href="<?php bloginfo('url'); ?>/wp-login.php?action=register" class="register-link">轻松注册</a></div>
					</div>
				</div>
			</div>
		</div>
	</div><!-- #content -->
	<?php get_template_part('footer');
}

// Show register forms
function mfthemes_register() {

	global $posted;
	
	$result = mfthemes_process_register_form();
		
	$errors = $result['errors'];
	$posted = $result['posted'];
	
	// Clear errors if loggedout is set.
	if ( !empty($_GET['loggedout']) ) $errors = new WP_Error();

	// If cookies are disabled we can't log in even with a valid user+pass
	if ( isset($_POST['testcookie']) && empty($_COOKIE[TEST_COOKIE]) )
			$errors->add('test_cookie', 'Cookies 被禁止或者浏览器不支持，你必须开启Cookies.');
	
	if ( isset($_GET['loggedout']) && TRUE == $_GET['loggedout'] )
			$message = "你已经登出!";

	elseif	( isset($_GET['registration']) && 'disabled' == $_GET['registration'] )	
			$errors->add('registerdisabled', __('User registration is currently not allowed.','lovephoto'));

	elseif	( isset($_GET['checkemail']) && 'confirm' == $_GET['checkemail'] )	
			$message = __('Check your email for the confirmation link.','lovephoto');

	elseif	( isset($_GET['checkemail']) && 'newpass' == $_GET['checkemail'] )	
			$message = __('Check your email for your new password.','lovephoto');

	elseif	( isset($_GET['checkemail']) && 'registered' == $_GET['checkemail'] )
			$message = __('Registration complete. Please check your e-mail.','lovephoto');

	get_template_part('header');
	?>
	<div id="content">
		<div id="signin" class="section">
			<div class="container clearfix">
				<div class="main">
					<h1 class="section-title">用户注册</h1>
					<?php 
						if (isset($message) && !empty($message)) echo '<div class="form-msg"><p class="success">'.$message.'</p></div>';
						if (isset($errors) && sizeof($errors)>0 && $errors->get_error_code()) :
							echo '<div class="form-msg">';
							foreach ($errors->errors as $error) {
								echo '<p class="error">'.$error[0].'</p>';
							}
							echo '</div>';
						endif;
						mfthemes_register_form( '', '' );
					?>
				</div>
				<div class="aside">
					<div class="login-reg">
						<h1 class="section-title">登陆</h1>
						<div class="form-item">已经有了账号？</div>
						<div class="form-item"><a class="login-link" href="<?php bloginfo('url'); ?>/wp-login.php">直接登录</a></div>
						<div class="form-item">
							<div>也可以使用以下方式登录</div>
							<div>
								<?php lp_login_weibo();?>						
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div><!-- #content -->
	<?php get_template_part('footer');
}

// show the forgot your password page
function mfthemes_password() {
    $errors = new WP_Error();

    if ( isset($_POST['user_login']) && $_POST['user_login'] ) {
        $errors = retrieve_password();

        if ( !is_wp_error($errors) ) {
            wp_redirect('wp-login.php?checkemail=confirm');
            exit();
        }

    }

    if ( isset($_GET['error']) && 'invalidkey' == $_GET['error'] ) $errors->add('invalidkey', __('Sorry, that key does not appear to be valid.','appthemes'));

    do_action('lost_password');
    do_action('lostpassword_post');

    get_template_part('header');
	?>
	<div id="content">
		<div id="signin" class="section">
			<div class="container clearfix">
				<div class="main">
					<h1>找回密码</h1>
					<?php 
						if (isset($message) && !empty($message)) echo '<div class="form-msg"><p class="success">'.$message.'</p></div>';
						if (isset($errors) && sizeof($errors)>0 && $errors->get_error_code()) :
							echo '<div class="form-msg">';
							foreach ($errors->errors as $error) {
								echo '<p class="error">'.str_replace("用户名或", "", $error[0]).'</p>';
							}
							echo '</div>';
						endif;
						mfthemes_forgot_form(); 
					?>
				</div>
			</div>
		</div>
	</div>
	<?php get_template_part('footer');
}

?>