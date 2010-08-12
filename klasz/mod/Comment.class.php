<?php
class Comment {
	private $c_id;
	private $c_text;
	private $c_date;
	private $c_byname;
	private $c_byuserid;

	function setId($id) {
		$this->c_id = $id;
	}
	
	function getId() {
		return $this->c_id;
	}
	
	function setText($s) {
		$this->c_text = $s;
	}
	
	function getText() {
		return $this->c_text;
	}
	
	function setDate($d) {
		$this->c_date = $d;
	}
	
	function getDate() {
		return $this->c_date;
	}
	
	function setCommentUsername($s) {
		$this->c_byname = $s;
	}	
	function getCommentUsername() {
		return $this->c_byname;
	}	

	function setByUserId($id) {
		$this->c_byuserid = $id;
	}	
	function getByUserId() {
		return $this->c_byuserid;
	}	
	
}
?>