<div class="bread">发布的文章</div>
<?php if (have_posts()) : while (have_posts()) : the_post();?>
	<?php get_template_part( 'loop/archive/loop', get_post_format() ); ?>
<?php endwhile;
	else:?>
	<div class="comment-tips">该用户尚未发布任何文章!</div>			
<?php endif;?>
<div class="pagenavi">
	<?php lp_pagenavi();?>
</div>