<?php


class ModelUserMembershiptier extends Model {
    
    
    public function user_can_access_sitefeature ($user_id, $sitefeature_code) {
        
        // Admin always returns true

		$this->load->model('user/user');
      //var_dump($this->model_user_user->isAdmin($user_id)); 
      //exit;
		if ($this->model_user_user->isAdmin($user_id)) return true;

      // SPS:
      if ($this->user->isSPS()) { 
         return $this->user->getSPS()->isAdmin();
      }

      $sql = "SELECT		M.id
        	FROM		membershiptier as M
        				INNER JOIN user as U
        					ON (M.id = U.membershiptier_id)
        				INNER JOIN sitefeature as SF
        					ON (M.id >= SF.min_membershiptier_id)
        	WHERE		1
        		AND		U.user_id = {$user_id}
        		AND		SF.code = '{$sitefeature_code}'
        ";
   
		$result = $this->db->query($sql);
      $retval = (boolean) $result->row['id'];		
//echo 'retval: ' . (boolean) $retval;
		return $retval;
   }

   // This is where we check to see if it's one of OUR (CatSol) style admins, not an SPS admin.
   // Use this when trying to restirct SPS Admins from areas of the backoffice.
   public function user_is_true_admin($user_id, $sitefeature_code) {

      $sql = "SELECT		M.id
        	FROM		membershiptier as M
        				INNER JOIN user as U
        					ON (M.id = U.membershiptier_id)
        				INNER JOIN sitefeature as SF
        					ON (M.id >= SF.min_membershiptier_id)
        	WHERE		1
        		AND		U.user_id = {$user_id}
        		AND		SF.code = '{$sitefeature_code}'
       ";
   
		$result = $this->db->query($sql);
      if ($result->num_rows == 0) { return false; }
      $retval = (boolean) $result->row['id'];		
		return $retval;
   }
    
	public function getMembershiptiers ($data = array()) {
	    
		$sql = " SELECT * FROM membershiptier ";		

		$query = $this->db->query($sql);
		
		return $query->rows;
			
	}
	
	
	public function getDropdownOptions ($selected_id=null, $firstblank=true) {
	    
  	    $rows = $this->getMembershiptiers();
  	    
  	    foreach ($rows as $row) {
  	        $dropdown_rows[$row['id']] = $row['name'];
  	    }
  	    
  	    return $this->get_pulldown_options($dropdown_rows, $selected_id, $firstblank);
  	    	    
	}
	
	
}
?>
