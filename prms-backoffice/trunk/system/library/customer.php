<?php

require_once(DIR_SYSTEM . 'library/spsuser.php');

final class Customer {
	private $customer_id;
	private $firstname;
	private $lastname;
	private $email;
	private $telephone;
	private $fax;
	private $newsletter;
	private $address_id;
	private $address = array();
   private $tax_exempt = 0;
   private $is_sps = false;
   private $sps = null;
   private $discount_levels = array();
	
	// Customer Group module
	private $customer_group_id;
	private $cust_tax_class_id;
	private $cust_group_discount;
	// end customer group	

   // Customer category discounts : KMC
   private $category_discounts = array();
	
  	public function __construct() {
		$this->db = Registry::get('db');
		$this->request = Registry::get('request');
		$this->session = Registry::get('session');
				
		if (isset($this->session->data['customer_id'])) { 
         //var_dump('x'.$this->session->data['shipping_address_id']);
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "' AND status = '1'");
			
			if ($customer_query->num_rows) {
				$this->customer_id = $customer_query->row['customer_id'];
				$this->customer_group_id = $customer_query->row['customer_group_id']; // Customer Group module				
				$this->firstname = $customer_query->row['firstname'];
				$this->lastname = $customer_query->row['lastname'];
				$this->email = $customer_query->row['email'];
				$this->telephone = $customer_query->row['telephone'];
				$this->fax = $customer_query->row['fax'];
				$this->newsletter = $customer_query->row['newsletter'];
				$this->address_id = $customer_query->row['address_id'];
            $this->tax_exempt = $customer_query->row['tax_exempt'];
			
				$address_query = $this->db->query("SELECT *, c.name AS country, z.name AS zone FROM " . DB_PREFIX . "address a LEFT JOIN " . DB_PREFIX . "country c ON a.country_id = c.country_id LEFT JOIN " . DB_PREFIX . "zone z ON a.zone_id = z.zone_id WHERE a.customer_id = '" . (int)$this->session->data['customer_id'] . "'");
						 
				foreach ($address_query->rows as $result) {
					$this->address[$result['address_id']] = array(
						'firstname'      => $result['firstname'],
						'lastname'       => $result['lastname'],
						'company'        => $result['company'],
						'address_1'      => $result['address_1'],
						'address_2'      => $result['address_2'],
						'postcode'       => $result['postcode'],
						'city'           => $result['city'],
						'country_id'     => $result['country_id'],
						'zone_id'        => $result['zone_id'],
						'iso_code_2'     => $result['iso_code_2'],
						'iso_code_3'     => $result['iso_code_3'],
						'code'           => $result['code'],
						'zone'           => $result['zone'],
						'country'        => $result['country'],	
						'address_format' => $result['address_format']
					);
				}

				// Customer Group module
				$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . $this->customer_group_id . "'");
			   $store_code = '';	
				if ($customer_query->num_rows) {
					$this->cust_tax_class_id = $customer_query->row['group_tax_class_id'];
					$this->cust_group_discount = $customer_query->row['group_discount'];
               // Grab the store code: KMC 08/05/2010
               $store_code = $customer_query->row['store_code'];
				}
				// end customer group

            // Get our BND discount levels.
            $this->setupDiscountLevels();
           
            // Customer category discounts : KMC
            $this->category_discounts = array();
            unset($this->category_discounts);
            $category_dis_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_categories WHERE customer_id ='" . $this->customer_id . "'");
            foreach ($category_dis_query->rows as $discount) {
               // If we see that they have All Categories chosen (category_id = '0') then we have to add all categories
               // to the array below.
               if ($discount['category_id'] == '0') {
                  // get all categories for store_code.
                  //echo 'have 0';
                  //echo 'sc:'.$store_code;
                  $all_cats = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category WHERE store_code='{$store_code}' and phrasekey != 'Gift Certificates'");
                  //echo $all_cats->num_rows;
                  if ($all_cats->num_rows) {
                    // Add them in...
                    //print_r($all_cats);
                    foreach ($all_cats->rows as $cat) {
                       $this->category_discounts[] = array($cat['category_id'] => $discount['discount_percent']);
                       //echo 'added all cat';
                    }
                  }
               }
               $this->category_discounts[] = array($discount['category_id'] => $discount['discount_percent']);

               // 6/3/2010 : We will now include all the children if they have any.
               $q = "SELECT * FROM " . DB_PREFIX . "category WHERE parent_id='" . $discount['category_id'] . "'";
               $children_cats = $this->db->query($q);
               foreach ($children_cats->rows as $child) {
                  $this->category_discounts[] = array($child['category_id'] => $discount['discount_percent']);
               }
            }
            //echo '<!--'.print_r($this->category_discounts).'-->';
            // end category discounts
			
      	   $this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . $this->db->escape(serialize($this->session->data['cart'])) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "'");
			} else {
            // Since we're likely coming through here from the backoffice processing; let's check the sps
            // for the customer_id.
            // KMC : 10/11/2010
			   $customer_q_bo = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_user WHERE user_id = '" . (int)$this->session->data['customer_id'] . "' AND active = '1'");
			   if ($customer_q_bo->num_rows) {
				   $this->user_id = $customer_q_bo->row['user_id'];
               $this->is_sps = true;
               $this->sps = new SPSUser($this->user_id);
   				$this->username = $customer_q_bo->row['username'];
   				$this->firstname = $customer_q_bo->row['firstname'];
   				$this->lastname = $customer_q_bo->row['lastname'];
   				$this->email = $customer_q_bo->row['email'];
               $this->tax_exempt = $customer_q_bo->row['tax_exempt'];
   				$this->telephone = $customer_q_bo->row['telephone'];
   				$this->fax = $customer_q_bo->row['fax'];
            } else { 
   				$this->logout();
            }
			}
      // SPS integration
      } else if (isset($this->session->data['user_id'])) {
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_user WHERE user_id = '" . (int)$this->session->data['user_id'] . "' AND active = '1'");
			
			if ($customer_query->num_rows) {
				$this->user_id = $customer_query->row['user_id'];
            $this->is_sps = true;
            $this->sps = new SPSUser($this->user_id);
				$this->username = $customer_query->row['username'];
				$this->firstname = $customer_query->row['firstname'];
				$this->lastname = $customer_query->row['lastname'];
				$this->email = $customer_query->row['email'];
            $this->tax_exempt = $customer_query->row['tax_exempt'];
				$this->telephone = $customer_query->row['telephone'];
				$this->fax = $customer_query->row['fax'];
            /*
				$this->newsletter = $customer_query->row['newsletter'];
				$this->address_id = $customer_query->row['address_id'];
            */
         } else {
            $this->logout();
         }   
      }
	}
		
  	public function login ($store_code, $email, $password) {
  	    
		$customer_query = $this->db->query("
			SELECT * 
			FROM " . DB_PREFIX . "customer 
			WHERE 	1
				AND	email = '" . $this->db->escape($email) . "' 
				AND password = '" . $this->db->escape(md5($password)) . "' 
				AND status = '1'
				AND	store_code = '{$store_code}'
		");
		
		if ($customer_query->num_rows) {
			$this->session->data['customer_id'] = $customer_query->row['customer_id'];	
		    
			/* ignore cart repopulation
			if (($customer_query->row['cart']) && (is_string($customer_query->row['cart']))) {
				$cart = unserialize($customer_query->row['cart']);
				
				foreach ($cart as $key => $value) {
					if (!array_key_exists($key, $this->session->data['cart'])) {
						$this->session->data['cart'][$key] = $value;
					} else {
						$this->session->data['cart'][$key] += $value;
					}
				}			
			}
			*/
			
			$this->customer_id = $customer_query->row['customer_id'];
			$this->customer_group_id = $customer_query->row['customer_group_id']; // Customer Group module
			$this->firstname = $customer_query->row['firstname'];
			$this->lastname = $customer_query->row['lastname'];
			$this->email = $customer_query->row['email'];
			$this->telephone = $customer_query->row['telephone'];
			$this->fax = $customer_query->row['fax'];
			$this->newsletter = $customer_query->row['newsletter'];
			$this->address_id = $customer_query->row['address_id'];
         // set our shipping address id early for taxes. this is going to cause a lot of queries to the tax system.
         $this->session->data['shipping_address_id'] = $this->address_id;
			$this->tax_exempt = $customer_query->row['tax_exempt'];
			
			$address_query = $this->db->query("SELECT *, c.name AS country, z.name AS zone FROM " . DB_PREFIX . "address a LEFT JOIN " . DB_PREFIX . "country c ON a.country_id = c.country_id LEFT JOIN " . DB_PREFIX . "zone z ON a.zone_id = z.zone_id WHERE a.customer_id = '" . (int)$this->session->data['customer_id'] . "'");
						 
			foreach ($address_query->rows as $result) {
				$this->address[$result['address_id']] = array(
					'firstname'      => $result['firstname'],
					'lastname'       => $result['lastname'],
					'company'        => $result['company'],
					'address_1'      => $result['address_1'],
					'address_2'      => $result['address_2'],
					'postcode'       => $result['postcode'],
					'city'           => $result['city'],
					'country_id'     => $result['country_id'],
					'zone_id'        => $result['zone_id'],
					'iso_code_2'     => $result['iso_code_2'],
					'iso_code_3'     => $result['iso_code_3'],
					'code'           => $result['code'],
					'zone'           => $result['zone'],
					'country'        => $result['country'],	
					'address_format' => $result['address_format']
				);
			}			

			// Customer Group module
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . $this->customer_group_id . "'");
			
			if ($query->num_rows) {
				$this->cust_tax_class_id = $query->row['group_tax_class_id'];
				$this->cust_group_discount = $query->row['group_discount'];
			}
			// end customer group
      
	  		return TRUE;
    	} else {
         // SPS integration
         $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_user WHERE username = '{$email}' AND password = '" . md5($password) . "' AND store_code='{$store_code}'");
         if ($user_query->num_rows) {

            $this->is_sps = true;
            $this->sps = new spsUser($user_query->row['user_id']);
            $this->session->data['user_id'] = $user_query->row['user_id'];

            /* Greg says no cart repopulation 
			   if (($user_query->row['cart']) && (is_string($user_query->row['cart']))) {
   				$cart = unserialize($user_query->row['cart']);
               if (is_array($cart)) {
   				foreach ($cart as $key => $value) {
   					if (!array_key_exists($key, $this->session->data['cart'])) {
   						$this->session->data['cart'][$key] = $value;
   					} else {
   						$this->session->data['cart'][$key] += $value;
   					}
   				}			
               }
			   }
             */

            return TRUE;
         }
         return FALSE;
    	}
    	
  	}
  	
  
  	public function logout() {
      if ($this->isSPS()) { $this->getSPS()->logout(); }

		unset($this->session->data['customer_id']);
		unset($this->session->data['continue_shopping']);
		unset($this->session->data['hidden_shipping_address_id']);

		$this->customer_id = '';
		$this->firstname = '';
		$this->lastname = '';
		$this->email = '';
		$this->telephone = '';
		$this->fax = '';
		$this->newsletter = '';
		$this->address_id = '';
		$this->address = array();
		$this->tax_exempt = 0;
		
		// Customer Group module
		$this->customer_group_id = '';
		$this->cust_tax_class_id = '';
		$this->cust_group_discount = '';
		// end customer group		

      unset($this->session);
  	}
  
  	public function isLogged() {
      if ($this->is_sps) { return $this->getSPS()->getUserID(); }
    	return $this->customer_id;
  	}

  	public function getId() {
      if ($this->isSPS()) { return $this->getSPS()->getUserID(); }
    	return $this->customer_id;
  	}
      
  	public function getFirstName() {
      if ($this->isSPS()) { return $this->getSPS()->getFirstname(); }
		return $this->firstname;
  	}
  
  	public function getLastName() {
      if ($this->isSPS()) { return $this->getSPS()->getLastname(); }
		return $this->lastname;
  	}
  
  	public function getEmail() {
      if ($this->isSPS()) { return $this->getSPS()->getEmail(); }
		return $this->email;
  	}
  
  	public function getTelephone() {
      if ($this->isSPS()) { return $this->getSPS()->getTelephone(); }
		return $this->telephone;
  	}
  
  	public function getFax() {
      if ($this->isSPS()) { return $this->getSPS()->getFax(); }
		return $this->fax;
  	}
	
  	public function getNewsletter() {
		return $this->newsletter;	
  	}
	
  	public function getAddressId() {
      if ($this->isSPS()) { return $this->getSPS()->getAddressID(); } 
		return $this->address_id;	
  	}

	public function getAddress($address_id, $billing_address=0) {
      if ($this->isSPS()) { return $this->getSPS()->getAddress($address_id, $billing_address); }
		return (isset($this->address[$address_id]) ? $this->address[$address_id] : array());
	}
	
	public function hasAddress($address_id) {
      if ($this->isSPS()) { return $this->getSPS()->hasAddress($address_id); }
		return isset($this->address[$address_id]);	
	}
	
	// Customer Group module
	public function getGroupId() {
		return $this->customer_group_id;
	}
	
	public function getGroupTaxClass() {
      if ($this->isSPS()) { return $this->getSPS()->getGroupTaxClass(); }
		return $this->cust_tax_class_id;
	}
	
	public function getGroupDiscount() {
		return $this->cust_group_discount;
	}
	// end customer group	

   // Category discounts : KMC 
   public function getCategoryDiscounts() {
      return $this->category_discounts;
   }
   // end Category discounts	

   public function isTaxExempt() {
      if ($this->isSPS()) { return $this->getSPS()->isTaxExempt(); }
      return $this->tax_exempt;
   }

   public function isSPS() {
      return $this->is_sps;
   }

   public function getSPS() {
      return $this->sps;
   }

   public function setSPS($sps) {
      unset($this->sps);
      $this->sps = $sps;
      $this->is_sps = true;
   }

   public function getDiscount($level) {
      $level = 'discount_'.$level;
      return $this->discount_levels[0][$level];
   }
	
   public function hasCategoryDiscount($category_id, &$discount_pct)
   {
       $has_discount = false;
       $discount_pct = 0;
       // Do we actually have any discounts in this category for the customer?
       if (count($this->category_discounts) > 0) {
          foreach ($this->category_discounts as $cat_discount) {
             if (array_key_exists($category_id, $cat_discount)) {
                $has_discount = true;
                $discount_pct = $cat_discount[$category_id];
                break;
             }     
          }
       }   
       return $has_discount;
   }

   private function setupDiscountLevels() {
      $q = $this->db->query("SELECT discount_1, discount_2, discount_3, discount_4 FROM customer WHERE customer_id='{$this->customer_id}'");
      if ($q->num_rows) {
         foreach ($q->rows as $k => $v) {
            $this->discount_levels[$k] = $v;
         }
      }
   }
}
?>
