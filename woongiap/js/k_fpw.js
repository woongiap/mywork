$(document).ready(function() {	
	$('#f-fpw').submit(function() {
		var cango = true; // determine if can proceed to reset password
		$('.hint').empty().removeClass('ferr');		
		if ($('#code').val() == '') {
			cango = false;
			$('#msg-code').addClass('ferr').text('Enter code');
		}
		if ($('#pw').val() == '') {
			cango = false;
			$('#msg-pw').addClass('ferr').text('Enter password');
		} else if($('#pw').val().length < 4) {
			cango = false;
			$('#msg-pw').addClass('ferr').text('At least 4 characters');
		}
		if ($('#pw2').val() == '') {
			cango = false;
			$('#msg-pw2').addClass('ferr').text('Retype password');
		}
		if (cango && ($('#pw').val() != $('#pw2').val())) {
				cango = false;
				$('#msg-pw2').addClass('ferr').text("Passwords don't match");
		}
		return cango;
	});
});
