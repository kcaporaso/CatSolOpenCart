<?php


class ModelAccountCustomer extends Model {
    
    
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
    
    
	public function addCustomer ($store_code, $data) {
	    
	    if ($default_customer_group_id = $this->getDefaultCustomerGroupID($store_code)) {
	        $default_customer_group_id_phrase = " customer_group_id = '{$default_customer_group_id}', ";
	    }
	    
      	$this->db->query("
      		INSERT INTO " . DB_PREFIX . "customer 
      		SET 		store_code = '{$store_code}', 
      					firstname = '" . $this->db->escape(@$data['firstname']) . "', 
      					lastname = '" . $this->db->escape(@$data['lastname']) . "', 
      					email = '" . $this->db->escape(@$data['email']) . "', 
      					telephone = '" . $this->db->escape(@$data['telephone']) . "', 
      					fax = '" . $this->db->escape(@$data['fax']) . "', 
      					password = '" . $this->db->escape(md5(@$data['password'])) . "', 
      					newsletter = '" . $this->db->escape(@$data['newsletter']) . "', 
      					status = '1',
      					tax_id = '" . $this->db->escape(@$data['taxid']) . "', 
      					schoolname = '" . $this->db->escape(@$data['schoolname']) . "', 
      					{$default_customer_group_id_phrase}
      					date_added = NOW()
      	");
      	
		$customer_id = $this->db->getLastId();
			
      	$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape(@$data['firstname']) . "', lastname = '" . $this->db->escape(@$data['lastname']) . "', company = '" . $this->db->escape(@$data['company']) . "', address_1 = '" . $this->db->escape(@$data['address_1']) . "', address_2 = '" . $this->db->escape(@$data['address_2']) . "', city = '" . $this->db->escape(@$data['city']) . "', postcode = '" . $this->db->escape(@$data['postcode']) . "', country_id = '" . (int)@$data['country_id'] . "', zone_id = '" . (int)@$data['zone_id'] . "'");
		
		$address_id = $this->db->getLastId();

      	$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");	
	
	}
	
	
	public function editCustomer ($data) {
      if ($this->customer->isSPS()) {
   		$sql="UPDATE " . DB_PREFIX . "sps_user 
   			SET 	firstname = '" . $this->db->escape(@$data['firstname']) . "', 
   					lastname = '" . $this->db->escape(@$data['lastname']) . "', 
   					email = '" . $this->db->escape(@$data['email']) . "', 
   					telephone = '" . $this->db->escape(@$data['telephone']) . "', 
   					fax = '" . $this->db->escape(@$data['fax']) . "' ,
   					tax_exempt_number = '" . $this->db->escape(@$data['taxid']) . "' 
   			WHERE 	user_id = '" . (int)$this->customer->getId() . "'
   		";
      } else {
   		$sql="UPDATE " . DB_PREFIX . "customer 
   			SET 	firstname = '" . $this->db->escape(@$data['firstname']) . "', 
   					lastname = '" . $this->db->escape(@$data['lastname']) . "', 
   					email = '" . $this->db->escape(@$data['email']) . "', 
   					telephone = '" . $this->db->escape(@$data['telephone']) . "', 
   					fax = '" . $this->db->escape(@$data['fax']) . "' ,
   					tax_id = '" . $this->db->escape(@$data['taxid']) . "' ,
   					schoolname = '" . $this->db->escape(@$data['schoolname']) . "'
   			WHERE 	customer_id = '" . (int)$this->customer->getId() . "'
   		";
      }

      //echo $sql;
		$this->db->query($sql);
	}
	

	public function editPassword ($store_code, $email, $password) {
	    
      if ($this->customer->isSPS()) {
         $this->db->query("UPDATE " . DB_PREFIX . "sps_user SET password = '" . $this->db->escape(md5($password)) . "' WHERE store_code = '{$store_code}' AND email = '" . $this->db->escape($email) . "'");
      } else {
         $this->db->query("UPDATE " . DB_PREFIX . "customer SET password = '" . $this->db->escape(md5($password)) . "' WHERE store_code = '{$store_code}' AND email = '" . $this->db->escape($email) . "'");
      }
	
	}

	
	public function editNewsletter ($customer_id, $newsletter) {
	    
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$customer_id . "'");
	
	}
	
			
	public function getCustomer ($customer_id) {
	   $query = null;
      // SPS:
      if ($this->customer->isSPS()) {
		   $query = $this->db->query("SELECT u.*, u.tax_exempt_number as tax_id, s.name as schoolname FROM " . DB_PREFIX . "sps_user u INNER JOIN sps_school s ON s.id = u.school_id WHERE user_id = '" . (int)$customer_id . "'");
      } else {
		   $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
      }
		
		return $query->row;
	
	}
	
	
	public function getTotalCustomersByEmail ($store_code, $email) {
	    
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE store_code = '{$store_code}' AND email = '" . $this->db->escape($email) . "'");
		
		return $query->row['total'];
	
	}
	
	
}


?>
