function close_popup() {
	$(".popup input[type='text']").val("");
	$(".popup textarea").val("");
	$(".popup").hide();
}
function show_popup(popup) {
	//var pos_x = event.pageX + 120;
	//var pos_x = window.screen.width/2 - 250;		
	//var pos_x = parseInt($('#mother').css('width'))/2 - 250;
	close_popup();
	var popup_width = parseInt(popup.css('width'));
	var pos_x = $(window).width()/2 - (popup_width/2);			
	var pos_y = $(window).height()/4;
	close_popup();
	popup.css({top: pos_y, left: pos_x}).show();	
	var first_input = popup.find('input:first'); 
	if (first_input.length) {
		first_input.focus();
	}	
}
function clear_form_value(frm) {
	frm.find("input[type='text']").val("");
	frm.find("textarea").val("");	
}
/*
 * for thing like counter increase
 */
function inc_number(s) {
	return parseInt(s) + 1;
}
var email_regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
function valid_email(email) {
	return email_regex.test(email);
}
function days_in_month(m, y)
{
	// month 0-based
	return 32 - new Date(y, m, 32).getDate();
}
$(document).ready(function() {
	$('html').keypress(function(e) {
		if (e.which == 47) {
			if (!e.target.name) {
				$('#q').focus();  
				return false;
			}
		} else if (e.keyCode == 27) {
			close_popup(); 
		}		
	});
	// hover items
	$(".hv").hover(function () {
		$(this).addClass("highlight");
	},
	function() {
		$(this).removeClass("highlight");
	});
});

// for search suggest, not using yet
function suggest_term(event) {
	$('#terms').show();
	if (event.keyCode == 27) {
		// escape key
		$('#terms').hide();
	}	
}

function dismiss_term() {
	$('#terms').hide();
}
function change_no_day() {
	var m = parseInt($("#b_month").val());
	var y = parseInt($("#b_year").val());
	var no_days = 0;
	if (m && y) {
		no_days = days_in_month(m - 1, y);
	} else {
		no_days = 31;
	}		
	var last_day = parseInt($("#b_day > option:last").attr("value"));
	if (no_days > last_day) {
		// append element
		for (var i=last_day+1; i<=no_days; i++) {
			$("#b_day").append('<option value="'+i+'">'+i+'</option>');
		}
	} else if (no_days < last_day) {
		// remove element
		for (var i=0; i<(last_day-no_days);i++) {
			$("#b_day > option:last").remove();
		}			
	}	
}

$(document).ready(function() {
	//var term_field = $('#q');
	//$('#terms').css('width', term_field.css('width'));	 
	//term_field.attr('autocomplete', 'off').keyup(suggest_term);
	//term_field.blur(dismiss_term);	
});


