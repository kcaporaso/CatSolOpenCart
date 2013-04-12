<?php


class ModelLocalisationGeoZone extends Model {
    
    
	public function addGeoZone ($store_code, $data) {
	    
		$this->db->query("INSERT INTO " . DB_PREFIX . "geo_zone SET store_code = '{$store_code}', name = '" . $this->db->escape(@$data['name']) . "', description = '" . $this->db->escape(@$data['description']) . "', date_added = NOW()");

		$geo_zone_id = $this->db->getLastId();
		
		if (isset($data['zone_to_geo_zone'])) {
			foreach ($data['zone_to_geo_zone'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "zone_to_geo_zone SET country_id = '"  . (int)$value['country_id'] . "', zone_id = '"  . (int)$value['zone_id'] . "', geo_zone_id = '"  .(int)$geo_zone_id . "', date_added = NOW()");
			}
		}
		
		//$this->cache->delete('geo_zone');
		
	}
	
	
	public function editGeoZone ($store_code, $geo_zone_id, $data) {
	    
		$this->db->query("
			UPDATE " . DB_PREFIX . "geo_zone 
			SET 		name = '" . $this->db->escape(@$data['name']) . "', 
						description = '" . $this->db->escape(@$data['description']) . "', 
						date_modified = NOW() 
			WHERE 		1
				AND		geo_zone_id = '" . (int)$geo_zone_id . "'
				AND		store_code = '{$store_code}'
		");

		$this->db->query("
			DELETE 		X.*
			FROM " . DB_PREFIX . "zone_to_geo_zone as X,
						geo_zone as GZ
			WHERE 		1
				AND		X.geo_zone_id = GZ.geo_zone_id
				AND		GZ.store_code = '{$store_code}'
				AND		X.geo_zone_id = '" . (int)$geo_zone_id . "'
		");

		if (($this->getGeoZoneStoreCode($geo_zone_id) == $store_code) && isset($data['zone_to_geo_zone'])) {
			foreach ($data['zone_to_geo_zone'] as $value) {
				$this->db->query("
					INSERT INTO " . DB_PREFIX . "zone_to_geo_zone 
					SET 	country_id = '"  . (int)$value['country_id'] . "', 
							zone_id = '"  . (int)$value['zone_id'] . "', 
							geo_zone_id = '"  .(int)$geo_zone_id . "', 
							date_added = NOW()
				");
			}
		}
		
		//$this->cache->delete('geo_zone');
		
	}
	
	
	public function getGeoZoneStoreCode ($geo_zone_id) {
	    
	    return $this->db->get_column('geo_zone', 'store_code', "geo_zone_id = '{$geo_zone_id}'");
	    
	}
	
	
	public function deleteGeoZone ($store_code, $geo_zone_id) {
	    
	    if ($this->getGeoZoneStoreCode($geo_zone_id) == $store_code) {
	        
    		$this->db->query("DELETE FROM " . DB_PREFIX . "geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");
    		$this->db->query("DELETE FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");
    
    		//$this->cache->delete('geo_zone');
    		
	    }
		
	}
	
	
	public function getGeoZone ($store_code, $geo_zone_id) {
	    
		$query = $this->db->query("
			SELECT DISTINCT * 
			FROM " . DB_PREFIX . "geo_zone 
			WHERE 		1
				AND		store_code = '{$store_code}' 
				AND 	geo_zone_id = '" . (int)$geo_zone_id . "'
		");
		
		return $query->row;
		
	}

	
	public function getGeoZones ($store_code, $data = array()) {
	    
		//if ($data) {
		    
			$sql = " SELECT * FROM " . DB_PREFIX . "geo_zone WHERE store_code = '{$store_code}' ";
	
			$sort_data = array(
				'name',
				'description'
			);	
			
			if (in_array(@$data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY name";	
			}
			
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
		    
			$geo_zone_data = $this->cache->get('geo_zone');

			if (!$geo_zone_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone WHERE store_code = '{$store_code}' ORDER BY name ASC");
	
				$geo_zone_data = $query->rows;
			
				$this->cache->set('geo_zone', $geo_zone_data);
			}
			
			return $geo_zone_data;	
						
		}
*/
	}
	
	
	public function getTotalGeoZones ($store_code) {
	    
      	$query = $this->db->query(" SELECT COUNT(*) AS total FROM " . DB_PREFIX . "geo_zone WHERE store_code = '{$store_code}' ");
		
		return $query->row['total'];
		
	}	
	
	
	public function getZoneToGeoZones ($geo_zone_id) {
	    	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "' ORDER BY zone_to_geo_zone_id");
		
		return $query->rows;
			
	}		

	
	public function getTotalZoneToGeoZoneByGeoZoneId ($geo_zone_id) {
	    
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");
		
		return $query->row['total'];
		
	}
	
	
	public function getTotalZoneToGeoZoneByCountryId($country_id) {
	    
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id = '" . (int)$country_id . "'");
		
		return $query->row['total'];
		
	}
	
	
	public function getTotalZoneToGeoZoneByZoneId($zone_id) {
	    
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone_to_geo_zone WHERE zone_id = '" . (int)$zone_id . "'");
		
		return $query->row['total'];
		
	}	

	
	public function geozone_continental_USA_not_created_yet ($store_code) {
	    
	    $result = $this->db->get_multiple('geo_zone', "store_code = '{$store_code}' AND name = 'Continental United States' ");
   
	    return empty($result);
	    
	}
	
	
}

?>