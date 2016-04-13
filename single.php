<?php get_header();

global $author_id;

get_currentuserinfo(); // grabs the user info and puts into vars
?>
<div class="section">
	<div class="container clearfix">
		<div class="main">
		<?php if (have_posts()) : while (have_posts()) : the_post();?>
			<?php get_template_part( 'loop/single/loop', get_post_format() ); ?>
		<?php endwhile; endif;?>
		</div>
		<div class="aside">
			<div class="atr-profile">
				<div class="atr-profile-body clearfix">
					<div class="atr-pic">
						<?php $author = get_userdata($author_id); echo get_avatar( $author_id , 48 ); ?>
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
				<div class="atr-profile-footer">
					<ul>
						<li><a href="<?php echo get_author_posts_url($author_id); ?>">主页</a></li>
						<li><a href="<?php echo get_author_posts_url($author_id); ?>/post"><?php printf(__('文章&#40;%s&#41;'), count_user_posts($author_id)); ?></a></li>
						<li><?php printf(__('关注&#40;%s&#41;'), lp_following_count($author_id)); ?></li>
						<li><?php printf(__('粉丝&#40;%s&#41;'), lp_followed_count($author_id)); ?></li>							
					</ul>
				</div>
				<div class="atr-follow">
					<?php lp_follow_button($author_id);?>
				</div>
			</div>		
			<div class="widget widget-popular widget-video">
				<h3 class="widget-title">本周热门内容</h3>
				<ul>
					<?php 
						$week = "views_". date("Y").date("W");
						
						
						$week_posts = query_posts( "showposts=12&meta_key={$week}&orderby=meta_value_num" );
						
						$week_array = array();
						
						if(have_posts()):
							while (have_posts()) : the_post();{
								array_push($week_array, $post->ID );
							?>
								<li>
									<div class="video-pic">
										<a href="<?php the_permalink();?>">
											<?php lp_aside_thumb();?>
										</a>
									</div>
									<div class="video-title">
										<a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
									</div>
									<div class="video-views"><?php if(function_exists('the_week_views')) the_week_views();?></div>
								</li>
							<?php } endwhile;
							$week_posts = null;
							wp_reset_query();
						endif;
						
						$count = count($week_array);

						if( $count <12 ){
							$count = 12 - $count;
							$week_posts = query_posts(array(
								"showposts" => $count,
								"post__not_in" => $week_array
							));
							//var_dump($week_posts);
							if(have_posts()):				
								while (have_posts()) : the_post();{?>
									<li>
										<div class="video-pic">
											<a href="<?php the_permalink();?>">
												<?php lp_aside_thumb();?>
											</a>
										</div>
										<div class="video-title">
											<a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
										</div>
										<div class="video-views"><?php if(function_exists('the_week_views')) the_week_views();?></div>
									</li>
								<?php } endwhile;
							endif;
						}
					?>
				</ul>				
			</div>		
		</div>
	</div>
</div>
<?php get_footer(); ?>