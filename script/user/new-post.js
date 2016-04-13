jQuery(document).ready(function($){

var ue = UM.getEditor('textarea');





$(".form-select").hover(function(){
	$(this).children(".form-dropdown").show();
}, function() {
	$(this).children(".form-dropdown").hide();
});


$(".form-option").click(function(){
	var _ = $(this),
		_select = _.parent().parent(),
		_text = _select.children(".form-text"),
		_input = _select.parent().children(".text"),
		
		value = _.attr("data-value"),
		text = _.text();
		
	_text.text(text);
	
	_input.val(value);
	
	_.parent().hide();
		
});

$("#enter-tag").focus(function(){
	$("#tags-select").show();
	
	
	
		var A = $(this).val();
		$(this).keydown(function(e) {
			var J = e.which;
			m = $(this).val();
			if (J == 13 || J == 188) {
				e.preventDefault();
				var t = $.trim(m);
				if (t != "") {
					$('<a class="tag-item">'+t+'<span class="icon icon-cross"></span>').appendTo($("#tags-container"));
					$(this).val("");
					G()
				}
			} else {
				if (J == 46 || J == 8) {
					if (m == "") {
						var I = $("#tags-container a").length;
						if (I > 0) {
							$("#tags-container a:last").remove();
							G()
						}
					}
				}
			}
		});
		$(this).keyup(function() {
			var t = $(this).val();
			if (t.indexOf("，") != -1) {
				var B = /，/g;
				t = $.trim(e.replace(B, ""));
				$(this).val(e);
				if (e != "") {
					$('<a class="tag-item">'+t+'<span class="icon icon-cross"></span>').appendTo($("#tags-container"));
					$(this).val("");
					G()
				}
			}
		})	
});

	$(document).click(function(e) {
		if ( e.target && e.target != $("#enter-tag")[0] ){
			$("#tags-select").hide();
		}
	});
	
	$("#tags-select").click(function(e) {
		e.stopPropagation();
		return false;
	});
	
	
	
var G = function() {
			var B = "";
	$("#tags-container a.tag-item").each(function() {
				B += $(this).text() + ","
			});
	$("#tag").val(B);
			var A = $("#tag").val(),
				e = $.trim(A).replace(/\s{2,}/ig, "").split(",");
				
	$("#common-tags a, #hot-tags a").each(function() {
				var H = $.inArray($(this).text(), e);
				if (H > -1) {
					$(this).hide();
				} else {
					$(this).show();
				}
			});
			
				$("#tags-select").css({
		top: $("#tags").height()
	});
		};	
	
	
$("#common-tags a, #hot-tags a").on("click", function(e) {
	e.preventDefault();
	var _ = $(this),
		t = _.text();

	//console.log($("#tags-container").html());	
		
	$("#tags-container").append('<a class="tag-item">'+t+'<span class="icon icon-cross"></span></a>');
	G();
	

	return false
});	
	
	
	$("#tags-container").on("click", ".icon-cross", function(e) {
		e.preventDefault();
		$(this).parent().remove();
G();
		return false
	});	
	
	
if( typeof(plupload) != "undefined"){

	
    var pconfig = {
        runtimes           : 'html5,flash,html4',
        browse_button      : "upload-button",
        container          : "upload-info",
        drop_element       : "image-preview",
        file_data_name     : "file",
        multiple_queues    : true,
        max_file_size      : "1MB",
        url                : lp.admin_ajax,
		
        flash_swf_url      : lp.plupload_flash,

        filters            : [ {title : "Image files", extensions : "jpg,gif,png"} ],
        multipart          : true,
        urlstream_upload   : true,
        multi_selection    : true,
        multipart_params   : 
        {
            _ajax_nonce : $("#upload-nonce").val(),
            action      : "lp_ajax_upload"
        }
    };



uploader = new plupload.Uploader( pconfig );

	uploader.bind( 'Init', function( up ){ });

    uploader.init();

    // a file was added in the queue
    uploader.bind( 'FilesAdded', function( up, files )
    {

        $.each( files, function( i, file ) 
        {
			if( file.size < 1000000){
				$("#upload-target").append(
				'<div class="upload-file" id="' + file.id + '"><div class="upload-file-name">' + file.name + '</div><div class="upload-progress"><div class="upload-progress-bar"></div></div><div href="#" class="upload-remove">X</div></div>');
				
				
				$('#' + file.id + ' div.upload-remove').first().click(function(e) {
				  e.preventDefault();
				  up.removeFile(file);
				  $('#' + file.id).remove();
				});				
				
			}else{
				alert("图片大小不能超过1MB")
			}
        });

        up.refresh();
        up.start();
    });

    uploader.bind( 'UploadProgress', function( up, file ) 
    {
        $( '#' + file.id + " .upload-progress-bar" ).width( file.percent + "%" );
       
	   $("#upload-total-progress-bar").css('width', uploader.total.percent + '%');
	   $("#upload-total-percent").text(uploader.total.uploaded+'/'+uploader.files.length);
    });

	
	uploader.bind( 'BeforeUpload', function( up, file ) 
    {
		$("#upload-target, #image-footer, .add-image").show();
		$("#upload-info").addClass("upload-begin").css("position", "absolute").hide().children("#upload-button").text("继续上传");

    });	
	
    // a file was uploaded
    uploader.bind( 'FileUploaded', function( up, file, data ) 
    {
		data = jQuery.parseJSON(data.response);

		if(data.error == undefined){
			$( '#' + file.id ).addClass("fileuploaded").html('<a original="'+data.image.original+'" normal="'+data.image.normal+'"><img src="'+data.image.large+'" width="160" height="108" /></a><div href="#" class="upload-remove">X</div>');
			
			var html = "";
			
			$(".fileuploaded a").each(function(){
				html+= '<a href="'+$(this).attr("original")+'"><img src="'+$(this).attr("normal")+'" /></a>';
			});
			
			$("#lovephoto-addValue").val(html);			

			$('#' + file.id + '.fileuploaded div.upload-remove').first().click(function(e) {
				  e.preventDefault();
					var html = "";
					
					$(".fileuploaded a").each(function(){
						html+= '<a href="'+$(this).attr("original")+'"><img src="'+$(this).attr("normal")+'" /></a>';
					});
					
					$("#lovephoto-addValue").val(html);	
				  $('#' + file.id + '.fileuploaded').remove();
			});	

			
		}else{
			$( '#' + file.id + " .upload-progress").remove();
			$( '#' + file.id ).addClass("upload-fail").append('<p>'+data.error+'</p>');
		}
    });
	
	uploader.bind( 'UploadComplete', function( up, file) 
    {
		$("#upload-info").show();
    });

	
	 uploader.bind( 'Error', function(up, err) {
		$( '#' + up.id + " .upload-progress").remove();
		$( '#' + up.id ).addClass("upload-fail").append('<p>'+err.message+'</p>');
	});	

	
}
	
	
	$("#video-preview #upload-button").click(function(){
		var val = $("#video-input").val(),
			regx = /http:\/\/v.youku.com\/v_show\/id_(\w+).html.*?/;
		if (! regx.test(val) || !val) {
			alert("错误：请输入优酷视频地址！")
		} else {
			val = val.match(regx);
			$.ajax({
				url: lp.ajaxurl,
				type: "POST",
				dataType: 'json',
				data:{
					"action": "lp_ajax_youku",
					"youkuid": val[1]
				},
				error: function(request){
					alert("Error：" + request.responseText);
					return false;
				},
				success: function(json) {
					if(!json){
						alert("错误：无法从优酷网获取相关信息！");
						return false;
					}
					
					$.ajax({
						url: lp.ajaxurl,
						type: "POST",
						dataType: 'json',
						data:{
							"action": "lp_ajax_cover",
							"type": "video",
							"src": json.image,
							"sid": val[1]
						},
						error: function(request){
							alert("Error：" + request.responseText);
							return false;
						},
						success: function(data) {
							var content = '[youku ltime="'+json.ltime+'" cover="'+data.small+'" original="'+data.large+'"]'+val[1]+'[/youku]';
		
							$("#video-preview > .form-item").hide();

							$("#youku-box").empty().html('<embed src="http://player.youku.com/player.php/Type/Folder/Fid/20074750/Ob/1/sid/'+val[1]+'/v.swf" quality="high" width="640" height="400" align="middle" allowScriptAccess="always" allowFullScreen="true" mode="transparent" type="application/x-shockwave-flash"></embed><div class="video-close">X</div>').slideDown();
							
							
							$(".add-video").show();
							
							$("#title").val(json.title);
							
							$("#lovephoto-addValue").val(content);
						}
					});
				}
			});

			$(this).text("加载中");
		}		
	});
	
	$("#video-preview").on("click", ".video-close", function(e){
		$("#video-preview > .form-item").show();
		$("#youku-box").empty();
		$(".add-video").hide();
		$("#title, #video-input, #lovephoto-addValue").val("");
		$("#video-preview #upload-button").text("预览视频")
	});
	
	
	
	
	
	
	
	$("#music-preview #upload-button").click(function(){
		var val = $("#music-input").val(),
			regx = /http:\/\/www.xiami.com\/song\/(\d+).*?/;
		if (! regx.test(val) || !val) {
			alert("错误：请输入虾米音乐地址！")
		} else {
			val = val.match(regx);
			$.ajax({
				url: "http://www.xiami.com/web/get-songs",
				data: {
					"type": 0,
					"rtype": "song",
					"id": val[1]					
				},
				dataType: 'jsonp',
				error: function(request){
					alert("Error：" + request.responseText);
					return false;
				},
				success: function(json) {
					if(!json.data.length){
						alert("错误：无法从虾米网获取相关信息！");
						return false;
					}
					
					$.ajax({
						url: lp.ajaxurl,
						type: "POST",
						dataType: 'json',
						data:{
							"action": "lp_ajax_cover",
							"type": "music",
							"src": json.data[0].cover,
							"sid": val[1]
						},
						error: function(request){
							alert("Error：" + request.responseText);
							return false;
						},
						success: function(data) {
							var content = '[xiami author="'+json.data[0].author+'" cover="'+data.small+'" original="'+data.large+'"]'+val[1]+'[/xiami]';
		
							$("#music-preview > .form-item").hide();

							$("#music-box").empty().html('<embed src="http://www.xiami.com/widget/0_' + val[1] + '/singlePlayer.swf" type="application/x-shockwave-flash" width="257" height="33" wmode="transparent"></embed><div class="music-close">X</div>').slideDown();
							
							
							$(".add-music").show();
							
							$("#title").val(json.data[0].title);
							
							$("#lovephoto-addValue").val(content);
						}
					});
				}
			});

			$(this).text("加载中");
		}		
	});
	
	$("#music-preview").on("click", ".music-close", function(e){
		$("#music-preview > .form-item").show();
		$("#music-box").empty();
		$(".add-music").hide();
		$("#title, #music-input, #lovephoto-addValue").val("");
		$("#music-preview #upload-button").text("预览音乐")
	});	
	
	$("#post-new-form form").submit(function(){
		$("#form-tips").empty();
		
		if( $("#title").val() == "" ){
			$("#form-tips").text("请填写文章 标题.");
		}else if( $("#category").val() == "" ){
			$("#form-tips").text("选择文章 分类.");
		}else{
			$(this).submit()
		}
		return false;
	});
	
});