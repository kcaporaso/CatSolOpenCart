<?php

class ModelSettingSetting extends Model {
    
    
    /*	this function does not appear to be used anywhere !!
     * 
	public function getSetting ($group) {
	    
		$data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = '" . $this->db->escape($group) . "'");
		
		foreach ($query->rows as $result) {
			$data[$result['key']] = $result['value'];
		}
				
		return $data;
		
	}
	*/
	
	
	public function editSetting ($group, $data) {
	    
	    // save Store Code if present; cannot save if Store Code NOT present; 
	    //    if in God Mode save only if Store Code = 'ZZZ' (else notify) 
	    
	    if (!$store_code = $_SESSION['store_code']) {
	        trigger_error("Store Code not present; cannot save setting."); exit;
	    }
	    
	    if ($_SESSION['modgode']) {
	        if ($store_code != 'ZZZ') {
	            trigger_error("Store Code invalid in this mode."); exit;
	        }
	    }
	
		foreach ($data as $key => $value) {
               
		    if ($setting_row = $this->getSetting($store_code, $group, $key)) {
  
		        if ($setting_row['value'] != trim($value)) {
		        
        			$this->db->query("
        				UPDATE " . DB_PREFIX . "setting 
        				SET 		`value` = '" . $this->db->escape($value) . "',
        							timestamp = NOW()
        				WHERE		1
        					AND		setting_id = '{$setting_row['setting_id']}'	
        			");
    			
		        }
		        
		    } else {

    			$this->db->query("
    				INSERT INTO " . DB_PREFIX . "setting 
    				SET 	store_code = '{$store_code}',
    						`group` = '" . $this->db->escape($group) . "', 
    						`key` = '" . $this->db->escape($key) . "', 
    						`value` = '" . $this->db->escape($value) . "',
    						timestamp = NOW()
    			");

		    }
			
		}

	}
	
	
	public function getSetting ($store_code, $group, $key) {
	    
	    $where = " store_code = '{$store_code}' AND `group` = '{$group}' AND `key` = '{$key}' ";
	    
	    $found = $this->db->get_multiple('setting', $where);
	    
	    return $found[0];
	    
	}
	
	
	public function deleteSetting ($group, $store_code) {
	    
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = '" . $this->db->escape($group) . "' and store_code='". $store_code ."'");
		
	}
	
	
}
?>
