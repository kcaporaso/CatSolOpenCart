<?php
class ModelAccountAddress extends Model {
	public function addAddress($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$this->customer->getId() . "', company = '" . $this->db->escape($data['company']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "'");
		
		$address_id = $this->db->getLastId();
		
      	if (@$data['default']) {
        	$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
      	}
		
		return $address_id;
	}
	
	public function editAddress($address_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "address SET company = '" . $this->db->escape($data['company']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "' WHERE address_id  = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
	
      	if (@$data['default']) {
        	$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
      	}
	}
	
	public function deleteAddress($address_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
	}	
	
	public function getAddress($address_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' and customer_id = '" . (int)$this->customer->getId() . "'");
		
		return $query->row;
	}
	
   public function getBillingAddresses() {
      $addresses = array();
      if ($this->customer->isSPS()) {
         $addresses = $this->customer->getSPS()->getAllBillingAddresses();
      }
      return $addresses;
   }

	public function getAddresses() {

      if ($this->customer->isSPS()) {
         // When we are SPS we do things differently for addresses.  The actual address is only modified in the admin
         // area, so we're only displaying the addresses associated with a shopper's school.

         // If the logged in user shopping is an approver or super user they can ship to the district or any school in that district.
         // Simple case 1st, user is of a shopper role and can only ship to their school.
         if ($this->customer->getSPS()->getRoleID() == SPS_SHOPPER) {
            $addresses = array();
            //$addresses[] = $this->customer->getSPS()->getAddress($this->customer->getSPS()->getSchoolID()); 
            $addresses = $this->customer->getSPS()->getAllAddresses();
            //var_dump($addresses);
            return $addresses;
         } else if ($this->customer->getSPS()->getRoleID() == SPS_APPROVER ||
                    $this->customer->getSPS()->getRoleID() == SPS_SUPERUSER) {
            $addresses = array();
            $addresses = $this->customer->getSPS()->getAllAddresses();
            return $addresses;
         }

      } else {
		   $query = $this->db->query("SELECT *, c.name AS country, z.name AS zone FROM " . DB_PREFIX . "address a LEFT JOIN " . DB_PREFIX . "country c ON (a.country_id = c.country_id) LEFT JOIN " . DB_PREFIX . "zone z ON (a.zone_id = z.zone_id) WHERE a.customer_id = '" . (int)$this->customer->getId() . "'");
		   return $query->rows;
      }
	}	
	
	public function getTotalAddresses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	
		return $query->row['total'];
	}
}
?>
