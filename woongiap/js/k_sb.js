$(document).ready(function() {
	var show_tips = false;
	$('#learn-more').click(function() {
		if (!show_tips) {
			$('#tips').show();
		} else {
			$('#tips').hide();
		}
		show_tips = !show_tips;
	});	
	var tf = false;
	var sf = false;
	var toggle_tellfriend = function() {
		if (!tf) {
			$('#tf .msg-bar').removeClass("err info progress").empty();
			$('#sf').hide();
			$('#tf').show();
			$("#tf input:first").focus();	
			sf = false;
		} else {
			$('#tf').hide();
		}
		tf = !tf;
	};
	var toggle_feedback = function() {
		if (!sf) {
			$('#sf .msg-bar').removeClass("err info progress").empty();
			$('#tf').hide();
			$('#sf').show();
			$('#sf input:first').focus();			
			tf = false;
		} else {
			$('#sf').hide();
		}
		sf = !sf;
	};
	var friend_told = function(responseText, statusText) {
		var response = eval('('+responseText+')');
		if (response.status == 1) {			
			$('#tf .msg-bar').addClass('info').removeClass('err progress').text(response.msg).show().fadeOut(4000);
			clear_form_value($('#f-tf'));						
		} else {
			$('#tf .msg-bar').addClass('err').removeClass('info progress').text(response.msg).show();
		}
	};
	var feedback_sent = function(responseText, statusText) {
		var response = eval('('+responseText+')');
		if (response.status == 1) {			
			$('#sf .msg-bar').addClass('info').removeClass('err progress').text(response.msg).show().fadeOut(4000);
			clear_form_value($('#f-sf'));	
		} else {
			$('#sf .msg-bar').removeClass('info progress').addClass('err').text(response.msg).show();
		}
	};
	$('#feedback-link').click(toggle_feedback);
	$('#tell-link').click(toggle_tellfriend);
	var tf_options = { 
	        //target:        '#output1',   // target element(s) to be updated with server response 
	        //beforeSubmit:  showRequest,  // pre-submit callback 
	        success:       friend_told,  // post-submit callback 	 
	        url:       "af.php"         // override for form's 'action' attribute 
	        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
	        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
	        //clearForm: true        // clear all form fields after successful submit 
	        //resetForm: true        // reset the form after successful submit
	        //timeout:   3000 
	    }; 	
	$('#f-tf').ajaxForm(tf_options);
	var sf_options = { 
	        success: feedback_sent,
	        url:       "af.php"
	    }; 	
	$('#f-sf').ajaxForm(sf_options);

});
