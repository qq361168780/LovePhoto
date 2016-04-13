<?php
/*
Template Name: 最新文章
*/
get_header(); 

global $array;
$array["query_type"] = "topics";
get_currentuserinfo(); // grabs the user info and puts into vars
$following_count = lp_following_count($user_ID);
?>
<div class="gather-body section">
	<div class="container clearfix">
		<div class="main">
			<div class="bread clearfix">
				最新动态
				<div class="right">
					<a href="<?php echo lp_page_link( "topics");?>">本周热门»</a>
				</div>
			</div>
			<?php
				if($following_count>0){
					$my_following = lp_following($user_ID);
					$my_following = implode(",", $my_following);  
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					query_posts(array(
						"author" => $my_following,
						"paged" => $paged
					));
					
					if(have_posts()): 
						while (have_posts()) : the_post();
							get_template_part( 'loop/archive/loop', get_post_format() );
						endwhile;
					endif;
				}else{
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					query_posts( "paged={$paged}" );
					
					if(have_posts()): 
						while (have_posts()) : the_post();
							get_template_part( 'loop/archive/loop', get_post_format() );
						endwhile;
					endif;
				}
			?>			
			<div class="pagenavi">
				<?php lp_pagenavi();?>
			</div>
		</div>
		<div class="aside">
			<div class="widget">
				<ul class="widget-ul">
					<li class="submit-new"><a href="<?php echo lp_page_link( "user" );?>"><span class="icon icon-pencil"></span>撰写新文章</a></li>
					<li><a href="<?php echo get_author_posts_url($user_ID); ?>"><span class="icon icon-home"></span>我的主页</a></li>
					<li><a href="<?php echo lp_page_link( "user" );?>/favourite"><span class="icon icon-heart"></span><?php printf(__('我的收藏<span class="split">(%s)</span>'), lp_liking_count($user_ID)); ?></a></li>
					<li><a href="<?php echo lp_page_link( "user" );?>/posts"><span class="icon icon-bookmark"></span><?php printf(__('我的文章<span class="split">(%s)</span>'), count_user_posts($user_ID)); ?></a></li>
					<li><a href="<?php echo lp_page_link( "user" );?>/message"><span class="icon icon-comment"></span>消息中心</a></li>
					<li><a href="<?php echo lp_page_link( "user" );?>/setting"><span class="icon icon-cog"></span>账号设置</a></li>
				</ul>
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
</div><!-- #archive -->
<?php get_footer(); ?>