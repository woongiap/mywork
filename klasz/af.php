<?php
session_start();
require_once('mod/collab.inc.php');
require_once('mod/post.inc.php');
require_once('mod/mail.inc.php');
require_once('inc/view.inc.php');

define('FT_COMMENT', 'comment');
define('FT_RATE', 'rate');
define('FT_FEEDBACK', 'feedback');
define('FT_TELL', 'tell');
define('FT_SHARE_POST', 'share');
define('FT_REPORT_POST', 'report');
define('FT_SUGGEST_CATEGORY', 'sugcat');
define('FT_BOOKMARK', 'bookmark');
define('FT_CHECK_LOGIN', 'checklogin');

function split_emails($emails) {
	if (empty($emails)) {
		return false;
	}
	return explode("\n", $emails);
}

function tell_friend() {	
	header("content-type: text/html");
	if ($emails = split_emails($_POST['emails'])) {
		foreach ($emails as $email) {
			$recipients[$email] = '';
		}
		$submitter = !empty($_POST['submitter'])?stripslashes($_POST['submitter']):'Someone';
		$msg = !empty($_POST['msg'])?stripslashes($_POST['msg']):"(She/He didn't write any message)";
		$body = "Hello,<br/><br/>$submitter just checked out 
				<a href=\"http://www.klasz.com/\">Klasz @Malaysia</a>
				and think you would like it too. 
				Go ahead and take a look.<br/><br/>$msg<br/><br/>Thank you.<br/><br/>From Klasz Team";		
		if (send_mail($recipients, "$submitter wants you to know about Klasz", $body)) {
			echo "{status:1, msg:'Done.'}";
		} else {
			echo "{status:0, msg:'Error occured..'}";
		}
	} else {
		echo "{status:0, msg:'Enter valid emails.'}";
	}
	exit;
}

function send_feedback() {
	$feedback = isset($_POST['feedback'])?$_POST['feedback']:'';
	header("content-type: text/html");	
	if (!empty($feedback)) {
		$recipients = array('stevenyong@ngiap.com'=>'steven','ykeesin@yahoo.com'=>'keesin');
		send_mail($recipients, 'Klasz - We heard your feedback', 'Got feedback!');
		$email = isset($_POST['email'])?$_POST['email']:'';
		if (feedback_send(0, FB_DEFAULT, $email, $feedback)) {
			echo "{status:1, msg:'Thanks for your feedback.'}";
		} else {
			echo "{status:0, msg:'Error occured.'}";
		}					
	} else {
		echo "{status:0, msg:'Enter feedback.'}";
	}
	exit;
}

function write_comment() {
	$author = empty($_POST['cname'])?'anonymous':$_POST['cname'];
	if ($author === 'error') { // test jquery form error handling
		header("HTTP/1.0 404 Not Found");
		header('Content-Type: text/plain');
		echo 0;
		exit;
	}
	$pid = $_POST['pid'];
	$body = $_POST['ctext'];
	$uid = $_POST['uid'];
	$aid = $_POST['aid'];
	header("content-type: text/html");
	if (empty($_POST['ctext'])) {
		echo "{status:0, msg:'Enter required fields.'}";
		exit;		
	}
	if (isset($_POST['captcha']) && $_SESSION[$_POST['captcha']] != $pid) {
		echo "{status:0, msg:'Invalid Captcha.'}";
		exit;
	} else {
		// clear the captcha in session
		unset($_SESSION[$captcha]);
	}	
	if(comment_add($pid, $body, $uid, $author)) {
		$body = htmlentities($body, ENT_QUOTES, 'utf-8');
		if ($uid == $aid) {
			$author = 'Author';
		} else {
			$author = htmlentities($_POST['cname'], ENT_QUOTES, 'utf-8');
		}
		$response_text = sprintf('<div class="comment-row"><div class="comment-header">%s wrote on %s:</div><div class=\"comment-body\">%s</div></div>',
			$author, date("Md Y h:i a"), $body);
		echo "{status:1, msg:'$response_text'}";
	} else {
		echo "{status:0, msg:'Error occured.'}";
	}	
}

function rate_post() {
	$pid = $_POST['pid'];
	header('Content-Type: text/html');
	$label = post_toggle(UP_LIKE, $pid, logged_in()); 
	if ($label == false) echo "{status:0, msg:'Error occured'}"; 
	echo "{status:1, msg:'$label'}";		
}

function bookmark_post() {
	$pid = $_POST['pid'];
	header('Content-Type: text/html');
	$label = post_toggle(UP_BOOKMARK, $pid, logged_in());
	if ($label == false) echo "{status:0, msg:'Error occured'}"; 
	echo "{status:1, msg:'$label'}";
}

function share_post() {
	$pid = isset($_POST['pid'])?$_POST['pid']:'';
	$optional_msg = isset($_POST['omsg'])?$_POST['omsg']:'';
	header('Content-Type: text/html');
	if (!empty($pid) && ($emails = split_emails($_POST['emails']))) {
		$title = post_get_title($pid);
		foreach ($emails as $email) {
			$recipients[$email] = '';
		}
		$sharer = isset($_POST['sharer'])?$_POST['sharer']:'Someone';
		$body = "Hi,<br/><br/>$sharer would like you to check this out:<br/><br/>
				<a href=\"http://www.klasz.com/home.php?p=$pid\">$title</a><br/><br/>
				<br/><br/>$optional_msg<br/><br/>Thank you.<br/><br/>From Klasz Team";
		if (send_mail($recipients, "Klasz - $title", $body)) {
			echo "{status:1, msg:'Done.'}";
		} else {
			echo "{status:0, msg:'Error occured.'}";
		}	
	} else {
		echo "{status:0, msg:'Enter valid emails.'}";
	}
	exit;
}

function report_post() {
	$pid = isset($_POST['pid'])?$_POST['pid']:'';
	$type = isset($_POST['rt'])?$_POST['rt']:'';
	header('Content-Type: text/html');
	if (!empty($pid) && !empty($type)) {
		$desc = isset($_POST['desc'])?$_POST['desc']:'';
		$email = isset($_POST['reporter'])?$_POST['reporter']:'';
		if (feedback_send($pid, $type, $email, $desc)) {
			echo "{status:1, msg:'Thank you.'}";
		} else {
			echo "{status:0, msg:'Error occured.'}";
		}
	} else {
		echo "{status:0, msg:'Select reason.'}";
	}
	exit;
}

function suggest_category() {
	$desc = isset($_POST['desc'])?$_POST['desc']:'';
	header('Content-Type: text/html');
	if (!empty($desc)) {
		$email = isset($_POST['suggestor'])?$_POST['suggestor']:'';
		if (feedback_send(0, FB_RQ_CATEGORY, $email, $desc)) {
			echo "{status:1, msg:'Thank you.'}";
		} else {
			echo "{status:0, msg:'Error occured.'}";
		}
	} else {
		echo "{status:0, msg:'Enter description.'}";
	}
	exit;
}

function check_login() {
	require_once('mod/user.inc.php');
	if (empty($_POST['login'])) {
		echo true;
		exit;
	}
	echo valid_login_name(trim($_POST['login']));
	exit;	
}

$type = isset($_POST['ft'])?$_POST['ft']:'';
if (empty($type)) exit;
if ($type == FT_TELL) {
	tell_friend();
} else if ($type == FT_FEEDBACK) {
	send_feedback();	
} else if ($type == FT_COMMENT) {
	write_comment();
} else if ($type == FT_RATE) {
	rate_post();
} else if ($type == FT_SHARE_POST) {
	share_post();
} else if ($type == FT_REPORT_POST) {
	report_post();
} else if ($type == FT_SUGGEST_CATEGORY) {
	suggest_category();
} else if ($type == FT_BOOKMARK) {
	bookmark_post();
} else if ($type == FT_CHECK_LOGIN) {
	check_login();
} else {
	echo false;
}
?>