</div><!-- #content --><div id="footer">
	<div class="container clearfix">		<?php wp_nav_menu(array( 'theme_location'=>'footer','container_class' => 'footer-nav right')); ?>		&copy; <?php echo date("Y");?> <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a> All Rights Reserved!	</div>
</div><!-- #footer --><?php wp_footer(); ?></body></html>