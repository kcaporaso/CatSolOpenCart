<?php

class ModelSPSChain extends Model {
    
	public function addChain($data) {
	    
		$this->db->query("
			INSERT INTO `" . DB_PREFIX . "sps_chain` 
			SET 		name = '" . $this->db->escape(@$data['name']) . "', 
						user_id_1 = '" . (int)@$data['user_id_1'] . "', 
						user_id_2 = '" . (int)@$data['user_id_2'] . "', 
						user_id_3 = '" . (int)@$data['user_id_3'] . "', 
						user_id_4 = '" . (int)@$data['user_id_3'] . "', 
						user_id_5 = '" . (int)@$data['user_id_3'] . "', 
						school_id = '" . (int)@$data['school_id'] . "', 
                  store_code = '" . $_SESSION['store_code'] . "',
						active = '" . (int)@$data['active'] . "', 
						create_date = NOW(),
                  modified_date = NOW()
		");
		
	}
	
	public function editChain($chain_id, $data) {

		$this->db->query("
			UPDATE `" . DB_PREFIX . "sps_chain` 
			SET 		name = '" . $this->db->escape(@$data['name']) . "', 
						user_id_1 = '" . (int)@$data['user_id_1'] . "', 
						user_id_2 = '" . (int)@$data['user_id_2'] . "', 
						user_id_3 = '" . (int)@$data['user_id_3'] . "', 
						user_id_4 = '" . (int)@$data['user_id_3'] . "', 
						user_id_5 = '" . (int)@$data['user_id_3'] . "', 
						school_id = '" . (int)@$data['school_id'] . "', 
                  store_code = '" . $_SESSION['store_code'] . "',
						active = '" . (int)@$data['active'] . "', 
                  modified_date = NOW()
			WHERE 	id = '" . (int)$chain_id . "'
		");
		
	}
	
	
	public function deleteChain($c_id) {
	    
		$this->db->query("DELETE FROM `" . DB_PREFIX . "sps_chain` WHERE id = '" . (int)$c_id . "'");
		
	}
	
	
	public function getChain($c_id) {
	    
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sps_chain` WHERE id = '" . (int)$c_id . "'");
	
		return $query->row;
		
	}
	
	
	public function getChains ($data = array(), $exclude_superadmins=false) {
	    
      $where = " 1 ";
      if (isset($data['school_id'])) {
         $where .= " AND school_id='{$data['school_id']}' ";
      } else if (isset($data['district_filter'])) {
         $where .= " AND school_id IN (SELECT id FROM sps_school WHERE district_id='{$data['district_filter']}') ";
      }

		$sql = "
			SELECT * 
			FROM `" . DB_PREFIX . "sps_chain`
			WHERE	{$exclude_superadmins_clause}
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
	
	
	public function getTotalChains($data=null, $exclude_chains=false) {

      //var_dump($data);
      $where = " 1 ";
      if (isset($data['chain_id'])) {
         $where .= " AND id = '{$data['chain_id']}' ";
      } else if (isset($data['district_filter'])) {
         $where .= " AND school_id IN (SELECT id FROM sps_school WHERE district_id='{$data['district_filter']}') ";
      }

      $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "sps_chain` WHERE " . $where);
		
		return $query->row['total'];
	}

   /**
    *  Here we are gathering all the approvers for a school_id, this is based on the approval chain
    *  for that school_id.
    *
    */
   public function getApprovalChainUsers($school_id) {

      $sql = "SELECT * FROM sps_chain WHERE school_id='{$school_id}'";
      $chain = $this->db->query($sql);
      $users = array();
      if ($chain->num_rows) {
         foreach ($chain->row as $k => $v) {
            if (strstr($k, 'user_id_')) {
               if ($v != 0 && $v != -1) {
                  $user = $this->db->query("SELECT u.user_id, u.username, u.firstname, u.lastname, u.role_id, u.email, u.notify_approval_via_email, r.role_name FROM sps_user u INNER JOIN sps_role r ON u.role_id = r.id WHERE user_id='{$v}'"); 
                  if ($user->num_rows) {
                     $users[] = $user->row;
                  }
               }
            }
         }
      }
      return $users;
   }

   /**
    *
    *
    *
    */
   public function whoApprovesNext($school_id) {
      // Pull this chain, then determine who goes next, if anyone.
      $approvers = $this->getApprovalChainUsers($school_id);
      $just_approved_user_id = $this->user->getSPS()->getUserID();
      $just_approved_role_id = $this->user->getSPS()->getRoleID();
      $chain_info = $this->db->query("SELECT * FROM sps_chain WHERE school_id='{$school_id}'");

      if ($chain_info->num_rows) {
         foreach ($chain_info->row as $k => $v) {
            if (strstr($k, 'user_id_')) {
               if ($just_approved_user_id == $v) {
                  // determine which pos in the chain this is:
                  $_pos = strrpos($k, '_');
                  $chain_pos = substr($k,$_pos+1);
                  //echo $chain_pos;
                  if ($approvers[$chain_pos]['role_id'] < $just_approved_role_id) {
                     //echo ' we have more in the chain..';
                     return $approvers[$chain_pos];
                  }
               }
            }
         }
      }
   }
}
?>
