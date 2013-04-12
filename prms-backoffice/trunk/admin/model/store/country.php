<?php

class ModelStoreCountry extends Model {
    
    
	public function addRecord ($data) {
	    
	    $data['created_datetime'] = date(ISO_DATETIME_FORMAT);
	    $this->db->add('store_country', $data);
	    
	}
	
	
	public function deleteRecord ($store_code, $country_id) {
	    
	    $sql = "
	    	DELETE		J.*
	    	FROM		store_country as J,
	    				store as S
	    	WHERE		1
	    		AND		J.store_code = S.code
	    		AND		S.code = '{$store_code}'
	    		AND		J.country_id = '{$country_id}'
	    ";
	    
	    $this->db->query($sql);
		
	}	
	
	
	public function getRecord ($store_code, $country_id) {
	    
	    $sql = "
	    	SELECT		J.*
	    	FROM		store_country as J,
	    				store as S
	    	WHERE		1
	    		AND		J.store_code = S.code
	    		AND		S.code = '{$store_code}'
	    		AND		J.country_id = '{$country_id}'
	    ";
	    
		$query = $this->db->query($sql);
		
		return $query->row;
		
	}
	
	
	public function getRecords ($store_code, $filterdata = array(), $count_only=false, $left_join=true) {
	    
	    if ($left_join) {
	        $junction_select = ", (IF(J.id IS NOT NULL, '1', NULL)) as included";
	        $join_type = 'LEFT';
	        $order_by = 'included DESC,';
	    } else {
	        $join_type = 'INNER';
	    }
	    
	    $core_query = "
			SELECT 		C.* {$junction_select}
			FROM 		
						country as C
						{$join_type} JOIN store_country as J
							ON (C.country_id = J.country_id AND J.store_code = '{$store_code}')
							
			WHERE 		1
	    ";	    
	    
		$sql = $core_query;

		if (isset($filterdata['name'])) {
			$sql .= " AND C.name LIKE '%" . $this->db->escape($filterdata['name']) . "%'";
		}
		
		if (($filterdata['included'])=='1') {
			$sql .= " AND J.id IS NOT NULL";
		} elseif (($filterdata['included'])=='0') {
		    $sql .= " AND J.id IS NULL";
		}

		$sort_data = array(
		    'name',
		    'iso_code_2',
			'iso_code_3',
			'included'
		);
		
		if ($count_only) {
		    unset($filterdata['sort']);
		    unset($filterdata['order']);
		    unset($filterdata['start']);
		    unset($filterdata['limit']);
		}			
		
		if (in_array(@$filterdata['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $filterdata['sort'];	
		} else {
			$sql .= " ORDER BY {$order_by} C.name";	
		}
		
		if (@$filterdata['order'] == 'DESC') {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($filterdata['start']) || isset($filterdata['limit'])) {
			$sql .= " LIMIT " . (int)$filterdata['start'] . "," . (int)$filterdata['limit'];
		}	

		$query = $this->db->query($sql);
        
		if ($count_only) {
		    return count($query->rows);
		} else {
		    return $query->rows;
		}
		
	}
	
	
	public function processAssignmentForm ($store_code, $form_data, $viewing_user_id) {
	    
	    $array_all = (array) $form_data['country_ids'];
	    
	    $array_selected = (array) $form_data['country_ids_selected'];
	    
	    $array_notselected = (array) array_diff($array_all, $array_selected);        
        
	    foreach ($array_notselected as $country_id) {
	        if ($this->getRecord($store_code, $country_id)) {
	            $this->deleteRecord($store_code, $country_id);
	        }
	    }
	    
	    $add_data['creator_user_id'] = $viewing_user_id;
	    $add_data['store_code'] = $store_code;
	    	    
	    foreach ($array_selected as $country_id) {
	        if (!$this->getRecord($store_code, $country_id)) {
    	        $add_data['country_id'] = $country_id;
    	        $this->addRecord($add_data);
	        }
	    }
	    
	}
	
	
	public function stores_assigned_for_country_id ($country_id) {
	    
	    return (bool) $this->db->get_multiple('store_country', "country_id = '{$country_id}'");
	    
	}
	
	
}

?>