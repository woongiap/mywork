<?php session_start();
require_once('inc/view.inc.php');
if (!logged_in()) {
	$_SESSION['last_url'] = 'account.php';
	header('Location: login.php' ); // redirect
	exit;
}
require_once('mod/user.inc.php');
require_once('mod/User.class.php');
$user = new User;
$user->setId(logged_in());
$user->getDBAccount();
$login = $user->getUsername();
$display_name = $user->getDisplayname();
$alt_email = $user->getAltEmail();
$alt_email = !empty($alt_email)?$alt_email:NOT_GIVEN;
$gender = $user->getGender();
$gender_str = !empty($gender)?$g_genders[$gender]:NOT_GIVEN;
$birth_date = $user->getBirthdate();
$bd_array = array('year'=>0,'month'=>0,'day'=>0);
if (!empty($birth_date)) {
	$bd_array = date_parse($birth_date);
	$birth_date = date_format(date_create($birth_date), 'd F Y');	
} else {
	$birth_date = NOT_GIVEN;	
}

show_header('Account Information - '.$_SESSION['username']);
echo '<div id="middle-box" style="width:76%;"><script type="text/javascript" src="js/k_acct.min.js"></script>';
echo '<div class="section-box round-corner-top">';
echo	'<div class="section-title">'.$login.' details</div>';
echo	' <form name="form-user" class="form">';
echo	' <input type="submit" style="display:none;" />';
echo	' <p>Display Name<br/>';
echo	' <span class="label" id="field-display_name">'.$display_name.'</span><span class="link edit">edit</span><br/>';
echo	' <span class="edit-field">';
echo	' 	<input type="text" name="display_name" value="'.$display_name.'" />';
echo	' 	<span class="link save">Save</span><span class="link cancel">Cancel</span>';
echo	' </span>';	
echo	' </p>';
echo	' <p>Alternate email address<br/>';
echo	' <span class="label" id="field-alt_email">'.$alt_email.'</span><span class="link edit">edit</span><br/>';
echo	' <span class="edit-field">';
echo	' 	<input type="text" name="alt_email" value="'.(($alt_email==NOT_GIVEN)?'':$alt_email).'" />';
echo	'	<span class="link save">Save</span><span class="link cancel">Cancel</span>';
echo	' </span>';	
echo	' </p>';
echo	' <p>Gender<br/>';
echo	' <span class="label" id="field-gender">'.$gender_str.'</span><span class="link edit">edit</span><br/>';
echo	' <span class="edit-field">';
echo	' 	<select name="gender"/>';
echo	'			<option value="">--Select--</option>';
foreach($g_genders as $key=>$value) {
	if ($key == $gender) {
		echo	'		<option value="'.$key.'" selected="selected">'.$value.'</option>';
	} else {
		echo	'		<option value="'.$key.'">'.$value.'</option>';	
	}		
}
echo	'</select>';
echo	'	<span class="link save">Save</span><span class="link cancel">Cancel</span>';
echo	' </span>';	
echo	' </p>';	 
echo	' <p>Birth Date<br/>';
echo	' <span class="label" id="field-birth_date">'.$birth_date.'</span><span class="link edit">edit</span><br/>';
echo 	'<span class="edit-field">';
echo 	'<select name="b_month" id="b_month">';
foreach ($g_months as $key=>$value) {
	if ($bd_array['month'] == $key) {
		echo "<option value=\"$key\" selected=\"selected\">$value</option>";
	} else {
		echo "<option value=\"$key\">$value</option>";	
	}	
}
echo '</select>';			
echo '<select name="b_day" id="b_day"><option value="0">Day</option>';
for ($d = 1; $d <= 31; $d++) {
	if ($bd_array['day'] == $d) {
		echo "<option value=\"$d\" selected=\"selected\">$d</option>";
	} else {
		echo "<option value=\"$d\">$d</option>";
	}
}				
echo '</select>';
echo '<select name="b_year" id="b_year"><option value="0">Year</option>';
for ($y = 2008; $y>=1908; $y--) {
	if ($bd_array['year'] == $y) {
		echo "<option value=\"$y\" selected=\"select\">$y</option>";
	} else {
		echo "<option value=\"$y\">$y</option>";
	}
}
echo '</select>';
echo '<span class="link save">Save</span><span class="link cancel">Cancel</span>';
echo '</span>';
echo	' </p>';
echo    '</form>';
echo	' <span class="link" id="cpw">Change Password</span>';
echo	' <div id="password-box">';
echo	' <form name="form-pw" class="form">';
echo	' <div class="msg-bar"></div>';
echo	' <div>Current password<span class="hint"></span><br/><input type="password" name="current_pw" value="" /></div>';
echo	' <div>New password<span class="hint"></span><br/><input type="password" name="pw" value="" /></div>';
echo	' <div>Retype password<span class="hint"></span><br/><input type="password" name="pw2" value="" /></div>';
echo	' <div><input class="button" type="submit" name="cpw" value="Change" /></div>';
echo	' </form>';
echo	' </div>';	 
echo '</div>';
echo '</div>';
echo '<div id="right-box" style="width:20%;">';
include_once('inc/sidebar.php');
echo '</div>';
show_footer();
?>
