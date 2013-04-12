<?php

class ModelProductsetProduct extends Model {
    
    
	public function addRecord ($data) {
	    
	    $data['created_datetime'] = date(ISO_DATETIME_FORMAT);
	    $this->db->add('productset_product', $data);
	    
	}
	
	
	public function deleteRecord ($productset_code, $product_id) {
	    
	    $sql = "
	    	DELETE		J.*
	    	FROM		productset_product as J,
	    				productset as PS
	    	WHERE		1
	    		AND		J.productset_id = PS.productset_id
	    		AND		PS.code = '{$productset_code}'
	    		AND		J.product_id = '{$product_id}'
	    ";
	    
	    $this->db->query($sql);
		
	}
	
	
	public function getRecord ($productset_code, $product_id) {
	    
	    $sql = "
	    	SELECT		J.*
	    	FROM		productset_product as J,
	    				productset as PS
	    	WHERE		1
	    		AND		J.productset_id = PS.productset_id
	    		AND		PS.code = '{$productset_code}'
	    		AND		J.product_id = '{$product_id}'
	    ";
	    
		$query = $this->db->query($sql);
		
		return $query->row;
		
	}
	
	public function getRecordCount ($productset_code, $filterdata = array(), $viewing_user_id) {
	    
	    $this->load->model('user/productset');
	    
	    $productset_row = $this->model_user_productset->getProductsetByCode($productset_code);
	    $productset_id = $productset_row['productset_id'];
	    
	    $owner_user_id = $this->model_user_productset->getOwnerUserIDFromCode($productset_code);
	    
	    $this->load->model('user/user');
	    
	    $core_query = "
			SELECT 		p.*
			FROM 		
						product as p
			WHERE 	1
				AND	p.productset_id='{$productset_id}'";	    
	    
		$sql = $core_query;
		
		$query = $this->db->query($sql);
        
		return count($query->rows);
	}
	
	public function getRecords ($productset_code, $filterdata = array(), $viewing_user_id, $count_only=false) {
	    
	    $this->load->model('user/productset');
	    
	    $productset_row = $this->model_user_productset->getProductsetByCode($productset_code);
	    $productset_id = $productset_row['productset_id'];
	    
	    $owner_user_id = $this->model_user_productset->getOwnerUserIDFromCode($productset_code);
	    
	    $this->load->model('user/user');
	    
	    if ($this->model_user_user->isAdmin($viewing_user_id)) {
	        $access_type_clause = "'W'";	// Write access
	    } else {	        
	        $access_type_clause = "IF((u.user_id = '{$viewing_user_id}'), 'W', 'R')";    // Write or Read access depending on ownership
	    }
	    
	    $user_id_clause = "	AND	(ug.admin_flag = 1 OR p.user_id = {$owner_user_id}) ";
	    
	    $core_query = "
			SELECT 		p.*, pd.*, {$access_type_clause} as access_type_code, 
						u.user_id, u.username as user_name, 
						m.name as manufacturer_name,
						PVG.name as productvariantgroup_name,
						GL.name as min_gradelevel_name,
						GL2.name as max_gradelevel_name,
						(IF(J.id IS NOT NULL, '1', NULL)) as included
			FROM 		
						product as p
			
						LEFT JOIN product_description pd 
							ON (p.product_id = pd.product_id)
							
						INNER JOIN user as u
							ON (p.user_id = u.user_id)
							
						INNER JOIN user_group as ug
							ON (u.user_group_id = ug.user_group_id)
							
						LEFT JOIN manufacturer as m
							ON (p.manufacturer_id = m.manufacturer_id)
							
						LEFT JOIN product_variant_group as PVG
							ON (p.productvariantgroup_id = PVG.id)	

						LEFT JOIN grade_level as GL
							ON (p.min_gradelevel_id = GL.id)								

						LEFT JOIN grade_level as GL2
							ON (p.max_gradelevel_id = GL2.id)								
							
						LEFT JOIN productset_product as J
							ON (p.product_id = J.product_id AND J.productset_id = '{$productset_id}')
							
			WHERE 		1
				AND		pd.language_id = '{$this->language->getId()}' 
            AND      p.productset_id='{$productset_id}'
	            {$user_id_clause}   
	    ";	    
	    
		$sql = $core_query;

	    if (isset($filterdata['product_id'])) {
			$sql .= " AND p.product_id = '" . (int)$filterdata['product_id'] . "'";
		}			
		
		if (isset($filterdata['user_id'])) {
			$sql .= " AND p.user_id = '" . (int)$filterdata['user_id'] . "'";
		}			
	
		if (isset($filterdata['name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($filterdata['name']) . "%'";
		}
		
		if (isset($filterdata['ext_product_num'])) {
			$sql .= " AND p.ext_product_num LIKE '%" . $this->db->escape($filterdata['ext_product_num']) . "%'";
		}		

		if (isset($filterdata['manufacturer_name'])) {
			$sql .= " AND m.name LIKE '%" . $this->db->escape($filterdata['manufacturer_name']) . "%'";
		}
		
		if (isset($filterdata['productvariantgroup_name'])) {
			$sql .= " AND PVG.name LIKE '%" . $this->db->escape($filterdata['productvariantgroup_name']) . "%'";
		}		
		
		if (isset($filterdata['min_gradelevel_id'])) {
			$sql .= " AND p.min_gradelevel_id = '" . (int)$filterdata['min_gradelevel_id']."'";
		}
		
		if (isset($filterdata['max_gradelevel_id'])) {
			$sql .= " AND p.max_gradelevel_id = '" . (int)$filterdata['max_gradelevel_id']."'";
		}
		
		if (($filterdata['included'])=='1') {
			$sql .= " AND J.id IS NOT NULL";
		} elseif (($filterdata['included'])=='0') {
		    $sql .= " AND J.id IS NULL";
		}

		$sort_data = array(
		    'p.product_id',
		    'user_name',
			'pd.name',
		    'p.ext_product_num',
			'manufacturer_name',
		    'productvariantgroup_name',
		    'min_gradelevel_name',
		    'max_gradelevel_name',
		    'p.price',
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
			$sql .= " ORDER BY included DESC, pd.name";	
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
	
	
	public function processAssignmentForm ($productset_code, $form_data, $viewing_user_id) {
	    
	    $array_all = (array) $form_data['product_ids'];
	    
	    $array_selected = (array) $form_data['product_ids_selected'];
	    
	    $array_notselected = (array) array_diff($array_all, $array_selected);
	    
        
	    // extra security in case any Product IDs are form-hack-inserted
        
	    $accessible_product_ids = array();
        
		$product_records = $this->getRecords($productset_code, null, $viewing_user_id);
        foreach ($product_records as $product_record) {
            if ($product_record['access_type_code']=='W') {
                $accessible_product_ids[] = $product_record['product_id'];
            }
        }
        
        $array_selected = array_intersect($array_selected, $accessible_product_ids);
        
        $array_notselected = array_intersect($array_notselected, $accessible_product_ids);
        
        
	    foreach ($array_notselected as $product_id) {
	        if ($this->getRecord($productset_code, $product_id)) {
	            $this->deleteRecord($productset_code, $product_id);
	        }
	    }
	    	    
	    $this->load->model('user/productset');
	    $productset_row = $this->model_user_productset->getProductsetByCode($productset_code);
	    
	    $add_data['creator_user_id'] = $viewing_user_id;
	    $add_data['productset_id'] = $productset_row['productset_id'];
	    	    
	    foreach ($array_selected as $product_id) {
	        if (!$this->getRecord($productset_code, $product_id)) {
    	        $add_data['product_id'] = $product_id;
    	        $this->addRecord($add_data);
	        }
	    }
	    
	}
	

   private function getDefaultProductCategoryProductsetRelationships($productset_id) {
      $sql = "select * from product_to_category where productset_id='{$productset_id}' AND store_code='ZZZ'"; 
      $query = $this->db->query($sql);
      if (count($query->rows) > 0) {
         return $query->rows;
      } else {
         return null; // let the caller know early!
      }
   }


   public function buildProductToCategoryAssociations($store_code, $productset_ids) {
      //2a: Clean the entire dealer's p2c stuff since we're about to rebuild it, keeping it clean here folks.
      $clean_sql = "DELETE FROM product_to_category
                          WHERE  1           
                          AND    store_code = '{$store_code}'"; 
      $this->db->query($clean_sql);

      // 3. Now populate our product_to_category table (populating for each selected productset_id).
      foreach ($productset_ids as $productset_id) {
         // Grab our default 'ZZZ' store to help build the category_id, product_id, productset_id relationship.
         $productset = $this->getDefaultProductCategoryProductsetRelationships($productset_id);

         if ($productset) {
            foreach ($productset as $product) {
     
               // Product-Category assignments
               $product_to_category_insert_sql = "INSERT INTO product_to_category (store_code, product_id, category_id, productset_id) VALUES ('{$store_code}', '{$product['product_id']}', '{$product['category_id']}', '{$productset_id}')";    

               $this->db->query($product_to_category_insert_sql);
            }   
         }
      }   
   }

   public function buildRelatedProductAssociations($store_code, $productset_ids) {
      // Clean out all existing relations for the store_code for all productsets, we will rebuild them below.
      $clean_sql = "DELETE FROM product_related WHERE 1 and store_code='{$store_code}'";
      $this->db->query($clean_sql);

      // Get the default related products for this productset_id first.
      foreach ($productset_ids as $productset_id) {
         $related_products = $this->getDefaultRelatedProducts($productset_id);

         if ($related_products) {
            // Now, build the relationships again for related products for a store_code and productset_id.
            foreach ($related_products as $product) {
               $related_product_sql = "INSERT INTO product_related (product_id, store_code, related_id, productset_id) VALUES ('{$product['product_id']}', '{$store_code}', '{$product['related_id']}', '{$productset_id}')";
               $this->db->query($related_product_sql);
//echo $related_product_sql . "<br/>";
            }
         }
      }
   }

   private function getDefaultRelatedProducts($productset_id) {
      $sql = "SELECT * FROM product_related WHERE productset_id='{$productset_id}' AND store_code='ZZZ'";
      $query = $this->db->query($sql);
      if (count($query->rows) > 0) {
         return $query->rows;
      } else {
         return null; // let the caller know early.
      }
   }

   public function getProductsetName($productset_id) {
      $q = $this->db->query("SELECT name FROM productset WHERE productset_id='{$productset_id}'");
      return $q->row['name'];
   }
}

?>
