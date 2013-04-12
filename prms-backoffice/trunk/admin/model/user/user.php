<?php

class ModelUserUser extends Model {
    
    
	public function addUser($data) {
	    
		$this->db->query("
			INSERT INTO `" . DB_PREFIX . "user` 
			SET 		username = '" . $this->db->escape(@$data['username']) . "', 
						password = '" . $this->db->escape(md5(@$data['password'])) . "', 
						firstname = '" . $this->db->escape(@$data['firstname']) . "', 
						lastname = '" . $this->db->escape(@$data['lastname']) . "', 
						email = '" . $this->db->escape(@$data['email']) . "', 
						user_group_id = '" . (int)@$data['user_group_id'] . "', 
						status = '" . (int)@$data['status'] . "', 
						membershiptier_id = ".(int)$data['membershiptier_id'].",
						date_added = NOW()
		");
		
	}
	
	
	public function editUser($user_id, $data) {
	    
		$this->db->query("
			UPDATE `" . DB_PREFIX . "user` 
			SET 		username = '" . $this->db->escape(@$data['username']) . "', 
						firstname = '" . $this->db->escape(@$data['firstname']) . "', 
						lastname = '" . $this->db->escape(@$data['lastname']) . "', 
						email = '" . $this->db->escape(@$data['email']) . "', 
						user_group_id = '" . (int)@$data['user_group_id'] . "', 
						status = '" . (int)@$data['status'] . "',
						membershiptier_id = ".(int)$data['membershiptier_id']."
			WHERE 		user_id = '" . (int)$user_id . "'
		");
		
		if (@$data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "user` SET password = '" . $this->db->escape(md5(@$data['password'])) . "' WHERE user_id = '" . (int)$user_id . "'");
		}
		
	}
	
	
	public function deleteUser($user_id) {
	    
		$this->db->query("DELETE FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");
		
	}
	
	
	public function getUser($user_id) {
	    
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");
	
		return $query->row;
		
	}
	
	
	public function getUsers ($data = array(), $exclude_superadmins=false) {
	    
	    if ($exclude_superadmins) {
	        $exclude_superadmins_clause = " AND	user_group_id != 1 ";
	    }
	    
		$sql = "
			SELECT 		* 
			FROM `" . DB_PREFIX . "user`
			WHERE		1
					{$exclude_superadmins_clause}
		";
			
		$sort_data = array(
			'username',
			'status',
			'date_added'
		);	
			
		if (in_array(@$data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY username";	
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
	
	
	public function getUsersWithProductsets ($user_id) {
	    
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
	
	
	public function getUsersWithProducts ($user_id) {
	    
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
	
	
	public function getUsersWithProductsForProductset ($productset_code) {
	    
	    $this->load->model('user/productset');
	    $owner_user_id = $this->model_user_productset->getOwnerUserIDFromCode($productset_code);
	    
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
	
	
	public function getUsersWithProductsForStore ($store_code, $viewing_user_id) {

	    $owner_user_id = $this->model_user_store->getOwnerUserIDFromCode($store_code);
	    
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
			
	
	public function getUsersWithStores () {
	    
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
	    
       // SPS:
       if ($this->user->isSPS()) {
          return $this->user->getSPS()->isAdmin();
       }

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

	
	public function getTotalUsers() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user`");
		
		return $query->row['total'];
	}

	public function getTotalUsersByGroupId($user_group_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE user_group_id = '" . (int)$user_group_id . "'");
		
		return $query->row['total'];
	}
	
	
	// IF Superadmin, get self and all non-Superadmins ELSE get self
	public function getAssignableUsers ($viewing_user_id, $lock_to_self=false) {
		   
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
	    
  	    $rows = $this->getUsers(null, $exclude_superadmins);
  	    
  	    foreach ($rows as $row) {
  	        $dropdown_rows[$row['user_id']] = $row['username']." ({$row['firstname']} {$row['lastname']})";
  	    }
  	    
  	    return $this->get_pulldown_options($dropdown_rows, $selected_id, $firstblank);
  	    	    
	}
	
	
}
?>
