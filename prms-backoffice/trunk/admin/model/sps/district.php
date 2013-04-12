<?php

class ModelSPSDistrict extends Model {
    
	public function addDistrict($data) {
	    
      $default_cust_group_id = $this->getDefaultCustomerGroupID($_SESSION['store_code']);
		$this->db->query("
			INSERT INTO `" . DB_PREFIX . "sps_district` 
			SET 		name = '" . $this->db->escape(@$data['name']) . "', 
                  store_code = '" . $_SESSION['store_code'] . "',
						state_id = '" . (int)@$data['state_id'] . "', 
						active = '" . (int)@$data['active'] . "', 
						free_shipping = '" . $this->db->escape(@$data['free_shipping']) . "', 
						free_freight_over = '" . $this->db->escape(@$data['free_freight_over']) . "', 
                  tax_exempt = '" . (int)@$data['tax_exempt'] . "',
                  customer_group_id = '" . (int)$default_cust_group_id . "',
						discount_1 = '" . (float)@$data['discount_1'] . "', 
						discount_2 = '" . (float)@$data['discount_2'] . "', 
						discount_3 = '" . (float)@$data['discount_3'] . "', 
						discount_4 = '" . (float)@$data['discount_4'] . "', 
                  modified_date = NOW(),
						create_date = NOW()
		");
		
	}
	
	public function editDistrict($district_id, $data) {
		$this->db->query("
			UPDATE `" . DB_PREFIX . "sps_district` 
			SET 		name = '" . $this->db->escape(@$data['name']) . "', 
						state_id = '" . (int)@$data['state_id'] . "', 
						active = '" . (int)@$data['active'] . "', 
						free_shipping = '" . (int)@$data['free_shipping'] . "', 
						free_freight_over = '" . (float)@$data['free_freight_over'] . "', 
                  tax_exempt = '" . (int)@$data['tax_exempt'] . "',
                  customer_group_id = '" . (int)@$data['customer_group_id'] . "',
						discount_1 = '" . (float)@$data['discount_1'] . "', 
						discount_2 = '" . (float)@$data['discount_2'] . "', 
						discount_3 = '" . (float)@$data['discount_3'] . "', 
						discount_4 = '" . (float)@$data['discount_4'] . "', 
                  modified_date = NOW()
			WHERE 	id = '" . (int)$district_id . "'
		");

      // If free_shipping is enabled then I must apply it to all the users in that district.
      if ((int)@$data['free_shipping']) {
         $this->db->query("UPDATE `" . DB_PREFIX . "sps_user` SET free_shipping=1 WHERE district_id='{$district_id}'");
      } else if (!(int)@$data['free_shipping']) {
         $this->db->query("UPDATE `" . DB_PREFIX . "sps_user` SET free_shipping=0 WHERE district_id='{$district_id}'");
      }
      // Also, update free_freight_over X
      $this->db->query("UPDATE `" . DB_PREFIX . "sps_user` SET free_freight_over='{$data['free_freight_over']}' WHERE district_id='{$district_id}'");

      // Also update customer_group_id for each user.
      $this->db->query("UPDATE `" . DB_PREFIX . "sps_user` SET customer_group_id='{$data['customer_group_id']}' WHERE district_id='{$district_id}'");

      // If tax_exemption is enabled then I must apply it to all the users in that district.
      if ((int)@$data['tax_exempt']) {
         $this->db->query("UPDATE `" . DB_PREFIX . "sps_user` SET tax_exempt=1 WHERE district_id='{$district_id}'");
      } else if (!(int)@$data['tax_exempt']) {
         $this->db->query("UPDATE `" . DB_PREFIX . "sps_user` SET tax_exempt=0 WHERE district_id='{$district_id}'");
      }
	}
	
	
	public function deleteDistrict($d_id) {
	    
		$this->db->query("DELETE FROM `" . DB_PREFIX . "sps_district` WHERE id = '" . (int)$d_id . "'");
		
	}
	
	
	public function getDistrict($d_id) {
	    
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sps_district` WHERE id = '" . (int)$d_id . "'");
	
		return $query->row;
		
	}
	
	
	public function getDistricts ($data = array(), $exclude_superadmins=false) {
	    
	   if ($exclude_superadmins) {
	       $exclude_superadmins_clause = " AND	role_id != 10000 ";
	   }

      $where = "";
      if (isset($data['district_id'])) {
         $where .= " AND id='{$data['district_id']}' ";
      }

		$sql = "
			SELECT * 
			FROM `" . DB_PREFIX . "sps_district`
			WHERE	1
					{$exclude_superadmins_clause}
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

	
	public function getTotalDistricts($data=null, $exclude_districts=false) {

	   if ($exclude_districts) {
	       $exclude_superadmins_clause = " AND role_id != 10000 ";
	   }

      $where = "";
      if (isset($data['district_id'])) {
         $where .= " AND id = '{$data['district_id']}' ";
      }

      $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "sps_district` WHERE 1 {$exclude_superadmins_clause}");
		
		return $query->row['total'];
	}

   public function getDefaultCustomerGroupID ($store_code) {
     
       $sql = " 
         SELECT customer_group_id
         FROM   customer_group
         WHERE  1
           AND  store_code = '{$store_code}'
           AND  default_flag = '1'
       ";
     
       $result = $this->db->query($sql);
     
       return $result->row['customer_group_id'];
   }    
}
?>
