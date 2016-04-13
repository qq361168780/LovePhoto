jQuery(document).ready(function($){

	$("body").append('<div id="lp_view_container" style="display:none"><div id="lp_view_header">'+$(".single-title").first().text()+'</div><div id="lp_view_body"></div><div id="lp_view_footer"><div class="lvf_head"><span class="af_nav_num"><span class="lvf_status_current"></span><span class="lvf_status_slash">/</span><span class="lvf_status_total">'+$(".single-content a:has('img')").length+'</span></span><span class="af_nav_divider">|</span><span class="af_nav_switch hide">隐藏缩略图</span></div><div class="carousel_wrapper"><div class="carousel_arrow_prev"></div><div class="carousel_ul_wrapper"></div><div class="carousel_arrow_next"></div></div></div></div></div>');


$(".single-content a:has('img')").click(function(e){
	e.preventDefault();
	
	var n = $(".single-content a:has('img')").index($(this));
	
	$('.lvf_status_current').first().text(n+1);
	
	
	
	$("body").addClass("lp_view");
	$("#lp_view_container").show();
	
	var html ="";
	$(".single-content a:has('img')").each(function(){
		//html+=;
	});
});








	var smile = function(e) {
			var a;
			e = " " + e + " ";
			if (document.getElementById("comment") && document.getElementById("comment").type == "textarea") {
				a = document.getElementById("comment")
			} else {
				return false
			}
			if (document.selection) {
				a.focus();
				sel = document.selection.createRange();
				sel.text = e;
				a.focus()
			} else {
				if (a.selectionStart || a.selectionStart == "0") {
					var c = a.selectionStart;
					var d = a.selectionEnd;
					var b = d;
					a.value = a.value.substring(0, c) + e + a.value.substring(d, a.value.length);
					b += e.length;
					a.focus();
					a.selectionStart = b;
					a.selectionEnd = b
				} else {
					a.value += e;
					a.focus()
				}
			}
			$(".smilies-box").hide();
		};
		
		
	$(".single-tab .nav-lia").click(function(e){
		e.preventDefault();
		if( !$(this).parent().hasClass("current") ){
			var n = $(".nav-lia").index($(this));
			$(".nav-lia").parent().removeClass("current");
			$(this).parent().addClass("current");
			$(".stc-li").removeClass("current");
			$(".stc-li").eq(n).addClass("current");
		}
		return false;
	});
	
	$(".smilies").first().click(function(e){
		e.preventDefault();
		$(".smilies-box").first().show();
		return false;		
	});
	
	$(".smilies-box a").click(function(e){
		e.preventDefault();
		if ( $(".smilies-box").first().is(':visible')){
			smile($(this).attr("data-smile"));
			$(".smilies-box").hide();
		}else{
			$(".smilies-box").first().show();
			smile($(this).attr("data-smile"));
		}
		return false;		
	});
});