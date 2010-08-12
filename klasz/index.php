<?php session_start();
require_once('inc/view.inc.php');
show_header('Klasz @Malaysia');
echo '<div id="left-box" style="width:78%;">';
include_once('inc/pl_d.php');
echo '</div>';
echo '<div id="right-box" style="width:20%;">';
include_once('inc/sidebar.php');
echo '</div>';
show_footer();
?>
