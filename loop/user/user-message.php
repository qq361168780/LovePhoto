<div class="user-posts">
	<div class="bread">消息中心</div>
	<table>
		<tbody>
<?php
global $visage;
						$paged = get_query_var('paged') ? get_query_var('paged') : 1;
						
						$results = $visage->get_visage($user_ID, $paged);
						
						$max_page = $visage->visage_page($user_ID);

						foreach( $results as $val ){
							switch ($val->type){
								case "liked":
									$user_id = $val->user;
									$user = get_user_by("id", $user_id);
									$user_link = get_author_posts_url($user_id);
									$name = $user->display_name;
									
									$post_id = $val->postid;
									$post = get_post($post_id);
									$post_title = $post->post_title;
									$post_link = get_permalink($post_id );
									
									$time = date("Y-m-d G:i:s", $val->created);
									$avatar = get_avatar( $user_id, 16);
									
									echo "<tr class='message'><td class='pding'>{$avatar}<a class='message-author lp-user-profile' href='{$user_link}' userid='{$user_id}'>{$name}</a> 收藏了 <a href='{$post_link}'>《{$post_title}》</a></td><td>{$time}</td></tr>";
								break;
								case "follow":
									$user_id = $val->user;
									$user = get_user_by("id", $user_id);
									$user_link = get_author_posts_url($user_id);
									$name = $user->display_name;
									
									$time = date("Y-m-d G:i:s", $val->created);
									$avatar = get_avatar( $user_id, 16);
									echo "<tr class='message'><td class='pding'>{$avatar}</div><a class='message-author lp-user-profile' href='{$user_link}' userid='{$user_id}'>{$name}</a> 关注了你</td><td>{$time}</td></tr>";									
								break;
								case "comment":
									$user_id = $val->user;
								break;
							}
						}
						
							$time = time() + (60*60*get_settings("gmt_offset"));
							update_user_meta($user_ID, "check_time", $time);	
						?>
					</tbody>
			</table>						
</div>

			<div class="pagenavi">
				<?php lp_pagenavi($max_page);?>
			</div>