jQuery(document).ready(function($) {
	var xiami = $('<div id="xiami-box"></div>').appendTo("body"),
		xiami_title = "",
		xiami_content = "",
	
		youku = $('<div id="youku-box"></div>').appendTo("body"),
		youku_title = "",
		youku_content = "";
		
	xiami.html('<div id="xiami-wrapper">\
		<div id="xiami-song" class="clearfix">\
			<div id="xms-id">\
				<a id="xms-empty" href="#"></a>\
				<label id="xms-label" for="xms-input">虾米音乐地址</label>\
				<input id="xms-input" type="text" />\
			</div>\
			<div id="xms-preview" class="button-primary">预览</div>\
			<div id="xms-insert" class="button-primary">插入文章</div>\
		</div>\
		<div id="xiami-footer">\
			<div id="xiami-preview">\
			</div>\
			<div id="xiami-tip">[注意] 输入虾米音乐地址：<strong>http://www.xiami.com/song/1769930336</strong></div>\
		</div>\
		<div id="xiami-arrow"></div>\
	</div>');
	
	youku.html('<div id="youku-wrapper">\
		<div id="youku-song" class="clearfix">\
			<div id="yks-id">\
				<a id="yks-empty" href="#"></a>\
				<label id="yks-label" for="yks-input">优酷视频地址</label>\
				<input id="yks-input" type="text" />\
			</div>\
			<div id="yks-preview" class="button-primary">预览</div>\
			<div id="yks-insert" class="button-primary">插入文章</div>\
		</div>\
		<div id="youku-footer">\
			<div id="youku-preview">\
			</div>\
			<div id="youku-tip">[注意] 输入优酷视频地址：<strong>http://v.youku.com/v_show/id_XMjk1MTYyNDIw.html</strong></div>\
		</div>\
		<div id="youku-arrow"></div>\
	</div>');	

	$(document).click(function(e) {
		if ( xiami.is(":visible") && e.target) {
			xiami.hide()
		}
		
		if ( youku.is(":visible") && e.target) {
			youku.hide()
		}		
	});
	
	$("#xiami-box").click(function(e) {
		if (xiami.is(":visible")) {
			e.stopPropagation()
		}
	});
	
	$("#youku-box").click(function(e) {
		if (youku.is(":visible")) {
			e.stopPropagation()
		}
	});	

	$("#mfthemes-xiami").click(function(e) {
		e.preventDefault();
		var A = $(this).offset().left,
			C = $(this).offset().top + $(this).height() + 15;
		xiami.css({
			left: A,
			top: C
		}).show();
		if (youku.is(":visible")) youku.hide();
		return false
	});	
	
	$("#mfthemes-youku").click(function(e) {
		e.preventDefault();
		var A = $(this).offset().left,
			C = $(this).offset().top + $(this).height() + 15;
		youku.css({
			left: A,
			top: C
		}).show();
		if (xiami.is(":visible")) xiami.hide();
		return false
	});
	

	$("#xms-input").on("focus keyup input paste blur", function() {
		if ($(this).val() != "") {
			$("#xms-label").hide();
			$("#xms-empty").show()
		} else {
			$("#xms-empty").hide();
			$("#xms-label").show()
		}
	});
	
	$("#xms-empty").on("click", function(e) {
		e.preventDefault();
		$("#xms-input").val("");
		$(this).hide();
		$("#xms-label").show();
		$("#xiami-preview").slideUp();
		$("#xms-preview").text("预览").show();
		$("#xms-insert").hide();
		return false
	});

	$("#yks-empty").on("click", function(e) {
		e.preventDefault();
		$("#yks-input").val("");
		$(this).hide();
		$("#yks-label").show();
		$("#youku-preview").empty().slideUp();
		$("#yks-preview").text("预览").show();
		$("#yks-insert").hide();
		return false
	});
	
	$("#yks-input").on("focus keyup input paste blur", function() {
		if ($(this).val() != "") {
			$("#yks-label").hide();
			$("#yks-empty").show()
		} else {
			$("#yks-empty").hide();
			$("#yks-label").show()
		}
	});
	
	$("#xms-preview").on("click", function() {
		var val = $("#xms-input").val(),
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
						url: "http://127.0.0.1/lovephoto/",
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
							xiami_title = json.data[0].title;
							xiami_content = '[xiami author="'+json.data[0].author+'" cover="'+data.small+'" original="'+data.large+'"]'+val[1]+'[/xiami]';
							$("#xms-preview").hide();
							$("#xms-insert").show();

							$("#xiami-preview").empty().html('<embed src="http://www.xiami.com/widget/1_' + val[1] + '/singlePlayer.swf" type="application/x-shockwave-flash" width="257" height="33" wmode="transparent"></embed>');
							$("#xiami-preview").slideDown();							
						}
					});
				}
			});
		
			$(this).text("加载中");
		}
	});
	
	$("#yks-preview").on("click", function() {
		var val = $("#yks-input").val(),
			regx = /http:\/\/v.youku.com\/v_show\/id_(\w+).html.*?/;
		if (! regx.test(val) || !val) {
			alert("错误：请输入优酷视频地址！")
		} else {
			val = val.match(regx);
			$.ajax({
				url: "http://127.0.0.1/lovephoto/",
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
						url: "http://127.0.0.1/lovephoto/",
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
							youku_title = json.title;
							youku_content = '[youku ltime="'+json.ltime+'" cover="'+data.small+'" original="'+data.large+'"]'+val[1]+'[/youku]';
							$("#yks-preview").hide();
							$("#yks-insert").show();
							$("#youku-preview").empty().html('<embed src="http://player.youku.com/player.php/Type/Folder/Fid/20074750/Ob/1/sid/'+val[1]+'/v.swf" quality="high" width="480" height="400" align="middle" allowScriptAccess="always" allowFullScreen="true" mode="transparent" type="application/x-shockwave-flash"></embed>').slideDown();
						}
					});
				}
			});

			$(this).text("加载中");
		}
	});	
	
	
	$("#xms-insert").on("click", function() {
		$("#title").val(xiami_title).focus();
		send_to_editor(xiami_content);
		xiami.hide()
	});
	
	$("#yks-insert").on("click", function() {
		$("#title").val(youku_title).focus();
		send_to_editor(youku_content);
		youku.hide()
	});	
	
	/*
	var H;
	$("#xmp-rating").on({
		mouseover: function() {
			$("#xmp-rating a").on({
				mouseover: function() {
					var A = $("#xmp-rating a").index($(this));
					$("#xmp-rating a").removeClass("selected");
					$("#xmp-rating a:lt(" + (A + 1) + ")").addClass("selected");
					$("#xmp-rating a:gt(" + A + ")").removeClass("selected")
				},
				click: function(B) {
					B.preventDefault();
					var A = $("#xmp-rating a").index($(this)),
						C = $("#xms-input").val(),
						D = new Date().valueOf();
					$("#xmp-rating a").removeClass("selected");
					$("#xmp-rating a:lt(" + (A + 1) + ")").addClass("selected");
					$("#xmp-rating a:gt(" + A + ")").removeClass("selected");
					H = A + 1;
					J = '<embed class="audio-player" src="http://www.xiami.com/widget/1_' + C + '/singlePlayer.swf" type="application/x-shockwave-flash" width="257" height="33" songid="' + C + '" rating="' + H + '" wmode="transparent"></embed>'
				}
			})
		},
		mouseout: function() {
			var A = H;
			$("#xmp-rating a").removeClass("selected");
			$("#xmp-rating a:lt(" + A + ")").addClass("selected");
			$("#xmp-rating a:gt(" + A + ")").removeClass("selected")
		}
	})*/
});