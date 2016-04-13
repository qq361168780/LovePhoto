<?php
/**
 * Theme functions file
 *
 *
 * @package LovePhoto
 * @author Mufeng
 */

	define("TPLDIR", get_bloginfo('template_directory'));

	add_action('init', 'my_rewrite_add_rewrites');
	function my_rewrite_add_rewrites(){
		global $wp; 
		$wp->add_query_var('user_item');

		add_rewrite_rule(
			'^author/([^/]+)/([^/]+)/?$',
			'index.php?author_name=$matches[1]&user_item=$matches[2]',
			'top'
		);

		add_rewrite_rule(
			'^author/([^/]+)/([^/]+)/page/?([0-9]+)/?$',
			'index.php?author_name=$matches[1]&user_item=$matches[2]&paged=$matches[3]',
			'top'
		);
		
		add_rewrite_rule(
			'^user/([^/]+)/?$',
			'index.php?pagename=user&user_item=$matches[1]',
			'top'
		);
		add_rewrite_rule(
			'^user/([^/]+)/page/?([0-9]+)/?$',
			'index.php?pagename=user&user_item=$matches[1]&paged=$matches[2]',
			'top'
		);
	}
		
	function lp_page_link($slug){
		return get_permalink( get_page_by_path( $slug ) );
	}
	
	add_filter('show_admin_bar', '__return_false');

	add_filter('get_comment_author_link', 'lp_comment_link');
	function lp_comment_link(){

		/* Get the comment author information */
		global $comment;
		$author_id = $comment->user_id;
		$author = get_user_by('id', $author_id);
		$author_name = $author->display_name;
		$url    = get_author_posts_url( $author_id );

		return "<a href='{$url}' userid='{$author_id}' rel='external nofollow' class='lp-user-profile url'>{$author_name}</a>";
	}
	
	
	get_template_part('functions/lp-action');
	
	if(is_admin()):
		get_template_part('functions/lp-admin');
		add_action('init', 'lp_admin_redirect');
	else:
		get_template_part('functions/lp-meta');
		get_template_part('functions/lp-visage');
		
		get_template_part('functions/lp-login');
		get_template_part('functions/forms/login/login-form');
		get_template_part('functions/forms/login/login-process');
		get_template_part('functions/forms/register/register-form');
		get_template_part('functions/forms/register/register-process');
		get_template_part('functions/forms/forgot-password/forgot-form');
	endif;
			
	add_action('widgets_init', 'lp_unregister_default_wp_widgets', 1);	
	function lp_unregister_default_wp_widgets() {
		unregister_widget('WP_Widget_Pages');
		unregister_widget('WP_Widget_Calendar');
		unregister_widget('WP_Widget_Archives');
		unregister_widget('WP_Widget_Links');
		unregister_widget('WP_Widget_Meta');
		unregister_widget('WP_Widget_Search');
		unregister_widget('WP_Widget_Text');
		unregister_widget('WP_Widget_Categories');
		unregister_widget('WP_Widget_Recent_Posts');
		unregister_widget('WP_Widget_Recent_Comments');
		unregister_widget('WP_Widget_RSS');
		unregister_widget('WP_Widget_Tag_Cloud');
	}

	add_theme_support( 'post-formats', array( 'image', 'audio', 'video') );
	
	add_theme_support( 'post-thumbnails' );
	
	register_nav_menus(array('primary' => '顶部菜单'));
	register_nav_menus(array('footer' => '底部菜单'));

	
	add_action( 'template_redirect', 'lp_redirect');
	function lp_redirect() {
		global $pagenow;
		if( is_page_template("tpl-user.php") ){
			if(isset($_GET["code"])){
				wp_safe_redirect(lp_page_link( "user" ));
				exit();
			}
		}
	}
	
	add_filter('smilies_src','lp_smilies_src',1,10);
	function lp_smilies_src ($img_src, $img, $siteurl){
		return TPLDIR.'/images/smilies/'.$img;
	}
	
	// Enqueue style-file, if it exists.
	add_action('wp_enqueue_scripts', 'lp_script');
	function lp_script() {
		global $wp_styles;
		$timer = @filemtime(TEMPLATEPATH .'/style.css');
		
		wp_enqueue_style('style', get_bloginfo('stylesheet_url'), array(), $timer, 'screen');
		wp_enqueue_style( 'style-old-ie', TPLDIR . '/script/ie.css', array( 'style' )  );
		$wp_styles->add_data( 'style-old-ie', 'conditional', 'lt IE 8' );
	
		wp_enqueue_script( 'jquery1.8.2', TPLDIR . '/script/jquery.min.js', array(), '1.8.2', false);
		wp_enqueue_script( 'base', TPLDIR . '/script/base.js', array(), '1.0.2', false);
		
		wp_localize_script( 'base', 'lp', 
			array(
				"ajaxurl" => home_url("/"),
				"admin_ajax" => admin_url('admin-ajax.php'),
				"plupload_flash" => includes_url('js/plupload/plupload.flash.swf'),				
		));	
		
		
		if( is_page_template("tpl-user.php") ){
			$type = get_query_var("user_item");
			$types_array = array("favourite", "posts", "following", "message", "setting");
			if( in_array($type, $types_array) ){
				
			}else{
				wp_enqueue_style('new-post', TPLDIR . '/script/user/new-post.css', array(), '1.02', 'screen');
				wp_enqueue_style('umeditor.min.css', TPLDIR . '/script/user/ueditor/themes/default/css/umeditor.min.css', array(), '1.02', 'screen');
				
				if($type == "add-image") wp_enqueue_script('plupload-all');
				
				wp_enqueue_script( 'umeditor.config.js', TPLDIR . '/script/user/ueditor/umeditor.config.js', array(), '1.02', false);
				wp_enqueue_script( 'umeditor.min.js', TPLDIR . '/script/user/ueditor/umeditor.min.js', array(), '1.02', false);
				wp_enqueue_script( 'new-post', TPLDIR . '/script/user/new-post.js', array(), '1.02', false);
			}
		}else if ( is_singular() || is_page()){
			//wp_enqueue_script( 'jquery1.8.2', 'http://lib.sinaapp.com/js/jquery/1.8.2/jquery.min.js', array(), '1.8.2', false);
			
			wp_enqueue_script( 'xiami', TPLDIR . '/script/xiamiplayer.js', array(), '1.0.2', false);
			
			wp_enqueue_script( 'single', TPLDIR . '/script/single.js', array(), '1.0.2', false);
				
		}
	}

	function lp_popular_thumb($icon = false, $width=235, $height=160){
		$format = get_post_format();
		if( $format == "audio" ){
			$audio = lp_audio_detail();
			echo "<img src='{$audio['original']}' alt='{$audio['title']}' width='{$width}' height='{$height}' />";
			if( $icon ) echo '<span class="music-icon"></span>';
		}else if( $format == "video" ){
			$video = lp_video_detail();
			echo "<img src='{$video['original']}' alt='{$video['title']}' width='{$width}' height='{$height}' />";	
			if( $icon ) echo '<span class="video-icon"></span>';			
		}else{
			ob_start();
			ob_end_clean();
			global $post;
			$title = $post->post_title;
			$content = $post->post_content;	
			
			preg_match('/\<img.+?src="(.+?)".*?\/>/is', $content, $match);
			$count = count( $match );
			if( $count > 0 ){
				if( $format == "image" ){
					$src = strpos($match[1], "2.jpg")>0 ? str_replace("2.jpg", "1.jpg", $match[1]) : str_replace(".jpg", ".1.jpg", $match[1]);
				}else{
					$src = TPLDIR . "/timthumb.php&#63;src={$match[1]}&#38;w={$width}&#38;h={$height}&#38;zc=1&#38;q=100";
				}
			}else{
				$src = TPLDIR . "/images/default.png";
			}
			
			echo "<img src='$src' alt='$title' width='{$width}' height='{$height}' />";			
		}
	}

	function lp_aside_thumb($width=87, $height=60, $post = null){
		$format = get_post_format();
		if( $format == "audio" ){
			$audio = lp_audio_detail();
			echo "<img src='{$audio['original']}' alt='{$audio['title']}' width='{$width}' height='{$height}' />";
		}else if( $format == "video" ){
			$video = lp_video_detail();
			echo "<img src='{$video['cover']}' alt='{$video['title']}' width='{$width}' height='{$height}' />";			
		}else{
			ob_start();
			ob_end_clean();
			global $post;
			$title = $post->post_title;
			$content = $post->post_content;	
			
			preg_match('/\<img.+?src="(.+?)".*?\/>/is', $content, $match);
			$count = count( $match );
			if( $count > 0 ){
				if( $format == "image" ){
					$src = strpos($match[1], "2.jpg")>0 ? str_replace("2.jpg", "0.jpg", $match[1]) : str_replace(".jpg", ".0.jpg", $match[1]);
				}else{		
					$src = TPLDIR . "/timthumb.php&#63;src={$match[1]}&#38;w={$width}&#38;h={$height}&#38;zc=1&#38;q=100";	
				}
			}else{
				$src = TPLDIR . "/images/default.png";
			}
			
			echo "<img src='$src' alt='$title' width='{$width}' height='{$height}' />";			
		}
	}
	
	function lp_audio_detail(){
		global $post;
		$content = $post->post_content;
		$title   = $post->post_title;
		$output  = preg_match('/\[xiami.*?author="(.+?)".*?cover="(.+?)".*?original="(.+?)".*?\](.*?)\[\/xiami\]/is', $content, $match);

		return array(
			"title" => $title,
			"author" => $match[1],
			"cover" => $match[2],
			"original" => $match[3],
			"songid" => $match[4]
		);
	}

	function lp_video_detail(){
		global $post;
		$content = $post->post_content;
		$title   = $post->post_title;
		$output  = preg_match('/\[youku.*?ltime="(.+?)".*?cover="(.+?)".*?original="(.+?)".*?\](.*?)\[\/youku\]/is', $content, $match);
		return array(
			"title" => $title,
			"ltime" => $match[1],
			"cover" => $match[2],
			"original" => $match[3],
			"youkuid" => $match[4] 
		);
	}
	
	function lp_archive_image(){
		global $post;
		$title = $post->post_title;
		$content = $post->post_content;	
		ob_start();
		ob_end_clean();
		
		preg_match_all('/\<img.+?src="(.+?)".*?\/>/i', $content, $match, PREG_SET_ORDER);
		$count = count( $match );
		if( $count > 0 ){
			if($count==1){
				echo "<img class='archive-image' src='{$match[0][1]}' alt='{$title}' />";
			}else{
				$count = $count<=5 ? $count : 5;
				for($i=0; $i<$count; $i++){
					$src = strpos($match[$i][1], "2.jpg")>0 ? str_replace("2.jpg", "1.jpg", $match[$i][1]) : str_replace(".jpg", ".1.jpg", $match[$i][1]);
					echo "<img class='archive-image' src='{$src}' alt='{$title}' width='200' height='136' />";
				}
			}
		}		
	}
	
	function lp_archive_text($width=235, $height=160){
		global $post;
		$content = $post->post_content;
		preg_match_all('/\<img.+?src="(.+?)".*?\/>/is', $content, $matches ,PREG_SET_ORDER);
		$cnt = count( $matches );
		if($cnt>0){
			$src = $matches[0][1];
			$src = TPLDIR . "/timthumb.php&#63;src=$src&#38;w=$width&#38;h=$height&#38;zc=$crop&#38;q=100";	
			echo "<div class='archive-thumbnail'><img src='$src' alt='$title' width='$width' height='$height' /></div>";			
		}
	}
	
	function lp_time_since($older_date, $comment_date = false) {
		$chunks = array(
			array(86400 , '天前'),
			array(3600 , '小时前'),
			array(60 , '分钟前'),
			array(1 , '秒前'),
		);
		$newer_date = time() + (60*60*get_settings("gmt_offset"));
		$since = abs($newer_date - $older_date);
		if($since < 2592000){
			for ($i = 0, $j = count($chunks); $i < $j; $i++){
				$seconds = $chunks[$i][0];
				$name = $chunks[$i][1];
				if (($count = floor($since / $seconds)) != 0) break;
			}
			$output = $count.$name;
		}else{
			$output = $comment_date ? (date('Y-m-j G:i', $older_date)) : (date('Y-m-j', $older_date));
		}
		return $output;
	}
	
	add_filter('wp_nav_menu_objects', 'lp_menu');
	function lp_menu($items) {
		foreach ($items as $item) {
			if (lp_hasub($item->ID, $items)) {
				$item->classes[] = 'parent-menu';
			}
		}
		return $items;    
	};

	function lp_hasub($menu_item_id, $items) {
		foreach ($items as $item) {
			if ($item->menu_item_parent && $item->menu_item_parent==$menu_item_id) {
				return true;
			}
		}
		return false;
	}

	function lp_pagenavi($max_page = null, $range = 4){
		global $paged, $wp_query;
		$max_page = $max_page ? $max_page : ($wp_query->max_num_pages);
		if($max_page > 1){if(!$paged){$paged = 1;}
		if($paged != 1){echo "<a href='" . get_pagenum_link(1) . "' class='home'>首页</a>";}
		if($paged>1) echo '<a href="' . get_pagenum_link($paged-1) .'" class="pageprev">上一页</a>';
		if($max_page > $range){
			if($paged < $range){for($i = 1; $i <= ($range + 1); $i++){echo "<a href='" . get_pagenum_link($i) ."'";
			if($i==$paged)echo " class='current'";echo ">$i</a>";}}
		elseif($paged >= ($max_page - ceil(($range/2)))){
			for($i = $max_page - $range; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";
			if($i==$paged)echo " class='current'";echo ">$i</a>";}}
		elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){
			for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){echo "<a href='" . get_pagenum_link($i) ."'";if($i==$paged) echo " class='current'";echo ">$i</a>";}}}
		else{for($i = 1; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";
		if($i==$paged)echo " class='current'";echo ">$i</a>";}}
		if($paged<$max_page) echo '<a href="' . get_pagenum_link($paged+1) .'" class="pagenext">下一页</a>';
		if($paged != $max_page){echo "<a href='" . get_pagenum_link($max_page) . "' class='last'>尾页</a>";}}
	}
	
	function lp_strip_tags($content){
		if($content){
			$content = preg_replace("/\[.*?\].*?\[\/.*?\]/is", "", strip_tags($content));
		}
		return $content;
	}
	
	
	// MF_coment part
	function lp_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		global $commentcount;
		if(!$commentcount) {
		   $page = ( !empty($in_comment_loop) ) ? get_query_var('cpage')-1 : get_page_of_comment( $comment->comment_ID, $args )-1;
		   $cpp = get_option('comments_per_page');
		   $commentcount = $cpp * $page;
		}
		?>
		   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
				<div id="comment-<?php comment_ID(); ?>" class="comment-body">
					<div class="comment-avatar">
						<a href="<?php echo get_author_posts_url( $comment->user_id );?>" userid="<?php echo $comment->user_id;?>" rel="external nofollow" class="lp-user-profile url">
						<?php echo get_avatar( $comment, $size = '50'); ?>
						</a>
					</div>				
					<div class="comment-data">
						<span class="comment-span"><?php printf(__('%s'), get_comment_author_link()) ?></span>
					</div>
					<div class="comment-text"><?php comment_text() ?></div>
					<div class="comment-reply clearfix">
						<div class="right">
						<?php
							++$commentcount;
							printf(__('<span class="comment-span">%s楼</span>'), $commentcount);
							printf(__('<span class="comment-span">%1$s %2$s</span>'), get_comment_date("Y-m-d"),  get_comment_time("H:i"));
							comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => __('回复'))));
						?>
						</div>
					</div>
				</div>
		<?php
	}
	
	
	// Add shortcode
	add_shortcode('xiami','lp_audio_shortcode');	
	function lp_audio_shortcode($atts, $content=null){
		extract(shortcode_atts(array("auto"=>0, "cover"=> ''),$atts));	
		return '<div class="audio-xiami"><div class="xiami-player" songid="'.$content.'"></div></div>';
	}

	function lp_related_posts($post_num = 4) {
		global $post;
		echo '<div class="single-related"><ul class="clearfix">';
		$exclude_id = $post->ID;
		$posttags = get_the_tags(); $i = 0;
		if ( $posttags ) {
			$tags = ''; foreach ( $posttags as $tag ) $tags .= $tag->term_id . ',';
			$args = array(
				'post_status' => 'publish',
				'tag__in' => explode(',', $tags),
				'post__not_in' => explode(',', $exclude_id),
				'ignore_sticky_posts' => 1,
				'orderby' => 'rand',
				'showposts' => $post_num
			);
			$query_ps = query_posts($args);
			while( have_posts() ) { the_post(); ?>			
				<li class="related-post<?php if($i%3==0 && $i>0) echo" related-part";?>">
					<a href="<?php the_permalink(); ?> " class="related-post-image" rel="nofollow"><?php lp_popular_thumb( false, 155, 102 ); ?></a>
					<a class="related-post-tittle" href="<?php the_permalink(); ?>" ><?php the_title(); ?></a>
				</li>
			<?php
				$exclude_id .= ',' . $post->ID; $i ++;
			} $query_ps = null; $args = null; wp_reset_query();
		}
		if ( $i < $post_num ) {
			$cats = ''; foreach ( get_the_category() as $cat ) $cats .= $cat->cat_ID . ',';
			$args = array(
				'category__in' => explode(',', $cats),
				'post__not_in' => explode(',', $exclude_id),
				'ignore_sticky_posts' => 1,
				'orderby' => 'rand',
				'showposts' => $post_num - $i
			);
			$query_ps = query_posts($args);
			while( have_posts() ) { the_post(); ?>
				<li class="related-post<?php if($i%3==0 && $i>0) echo" related-part";?>">
					<a href="<?php the_permalink(); ?> " class="related-post-image" rel="nofollow"><?php lp_popular_thumb( false, 155, 102 ); ?></a>
					<a class="related-post-tittle" href="<?php the_permalink(); ?>" ><?php the_title(); ?></a>
				</li>
		 
			<?php $i++;
			} $query_ps = null; $args = null; wp_reset_query();
		}
		if ( $i  == 0 )  echo '<li>没有相关文章!</li>';
		echo '</ul></div>';
	}	

	function lp_current_page_url(){
		$current_page_url = 'http';
		if($_SERVER["HTTPS"] == "on"){
			$current_page_url .= "s";
		}
		$current_page_url .= "://";
		if($_SERVER["SERVER_PORT"] != "80"){
			$current_page_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		}else{
			$current_page_url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $current_page_url;
	}
	
	function lp_login_weibo(){
		$callback_url= home_url();
		$home_url = get_bloginfo('home');
		$sina_url = $home_url .'?socialmedia=sinaweibo&callback_url='.$callback_url;
		$qq_url = $home_url .'?socialmedia=qqsns&callback_url='.$callback_url;
		echo '<a class="go-weibo" href="'.$sina_url.'">新浪微博</a><a class="go-qq" href="'.$qq_url.'">QQ帐号</a>';
	}
	
	/*
	* Redirect to home url if not Administrators
	*/
	function lp_admin_redirect() {
		if( is_admin() ){
			if( !current_user_can( 'manage_options' ) ) {
				global $pagenow;
				$link = lp_page_link( "user" );
				
				if( $pagenow == "index.php" || $pagenow == "profile.php" || $pagenow == "edit.php" || $pagenow == "post-new.php" || $pagenow == "edit-comments.php" || $pagenow == "tools.php"){
					wp_redirect($link);
					exit;
				}
			}
		}
	}
	
	function lp_new_user($user_name, $user_pass, $user_email){
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
		$wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
		$subj = "您的用户名和密码 - $blogname";
		$body = "您使用{$user_name}账号在 $blogname 的注册信息：\r\n";
		$body.= "用户名：$user_email\r\n";
		$body.= "密码：$user_pass\r\n";
		$body.= "登陆地址：".site_url('/wp-login.php')."\r\n";
		$body.= "\r\n";
		$body.= "-----------------------------------\r\n";
		$body.= "这是一封自动发送的邮件。 \r\n";
		$body.= "来自 {$blogname}。\r\n";
		$body.= "请不要回复本邮件。\r\n";
		$body.= "Powered by © $blogname。\r\n";
		$from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
		$headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";	
		wp_mail($user_email, $subj, $body, $headers);
	}	
	
?>	