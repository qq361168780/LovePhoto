<?php
/*
Template Name: 文字
*/
get_header(); 

global $array;
$array["query_type"] = "tax_query";
?>
<div class="gather-body section">
	<div class="container clearfix">
		<div class="main">
			<div class="bread clearfix">
				<a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>">首页</a><span class="split">/</span>文字
			</div>				
			<?php 
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				query_posts(
					array(
						'tax_query' => array(
							array(
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => array('post-format-image', 'post-format-audio', 'post-format-video'),
								'operator' => 'NOT IN'
							)
						),
						'paged' => $paged
				));
				
				if(have_posts()): 
					while (have_posts()) : the_post();
						get_template_part( 'loop/archive/loop', get_post_format() );
					endwhile;
				endif;
			?>			
			<div class="pagenavi">
				<?php lp_pagenavi();?>
			</div>
		</div>
		<div class="aside">
			<div class="widget widget-popular widget-video">
				<h3 class="widget-title">本周热门内容</h3>
				<ul>
					<?php
						$week = "views_". date("Y").date("W");
						
						$query_posts = wp_cache_get(1, 'views_week');

						if(false === $query_posts){
							$query_posts = query_posts( "showposts=12&meta_key={$week}&orderby=meta_value_num" );
							wp_cache_set(1, $query_posts, 'views_week', 3600);
						}						
						
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
									<div class="video-views"><?php if(function_exists('the_week_views')) the_week_views();?></div>
								</li>
							<?php }
							endwhile;
							$week = null;
							$query_posts = null;
							wp_reset_query();							
						endif;
					?>
				</ul>				
			</div>		
		</div>
	</div>
</div><!-- #archive -->
<?php get_footer(); ?>