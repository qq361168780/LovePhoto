<?php
/**
 * lovephoto Register Process
 * Processes the registration forms and returns errors/redirects to a page
 *
 *
 * @version 1.0
 * @author lovephoto
 * @copyright 2010 all rights reserved
 *
 */

function mfthemes_process_forgot_form( $success_redirect = '' ) {
	
	if ( get_option('users_can_register') ) :
		
		global $posted, $app_abbr;
		
		$posted = array();
		$errors = new WP_Error();
		$user_pass = wp_generate_password();
		
		if (isset($_POST['register']) && $_POST['register']) {

                        // include the WP registration core
			require_once( ABSPATH . WPINC . '/registration.php');

		
			// Get (and clean) data
			$fields = array(
				'your_username',
				'your_email',
				'your_password',
				'your_password2',
				'your_captcha',
				'role'
			);
			foreach ($fields as $field) {
				if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field])); else $posted[$field] = '';
			}
		
			$user_login = sanitize_user( $posted['your_username'] );
			$user_email = apply_filters( 'user_registration_email', $posted['your_email'] );
			$user_role = get_option('default_role');

			
			// Check the username
			if ( $posted['your_username'] == '' ) $errors->add('empty_username', "错误：请输入用户昵称！");

		
			// Check the e-mail address
			if ($posted['your_email'] == '' || !is_email( $posted['your_email'] ) ) {
				$errors->add('invalid_email', "错误：请输入一个正确的邮箱地址！");
				$posted['your_email'] = '';
			} elseif ( email_exists( $posted['your_email'] ) )
				$errors->add('email_exists', "错误：此邮箱已被注册，请更换一个！");
			

			// Check Passwords match
			if ($posted['your_password'] == '')	
				$errors->add('empty_password', "错误：请输入密码！");
			elseif ( strlen($posted['your_password']) < 8 )	
				$errors->add('empty_password', "错误：密码长度至少8位！");
			elseif ($posted['your_password2'] == '')
				$errors->add('empty_password', "错误：请重复输入密码！");
			elseif ($posted['your_password'] !== $posted['your_password2'])
				$errors->add('wrong_password', "错误：两次输入的密码不匹配！");
				
			$user_pass = $posted['your_password'];
			
            if (empty($_SESSION['captcha']) || trim(strtolower($posted['your_captcha'])) != $_SESSION['captcha']) {
				$errors->add('wrong_captcha', "错误：验证码错误！");
			}
			
			unset($_SESSION['captcha']);
	
			
			do_action('register_post', $posted['your_username'], $posted['your_email'], $errors);
			$errors = apply_filters( 'registration_errors', $errors, $posted['your_username'], $posted['your_email'] );
		
                        // if there are no errors, let's create the user account
			if ( !$errors->get_error_code() ) {
				$user_id = wp_create_user( strtolower(lp_rand_string()), $user_pass, $posted['your_email'] );
				if ( !$user_id ) {
					$errors->add('registerfail', "错误：注册失败，请联系管理员！");
					return array( 'errors' => $errors, 'posted' => $posted);
				}

				// Change role
				wp_update_user( array (
					'ID' => $user_id,
					'role' => $user_role,
					'display_name' => $posted['your_username']
				) ) ;
								
				// set the WP login cookie
				$secure_cookie = is_ssl() ? true : false;
				wp_set_auth_cookie($user_id, true, $secure_cookie);
									
				// mail to new user 
				lp_new_user($posted['your_username'], $posted['your_password'], $posted['your_email']);
									
				// redirect
				wp_redirect($success_redirect);
				exit;

			} else {

				// there were errors so go back and display them without creating new user
				return array( 'errors' => $errors, 'posted' => $posted);

			}
		}
		
	endif;

}