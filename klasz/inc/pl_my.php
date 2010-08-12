<?php
require_once('mod/post.inc.php');
$uid = $_SESSION['userid'];
$my = $_GET['my'];
$sec_title = 'My Postings';
if ('c'==$my) {
	$posts = post_by_user($uid);
} else if ('b'==$my) {
	$sec_title = 'My Bookmarked postings';
	$posts = bookmark_by_user($uid); 
} else if ('l'==$my) {
	$sec_title = 'Postings I like';
	$posts = like_by_user($uid);	 
}
echo '<div class="section-box round-corner-top">';
echo "<div class=\"section-title\">$sec_title</div>";
if (sizeof($posts) > 0) {
	foreach ($posts as $post) {
		echo_post($post);
	}
} else {
	echo '<div class="msg-bar info">No posting.</div>';
}
unset($posts); // useful?
echo '</div>';
?>
