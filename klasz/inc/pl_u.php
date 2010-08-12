<?php
require_once('mod/post.inc.php');
$u = $_GET['u'];
$posts = post_by_user($u);
echo '<div class="section-box round-corner-top">';
global $g_user;
$dn = $g_user->getDisplayname();
echo "<div class=\"section-title\">Postings by $dn</div>";
foreach ($posts as $post) {
	echo_post($post);
}
unset($posts); // useful?
echo '</div>';
?>
