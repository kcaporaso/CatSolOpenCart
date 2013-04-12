<?php

class ModelSPSUser extends Model {
    
    
	public function addUser($data) {
	    
      // since we import data, we must find the max 'user_id' and then add 1 to it for our new
      // user_id.
      $query = $this->db->query("select max(user_id) as new_id from sps_user");
      $new_user_id = $query->row['new_id']+1;
//var_dump($data);
//var_dump($new_user_id);
//exit; 
      $default_cust_group_id = $this->getDefaultCustomerGroupID($_SESSION['store_code']);

      // Pick up the district level shipping, tax features:
      $ship = $this->db->query("SELECT free_shipping, free_freight_over, tax_exempt FROM sps_district WHERE id='{$data['district_id']}'");
      if ($ship->num_rows) {
         $data['free_shipping'] = $ship->row['free_shipping'];
         $data['free_freight_over'] = $ship->row['free_freight_over'];
         $data['tax_exempt'] = $ship->row['tax_exempt'];
      }

		$this->db->query("
			INSERT INTO `" . DB_PREFIX . "sps_user` 
			SET 		username = '" . $this->db->escape(@$data['username']) . "', 
						user_id  = '" . (int)$new_user_id . "', 
                  store_code = '" . $_SESSION['store_code'] . "',
						active = '" . (int)@$data['active'] . "', 
                  state_id = '" . (int)@$data['state_id'] . "',
                  district_id = '" . (int)@$data['district_id'] . "',
                  school_id = '" . (int)@$data['school_id'] . "',
						role_id = '" . (int)@$data['role_id'] . "', 
						firstname = '" . $this->db->escape(@$data['firstname']) . "', 
						lastname = '" . $this->db->escape(@$data['lastname']) . "', 
						password = '" . $this->db->escape(md5(@$data['password'])) . "', 
						email = '" . $this->db->escape(@$data['email']) . "', 
						tax_exempt = '" . (int)@$data['tax_exempt'] . "', 
						tax_exempt_number = '" . $this->db->escape(@$data['tax_exempt_number']) . "', 
						free_shipping = '" . (int)@$data['free_shipping'] . "', 
						free_freight_over = '" . (float)@$data['free_freight_over'] . "', 
						instant_approval = '" . (int)@$data['instant_approval'] . "', 
                  notify_approval_via_email = '" . (int)@$data['notify_approval_via_email'] . "',
						modified_date = NOW(),
                  customer_group_id = '".(int) $default_cust_group_id."',
						create_date = NOW()
		");
		
	}
	
	
	public function editUser($user_id, $data) {
	    
		$this->db->query("
			UPDATE `" . DB_PREFIX . "sps_user` 
			SET 		username = '" . $this->db->escape(@$data['username']) . "', 
						active = '" . (int)@$data['active'] . "', 
                  state_id = '" . (int)@$data['state_id'] . "',
                  district_id = '" . (int)@$data['district_id'] . "',
                  school_id = '" . (int)@$data['school_id'] . "',
						role_id = '" . (int)@$data['role_id'] . "', 
						firstname = '" . $this->db->escape(@$data['firstname']) . "', 
						lastname = '" . $this->db->escape(@$data['lastname']) . "', 
						email = '" . $this->db->escape(@$data['email']) . "', 
						tax_exempt = '" . (int)@$data['tax_exempt'] . "', 
						tax_exempt_number = '" . $this->db->escape(@$data['tax_exempt_number']) . "', 
						free_shipping = '" . (int)@$data['free_shipping'] . "', 
						free_freight_over = '" . (float)@$data['free_freight_over'] . "', 
						instant_approval = '" . (int)@$data['instant_approval'] . "', 
						email = '" . $this->db->escape(@$data['email']) . "',
                  notify_approval_via_email = '" . (int)@$data['notify_approval_via_email'] . "',
						modified_date = NOW()
			WHERE 	user_id = '" . (int)$user_id . "'
		");
		
		if (@$data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "sps_user` SET password = '" . $this->db->escape(md5(@$data['password'])) . "' WHERE user_id = '" . (int)$user_id . "'");
		}
	}
	
	public function deleteUser($user_id) {
	    
		$this->db->query("DELETE FROM `" . DB_PREFIX . "sps_user` WHERE user_id = '" . (int)$user_id . "'");
		
	}
	
	
	public function getUser($user_id) {
	    
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sps_user` WHERE user_id = '" . (int)$user_id . "'");
	
		return $query->row;
		
	}
	
	
	public function getUsers ($data = array(), $exclude_superadmins=true) {
	    
	   if ($exclude_superadmins) {
	       $exclude_superadmins_clause = " AND	role_id != 10000 ";
	   }

      $where = "";
      if (isset($data['search'])) {
         foreach ($data['search'] as $key => $value) {
            if ($key == 'schoolname') {
               // We have to do some work here - go get the id's of all schools with a name like $value.
               $query = $this->db->query("SELECT id FROM `" . DB_PREFIX . "sps_school` WHERE name like '%".$value."%'");
               if ($query->num_rows) {
                  foreach ($query->rows as $row) {
                     $ids[] = $row['id'];
                  }
                  $in_string = implode(",", $ids);
                  $where .= " AND school_id IN ({$in_string}) ";
               }
            } else if ($key == 'role') {
               // We have to do some more work here to get the id's of all the roles that we're searching for ($value).
               $query = $this->db->query("SELECT id FROM `" . DB_PREFIX . "sps_role` WHERE role_name like '%".$value."%'");
               if ($query->num_rows) {
                  foreach ($query->rows as $row) {
                     $ids[] = $row['id'];
                  }
                  $in_string = implode(",", $ids);
                  $where .= " AND role_id IN ({$in_string}) ";
               }
            } else {
               $where .= " AND " . $key . " like '%" . $value . "%'";
            }
         }
      }

      if (isset($data['district_id'])) {
         $where .= " AND district_id = '{$data['district_id']}' ";
      }

      if (isset($data['district_filter'])) {
         $where .= " AND district_id = '{$data['district_filter']}' ";
      }

		$sql = "SELECT * 
			FROM `" . DB_PREFIX . "sps_user`
			WHERE	1
					{$exclude_superadmins_clause}
               {$where}
		";
			
		$sort_data = array(
			'lastname',
         'firstname',
         'username',
			'active',
			'create_date'
		);	
			
		if (in_array(@$data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY lastname";	
		}
			
		if (@$data['order'] == 'DESC') {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
			
		if (isset($data['start']) || isset($data['limit'])) {
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
//var_dump($sql);			
		$query = $this->db->query($sql);
	
		return $query->rows;
		
	}
	
	public function getTotalUsers($data=null, $exclude_superadmins=true) {

	   if ($exclude_superadmins) {
	       $exclude_superadmins_clause = " AND role_id != 10000 ";
	   }

      $where = "";
      if ($data['search']) {
         foreach ($data['search'] as $key => $value) {
            if ($key == 'schoolname') {
               // We have to do some work here - go get the id's of all schools with a name like $value.
               $query = $this->db->query("SELECT id FROM `" . DB_PREFIX . "sps_school` WHERE name like '".$value."%'");
               if ($query->num_rows) {
                  foreach ($query->rows as $row) {
                     $ids[] = $row['id'];
                  }
                  $in_string = implode(",", $ids);
                  $where .= " AND school_id IN ({$in_string}) ";
               }
            } else if ($key == 'role') {
               // We have to do some more work here to get the id's of all the roles that we're searching for ($value).
               $query = $this->db->query("SELECT id FROM `" . DB_PREFIX . "sps_role` WHERE role_name like '".$value."%'");
               if ($query->num_rows) {
                  foreach ($query->rows as $row) {
                     $ids[] = $row['id'];
                  }
                  $in_string = implode(",", $ids);
                  $where .= " AND role_id IN ({$in_string}) ";
               }
            } else {
               $where .= " AND " . $key . " like '" . $value . "%'";
            }
         }   
      }   

      if (isset($data['district_id'])) {
         $where .= " AND district_id = '{$data['district_id']}' ";
      }

      if (isset($data['district_filter'])) {
         $where .= " AND district_id = '{$data['district_filter']}' ";
      }

      $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "sps_user` WHERE 1 {$exclude_superadmins_clause} {$where}");
		
		return $query->row['total'];
	}

   public function getDefaultCustomerGroupID ($store_code) {
     
       $sql = " 
         SELECT      customer_group_id
         FROM     customer_group
         WHERE    1
            AND      store_code = '{$store_code}'
            AND      default_flag = '1'
       ";
     
       $result = $this->db->query($sql);
     
       return $result->row['customer_group_id'];
   }    


   // Grab the Approver and SuperUsers for a school_id.
   // 12/01/2010 : We now need to get the Approvers/SuperUsers for the district the school is in.
   public function getApproversAndSuperUsers($s_id) {
      $users = array();
      // Grab the district_id for the school_id.
      $q = $this->db->query("SELECT district_id FROM sps_school WHERE id='{$s_id}'");
      $d_id = 0;
      if ($q->num_rows) {
         $d_id = $q->row['district_id'];
      }
      if ($s_id != 0 && $d_id) {
         $sql = "SELECT user_id, username, firstname, lastname, role_id FROM " . DB_PREFIX . "sps_user WHERE district_id='{$d_id}' AND role_id IN ('".SPS_APPROVER."','".SPS_SUPERUSER."')";
         $query = $this->db->query($sql);
         foreach ($query->rows as $u) {
            $rn_query = $this->db->query("SELECT role_name FROM sps_role WHERE id='{$u['role_id']}'");
            $u['rolename'] = $rn_query->row['role_name'];
            $users[] = $u;
         }
      }
      return $users;
   }
	
   public function getUserName($u_id) {
      $query = $this->db->query("SELECT firstname, lastname FROM " . DB_PREFIX . "sps_user WHERE user_id='{$u_id}'");
      return $query->row;
   }   
}
?>
