<?php

class Debugger {
    
	
	public function __construct() {
	    
		$this->config = Registry::get('config');	
		$this->db = Registry::get('db');	
		$this->session = Registry::get('session');
		
	}
	
	
	public function d ($msg, $label = null, $vardump = false) {
	    
		echo "\n<pre>\n";
		if ($label)
			echo "<b>$label</b>\n";
		if ($vardump)
			var_dump($msg);
		else
			print_r($msg);
		echo "\n</pre>\n";
		
		flush();
		
	}
	
	
	public function x ($data, $label=null) {
	    
		if ($_SESSION['debug'])	{
			$this->d($data, $label);
		}
		
	}
  	
  	
}
?>