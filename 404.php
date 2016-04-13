<?php
get_header();
?>
<div class="gather-body section">
	<div class="container clearfix">
		<div class="main">
			<h3>404，该页面未能找到！</h3>
		</div>
		<div class="aside">
			<div class="widget widget-popular widget-video">
			<div class="widget widget-popular widget-video">
				<h3 class="widget-title">历史热门内容</h3>
				<ul>
					<?php
						$query_posts = query_posts( "showposts=20&meta_key=views&orderby=meta_value_num" );
						if(have_posts()): 
							while (have_posts()) : the_post();{
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
									<div class="video-views"><?php if(function_exists('the_views')) the_views();?></div>
								</li>
							<?php }
							endwhile;
							$query_posts = null;
							wp_reset_query();							
						endif;
					?>	
				</ul>				
			</div>				
			</div>
		</div>
	</div>
</div><!-- #archive -->
<?php get_footer(); ?>