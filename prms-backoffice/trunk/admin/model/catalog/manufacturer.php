<?php

class ModelCatalogManufacturer extends Model {
    
    
	public function addManufacturer($data) {
	    
      	$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape(@$data['name']) . "', image = '" . $this->db->escape(basename($data['image'])) . "', sort_order = '" . (int)$data['sort_order'] . "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('manufacturer');
		
	}
	
	
	public function editManufacturer($manufacturer_id, $data) {
	    
      	$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape(@$data['name']) . "', image = '" . $this->db->escape(basename($data['image'])) . "', sort_order = '" . (int)@$data['sort_order'] . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('manufacturer');
		
	}
	
	
	public function deleteManufacturer($manufacturer_id) {
	    
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "'");
			
		$this->cache->delete('manufacturer');
		
	}	
	
	
	public function getManufacturer($manufacturer_id) {
	    
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "') AS keyword FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		
		return $query->row;
		
	}
	
	
	public function getManufacturers($data = array()) {
	    
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "manufacturer";
			
			$sort_data = array(
				'name',
				'sort_order'
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
		    
			$manufacturer_data = $this->cache->get('manufacturer');
		
			if (!$manufacturer_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer ORDER BY name");
	
				$manufacturer_data = $query->rows;
			
				$this->cache->set('manufacturer', $manufacturer_data);
			}
		 
			return $manufacturer_data;
			
		}
		
		
	}
	
	
	public function getTotalManufacturersByImageId($image_id) {
	    
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manufacturer WHERE image_id = '" . (int)$image_id . "'");

		return $query->row['total'];
		
	}

	
	public function getTotalManufacturers() {
	    
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manufacturer");
		
		return $query->row['total'];
		
	}	
	
	
	public function url_alias_already_in_use ($keyword, $ignore_record_id=null) {
	    
	    if ($ignore_record_id) {
	        $ignore_record_id_clause = " AND `query` != 'manufacturer_id={$ignore_record_id}' ";
	    }
	    
	    $sql = "
	    	SELECT		url_alias_id
	    	FROM		url_alias
	    	WHERE		1
	    		AND		store_code IS NULL		/* because Manufacturer is global */
	    		AND		keyword = '{$keyword}'
	    		{$ignore_record_id_clause}		
	    ";
	    		
	    $result = $this->db->query($sql);
	    
	    return (boolean) $result->row['url_alias_id'];
	    
	}
	
	
	public function get_id_from_name ($name) {
	    
	    $name_escaped = mysql_real_escape_string(htmlentities(trim($name), ENT_QUOTES, $this->detect_encoding($name)));
	    
	    $sql = "
	    	SELECT	manufacturer_id
	    	FROM	manufacturer
	    	WHERE	name LIKE '{$name_escaped}'
	    ";	   
	   
	    $result = $this->db->query($sql);
	    
	    return $result->row['manufacturer_id'];	    
	    
	}
	
	
	public function add_manufacturer_record_if_not_exists ($name) {
	    
	    if (!$this->get_id_from_name($name)) {
	        
	        $name_escaped = mysql_real_escape_string(htmlentities(trim($name), ENT_QUOTES, $this->detect_encoding($name)));
	        
	        $sql = "
	        	INSERT DELAYED INTO		manufacturer
	        				SET			name = '{$name_escaped}'
	        ";
	        
	        $this->db->query($sql);
	        
	    }
	    
	}
	

	public function get_manufacturers_dropdown ($selected_ids) {
	    	    
	    $data_array = array();
	    
	    $rows = (array) $this->getManufacturers();
	    
	    foreach ($rows as $row) {
	                
	        $data_array[$row['manufacturer_id']] = $row['name'];
	        
	    }

	    $pulldown_options = $this->get_pulldown_options($data_array, $selected_ids, false);
	    
	    return $pulldown_options;
	    
	}		
	
	protected function detect_encoding( $str ) {
		// auto detect the character encoding of a string
		return mb_detect_encoding( $str, 'UTF-8,ISO-8859-15,ISO-8859-1,cp1251,KOI8-R' );
	}
	
	
}
?>