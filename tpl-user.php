<?php
/*
Template Name: 用户页面
*/
nocache_headers();

if( !is_user_logged_in() ){
	wp_safe_redirect(home_url());
	exit();
}
global $userdata;

get_currentuserinfo(); // grabs the user info and puts into vars

$type = get_query_var("user_item");
$types_array = array("favourite", "posts", "following", "message", "setting");
if( isset($_POST["action"]) ){
	if($_POST["action"] == "lp_ajax_del_post" ){
		wp_delete_post(intval($_POST['postid']));
		$redirect_to = lp_page_link( "user" ) . "/posts?msg=deleted";
		wp_safe_redirect($redirect_to);
		exit();
		
	}
	
	if($_POST["action"] == "lp_ajax_del_love" ){
		global $visage;
		$visage->delete_like($user_ID, intval($_POST['postid']));
		$redirect_to = lp_page_link( "user" ) . "/favourite?msg=deleted";
		wp_safe_redirect($redirect_to);
		exit();
		
	}
	
	if($_POST["action"] == "lp_ajax_setting" ){
		$display_name = trim( strip_tags(stripslashes($_POST["display_name"])) );
		$description = trim( strip_tags(stripslashes($_POST["description"])) );
		
		$user_nicename = strtolower( trim( strip_tags(stripslashes($_POST["user_nicename"])) ) );

		$user_array = array();
		$blogusers = get_users("exclude=$user_ID");
		foreach ($blogusers as $user) {
array_push( $user_array, $user->nickname );
		}
		
		$pwd1 = isset($_POST["pwd1"]) ? $_POST["pwd1"] : "";
		$pwd2 = isset($_POST["pwd2"]) ? $_POST["pwd2"] : "";
		
		if( $display_name == "" ){
$redirect_to = lp_page_link( "user" ) . "/setting?err=1";
		}else if( !preg_match("/^[a-z_][0-9a-z_]{3,15}$/", $user_nicename) ){
$redirect_to = lp_page_link( "user" ) . "/setting?err=2";
		}else if( in_array($user_nicename, $user_array) ){
$redirect_to = lp_page_link( "user" ) . "/setting?err=3";
		}else if( !empty($pwd1) || !empty($pwd2) ){
if( strlen($pwd1) <8 ){
	$redirect_to = lp_page_link( "user" ) . "/setting?err=4";
}else if( $pwd1!= $pwd2 ){
	$redirect_to = lp_page_link( "user" ) . "/setting?err=5";
}else{
	wp_update_user(array (
	'ID' => $user_ID,
	'user_nicename' => $user_nicename,
	
	'display_name' => $display_name,
	'description' => $description,
	'user_pass' => $pwd1
	));
	$redirect_to = lp_page_link( "user" ) . "/setting?msg=updated";
}
		}else if( empty($pwd1) && empty($pwd2) ){
wp_update_user(array (
'ID' => $user_ID,
'user_nicename' => $user_nicename,

'display_name' => $display_name,
'description' => $description
));
$redirect_to = lp_page_link( "user" ) . "/setting?msg=updated";
		}
		wp_safe_redirect($redirect_to);
		exit();
		
	}
	
	if( $_POST["action"] == "lp_post_new" ){
		global $allowedposttags;
		get_currentuserinfo();
		ob_start();
		ob_end_clean();
		
		$post_title = $_POST['title'];
		$post_category = array($_POST['category'] );
		$post_tags = explode(",", $_POST['tag']);
		
		$post_content = isset( $_POST['lpAddValue'] ) ? ($_POST['lpAddValue'] . $_POST['editorValue'] ):  $_POST['editorValue'];
		
		//$pending = current_user_can('contributor') ? 'pending' : 'publish' ;

		$new_post = array(
  'ID' => '',
  'post_author' => $user_ID,  
  'post_category' => $post_category,
  'tags_input' => $post_tags,
  'post_content' => $post_content, 
  'post_title' => $post_title,
  'post_status' => 'pending'
);

		$post_id = wp_insert_post($new_post);
		$post = get_post($post_id);
		
		if( isset($_POST['format'])) set_post_format($post_id, $_POST['format'] );
		
		wp_redirect($post->guid);
		
	}
}else{
	get_header();
?>

<div class="section">
	<div class="container clearfix">
		<div class="main">
			<?php get_template_part( 'loop/user/user', $type ); ?>
		</div>
		<div class="aside">
			<div class="widget">
				<ul class="widget-ul">
					<li class="<?php echo in_array($type, $types_array) ? "" : "current";?>"><a href="<?php echo lp_page_link( "user" );?>"><span class="icon icon-pencil"></span>撰写新文章</a></li>
					<li><a href="<?php echo get_author_posts_url($user_ID); ?>"><span class="icon icon-home"></span>我的主页</a></li>
					<li class="<?php echo $type == "favourite" ? "current": "";?>"><a href="<?php echo lp_page_link( "user" );?>/favourite"><span class="icon icon-heart"></span><?php printf(__('我的收藏<span class="split">(%s)</span>'), lp_liking_count($user_ID)); ?></a></li>
					<li class="<?php echo $type == "posts" ? "current": "";?>"><a href="<?php echo lp_page_link( "user" );?>/posts"><span class="icon icon-bookmark"></span><?php printf(__('我的文章<span class="split">(%s)</span>'), count_user_posts($user_ID)); ?></a></li>
					<li class="<?php echo $type == "following" ? "current": "";?>"><a href="<?php echo lp_page_link( "user" );?>/following"><span class="icon icon-user"></span><?php printf(__('我的关注<span class="split">(%s)</span>'), lp_following_count($user_ID)); ?></a></li>
					<li class="<?php echo $type == "message" ? "current": "";?>"><a href="<?php echo lp_page_link( "user" );?>/message"><span class="icon icon-comment"></span>消息中心</a></li>
					<li class="<?php echo $type == "setting" ? "current": "";?>"><a href="<?php echo lp_page_link( "user" );?>/setting"><span class="icon icon-cog"></span>账号设置</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>
<?php }?>