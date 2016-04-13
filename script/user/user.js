jQuery(document).ready(function($){


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
					$('<a class="tag-item">'+t+'<span class="icon icon-remove"></span>').appendTo($("#tags-container"));
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
					$('<a class="tag-item">'+t+'<span class="icon icon-remove"></span>').appendTo($("#tags-container"));
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
	$("#tags-container a").each(function() {
				B += $(this).text() + ","
			});
	$("#tag").val(B);
			var A = $("#tag").val(),
				e = A.trim().replace(/\s{2,}/ig, "").split(",");
				
	$("#common-tags a, #hot-tags a").each(function() {
				var H = e.indexOf( $(this).text());
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
	
	
$("#common-tags a, #hot-tags a").click(function(e) {
	e.preventDefault();
	var _ = $(this),
		t = _.text();

	$("#tags-container").append('<a class="tag-item">'+t+'<span class="icon icon-remove"></span>');
	G();
	

	return false
});	
	
	
	$("#tags-container").on("click", ".icon-remove", function(e) {
		e.preventDefault();
		$(this).parent().remove();
G();
		return false
	});	
	
	
});