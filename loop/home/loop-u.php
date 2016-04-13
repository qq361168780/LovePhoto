<div id="register" class="section">
		<div class="container clearfix">
			<div class="login">
				<form action="<?php echo site_url('wp-login.php'); ?>" method="post">
					<div class="item item-account">
						<input type="text" name="log" value="" class="inp" placeholder="邮箱地址" tabindex="1">
						<div class="opt"><label for="form-remember"><input id="form-remember" name="rememberme" type="checkbox" value="forever" checked="checked" tabindex="4"> 记住我的登录状态</label></div>
					</div>
					<div class="item item-passwd">
						<input name="pwd" placeholder="密码" class="inp" type="password" tabindex="2">
						<div class="opt">
							<?php lp_login_weibo(lp_page_link( "user" ));?>
						</div>
					</div>
					<div class="item-submit">
						<input name="login" value="登录" type="submit" class="bn-submit" tabindex="4">
					</div>
				</form>
			</div>
			<div class="register">
				<a href="<?php bloginfo('url'); ?>/wp-login.php?action=register" class="register-link">加入我们</a>
				<div class="register-info">发现更多生活</div>
			</div>
		</div>
</div><!-- #register -->

<div id="popular" class="section">
	<div class="container">
		<div class="section-header clearfix">
			<h1 class="section-title left">本周热门内容</h1>
			<a href="<?php echo lp_page_link( "topics" );?>" class="right">更多»</a>
		</div>
		<div class="clearfix">
		<?php $index = 8; 
			$args = array(
				'paged' => 1,
				'showposts' => 8,
				'meta_key' => "views_". date("Y").date("W"),
				'orderby' => 'meta_value_num',
				'ignore_sticky_posts' => 1
			);
			$posts = query_posts($args);
			$count = count($posts);
			
			if($count  < 8) $exclude_id ="";
			
			if(have_posts()): 
				while (have_posts()) : the_post();
					$index--;
				?>
					<a class="popular<?php if($index%4==0) echo " the-final";?>" href="<?php the_permalink(); ?>" title="<?php the_title();?>">
						<?php lp_popular_thumb(true); ?>
						<span class="popular-text"><?php the_title();?></span>
					</a>
				<?php 
					if( $count <8 ) $exclude_id .= ',' . $post->ID;
				endwhile;
				$posts = null;
				wp_reset_query();
			endif;

			if($count  < 8){
				$args = array(
					'showposts' => $index,
					'ignore_sticky_posts' => 1,
					'post__not_in' => explode(',', $exclude_id),
				);
				$posts = query_posts($args);
				if(have_posts()): 
					while (have_posts()) : the_post();
						$index--;
					?>
						<a class="popular<?php if($index%4==0) echo " the-final";?>" href="<?php the_permalink(); ?>" title="<?php the_title();?>">
							<?php lp_popular_thumb(true); ?>
							<span class="popular-text"><?php the_title();?></span>
						</a>
					<?php endwhile;
					$posts = null;
					$index = null;
					$exclude_id = null;
					wp_reset_query();
				endif;			
			}
		?>
		</div>
		<div id="hma" class="clearfix">
			<h1 class="section-title left">热门作者</h1>
			<ul class="hma-ul left">
				<?php
				$hmas = get_users('orderby=post_count&number=7');
				foreach(array_reverse($hmas) as $hma){
				?>
					<li class="hma-li">
						<a class="hma lp-user-profile" href="<?php echo get_author_posts_url($hma->ID); ?>" title="<?php echo $hma->display_name;?>" userid="<?php echo $hma->ID;?>">
							<?php echo get_avatar( $hma->ID , 42 ); ?>
							<span class="hma-nicename"><?php echo $hma->display_name;?></span>
							<span class="hma-follow"><span class="split2">粉丝：</span><?php echo lp_followed_count($hma->ID);?></span>
						</a>
					</li>
				<?php }
				?>
			</ul>
		</div>
	</div>
</div><!-- #popular -->

<div id="music" class="section">
	<div class="container clearfix">
		<div class="main">
			<div class="section-header clearfix">
				<h1 class="section-title left">音乐</h1>
				<a href="<?php echo get_post_format_link('audio')?>" class="right">更多»</a>
			</div>
			<div class="section-body">
				<?php
					$index = 10; 
					$args = array(
						'paged' => 1,
						'showposts' => 10,
						'tax_query' => array(
							array(
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => 'post-format-audio'
							)
						)
					);
					$posts = query_posts($args);
					if(have_posts()): 
						while (have_posts()) : the_post();
							$index--;
							$audio_array = lp_audio_detail(); 
						?>
							<div class="music<?php if($index%5==0) echo " the-final";?>">
								<div class="music-pic">
									<a href="<?php the_permalink() ?>">
										<img src="<?php echo $audio_array["cover"];?>" alt="<?php the_title(); ?>" width="100" height="100" />
									</a>
								</div>
								<div class="music-title">
									<a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
								</div>
								<div class="music-author"><?php echo $audio_array["author"];?></div>
							</div>
						<?php
						endwhile;
						$audio_array = null;
						$posts = null;
						$index = null; 
						wp_reset_query();
					endif;
				?>
			</div>
		</div>
		<div class="aside">
			<div class="widget widget-music">
				<h3 class="widget-title">热门音乐</h3>
				<ul>
					<?php
						$args = array(
							'paged' => 1,
							'showposts' => 6,
							'tax_query' => array(
								array(
									'taxonomy' => 'post_format',
									'field' => 'slug',
									'terms' => 'post-format-audio'
								)
							),
							'meta_key' => 'views',
							'orderby' => 'meta_value_num'					
						);
						$posts = query_posts($args);
						if(have_posts()): 
							while (have_posts()) : the_post();
								$audio_array = lp_audio_detail(); 
							?>
								<li>
									<div class="music-body">
										<div class="music-pic">
											<a href="<?php the_permalink() ?>">
												<img src="<?php echo $audio_array["cover"];?>" alt="<?php the_title(); ?>" width="36" height="36" />
											</a>
										</div>
										<div class="music-title">
											<a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
										</div>
										<div class="music-author"><?php echo $audio_array["author"];?></div>
									</div>
								</li>
							<?php endwhile;
							$posts = null;
							$audio_array = null;
							wp_reset_query();
						endif;
					?>				
				</ul>
			</div>
		</div>
	</div>
</div><!-- #music -->

<div id="video" class="section">
	<div class="container clearfix">
		<div class="main">
			<div class="section-header clearfix">
				<h1 class="section-title left">视频</h1>
				<a href="<?php echo get_post_format_link('video')?>" class="right">更多»</a>
			</div>
			<div class="section-body clearfix">
				<?php
					$index = 6; 
					$args = array(
						'paged' => 1,
						'showposts' => 6,
						'tax_query' => array(
							array(
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => 'post-format-video'
							)
						)
					);
					$posts = query_posts($args);
					if(have_posts()): 
						while (have_posts()) : the_post();
							$index--;
							$video_array = lp_video_detail(); 
						?>
							<div class="video<?php if($index%3==0) echo " the-final";?>">
								<div class="video-pic">
									<a href="<?php the_permalink();?>">
										<img src="<?php echo $video_array["original"];?>" alt="<?php the_title(); ?>" width="206" height="140" />
										<span class="video-time"><?php echo $video_array["ltime"];?></span>
									</a>
								</div>
								<div class="video-title">
									<a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
								</div>
							</div>
						<?php endwhile;
						$posts = null;
						$index = null;
						$video_array = null;
						wp_reset_query();
					endif;
				?>
			</div>
		</div>
		<div class="aside">
			<div class="widget widget-video">
				<h3 class="widget-title">热门视频</h3>
				<ul>
					<?php
						$args = array(
							'paged' => 1,
							'showposts' => 5,
							'tax_query' => array(
								array(
									'taxonomy' => 'post_format',
									'field' => 'slug',
									'terms' => 'post-format-video'
								)
							),
							'meta_key' => 'views',
							'orderby' => 'meta_value_num'					
						);
						$posts = query_posts($args);
						if(have_posts()): 
							while (have_posts()) : the_post();
								$video_array = lp_video_detail(); 
							?>
								<li>
									<div class="video-pic">
										<a href="<?php the_permalink();?>">
											<img src="<?php echo $video_array["cover"];?>" alt="<?php the_title(); ?>" width="87" height="60" />
											<span class="video-time"><?php echo $video_array["ltime"];?></span>
										</a>
									</div>
									<div class="video-title">
										<a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
									</div>
									<div class="video-views"><?php if(function_exists('the_views')) the_views();?></div>
								</li>
							<?php endwhile;
							$posts = null;
							$video_array = null;
							wp_reset_query();
						endif;
					?>				
				</ul>
			</div>
		</div>
	</div>
</div><!-- #video -->