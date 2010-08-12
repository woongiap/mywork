<?php
require_once('mod/Category.class.php');

class Post {
	private $id;
	private $title;
	private $html_desc;
	private $location;
	private $divcode;
	private $category;
	private $num_view;
	private $num_comment;
	private $num_like;
	private $state;
	private $create_date;
	private $by_username;
	private $by_userid;
	
	function __construct($title, Category $category, $location, $divcode, $desc="") {
		$this->title = $title;
		$this->category = $category;
		$this->location = $location;
		$this->divcode = $divcode;
		$this->html_desc = $desc;
	}
	
	function getTitle() { return $this->title; }	
	function getCategory() { return $this->category; }	
	function getLocation() { return $this->location; }
	function getDivcode() {	return $this->divcode; }
	
	function setDesc($desc) { $this->html_desc = $desc;	}
	function getDesc() { return $this->html_desc;	}	
	
	function setId($id) { $this->id = $id; }
	function getId() { return $this->id; }
	
	function setState(State $state) { $this->state = $state; }	
	function getState() { return $this->state; }
	
	function setNumView($num) { $this->num_view = $num;	}
	function getNumView() { return $this->num_view; }
		
	function setNumComment($num) { $this->num_comment = $num;	}
	function getNumComment() { return $this->num_comment; }
	
	function setNumLike($num) { $this->num_like = $num;	}
	function getNumLike() { return $this->num_like; }
	
	function setCreateDate($date) { $this->create_date = $date; }
	function getCreateDate() { return $this->create_date; }
	
	function setByUsername($name) { $this->by_username = $name; }
	function getByUsername() { return $this->by_username; }

	function setByUserId($n) { $this->by_userid = $n; }
	function getByUserId() { return $this->by_userid; }	
}
?>