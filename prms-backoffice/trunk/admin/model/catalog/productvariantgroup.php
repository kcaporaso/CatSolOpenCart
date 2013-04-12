<?php


class ModelCatalogProductVariantGroup extends Model {
    
    
	public function addProductVariantGroup ($data) {
	    
		$this->db->query("
			INSERT INTO " . DB_PREFIX . "product_variant_group 
			SET 	
					name = '{$data['name']}'
		");
	
	}
	
	
	public function editProductVariantGroup ($product_variant_group_id, $data) {
	    
		$this->db->query("
			UPDATE " . DB_PREFIX . "product_variant_group 
			SET 	name = '{$data['name']}'
			WHERE 		1
				AND		id = '" . (int)$product_variant_group_id . "'
		");
	
	}
	
	
	public function deleteProductVariantGroup ($product_variant_group_id) {
	    
		$this->db->query("
			DELETE 
			FROM " . DB_PREFIX . "product_variant_group 
			WHERE 		1
				AND		id = '" . (int)$product_variant_group_id . "'
		");
		
	}
	
	
	public function getProductVariantGroup ($product_variant_group_id) {
	    
		$query = $this->db->query("
			SELECT DISTINCT * 
			FROM " . DB_PREFIX . "product_variant_group 
			WHERE 		1
				AND		id = '" . (int)$product_variant_group_id . "' 
		");

		$product_variant_group = array(
			'name'             => $query->row['name']
		);

		return $product_variant_group;
		
	}
	
		
	public function getProductVariantGroups ($data = array()) {
	    
		$sql = "SELECT * FROM " . DB_PREFIX . "product_variant_group";

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
	    
  	    $rows = $this->model_catalog_productvariantgroup->getProductVariantGroups(null);
  	    
  	    foreach ($rows as $row) {
  	        $dropdown_rows[$row['id']] = $row['name'];
  	    }
  	    
  	    return $this->get_pulldown_options($dropdown_rows, $selected_id, $firstblank);
  	    	    
	}
	
	
	public function get_id_from_name ($name, $productset_id) {
	    
	    $name_escaped = mysql_real_escape_string(htmlentities(trim($name), ENT_QUOTES, $this->detect_encoding($name)));
	    
	    $sql = "
	    	SELECT	id
	    	FROM	product_variant_group
	    	WHERE	name = '{$name_escaped}' AND productset_id = '{$productset_id}'
	    ";
	    
	    $result = $this->db->query($sql);
	    
	    return $result->row['id'];	    
	    
	}
	
	
	public function create_from_name ($name, $productset_id) {
	    
        $name_escaped = mysql_real_escape_string(htmlentities(trim($name), ENT_QUOTES, $this->detect_encoding($name)));
        
		// NOTE: Removed DELAYED insert since we are returning the ID.  
        $sql = "
        	INSERT INTO		product_variant_group
        				SET			name = '{$name_escaped}', productset_id = '{$productset_id}'
        ";
        
        $this->db->query($sql);
	    
	    return $this->db->getLastId();
	    
	}
	protected function detect_encoding( $str ) {
		// auto detect the character encoding of a string
		return mb_detect_encoding( $str, 'UTF-8,ISO-8859-15,ISO-8859-1,cp1251,KOI8-R' );
	}
	
}
?>
