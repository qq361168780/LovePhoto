<?php get_header();?>
<div class="section">
	<div class="container clearfix">
		<?php if (have_posts()) : while (have_posts()) : the_post();?>
			<?php the_content(); ?>
		<?php endwhile; endif;?>
	</div>
</div>
<?php get_footer(); ?>