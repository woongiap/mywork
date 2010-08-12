<?php session_start(); 
$p = isset($_GET['p'])?$_GET['p']:'';
$c = isset($_GET['c'])?$_GET['c']:'';
$u = isset($_GET['u'])?$_GET['u']:'';
$my = isset($_GET['my'])?$_GET['my']:'';

require_once('inc/view.inc.php');
show_header('');

echo '<div id="left-box" style="width:78%;">';
if (empty($_SERVER["QUERY_STRING"])) {
	// no query string
	include_once('inc/pl_d.php');	
} else {
	if (!empty($p)) {
		include_once('inc/pv.php');
	} else if (!empty($c)) {
		include_once('inc/pl_c.php');
	} else if (!empty($u)) {
		include_once('inc/pl_u.php');		
	} else if (!empty($my) && logged_in()) {
		include_once('inc/pl_my.php');		
	} else {
		// there is query string but not p/c/u. goto default
		include_once('inc/pl_d.php');
	}
}
echo '</div>';
echo '<div id="right-box" style="width:20%;">';
include_once('inc/sidebar.php');
echo '</div>';
show_footer();
?>
