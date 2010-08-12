<?php
require_once('mod/post.inc.php');
require_once('mod/Comment.class.php');
require_once('mod/collab.inc.php');

function report_popup($p) {	
echo <<<klasz
<div id="report-box" class="popup" style="width:520px;">
	<div class="popup-title">Report this posting</div>
	<div style="display:none;" class="msg-bar"></div>
	<form class="form" name="f-report" id="f-report" method="post">
		<label>Your email<span>(optional)</span></label>
		<div>
		<input type="text" id="reporter" name="reporter" size="30"/>
		<input type="hidden" name="ft" value="report" />
		<input type="hidden" name="pid" value="$p" />		
		<select name="rt">
			<option value="3">Spam</option>
			<option value="4">Duplicate</option>
			<option value="5">Fraud</option>
			<option value="6">Obsolete</option>
			<option value="7">Miscategorized</option>
			<option value="20">Other</option>
			</select>
		</div>		
		<label>Description<span>(optional)</span></label><br/>
		<textarea style="width:96%;" name="desc" rows="6"></textarea>		
		<div style="text-align:right;">
			<span class="link" onclick="close_popup();">Close</span>
			<input type="submit" class="button" name="submit" value="Report" />
		</div>		
	</form>
</div>
klasz;
}

function share_popup($p) {	
echo <<<klasz
<div id="share-box" class="popup" style="width:400px;">
	<div class="popup-title">Share with friends</div>
	<div style="display:none;" class="msg-bar"></div>
	<form class="form" name="f-share" id="f-share" method="post">
		<label>Your Name<span>(optional)</span></label>
		<div>
		<input type="text" name="sharer" />
		<input type="hidden" name="ft" value="share" />
		<input type="hidden" name="pid" value="$p" />		
		</div>
		<label>Email addresses<span>(one per line)</span></label><br/>
		<textarea style="width:66%;" name="emails" rows="5"></textarea>
		<br/><label>Message<span>(optional)</span></label><br/>
		<textarea style="width:96%;" name="omsg" rows="6"></textarea>		
		<div style="text-align:right;">
			<span class="link" onclick="close_popup();">Close</span>
			<input type="submit" class="button" name="submit" value="Share" />
		</div>		
	</form>
</div>
klasz;
}

function show_commentbox($post) {
echo '<div id="comment-box" style="margin:30px 20px;border-top:1px solid #C0C0C0;">';
echo '<div class="msg-bar"></div>';
echo 	'<form class="form" id="f-comment" name="f-comment" method="post">';
if ($post->getByUserId() != logged_in()) {
	echo 		'<label>Your Name<span>(optional)</span></label><br/>';
	echo 		'<input type="text" name="cname" />';	
}
echo 		'<input type="hidden" name="ft" value="comment" />';
echo 		'<input type="hidden" name="pid" value="'.$post->getId().'" />';
echo 		'<input type="hidden" name="aid" value="'.$post->getByUserId().'" />';
echo 		'<input type="hidden" name="uid" value="'.logged_in().'" />';
echo 		'<div>Comment</div>';
echo 		'<textarea name="ctext" rows="8" style="width:60%;"></textarea>';
if (!logged_in()) {
echo 		'<div><img id="captcha-img" src="inc/captcha.php?key='.$post->getId().'"></img><br/>';
echo 		'Enter text above: <input type="text" name="captcha" value=""/></div>';	
}
echo 		'<div><input type="submit" class="button" value="Post" /></div>';
echo 	'</form>';
echo '</div>';	
}

function show_toolbar($post) {
$pid = $post->getId();	
$url_loc = urlencode($post->getLocation());
$s_url_name = urlencode(k_state_getname($post->getDivcode()));	
echo '<div style="text-align:right;background-color:#E8E8E8;">';
if (logged_in() && ($post->getByUserId() != logged_in())) {
	echo "<span class=\"nav-item link\" id=\"bookmark-link\" onclick=\"bookmark_post($pid);\">Bookmark</span>";
	echo "<span class=\"nav-item link\" id=\"like-link\" onclick=\"like_post($pid);\">Like</span>";
}
if ($post->getByUserId() != logged_in()) {		
	echo '<span class="nav-item link" id="report-link">Report</span>';	
} else {
	echo "<span class=\"nav-item link\" id=\"edit-link\" onclick=\"window.location.href='pedit.php?p=$pid';\">Edit</span>";
}
echo '<span class="nav-item link" id="share-link">Share with Friends</span>';
echo "<span class=\"nav-item link\" id=\"map-link\" onclick=\"toggle_map('$url_loc', '$s_url_name');\">View Map</span>";
echo '<span class="nav-item"><a name="fb_share" type="button" href="http://www.facebook.com/sharer.php">Facebook</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script></span>';
echo '</div>';
}

$pid = $_GET['p'];
global $g_post;
if (!isset($g_post)) $g_post = post_get($pid, 1);
$s = $g_post->getDivcode();
$s_name = k_state_getname($g_post->getDivcode());
$loc = $g_post->getLocation();
$comments = comment_get($pid);
echo '<script type="text/javascript" src="js/k_p.min.js?v=1"></script>';
// TODO: owner edit
echo '<div class="section-box round-corner-top">';
echo '<div class="section-title" style="text-align:center;">'.htmlspecialchars($g_post->getTitle()).'</div>';
echo '<div style="background-color:#E8E8E8;">';
echo '<span class="post-subtitle" style="padding:3px 0;margin-right: 5px;">@'.$loc.'/'.$s_name;
echo '&nbsp;&nbsp;by&nbsp;<a href="home.php?u='.$g_post->getByUserId().'">'.$g_post->getByUsername().'</a>';
echo '&nbsp;'.date_format(date_create($g_post->getCreateDate()), 'Md Y').'</span>';
echo '</div>';
show_toolbar($g_post);
echo '<div style="float:left;">';
echo '<div class="counter-flag-v"><div class="counter-number" style="font-size:12pt;">'.$g_post->getNumView().'</div>views</div>';
echo '<div class="counter-flag-c"><div class="counter-number" id="nc" style="font-size:12pt;">'.$g_post->getNumComment().'</div>comments</div>';
echo '<div class="counter-flag-l"><div class="counter-number" id="ng" style="font-size:12pt;">'.$g_post->getNumLike().'</div>likes</div>';
echo '</div>';
echo '<div id="posting" style="float:left;margin:30px 30px 0px 30px;width:660px;">'; // content+comment
echo '<div id="post-content" style="min-height:320px;">';
echo $g_post->getDesc();
echo '</div><!-- post-content -->';
echo '<div id="comment-list">';
echo '<div>Comments:</div>';
foreach ($comments as $c) {
	if ($c->getByUserId() != $g_post->getByUserId()) { 
		echo "<div class=\"comment-row\">";
	} else { // commentor is post's author
		$bg_color = '#F0F8FF';
		echo "<div class=\"comment-row\" class=\"round-corner\" style=\"background-color:$bg_color\">";		
	}	
	echo '<div class="comment-header">';
	$commentor = htmlentities($c->getCommentUsername(), ENT_QUOTES, 'utf-8');
	if ($c->getByUserId() == $g_post->getByUserId()) {
		$commentor = '<span style="font-weight:bold;font-style:italic">Author</span>';
	}
	echo $commentor.' wrote on '.date_format(date_create($c->getDate()), 'Md Y h:i a').':';	
	echo '</div>'; // <!-- comment header -->
	echo '<div class="comment-body">';
	$ctext = htmlentities($c->getText(), ENT_QUOTES, 'utf-8');
	echo $ctext;
	echo '</div>'; // <!-- comment body -->
	echo '</div>';
}
echo '</div>';
show_commentbox($g_post);
echo '</div>'; // end content+comment
echo '<div class="clear"></div>';
echo '</div>'; // #section-box
share_popup($pid);
report_popup($pid);
?>
