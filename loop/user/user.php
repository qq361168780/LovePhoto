<?php global $user_ID; $type = get_query_var("user_item");?>
<div id="post-new" class="clearfix">

	<div class="user-tab">
		<ul class="nav clearfix">
			<li class="nav-li <?php echo ($type == "" || $type == "add-text") ? "current": "";?>"><a class="nav-lia" href="<?php echo lp_page_link( "user" );?>/add-text"><span class="icon icon-pen"></span>发布文字</a></li>
			<li class="nav-li <?php echo ($type == "add-image") ? "current": "";?>"><a class="nav-lia" href="<?php echo lp_page_link( "user" );?>/add-image"><span class="icon icon-images"></span>发布图片</a></li>
			<li class="nav-li <?php echo ($type == "add-music") ? "current": "";?>"><a class="nav-lia" href="<?php echo lp_page_link( "user" );?>/add-music"><span class="icon icon-music"></span>发布音乐</a></li>
			<li class="nav-li <?php echo ($type == "add-video") ? "current": "";?>"><a class="nav-lia" href="<?php echo lp_page_link( "user" );?>/add-video"><span class="icon icon-play"></span>发布视频</a></li>		
		</ul>
	</div>
	<div id="post-new-form">
		<form action="" method="post" class="clearfix">
			<?php
				if( $type == "add-image" ){?>
					<div id="image-preview">
						<div id="upload-target"></div>
						<div id="upload-info"><div id="upload-button">选择您计算机上的照片</div></div>
					</div>
					<div id="image-footer">
						<span id="upload-total-text">上传进度 </span>
						<span id="upload-total-percent"></span>
						<span id="upload-total-progress">
							<span id="upload-total-progress-bar"></span>
						</span>
						<input id="upload-nonce" type="hidden" value="<?php echo wp_create_nonce( "lp-ajax-upload" ); ?>"/>
						<input type="hidden" id="lovephoto-addValue" name="lpAddValue" value=""/>
						<input type="hidden" name="format" value="image" />
					</div>
				<?php }else if( $type == "add-music" ){?>
					<div id="music-preview">
						<div class="form-item clearfix">
							<label class="form-label" for="music-input">音乐地址</label><input type="text" class="text" id="music-input" />
						</div>
						<div class="form-item clearfix">
							<div id="upload-button" class="left">预览音乐</div>
							<p>* 输入虾米音乐地址，点击<strong>预览音乐</strong>（例：http://www.xiami.com/song/1769930336）</p>
						</div>
						<div id="music-box"></div>
						<input type="hidden" id="lovephoto-addValue" name="lpAddValue" value=""/>
						<input type="hidden" name="format" value="audio" />
					</div>
				<?php }else if( $type == "add-video" ){?>
					<div id="video-preview">
						<div class="form-item clearfix">
							<label class="form-label" for="video-input">视频地址</label><input type="text" class="text" id="video-input" />
						</div>
						<div class="form-item clearfix">
							<div id="upload-button" class="left">预览视频</div>
							<p>输入优酷视频地址，点击<strong>预览视频</strong>（例：http://v.youku.com/v_show/id_XNTQ2NTYwNjYw.html）</p>
						</div>
						<div id="youku-box"></div>
						<input type="hidden" id="lovephoto-addValue" name="lpAddValue" value=""/>
						<input type="hidden" name="format" value="video" />
					</div>				
				<?php }else{?>
					<input id="upload-nonce" type="hidden" value="<?php echo wp_create_nonce( "lp-ajax-upload" ); ?>"/>
				<?php }
			?>
			
			<div class="<?php echo $type ? $type : "add-text";?>">
				<div class="form-item clearfix">
					<label class="form-label" for="title">文章标题</label>
					<input type="text" class="text" name="title" id="title" tabindex="1" />
					<span class="form-tips">（必须）</span>
				</div>
				<div class="form-item clearfix">
					<label class="form-label" for="category">文章分类</label>
					<div class="form-select">
						<div class="form-text"></div>
						<span class="form-sbutton"></span>
						<div class="form-dropdown">
							<?php 		
								$categories=get_categories('hide_empty=0');
								foreach($categories as $category) {
									echo '<div class="form-option" data-value="'.$category->term_id.'">'.$category->cat_name.'</div>';
								}
							?>			
							
						</div>
					</div>
					<span class="form-tips">（在下拉菜单中选取相应的文章分类，必须）</span>
					<input type="hidden" class="text" name="category" id="category" tabindex="2" />
				</div>
				<div class="form-item clearfix">
					<label class="form-label" for="title">文章内容</label>
					<div class="form-right">
						<div id="textarea"></div>		
					</div>
				</div>					

				<div class="form-item clearfix">
					<label class="form-label" for="tag">文章标签</label>
					<div id="tags">
						<div id="tags-container" class="clearfix"></div>
						<input type="text" id="enter-tag" placeholder="添加标签，以逗号或回车隔开" />
						<div id="tags-select">
							<div class="form-item clearfix">
								<label class="form-label" for="common-tags">常用标签：</label>
								<div id="common-tags" class="left clearfix">
									<?php lp_author_tags($user_ID);?>
								</div>
							</div>
							<div class="form-item clearfix">
								<label class="form-label" for="hot-tags">热门标签：</label>
								<div id="hot-tags" class="left clearfix"><?php wp_tag_cloud( array('unit' => 'px', 'smallest' => 12, 'largest' => 12, 'number' => 12, 'format' => 'flat', 'orderby' => 'count', 'order' => 'DESC' )); ?></div>
							</div>
						</div>
					</div>
					<input type="hidden" class="text" name="tag" id="tag" tabindex="4" />
				</div>

				<div class="form-item form-blank">
					<input type="hidden" name="action" value="lp_post_new">
					<input type="hidden" name="auth" value="<?php echo wp_create_nonce( "post-new-nonce" ); ?>"/>
					<input type="submit" id="form-submit" value="发布文章">
					<span id="form-tips"></span>
				</div>
			</div>
		</form>
	</div>
</div>