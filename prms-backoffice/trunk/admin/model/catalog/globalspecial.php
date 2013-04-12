<?php


class ModelCatalogGlobalspecial extends Model {
    
    
	public function addGlobalspecial ($store_code, $data) {
	    
		$this->db->query("
			INSERT INTO " . DB_PREFIX . "global_special 
			SET 	store_code = '{$store_code}', 
					discount = '{$data['discount']}',
					date_start = '{$data['date_start']}',
					date_end = '{$data['date_end']}',
					active_flag = '{$data['active_flag']}'
		");
	
	}
	
	
	public function editGlobalspecial ($store_code, $global_special_id, $data) {
	    
		$this->db->query("
			UPDATE " . DB_PREFIX . "global_special 
			SET 	discount = '{$data['discount']}',
					date_start = '{$data['date_start']}',
					date_end = '{$data['date_end']}',
					active_flag = '{$data['active_flag']}'
			WHERE 		1
				AND		id = '" . (int)$global_special_id . "'
				AND		store_code = '{$store_code}'
		");
	
	}
	
	
	public function getGlobalspecialStoreCode ($global_special_id) {
	    
	    return $this->db->get_column('global_special', 'store_code', "id = '{$global_special_id}'");
	    
	}	
	
	
	public function deleteGlobalspecial ($store_code, $global_special_id) {
	    
	    if ($this->getGlobalspecialStoreCode($global_special_id) == $store_code) {
    	    
    		$this->db->query("
    			DELETE 
    			FROM " . DB_PREFIX . "global_special 
    			WHERE 		1
    				AND		id = '" . (int)$global_special_id . "' 
    				AND 	store_code = '{$store_code}' 
    		");
		
	    }
		
	}
	
	
	public function getGlobalspecial ($store_code, $global_special_id) {
	    
		$query = $this->db->query("
			SELECT DISTINCT * 
			FROM " . DB_PREFIX . "global_special 
			WHERE 		1
				AND		id = '" . (int)$global_special_id . "' 
				AND 	store_code = '{$store_code}' 
		");

		$global_special = array(
			'discount'             => $query->row['discount'],
			'date_start'           => $query->row['date_start'],
			'date_end'             => $query->row['date_end'],
			'active_flag'          => $query->row['active_flag']
		);

		return $global_special;
		
	}
	
		
	public function getGlobalspecials ($store_code, $data = array()) {
	    
		$sql = "SELECT * FROM " . DB_PREFIX . "global_special WHERE store_code = '{$store_code}' ";

		$implode = array();
		
		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'discount',
			'date_start',
		    'date_end',
		    'active_flag'
		);	
			
		if (in_array(@$data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY date_start";
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
			
	}
	
	
}
?>