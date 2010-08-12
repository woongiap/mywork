<div id="top-nav">
<!--  hold this for later
	<div class="nav-item">
		<div id="language" class="clickable">English v</div>
		<div id="languages" class="clickable" style="display: none; position: absolute;">Bahasa<br/>Chinese</div> 
	</div>
-->		
<?php
require_once('inc/view.inc.php');
	if (logged_in()) {
		echo '<span class="nav-item">Welcome '.$_SESSION['userdn'].'</span>';
		echo '<span class="nav-item"><a href="account.php">Account</a></span>';
		echo '<span class="nav-item"><a href="logout.php">Logout</a></span>';
	} else {
		echo '<span class="nav-item"><a href="login.php">Login</a></span>';
		echo '<span class="nav-item"><a href="signup.php">Sign Up!</a></span>';				
	}
	echo '<span class="nav-item"><a href="faq.php">FAQ</a></span>';
	//echo $_SERVER["REMOTE_ADDR"];
	//echo isset($_SESSION['logon_time'])?$_SESSION['logon_time']:'';
?>
</div>

