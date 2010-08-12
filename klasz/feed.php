<?php
require_once('mod/post.inc.php');
date_default_timezone_set('Asia/Kuala_Lumpur');
header('Content-Type: application/atom+xml');
echo '<?xml version="1.0" encoding="utf-8"?>'; 
echo '<feed xmlns="http://www.w3.org/2005/Atom">';
echo ' <title>Klasz @Malaysia</title>';
echo '<subtitle>Contribute, Collaborate and Search for local classifieds</subtitle>';
echo '<link rel="self" type="application/atom+xml" href="http://www.klasz.com/feed.php" />';
echo '<link rel="alternate" type="text/html" href="http://www.klasz.com" />';
echo '<updated>'.date(DATE_ATOM).'</updated>';
echo '<author><name>Klasz @Malaysia</name></author><id>http://klasz.com/feed.php</id>';
$posts = post_feed();
foreach ($posts as $post) {
	echo '<entry>';
	echo '<title>'.htmlspecialchars($post->getTitle()).'</title>';
	echo '<link href="http://klasz.com/home.php?p='.$post->getId().'"/>';
	echo '<id>tag:klasz.com,'.date_format(date_create($post->getCreateDate()), 'Y-m-d').':/p/'.$post->getId().'</id>';
	echo '<updated>'.date_format(date_create($post->getCreateDate()), DATE_ATOM).'</updated>';
	//echo '<summary type="html">'.html_entity_decode(k_strip_tags($post->getDesc()),ENT_NOQUOTES,'utf-8').'</summary>';
	echo '<summary type="html">'.massage($post->getDesc()).'</summary>';
	echo '</entry>';	 
}
echo '</feed>';

function massage($string) {
	$string = k_strip_tags($string);
	$string = preg_replace('/&nbsp;/i', ' ', $string);
	return $string;
}

?>
