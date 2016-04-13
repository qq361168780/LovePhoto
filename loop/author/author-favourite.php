<?php 
global $visage;
$author = $author_name ? get_user_by('slug', $author_name) : get_userdata(intval($author));
$author_id = $author->ID;

$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$pre_page = get_option('posts_per_page');					
$array = $visage->user_liking($author_id, $paged, $pre_page);
$count = $visage->user_liking_count($author_id);		
$max_page = ceil( $count / $pre_page );
?>
<div class="bread">收藏的文章</div>
<?php
if($count>0){
	$args = array();
	foreach($array as $val){
		array_push($args, $val->postid);
	}

	$array = array(
		'post__in'   => $args,
		'posts_per_page' => $pre_page,
		'ignore_sticky_posts' => 1	
	);

	$posts = query_posts($array);
	if(have_posts()): 
		while (have_posts()) : the_post();
			get_template_part( 'loop/archive/loop', get_post_format() );
		endwhile;
		$posts = null;
		$array = null;
		wp_reset_query();
	endif;
	echo '<div class="pagenavi">';
	lp_pagenavi($max_page);
	echo '</div>';
}else{
?>
<div class="comment-tips">该用户尚未收藏任何文章!</div>
<?php }
?>