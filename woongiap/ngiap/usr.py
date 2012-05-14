UID_MAX = 2147483646

def user_spare_id():
	return mt_rand(UID_MAX/2+1, UID_MAX)

def user_new_id():
	return mt_rand(123, UID_MAX/2)

def get_confirm_code():
	return mt_rand(1000000001, PHP_INT_MAX)

def valid_login_name(s):
	q = db.GqlQuery("SELECT __id FROM User " +
                "WHERE __username = :1", s)
	return q.get() is None

def user_login(username, pw):
	$q = "select user_id, display_name from k_user 
		where user_name='$username' and user_passwd=sha('$pw') and ustate=1";
	$user;
	$mysqli = k_get_mysqli();
	if ($result = $mysqli->query($q)) {
		if($result->num_rows == 1) {
			$row = $result->fetch_assoc();
			$user = new User();
			$user->setId($row['user_id']);
			$user->setDisplayname($row['display_name']);
			$result->close();			
		}
	}
	$mysqli->close();
	return $user; // num of row returned must be exactly 1

ACT_SUCCESS = 1
ACT_NO_USER = 2
ACT_INVALID_CODE = 3

def user_activate(email, code):
	$return_code = ACT_SUCCESS;;
	$q = "select last_key from k_user where user_name='$email'";
	$mysqli = k_get_mysqli();
	if ($result = $mysqli->query($q)) {
		$num_row = $result->num_rows;
		if ($num_row == 1) {
			$row = $result->fetch_array();
			if($code == $row['last_key']) {
				// clear the last key
				$q = "update k_user set last_key=NULL, ustate=1 where user_name='$email'";
				$mysqli->query($q);
			} else {
				$return_code = ACT_INVALID_CODE;
			}
		} else {
			// no such user
			$return_code = ACT_NO_USER;
		}
		$result->close();
	} else {
		$return_code = ACT_NO_USER;
	}	
	$mysqli->close();
	return $return_code;	

def password_change(user_id, old_password, new_password):
	$q = "update k_user set user_passwd=sha('$new_password')
			where user_id=$user_id and user_passwd=sha('$old_password')";
	$ret_code = 0;
	$mysqli = k_get_mysqli();
	if ($mysqli->query($q)) {
		$ret_code = ($mysqli->affected_rows == 1);
	}
	$mysqli->close();
	return $ret_code;

def password_reset(email, code, newpassword):
	$q = "update k_user set last_key=NULL, user_passwd=sha('$newpassword')
			where user_name='$email' and last_key='$code'";
	$ret_code = 0;
	$mysqli = k_get_mysqli();
	if ($mysqli->query($q)) {
		$ret_code = ($mysqli->affected_rows == 1);
	}
	$mysqli->close();
	return $ret_code;

def send_forgotpw_email(email, code):
	require_once('mod/mail.inc.php');
	$recipients[$email] = 'Klasz User';
	$email = urlencode($email);
$body = <<<msg
Hello Klasz fellow,
<br/><br/>
Please follow <a href="http://www.klasz.com/fpw.php?e=$email&code=$code">this link</a> and regain your login to Klasz.
<br/><br/>
Your code is: $code
<br/><br/>
Thank you.
<br/>
From Klasz Team
msg;
$alt_body = <<<altmsg
Hello Klasz fellow,
\n\n
Please visit http://www.klasz.com/fpw.php?e=$email&code=$code to regain your login to Klasz.
\n\n
Your code is: $code
\n\n
Thank you.
\n
From Klasz Team
altmsg;
	send_mail($recipients, "Klasz Login Retrieval", $body, $alt_body);			
	
def password_reset_request(email):
	$code = get_confirm_code();
	$q = "update k_user set last_key='$code' where user_name='$email' and last_key is null";
	$ret_code = 1; // ignore error if email not valid, no email sent anyway
	$mysqli = k_get_mysqli();
	if ($mysqli->query($q)) {
		if ($mysqli->affected_rows == 1) {
			send_forgotpw_email($email, $code);
			$ret_code = 1;		
		}
	}
	$mysqli->close();
	return $ret_code;

def get_user(uid):
	$q = "select * from k_user where user_id=$uid";
	$mysqli = k_get_mysqli();
	$user;
	if ($result = $mysqli->query($q)) {
		$row = $result->fetch_assoc();
		$user = new User;
		$user->setId($row['user_id']);
		$user->setDisplayname($row['display_name']);
	}
	$mysqli->close();
	return $user