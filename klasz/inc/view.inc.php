<?php
require_once('mod/post.inc.php');
$g_post;
$g_user;
define('NOT_GIVEN', 'Not given');
static $g_months = array('Month','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Noc','Dec');
static $g_genders = array('f'=>'Female', 'm'=>'Male');
function put_pg_title() {
	$page_title = 'Klasz @Malaysia';
	if (!empty($_SERVER["QUERY_STRING"])) {
		$pid = isset($_GET['p'])?$_GET['p']:'';
		if (!empty($pid)) {
			global $g_post;
			$g_post = post_get($pid, true); // 2nd arg pass in 1 to increase view counter
			if (!$g_post) {
				header('Location: home.php');
				exit;
			} else {
				$page_title = 'Klasz - '.$g_post->getTitle();	
			}			
		} else {
			$c = isset($_GET['c'])?$_GET['c']:'';
			if (!empty($c)) {
				$category_name = k_category_getname($c);
				if (!$category_name) {
					header('Location: home.php');
					exit;					
				}
				$page_title = 'Klasz - '.htmlspecialchars($category_name);	
			} else {
				$u = isset($_GET['u'])?$_GET['u']:'';
				if (!empty($u)) {
					require_once('mod/user.inc.php');
					global $g_user;
					$g_user = get_user($u);
					$page_title = 'Klasz - Postings by '.$g_user->getDisplayname();	
				}			
			}			
		} 
	} // TODO: my items
	
	return $page_title;
}

function show_header($page_title, $extra='') {
	global $g_post;
	if (empty($page_title)) {
		$page_title = put_pg_title();
	}
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr"><head>';	
	echo "<title>$page_title</title>";
	echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" />';
	echo '<link rel="shortcut icon" href="http://www.klasz.com/favicon.ico" />';
	echo '<link rel="icon" type="image/vnd.microsoft.icon" href="http://www.klasz.com/favicon.ico" />';
	echo '<link rel="alternate" type="application/atom+xml" title="Klasz @Malaysia" href="http://klasz.com/feed.php" />';
	echo '<link rel="stylesheet" type="text/css" href="css/k.css" media="screen" />';
	if (isset($g_post)) {
		echo '<meta name="title" content="'.k_strip_tags($g_post->getTitle()).'" />';
		echo '<meta name="description" content="'.k_strip_tags($g_post->getDesc()).'" />';
		echo '<link rel="image_src" href="http://klasz.com/img/k.png" />';	
	}
	echo '<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>';	
	echo '<script type="text/javascript" src="js/jquery.form.min.js"></script>';	
	echo '<script src="js/k.min.js" type="text/javascript"></script>';
	if (!empty($extra)) echo $extra;
	echo '</head><body><div id="mother">';
	include_once('inc/topnav.php'); 
	include_once('inc/search_box.php');	
}

function show_footer() {
	include_once('inc/footer.php');
	echo '</div> <!-- mother --></body></html>';	
}

function echo_post($post) {
	echo '<div class="post-row">';
	echo '<div class="counter-flag-v" style="float:left;"><span class="counter-number">'.$post->getNumView().'</span>views</div>';
	echo '<div class="counter-flag-c" style="float:left;"><span class="counter-number">'.$post->getNumComment().'</span>comments</div>';
	echo '<div class="counter-flag-l" style="float:left;"><span class="counter-number">'.$post->getNumLike().'</span>likes</div>';	
	echo '<div class="post-category" style="float:left;"><a href="home.php?c='.$post->getCategory()->getId().'">'.$post->getCategory()->getName().'</a>&gt;</div>';
	echo '<div class="post-title" style="float:left;"><a href="home.php?p='.$post->getId().'">'.htmlspecialchars($post->getTitle()).'</a></div>';
	echo '<div class="post-subtitle" style="float:left;"> @'.$post->getLocation().'</div>';
	echo '<div class="clear"></div>';
	echo '</div>';
}

function logged_in() {
	return !empty($_SESSION['userid'])?$_SESSION['userid']:0;
}

function show_login_menu() {
if (logged_in()) {
echo <<<menu
	<div class="round-corner-top menu">
	<div class="menu-title">&nbsp;</div>
		<div class="clickable menu-item" onclick="window.location.href='home.php?my=b'">Bookmarked Items</div>
		<div class="clickable menu-item" onclick="window.location.href='home.php?my=l'">Liked Items</div>
		<div class="clickable menu-item" onclick="window.location.href='home.php?my=c'">My Items</div>
	</div>
menu;
}
	
}

?>
