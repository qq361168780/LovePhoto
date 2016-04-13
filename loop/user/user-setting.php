<?php
global $userdata;
$smcdata = get_user_meta($userdata->ID, "smcdata");
?>
<div class="user-posts">
	<div class="bread">账号设置</div>
	<?php 
		if( isset($_GET["msg"]) ) echo '<div class="form-msg">已更新个人资料！</div>';
		if( isset($_GET["err"]) ){
			switch($_GET["err"]){
				case 1:
					echo '<div class="form-msg"><strong>昵称</strong> 不能为空！</div>';
					break;
				case 2:
					echo '<div class="form-msg"><strong>个性网址</strong> 只能由：数字、英文字母、下划线组成，必须以英文字母开头，长度在4-16以内！</div>';
					break;
				case 3:
					echo '<div class="form-msg"><strong>个性网址</strong> 已被占用，请更换成其他！</div>';
					break;						
				case 4:
					echo '<div class="form-msg"><strong>密码长度</strong> 不能小于8位！</div>';
					break;
				case 5:
					echo '<div class="form-msg"><strong>两次输入的密码</strong> 不相同，请重新输入！</div>';
					break;
			}
		};
	?>
	<form action="" method="post">
		<div class="form-item clearfix">
			<label class="form-label" for="nicename">用户昵称</label>
			<input type="text" class="text" name="display_name" placeholder="用户昵称" id="nicename" tabindex="1" value="<?php echo $userdata->display_name; ?>">
		</div>
		<div class="form-item clearfix">
			<label class="form-label" for="url_token">个性网址</label>
			<input type="text" class="text" name="user_nicename" placeholder="用户昵称" id="url_token" tabindex="1" value="<?php echo $userdata->user_nicename; ?>">
		</div>
		<div class="url_token form-blank"><?php echo home_url("/author/");?><strong id="url_token-input"><?php echo $userdata->user_nicename; ?></strong></div>
		<div class="form-item clearfix">
			<label class="form-label" for="userdetail">个人简介</label>
			<textarea id="userdetail" name="description" class="text" tabindex="2"><?php echo $userdata->user_description; ?></textarea>	
		</div>
		<?php if( empty($smcdata) ){?>
			<div class="form-item clearfix">
				<label class="form-label" for="pwd1">修改密码</label>
				<input type="password" class="text" name="pwd1" placeholder="修改密码" id="pwd1" tabindex="3" value="">
			</div>
			<div class="form-item clearfix">
				<label class="form-label" for="pwd2">确认密码</label>
				<input type="password" class="text" name="pwd2" placeholder="确认密码" id="pwd2" tabindex="4" value="">
			</div>		
		<?php }?>
		<div class="form-item form-blank">
			<input type="hidden" name="action" value="lp_ajax_setting" />
			<input type="submit" id="form-submit" value="提交修改">
		</div>		
	</form>
	<script type="text/javascript">
		;jQuery(document).ready(function($){
			$("#url_token").on('focus keyup input paste',function(){
				$("#url_token-input").text($(this).val());
			});
		});
	</script>
</div>