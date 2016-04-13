<?php
get_header();
$logined = is_user_logged_in() ? "l" : "u";
get_template_part( 'loop/home/loop', $logined );
?>
<?php get_footer(); ?>