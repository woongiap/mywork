<?php
require_once('mod/post.inc.php');

$latest_posts = post_get_latest();
echo '<div class="section-box round-corner-top">';
echo '<div class="section-title">Latest</div>';
foreach ($latest_posts as $post) {
	echo_post($post);
}
echo '</div>';
$popular_posts = post_get_popular();
echo '<div class="section-box round-corner-top">';
echo '<div class="section-title">Popular</div>';
foreach ($popular_posts as $post) {
	echo_post($post);
}
echo '</div>';
$recommended_posts = post_get_recommended();
echo '<div class="section-box round-corner-top">';
echo '<div class="section-title">Recommended</div>';
foreach ($recommended_posts as $post) {
	echo_post($post);
}
echo '</div>';
?>
