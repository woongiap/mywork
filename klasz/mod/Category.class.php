<?php
class Category {
	private $id;
	
	function __construct($id) {
		$this->id = $id;
	}
	
	function getId() { return $this->id; }	
	function getName() { return k_category_getname($this->id); }
	function getTableName() { return k_category_gettabname($this->id); }
}
?>