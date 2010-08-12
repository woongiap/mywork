<?php session_start();
require_once('inc/view.inc.php');
if (logged_in()) {
	// user already logged in, redirect to home
	header('Location: home.php' );
	exit;
}

function show_success() {
echo <<<klasz
<div class="section-box round-corner-top" style="width:400px;margin:0 auto;">
	<div class="section-title">Your account is confirmed</div>
	<div class="msg-bar">You can now <a href="login.php">login</a> to Klasz. Enjoy!</div>
</div>
klasz;
}

function show_form($err='') {
$css = empty($err)?'':'err';
//1257012178
$c = isset($_REQUEST['c'])?$_REQUEST['c']:'';
echo <<<klasz
<div class="section-box round-corner-top" style="width:600px;margin:auto;text-align:center;">
	<div class="section-title">Activate your Account</div>
	<div class="msg-bar $css">$err</div>
		<div>Thank you for signing up Klasz. Click "confirm" button to activate your account.</div>
	<form name="f-confirm" id="f-confirm" method="post" action="{$_SERVER['PHP_SELF']}">			
		<div class="form-item">
			<label for="code">Confirmation Code</label><span class="hint"></span>
			<br/><input type="text" size="8" name="c" id="code" value="$c" />
			<input type="hidden" name="e" value="{$_REQUEST['e']}" />			
		</div>
		<div class="form-item" style="text-align:center;">
			<input class="button" type="submit" name="submit" value="Confirm"/>		
		</div>
	</form>	
</div>
klasz;
}
require_once('inc/view.inc.php');
show_header('Klasz - Account Confirmation');
require_once('mod/user.inc.php');
if (isset($_GET['e'])) { // show confirm form
	show_form();
} else if (isset($_POST['submit'])) { // submit confirmation
	$return_code = user_activate($_POST['e'], $_POST['c']);
	if ($return_code == ACT_SUCCESS) {
		show_success();	
	} else {
		show_form('Something is wrong, check if your account has already been activated.<br/>
					Please make sure both email and code are correct.');
	}
	
} else {
	show_form('Invalid URL.');

}
show_footer();
?>

