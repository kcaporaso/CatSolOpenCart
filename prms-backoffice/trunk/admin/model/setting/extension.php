<?php

class ModelSettingExtension extends Model {
    
    
	public function getInstalled ($store_code, $type) {
	    
		$extension_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE store_code = '{$store_code}' AND `type` = '" . $this->db->escape($type) . "'");
		
		foreach ($query->rows as $result) {
			$extension_data[] = $result['key'];
		}
		
		return $extension_data;
		
	}
	
	
	public function install ($store_code, $type, $key) {
	    
		$this->db->query("INSERT INTO " . DB_PREFIX . "extension SET store_code = '{$store_code}', `type` = '" . $this->db->escape($type) . "', `key` = '" . $this->db->escape($key) . "'");
		
	}
	
	
	public function uninstall ($store_code, $type, $key) {
	    
      $sql = "DELETE FROM " . DB_PREFIX . "extension WHERE store_code = '{$store_code}' AND `type` = '" . $this->db->escape($type) . "' AND `key` = '" . $this->db->escape($key) . "'";
		$this->db->query($sql);
	}
	
	
}
?>
