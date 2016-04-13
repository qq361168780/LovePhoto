<?php
/**
 * Theme functions file =W= lp-visage.php 
 *
 *
 * @package LovePhoto
 * @author Mufeng
 */
	global $visage;
	
	class visage
	{
		//construct
		function __construct(){
			global $wpdb;
			$this->db = $wpdb;
			$this->prefix = $this->db->prefix;
		}
		
		public function add_visage($time, $user, $other, $post_id, $type){
			$this->db->insert(
				$this->prefix.'visage', 
				array(
					'created' => $time,
					'user' => $user,
					'other' => $other,
					'postid' => $post_id,
					'type' => $type
				), 
				array(
					'%d',
					'%d', 
					'%d', 
					'%d',
					'%s'
				)
			);			
		}
		
		public function delete_visage($id){
			$this->db->delete( $this->prefix.'visage', array('id' => $id ), array( '%d' ) );
		}
		
		public function get_visage($user, $paged, $limit=20){
			$offset = $limit * ($paged - 1);
			$sql = "SELECT * FROM {$this->prefix}visage WHERE other = '{$user}' AND user != '{$user}' ORDER BY id DESC LIMIT $offset, $limit";
			$result = $this->db->get_results($sql);
			return $result;
		}
		
		public function visage_count($user){
			$created = get_user_meta($user, "check_time", true);
			$created = $created ? $created : 0;
			$sql = "SELECT COUNT(id) as count FROM {$this->prefix}visage WHERE other = '{$user}' AND user != '{$user}' AND created >= $created";
			$result = $this->db->get_results($sql);
			$count = empty($result) ? 0 : $result[0]->count;
			return $count;	
		}
		
		public function visage_page($user){
			$sql = "SELECT COUNT(id) as count FROM {$this->prefix}visage WHERE other = '{$user}' AND user != '{$user}'";
			$result = $this->db->get_results($sql);
			$count = empty($result) ? 0 : $result[0]->count;
			return ceil($count/20);			
		}
		
		public function is_liked($user, $post_id){ // 判断自己是否收藏
			$sql = "SELECT * FROM {$this->prefix}visage WHERE user = '{$user}' AND  postid = '{$post_id}' AND type = 'liked' LIMIT 1";
			$result = $this->db->get_results($sql);
			return count($result);
		}

		public function post_likes($post_id){ // 文章收藏
			$sql = "SELECT * FROM {$this->prefix}visage WHERE postid = '{$post_id}' AND type = 'liked' ORDER BY id DESC";
			$result = $this->db->get_results($sql);
			return $result;			
		}

		public function post_liked_count($post_id){ // 某篇文章收藏数量
			$sql = "SELECT COUNT(id) as count FROM {$this->prefix}visage WHERE postid = '{$post_id}' AND type = 'liked'";
			$result = $this->db->get_results($sql);
			return empty($result) ? 0 : $result[0]->count;
		}

		public function liked_count($user){ // 被收藏总数
			$sql = "SELECT COUNT(id) as count FROM {$this->prefix}visage WHERE other = '{$user}' AND type = 'liked'";
			$result = $this->db->get_results($sql);
			return empty($result) ? 0 : $result[0]->count;	
		}
		
		public function user_liking($user, $paged, $limit = 20){ // 用户收藏的文章
			$offset = $limit * ($paged - 1);
			$sql = "SELECT postid FROM {$this->prefix}visage WHERE user = '{$user}' AND type = 'liked' ORDER BY postid DESC LIMIT $offset, $limit";
			$result = $this->db->get_results($sql);
			return $result;				
		}		
		
		public function user_liking_count($user){ // 收藏的文章分页
			$sql = "SELECT COUNT(postid) as count FROM {$this->prefix}visage WHERE user = '{$user}' AND type = 'liked'";
			$result = $this->db->get_results($sql);
			$count = empty($result) ? 0 : $result[0]->count;
			return $count;		
		}		
		
		public function delete_like($user, $post_id){
			$this->db->delete(
				$this->prefix.'visage', 
				array(
					'user' => $user,
					'postid' => $post_id,
					'type' => 'liked'
				),
				array(
				'%d',
				'%d',
				'%s'
				)
			);		
		}
				
		public function my_followed($user){ // 粉丝
			$sql = "SELECT * FROM {$this->prefix}visage WHERE other = '{$user}' AND type = 'follow'";
			$result = $this->db->get_results($sql);
			foreach($result as $val){
				var_dump($val);
			}
		}

		public function my_following($user, $limit){ // 关注
			$limit = $limit ? "LIMIT $limit" : "";
			$sql = "SELECT other FROM {$this->prefix}visage WHERE user = '{$user}' AND type = 'follow' $limit";
			$result = $this->db->get_results($sql);
			$array = array();
			foreach($result as $val){
				array_push($array, $val->other);
			}
			return $array;
		}		
		
		public function is_following($user, $other){ // 是否关注他
			$sql = "SELECT * FROM {$this->prefix}visage WHERE user = '{$user}' AND  other = '{$other}' AND type = 'follow' LIMIT 1";
			$result = $this->db->get_results($sql);
			return !empty($result);
		}
		
		public function is_followed($other, $user){ // 是否关注我
			$sql = "SELECT * FROM {$this->prefix}visage WHERE user = '{$other}' AND  other = '{$user}' AND type = 'follow' LIMIT 1";
			$result = $this->db->get_results($sql);
			return !empty($result);
		}

		public function following_count($user){ // 关注数量
			$sql = "SELECT COUNT(id) as count FROM {$this->prefix}visage WHERE user = '{$user}' AND type = 'follow'";
			$result = $this->db->get_results($sql);
			return empty($result) ? 0 : $result[0]->count;
		}
		
		public function followed_count($user){ // 粉丝数量
			$sql = "SELECT COUNT(id) as count FROM {$this->prefix}visage WHERE other = '{$user}' AND type = 'follow'";
			$result = $this->db->get_results($sql);
			return empty($result) ? 0 : $result[0]->count;
		}
		
		public function delete_follow($user, $other){ // 取消关注
			$this->db->delete(
				$this->prefix.'visage', 
				array(
					'user' => $user,
					'other' => $other,
					'type' => 'follow'
				),
				array(
				'%d',
				'%d',
				'%s'
				)
			);		
		}		
	}
	
	if ( !isset( $visage ) ) {
		$visage = new visage();
	}

	function lp_post_likes(){
		global $post, $visage;
		$result = $visage->post_likes($post->ID);
		
		if( !empty($result) ){
			echo '<div class="single-liked"><ul class="liked-list">';
			foreach( $result as $val ){
				$user_id = $val->user;
				$user = get_user_by( 'id', $user_id);
				$name = $user->display_name;
			?>
				<li>
					<div class="liked-avatar">
						<?php echo get_avatar( $user_id, 24);?>
					</div>
					<a href="<?php echo get_author_posts_url($user_id); ?>" userid="<?php echo $user_id;?>" class="lp-user-profile url"><?php echo $name;?></a>收藏了这篇文章
					<span class="liked-pubtime"><?php echo date("Y-m-d G:i:s", $val->created);?></span>
				</li>
			<?php }
			echo '</ul></div>';
		}
	}

	function lp_post_liked_count(){ // 某篇文章被收藏数量
		global $post, $visage;
		$result = $visage->post_liked_count($post->ID);
		echo $result;
	}
	
	function lp_liked_count($user){  // 某用户的文章 被收藏总数
		global $visage;
		$result = $visage->liked_count($user);
		return $result;
	}
	
	function lp_liking_count($user){  // 某用户 收藏的文章总数
		global $visage;
		return $visage->user_liking_count($user);
	}
	
	function lp_followed_count($user){ // 粉丝数量
		global $visage;
		$result = $visage->followed_count($user);
		return $result;
	}
	
	function lp_like_button(){ 
		global $post, $visage, $user_ID;
		
		$post_id = $post->ID;
		
		//$count = wp_cache_get($post_id, 'one_post_like_count');

		//if(false === $count){
			$count = $visage->post_liked_count($post_id);
			//wp_cache_set($post_id, $count, 'one_post_like_count', 3600);
		//}
		
		if( $user_ID >0 ){
			//$liked = wp_cache_get($user_ID."-".$post_id, 'one_post_is_liked');

			//if(false === $liked){
				$liked = $visage->is_liked($user_ID, $post_id);
				//wp_cache_set($user_ID."-".$post_id, $liked, 'one_post_is_liked', 3600);
			//}
			
			if( $liked ){
				echo "<div id='like-{$post_id}'><a href='javascript:lp_liked({$post_id});' class='liked'><span class='like-span icon icon-heart'></span><span class='like-span like-text'>{$count} 人收藏</span><span class='like-span like-tip'>取消？</span></a></div>";
			}else{
				echo "<div id='like-{$post_id}'><a href='javascript:lp_liked({$post_id});' class='unliked'><span class='like-span icon icon-heart2'></span><span class='like-span like-text'>{$count} 人收藏</span></a></div>";
			}
			
		}else{
			$login_link = get_bloginfo('url') . "/wp-login.php";
			echo "<a class='notsignined unliked' href='{$login_link}'><span class='like-span icon icon-heart2'></span><span class='like-span like-text'>{$count}人收藏</span></a>";
		}
	}
	
	function lp_following_count($user){
		global $visage;
		return $visage->following_count($user);
	}
	
	function lp_following($user, $limit=0){
		global $visage;
		return $visage->my_following($user, $limit);
	}
	
	function lp_follow_button($author){
		global $visage, $user_ID;
		
		$count = $visage->followed_count($author);
		
		if( $user_ID >0 ){
			$followed = $visage->is_following($user_ID, $author);
			
			if( $followed ){
				$following = $visage->is_following($author, $user_ID);
				$text = $following ? "相互关注" : "已关注";
				$button = "<div class='follow-button followed'><span class='split'>{$text} |</span><a href='javascript:lp_followed({$author});' id='follow-button-{$author}' >取消</a></div>";
			}else{
				$button = "<div class='follow-button unfollow'><a href='javascript:lp_followed({$author});' id='follow-button-{$author}'><span class='follow-icon'></span>关注</a></div>";
			}
			
			echo $author!=$user_ID ? $button : "";
		}else{
			$login_link = get_bloginfo('url') . "/wp-login.php";
			echo "<div class='follow-button unfollow'><a href='{$login_link}' class='notsignined'><span class='follow-icon'></span><span class='follow-tipo'>关注</span></a></div>";
		}		
	}
	
	function lp_check_count(){
		global $visage, $user_ID;
		$count = $visage->visage_count($user_ID);
		return $count;
	}
?>