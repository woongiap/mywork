<?php
require_once('mod/post.inc.php');
$kid = $_GET['c'];
$pg = isset($_GET['pg'])?$_GET['pg']:0;
$posts = post_by_category($kid, $pg);
echo '<div class="section-box round-corner-top">';
echo '<div class="section-title">'.htmlspecialchars(k_category_getname($kid));
$num = sizeof($posts);
if ($pg) {
	$ppg = $pg - 1;
	echo " <a href=\"home.php?c=$c&pg=$ppg\">&lt;&lt; </a>";	
}
if ($num > PG_ROW_VIEW) {
	$npg = $pg + 1;
	echo " <a href=\"home.php?c=$c&pg=$npg\"> &gt;&gt;</a>";	
}	
echo '</div>';
$last = ($num > PG_ROW_VIEW)? PG_ROW_VIEW: $num;
for ($i = 0; $i < $last; $i++) {
	echo_post($posts[$i]);
}
echo '</div>';
?>
