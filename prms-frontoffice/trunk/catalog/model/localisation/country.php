<?php

class ModelLocalisationCountry extends Model {
    
    
	public function getCountries ($store_code) {
	    
		$country_data = $this->cache->get($store_code.'.country');
		
		if (!$country_data) {
		    
			$query = $this->db->query("
				SELECT C.* 
				FROM " . DB_PREFIX . "country as C,
						store_country as SC
				WHERE		1
					AND		C.country_id = SC.country_id
					AND		SC.store_code = '{$store_code}'
				ORDER BY 	C.name ASC
			");
	
			$country_data = $query->rows;
		
			$this->cache->set($store_code.'.country', $country_data);
		}

		return $country_data;
		
	}
	
	
	public function getCountry ($store_code, $country_id) {
	    
		$query = $this->db->query("
			SELECT C.* 
			FROM " . DB_PREFIX . "country as C,
					store_country as SC
			WHERE		1
				AND		C.country_id = SC.country_id
				AND		SC.store_code = '{$store_code}'
				AND		C.country_id = '{$country_id}'
			ORDER BY 	C.name ASC
		");		
		
		return $query->row;
		
	}
	
	
	
	
}
?>