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
	<div class="section-title">Your password has been reset.</div>
	<div class="msg-bar">You can now <a href="login.php">login</a> to Klasz. Enjoy!</div>
</div>
klasz;
}

function show_form($err='') {
$css = empty($err)?'':'err';
$code = isset($_REQUEST['code'])?$_REQUEST['code']:'';
$e = isset($_REQUEST['e'])?$_REQUEST['e']:'';
echo <<<klasz
<div class="section-box round-corner-top" style="width:600px;margin:auto;text-align:center;">
<script type="text/javascript" src="js/k_fpw.min.js"></script>
	<div class="section-title">Reset your password</div>
	<div class="msg-bar $css">$err</div>
	<form class="form" name="f-fpw" id="f-fpw" method="post" action="{$_SERVER['PHP_SELF']}">			
		<div class="form-item">
			<label for="code">Confirmation Code</label><span class="hint" id="msg-code"></span>
			<br/><input type="text" size="8" name="code" id="code" value="$code" />
			<input type="hidden" name="e" value="$e" />			
		</div>
		<div class="form-item">
			<label for="pw">New password</label><span class="hint" id="msg-pw"></span>
			<br/><input type="password" name="pw" id="pw" value="" />						
		</div>
		<div class="form-item">
			<label for="pw2">Retype new password</label><span class="hint" id="msg-pw2"></span>
			<br/><input type="password" name="pw2" id="pw2" value="" />						
		</div>		
		<div class="form-item" style="text-align:center;">
			<input class="button" type="submit" name="submit" value="Submit"/>		
		</div>
	</form>		
</div>
klasz;
}
require_once('inc/view.inc.php');
show_header('Klasz - Reset Password');
require_once('mod/user.inc.php');
if (isset($_GET['e'])) { // show confirm form
	show_form();
} else if (isset($_POST['submit'])) { // submit confirmation
	if (password_reset($_POST['e'], $_POST['code'], $_POST['pw'])) {
		show_success();	
	} else {
		show_form('Something is wrong. Please make sure both email and code are correct.');
	}
	
} else {
	show_form('Invalid URL.');

}
show_footer();
?>

