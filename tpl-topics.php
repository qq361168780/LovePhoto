<?php
/*
Template Name: 热门文章
*/
get_header(); 

global $array;
$array["query_type"] ="topics";
?>

<div class="gather-body section">
	<div class="container clearfix">
		<div class="main">
			<div class="bread clearfix">
				本周热门文章
				<div class="right">
					<a href="<?php echo home_url();?>">最新动态»</a>
				</div>
			</div>			
			<?php 
				$week = "views_". date("Y").date("W");
				
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$pre_page = get_option('posts_per_page');
				
				$week_posts = query_posts( "paged={$paged}&meta_key={$week}&orderby=meta_value_num" );
				
				$week_count = count($week_posts);
				
				if(have_posts()){
					while (have_posts()) : the_post();
						get_template_part( 'loop/archive/loop', get_post_format() );
					endwhile;
						
					if( $week_count < $pre_page){
						$week = $pre_page - $week_count;
						$week_posts = query_posts( "paged={$paged}&posts_per_page={$week}" );
						if(have_posts()):while (have_posts()) : the_post();
							get_template_part( 'loop/archive/loop', get_post_format() );
						endwhile;endif;						
					}
				}else{
					$week_posts = query_posts( "paged={$paged}" );
					if(have_posts()):while (have_posts()) : the_post();
						get_template_part( 'loop/archive/loop', get_post_format() );
					endwhile;endif;					
				}
			?>			
			<div class="pagenavi">
				<?php lp_pagenavi();?>
			</div>
		</div>
		<div class="aside">
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
</div><!-- #archive -->
<?php get_footer(); ?>