<?php
require_once(DIR_SYSTEM . 'library/spsuser.php');

class User {
	private $user_id;
	private $username;
   private $membershiptier;
   private $is_sps;
  	private $permission = array();
   private $sps;

  	public function __construct() {
		$this->db = Registry::get('db');
		$this->request = Registry::get('request');
		$this->session = Registry::get('session');

    	if (isset($this->session->data['user_id'])) {
			$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$this->session->data['user_id'] . "'");
			
			if ($user_query->num_rows) {
				$this->user_id = $user_query->row['user_id'];
				$this->username = $user_query->row['username'];
				$this->firstname = $user_query->row['firstname'];
            $this->membershiptier = $user_query->row['membershiptier_id'];
				
      		$this->db->query("UPDATE " . DB_PREFIX . "user SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int)$this->session->data['user_id'] . "'");

      		$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");
				
      		$unserialized_user_group_query_row_permissions = (array) unserialize($user_group_query->row['permission']);
//print_r($user_group_query->row['permission']);
	  			foreach ($unserialized_user_group_query_row_permissions as $key => $value) {
	    			$this->permission[$key] = $value;
	  			}
            // SPS
            if ($this->membershiptier == PLATINUM) {
               $this->is_sps = true;
               $this->sps = new spsUser($this->user_id);
//               var_dump($this->sps->sps_permission);exit;
            } else {
               $this->is_sps = false;
            }
			} else {
            // SPS
            // Let's check for SPS system access.
//          var_dump($this->session->data);
    	      $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_user WHERE user_id = '" . $this->session->data['user_id'] . "'");
//          var_dump($user_query);
            if ($user_query->num_rows) {
				   $this->user_id = $user_query->row['user_id'];
				   $this->username = $user_query->row['username'];
				   $this->firstname = $user_query->row['firstname'];
				   $this->lastname = $user_query->row['lastname'];
               $this->is_sps = true;
               $this->sps = new spsUser($this->user_id);
            } else {
				   $this->logout();
            }
			}
//         var_dump($this->getSPS()); exit;
    	}
//var_dump($this->permission);
//exit;
  	}
		
  	public function login($username, $password) {
    	$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND password = '" . $this->db->escape(md5($password)) . "'");

    	if ($user_query->num_rows) {
			$this->session->data['user_id'] = $user_query->row['user_id'];
			
			$this->user_id = $user_query->row['user_id'];
			$this->username = $user_query->row['username'];			

      		$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

      		$unserialized_user_group_query_row_permissions = (array) unserialize($user_group_query->row['permission']);
	  		foreach ($unserialized_user_group_query_row_permissions as $key => $value) {
	    		$this->permissions[$key] = $value;
	  		}
		
      		return TRUE;
    	} else {
         // SPS.
         // OK, check to see if this user is in the sps system.
    	   $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_user WHERE username = '" . $this->db->escape($username) . "' AND password = '" . $this->db->escape(md5($password)) . "'");

         if ($user_query->num_rows) {
			   $this->session->data['user_id'] = $user_query->row['user_id'];
			   $this->user_id = $user_query->row['user_id'];
      	   $this->username = $user_query->row['username'];			
            $role = $user_query->row['role_id'];
            if ($role == SPS_ADMIN ||
                $role == SPS_SUPERUSER ||
                $role == SPS_APPROVER) {
               return TRUE;
            } else { 
			      $this->session->data['user_id'] = null;
			      $this->user_id = null;
      	      $this->username = null;
               return FALSE; 
            }
         } 
      	return FALSE;
    	}
  	}
  	
  	public function logout() {
  	    
		unset($this->session->data['user_id']);
	
		$this->user_id = '';
		$this->username = '';
		
		// GC :
		unset($_SESSION);
		session_destroy();
		
  	}

  	public function hasPermission($key, $value) {
//var_dump($this->permission);
//exit;
      if ($this->isSPS()) {
         return $this->getSPS()->hasPermission($key, $value);
      }
    	if (isset($this->permission[$key])) {
	  		return in_array($value, $this->permission[$key]);
		} else {
	  		return FALSE;
		}
  	}
  
  	public function isLogged() {
    	return $this->user_id;
  	}
  
  	public function getId() {
    	return $this->user_id;
  	}
	
  	public function getUserName() {
    	return $this->username;
  	}
  	
  	public function getFirstName() {
    	return $this->firstname;
  	}

   public function getLastName() {
      return $this->lastname;
   }

   public function getRoleName() {
      if ($this->getSPS()) { return $this->getSPS()->getRoleName(); }
   }
  	  	
   public function isSPS() {
      return $this->is_sps;
   }

   public function getSPS() {
      return $this->sps;
   }
}
?>
