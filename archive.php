<?php
get_header();

global $array;

if( is_category() ){
	$category_name = single_tag_title('',false);
	$category_ID = get_cat_ID($category_name);

	$array = array(
		"type" => "分类：".single_cat_title('',false),
		"query_type" => "cat",
		"query_value" => $category_ID
	);
}

if( is_tag() ){
	$tag_name = single_tag_title('', false);
	
	$array = array(
		"type" => "标签：".$tag_name,
		"query_type" => "tag",
		"query_value" => $tag_name
	);
}

if( is_tax( 'post_format' ) ){
	$format = get_post_format();
	
	$format_array = array(
		"image" => "图片",
		"audio" => "音乐",
		"video" => "视频"
	);
	
	$array = array(
		"type" => $format_array[$format],
		"query_type" => "tax_query",
		"query_value" => array(
			array(
				'taxonomy' => 'post_format',
				'field' => 'slug',
				'terms' => 'post-format-' . $format
			)
		)
	);	
}

?>
<div class="gather-body section">
	<div class="container clearfix">
		<div class="main">
			<div class="bread clearfix">
				<a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>">首页</a><span class="split">/</span><?php printf(__('%s'), $array["type"]) ?>
			</div>		
			<?php if (have_posts()) : while (have_posts()) : the_post();?>
				<?php get_template_part( 'loop/archive/loop', get_post_format() ); ?>
			<?php endwhile; endif;?>
			<div class="pagenavi">
				<?php lp_pagenavi();?>
			</div>
		</div>
		<div class="aside">
			<div class="widget widget-popular widget-video">
				<h3 class="widget-title"><?php printf(__('%s'), $array["type"]) ?> 本周热门内容</h3>
				<ul>
					<?php
						$week = "views_". date("Y").date("W");
						$args = array(
							'showposts' => 12,
							$array["query_type"] => $array["query_value"],
							'meta_key' => $week,
							'orderby' => 'meta_value_num'					
						);
						$posts = query_posts($args);
						if(have_posts()): 
							while (have_posts()) : the_post();
								$audio_array = lp_audio_detail(); 
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
</div><!-- #archive -->
<?php get_footer(); ?>