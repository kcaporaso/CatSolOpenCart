<?php

class ModelUserProductset extends Model {
    
    
	public function addProductset ($data) {
	    
	    $data['created_datetime'] = date(ISO_DATETIME_FORMAT);
	    $this->db->add('productset', $data);
	    
	}
	
	
	public function editProductset ($productset_id, $data) {
	    
	    $this->db->update('productset', $data, "productset_id = '{$productset_id}'");

	}
	
	
	public function deleteProductset ($productset_id) {
	    
	    $this->db->delete('productset', "productset_id = '{$productset_id}'");
		
	}
	
	
	public function getProductset ($productset_id) {
	    
	    $sql = "
	    	SELECT		P.*, U.username as user_name
	    	FROM		productset as P,
	    				user as U
	    	WHERE		1
	    		AND		P.user_id = U.user_id
	    		AND		P.productset_id = '{$productset_id}'
	    ";
	    
		$query = $this->db->query($sql);
		
		return $query->row;
		
	}

	
	public function getProductsets($data = array(), $viewing_user_id, $count_only=false, $restrict_owner_by_store_id=null) {

	    $this->load->model('user/user');
	    
	    if ($restrict_owner_by_store_id) {
	    
    	    $this->load->model('user/store');
    	    	    
    		$store = $this->model_user_store->getStore($restrict_owner_by_store_id);
    	    
    		if ($this->model_user_user->isAdmin($viewing_user_id)) {
    		    $user_id_clause = "	AND	(UG.admin_flag = 1 OR P.user_id = '{$store['user_id']}') ";
    	    } else {
    	        $user_id_clause = "	AND	(UG.admin_flag = 1 OR P.user_id = '{$viewing_user_id}') ";
    	    }

	    }
	    
	    if ($this->model_user_user->isAdmin($viewing_user_id)) {
	        $access_type_clause = "'W'";	// Write access
	    } else {
	        $viewing_user_id_clause = "	AND	(P.user_id = {$viewing_user_id} OR UG.admin_flag = 1) ";
	        $access_type_clause = "IF((U.user_id = '{$viewing_user_id}'), 'W', 'R')";    // Write or Read access depending on ownership
	    }  
	    
		$sql = "
			SELECT		P.*, U.username as user_name, {$access_type_clause} as access_type_code
			FROM		productset as P,
						user as U,
	    				user_group as UG
			WHERE		1
				AND		P.user_id = U.user_id
				AND		U.user_group_id = UG.user_group_id
				{$user_id_clause}
				{$viewing_user_id_clause}
		";																																					  
	
		$sort_data = array(
			'productset_id',
			'user_name',
			'code',
			'name'
		);
		
		if ($count_only) {
		    unset($data['sort']);
		    unset($data['order']);
		    unset($data['start']);
		    unset($data['limit']);
		}
		
		if (isset($data['productset_id'])) {
			$sql .= " AND productset_id = '" . (int)$data['productset_id'] . "'";
		}

		if (isset($data['user_id'])) {
			$sql .= " AND P.user_id = '" . (int)$data['user_id'] . "'";
		}

		if (isset($data['code'])) {
			$sql .= " AND P.code LIKE '%{$data['code']}%'";
		}
		
		if (isset($data['name'])) {
			$sql .= " AND P.name LIKE '%{$data['name']}%'";
		}		
			
		if (in_array(@$data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
		    $data['order'] = 'DESC';
			$sql .= " ORDER BY P.productset_id";	
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
		
		if ($count_only) {
		    return count($query->rows);
		} else {
		    return $query->rows;
		}
		
	}
	
	
	public function getTotalProductsets() {
	    
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "productset");
		
		return $query->row['total'];
		
	}
	
	
	// if not Superadmin, and if Productset owner is Superadmin, then these are restricted
	public function getRestrictedProductsetIDs ($viewing_user_id) {
	    
		$this->load->model('user/user');
	    
	    if ($this->model_user_user->isAdmin($viewing_user_id)) {
	        return array();    // nothing is restricted if Superadmin
	    }
	    
	    $sql = "
	    	SELECT		P.productset_id
	    	FROM		productset as P,
	    				user as U,
	    				user_group as UG
	    	WHERE		1
	    		AND		P.user_id = U.user_id
	    		AND		U.user_group_id = UG.user_group_id
	    		AND		UG.admin_flag = 1
	    ";
	    
	    $result = $this->db->query($sql);

	    foreach ((array) $result->rows as $row) {
	        $output[] = $row['productset_id'];
	    }
	    
	    return (array) $output;
	    
	}
	
	
	// if not Superadmin, then can only view own records
	/* W - full access | R - readonly access | NULL - no access
		Superadmin : full access
		Dealer : 	if own Productset, W
					elseif Superadmin Productset, R
					else NULL
	*/
	
	public function getOwnershipAccessType ($productset_id, $viewing_user_id) {	    

		$this->load->model('user/user');
		
		$viewer_is_admin = $this->model_user_user->isAdmin($viewing_user_id);
	    
	    if ($viewer_is_admin) {
	        $access_type_clause = "'W'";	// Write access
	    } else {
	        $viewing_user_id_clause = "	AND	(P.user_id = {$viewing_user_id} OR UG.admin_flag = 1) ";
	        $access_type_clause = "IF((U.user_id = '{$viewing_user_id}'), 'W', 'R')";    // Write or Read access depending on ownership
	    }    
	    
	    $sql = "
	    	SELECT		P.productset_id, {$access_type_clause} as access_type_code
	    	FROM		productset as P,
	    				user as U,
	    				user_group as UG
	    	WHERE		1
	    		AND		P.user_id = U.user_id
	    		AND		U.user_group_id = UG.user_group_id
	    		AND		P.productset_id = '{$productset_id}'
	    		{$viewing_user_id_clause}
	    ";
	    
	    $result = $this->db->query($sql);
	    
	    return $result->row['access_type_code'];
	    
	}
	
	
	public function getProductsetByCode ($productset_code, $ignore_id=null) {
	
        if ($ignore_id) {
            $ignore_id_clause = "AND productset_id != '{$ignore_id}'";
        }
	    
        $found = $this->db->get_multiple('productset', "code = '{$productset_code}' {$ignore_id_clause}");
          
        return $found[0];
	   
	}
	
	
	public function getProductsetIDFromCode ($productset_code) {
	    
        $found = $this->db->get_multiple('productset', "code = '{$productset_code}'");
          
        return $found[0]['productset_id'];
            
	}
	
	
	public function getOwnerUserIDFromCode ($productset_code) {
	    
        $found = $this->db->get_multiple('productset', "code = '{$productset_code}'");
          
        return $found[0]['user_id'];
            
	}
	
	
	public function is_own_or_core_productset ($productset_id, $viewing_user_id) {
	    
	    $sql = "
	    	SELECT		P.productset_id
	    	FROM		productset as P
	    				INNER JOIN user as U
	    					ON (P.user_id = U.user_id)
	    	WHERE		1
	    		AND		(
	    					U.user_group_id = 1
	    						OR
	    					P.user_id = '{$viewing_user_id}'
	    				)
	    		AND		P.productset_id = '{$productset_id}'
	    ";
	    
	    $result = $this->db->query($sql);
	    
	    return (boolean) $result->row['productset_id'];
	    
   }

   public function getProductsetsForStoreId($store_id) {

      $psets = $this->db->get_multiple('store_productsets', "store_id = '{$store_id}'"); 
      // careful, we're returning all columns.
      return $psets;
   }

   public function getProductsetsForStoreCode($store_code) {
      $this->load->model('user/store');
      $store_id = $this->model_user_store->getStoreIDFromCode($store_code);

      $psets = $this->db->get_multiple('store_productsets', "store_id = '{$store_id}'"); 
      // careful, we're returning all columns.
      return $psets;
   }
	
}

?>
