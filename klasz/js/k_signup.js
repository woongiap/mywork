var valid_login = true;
function check_login_done(data) {
	if (!data) {
		$('#msg-email').addClass('ferr').text('Email in use');
		valid_login = false;
	} else {
		$('#msg-email').removeClass('ferr').empty();
		valid_login = true;
	}	
}
function check_login(login) {
	$.post('af.php', {'ft':'checklogin', 'login':login}, check_login_done);
  	return false;	
}
$(document).ready(function() {
	$("#username").focus();
	change_no_day();
	$("#username").blur(function() {
		if ($("#username").val() != '') {
			// check duplicate login name
			check_login($("#username").val());
		}
	});
	$("#b_month,#b_year").change(change_no_day);	
	
	$('#f-signup').submit(function() {
		var cango = true; // determine if can proceed to signup
		$('.hint').empty().removeClass('ferr');		
		if ($('#username').val() == '') {
			cango = false;
			$('#msg-email').addClass('ferr').text('Enter email');
		} else if (!valid_email($('#username').val())) {
			cango = false;
			$('#msg-email').addClass('ferr').text('Email not valid');
		} else if (!valid_login) {
			cango = false;
			$('#msg-email').addClass('ferr').text('Email in use');
		}
		if ($('#display_name').val() == '') {
			cango = false;
			$('#msg-dname').addClass('ferr').text('Enter display name');
		}
		if ($('#password').val() == '') {
			cango = false;
			$('#msg-pw').addClass('ferr').text('Enter password');
		} else if ($('#password').val().length < 4) {
			cango = false;
			$('#msg-pw').addClass('ferr').text('At least 4 characters');
		}
		if ($('#password2').val() == '') {
			cango = false;
			$('#msg-pw2').addClass('ferr').text('Retype password');
		}
		if (cango && ($('#password').val() != $('#password2').val())) {
				cango = false;
				$('#msg-pw2').addClass("ferr").text("Passwords don't match");
		}
		if ($('#altemail').val()!="" && !valid_email($('#altemail').val())) {
			cango = false;
			$('#msg-altemail').addClass("ferr").text("Email not valid");
		}		
		return cango;
	});
});
