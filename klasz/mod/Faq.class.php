<?php
class Faq {
	public $q;
	public $a;
	public $dorder;
	
	function __construct($q, $a, $dorder=1) {
		$this->q = $q;
		$this->a = $a;
		$this->dorder = $dorder;
	}
}
?>