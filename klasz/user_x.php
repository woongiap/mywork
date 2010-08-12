<?php session_start();
require_once('inc/view.inc.php');
require_once('mod/user.inc.php');
if ($userid = logged_in()) {
	if (isset($_POST['cpw'])) {
		if (password_change(logged_in(), $_POST['current_pw'], $_POST['pw'])) {
			echo "{status: 1, msg: 'Done.'}";	
		} else {
			echo "{status: 0, msg: 'Double check your current and new passwords.'}";
		}		
		exit;	
	}
	require_once('mod/user.inc.php');
	$field_name = $_POST['name'];
	$field_value = $_POST['value'];	
	$new_label = $field_value;
	if ($field_name == 'gender' && !empty($field_value)) {
		$new_label = $g_genders[$field_value];
	} else if ($field_name == 'birth_date') {
		if ($birth_date = date_create($field_value)) {
			$new_label = date_format($birth_date, 'd F Y');		
		} else {
			$field_value = '';
		}
	}
	$new_label = empty($new_label)? NOT_GIVEN : $new_label;
	if (user_update($userid, $field_name, $field_value)) {
		if ($field_name == 'display_name') {
			$_SESSION['userdn'] = $field_value;	
		}
		echo "{status:1, msg:'$new_label'}";	
	} else {
		echo "{status:0, msg:'Update error'}";
	}	
	exit;	
} else {
	echo "{status:0, msg:'Not logged in'}";
	exit;
}
?>
