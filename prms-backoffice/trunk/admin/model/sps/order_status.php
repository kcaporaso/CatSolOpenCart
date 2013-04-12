<?php 
class ModelSPSOrderStatus extends Model {
	public function addOrderStatus($data) {
		foreach ($data['order_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_status SET order_status_id = '" . (int)@$order_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
			
			$order_status_id = $this->db->getLastId();
		}
		
		$this->cache->delete('order_status');
	}

	public function editOrderStatus($order_status_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "sps_order_status WHERE order_status_id = '" . (int)$order_status_id . "'");

		foreach ($data['order_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "sps_order_status SET order_status_id = '" . (int)$order_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
				
		$this->cache->delete('order_status');
	}
	
	public function deleteOrderStatus($order_status_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "sps_order_status WHERE order_status_id = '" . (int)$order_status_id . "'");
	
		$this->cache->delete('order_status');
	}
	
	
	public function getOrderStatus ($order_status_id) {
	    
		$query = $this->db->query("
			SELECT 		OS.*, CONCAT(OS.name, ' [',OSG.name,']') as name
			FROM " . DB_PREFIX . "sps_order_status as OS,
						sps_order_status_group as OSG
			WHERE 		1
				AND		OS.order_status_group_id = OSG.id
				AND		OS.order_status_id = '" . (int)$order_status_id . "' 
				AND 	OS.language_id = '" . (int)$this->language->getId() . "'
		");
		
		return $query->row;
		
	}
		
	
	public function getOrderStatuses ($data = array()) {
	    
      	if ($data) {
			$sql = "
				SELECT OS.order_status_id, CONCAT(OS.name, ' [',OSG.name,']') as name
				FROM " . DB_PREFIX . "sps_order_status as OS,
							sps_order_status_group as OSG
				WHERE 		1
					AND		OS.order_status_group_id = OSG.order_status_group_id
					AND		OS.language_id = '" . (int)$this->language->getId() . "'
			";
			
			$sql .= " ORDER BY sort_order";	
			
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
			
		} else {
		    
			$order_status_data = $this->cache->get('sps_order_status.' . $this->language->getId());
		
			if (!$order_status_data) {

    			$sql = "
    				SELECT OS.order_status_id, CONCAT(OS.name, ' [',OSG.name,']') as name
    				FROM " . DB_PREFIX . "sps_order_status as OS,
    							sps_order_status_group as OSG
    				WHERE 		1
    					AND		OS.order_status_group_id = OSG.order_status_group_id
    					AND		OS.language_id = '" . (int)$this->language->getId() . "'
    				ORDER BY 	sort_order
    			";			    
			    
				$query = $this->db->query($sql);
	
				$order_status_data = $query->rows;
			
				$this->cache->set('sps_order_status.' . $this->language->getId(), $order_status_data);
			}	
	
			return $order_status_data;
					
		}
		
	}
	
	
	public function getOrderStatusDescriptions ($order_status_id) {
	    
		$order_status_data = array();
		
		$query = $this->db->query("
			SELECT 		OS.*
			FROM " . DB_PREFIX . "sps_order_status as OS
			WHERE 		1
				AND		OS.order_status_id = '" . (int)$order_status_id . "'
		");
		
		foreach ($query->rows as $result) {
			$order_status_data[$result['language_id']] = array('name' => $result['name']);
		}
		
		return $order_status_data;
		
	}
	
	
	public function getTotalOrderStatuses () {
	    
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sps_order_status WHERE language_id = '" . (int)$this->language->getId() . "'");
		
		return $query->row['total'];
		
	}	

	public function getOrderStatusNameForDisplay ($order_status_id) {
	    
      $sql = "SELECT OS.name
    			  FROM " . DB_PREFIX . "sps_order_status as OS
    			  WHERE 		1 AND order_status_id = '{$order_status_id}' ";
			    
		$query = $this->db->query($sql);
	
		$order_status_data = $query->row['name'];
			
		return $order_status_data;
	}
	
}
?>
