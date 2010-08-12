<?php
	session_start();
	session_unset();
	session_destroy();	
	header('Location: home.php' ); // redirect
	setcookie("PHPSESSID", "", time() - 3600); // delete session id cookie
	exit;	
?>