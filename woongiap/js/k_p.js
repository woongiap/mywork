var map_shown = false;
function toggle_map(location, state) {
	if(!map_shown) {
		load_map(location, state);
	} else {
		remove_map();
	}
	map_shown = !map_shown;
}
function load_map(location, state) {
	var src = "http://maps.google.com.my/?q="+location+"+"+state+"&amp;ie=UTF8&amp;hq=&amp;hnear="+location+",+"+state+"&amp;output=embed";
	var src_url = "http://maps.google.com.my/?f=q&amp;hl=en&amp;geocode=&amp;q="+location+",+"+state+"&amp;hq="+location+"+"+state+"&amp;ie=UTF8&amp;hnear="+location+"+"+state+"&amp;source=embed";	
	var gmap_iframe = '<iframe width="640" height="480" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'+src+'"></iframe>'+
		'<br /><small><a href="'+src_url+'" '+'style="color:#0000FF;text-align:left">View Larger Map</a></small>';
	$("#gmap").append(gmap_iframe).show();
	$("#post-content").hide();
	$("#map-link").text("Hide Map");	
}
function remove_map() {
	$("#gmap").hide().empty();
	$("#post-content").show();
	$("#map-link").text("View Map");
}
function rate_done(data) {
	var response = eval('('+data+')');
	if (response.status == 1) {
		$('#like-link').text(response.msg);
	} else {
		alert(response.msg);
	}
};
function like_post(pid) {
	$.post('af.php', {'ft':'rate', 'pid':pid}, rate_done);
  	return false;	
}
function bookmark_done(data) {
	var response = eval('('+data+')');
	if (response.status == 1) {
		$('#bookmark-link').text(response.msg);
	} else {
		alert(response.msg);
	}	
};
function bookmark_post(pid) {
	$.post('af.php', {'ft':'bookmark', 'pid':pid}, bookmark_done);
  	return false;	
}
$(document).ready(function() {
	$("#posting").prepend('<div id="gmap"></div>');
	
	var comment_posted = function(responseText, statusText) {
		var resp = eval('(' + responseText + ')');
		var comment_msg = $("#comment-box .msg-bar");
		if (resp.status == 1) {
			comment_msg.addClass("info").removeClass("err progress").text("Done.").show().fadeOut(4000);
			$("#comment-list").append(resp.msg);
			var old_src = $("#captcha-img").attr("src");
			$("#captcha-img").attr("src", old_src+'&a');
		} else {
			comment_msg.addClass("err").removeClass("info progress").text(resp.msg).show();
		}		
	};
	var comment_error = function(XMLHttpRequest, textStatus, errorThrown) {
		$("#comment-box .msg-bar").html(textStatus);
	}
	var options = { 
	        success: comment_posted,
	        url: "af.php"
	        //error: comment_error
	    };
	$("#f-comment").ajaxForm(options);	

	/* show ajax working status
	$('#status').ajaxStart(function() {
		  $(this).show();
		}).ajaxStop(function() {
		  $(this).hide();
		});
	*/
	$('#report-link').click(function(event) {
		$("#report-box .msg-bar").removeClass('err info progress').empty();
		show_popup($('#report-box'));
	});
	var reported = function(responseText, statusText) {
		var response = eval('('+responseText+')');
		var report_hint = $("#report-box .msg-bar"); 
		if (response.status == 1) {
			report_hint.removeClass("err progress").addClass('info').text(response.msg).show().fadeOut(4000).queue(function() {
				$('#report-box').hide();
				$(this).dequeue();
			});
		} else {
			report_hint.removeClass("info progress").addClass('err').text(response.msg).show();		
		}
	};
	var ops = { 
	        success: reported,
	        url: "af.php"
	    };
	$('#f-report').ajaxForm(ops);

	$('#share-link').click(function(event) {
		$("#share-box .msg-bar").removeClass("err info progress").empty();
		show_popup($('#share-box'));
	});
	var post_shared = function(responseText, statusText) {
		var response = eval('('+responseText+')');
		var share_hint = $("#share-box .msg-bar"); 
		if (response.status == 1) {
			share_hint.removeClass("err progress").addClass("info").text(response.msg).show().fadeOut(4000).queue(function() {
				$('#share-box').hide();
				$(this).dequeue();
			});
		} else {
			share_hint.removeClass("info progress").addClass("err").text(response.msg).show();
		}
	};
	var opts = { 
	        success: post_shared,
	        url: "af.php"
	    };
	$('#f-share').ajaxForm(opts);
	
});
