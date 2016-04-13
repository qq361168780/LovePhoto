<div id="comments">
	<?php
		if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
			die ('请不要直接加载该页面，谢谢！');
		
		if ( post_password_required() ) { ?>
			<p class="nocomments"><?php _e('This article requires a password, enter the password to access.'); ?></p> 
		<?php
			return;
		}
	?>

	<?php
		if( comments_open() ) : ?>
		
		<div id="respond">
			<form method="post" action="<?php echo home_url('/wp-comments-post.php'); ?>" id="comment_form">
				<?php if( is_user_logged_in() )  :?>
					<div class="welcome clearfix">
						<span id="cancel-comment-reply" class="right"><?php cancel_comment_reply_link() ?></span>
						<?php printf(__('<a href="#">%s</a>'), $user_identity); ?>
					</div>
				<?php else : ?>
					<div class="comment-tips">
						请 <a class="notsignined" href="<?php bloginfo('url'); ?>/wp-login.php" class="bn-submit">登录</a> 后发表评论。 还没有帐号 <a href="<?php bloginfo('url'); ?>/wp-login.php?action=register" class="register-link">现在注册</a> 也可以使用 <a class="go-weibo" href="<?php echo plugins_url("wp-connect/login.php?go=sina");?>">新浪微博</a> 或 <a class="go-qq" href="<?php echo plugins_url("wp-connect/login.php?go=qzone");?>">QQ帐号</a> 直接登录。
					</div>				
				<?php endif; ?>
					<div id="author_textarea">
						<textarea name="comment" id="comment" class="textarea" placeholder="要说点什么呢..."></textarea>
					</div>
					<div class="respond-footer clearfix">
						<div id="smilies" class="left">
							<a class="smilies" href="#"><span class="smilies-icon"></span><span>表情</span></a>
						</div>
						<div class="smilies-box">
							<a href="#" data-smile=":smile:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_smile.gif" alt="" /></a>
							<a href="#" data-smile=":grin:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_biggrin.gif" alt="" /></a>
							<a href="#" data-smile=":sad:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_sad.gif" alt="" /></a>
							<a href="#" data-smile=":eek:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_surprised.gif" alt="" /></a>
							<a href="#" data-smile=":shock:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_eek.gif" alt="" /></a>
							<a href="#" data-smile=":cool:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_cool.gif" alt="" /></a>
							<a href="#" data-smile=":mad:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_mad.gif" alt="" /></a>
							<a href="#" data-smile=":razz:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_razz.gif" alt="" /></a>
							<a href="#" data-smile=":neutral:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_neutral.gif" alt="" /></a>
							<a href="#" data-smile=":wink:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_wink.gif" alt="" /></a>
							<a href="#" data-smile=":lol:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_lol.gif" alt="" /></a>
							<a href="#" data-smile=":oops:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_redface.gif" alt="" /></a>
							<a href="#" data-smile=":cry:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_cry.gif" alt="" /></a>
							<a href="#" data-smile=":evil:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_evil.gif" alt="" /></a>
							<a href="#" data-smile=":twisted:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_twisted.gif" alt="" /></a>
							<a href="#" data-smile=":roll:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_rolleyes.gif" alt="" /></a>
							<a href="#" data-smile=":?:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_question.gif" alt="" /></a>
							<a href="#" data-smile=":idea:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_idea.gif" alt="" /></a>
							<a href="#" data-smile=":mrgreen:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_mrgreen.gif" alt="" /></a>
							<a href="#" data-smile=":!:"><img src="<?php bloginfo('template_url'); ?>/images/smilies/icon_exclaim.gif" alt="" /></a>
						</div>
						<input id="submit" type="submit" name="submit" <?php if(!is_user_logged_in()) echo "disabled";?> value="<?php _e('发布评论'); ?>" class="submit" />
					</div>
				<?php comment_id_fields(); ?> 
				<?php do_action('comment_form', $post->ID); ?>
			</form>
		</div>
	<?php endif; ?>	
	
	<?php if ( have_comments() ) : ?>
		<div class="comments-container">
			<ol class="commentlist clearfix">
				<?php wp_list_comments('type=comment&callback=lp_comment'); ?>
			</ol>
			<div class="commentlist-footer">
				<?php if ('open' != $post->comment_status) : ?>
					<h2 class="comments-title">评论已关闭.</h2>
				 <?php else : ?>
					<div class="comment-nav"><?php paginate_comments_links('prev_text=« Previous&next_text=Next »');?></div>
				<?php endif; ?>
			</div>
		</div>
	 <?php else : ?>
		<?php if ('open' != $post->comment_status) : ?>
			<h2 class="comments-title">评论已关闭.</h2>
		<?php endif; ?>
	<?php endif; ?>

</div>