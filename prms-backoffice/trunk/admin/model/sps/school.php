<?php

class ModelSPSSchool extends Model {
    
	public function addSchool($data) {
	    
		$this->db->query("
			INSERT INTO `" . DB_PREFIX . "sps_school` 
			SET 		name = '" . $this->db->escape(@$data['name']) . "', 
						store_code = '" . $_SESSION['store_code'] . "', 
						district_id = '" . $this->db->escape(@$data['district_id']) . "', 
						active = '" . $this->db->escape(@$data['active']) . "', 
						address1 = '" . $this->db->escape(@$data['address1']) . "', 
						address2 = '" . $this->db->escape(@$data['address2']) . "', 
						city = '" . $this->db->escape(@$data['city']) . "', 
						state = '" . $this->db->escape(@$data['state']) . "', 
						county = '" . $this->db->escape(@$data['county']) . "', 
						country = '" . $this->db->escape(@$data['country']) . "', 
						zipcode = '" . $this->db->escape(@$data['zipcode']) . "', 
						phone = '" . $this->db->escape(@$data['phone']) . "', 
						fax = '" . $this->db->escape(@$data['fax']) . "', 
						url = '" . $this->db->escape(@$data['url']) . "', 
						email = '" . $this->db->escape(@$data['email']) . "', 
						instant_approval = '" . $this->db->escape(@$data['instant_approval']) . "', 
						modified_date = NOW(),
						create_date = NOW(),
                  billing_firstname = '" . $this->db->escape(@$data['billing_firstname']) . "',
                  billing_lastname = '" . $this->db->escape(@$data['billing_lastname']) . "',
                  billing_address1 = '" . $this->db->escape(@$data['billing_address1']) . "',
                  billing_address2 = '" . $this->db->escape(@$data['billing_address2']) . "',
                  billing_city = '" . $this->db->escape(@$data['billing_city']) . "',
                  billing_state = '" . $this->db->escape(@$data['billing_state']) . "',
                  billing_zipcode = '" . $this->db->escape(@$data['billing_zipcode']) . "',
                  billing_phone = '" . $this->db->escape(@$data['billing_phone']) . "'
		");
		
	}
	
	public function editSchool($school_id, $data) {

		$this->db->query("
			UPDATE `" . DB_PREFIX . "sps_school` 
			SET 		name = '" . $this->db->escape(@$data['name']) . "', 
						district_id = '" . $this->db->escape(@$data['district_id']) . "', 
						active = '" . $this->db->escape(@$data['active']) . "', 
						address1 = '" . $this->db->escape(@$data['address1']) . "', 
						address2 = '" . $this->db->escape(@$data['address2']) . "', 
						city = '" . $this->db->escape(@$data['city']) . "', 
						state = '" . $this->db->escape(@$data['state']) . "', 
						county = '" . $this->db->escape(@$data['county']) . "', 
						country = '" . $this->db->escape(@$data['country']) . "', 
						zipcode = '" . $this->db->escape(@$data['zipcode']) . "', 
						phone = '" . $this->db->escape(@$data['phone']) . "', 
						fax = '" . $this->db->escape(@$data['fax']) . "', 
						url = '" . $this->db->escape(@$data['url']) . "', 
						email = '" . $this->db->escape(@$data['email']) . "', 
						instant_approval = '" . $this->db->escape(@$data['instant_approval']) . "', 
                  modified_date = NOW(),
                  billing_firstname = '" . $this->db->escape(@$data['billing_firstname']) . "',
                  billing_lastname = '" . $this->db->escape(@$data['billing_lastname']) . "',
                  billing_address1 = '" . $this->db->escape(@$data['billing_address1']) . "',
                  billing_address2 = '" . $this->db->escape(@$data['billing_address2']) . "',
                  billing_city = '" . $this->db->escape(@$data['billing_city']) . "',
                  billing_state = '" . $this->db->escape(@$data['billing_state']) . "',
                  billing_zipcode = '" . $this->db->escape(@$data['billing_zipcode']) . "',
                  billing_phone = '" . $this->db->escape(@$data['billing_phone']) . "'
			WHERE 	id = '" . (int)$school_id . "'
		");
		
	}
	
	
	public function deleteSchool($school_id) {
	    
		$this->db->query("DELETE FROM `" . DB_PREFIX . "sps_school` WHERE id = '" . (int)$school_id . "'");
		
	}
	
	
	public function getSchool($s_id) {
	    
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sps_school` WHERE id = '" . (int)$s_id . "'");
	
		return $query->row;
	}


   public function getSchoolName($s_id) {
      $query = $this->db->query("SELECT name FROM sps_school WHERE id='{$s_id}'");
      return $query->row['name'];
   }   
	
	public function getSchools ($data = array()) {
      $where = "";

      if (isset($data['district_filter'])) {
         $where .= " AND district_id = '{$data['district_filter']}' "; 
      }

      if (isset($data['district_id'])) {
         $where .= " AND district_id = '{$data['district_id']}' "; 
      }

      if (isset($data['search'])) {
         foreach ($data['search'] as $key => $value) {
            $where .= " AND " . $key . " like '%" . $value . "%'";
         }
      }
		$sql = "
			SELECT * 
			FROM `" . DB_PREFIX . "sps_school`
			WHERE	1
					{$where}
		";
			
		$sort_data = array(
			'name',
			'create_date'
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
		
	}
	
	
	public function getSchoolsWithProductsets ($user_id) {
	    
	    if (!$this->isAdmin($user_id)) {
	        $user_id_clause = "	AND	(P.user_id = {$user_id} OR UG.admin_flag = 1) ";
	    } 
	    
	    $sql = "
	    	SELECT		U.user_id, U.username as name
	    	FROM		productset as P,
	    				user as U,
	    				user_group as UG
	    	WHERE		1
	    		AND		P.user_id = U.user_id
	    		AND		U.user_group_id = UG.user_group_id
	    		{$user_id_clause}
	    	GROUP BY	U.user_id
	    ";
	    
	    $result = $this->db->query($sql);
	    
	    return (array) $result->rows;

	}
	
	
	public function getSchoolsWithProducts ($user_id) {
	    
	    if (!$this->isAdmin($user_id)) {
	        $user_id_clause = "	AND	(P.user_id = {$user_id} OR UG.admin_flag = 1) ";
	    } 
	    
	    $sql = "
	    	SELECT		U.user_id, U.username as name
	    	FROM		product as P,
	    				user as U,
	    				user_group as UG
	    	WHERE		1
	    		AND		P.user_id = U.user_id
	    		AND		U.user_group_id = UG.user_group_id
	    		{$user_id_clause}
	    	GROUP BY	U.user_id
	    ";
	    
	    $result = $this->db->query($sql);
	    
	    return (array) $result->rows;

	}	
	
	
	public function getSchoolsWithProductsForProductset ($productset_code) {
	    
	    $this->load->model('user/productset');
	    $owner_user_id = $this->model_user_productset->getOwnerSchoolIDFromCode($productset_code);
	    
	    $user_id_clause = "	AND	(UG.admin_flag = 1 OR P.user_id = {$owner_user_id}) ";	    
	    
	    $sql = "
	    	SELECT		U.user_id, U.username as name
	    	FROM		product as P,
	    				user as U,
	    				user_group as UG
	    	WHERE		1
	    		AND		P.user_id = U.user_id
	    		AND		U.user_group_id = UG.user_group_id
	    		{$user_id_clause}
	    	GROUP BY	U.user_id
	    ";
	    
	    $result = $this->db->query($sql);
	    
	    return (array) $result->rows;

	}
	
	
	public function getSchoolsWithProductsForStore ($store_code, $viewing_user_id) {

	    $owner_user_id = $this->model_user_store->getOwnerSchoolIDFromCode($store_code);
	    
		if ($this->isAdmin($viewing_user_id)) {
		    $user_id_clause = "	AND	(UG.admin_flag = 1 OR P.user_id = '{$owner_user_id}') ";
	    } else {
	        $user_id_clause = "	AND	(UG.admin_flag = 1 OR P.user_id = '{$viewing_user_id}') ";
	    }
	    
	    $sql = "
	    	SELECT		U.user_id, U.username as name
	    	FROM		product as P,
	    				productset_product as PP,
	    				store_productsets as SP,
	    				store as S,
	    				user as U,
	    				user_group as UG
	    	WHERE		1
	    		AND		P.product_id = PP.product_id
	    		AND		PP.productset_id = SP.productset_id
	    		AND		SP.store_id = S.store_id
	    		AND		S.code = '{$store_code}'
	    		AND		P.user_id = U.user_id
	    		AND		U.user_group_id = UG.user_group_id
	    		{$user_id_clause}
	    	GROUP BY	U.user_id
	    ";
	    
	    $result = $this->db->query($sql);
	    
	    return (array) $result->rows;

	}
			
	
	public function getSchoolsWithStores () {
	    
	    $sql = "
	    	SELECT		U.user_id, U.username as name
	    	FROM		".DB_PREFIX."user as U,
	    				".DB_PREFIX."store as S
	    	WHERE		1
	    		AND		S.user_id = U.user_id
	    	GROUP BY	U.user_Id
	    ";
	    
	    $result = $this->db->query($sql);
	    
	    return (array) $result->rows;

	}	
	
	
	public function getStores ($user_id, $godmode=false) {
	    
	    if (!$this->isAdmin($user_id)) {
	        $user_id_clause = "	AND	S.user_id = {$user_id}";
	    }
	    
	    if (!$godmode) {
	        $godmode_clause = " AND S.code != 'ZZZ'";
	    }
	    
	    $sql = "
	    		SELECT		S.*
	    		FROM		store as S
	    		WHERE		1
	    			{$user_id_clause}
	    			{$godmode_clause}
            ORDER BY S.code ASC
	    ";
	    
	    $result = $this->db->query($sql);
	    
	    return (array) $result->rows;
	    
	}
	
	
	public function isAdmin ($user_id) {
	    
	    $sql = "
	    	SELECT		UG.admin_flag
	    	FROM		user as U,
	    				user_group as UG
	    	WHERE		1
	    		AND		U.user_group_id = UG.user_group_id
	    		AND		U.user_id = '{$user_id}'
	    ";
	    
	    $result = $this->db->query($sql);

	    return (boolean) $result->row['admin_flag'];
	    
	}

	
	public function getTotalSchools($data=null) {

      $filter_clause = "";
	   if (isset($data['district_filter'])) {
	       $filter_clause .= " AND district_id = '{$data['district_filter']}' ";
	   }
      $where = "";
      if (isset($data['district_id'])) {
         $where .= " AND district_id = '{$data['district_id']}' ";
      }

      if (isset($data['search'])) {
         foreach ($data['search'] as $key => $value) {
            $where .= " AND " . $key . " like '%" . $value . "%'";
         }
      }

      $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "sps_school` WHERE 1 {$filter_clause} {$where}");
		
		return $query->row['total'];
	}

	public function getTotalSchoolsByGroupId($user_group_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE user_group_id = '" . (int)$user_group_id . "'");
		
		return $query->row['total'];
	}
	
	
	// IF Superadmin, get self and all non-Superadmins ELSE get self
	public function getAssignableSchools ($viewing_user_id, $lock_to_self=false) {
		   
	    if ($lock_to_self || !$this->isAdmin($viewing_user_id)) {
	        $user_id_clause = "	AND	U.user_id = '{$viewing_user_id}' ";
	    }
	    
	    $sql = "
	    	SELECT		U.user_id, U.username as name
	    	FROM		user as U,
	    				user_group as UG
	    	WHERE		1
	    		AND		U.user_group_id = UG.user_group_id
	    		{$user_id_clause}
	    	ORDER BY	UG.user_group_id, U.username
	    ";
	    
	    $result = $this->db->query($sql);

	    return (array) $result->rows;	    
	    
	}
	
    
	public function getDropdownOptions ($selected_id=null, $firstblank=true, $exclude_superadmins=false) {
	    
	    if ($exclude_superadmins) {
	        
	    }
	    
  	    $rows = $this->getSchools(null, $exclude_superadmins);
  	    
  	    foreach ($rows as $row) {
  	        $dropdown_rows[$row['user_id']] = $row['username']." ({$row['firstname']} {$row['lastname']})";
  	    }
  	    
  	    return $this->get_pulldown_options($dropdown_rows, $selected_id, $firstblank);
  	    	    
	}
	
   public function getDistrictIdsForSchoolIds($school_ids) {
      $return_array = array();
      if ($school_ids) {
         $school_list = implode(", ", $school_ids);
   
         $sql = "SELECT district_id FROM sps_school WHERE id IN (" . $school_list . ")";
         $query = $this->db->query($sql);
         foreach ($query->rows as $r) {
            $return_array[] = $r['district_id'];
         }
      }
      return $return_array;
   }

   public function getDistrictIdForSchoolId($school_id) {
      $q = $this->db->query("SELECT district_id FROM sps_school WHERE id = '{$school_id}'");
      if ($q->num_rows) {
         return $q->row['district_id'];
      }
   }

   public function getDistrictNameForSchoolId($school_id) {
      $sql = "select sd.name as district_name from sps_district sd inner join sps_school ss on sd.id=ss.district_id where ss.id='{$school_id}'";
      $query = $this->db->query($sql);
      if ($query->num_rows) {
         return $query->row['district_name'];
      }
      return '';
   }
	
}
?>
