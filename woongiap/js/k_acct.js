$(document).ready(function() {
	var f_name, f_value, selected_field;
	change_no_day();
	$("form .edit-field .cancel").click(function () {
		$("form .edit-field").hide();
		$(this).parent().siblings(".label, .edit").show();		
	});		
	$("form .edit").click(function() {
		$("form .edit-field").hide();
		$("form .label,form .edit").show();
		$(this).hide();		
		$(this).siblings(".label").hide();
		$(this).siblings(".edit-field").show();
		selected_field = $(this).siblings().children("input");
		if (!selected_field.length) {
			selected_field = $(this).siblings().children("select");
		}
	});
	$("form .edit-field").hide();
	var cpw = false;
	$("#cpw").text("Change Password");
	$("#password-box").hide();
	$("#cpw").click(function() {
		if (!cpw) {
			$("#password-box").show();
			$(this).text("Cancel");
		} else {
			$("#password-box").hide();
			$(this).text("Change Password");
			$("#password-box span.hint").removeClass("ferr").empty();
			$("#password-box input[type='password']").val("");
			$("#password-box .msg-bar").removeClass("err info").empty();
		}
		$("form .edit-field").hide();
		$("form .edit, form .label").show();
		cpw = !cpw;
	});
	
	var save_done = function(data) {
		var resp = eval('(' + data + ')');
		if (resp.status == 1) {
			$("form .edit-field").hide();
			$("#field-" + f_name).text(resp.msg).show();
			$("#field-" + f_name).siblings(".label,.edit").show();		
		} else {
			alert("Error, please try again.");
		}	
	};
	var save_field = function() {
		if (selected_field.length > 1) { // must be date field
			f_name = "birth_date";
			f_value = selected_field.eq(2).val()+"-" + selected_field.eq(0).val()+"-" + selected_field.eq(1).val();  
		} else {
			f_name = selected_field.attr("name");
			f_value = selected_field.val();
			if (f_name == "display_name" && f_value == "") {
				alert("Enter display name");
				return false;
			}
		}
		$.post('user_x.php', {'name':f_name, 'value':f_value}, save_done);
		return false;
	};
	$("form .edit-field .save").click(save_field);
	/*
	$("form .edit-field .save").click(function() {
		
		var text_field = $(this).siblings("input");
		var dropdown_field = $(this).siblings("select");
		if (text_field.length) {
			f_name = text_field.attr("name");
			f_value = text_field.val(); 
			if (f_name == "display_name" && f_value == "") {
				alert("Enter display name");
				return false;
			}
		} else if (dropdown_field.length) {
			f_name = dropdown_field.attr("name");
			f_value = dropdown_field.val();			
			if (dropdown_field.length > 1) { // must be date field
				f_name = "birth_date";
				f_value = dropdown_field.eq(2).val()+"-"+dropdown_field.eq(0).val()+"-"+dropdown_field.eq(1).val();  
			}
		}		
		$.post('user_x.php', {'name':f_name, 'value':f_value}, save_done);		
		return save_field();
	});
	*/
	$("#b_month,#b_year").change(change_no_day);
	var validate_password = function() {
		$('#password-box .hint').empty().removeClass('ferr');		
		var cango = true;
		var current_password = $("#password-box input[name='current_pw']").val();
		var new_password = $("#password-box input[name='pw']").val();
		var new_password2 = $("#password-box input[name='pw2']").val();
		if (current_password == '') {
			cango = false;
			$("#password-box span.hint").eq(0).addClass('ferr').text('Enter current password');
		}
		if (new_password == '') {
			cango = false;
			$("#password-box span.hint").eq(1).addClass('ferr').text('Enter new password');
		} else if (new_password.length < 4) {
			cango = false;
			$("#password-box span.hint").eq(1).addClass('ferr').text('At least 4 characters');
		}
		if (new_password2 == '') {
			cango = false;
			$("#password-box span.hint").eq(2).addClass('ferr').text('Retype new password');			
		}		
		if (cango && (new_password != new_password2)) {
			cango = false;
			$("#password-box span.hint").eq(2).addClass('ferr').text('Passwords do not match');
		}		
		return cango;
	};
	var password_changed = function(rtext, rstatus) {
		var resp = eval("(" + rtext + ")");
		if (resp.status == 1) {
			$("#password-box .msg-bar").removeClass("err").addClass("info").text(resp.msg).show().fadeOut(4000);			
		} else {
			$("#password-box .msg-bar").removeClass("info").addClass("err").text(resp.msg);
		}
	};
	var opts = { 
	        beforeSubmit:  validate_password, 
	        success:       password_changed, 	 
	        url:       "user_x.php",
	        type:      "POST" 
	    }; 	
	$("form[name='form-pw']").ajaxForm(opts);
	var user_opts = { 
	        beforeSubmit:  save_field,
	        success:       save_done,
	        url:       "user_x.php",
	        type:      "POST" 
	    }; 	
	$("form[name='form-user']").ajaxForm(user_opts);
});
