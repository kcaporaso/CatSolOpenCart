<?php 

class ModelLocalisationTaxClass extends Model {
    
    
	public function addTaxClass ($store_code, $data) {
	    
		$this->db->query("
			INSERT INTO " . DB_PREFIX . "tax_class 
			SET 
				store_code = '{$_SESSION['store_code']}', 
				title = '" . $this->db->escape($data['title']) . "', 
				description = '" . $this->db->escape($data['description']) . "',
				taxrate_lookup_by_zipcode_flag = '{$data['taxrate_lookup_by_zipcode_flag']}',
				date_added = NOW()
		");
		
		$tax_class_id = $this->db->getLastId();
		
		if (isset($data['tax_rate'])) {
			foreach ($data['tax_rate'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "tax_rate SET geo_zone_id = '" . (int)$value['geo_zone_id'] . "', tax_class_id = '" . (int)$tax_class_id . "', priority = '" . (int)$value['priority'] . "', rate = '" . (float)$value['rate'] . "', description = '" . $this->db->escape($value['description']) . "', date_added = NOW()");
			}
		}
		
		//$this->cache->delete('tax_class');
		
	}
	
	
	public function editTaxClass ($store_code, $tax_class_id, $data) {
	    
		$this->db->query("
			UPDATE " . DB_PREFIX . "tax_class 
			SET 
				title = '" . $this->db->escape($data['title']) . "', 
				description = '" . $this->db->escape($data['description']) . "', 
				date_modified = NOW(),
				taxrate_lookup_by_zipcode_flag = '{$data['taxrate_lookup_by_zipcode_flag']}'
			WHERE 		1
				AND		tax_class_id = '" . (int)$tax_class_id . "'
				AND		store_code = '{$store_code}'
		");
		
		if ($this->getTaxClassStoreCode($tax_class_id) == $store_code) {
		
    		$this->db->query("DELETE FROM " . DB_PREFIX . "tax_rate WHERE tax_class_id = '" . (int)$tax_class_id . "'");
    
    		if (isset($data['tax_rate'])) {
    			foreach ($data['tax_rate'] as $value) {
    				$this->db->query("INSERT INTO " . DB_PREFIX . "tax_rate SET geo_zone_id = '" . (int)$value['geo_zone_id'] . "', tax_class_id = '" . (int)$tax_class_id . "', priority = '" . (int)$value['priority'] . "', rate = '" . (float)$value['rate'] . "', description = '" . $this->db->escape($value['description']) . "', date_added = NOW()");
    			}
    		}
    		
    		//$this->cache->delete('tax_class');
		
		}
		
	}
	
	
	public function getTaxClassStoreCode ($tax_class_id) {
	    
	    return $this->db->get_column('tax_class', 'store_code', "tax_class_id = '{$tax_class_id}'");
	    
	}	
	
	
	public function deleteTaxClass ($store_code, $tax_class_id) {
	    
	    if ($this->getTaxClassStoreCode($tax_class_id) == $store_code) {
	    
    		$this->db->query("DELETE FROM " . DB_PREFIX . "tax_class WHERE tax_class_id = '" . (int)$tax_class_id . "'");
    		$this->db->query("DELETE FROM " . DB_PREFIX . "tax_rate WHERE tax_class_id = '" . (int)$tax_class_id . "'");
    		
    		//$this->cache->delete('tax_class');
		
	    }
		
	}
	
	
	public function getTaxClass ($store_code, $tax_class_id) {
	    
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_class WHERE store_code = '{$store_code}' AND tax_class_id = '" . (int)$tax_class_id . "'");
		
		return $query->row;
		
	}
	

	public function getTaxClasses ($store_code, $data = array()) {
	    
    	//if ($data) {
    	    
			$sql = " SELECT * FROM " . DB_PREFIX . "tax_class WHERE store_code = '{$_SESSION['store_code']}' ";

			$sql .= " ORDER BY title";	
			
			if (@$data['order'] == 'DESC') {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
	  		$query = $this->db->query($sql);
		
			return $query->rows;
/*					
		} else {
		    
			$tax_class_data = $this->cache->get('tax_class');

			if (!$tax_class_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_class");
	
				$tax_class_data = $query->rows;
			
				$this->cache->set('tax_class', $tax_class_data);
			}
			
			return $tax_class_data;			
		}
*/		
	}
	
	
	public function getTaxRates ($tax_class_id) {
	    
      	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_rate WHERE tax_class_id = '" . (int)$tax_class_id . "'");
		
		return $query->rows;
		
	}
	
			
	public function getTotalTaxClasses ($store_code) {
	    
      	$query = $this->db->query(" SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tax_class WHERE store_code = '{$_SESSION['store_code']}' ");
		
		return $query->row['total'];
		
	}	
	
	
	public function getTotalTaxRatesByGeoZoneId ($geo_zone_id) {
	    
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tax_rate WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");
		
		return $query->row['total'];
		
	}
	
	
	public function record_in_use ($store_code, $tax_class_id) {
	    
	    // may be in use in other tables besides this
	    
	    $sql = "
	    	SELECT		customer_group_id
	    	FROM		customer_group
	    	WHERE		1
	    		AND		store_code = '{$store_code}'
	    		AND		group_tax_class_id = ".(int)$tax_class_id."
	    ";
	    
	    return (boolean) $this->db->get_multiple('customer_group', "group_tax_class_id = ".(int)$tax_class_id);
	    
	}

	
}
?>