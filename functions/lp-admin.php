<?php
/**
 * Theme functions file =W= lp-admin.php 
 *
 *
 * @package LovePhoto
 * @author Mufeng
 */
 

	add_action('media_buttons_context', 'mfthemes_custom_button');
	function mfthemes_custom_button($context) {
		$xiami_src = TPLDIR . "/images/admin/xiami.png";
		$youku_src = TPLDIR . "/images/admin/youku.png";
		$context .= "<a id='mfthemes-xiami' class='button' href='#' title='添加虾米音乐'><img src='{$xiami_src}' />添加虾米音乐</a><a id='mfthemes-youku' class='button' href='#' title='添加优酷视频'><img src='{$youku_src}' />添加优酷视频</a>";
		return $context;
	}
	
	add_action('admin_init', 'lp_admin_init');
	function lp_admin_init(){
		global $pagenow;
		
		if( $pagenow == ("post-new.php" || "post.php")){
			wp_enqueue_style('admin-postcss', TPLDIR . "/script/admin/admin-post.css", false, '1.0.0', false);
			wp_enqueue_script('admin-postjs', TPLDIR . "/script/admin/admin-post.js", false, '1.0.0', false);
		}
	}