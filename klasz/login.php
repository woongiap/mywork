<?php session_start();
require_once('mod/user.inc.php');
require_once('inc/view.inc.php');

if (logged_in()) {
	header('Location: home.php' ); // redirect
	exit();
}

$is_login = !empty($_POST['login']);
$is_forgotpassword = !empty($_POST['forgot-password']);
if ($is_login) {
	$username = $_POST['username'];
	$pw = $_POST['password'];
	if ($user = user_login($username, $pw)) {		
		$_SESSION['userid'] = $user->getId();
		$_SESSION['userdn'] = $user->getDisplayname();
		$_SESSION['username'] = $username;
		$_SESSION['logon_time'] = date("M d Y H:i:s");
		if (isset($_SESSION['last_url'])) {
			header('Location: '.$_SESSION['last_url']); // redirect
			unset($_SESSION['last_url']);
		} else {
			header('Location: home.php' ); // redirect	
		}		
		exit;
	} else {
		show_header('Login to Klasz');
		start_background();
		show_leftbox();
		show_form('Username or password is incorrect.');
		show_rightbox();
		close_background();		
		show_footer();		
	}
} else if ($is_forgotpassword) {
	$username = $_POST['username'];
	if (password_reset_request($username)) {
		show_header('Login to Klasz');
		start_background();		
		show_fpw_confirm($username);	
		close_background();	
		show_footer();		
	} 
} else {
	$last_msg = isset($_SESSION['last_msg'])?$_SESSION['last_msg']:'';
	unset($_SESSION['last_msg']);
	show_header('Login to Klasz');
	start_background();
	show_leftbox();
	show_form($last_msg);
	show_rightbox();
	close_background();
	show_footer();	
}

function show_fpw_confirm($login) {
	echo '<div class="section-box round-corner-top" style="width:480px;margin:0 auto;">';
	echo '<div class="section-title">Check your Email</div>';
	echo "<div class=\"msg-bar info\">An email will be sent to $login, kindly follow instruction in the email to reset your password.</div>";
	echo '</div>';
}

function start_background() {
	echo '<div style="width:100%;height:410px;background-image:url(\'img/my-map.png\');background-position:0px 20px;background-repeat:no-repeat;">';
}
function close_background() {
	echo '<div class="clear"></div>';
	echo '</div>';
}

function show_leftbox() {
echo <<<left
	<div id="left-box" style="width:30%;margin-top:10px;">
	<span style="font-weight:bold;color:#787878;font-size:2em;">Local Classifieds</span><br/>
	</div>
left;
}
function show_form($msg='') {
$username = isset($_POST['username'])?$_POST['username']:'';	
echo <<<middle1
<div id="middle-box" style="width:34%">
<script type="text/javascript" src="js/k_login.min.js"></script>
<script type="text/javascript">

</script>
<div class="section-box round-corner-top">
	<div class="section-title">Login</div>
middle1;
	if (!empty($msg)) {
		echo "<div id=\"stat\" class=\"msg-bar err\">$msg</div>";
	} else {
		echo '<div id="stat" class="msg-bar"></div>';
	}	
echo <<<middle2
	<form class="form" name="login_form" id="login_form" method="post" action="{$_SERVER['PHP_SELF']}">		
		<div class="form-item">
			<label for="username">Email</label><span class="hint" id="msg-email"></span>
			<br/><input type="text" name="username" id="email" value="$username" size="30"/>			
		</div>
		<div class="form-item" id="pw-field">
			<label for="password">Password</label><span class="hint" id="msg-pw"></span>
			<br/><input type="password" name="password" id="password" size="30"/>			
		</div>		
		<div class="form-item" style="text-align:right;">
			<input class="button" type="submit" id="submit" name="login" value="Login"/>
		</div>		
		<div class="link" id="fpw">Forgot password</div>
	</form>	
</div>
</div>
middle2;
}
function show_rightbox() {
echo <<<right
<div id="right-box" style="width:26%;">
	<div class="tips-box round-corner" style="text-align:center;">
		Don't have an account?
		<div class="big-button round-corner" onclick="window.location.href='signup.php'">Sign Up</div>
		<div><span style="font-weight:bold;">Why sign up?</span>
		<ul style="text-align:left;">
			<li>Start posting for free</li>
			<li>Manage your own postings</li>
			<li>Keep your bookmarked/liked postings for easy access</li>
			<li>Become top contributors and moderate postings</li>
		</ul>
		</div>
	</div>
</div>
right;
}
?>
