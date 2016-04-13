<?php
/**
 * Theme functions file =W= lp-action.php 
 *
 *
 * @package LovePhoto
 * @author Mufeng
 */
 

	add_action('init', 'lp_ajax_action');
	function lp_ajax_action(){
		// 请求方法为 post
		if( 'POST' == $_SERVER['REQUEST_METHOD'] ){
			// 虾米封面
			if( $_POST['action'] == 'lp_ajax_cover' ){
				$src = $_POST['src'] ? $_POST['src'] : null;
				$sid = $_POST['sid'] ? $_POST['sid'] : null;
				$type = $_POST['type'] ? $_POST['type'] : null;
				
				if( !$src ){
					lp_ajax_error("Not a valid image url.");
				}

				if( !$src ){
					lp_ajax_error("Not a valid song id.");
				}

				if( !$type ){
					lp_ajax_error("Not type. No way.");
				}			
				
				$blog_url = home_url("/");
				$path = "wp-content/uploads/{$type}/";
				
				$small_one = $path. $sid .".0.png";
				$large_one = $path. $sid .".1.png";			
				
				if( !( @file_exists($small_one) && @file_exists($large_one) ) ){
					$image = wp_get_image_editor( $src ); 
					if ( is_wp_error( $image ) ) {
						lp_ajax_error("Not a valid image url.");
					}
					
					$image->set_quality( 100 );
					
					$image_tmp = $image;
					
					$image->resize( 235, 160, true );
					$image->save($large_one);

					$image = $image_tmp;
					
					if( $type == "music" ){
						$image->resize( 100, 100, true );
					}else if( $type == "video" ){
						$image->resize( 117.5, 80, true );
					}
					$image->save($small_one);
					
					$image = null;
					$image_tmp = null;
				}
				
				$array = array(
					"large" => $blog_url . $large_one,
					"small" => $blog_url . $small_one
				);	
				echo json_encode($array);
				
				die();
			}
			
			// 优酷封面
			if( $_POST['action'] == 'lp_ajax_youku' ){
				$youkuid = $_POST['youkuid'] ? $_POST['youkuid'] : null;
				
				if( !$youkuid ){
					lp_ajax_error("Not a valid youku video url.");
				}
				
				$link = "http://v.youku.com/player/getPlayList/VideoIDS/{$youkuid}/timezone/+08/version/5/source/out?password=&ran=2513&n=3";

				$array = array();
				
				$ch=@curl_init($link);
				@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$cexecute=@curl_exec($ch);
				@curl_close($ch);
				if ($cexecute) {
					$result = json_decode($cexecute,true);
					$json = $result['data'][0];
					$hour = floor( $json["seconds"]/3600 );
					$hour = $hour > 0 ? "{$hour}:" : "";
					$ltime = $hour . gmstrftime('%M:%S', $json["seconds"]);
					
					$array = array(
						"image" => $json['logo'],
						"title" => $json['title'],
						"ltime" => $ltime
					);	
				}
				
				echo json_encode($array);
				die();
			}
			
			// 收藏文章
			if( $_POST['action'] == 'lp_ajax_liked' ){
				global $visage, $user_ID;
				$post_id = intval($_POST['postid']);

				if( !$post_id ) lp_ajax_error("错误的文章id!");
				
				$liked = $visage->is_liked($user_ID, $post_id);
				
				if( $liked ){
					$visage->delete_like($user_ID, $post_id);
					$class = "unliked";
				}else{
					$post_7 = get_post($post_id); 
					$author_id = $post_7->post_author;
					$time = time() + (60*60*get_settings("gmt_offset"));
					$visage->add_visage($time, $user_ID, $author_id, $post_id, "liked");
					$class = "liking";
					$tips = "<span class='like-span like-tip'>取消收藏</span>";
				}
				
				$count = $visage->post_liked_count($post_id);

				echo "<a href='javascript:lp_liked({$post_id});' class='{$class}'><span class='like-span icon icon-heart'></span><span class='like-span like-text'>{$count} 人收藏</span>{$tips}</a>";
				
				die();
			}

			// 关注
			if( $_POST['action'] == 'lp_ajax_followed' ){
				global $visage, $user_ID;
				$author = intval($_POST['authorid']);
				
				$aux = get_userdata( $author );
				
				if( $aux === false ) lp_ajax_error("该文章作者不存在!");
				
				if( $user_ID == $author) lp_ajax_error("无法关注自己!");
				
				$followed = $visage->is_following($user_ID, $author);
				
				if( $followed ){
					$visage->delete_follow($user_ID, $author);
					$button = "<div class='follow-button unfollow'><a href='javascript:lp_followed({$author});' id='follow-button-{$author}' ><span class='follow-icon'></span>关注</a></div>";	
				}else{
					$time = time() + (60*60*get_settings("gmt_offset"));
					$visage->add_visage($time, $user_ID, $author, null, "follow");
					
					$following = $visage->is_following($author, $user_ID);
					$text = $following ? "相互关注" : "已关注";					
					$button = "<div class='follow-button followed'><span class='split'>{$text} |</span><a href='javascript:lp_followed({$author});' id='follow-button-{$author}'>取消</a></div>";			
				}
				
				$count = $visage->followed_count($author);
			
				echo $button;

				die();
			}

			// 关注
			if( $_POST['action'] == 'lp_ajax_checked' ){
				$user_id = $_POST["user"];
				if( !$user_id) lp_ajax_error("用户不存在!");
				$time = time() + (60*60*get_settings("gmt_offset"));
				update_user_meta($user_id, "check_time", $time);
				die();
			}		
			
		// 请求方法为 get		
		}else if( 'GET' == $_SERVER['REQUEST_METHOD'] ){
			if( $_GET["action"] == "lp_ajax_profile"){
				$user_id = $_GET["user_id"];
				if($user_id){
					$user = get_userdata($user_id);
				?>
					
						<div class="widget-author">
							<?php echo get_avatar( $user_id , 65 ); ?>
							<div class="widget-author-content">
								<p><strong><?php echo $user->display_name; ?></strong></p>
								<ul class="commodity-info">
									<li><span class="title">关注</span><span class="count"><?php echo lp_following_count($user_id);?></span></li>
									<li><span class="title">粉丝</span><span class="count"><?php echo lp_followed_count($user_id);?></span></li>
									<li class="last"><a href="<?php echo lp_page_link( "user" );?>/posts"><span class="title">文章</span><span class="count"><?php echo count_user_posts($user_id); ?></span></a></li>
								</ul>
							</div>
						</div>
						<div class="widget-desc">
							<?php echo $user->user_description; ?>
						</div>
						<div class="widget-follow">
							<?php lp_follow_button($user_id);?>
						</div>				
				<?php }
				die();
			}
		}else{return;}
	}
	
	// 增加: 錯誤提示功能
	function lp_ajax_error($a) {
		header('HTTP/1.0 500 Internal Server Error');
		header('Content-Type: text/plain;charset=UTF-8');
		echo $a;
		exit;
	}
	
	function lp_resize($tempFile, $tempFilesize, $file_path, $file_name, $file_ext, $size){
		
		$size_array = array(
			"2" => array(680, null),
			"1" => array(235, 160),
			"0" => array(117, 80)
		);
		
		$size_array = $size_array[$size];
		
		$new_name = $file_name.".".$size.".".$file_ext;
		$newfile = $file_path . $new_name;
		
		
		$new_width = $size_array[0];
		$new_height = $size_array[1];
		
		$cut_width = 0;
		$cut_height = 0;	
		
		if($tempFilesize[2]==2) $im = imagecreatefromjpeg($tempFile);
		if($tempFilesize[2]==1) $im = imagecreatefromgif($tempFile);
		if($tempFilesize[2]==3) $im = imagecreatefrompng($tempFile);
		
		if($new_height==null){
			if( $tempFilesize[0] < $new_width ) return $file_name.".".$file_ext;
			
			$new_height = ($tempFilesize[1]/$tempFilesize[0]) * $new_width;
			
			$newimg = imagecreatetruecolor($new_width,$new_height);
			imagecopyresampled($newimg, $im, 0, 0, 0, 0, $new_width, $new_height, $tempFilesize[0], $tempFilesize[1]);			
			
		}else{
			if($tempFilesize[0]/$tempFilesize[1] > $new_width/$new_height){
				$tmp_width = intval( ($tempFilesize[0]/$tempFilesize[1]) * $new_height);
				$tmp_height = $new_height;
				$cut_width = ($new_width - $tmp_width)/2;
			}else{
				$tmp_height = intval( ($tempFilesize[1]/$tempFilesize[0]) * $new_width);
				$tmp_width = $new_width;	
				$cut_height = ($new_height - $tmp_height)/2;
			
			}
			$newimg = imagecreatetruecolor($new_width,$new_height);
			imagecopyresampled($newimg, $im, $cut_width, $cut_height, 0, 0, $tmp_width, $tmp_height, $tempFilesize[0], $tempFilesize[1]);			
		}

		ImageJpeg ($newimg, $newfile, 100);
		ImageDestroy ($im);
		return $new_name;
	}
	
	
	function lp_rand_string(){
		return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6).''.mktime(date("Y-m-d H:i:s"));
	}
	
	
	//wp_ajax_lp_ajax_upload
	
	add_action( 'wp_ajax_lp_ajax_upload', 'lp_upload' );
	function lp_upload() {

		if (!empty($_FILES) && wp_verify_nonce($_POST['_ajax_nonce'], "lp-ajax-upload")) {
			$temp_file = $_FILES['file']['tmp_name'];
			
			$tempFilesize = getimagesize( $temp_file );
			
			if(!$tempFilesize || empty($tempFilesize)){
				die('{"jsonrpc" : "2.0", "error" : "错误的文件类型", "id" : "id"}');
			}
			
			$content_url = content_url("uploads/image/");
			
			$target_folder = "../wp-content/uploads/image/"; // Relative to the root


			$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
			$fileParts = pathinfo($_FILES['file']['name']);
			$file_ext = strtolower($fileParts['extension']);
			
			if (in_array($file_ext,$fileTypes)) {
				$file_name = lp_rand_string();
				$target_file = $target_folder . $file_name . "." . $file_ext;	

			
				move_uploaded_file($temp_file, $target_file);

				$original = $content_url. $file_name . "." . $file_ext;
				
				$normal = $content_url. lp_resize($target_file, $tempFilesize, $target_folder, $file_name, $file_ext, 2);
				
				$small = $content_url. lp_resize($target_file, $tempFilesize, $target_folder, $file_name, $file_ext, 0);

				$large = $content_url. lp_resize($target_file, $tempFilesize, $target_folder, $file_name, $file_ext, 1);		

				die('{"jsonrpc" : "2.0", "image" : {"original": "'.$original.'", "normal":"'.$normal.'", "large": "'.$large.'", "small": "'.$small.'"}, "id" : "id"}');
			} else {
				die('{"jsonrpc" : "2.0", "error" : "错误的文件类型", "id" : "id"}');
			}
		}

		die('{"jsonrpc" : "2.0", "error" : "文件不存在", "id" : "id"}');
	}