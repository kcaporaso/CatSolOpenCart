<?php


class ModelCatalogGradeLevel extends Model {
    
    
	public function addGradelevel ($data) {
	    
		$this->db->query("
			INSERT INTO " . DB_PREFIX . "grade_level 
			SET 	
					name = '{$data['name']}'
		");
	
	}
	
	
	public function editGradelevel ($grade_level_id, $data) {
	    
		$this->db->query("
			UPDATE " . DB_PREFIX . "grade_level 
			SET 	name = '{$data['name']}'
			WHERE 		1
				AND		id = '" . (int)$grade_level_id . "'
		");
	
	}
	
	
	public function deleteGradelevel ($grade_level_id) {
	    
		$this->db->query("
			DELETE 
			FROM " . DB_PREFIX . "grade_level 
			WHERE 		1
				AND		id = '" . (int)$grade_level_id . "'
		");
		
	}
	
	
	public function getGradelevel ($grade_level_id) {
	    
		$query = $this->db->query("
			SELECT DISTINCT * 
			FROM " . DB_PREFIX . "grade_level 
			WHERE 		1
				AND		id = '" . (int)$grade_level_id . "' 
		");

		$grade_level = array(
			'name'             => $query->row['name']
		);

		return $grade_level;
		
	}
	
		
	public function getGradelevels ($data = array()) {
	    
		$sql = "SELECT * FROM " . DB_PREFIX . "grade_level";

		$implode = array();

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'id',
			'name'
		);	
			
		if (in_array(@$data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY id";
		}
			
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
			
	}
	
	
	public function getDropdownOptions ($selected_id=null, $firstblank=true) {
	    
  	    $rows = $this->getGradelevels();
  	    
  	    foreach ($rows as $row) {
  	        $dropdown_rows[$row['id']] = $row['name'];
  	    }
  	    
  	    return $this->get_pulldown_options($dropdown_rows, $selected_id, $firstblank);
  	    	    
	}	
	
	
	public function get_gradelevel_id_from_name ($name) {
	    return $this->db->get_column('grade_level', 'id', "name = '{$name}'");
	}
	
	
}
?>