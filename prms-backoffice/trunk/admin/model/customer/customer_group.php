<?php


class ModelCustomerCustomerGroup extends Model {
    
    
	public function addCustomerGroup ($store_code, $data) {
	    
		$this->db->query("
			INSERT INTO " . DB_PREFIX . "customer_group 
			SET 		store_code = '{$store_code}', 
						group_name = '" . $this->db->escape(@$data['group_name']) . "', 
						group_tax_class_id = '" . (int)@$data['group_tax_class_id'] . "', 
						group_discount = '" . (float)@$data['group_discount'] . "', 
						status = '" . (int)@$data['status'] . "',
						default_flag = '" . (int)@$data['default_flag'] . "'
		");
	
	}
	
	
	public function editCustomerGroup ($store_code, $customer_group_id, $data) {
	    
		$this->db->query("
			UPDATE " . DB_PREFIX . "customer_group 
			SET 	group_name = '" . $this->db->escape(@$data['group_name']) . "', 
					group_tax_class_id = '" . (int)@$data['group_tax_class_id'] . "', 
					group_discount = '" . (float)@$data['group_discount'] . "', 
					status = '" . (int)@$data['status'] . "' ,
					default_flag = '" . (int)@$data['default_flag'] . "'
			WHERE 		1
				AND		customer_group_id = '" . (int)$customer_group_id . "'
				AND		store_code = '{$store_code}'
		");
	
	}
	
	
	public function getCustomerGroupStoreCode ($customer_group_id) {
	    
	    return $this->db->get_column('customer_group', 'store_code', "customer_group_id = '{$customer_group_id}'");
	    
	}	
	
	
	public function deleteCustomerGroup ($store_code, $customer_group_id) {
	    
	    if ($this->getCustomerGroupStoreCode($customer_group_id) == $store_code) {
	    
		    $this->db->query("DELETE FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "' AND store_code = '{$store_code}' ");
		    
	    }
		
	}
	
	
	public function getCustomerGroup ($store_code, $customer_group_id) {
	    
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "' AND store_code = '{$store_code}' ");

		$customer_group = array(
			'group_name'         => $query->row['group_name'],
			'group_tax_class_id' => $query->row['group_tax_class_id'],
			'group_discount'     => $query->row['group_discount'],
			'status'             => $query->row['status'],
			'default_flag'             => $query->row['default_flag']
		);

		return $customer_group;
		
	}
	
		
	public function getCustomerGroups ($store_code, $data = array()) {
	    
		$sql = "SELECT * FROM " . DB_PREFIX . "customer_group WHERE store_code = '{$store_code}' ";

		$implode = array();
		
		if (isset($data['group_name'])) {
			$implode[] = "group_name LIKE '%" . $this->db->escape($data['group_name']) . "%'";
		}
		
		if (isset($data['status'])) {
			$implode[] = "status = '" . (int)$data['status'] . "'";
		}			
		
		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'group_name',
			'status'
		);	
			
		if (in_array(@$data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY group_name";
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

	
	public function getTotalCustomerGroups ($store_code, $data = array()) {
	    
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_group WHERE store_code = '{$store_code}' ";
		
		$implode = array();
		
		if (isset($data['group_name'])) {
			$implode[] = "group_name LIKE '%" . $this->db->escape($data['group_name']) . "%'";
		}
		
		if (isset($data['status'])) {
			$implode[] = "status = '" . (int)$data['status'] . "'";
		}		
		
		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}
				
		$query = $this->db->query($sql);
				
		return $query->row['total'];
		
	}
	
  	
  	public function setOtherCustomerGroupsNondefault ($store_code, $ignore_record_id) {
  	    
  	    $sql = "
  	    	UPDATE		customer_group
  	    	SET			default_flag = '0'
  	    	WHERE		1
  	    		AND		store_code = '{$store_code}'
  	    		AND		customer_group_id != '{$ignore_record_id}'
  	    ";
  	    
  	    $query = $this->db->query($sql);
  	    
  	}
  	
  	
  	public function getDefaultCustomerGroupID ($store_code) {
  	    
  	    $sql = "
  	    	SELECT		customer_group_id
  	    	FROM		customer_group
  	    	WHERE		1
  	    		AND		store_code = '{$store_code}'
  	    		AND		default_flag = '1'
  	    ";
  	    
  	    $result = $this->db->query($sql);
  	    
  	    return $result->row['customer_group_id'];
  	    
  	}
	
	
}
?>