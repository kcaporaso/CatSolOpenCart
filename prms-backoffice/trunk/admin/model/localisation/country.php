<?php
class ModelLocalisationCountry extends Model {
	public function addCountry($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "country SET name = '" . $this->db->escape($data['name']) . "', iso_code_2 = '" . $this->db->escape($data['iso_code_2']) . "', iso_code_3 = '" . $this->db->escape($data['iso_code_3']) . "', address_format = '" . $this->db->escape($data['address_format']) . "'");
	
		$this->cache->delete('country');
	}
	
	public function editCountry($country_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "country SET name = '" . $this->db->escape($data['name']) . "', iso_code_2 = '" . $this->db->escape($data['iso_code_2']) . "', iso_code_3 = '" . $this->db->escape($data['iso_code_3']) . "', address_format = '" . $this->db->escape($data['address_format']) . "' WHERE country_id = '" . (int)$country_id . "'");
	
		$this->cache->delete('country');
	}
	
	public function deleteCountry($country_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");
		
		$this->cache->delete('country');
	}
	
	public function getCountry($country_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");
		
		return $query->row;
	}
		
	public function getCountries($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "country";
			
			$sort_data = array(
				'name',
				'iso_code_2',
				'iso_code_3'
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
		} else {
			$country_data = $this->cache->get('country');
		
			if (!$country_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country ORDER BY name ASC");
	
				$country_data = $query->rows;
			
				$this->cache->set('country', $country_data);
			}

			return $country_data;			
		}	
	}
	
	public function getTotalCountries() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "country");
		
		return $query->row['total'];
	}	

   public function getCountriesForStore ($store_code) {
    
      $country_data = $this->cache->get($store_code.'.country');
    
      if (!$country_data) {
    
         $query = $this->db->query("
            SELECT C.* 
            FROM " . DB_PREFIX . "country as C,
                  store_country as SC
            WHERE    1
               AND      C.country_id = SC.country_id
               AND      SC.store_code = '{$store_code}'
            ORDER BY    C.name ASC
         ");
   
         $country_data = $query->rows;
    
         $this->cache->set($store_code.'.country', $country_data);
      }  

      return $country_data;
    
   }  
   
   
   public function getCountryForStore ($store_code, $country_id) {
    
      $query = $this->db->query("
         SELECT C.* 
         FROM " . DB_PREFIX . "country as C,
               store_country as SC
         WHERE    1
            AND      C.country_id = SC.country_id
            AND      SC.store_code = '{$store_code}'
            AND      C.country_id = '{$country_id}'
         ORDER BY    C.name ASC
      ");   
    
      return $query->row;
    
   }  

}
?>
