<?php 
global $array;
$audio = lp_audio_detail();
?>
<div class="archive">
	<a class="archive-avatar lp-user-profile" href="<?php echo get_author_posts_url(get_the_author_id()); ?>" title="<?php the_author(); ?>" userid="<?php echo get_the_author_id();?>">
		<?php echo get_avatar( get_the_author_id() , 50 ); ?>
	</a>
	<div class="archive-header clearfix">
		<span class="archive-date right"><?php echo lp_time_since(abs(strtotime($post->post_date. "GMT")));?></span>
		<a class="lp-user-profile" href="<?php echo get_author_posts_url(get_the_author_id()); ?>" title="<?php the_author(); ?>" userid="<?php echo get_the_author_id();?>">
			<?php the_author(); ?>
		</a>发布了音乐
	</div>
	<div class="archive-body">
		<h2 class="archive-title"><a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><?php the_title();?></a></h2>
		<div class="archive-content clearfix">
			<div class="archive-thumbnail">
				<a href="<?php the_permalink(); ?>" title="<?php the_title();?>">
					<img src="<?php echo $audio["original"];?>" alt="<?php the_title(); ?>" width="160" height="108" />
				</a>
				<span class="music-icon"></span>
			</div>
			<p><?php echo mb_strimwidth(lp_strip_tags($post->post_content), 0, 300,"...");?></p>
		</div>
		<div class="archive-footer clearfix">
			来至 <a href="<?php echo get_post_format_link('audio')?>">音乐</a><span class="archive-blank">/</span>分类 <?php the_category(' '); ?>
			<div class="right">
				<span class="archive-span">
					<?php if($array["query_type"] == "topics"){
						if(function_exists('the_week_views')) the_week_views();
					}else{
						if(function_exists('the_views')) the_views();
					}
					?>			
				</span>
				<span class="archive-span">
					<?php comments_popup_link('0评论', ' 1评论', '%评论'); ?>
				</span>
				<span class="archive-span">
					<?php lp_like_button();?>
				</span>			
			</div>
		</div>
	</div>
</div>