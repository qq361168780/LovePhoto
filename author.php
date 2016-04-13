<?php get_header();
global $array;
$array["author"] = "unabled";
$author_name = get_query_var('author_name');
$type = get_query_var("user_item");

$types_array = array("post", "favourite");
$author = $author_name ? get_user_by('slug', $author_name) : get_userdata(intval($author));

$author_id = $author->ID;

get_currentuserinfo(); // grabs the user info and puts into vars
?>

<div class="gather-body section">
	<div class="container clearfix">
		<div class="main">
			<div class="atr-profile">
				<div class="atr-profile-body clearfix">
					<div class="atr-pic">
						<?php echo get_avatar( $author_id , 48 ); ?>
					</div>
					<div class="atr-info">
						<h2><?php echo $author->display_name; ?></h2>
						<p class="atr-dsc">
							<?php if($author->user_description){
								printf(__('%s'), $author->user_description);
							}else{
								if( $user_ID >0 && $author_id == $user_ID ){?>
									<a href="<?php echo lp_page_link( "user" );?>/setting">添加自我介绍</a>
								<?php }							
							}
						?></p>
						<?php 
							$weibo = get_user_meta($author_id, "smcdata", true);
							if($weibo &&$weibo["socialmedia"]!=null){
								$socialmedia = $weibo["socialmedia"];
								if($socialmedia["sinaweibo"]!=null){
									echo '<a class="atr-weibo sinaweibo" title="新浪微博" href="http://weibo.com/'.$author->user_login.'"></a>';
								}else if($socialmedia["qqsns"]!=null){
									echo '<a class="atr-weibo qqweibo" title="腾讯微博" href="http://t.qq.com/'.$author->user_login.'"></a>';
								}
							}
							
						?>
					</div>
				</div>
				<div class="atr-profile-footer clearfix">
					<div class="left">
						<ul>
							<li class="<?php echo in_array($type, $types_array) ? "" : "current";?>">
								<a href="<?php echo get_author_posts_url($author_id); ?>">主页</a>
							</li>
							<li class="<?php echo $type == "post" ? "current": "";?>">
								<a href="<?php echo get_author_posts_url($author_id); ?>/post"><?php printf(__('文章&#40;%s&#41;'), count_user_posts($author_id)); ?></a>
							</li>
							<li class="<?php echo $type == "favourite" ? "current": "";?>">
								<a href="<?php echo get_author_posts_url($author_id); ?>/favourite"><?php printf(__('收藏&#40;%s&#41;'), lp_liking_count($author_id)); ?></a>
							</li>
							<li>
							<?php printf(__('关注&#40;%s&#41;'), lp_following_count($author_id)); ?>
							</li>
							<li>
							<?php printf(__('粉丝&#40;%s&#41;'), lp_followed_count($author_id)); ?>
							</li>							
						</ul>
					</div>
					<div class="right">
						<?php lp_follow_button($author_id);?>
					</div>
				</div>	
			</div>
			<?php get_template_part( 'loop/author/author', $type ); ?>
		</div>
			<div class="aside">
				<?php if( $author_id == $user_ID ):?>
					<div class="widget">
						<ul class="widget-ul">
							<li><a href="<?php echo lp_page_link( "user" );?>"><span class="icon icon-pencil"></span>撰写新文章</a></li>
							<li><a href="<?php echo lp_page_link( "user" );?>/favourite"><span class="icon icon-heart"></span><?php printf(__('我的收藏<span class="split">(%s)</span>'), lp_liking_count($user_ID)); ?></a></li>
							<li><a href="<?php echo lp_page_link( "user" );?>/posts"><span class="icon icon-bookmark"></span><?php printf(__('我的文章<span class="split">(%s)</span>'), count_user_posts($user_ID)); ?></a></li>
							<li><a href="<?php echo lp_page_link( "user" );?>/message"><span class="icon icon-comment"></span>消息中心</a></li>
							<li><a href="<?php echo lp_page_link( "user" );?>/setting"><span class="icon icon-cog"></span>账号设置</a></li>
						</ul>
					</div>
				<?php endif;?>
				<div class="widget">
					<h3 class="widget-title"><?php echo $author->display_name; ?>的关注 (<a href="#">共<?php echo ($fcount = lp_following_count($author_id));?>位</a>)</h3>
					<?php if($fcount>0){
						
					}?>
				</div>				
				<div class="widget">
					<h3 class="widget-title"><?php echo $author->display_name; ?>的粉丝 (<a href="#">共<?php echo ($fdcount = lp_followed_count($author_id));?>位</a>)</h3>
				</div>			
			</div>
	</div>
</div><!-- #archive -->
<?php get_footer(); ?>