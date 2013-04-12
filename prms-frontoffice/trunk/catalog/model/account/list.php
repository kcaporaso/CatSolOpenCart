<?php
class ModelAccountList extends Model {
   // NOTE:  list_type = 0 is Shopping List, list_type = 1 is Wish List
        
	public function getList($list_id, $store_code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "list  WHERE id='{$list_id}'");
		return $query->row;
	}
	
   public function getAllLists($user_id, $store_code) {
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "list WHERE user_id='{$user_id}' AND name != '' AND store_code='{$store_code}'");	
		return $query->rows;
   }

	public function getShoppingLists($user_id, $store_code) {
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "list WHERE user_id='{$user_id}' AND name != '' AND list_type = " . (int) SHOPPING_LIST);	
		return $query->rows;
	}

   public function getWishLists($user_id, $store_code) {
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "list WHERE user_id='{$user_id}' AND name != '' AND list_type = " . (int) WISH_LIST);	
		return $query->rows;
   }

   public function getWishListById($wishlistid, $store_code) {
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "list WHERE id='{$wishlistid}' AND list_type = " . (int) WISH_LIST . " AND store_code = '{$store_code}'");	
		return $query->rows;
   }

   public function saveWishList($wishlistid, $cart, $store_code) {
      $this->db->query("UPDATE " . DB_PREFIX . "list SET cart = '" . $this->db->escape(serialize($cart)) . "', date_modified = NOW() WHERE id = '{$wishlistid}' AND store_code='{$store_code}'");
   }
	
   public function createList($data, &$new_id = 0) {
      // Verify it doesn't exist already.
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "list WHERE name='" . $this->db->escape($data['name']) . "' AND list_type=" . (int) $data['list_type'] . " AND store_code='{$data['store_code']}' AND user_id='{$data['user_id']}'");

      if ($query->num_rows) {
         return false;
      } else {
         // Now create it since it doesn't exist.
         $this->updateList(0, $data);
         $new_id = $this->db->get_last_insert_id();
      }
      return true;
   }

   public function deleteList($list_id) {
      if ($list_id) {
         $this->db->query("DELETE FROM " . DB_PREFIX . "list WHERE id='{$list_id}'");
      }
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
         $cart = array();
         if ($data['product_id'] and $data['qty']) {
            $cart = array($data['product_id'] => $data['qty']);
         }

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
