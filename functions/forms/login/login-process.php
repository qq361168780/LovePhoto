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

if (!function_exists('user_can')) :
	function user_can( $user, $capability ) {
		if ( ! is_object( $user ) )
			$user = new WP_User( (int) $user );
		
		if ( ! $user || ! $user->ID )
			return false;
	
		$args = array_slice( func_get_args(), 2 );
		$args = array_merge( array( $capability ), $args );
	
		return call_user_func_array( array( &$user, 'has_cap' ), $args );
	}
endif;

function mfthemes_process_login_form() {

	global $posted;
	
	$errors = new WP_Error();
	$redirect =  home_url();
	ob_start();
	ob_end_clean();
			
	// If cookies are disabled we can't log in even with a valid user+pass
	if ( is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ) )
		$secure_cookie = false;
	else
		$secure_cookie = '';
	
	$user = wp_signon('', $secure_cookie);

	if ( !is_wp_error($user) ) {
		wp_redirect($redirect);
		exit();
	}

	$errors = $user;

	return $errors;

}