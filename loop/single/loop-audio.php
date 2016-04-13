<?php global $author_id;$author_id = get_the_author_id();$audio = lp_audio_detail(); ?>

	<div class="single-header">
		<h2 class="single-title"><?php the_title();?></h2>
		<div class="single-postmeta clearfix"><a class="lp-user-profile" href="<?php echo get_author_posts_url(get_the_author_id()); ?>" title="<?php the_author(); ?>" userid="<?php echo get_the_author_id();?>">
			<?php the_author(); ?>
		</a><span class="split2">|</span><?php the_category(','); ?><span class="split2">|</span><?php the_time('Y-m-d') ?><span class="split2">|</span><?php if(function_exists('the_views')) the_views();?><div class="single-like right">
			<?php lp_like_button();?>
		</div></div>
	</div>
	<div class="single-content">
		<?php the_content();?>
	</div>
	<div class="single-author">
		<?php echo get_avatar( get_the_author_id() , 40 ); ?>
		<div class="single-follow"><?php lp_follow_button(get_the_author_id());?></div>	
		<p>作者：<a class="lp-user-profile" href="<?php echo get_author_posts_url(get_the_author_id()); ?>" title="<?php the_author(); ?>" userid="<?php echo get_the_author_id();?>">
			<?php the_author(); ?>
		</a></p>
		<p><?php echo get_the_author_meta('user_description');?></p>
	</div>	
	<div class="single-sns clearfix">
		<div class="single-tags left">
			<?php if ( get_the_tags() ) { the_tags('标签：', '', ''); } else{ echo "暂无关键词！";  } ?>
		</div>
		<div class="single-share right">
		
			<!-- Baidu Button BEGIN -->
			<div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare">
			<a class="bds_tsina"></a>
			<a class="bds_tqq"></a>
			<a class="bds_qzone"></a>
			<a class="bds_renren"></a>
			<a class="bds_douban"></a>
			<a class="bds_huaban"></a>
			<span class="bds_more"></span>
			</div>
			<script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=2256587" ></script>
			<script type="text/javascript" id="bdshell_js"></script>
			<script type="text/javascript">
			document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000)
			</script>
			<!-- Baidu Button END -->		
			
		</div>			
	</div>
	<?php lp_related_posts();?>
	<div class="single-tab">
		<ul class="nav clearfix">
			<li class="nav-li current"><a class="nav-lia" href="<?php the_permalink(); ?>#comments"><?php comments_number('0','1','%'); ?>条评论</a></li>
			<li class="nav-li"><a class="nav-lia" href="<?php the_permalink(); ?>#likes"><?php lp_post_liked_count();?>人收藏</a></li>
		</ul>
		<ul class="stc-ul">
			<li class="stc-li current">
				<?php comments_template(); ?>
			</li>
			<li class="stc-li"><?php lp_post_likes();?></li>
		</ul>
	</div>