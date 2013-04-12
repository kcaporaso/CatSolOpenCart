<?php
final class spsHierarchy {

   private $hierarchy = array();
   /*
    * hierarchy[states[districts[schools[users]]]]
    *
    */

  	public function __construct() {
		$this->db = Registry::get('db');
		
  	}
}
?>
