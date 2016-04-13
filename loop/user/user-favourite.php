<?php 

global $visage;
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
						
$array = $visage->user_liking($user_ID, $paged);

$count = $visage->user_liking_count($user_ID);
									
$max_page = ceil( $count / 20 );

?>
	<div class="user-posts">
		<div class="bread">我的收藏</div>
		<?php if( isset($_GET["msg"]) ) echo '<div class="form-msg">已取消收藏！</div>';?>
			<table>
				<thead><tr><th class="pding">标题</th><th>作者</th><th>时间</th><th>操作</th></tr></thead>
				<tbody>
				<?php
					if( $count >0 ){
						$args = array();
						
						foreach($array as $val){
							array_push($args, $val->postid);
						}
						
						$array = array(
							'post__in'   => $args,
							'posts_per_page' => 20,
							'ignore_sticky_posts' => 1							
						);
						
						$posts = query_posts($array);
						if(have_posts()): 
							while (have_posts()) : the_post();?>
								<tr>
									<td class="pding"><a href="<?php the_permalink(); ?>" title="<?php the_title();?>" target="_blank"><?php the_title();?></a></td>
									<td><a class="lp-user-profile" href="<?php echo get_author_posts_url(get_the_author_id()); ?>" title="<?php the_author(); ?>" userid="<?php echo get_the_author_id();?>"><?php the_author(); ?></a></td>
									<td><?php the_time('Y-m-d'); ?></td>
									<td><a class="delete-post" href="#" data-postid="<?php echo $post->ID;?>">取消收藏</a></td>
								</tr>
							<?php endwhile;
							$posts = null;
							$array = null;
							wp_reset_query();
						endif;
					}
				?>
				</tbody>
			</table>
		<form id="delete-post-form" method="post" action="">
			<input type="hidden" name="action" value="lp_ajax_del_love" />
			<input id="delete-postid" type="hidden" name="postid" value="" />
		</form>
		<script type="text/javascript">
			$(".delete-post").click(function(e){
				e.preventDefault();
				var postid = $(this).attr("data-postid");
				var posttitle = $(this).parent().parent().find(".pding a").attr("title");
				var result = window.confirm("你确定不再收藏 《"+posttitle+"》 吗?");
				if(result){
					$("#delete-postid").val(postid);
					$("#delete-post-form").submit();
				}
				return false;
			});
		</script>
	</div>
	<div class="pagenavi">
		<?php lp_pagenavi($max_page);?>
	</div>

			