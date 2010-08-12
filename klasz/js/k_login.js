$(document).ready(function() {
	$("#email").focus();
	var fpw = false;
	$("#fpw").click(function() {
		$('.hint').empty().removeClass('ferr');
		if (!fpw) {
			$("#stat").removeClass("err").addClass("msg").text("Click on \"Send\" and we will send you an email.");
			$("#pw-field").hide();
			$("#submit").attr({"name": "forgot-password","value": "Send"});
			$(this).text("Cancel");
			$("#email").val("").focus();			
		} else {
			$("#stat").removeClass("msg").empty();
			$("#pw-field").show();
			$("#submit").attr({"name": "login", "value": "Login"});
			$(this).text("Forgot password");
			$("#email").focus().val("");			
		}
		fpw = !fpw;
	});
	$('#login_form').submit(function() {
		$('.hint').empty().removeClass('ferr');
		var cango = true;
		if ($('#email').val() == "") {
			cango = false;
			$('#msg-email').addClass('ferr').text('Enter email');
		} else if (!valid_email($('#email').val())) {
			cango = false;
			$('#msg-email').addClass('ferr').text('Email not valid');
		}
		if ($('#password').val() == '' && ("Send" != $("#submit").attr("value")) ) {
			cango = false;
			$('#msg-pw').addClass('ferr').text('Enter password');
		}
		return cango;
	});	
});
