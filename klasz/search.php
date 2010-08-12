<?php session_start(); 
require_once('mod/post.inc.php');
require_once('mod/global.inc.php');
require_once('inc/view.inc.php');
show_header('Welcome to Klasz - Search Result');
echo '<div id="left-box" style="width:78%">';
$q = isset($_GET['q'])?$_GET['q']:'';
$c = isset($_GET['c'])?$_GET['c']:'';
$s = isset($_GET['s'])?$_GET['s']:'';
$pg = isset($_GET['pg'])?$_GET['pg']:0;
if (empty($q) && ANY_STATE==$s && ANY_CATEGORY==$c) {
	echo '<div class="msg-bar info">You should at least give me something to search for, try again.</div>';
} else {
	$results = array();
	if (!empty($q)) {
		$results = search($q, $c, $s, $pg);
		show_result($results, $q, $c, $s, $pg);
	} else if (!empty($c) || !empty($s)) {
		$results = search_browse($c, $s, $pg);
		show_result($results, $q, $c, $s, $pg);
	} else {
		echo '<div class="msg-bar info">Look like you did not provide any search term. Nice try :-)</div>';
	}	
}
echo '</div>'; // left-box
echo '<div id="right-box" style="width:20%">';
include_once('inc/sidebar.php');
echo '</div>';	
show_footer();

function show_result($results, $q, $c, $s, $pg=0) {
	$num = sizeof($results);
	echo '<div class="section-box round-corner-top">';	
	echo '<div class="section-title">Search Results';
	if ($pg) {
		$ppg = $pg - 1;
		echo " <a href=\"search.php?q=$q&c=$c&s=$s&pg=$ppg\">&lt;&lt; </a>";	
	}
	if ($num > PG_ROW_VIEW) {
		$npg = $pg + 1;
		echo " <a href=\"search.php?q=$q&c=$c&s=$s&pg=$npg\"> &gt;&gt;</a>";	
	}	
	echo '</div>';
	if ($num > 0) {
		$last = ($num > PG_ROW_VIEW)? PG_ROW_VIEW: $num;
		for ($i = 0; $i < $last; $i++) {
			echo_post($results[$i]);
		}	
	} else {
		echo '<div class="msg-bar info">No match found.</div>';
	}
	echo '</div>'; // section-box	
}
?>
