<?php

class ModelCheckoutExtension extends Model {
    
	function getExtensions ($store_code, $type) {
	    
		$query = $this->db->query("
			SELECT * 
			FROM " . DB_PREFIX . "extension 
			WHERE 		1
				AND		`type` = '" . $this->db->escape($type) . "'
				AND		store_code = '{$store_code}'
		");

		return $query->rows;
		
	}
	
}
?>