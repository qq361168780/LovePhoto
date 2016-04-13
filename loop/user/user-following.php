<div class="user-posts">
	<div class="bread">我的关注</div>
	<table>
		<tbody>
			<?php 
				$my_following = lp_following($user_ID);
				foreach($my_following as $val){
					$follow_uer = get_user_by("id", $val);
				?>
					<tr class="following">
						<td class="pding"><a class="lp-user-profile" href="<?php echo get_author_posts_url($follow_uer->ID); ?>" title="<?php echo $follow_uer->display_name;?>" userid="<?php echo $follow_uer->ID;?>"><?php echo get_avatar($val , 50 ); ?><span class="follow_uername"><?php echo $follow_uer->display_name;?></span></a></td>
						<td class="ptright"><?php lp_follow_button($val);?></td>
					</tr>
				<?php }
			?>
		</tbody>
	</table>
</div>