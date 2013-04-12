<?php

final class spsUser {

   private $role_id;
   private $user_id;
   private $district_id; //region_id
   private $school_id;
   private $store_code;
   private $address = array();
   private $billing_address = array();
   private $sps_permission = array(); 
   private $roles = array();
   //
   private $fax;
   private $telephone;
   private $email;
   private $discount_levels = array();
   private $free_shipping;
   private $free_freight_over;
   private $customer_group_id;
   private $cust_tax_class_title;
   private $tax_exempt = 0;
   private $instant_approval = 0;

  	public function __construct($user_id) {
		$this->db = Registry::get('db');
      $this->session = Registry::get('session');
		
      $this->roles = array(SPS_ADMIN => 'Administrator',
                     SPS_SUPERUSER => 'Super User',
                     SPS_APPROVER => 'Approver',
                     SPS_SHOPPER => 'Shopper');

      // Pull our sps specific user and role (permission) details.
      $sps_user_q = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_user WHERE user_id='{$user_id}'");
      if ($sps_user_q->num_rows) {
         $this->user_id = $sps_user_q->row['user_id'];
         $this->username = $sps_user_q->row['username'];
         $this->firstname = $sps_user_q->row['firstname'];
         $this->lastname = $sps_user_q->row['lastname'];
         $this->role_id = $sps_user_q->row['role_id'];
         $this->district_id = $sps_user_q->row['district_id'];
         $this->school_id = $sps_user_q->row['school_id'];
         $this->store_code = $sps_user_q->row['store_code'];
         $this->email = $sps_user_q->row['email'];
         $this->free_shipping = $sps_user_q->row['free_shipping'];
         $this->free_freight_over = $sps_user_q->row['free_freight_over'];
         $this->session->data['store_code'] = $this->store_code;
         $this->customer_group_id = $sps_user_q->row['customer_group_id'];
         $this->tax_exempt = $sps_user_q->row['tax_exempt'];
         $this->instant_approval = $sps_user_q->row['instant_approval'];

			// Customer Group module
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . $this->customer_group_id . "'");
			
			if ($query->num_rows) {
				$this->cust_tax_class_id = $query->row['group_tax_class_id'];
				$this->cust_group_discount = $query->row['group_discount'];
			}

         $q = $this->db->query("SELECT title, description FROM tax_class WHERE tax_class_id='{$this->cust_tax_class_id}'");
         if ($q->num_rows) {
            $this->cust_tax_class_title = $q->row['description'];
         }

			// end customer group
         $this->buildDefaultAddressInfo($this->school_id);

         //if ($this->role_id == SPS_APPROVER ||
         //    $this->role_id == SPS_SUPERUSER) {
            $this->buildAllAddresses();
         //}

         // Get our district discount level.
         $this->setupDiscountLevels();
         $sps_perm_q = $this->db->query("SELECT permission FROM " . DB_PREFIX . "sps_role WHERE id = '{$this->role_id}'");
         if ($sps_perm_q->num_rows) {
            // Set up some basic permissions for an Admin user!
            //if (empty($sps_perm_q) && $sps_perm_q->row['id'] == '9999') { 
            //}
            if (empty($sps_perm_q->row['permission']) && $this->role_id == SPS_ADMIN) {
               // Gotta give some initial permission to set permissions... weird I know.
		         $files = glob(DIR_APPLICATION . 'controller/sps/*.php');
               $p = array();
         		foreach ($files as $file) {
			         $permission = end(explode('/', dirname($file))) . '/' . basename($file, '.php');
//                  var_dump($permission);
                  $p['modify'][] = $permission;
                  $p['access'][] = $permission;
               }
               $this->sps_permission = $p;
//               echo ' ok ' ; exit;
            } else {
      		   $unserialized_perms = (array) unserialize($sps_perm_q->row['permission']);
	  			   foreach ($unserialized_perms as $key => $value) {
	    			   $this->sps_permission[$key] = $value;
	  			   }
            }
         }
      	$this->db->query("UPDATE " . DB_PREFIX . "sps_user SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int)$user_id . "'");
      	//$this->db->query("UPDATE " . DB_PREFIX . "sps_user SET cart = '" . $this->db->escape(serialize($this->session->data['cart'])) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int)$user_id . "'");

      }
//var_dump($this->roles);
//exit;
  	}
		
  	public function hasPermission($key, $value) {
//var_dump($this->sps_permission);
//      echo $key; echo $value;
    	if (isset($this->sps_permission[$key])) {
	  		return in_array($value, $this->sps_permission[$key]);
		} else {
	  		return FALSE;
		}
  	}

   public function hasFreeShipping($subtotal) {
      // Two ways:
      // 1. Has free shipping flag set to 1.
      // 2. Meets min. subtotal value to get free shipping.
      if ($this->free_shipping) { return true; }
      if ($this->free_freight_over) { // did we set a value?
         if ($subtotal > $this->free_freight_over) { return true; }    
      } 
      return false;
   }

   public function getRoleID() {
      return $this->role_id;
   }

   public function getRoleName() {
      return $this->roles[$this->role_id];
   }

   public function getSchoolID() {
      return $this->school_id;
   }

   public function getDistrictID() {
      return $this->district_id;
   }

   public function getDistrictName() {
      $d = $this->db->query("SELECT name FROM sps_district WHERE id='{$this->district_id}'");
      return $d->row['name'];
   }

   public function getDiscount($level) {
      $level = 'discount_'.$level;
      return $this->discount_levels[0][$level];
   }

   public function dumpPermissions() {
      var_dump($this->sps_permission);
   }

   public function isAdmin() {
      return ($this->role_id == SPS_ADMIN);
   }

   public function isSuperUser() {
      return ($this->role_id == SPS_SUPERUSER);
   }

   public function approves() {
      if ($this->role_id == SPS_SUPERUSER ||
          $this->role_id == SPS_APPROVER) { 
         return true;
      }
      return false;
   }

	public function getGroupTaxClass() {
		return $this->cust_tax_class_id;
	}

   public function getGroupTaxTitle() {
	   return $this->cust_tax_class_title;
   }

   public function isTaxExempt() {
      return $this->tax_exempt;
   }

   public function isInstantApproval() {
      if ($this->isSuperUser() || 
          $this->instant_approval) {
         return true;
      }
      return false;
   }

   public function getUserID() {
      return $this->user_id;
   }
  
   public function getUsername()  { return $this->username;  }
   public function getFirstname() { return $this->firstname; }
   public function getLastname()  { return $this->lastname;  }
   public function getSchoolname() { return $this->schoolname; }
   public function getEmail() { return $this->email; }
   public function getTelephone() { return $this->telephone; }
   public function getFax() { return $this->fax; }

   // We returning our school_id since it contains the shipping address.
   public function getAddressID() {
      return $this->getSchoolID();
   }

   public function getBillingAddressID() {
      return $this->getSchoolID();
   }

   //returns array of default address info, address_id is really the school_id.
   public function getAddress($address_id, $billing_address=0) {
      if ($billing_address) {
         return $this->billing_address[$address_id];
      } else {
         return $this->address[$address_id];
      }
   }

   public function getBillingAddress($address_id) {
      return $this->billing_address[$address_id];
   }

   public function hasAddress($address_id) {
      // We're using school_id for address_id
      return isset($this->address[$address_id]);
   }

   public function hasBillingAddress($address_id) {
      // We're using school_id for address_id
      return isset($this->billing_address[$address_id]);
   }

   public function getAllAddresses() {
      return $this->address;
   }

   public function getAllBillingAddresses() {
      return $this->billing_address;
   }

   public function buildAllAddresses() {
      // Grab the district ones.
      $q = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_school WHERE district_id='{$this->district_id}'");
      if ($q->num_rows) {
         foreach ($q->rows as $school) {
            $this->address[$school['id']] = array(
               'address_id'     => $school['id'],
					'firstname'      => $this->getFirstname(),
					'lastname'       => $this->getLastname(),
					'company'        => $school['name'],
					'address_1'      => $school['address1'],
					'address_2'      => $school['address2'],
					'postcode'       => $school['zipcode'],
					'city'           => $school['city'],
					'country_id'     => 223,
					'zone'           => $school['state'],
					'country'        => $school['country'],	
               'email'          => $school['email'],
               'url'            => $school['url'],
               'type'           => 'shipping'
           );

           if (!empty($school['billing_firstname'])) {
              $this->billing_address[$school['id']] = array(
                 'address_id' => $school['id'],
					  'firstname'      => $school['billing_firstname'],
					  'lastname'       => $school['billing_lastname'],
					  'address_1'      => $school['billing_address1'],
					  'address_2'      => $school['billing_address2'],
					  'postcode'       => $school['billing_zipcode'],
					  'city'           => $school['billing_city'],
					  'country_id'     => 223,
					  'zone'           => $school['billing_state'],
                 'type'           => 'billing'
              );
           }
         }
      }
   }

   public function buildDefaultAddressInfo($school_id) {
      // All addresses are held at the school level.
      $school_info = $this->db->query("SELECT * FROM " . DB_PREFIX . "sps_school WHERE id='{$school_id}'"); 
      // OK, set up an address that resembles what is expected of the "non-sps" system so we can "fit-in".
      // We base ours off the 'school_id' though.
      if ($school_info->num_rows) {
         $this->schoolname = $school_info->row['name'];
         $this->fax = $school_info->row['fax'];
         $this->telephone = $school_info->row['phone'];
         $this->address[$school_id] = array(
               'address_id'     => $school_id,
					'firstname'      => $this->firstname,
					'lastname'       => $this->lastname,
					'company'        => $school_info->row['name'],
					'address_1'      => $school_info->row['address1'],
					'address_2'      => $school_info->row['address2'],
					'postcode'       => $school_info->row['zipcode'],
					'city'           => $school_info->row['city'],
					'country_id'     => 223,
//					'zone_id'        => $result['zone_id'],
//					'iso_code_2'     => $result['iso_code_2'],
//					'iso_code_3'     => $result['iso_code_3'],
//					'code'           => $result['code'],
					'zone'           => $school_info->row['state'],
					'country'        => $school_info->row['country'],	
               'email'          => $school_info->row['email'],
               'url'            => $school_info->row['url'],
					'address_format' => $school_info->row['address_format']
       );
       if (!isset($this->session->data['shipping_address_id'])) {
          $this->session->data['shipping_address_id'] = $school_id;
       }
    }
 }

   public function logout() {
      unset($this->session->data['user_id']);
      $this->role_id = '';
      $this->user_id = '';
      $this->district_id = ''; 
      $this->school_id = '';
      $this->store_code = '';
      unset($this->address);
      unset($this->sps_permission);
      unset($this->roles);
      $this->fax = '';
      $this->telephone = '';
      $this->email = '';
      unset($this->session->data['cart']);
		unset($this->session->data['continue_shopping']);
      unset($this->session->data['use_billing_address']);
   }

  private function setupDiscountLevels() {
     $q = $this->db->query("SELECT discount_1, discount_2, discount_3, discount_4 FROM sps_district WHERE id='{$this->district_id}'");
     if ($q->num_rows) {
        foreach ($q->rows as $k => $v) {
           $this->discount_levels[$k] = $v;
        }
     }
  }
}
?>
