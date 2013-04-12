<?php
class ModelAccountFindList extends Model {
   // NOTE:  list_type = 0 is Shopping List, list_type = 1 is Wish List
        
	public function search($email, $store_code) {
      // We are searching on an email address... Only applies to retail
      // customer
      $query = $this->db->query("SELECT customer_id, firstname, lastname FROM customer WHERE email='{$email}' AND store_code='{$store_code}'");
      if ($query->num_rows) {
         return $query->rows;
      }
      return false;
	}
	
   // Used to create a new list or add to an existing list.
	public function updateList($list_id=0, $data) {
      if (!$data) { return false; }

      if ($list_id) {
         // update existing list.
         // unserialize the cart.
         $query = $this->db->query("SELECT cart FROM " . DB_PREFIX . "list WHERE id='{$list_id}'");
         $cart = array();
         if ($query->num_rows) {
            $cart = unserialize($query->row['cart']);
            if (array_key_exists($data['product_id'], $cart)) {
               $cart[$data['product_id']] += $data['qty'];
            } else {
               $cart[$data['product_id']] = $data['qty'];
            }
         }

   		$this->db->query("UPDATE " . DB_PREFIX . "list SET date_modified = NOW(), cart = '" . $this->db->escape(serialize($cart)) . "' WHERE id = '{$list_id}' AND store_code = '{$data['store_code']}'");
      } else {
         // Insert new list.
         $cart = array($data['product_id'] => $data['qty']);

   		$this->db->query("INSERT INTO " . DB_PREFIX . "list SET list_type = '{$data['list_type']}', user_id = '{$data['user_id']}', store_code = '{$data['store_code']}', name = '" . $this->db->escape($data['name']) . "', date_added = NOW(), cart = '" . $this->db->escape(serialize($cart)) . "'");
      }
      return true;
	}

   public function deleteItemFromList($product_id, $list_id, $store_code) {
      $q = $this->db->query("SELECT cart FROM " . DB_PREFIX . "list where id='{$list_id}' AND store_code='{$store_code}'"); 
      $cart = array();
      if ($q->num_rows) {
         $cart = unserialize($q->row['cart']);
         if (array_key_exists($product_id, $cart)) {
            unset($cart[$product_id]);  
   		   $this->db->query("UPDATE " . DB_PREFIX . "list SET date_modified = NOW(), cart = '" . $this->db->escape(serialize($cart)) . "' WHERE id = '{$list_id}' AND store_code = '{$store_code}'");
            return true;
         } else {
            return false;
         }
      }
      return false;
   }
}
?>
