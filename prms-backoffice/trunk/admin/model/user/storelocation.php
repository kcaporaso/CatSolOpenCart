<?php


class ModelUserStorelocation extends Model {
    
    
	public function addStorelocation ($store_code, $data) {
		$this->db->query("
			INSERT INTO " . DB_PREFIX . "storelocation 
			SET 	
					store_code = '{$store_code}',
					name = '{$data['name']}',
					address_1 = '{$data['address_1']}',
					address_2 = '{$data['address_2']}',
					city = '{$data['city']}',
					postalcode = '{$data['postalcode']}',
					phone = '{$data['phone']}',
					localpickup_fee = '{$data['localpickup_fee']}'
		");
	
	}
	
	
	public function editStorelocation ($store_code, $storelocation_id, $data) {
	    
		$this->db->query("
			UPDATE " . DB_PREFIX . "storelocation 
			SET 	name = '{$data['name']}',
			      address_1 = '{$data['address_1']}',
			      address_2 = '{$data['address_2']}',
			      city = '{$data['city']}',
			      postalcode = '{$data['postalcode']}',
			      phone = '{$data['phone']}',
			      localpickup_fee = '{$data['localpickup_fee']}'			      
			WHERE 		1
				AND		id = '" . (int)$storelocation_id . "'
				AND		store_code = '{$store_code}'
		");
	
	}
	
	
	public function deleteStorelocation ($store_code, $storelocation_id) {
	    
		$this->db->query("
			DELETE 
			FROM " . DB_PREFIX . "storelocation 
			WHERE 		1
				AND		id = '" . (int)$storelocation_id . "'
				AND		store_code = '{$store_code}'
		");
		
	}
	
	
	public function getStorelocation ($store_code, $storelocation_id) {
	    
		$query = $this->db->query("
			SELECT DISTINCT * 
			FROM " . DB_PREFIX . "storelocation 
			WHERE 		1
				AND		id = '" . (int)$storelocation_id . "' 
				AND		store_code = '{$store_code}'
		");

		$storelocation = array(
		    'store_code'	   => $query->row['store_code'],
  			 'name'             => $query->row['name'],
			 'address_1'             => $query->row['address_1'],
			 'address_2'             => $query->row['address_2'],
			 'city'             => $query->row['city'],
			 'postalcode'             => $query->row['postalcode'],
			 'phone'             => $query->row['phone'],
		     'localpickup_fee'	    => $query->row['localpickup_fee']
		);

		return $storelocation;
	}
	
		
	public function getStorelocations ($store_code, $data = array()) {
	    
		$sql = "SELECT * FROM " . DB_PREFIX . "storelocation WHERE store_code = '{$store_code}' ";

		$implode = array();

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'id',
			'name'
		);	
			
		if (in_array(@$data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY id";
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
	
	
	public function getDropdownOptions ($store_code, $selected_id=null, $firstblank=true) {
	    
  	    $rows = $this->model_user_storelocation->getStorelocations($store_code);
  	    
  	    foreach ($rows as $row) {
  	        $dropdown_rows[$row['id']] = $row['name'];
  	    }
  	    
  	    return $this->get_pulldown_options($dropdown_rows, $selected_id, $firstblank);
  	    	    
	}
	
	
	public function get_id_from_name ($store_code, $name) {
	    
	    return $this->db->get_column('storelocation', 'id', " store_code = '{$store_code}' AND name LIKE TRIM('{$name}') ");
	    
	}
	
	
}
?>
