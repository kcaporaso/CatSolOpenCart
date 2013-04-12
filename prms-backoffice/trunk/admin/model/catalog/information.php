<?php

class ModelCatalogInformation extends Model {
    
    
	public function addInformation ($store_code, $data) {
	    
		$this->db->query("INSERT INTO " . DB_PREFIX . "information SET store_code = '{$store_code}', sort_order = '" . (int)$this->request->post['sort_order'] . "'");

		$information_id = $this->db->getLastId(); 
			
		foreach ($data['information_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "information_description SET information_id = '" . (int)$information_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		if ($data['keyword']) {
			$this->db->query("
				INSERT INTO " . DB_PREFIX . "url_alias 
				SET 	query = 'information_id=" . (int)$information_id . "', 
						keyword = '" . $this->db->escape($data['keyword']) . "',
						store_code = '{$store_code}'						
			");
		}
		
		//$this->cache->delete('information');
		
	}
	
	
	public function editInformation ($store_code, $information_id, $data) {
	    
		$this->db->query("
			UPDATE " . DB_PREFIX . "information 
			SET 		sort_order = '" . (int)$data['sort_order'] . "' 
			WHERE 		1
				AND		information_id = '" . (int)$information_id . "'
				AND		store_code = '{$store_code}'
		");
		
		if ($this->getInformationStoreCode($information_id) == $store_code) {
    
    		$this->db->query("DELETE FROM " . DB_PREFIX . "information_description WHERE information_id = '" . (int)$information_id . "'");
    					
    		foreach ($data['information_description'] as $language_id => $value) {
    			$this->db->query("INSERT INTO " . DB_PREFIX . "information_description SET information_id = '" . (int)$information_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
    		}
    		
    		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE store_code = '{$store_code}' AND query = 'information_id=" . (int)$information_id. "'");
    		
    		if ($data['keyword']) {
    			$this->db->query("
    				INSERT INTO " . DB_PREFIX . "url_alias 
    				SET 	query = 'information_id=" . (int)$information_id . "', 
    						keyword = '" . $this->db->escape($data['keyword']) . "',
    						store_code = '{$store_code}'
    			");
    		}
    		
    		//$this->cache->delete('information');
		
		}
		
	}
	
	
	public function getInformationStoreCode ($information_id) {
	    
	    return $this->db->get_column('information', 'store_code', "information_id = '{$information_id}'");
	    
	}	
	
	
	public function deleteInformation ($store_code, $information_id) {
	    
	    if ($this->getInformationStoreCode($information_id) == $store_code) {
    	    
    		$this->db->query("DELETE FROM " . DB_PREFIX . "information WHERE store_code = '{$store_code}' AND information_id = '" . (int)$information_id . "'");
    		$this->db->query("DELETE FROM " . DB_PREFIX . "information_description WHERE information_id = '" . (int)$information_id . "'");
    		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE store_code = '{$store_code}' AND query = 'information_id=" . (int)$information_id . "'");
    
    		//$this->cache->delete('information');
		
	    }
		
	}	

	
	public function getInformation ($store_code, $information_id) {
	    
		$query = $this->db->query("
			SELECT 		DISTINCT *, 
						(	SELECT keyword 
							FROM " . DB_PREFIX . "url_alias 
							WHERE 		1
								AND		query = 'information_id=" . (int)$information_id . "'
								AND		store_code = '{$store_code}'
						) AS keyword 
			FROM " . DB_PREFIX . "information 
			WHERE 		1
				AND		information_id = '" . (int)$information_id . "'
				AND		store_code = '{$store_code}'
		");
		
		return $query->row;
		
	}
		
	
	public function getInformations ($store_code, $data = array()) {
	    
		//if ($data) {
		    
			$sql = "
				SELECT * 
				FROM " . DB_PREFIX . "information i 
					LEFT JOIN " . DB_PREFIX . "information_description id 
					ON (i.information_id = id.information_id) 
				WHERE 	1
					AND	id.language_id = '" . (int)$this->language->getId() . "'
					AND	i.store_code = '{$store_code}'
			";
		
			$sort_data = array(
				'id.title',
				'i.sort_order'
			);		
		
			if (in_array(@$data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY id.title";	
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
		    
			$information_data = $this->cache->get('information.' . $this->language->getId());
		
			if (!$information_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '" . (int)$this->language->getId() . "' ORDER BY id.title");
	
				$information_data = $query->rows;
			
				$this->cache->set('information.' . $this->language->getId(), $information_data);
			}	
	
			return $information_data;
					
		}
*/		
	}
	
	
	public function getInformationDescriptions ($store_code, $information_id) {
	    
		$information_description_data = array();
		
		$query = $this->db->query("
			SELECT 		ID.* 
			FROM " . DB_PREFIX . "information_description as ID,
						information as I
			WHERE 		1
				AND		ID.information_id = I.information_id
				AND		I.store_code = '{$store_code}' 
				AND 	ID.information_id = '" . (int)$information_id . "'
		");

		foreach ($query->rows as $result) {
			$information_description_data[$result['language_id']] = array(
				'title'       => $result['title'],
				'description' => $result['description']
			);
		}
		
		return $information_description_data;
		
	}
	
	
	public function getTotalInformations ($store_code) {
	    
      	$query = $this->db->query(" SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information WHERE store_code = '{$store_code}' ");
		
		return $query->row['total'];
		
	}
	
	
	public function url_alias_already_in_use ($store_code, $keyword, $ignore_record_id=null) {
	    
	    if ($ignore_record_id) {
	        $ignore_record_id_clause = " AND `query` != 'information_id={$ignore_record_id}' ";
	    }
	    
	    $sql = "
	    	SELECT		url_alias_id
	    	FROM		url_alias
	    	WHERE		1
	    		AND		store_code = '{$store_code}'
	    		AND		keyword = '{$keyword}'
	    		{$ignore_record_id_clause}		
	    ";
	    		
	    $result = $this->db->query($sql);
	    
	    return (boolean) $result->row['url_alias_id'];
	    
	}	
	
	
}

?>