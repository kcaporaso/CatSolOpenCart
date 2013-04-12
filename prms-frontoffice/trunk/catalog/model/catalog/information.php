<?php

class ModelCatalogInformation extends Model {
    
    
	public function getInformation ($store_code, $information_id) {
	    
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.store_code = '{$store_code}' AND i.information_id = '" . (int)$information_id . "' AND id.language_id = '" . (int)$this->language->getId() . "'");
	
		return $query->row;
		
	}
	
	
	public function getInformations ($store_code) {
	    
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i.store_code = '{$store_code}' AND id.language_id = '" . (int)$this->language->getId() . "' ORDER BY i.sort_order ASC");
	
		return $query->rows;
		
	}
	
}
?>