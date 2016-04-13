<?php 
global $visage;
$author = $author_name ? get_user_by('slug', $author_name) : get_userdata(intval($author));
$author_id = $author->ID;
$array = $visage->user_liking($author_id, 1, 5);
?>

<div class="bread author-bread"><?php printf(__('收藏 · · · · · · <span class="bread-span">&#40;<a href="%s">共%s篇</a>&#41;</span>'), get_author_posts_url($author_id)."/favourite", lp_liking_count($author_id)); ?></div>
<div class="author-fav">
	<?php
		if( count($array)>0 ){
			echo '<ul class="inline-ul">';
			$args = array();
			foreach($array as $val){
				array_push($args, $val->postid);
			}
			
			$array = array(
				'post__in'   => $args,
				'ignore_sticky_posts' => 1	
			);
			
			$posts = query_posts($array);
				if(have_posts()): 
					while (have_posts()) : the_post();
						var_dump($i++);
					?>
						<li class="inline-li">
							<a class="post-fav" href="<?php the_permalink(); ?>" title="<?php the_title();?>">
								<?php lp_popular_thumb(true, 176, 120); ?>
								<span class="popular-text"><?php the_title();?></span>
							</a>
						</li>
					<?php
					endwhile;
					$posts = null;
					$array = null;
					$args = null;
					wp_reset_query();
				endif;
				echo '</ul>';		
		}else{
			?>
				<div class="comment-tips">该用户尚未收藏任何文章!</div>
		<?php }
	?>
</div>
<div class="bread author-bread"><?php printf(__('文章 · · · · · · <span class="bread-span">&#40;<a href="%s">共%s篇</a>&#41;</span>'), get_author_posts_url($author_id)."/post", count_user_posts($author_id)); ?></div>
		<?php
			$argsx = array(
				'paged' => 1,
				'author' => $author_id,
				'ignore_sticky_posts' => 1
			);
			$postsx = query_posts($argsx);
			if(have_posts()): 
				while (have_posts()) : the_post();
					get_template_part( 'loop/archive/loop', get_post_format() );
				endwhile;
				$postsx = null;
				$argsx = null;
				wp_reset_query();
else:?>
	<div class="comment-tips">该用户尚未发布任何文章!</div>			
<?php endif;?>