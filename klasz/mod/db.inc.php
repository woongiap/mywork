<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'ngiapco_steven');
define('DB_PW', 'password');
define('DB_NAME', 'ngiapco_ad');
define('ER_DUP_ENTRY', 1062);

function k_get_mysqli() {
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PW, DB_NAME);
	if (mysqli_connect_errno()) {
		printf("mysqli error: %s\n", mysqli_connect_error());
	} else {
		return $mysqli;
	}	
}
?>