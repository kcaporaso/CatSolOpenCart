<?php


class ModelCatalogCategory extends Model {
    
    
	public function getCategory ($store_code, $category_id) {
	    
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.store_code = '{$store_code}' AND c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->language->getId() . "'";
		
//echo "getCategorySQL: " . $sql;
		$query = $this->db->query($sql);
		return $query->row;
		
	}
	
	
	public function getCategories ($store_code, $parent_id = 0) {
      // This is for the new category management code
      // KMC have to get the store_id of the store_code.
      $store_id = $this->getStoreIDFromCode($store_code);
      $productset_ids = array();
      $productset_ids_quoted = array();
      $productset_ids = $this->get_productset_for_store($store_id);
//print_r($productset_ids);
//echo count($productset_ids);
      // if we have no catalogs selected return now with null array:
      if (count($productset_ids) == 0) { return (array)null; }
      $productset_ids_commasep = "";
      if (count($productset_ids) == 1) { 
         $productset_ids_commasep = "'" . $productset_ids[0]['productset_id'] . "'";
      } else {
         foreach ($productset_ids as $productset_id) {
            $productset_ids_quoted[] = "'{$productset_id['productset_id']}'";
         }
         $productset_ids_commasep = implode(',', $productset_ids_quoted);
      }

//      echo $productset_ids_commasep;
// KMC new category tied to productset_id code.
      $sql = "select c.category_id, c.parent_id, cd.name,c.productset_id,c.store_code,c.enabled from category c
                 inner join category_description cd on c.category_id = cd.category_id
                 where c.store_code='{$store_code}' and c.parent_id='{$parent_id}' and c.enabled=1 and c.invisible=0 and c.productset_id IN ({$productset_ids_commasep}) order by c.sort_order, cd.name ASC";

//echo '<!-- category sql:' . $sql .'-->';
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
				
	public function getTotalCategoriesByCategoryId ($store_code, $parent_id = 0) {
	    
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category WHERE store_code = '{$store_code}' AND invisible=0 and parent_id = '" . (int)$parent_id . "'");
		
		return $query->row['total'];
		
	}
	
	
	public function get_all_children_product_ids_in_category ($store_code, $level_num, $parent_id) {
	    
	    $result = $this->db->get_column(
	    	"category_all_product_ids_in_level_{$level_num}", 
	    	'product_ids', 
	    	"store_code = '{$store_code}' AND parent_id = '{$parent_id}'"
        );
        	    
        return $result;
	}
	
	
	public function get_categories_dropdown ($store_code, $category_id_or_path) {
	    
	    $id_path_array = explode('_', $category_id_or_path);
	    $selected_id = end($id_path_array);	    
	    
	    $data_array = array();
	    
	    $rows = (array) $this->get_records_tree_to_linear($store_code);
	    
	    foreach ($rows as $row) {
	        
	        $prefix_string = '';
	        for ($i=1; $i < $row['level_num']; $i++) {
	            $prefix_string .= '&nbsp;&nbsp;&nbsp;';
	        }
	        
	        $category_name = "&nbsp;{$prefix_string} - {$row['name']}";

	        $data_array[$row['path']] = $category_name;
	        
	    }

	    $pulldown_options = $this->get_pulldown_options($data_array, $category_id_or_path, false);
	    
	    return $pulldown_options;
	    
	}
	
	
	public function get_records_tree_to_linear ($store_code, $parent_id=0, $current_path = '') {
		
		$rows = (array) $this->getCategories($store_code, $parent_id);
		
		foreach ($rows as $row) {
		    	
			if (!$current_path) {
				$new_path = $row['category_id'];
			} else {
				$new_path = $current_path . '_' . $row['category_id'];
			}
			
			$children = (array) $this->get_records_tree_to_linear($store_code, $row['category_id'], $new_path);

			$level_num = count(explode('_', $new_path));
			 
			$output[] = array(
			    'id' => $row['category_id'],
			    'path' => $new_path,
			    'level_num' => $level_num,
				'name' => $row['name']
			);

			if ($children) {
			    $output = array_merge($output, $children);
			}
        
		}

		return $output;
	    
	}
	
	
	public function category_has_products ($store_code, $category_id) {
	    
		$sql = "
			SELECT 	p.product_id
					   				
			FROM 	product as p 
				
				INNER JOIN productset_product as PP
					ON (p.product_id = PP.product_id)
				INNER JOIN store_productsets as SPS
					ON (PP.productset_id = SPS.productset_id)
				INNER JOIN store as S
					ON (SPS.store_id = S.store_id AND S.code = '{$store_code}')
				INNER JOIN store_product as SP
					ON (p.product_id = SP.product_id AND SP.excluded_flag = '0' AND SP.store_code = '{$store_code}')

				INNER JOIN product_to_category p2c ON (p.product_id = p2c.product_id AND p2c.store_code = '{$store_code}')					
								
			WHERE 		1
				AND		p2c.category_id = '{$category_id}'
			LIMIT		1			
		";	    
		
	    $result = $this->db->query($sql);
	    
	    return (boolean) $result->num_rows;		
	    
	}
	
   public function getCategoryForProductID($store_code, $product_id)	 {
      $sql = "SELECT category_id FROM product_to_category WHERE product_id ='{$product_id}' AND store_code='{$store_code}'";
      $result = $this->db->query($sql);
      return $result->row['category_id'];
   }

   private function getStoreIDFromCode ($store_code) {
      $found = $this->db->get_multiple('store', "code = '{$store_code}'");
      return $found[0]['store_id'];
   }   

   private function get_productset_for_store($store_id) {
      $psets = $this->db->query("select ps.productset_id from store_productsets sp inner join productset ps on sp.productset_id = ps.productset_id where store_id='{$store_id}'");
      return $psets->rows;
   }   


}
?>