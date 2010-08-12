<?php
require_once('mod/db.inc.php');
require_once('mod/collab.inc.php');
require_once('mod/user.inc.php');

class User {
	private $username;
	private $alt_email;
	private $password;
	private $display_name;
	private $gender;
	private $birth_date;
	private $id;

	function __construct($password='') {
		$this->password = $password;
	}

	function setId($id) {
		$this->id = $id;
	}
	function getId() {
		return $this->id;
	}

	function setUsername($s) {
		$this->username = $s;
	}
	function getUsername() {
		return $this->username;
	}

	function setDisplayname($s) {
		$this->display_name = $s;
	}
	function getDisplayname() {
		return $this->display_name;
	}

	function setAltEmail($s) {
		$this->alt_email = $s;
	}
	function getAltEmail() {
		return $this->alt_email;
	}

	function setGender($g) {
		$this->gender = $g;
	}
	function getGender() {
		return $this->gender;
	}

	function setBirthdate($bdate) {
		$this->birth_date = $bdate;
	}
	function getBirthdate() {
		return $this->birth_date;
	}
	function getBirthdateDBStr() {
		if (is_null($this->birth_date))
			return "NULL";
		return "'".date_format($this->getBirthdate(), 'Y-m-d')."'";
	}

	// call like this: User::getStaticHello();
	static function getStaticHello() {
		return 'hello php';
	}

	function signup() {
		$username = $this->getUsername();
		$password = $this->password;
		$displayname = $this->getDisplayname();
		$alt_email = $this->getAltEmail();
		$gender = $this->getGender();
		$bdate_str = $this->getBirthdateDBStr();
		$code = get_confirm_code();
		$new_id = user_new_id();		
		$q = "insert into k_user
			(user_id, user_name, user_passwd, display_name, alt_email, signup_date, gender, birth_date, last_key)
				values ($new_id, '$username', sha('$password'), '$displayname', '$alt_email', now(),
				'$gender',$bdate_str,'$code')";
		$mysqli = k_get_mysqli();
		$mysqli->query($q);
		if ($mysqli->errno == ER_DUP_ENTRY) {
			$new_id = user_spare_id(); // try again
			$q = "insert into k_user
			(user_id, user_name, user_passwd, display_name, alt_email, signup_date, gender, birth_date, last_key)
				values ($new_id, '$username', sha('$password'), '$displayname', '$alt_email', now(),
				'$gender',$bdate_str,'$code')";
			$mysqli->query($q);
		}
		if ($mysqli->errno) {
			echo 'MYSQL error: '.$mysqli->errno.' > '.$mysqli->error;
			$mysqli->close();
			return false;
		}
		$affected_rows = $mysqli->affected_rows;
		$mysqli->close();
		confirm_code_send($this->getUsername(), $code);
		return ($affected_rows == 1);
	}

	function getDBAccount() {
		$mysqli = k_get_mysqli();
		$q = "select * from k_user where user_id='".$this->getId()."'";
		$result = $mysqli->query($q);
		$row = $result->fetch_assoc();
		$affected_rows = $mysqli->affected_rows;
		if ($affected_rows > 0) {
			$this->setDisplayname($row['display_name']);
			$this->setAltEmail($row['alt_email']);
			$this->setUsername($row['user_name']);
			$this->setGender($row['gender']);
			$this->setBirthdate($row['birth_date']);
		}
		$mysqli->close();
		return ($affected_rows == 1);
	}

	function updateDBAccount() {
		$mysqli = k_get_mysqli();
		$q = "update k_user set display_name='".$this->getDisplayname()."', alt_email='".$this->getAltEmail()."' where user_id=".$this->getId();
		$result = $mysqli->query($q);
		$errno = $mysqli->errno;
		$affected_rows = $mysqli->affected_rows;
		$mysqli->close();
		return ($errno == 0);
	}
}

?>