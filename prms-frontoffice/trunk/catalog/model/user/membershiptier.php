<?php


class ModelUserMembershiptier extends Model {
    
    
    public function site_is_gold ($store_code) {
        
      $sql = "select u.membershiptier_id from store s inner join `user` u on s.user_id = u.user_id where code='{$store_code}'"; 
		$result = $this->db->query($sql);
      $retval = (int) $result->row['membershiptier_id'];		

      if ($retval >= 2) {
		   return TRUE;
      } else {
         return FALSE;
      }
   }

}
?>
