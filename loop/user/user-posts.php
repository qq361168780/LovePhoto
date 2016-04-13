<?php
	global $visage;
	$post_count = count_user_posts($user_ID);
	$max_page = ceil( $post_count / 20 );
?>
	<div class="user-posts">
		<div class="bread">我的文章</div>
			<?php if( isset($_GET["msg"]) ) echo '<div class="form-msg">已删除！</div>';?>
			<table>
				<thead><tr><th class="pding">标题</th><th>时间</th><th>状态</th><th>操作</th></tr></thead>
				<tbody>
				<?php			
						$paged = get_query_var('paged') ? get_query_var('paged') : 1;
						$status = array("publish" => "已发布", "pending" => "待审核");
						$array = array(
							'author' => $user_ID,
							'paged' => $paged,
							'posts_per_page' => 20,
							'post_status' => array('publish', 'pending'),
							'ignore_sticky_posts' => 1
						);
						
						$query_posts = query_posts($array);
						if(have_posts()): 
							while (have_posts()) : the_post();?>
								<tr>
									<td class="pding"><a href="<?php the_permalink(); ?>" title="<?php the_title();?>" target="_blank"><?php the_title();?></a></td>
									<td><?php the_time('Y-m-d') ?></td>
									<td><?php echo $status[$post->post_status];?></td>
									<td><a class="delete-post" href="#" data-postid="<?php echo $post->ID;?>">删除</a></td>
								</tr>
							<?php endwhile;
							$query_posts = null;
							$array = null;
							wp_reset_query();
						endif;
						?>
				</tbody>
			</table>
		<form id="delete-post-form" method="post" action="">
			<input type="hidden" name="action" value="lp_ajax_del_post" />
			<input id="delete-postid" type="hidden" name="postid" value="" />
		</form>
		<script type="text/javascript">
			$(".delete-post").click(function(e){
				e.preventDefault();
				var postid = $(this).attr("data-postid");
				var posttitle = $(this).parent().parent().find(".pding a").attr("title");
				var result = window.confirm("你确定删除 《"+posttitle+"》 吗?");
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