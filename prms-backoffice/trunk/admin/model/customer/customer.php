<?php


class ModelCustomerCustomer extends Model {
    
    
	public function addCustomer ($store_code, $data) {
      	
		// MODIFIED for Customer Group module
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET store_code = '{$store_code}', firstname = '" . $this->db->escape(@$data['firstname']) . "', lastname = '" . $this->db->escape(@$data['lastname']) . "', email = '" . $this->db->escape(@$data['email']) . "', telephone = '" . $this->db->escape(@$data['telephone']) . "', fax = '" . $this->db->escape(@$data['fax']) . "', newsletter = '" . (int)@$data['newsletter'] . "', password = '" . $this->db->escape(md5(@$data['password'])) . "', customer_group_id = '" . (int)@$data['customer_group_id'] . "', status = '" . (int)@$data['status'] . "', date_added = NOW(), tax_id = '" . $this->db->escape(@$data['tax_id']) . "', tax_exempt = '" . (int)@$data['tax_exempt'] . "', schoolname = '" . $this->db->escape(@$data['schoolname']) . "'");      	
	
	}
	
	
	public function editCustomer ($store_code, $customer_id, $data) {

		// MODIFIED for Customer Group module		
		$this->db->query("
			UPDATE " . DB_PREFIX . "customer 
			SET 		firstname = '" . $this->db->escape(@$data['firstname']) . "', 
						lastname = '" . $this->db->escape(@$data['lastname']) . "', 
						email = '" . $this->db->escape(@$data['email']) . "', 
						telephone = '" . $this->db->escape(@$data['telephone']) . "', 
						fax = '" . $this->db->escape(@$data['fax']) . "', 
						newsletter = '" . (int)@$data['newsletter'] . "', 
						customer_group_id = '" . (int)@$data['customer_group_id'] . "', 
						status = '" . (int)@$data['status'] . "',
                  tax_id = '" . $this->db->escape(@$data['tax_id']) . "', 
                  schoolname = '" . $this->db->escape(@$data['schoolname']) . "', 
                  tax_exempt = '" . (int)(@$data['tax_exempt']) . "',
                  discount_1 = '" . (int)(@$data['discount_1']) . "',
                  discount_2 = '" . (int)(@$data['discount_2']) . "',
                  discount_3 = '" . (int)(@$data['discount_3']) . "',
                  discount_4 = '" . (int)(@$data['discount_4']) . "'
			WHERE 		1
				AND		customer_id = '" . (int)$customer_id . "'
				AND		store_code = '{$store_code}'
		");
			
      	if (@$data['password']) {
        	$this->db->query("
        		UPDATE " . DB_PREFIX . "customer 
        		SET 		password = '" . $this->db->escape(md5(@$data['password'])) . "' 
        		WHERE 		1
        			AND		customer_id = '" . (int)$customer_id . "'
        			AND		store_code = '{$store_code}'
        	");
      	}
      	
      // Potentially, we may have updated the customer address.
      if (@$data['address_id']) { 
         $address_sql = "UPDATE " . DB_PREFIX . "address
                         SET company = '" . $this->db->escape(@$data['company']) . "',
                             address_1 = '" . $this->db->escape(@$data['address_1']) . "',                      
                             address_2 = '" . $this->db->escape(@$data['address_2']) . "',                      
                             postcode = '" . $this->db->escape(@$data['postcode']) . "',                      
                             city = '" . $this->db->escape(@$data['city']) . "',                      
                             country_id = '" . $this->db->escape(@$data['country_id']) . "',                      
                             zone_id = '" . $this->db->escape(@$data['zone_id']) . "'                     
                         WHERE 1
                           AND address_id = '{$data['address_id']}'
                           AND customer_id = '{$customer_id}'";
         $this->db->query($address_sql);
      }
	  
	  // Asked to Notify Customer; Better do it.
	  if(@$data['notify_user_of_update']){
		  $this->notifyCustomerOfUpdate($data);
	  }
	}

   /**
    * addCustomerCategoryDiscount(id, array())
    *
    **/
   public function addCustomerCategoryDiscounts($customer_id, $customercategorydiscount, $store_code)
   {
      // Let's delete all the discounts and re-add to reduce code load.
      $this->db->query("DELETE FROM " . DB_PREFIX . "customer_categories WHERE customer_id='{$customer_id}'");

      // Now add in the discounts that just were submitted.
      foreach ($customercategorydiscount as $categorydiscount):

         //echo stripslashes($categorydiscount['category_id']);
         //$this->db->query("INSERT INTO " . DB_PREFIX . "customer_categories SET customer_id = '{$customer_id}', category_id = '" . $this->db->escape(stripslashes($categorydiscount['category_id'])) . "', discount_percent = '" . $this->db->escape(@$categorydiscount['discount_percent']) . "'");     
         $sql = "INSERT INTO " . DB_PREFIX . "customer_categories SET customer_id = '{$customer_id}', category_id = '" . $this->db->escape(stripslashes($categorydiscount['category_id'])) . "', discount_percent = '" . $this->db->escape(@$categorydiscount['discount_percent']) . "'";
         $this->db->query($sql);
      endforeach;
      //echo $sql;
      //exit;
   } 	

   /**
    *
    * KMC : 08/05/2010 : Discovered discounts were not getting deleted when you delete the last one.
    * deleteCustomerCategoryDiscounts(customer_id)
    *
    */
   public function deleteCustomerCategoryDiscounts($customer_id) {
      $sql = "DELETE FROM " . DB_PREFIX . "customer_categories WHERE customer_id='{$customer_id}'";
      $this->db->query($sql);
   }

   public function getCustomerCategoryDiscounts($customer_id)
   {
      $q = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_categories WHERE customer_id = '{$customer_id}'");
      return $q->rows;
   }

	public function getCustomerStoreCode ($customer_id) {
	    
	    return $this->db->get_column('customer', 'store_code', "customer_id = '{$customer_id}'");
	    
	}	
	
	
	public function deleteCustomer ($store_code, $customer_id) {
	    
	    if ($this->getCustomerStoreCode($customer_id) == $store_code) {
    	    
    		$this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
    		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");
    	
	    }
		
	}
	
	
	public function getCustomer ($store_code, $customer_id) {
	    
		$query = $this->db->query("
			SELECT DISTINCT * 
			FROM " . DB_PREFIX . "customer 
			WHERE 		1
				AND		customer_id = '" . (int)$customer_id . "'
				AND		store_code = '{$store_code}'
		");
	
		return $query->row;
		
	}
	
   public function getCustomerAddress($customer_id) {
      $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "address
                                 WHERE 1
                                 AND customer_id='" . (int)$customer_id . "'");
      return $query->row;
   }	
	
	public function getCustomers ($store_code, $data = array()) {
	    
		$sql = "SELECT *, CONCAT(firstname, ' ', lastname) AS name FROM " . DB_PREFIX . "customer";

		$implode = array();
		
		$implode[] = "store_code = '{$_SESSION['store_code']}'";
		
		if (isset($data['name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['name']) . "%'";
		}
		
		if (isset($data['status'])) {
			$implode[] = "status = '" . (int)$data['status'] . "'";
		}			
		
		if (isset($data['discount_1'])) {
			$implode[] = "discount_1 = '" . (int)$data['discount_1'] . "'";
		}			
		
		if (isset($data['date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['date_added']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'name',
			'status',
			'discount_1',
			'date_added'
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
	

	public function getCustomersByNewsletter ($store_code) {
	    
		$query = $this->db->query("
			SELECT * 
			FROM " . DB_PREFIX . "customer 
			WHERE 		1
				AND		store_code = '{$store_code}' 
				AND 	newsletter = '1' 
			ORDER BY 	firstname, lastname, email
		");
	
		return $query->rows;
		
	}
	
	
	public function getTotalCustomers ($store_code, $data = array()) {
	    
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer";
		
		$implode[] = "store_code = '{$store_code}'";
		
		if (isset($data['name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['name']) . "%'";
		}
		
		if (isset($data['status'])) {
			$implode[] = "status = '" . (int)$data['status'] . "'";
		}		
		
		if (isset($data['discount_1'])) {
			$implode[] = "discount_1 = '" . (int)$data['discount_1'] . "'";
		}			
		
		if (isset($data['date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['date_added']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
				
		$query = $this->db->query($sql);
				
		return $query->row['total'];
		
	}
	
	
	//apparently this is not used
	public function getTotalAddressesByCustomerId ($customer_id) {
	    
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");
		
		return $query->row['total'];
		
	}
	
	
	public function getTotalAddressesByCountryId ($country_id) {
	    
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE country_id = '" . (int)$country_id . "'");
		
		return $query->row['total'];
		
	}	
	
	
	public function getTotalAddressesByZoneId ($zone_id) {
	    
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE zone_id = '" . (int)$zone_id . "'");
		
		return $query->row['total'];
		
	}
	
	
	// Customer Group module	
	public function getTotalCustomersByGroupId ($customer_group_id) {
	
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE customer_group_id = '" . (int)$customer_group_id . "'");
	
		return $query->row['total'];
	
	}	
	// end customer group
	
	
	public function getTotalCustomersByEmail($store_code, $email, $ignore_customer_id=null) {
	    
	    if ($ignore_customer_id) {
	       $ignore_customer_id_clause = " AND customer_id != '{$ignore_customer_id}' ";
	    }
	    
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE store_code = '{$store_code}' AND email = '" . $this->db->escape($email) . "' {$ignore_customer_id_clause} ");
		
		return $query->row['total'];
		
	}
	
	private function notifyCustomerOfUpdate($data){
         $language = new Language($query->row['language']);
         $language->load('customer/customer');
     
         $subject = sprintf($language->get('mail_subject'), $language->clean_store_name($this->config->get('config_store')));
     
         $message  = $language->get('mail_discount_update') . "\n\n";
		 $message .= $language->get('mail_discount_header') . "\n\n";
         $message .= sprintf($language->get('mail_discount_1'),$data['discount_1']) . "\n";
         $message .= sprintf($language->get('mail_discount_2'),$data['discount_2']) . "\n\n";
            
         $message .= sprintf($language->get('mail_footer'),$this->config->get('config_telephone'),$language->clean_store_name($this->config->get('config_store')));
     
         $mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password')), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout'));
         
		 $mail->setTo($data['email']);
         $mail->setFrom($this->config->get('config_email'));
         $mail->setSender($language->clean_store_name($this->config->get('config_store')));
         $mail->setSubject($subject);
         $mail->setText($message);
         $mail->send();
	}
			
}
?>
