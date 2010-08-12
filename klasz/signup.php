<?php session_start();
require_once('mod/global.inc.php');
require_once('inc/view.inc.php');
if (logged_in()) {
	header('Location: home.php');
	exit;
}
require_once('mod/user.inc.php');
require_once('mod/User.class.php');
require_once('inc/recaptchalib.php');

function show_success($login) {
	echo '<div class="section-box round-corner-top" style="width:420px;margin:0 auto;">';
	echo '<div class="section-title">Thank you for signing up</div>';
	echo "<div class=\"msg-bar info\">An email will be sent to $login, kindly follow instruction in the email to activate your account.</div>";
	echo '</div>';	
}

function show_error($err) {
	echo "<div class=\"msg-bar err\">$err</div>";
}

function show_form($err='') {
	global $g_months;
echo '<script type="text/javascript" src="js/k_signup.min.js"></script>';
echo <<<middlebox1
<div id="middle-box" style="width:440px;">
<div class="section-box round-corner-top">
	<div class="section-title">Sign Up</div>
middlebox1;
if (!empty($err)) {
	show_error($err);
}
echo <<<middlebox2
	<form class="form" id="f-signup" name="f-signup" method="post" action="{$_SERVER['PHP_SELF']}">
		<div class="form-item">			
			<label for="username">Email</label><span id="msg-email" class="hint"></span>
			<br/><input type="text" name="username" id="username" size="30"/>			
		</div>
		<div class="form-item">
			<label for="first_name">Display Name</label><span id="msg-dname" class="hint"></span>
			<br/><input type="text" name="display_name" id="display_name" size="30"/>			
		</div>
		<div class="form-item">
			<label for="gender">Gender<span>(optional)</span></label><span id="msg-gender" class="hint"></span>
			<br/><select name="gender" id="gender"/>
				<option value="">--Select--</option>
				<option value="f">Female</option>
				<option value="m">Male</option>
				</select>			
		</div>
middlebox2;
echo '<div class="form-item"><label for="birth_date">Birth Date<span>(optional)</span></label><br/>';
echo '<select name="b_month" id="b_month">';
foreach ($g_months as $key=>$value) {
	echo "<option value=\"$key\">$value</option>";
}
echo '</select>';			
echo '<select name="b_day" id="b_day"><option value="0">Day</option>';
for ($d = 1; $d <= 31; $d++) {
	echo "<option value=\"$d\">$d</option>";
}				
echo '</select>';

echo '<select name="b_year" id="b_year"><option value="0">Year</option>';
$byear = !empty($_POST['b_year'])?$_POST['b_year']:'';
for ($y = 2008; $y>=1908; $y--) {
	if ($byear == $y) {
		echo "<option value=\"$y\" selected=\"select\">$y</option>";
	} else {
		echo "<option value=\"$y\">$y</option>";
	}
}
echo '</select></div>';
echo <<<middlebox3
		<div class="form-item">
			<label for="password">Password</label><span id="msg-pw" class="hint"></span>
			<br/><input type="password" name="password" id="password"/>			
		</div>
		<div class="form-item">
			<label for="password2">Retype Password</label><span id="msg-pw2" class="hint"></span>
			<br/><input type="password" name="password2" id="password2"/>			
		</div>
		<div class="form-item">
			<label for="email">Alternate Email<span>(optional)</span></label><span id="msg-altemail" class="hint"></span>
			<br/><input type="text" name="altemail" id="altemail" size="30"/>			
		</div>
middlebox3;
$publickey = "6LeI7wkAAAAAAOSLWNDctQom40MXfBXsOuG0J-Dl"; // you got this from the signup page
echo recaptcha_get_html($publickey);
echo <<<middlebox3
		<div class="form-item" style="text-align:right;">
			<input class="button" type="submit" name="submit" value="Signup"/>
		</div>		
	</form>
</div>
</div>
middlebox3;
}

function show_leftbox() {
echo <<<leftbox
<div id="left-box" style="width:480px;margin-top:20px;color:#666;">
	<div style="font-weight:bold;font-size:1.5em;">Signup to enjoy more with Klasz @Malaysia</div>
	<ul>
	<li>Choose a hard-to-guess *strong* password</li>
	<li>Do provide an alternate email address in case you forgot your real login</li>
	<li>Click on <img src="http://api.recaptcha.net/img/red/refresh.gif"></img> to refresh the CAPTCHA image if it is too hard to read</li>
	<li>Alternatively, you can also try the audio challenge by clicking <img src="http://api.recaptcha.net/img/red/audio.gif"></img></li>
	<li>We will NOT use any of your information for any commercial purpose, check out our <a href="privacy.php">"Privacy Policy"</a></li>
	<li>Please read and respect the <a href="tou.php">"Terms of Use"</a> of Klasz</li>
	<li>Proceed to <a href="login.php">login</a> if you have already signed up</li>
	<li>Thank you so much for becoming contributor of Klasz</li>
	</ul>	
</div>
leftbox;
}

function check_recaptcha() {
	if ($_SERVER["REMOTE_ADDR"] == '127.0.0.1') return true;
	$privatekey = "6LeI7wkAAAAAAJwG9ofNEErGauqyPO3qJS0MOExd";
	$resp = recaptcha_check_answer($privatekey,
		                                $_SERVER["REMOTE_ADDR"],
		                                $_POST["recaptcha_challenge_field"],
		                                $_POST["recaptcha_response_field"]);
	return $resp->is_valid;	                               
}

if (isset($_POST['submit'])) { // a POST submit
	if (!check_recaptcha()) {
			show_header('Klasz - Sign Up!');
			show_leftbox();
			show_form('Invalid CAPTCHA, please try again.');
			show_footer();
	} else { // captcha correct, proceed user creation
		$login = $_POST['username'];
		$dn = $_POST['display_name'];
		$pw = $_POST['password'];
		$alt_email = $_POST['altemail'];
		$byear = $_POST['b_year'];
		$bmonth = $_POST['b_month'];
		$bday = $_POST['b_day'];
		$gender = $_POST['gender'];
		$user = new User($pw);
		$user->setUsername($login);
		$user->setDisplayname($dn);
		$user->setAltEmail($alt_email);		
		$user->setGender($gender);
		if (checkdate(intval($bmonth), intval($bday), intval($byear))) {
			$user->setBirthdate(date_create("$byear-$bmonth-$bday"));
		}		
		if ($user->signup()) {			
			show_header('Klasz - Thank you for signing up');
			show_success($login);
			show_footer();
		} else {			
			show_header('Klasz - Sign Up!');
			show_leftbox();
			show_form('Unexpected error occured, please try again later. Sorry for inconvenience.');
			show_footer();			
		}
	}
} else { // just a GET, show the form
	show_header('Klasz - Sign Up!');
	show_leftbox();
	show_form();
	show_footer();
}

?>
