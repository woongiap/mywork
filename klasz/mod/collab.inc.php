<?php
require_once('mod/db.inc.php');
require_once('mod/global.inc.php');
require_once('mod/Comment.class.php');
require_once("mod/mail.inc.php");

define('FB_DEFAULT', 1);
define('FB_RQ_CATEGORY', 2);
define('FB_SPAM', 3);
define('FB_DUPLICATE', 4);
define('FB_FRAUD', 5);
define('FB_OBSOLETE', 6);
define('FB_MISCATEGORIZED', 7);
define('FB_OTH', 20);

/* possible value in k_post_history.uptype and k_userpost.uptype*/
define('UP_BOOKMARK', 1);
define('UP_UNBOOKMARK', 2);
define('UP_VIEW', 3); // for k_post_history only
define('UP_LIKE', 5);
define('UP_UNLIKE', 6);
define('UP_EDIT', 7); // for k_post_history only

function comment_increase_counter($mysqli, $pid) {
	$q = "update k_post set num_comment=num_comment+1 where post_id=$pid";
	$mysqli->query($q);
}

function comment_add($pid, $comment, $uid, $username='') {
	$mysqli = k_get_mysqli();
	$q = "insert into k_comment (post_id, user_id, comment_username, comment_text, comment_date)
			values ($pid, $uid, '$username', '$comment', now())";
	$mysqli->query($q);
	$affected_row = $mysqli->affected_rows;
	comment_increase_counter($mysqli, $pid);
	$mysqli->close();
	return ($affected_row==1);
}

function comment_get($pid) {
	$mysqli = k_get_mysqli();
	$q = "select * from k_comment where post_id=$pid";
	$result = $mysqli->query($q);
	$comments = array();
	if ($result) {
		while ($row = $result->fetch_array()) {
			$c = new Comment;
			$c->setId($row['comment_id']);
			$c->setText($row['comment_text']);
			$c->setDate($row['comment_date']);
			$c->setCommentUsername($row['comment_username']);
			$c->setByUserId($row['user_id']);
			$comments[] = $c;
		}
		$result->close();
	}
	$mysqli->close();
	return $comments;
}

function post_add_history($mysqli, $pid, $type) {
	$ip = $_SERVER['REMOTE_ADDR'];
	$agent = $_SERVER["HTTP_USER_AGENT"];
	$referer = $_SERVER['HTTP_REFERER'];
	$sessionid = session_id();
	$uid = isset($_SESSION['userid'])?$_SESSION['userid']:0;
	$q = "insert into k_post_history (uptype, pid, remote_ip, uid, session_id, user_agent, http_referer, do_date) values 
			($type, $pid, '$ip', $uid, '$sessionid', '$agent', '$referer', now())";
	return $mysqli->query($q);	
}

// TODO: should be i18n strings
$g_type_label = array(UP_BOOKMARK=>'Bookmark',UP_UNBOOKMARK=>'Unbookmark',UP_LIKE=>'Like',UP_UNLIKE=>'Unlike');
function post_toggle($type, $pid, $uid=0) {
	if (!$uid) {
		return false; // public not allowed to like/bookmark (for time being)
	}
	global $g_type_label; 
	$q = "insert into k_userpost (post_id, uptype, user_id) values ($pid, $type, $uid)";
	//ERROR 1062 (23000): Duplicate entry '751526285-272479099-4' for key 1			
	$mysqli = k_get_mysqli();
	$mysqli->query($q);
	$next_label = false;	
	if ($mysqli->errno == 0) { // no error
		post_add_history($mysqli, $pid, $type);
		if (UP_LIKE == $type) {
			$q = "update k_post set num_like=num_like+1 where post_id=$pid";				
			$mysqli->query($q);
		}
		if (!$mysqli->errno) {
			$next_label = $g_type_label[$type + 1]; 
		}
	} else if ($mysqli->errno == ER_DUP_ENTRY) { // already a "liked" item
		$q = "delete from k_userpost where uptype=$type and post_id=$pid and user_id=$uid";
		$mysqli->query($q);
		post_add_history($mysqli, $pid, $type+1); // opposite of uptype always uptype+1
		if (UP_LIKE == $type) {
			$q = "update k_post set num_like=num_like-1 where post_id=$pid";			
			$mysqli->query($q);			
		}
		if (!$mysqli->errno) {
			$next_label = $g_type_label[$type]; 
		}		
	}
	$mysqli->close();
	return $next_label;
}

function confirm_code_send($email, $code) {
	$recipients = array($email => "Klasz User"); // TODO: put real display name
	$subject = "Klasz Account Confirmation";
	$email = urlencode($email);
$body = <<<b
Hello Klasz fellow,
<br/><br/>
Click <a href="http://www.klasz.com/confirm.php?e=$email&c=$code">this link</a> to activate your account.
<br/><br/>
Your code is: $code
<br/><br/>
Thank you.
<br/>From Klasz Team
b;
$alt_body = <<<alt_b
Hellow Klasz fellow,
\n\n
Kindly access this URL http://www.klasz.com/confirm.php?e=$email&c=$code to activate your account.
\n\n
Your code is: $code
\n\n
Thank you.
\nFrom Klasz Team
alt_b;
	return send_mail($recipients, $subject, $body, $alt_body);
}

function feedback_send($pid, $type, $email='', $msg='') {	
	$q = "insert into k_feedback (fb_type, fb_msg, fb_date, sender_email, post_id) values 
			($type, '$msg', now(), '$email', $pid)";
	$mysqli = k_get_mysqli();
	$mysqli->query($q);
	$num_row = $mysqli->affected_rows;
	$mysqli->close();
	if ($num_row == 1) {
		if (!empty($email)) {
			$recipients = array($email=>'Submitter');
			$body = "Hi,<br/><br/>We've stored your feedback in our database. 
					We will let you know again when we changed something relevant to your feedback.
					<br/><br/>Thank you for your feedback.<br/><br/>From Klasz Team";
			send_mail($recipients, 'Klasz - We heard your feedback', $body);			
		}		
	}
	return ($num_row == 1); 
}

/* mail() way 
function confirm_code_sent_deprecated($email, $code) {
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";		
	// Additional headers
	$headers .= 'From: Klasz Team <team@klasz.com>' . "\r\n";
	$body = "Hi,<br/><br/>Click  
		<a href=\"http://www.klasz.com/confirm.php?e=".urlencode($email)."&c=$code\">here</a>
		to activate your account.
		<br/><br/>From Klasz Team";		
	mail($email, 'Klasz Account Confirmation', $body, $headers);		
}
*/
require_once('mod/Faq.class.php');
function faq_get() {
	$q = 'select * from k_faq order by dorder';
	$mysqli = k_get_mysqli();
	$faqs = array();
	if ($result = $mysqli->query($q)) {
		$row = $result->fetch_assoc();
		$faqs[] = new Faq($row['question'], $row['answer'], $row['dorder']);
	}
	$mysqli->close();
	return $faqs;	
}
?>
