<?php
ini_set('memory_limit', -1);

class ModelStoreProduct extends Model {
    
    
	public function addRecord ($data) {
	    
	    $data['created_datetime'] = date(ISO_DATETIME_FORMAT);
	    $this->db->add('store_product', $data);
	    
	}
	
	
	public function getRecord ($store_code, $product_id) {
	    
	    $sql = "
	    	SELECT		J.*, P.price as default_price
	    	FROM		store_product as J,
	    				store as S,
	    				product as P
	    	WHERE		1
	    		AND		J.store_code = S.code
	    		AND		S.code = '{$store_code}'
	    		AND		J.product_id = P.product_id
	    		AND		J.product_id = '{$product_id}'
	    ";
		$query = $this->db->query($sql);
		
		return $query->row;
		
	}
	
   // KMC - Builds inner join sql when filtering on dates for pricing.
   private function buildSpecialJoinSQL($store_code, $filterdata, &$special_join_select) {
      $sql = '';

      $special_join_select = ", pspec.price as product_special, pspec.date_start, pspec.date_end ";

      if (isset($filterdata['start_date']) && isset($filterdata['end_date'])) {
        $sql = " INNER JOIN product_special as pspec ON
                 pspec.product_id = P.product_id AND
                 pspec.store_code = '{$store_code}' AND
                 pspec.date_start='{$filterdata['start_date']}' AND 
                 pspec.date_end='{$filterdata['end_date']}' ";

      } else if (isset($filterdata['start_date'])) {

        $sql = " INNER JOIN product_special as pspec ON
                 pspec.product_id = P.product_id AND
                 pspec.store_code = '{$store_code}' AND
                 pspec.date_start='{$filterdata['start_date']}' ";

      } else if (isset($filterdata['end_date'])) {
        $sql = " INNER JOIN product_special as pspec ON
                 pspec.product_id = P.product_id AND
                 pspec.store_code = '{$store_code}' AND
                 pspec.date_end='{$filterdata['end_date']}' ";
      } 
      return $sql;
   }

   // KMC - Builds default select for product specials (when not filtering on dates)
   public function buildSpecialSelectSQL($store_code) {
        $sql = '';
		  $sql = ", (SELECT 		price
                	FROM		product_special
                	WHERE 		1
                	   AND	store_code = '{$store_code}' 
                		AND 	product_id = P.product_id
                		LIMIT 1							
						) as product_special,
				  (SELECT 	date_start
                  FROM		product_special
                	WHERE 		1
                	   AND	store_code = '{$store_code}' 
                	   AND 	product_id = P.product_id
                	   LIMIT 1							
						) as date_start,
				  (SELECT 		date_end
                  FROM		product_special
                	WHERE 		1
                	   AND	store_code = '{$store_code}' 
                		AND 	product_id = P.product_id
                	   LIMIT 1							
						) as date_end ";
        return $sql;
   }	

   private function getQuickRecordCount($store_id) {
      $records = $this->db->query("select product_id from product where productset_id in (select productset_id from store_productsets where store_id='{$store_id}')");
      return $records->num_rows;
   }

	public function getRecords ($store_code, $filterdata = array(), $viewing_user_id, $count_only=false, $exclude_product_id=false) {
	    
	   $this->load->model('user/store');
	   $store_row = $this->model_user_store->getStoreByCode($store_code);
	   $store_id = $store_row['store_id'];
      if ($count_only && 
          !isset($filterdata['ext_product_num']) && !isset($filterdata['category_id']) &&
          !isset($filterdata['name']) && !isset($filterdata['manufacturer_name']) &&
          !isset($filterdata['discount_level'])) { 
             return $this->getQuickRecordCount($store_id); 
      }
	    
	   $this->load->model('user/user');
	    	    
		$owner_user_id = $this->model_user_store->getOwnerUserIDFromCode($store_code);
	    
		if ($this->model_user_user->isAdmin($viewing_user_id)) {
		    $user_id_clause = "	AND	(UG.admin_flag = 1 OR P.user_id = '{$owner_user_id}') ";
	    } else {
	        $user_id_clause = "	AND	(UG.admin_flag = 1 OR P.user_id = '{$viewing_user_id}') AND S.user_id = {$viewing_user_id} ";
	    }

	    if ($exclude_product_id) {
	        $exclude_product_id_clause = " AND P.product_id != '{$exclude_product_id}' ";
	    }
	    
		if (isset($filterdata['category_id']) && !($filterdata['parent_category'])) {
			$category_join = "INNER JOIN product_to_category as P2C
						ON (		1
							AND		P2C.store_code = '{$store_code}'
							AND		P2C.product_id = P.product_id
							AND		P2C.category_id = '{$filterdata['category_id']}'
						)
			";
		}	    
      // Here we are getting products under a specific parent category
      if (isset($filterdata['category_id']) && ($filterdata['parent_category'])) {
         $category_join = "INNER JOIN category as CAT 
                 ON (1 
                     AND CAT.parent_id  = '{$filterdata['category_id']}'
                     AND CAT.store_code = '{$store_code}'
                 ) 
                 INNER JOIN product_to_category as P2C
                 ON (1 
                     AND P2C.category_id = CAT.category_id
                     AND P2C.product_id = P.product_id
                     AND P2C.store_code = '{$store_code}'
                 )
            ";
      }

      $special_join = '';
      $special_join_select = '';
      $special_select = '';
      
      if (isset($filterdata['start_date']) || isset($filterdata['end_date'])) {
         $special_join = $this->buildSpecialJoinSQL($store_code, $filterdata, $special_join_select);
      } else {
         $special_select = $this->buildSpecialSelectSQL($store_code);
      }

	   // Begin Core SQL  
	   $core_query = "
			SELECT 	P.*, PD.*,
						U.user_id,
						U.username as user_name,
						M.name as manufacturer_name,
                  /*PVG.name as productvariantgroup_name,
						GL.name as min_gradelevel_name,
                  GL2.name as max_gradelevel_name,*/
						J.quantity,
						J.stock_status_id, SS.name as stock_status_name,
						TC.tax_class_id, TC.title as tax_class_name,
						P.price as default_price,
						J.price,
						J.featured_flag as featured,
						J.cartstarter_flag as cartstarter,
                  J.cataloghome_flag as cataloghome,
						J.excluded_flag as excluded, 
                  PS.code as catalogcode
                  {$special_join_select}
                  {$special_select}
                 
			FROM 		
						product as P
						
                  {$special_join}

						{$category_join}
							
						INNER JOIN productset_product as PP
							ON (P.product_id = PP.product_id)

    				   INNER JOIN store_productsets as SP
    						ON (PP.productset_id = SP.productset_id)

                  INNER JOIN productset as PS
                     ON (SP.productset_id =  PS.productset_id)   							

    					INNER JOIN store as S
    						ON (SP.store_id = S.store_id)

						INNER JOIN user as U
							ON (P.user_id = U.user_id)

						INNER JOIN user_group as UG
							ON (U.user_group_id = UG.user_group_id)
			
						LEFT JOIN product_description as PD 
							ON (P.product_id = PD.product_id)
							
						LEFT JOIN manufacturer as M
							ON (P.manufacturer_id = M.manufacturer_id)
							
						/*LEFT JOIN product_variant_group as PVG
                     ON (P.productvariantgroup_id = PVG.id)	 

						LEFT JOIN grade_level as GL
							ON (P.min_gradelevel_id = GL.id)								

						LEFT JOIN grade_level as GL2
                  ON (P.max_gradelevel_id = GL2.id)*/
							
						LEFT JOIN store_product as J
							ON (P.product_id = J.product_id AND J.store_code = '{$store_code}')
							
						LEFT JOIN stock_status as SS
							ON (J.stock_status_id = SS.stock_status_id AND SS.language_id = 1)
							
						LEFT JOIN tax_class as TC
							ON (J.tax_class_id = TC.tax_class_id)							
							
			WHERE 		1
				AND		SP.store_id = '{$store_id}'
				AND		PD.language_id = '{$this->language->getId()}' 
	            {$user_id_clause}
	            {$exclude_product_id_clause}
	    ";	    
		
       $sql = $core_query;
//echo $sql; 
	    if (isset($filterdata['product_id'])) {
			$sql .= " AND P.product_id = '" . (int)$filterdata['product_id'] . "'";
		}			
		
		if (isset($filterdata['user_id'])) {
			$sql .= " AND P.user_id = '" . (int)$filterdata['user_id'] . "'";
		}			
	
		if (isset($filterdata['name'])) {
			$sql .= " AND PD.name LIKE '%" . $this->db->escape($filterdata['name']) . "%'";
		}

		if (isset($filterdata['ext_product_num'])) {
			$sql .= " AND P.ext_product_num LIKE '%" . $this->db->escape($filterdata['ext_product_num']) . "%'";
		}
		
		if (isset($filterdata['manufacturer_name'])) {
			$sql .= " AND M.name LIKE '%" . $this->db->escape($filterdata['manufacturer_name']) . "%'";
		}		
		
		if (isset($filterdata['productvariantgroup_name'])) {
			$sql .= " AND PVG.name LIKE '%" . $this->db->escape($filterdata['productvariantgroup_name']) . "%'";
		}		
		
		if (isset($filterdata['min_gradelevel_id'])) {
			$sql .= " AND P.min_gradelevel_id = '" . (int)$filterdata['min_gradelevel_id']."'";
		}	
		
		if (isset($filterdata['max_gradelevel_id'])) {
			$sql .= " AND P.max_gradelevel_id = '" . (int)$filterdata['max_gradelevel_id']."'";
		}	
		
		if (isset($filterdata['stock_status_id'])) {
			$sql .= " AND J.stock_status_id = '{$filterdata['stock_status_id']}' ";
		}
		
		if (isset($filterdata['tax_class_id'])) {
			$sql .= " AND J.tax_class_id = '{$filterdata['tax_class_id']}' ";
		}		

		if (($filterdata['featured'])=='1') {
			$sql .= " AND J.featured_flag = '1' ";
		} elseif (($filterdata['featured'])=='0') {
		    $sql .= " AND (J.id IS NULL OR J.featured_flag='0' ) ";
		}		

		if (($filterdata['cartstarter'])=='1') {
			$sql .= " AND J.cartstarter_flag = '1' ";
		} elseif (($filterdata['cartstarter'])=='0') {
		    $sql .= " AND (J.id IS NULL OR J.cartstarter_flag='0' ) ";
		}			

		if (($filterdata['cataloghome'])=='1') {
			$sql .= " AND J.cataloghome_flag = '1' ";
		} elseif (($filterdata['cataloghome'])=='0') {
		    $sql .= " AND (J.id IS NULL OR J.cataloghome_flag='0' ) ";
		}			
		
		if (($filterdata['excluded'])=='1') {
			$sql .= " AND J.excluded_flag = '1' ";
		} elseif (($filterdata['excluded'])=='0') {
		    $sql .= " AND (J.id IS NULL OR J.excluded_flag='0' ) ";
		}

      if (isset($filterdata['discount_level'])) {
          $sql .= " AND (P.discount_level = '{$filterdata['discount_level']}') ";
      }

		$sort_data = array(
		    'P.product_id',
		    'user_name',
			'PD.name',
		    'P.ext_product_num',
			'manufacturer_name',
			'productvariantgroup_name',
		    'min_gradelevel_name',
		    'max_gradelevel_name',
		    'J.quantity',
            'stock_status_name',
		    'tax_class_name',
		    'J.price',
		    'default_price',
		    'product_special',
		    'featured',
		    'cartstarter',
          'cataloghome',
          'excluded',
          'discount_level'
		);
		
		if ($count_only) {
		    unset($filterdata['sort']);
		    unset($filterdata['order']);
		    unset($filterdata['start']);
		    unset($filterdata['limit']);
		}			
		
	    $sql .= "GROUP BY	P.product_id";
	    
	    if ($filterdata['sort']=='J.quantity' || $filterdata['sort']=='default_price' || $filterdata['sort']=='J.price' || $filterdata['sort']=='product_special') {
	        $sort_numeric_prepend = " + 0";
	    }
		
		if (in_array(@$filterdata['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $filterdata['sort'].$sort_numeric_prepend;	
		} else {
			$sql .= " ORDER BY excluded DESC, PD.name";	
		}
		
		if (@$filterdata['order'] == 'DESC') {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($filterdata['start']) || isset($filterdata['limit'])) {
			$sql .= " LIMIT " . (int)$filterdata['start'] . "," . (int)$filterdata['limit'];
		}	
echo '<!--'.$sql.'-->';
		$query = $this->db->query($sql);
                  
		if ($count_only) {
		    return count($query->rows);
		} else {
		    return $query->rows;
		}
		
	}
	
	
	public function processListForm ($store_code, $form_data, $viewing_user_id) {

       $proc_type = $form_data['process_type'];

       if ($proc_type == 'productselection') {
	       $this->processListForm_excluded($store_code, $form_data, $viewing_user_id);
       }

       if ($proc_type == 'pricing') {
          $this->processListForm_saleprice($store_code, $form_data, $viewing_user_id);
          $this->processListForm_storeprice($store_code, $form_data, $viewing_user_id);
       }

       if ($proc_type == 'featured') {
	       $this->processListForm_featured($store_code, $form_data, $viewing_user_id);
          $this->processListForm_cataloghome($store_code, $form_data, $viewing_user_id);
       }
	}

   public function processListForm_saleprice($store_code, $form_data, $viewing_user_id) {

//var_dump($form_data); exit;
      foreach ($form_data as $key => $value) {
         // not wanting arrays right now. just form data.
         if (!is_array($value)) {
           //echo $key . ":" . $value . "<br/>";
            // fetch sale price
            if (strpos($key, "sale_price_")===false) {  // don't  have a sale_price item
            } else {
               // check for empty value first! If empty, don't insert... 
               if (!empty($value)) {
                  // get id and pick off the start/end dates.
                  $us = strrpos($key, "_");
                  $id = substr($key, $us+1);
                  if (is_numeric($id)) { 
                     $sale_price = $value;
                     $sale_start = $form_data['sale_start_'.$id];
                     $sale_end  = $form_data['sale_end_'.$id];
                  }
                  //echo $sale_price . ':' . $sale_start . ':' . $sale_end . '<br/>';
                  // clean up first.
  		            $this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE store_code='{$store_code}' AND product_id = '" . (int)$id . "'");
                  //echo 'cleaning up.. product_id='.$id;
                  // now put updated ones in.
   			      $this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET store_code='{$store_code}', product_id = '" . (int)$id. "', price = '" . $sale_price . "', date_start = '" . $this->db->escape($sale_start) . "', date_end = '" . $this->db->escape($sale_end) . "'");
               }
            }
         } 
      }
   }

   public function processListForm_storeprice($store_code, $form_data, $viewing_user_id) {

      //var_dump($form_data);
      foreach ($form_data as $key => $value) {
         // not wanting arrays right now. just form data.
         if (!is_array($value)) {
           //echo $key . ":" . $value . "<br/>";
            if (!empty($value)) { // only want items with values!
               // fetch sale price
               if (strpos($key, "store_price_")===false) {  // don't  have a sale_price item
               } else {
                  // get id and pick off the start/end dates.
                  $us = strrpos($key, "_");
                  $id = substr($key, $us+1);
                  if (is_numeric($id)) { 
                     $store_price = $value;
                  }
                  //echo 'STORE PRICE: ' . $store_price . '<br/>';
                  // now put updated ones in.
				      $this->db->query("UPDATE " . DB_PREFIX . "store_product SET price='{$store_price}' WHERE product_id = '" . (int)$id. "' AND store_code='{$store_code}'");
               }
            }
         } 
      }
   }

	public function processListForm_featured ($store_code, $form_data, $viewing_user_id) {
	    
	    $array_all = (array) $form_data['featured_product_ids'];
	    
	    $array_selected = (array) $form_data['featured_product_ids_selected'];
	    
	    $array_notselected = (array) array_diff($array_all, $array_selected);
	    
	    
	    // these are checked, so featured
	    
	    $add_data['creator_user_id'] = $viewing_user_id;
	    $add_data['store_code'] = $store_code;
	    $add_data['featured_flag'] = '1';
	    $add_data['stock_status_id'] = $this->config->get('config_stock_status_id');
	    $add_data['quantity'] = '999';
	    
	    foreach ($array_selected as $product_id) {
	        
	        if (!$this->getRecord($store_code, $product_id)) {
	            
    	        $add_data['product_id'] = $product_id;
    	        $this->addRecord($add_data);
    	        
	        } else {
	            
	            $this->recordSetFeaturedFlag($store_code, $product_id, '1');
	                    
	        }
	        
	    }	    

	    // these are unchecked, so NOT featured
        
	    foreach ($array_notselected as $product_id) {
	        if ($this->getRecord($store_code, $product_id)) {
	            $this->recordSetFeaturedFlag($store_code, $product_id, '0');
	        }
	    }	    
	}
	
	
	public function processListForm_cataloghome ($store_code, $form_data, $viewing_user_id) {
	    $array_all = (array) $form_data['cataloghome_product_ids'];
	    
	    $array_selected = (array) $form_data['cataloghome_product_ids_selected'];
	    
	    $array_notselected = (array) array_diff($array_all, $array_selected);
	    
	    // these are checked, so cataloghome
	    $add_data['creator_user_id'] = $viewing_user_id;
	    $add_data['store_code'] = $store_code;
	    $add_data['cataloghome_flag'] = '1';
	    $add_data['stock_status_id'] = $this->config->get('config_stock_status_id');
	    $add_data['quantity'] = '999';

	    foreach ($array_selected as $product_id) {
	        
	        if (!$this->getRecord($store_code, $product_id)) {
	            
    	        $add_data['product_id'] = $product_id;
    	        $this->addRecord($add_data);
    	        
	        } else {
	            
	            $this->recordSetCatalogHomeFlag($store_code, $product_id, '1');
	                    
	        }
	    }	    

	    // these are unchecked, so NOT cataloghome
	    foreach ($array_notselected as $product_id) {
	        if ($this->getRecord($store_code, $product_id)) {
	            $this->recordSetCatalogHomeFlag($store_code, $product_id, '0');
	        }
	    }	    
	}	

	public function processListForm_cartstarter ($store_code, $form_data, $viewing_user_id) {
	    
	    $array_all = (array) $form_data['cartstarter_product_ids'];
	    
	    $array_selected = (array) $form_data['cartstarter_product_ids_selected'];
	    
	    $array_notselected = (array) array_diff($array_all, $array_selected);
	    
	    // these are checked, so cartstarter
	    $add_data['creator_user_id'] = $viewing_user_id;
	    $add_data['store_code'] = $store_code;
	    $add_data['cartstarter_flag'] = '1';
	    $add_data['stock_status_id'] = $this->config->get('config_stock_status_id');
	    $add_data['quantity'] = '999';
	    
	    foreach ($array_selected as $product_id) {
	        
	        if (!$this->getRecord($store_code, $product_id)) {
	            
    	        $add_data['product_id'] = $product_id;
    	        $this->addRecord($add_data);
    	        
	        } else {
	            
	            $this->recordSetCartstarterFlag($store_code, $product_id, '1');
	                    
	        }
	    }	    

	    // these are unchecked, so NOT cartstarter
	    foreach ($array_notselected as $product_id) {
	        if ($this->getRecord($store_code, $product_id)) {
	            $this->recordSetCartstarterFlag($store_code, $product_id, '0');
	        }
	    }	    
	}	
	
	
	public function processListForm_excluded ($store_code, $form_data, $viewing_user_id) {
	    
	    $array_all = (array) $form_data['excluded_product_ids'];
	    
	    $array_selected = (array) $form_data['excluded_product_ids_selected'];
	    
	    $array_notselected = (array) array_diff($array_all, $array_selected);
	    
	    
	    // these are checked, so excluded
	    
	    $add_data['creator_user_id'] = $viewing_user_id;
	    $add_data['store_code'] = $store_code;
	    $add_data['excluded_flag'] = '1';
	    $add_data['stock_status_id'] = $this->config->get('config_stock_status_id');
	    $add_data['quantity'] = '999';
	    
	    foreach ($array_selected as $product_id) {
	        
	        if (!$this->getRecord($store_code, $product_id)) {
	            
    	        $add_data['product_id'] = $product_id;
    	        $this->addRecord($add_data);
    	        
	        } else {
	            
	            $this->recordSetExcludedFlag($store_code, $product_id, '1');
	                    
	        }
	        
	    }	    

	    // these are unchecked, so NOT excluded
        
	    foreach ($array_notselected as $product_id) {
	        if ($this->getRecord($store_code, $product_id)) {
	            $this->recordSetExcludedFlag($store_code, $product_id, '0');
	        }
	    }	    
	    
	    
	}
	
   public function recordSetUpdateSalePrice($store_code, $product_id, $sale_price) {
      $where = "store_code = '{$store_code}' AND product_id = '{$product_id}' ";
      //$data['']
   }
	
	public function recordSetFeaturedFlag ($store_code, $product_id, $featured_flag) {
	    
	    $where = "store_code = '{$store_code}' AND product_id = '{$product_id}' ";
	    
	    $data['featured_flag'] = $featured_flag;
	    
	    $this->db->update('store_product', $data, $where);
	    
	}
	

	public function recordSetCartstarterFlag ($store_code, $product_id, $cartstarter_flag) {
	    
	    $where = "store_code = '{$store_code}' AND product_id = '{$product_id}' ";
	    
	    $data['cartstarter_flag'] = $cartstarter_flag;
	    
	    $this->db->update('store_product', $data, $where);
	    
	}

	public function recordSetCatalogHomeFlag ($store_code, $product_id, $cataloghome_flag) {
	    
	    $where = "store_code = '{$store_code}' AND product_id = '{$product_id}' ";
	    
	    $data['cataloghome_flag'] = $cataloghome_flag;
	    
	    $this->db->update('store_product', $data, $where);
	    
	}
		
	
	public function recordSetExcludedFlag ($store_code, $product_id, $excluded_flag) {
	    
	    $where = "store_code = '{$store_code}' AND product_id = '{$product_id}' ";
	    
	    $data['excluded_flag'] = $excluded_flag;
	    
	    $this->db->update('store_product', $data, $where);
	    
	}
	
	
	public function editRecord ($store_code, $product_id, $data, $viewing_user_id=null) {
	    
	    
	    $record_exists = $this->db->get_record('store_product', " store_code = '{$store_code}' AND product_id = '{$product_id}' ");
	    
	    if (!$record_exists) {
	        $add_data['store_code'] = $store_code;
	        $add_data['product_id'] = $product_id;
	        $add_data['creator_user_id'] = $viewing_user_id;
	        $add_data['created_datetime'] = date(ISO_DATETIME_FORMAT);
	        $this->db->add('store_product', $add_data);
	    }
	    
	    if (trim($data['price'])=='') {
	        $data['price'] = 'NULL';
	    }

      // if we modify the name or grade level we have to update the main product table.
      // SPS todo
      $product_sql = 
         " UPDATE " . DB_PREFIX . "product
           SET
           ext_product_num = '" . $this->db->escape(@$data['ext_product_num']) . "',
           min_gradelevel_id = '" . (int)@$data['min_gradelevel_id'] . "',
           max_gradelevel_id = '" . (int)@$data['max_gradelevel_id'] . "',
           price = '" . (float)@$data['default_price'] . "',
           discount_level = '" . (int)@$data['discount_level'] . "',
           manufacturer_id = '" . (int)@$data['manufacturer_id'] . "'
           WHERE 1
           AND product_id = '" . (int)$product_id . "' AND productset_id = '" . (int)$data['productset_id'] . "'";

	   $this->db->query($product_sql);	

      $pd_sql =
         " UPDATE " . DB_PREFIX . "product_description
           SET
           name = '" . $this->db->escape(@$data['name']) . "',
           description = '" . $this->db->escape(@$data['description']) . "'
           WHERE 1 AND product_id = '" . (int)$product_id . "'";

      $this->db->query($pd_sql);

		$sql = "
			UPDATE " . DB_PREFIX . "store_product
			SET 
					quantity = '" . (int)@$data['quantity'] . "',
					stock_status_id = '" . (int)@$data['stock_status_id'] . "',
					featured_flag = '" . (int)@$data['featured_flag'] . "',
					cartstarter_flag = '" . (int)@$data['cartstarter_flag'] . "',
					excluded_flag = '" . (int)@$data['excluded_flag'] . "',
					cataloghome_flag = '" . (int)@$data['cataloghome_flag'] . "',
					price = " . @$data['price'] . ",
					tax_class_id = '" . (int)@$data['tax_class_id'] . "'		
			WHERE 	1
				AND	store_code = '{$store_code}'
				AND	product_id = '" . (int)$product_id . "'			
		";
		
		$this->db->query($sql);
	
		
		
		if (isset($data['product_discount'])) {
		   $this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE store_code='{$store_code}' AND product_id = '" . (int)$product_id . "'");

			foreach ($data['product_discount'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET store_code='{$store_code}', product_id = '" . (int)$product_id . "', quantity = '" . (int)$value['quantity'] . "', discount = '" . (float)$value['discount'] . "'");
			}
		}


		if (isset($data['product_special'])) {
		   $this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE store_code='{$store_code}' AND product_id = '" . (int)$product_id . "'");

			foreach ($data['product_special'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET store_code='{$store_code}', product_id = '" . (int)$product_id . "', price = '" . (float)$value['price'] . "', date_start = '" . $this->db->escape($value['date_start']) . "', date_end = '" . $this->db->escape($value['date_end']) . "'");
			}
		}

		
		
		if (isset($data['product_category'])) {
		   $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE store_code='{$store_code}' AND product_id = '" . (int)$product_id . "'");

			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET store_code='{$store_code}', product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "', productset_id = '" . (int)$data['productset_id'] . "'");
			}		
		}


		if (isset($data['product_related'])) {
		   $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE store_code='{$store_code}' AND product_id = '" . (int)$product_id . "'");
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET store_code='{$store_code}', product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "', productset_id='" . (int)$data['productset_id'] . "'");
			}
		}
		
	}
	
    
	public function getProductSpecial ($store_code, $product_id) {
	    
	    // this reads ultimately from table global_special
	    $sql = "
			SELECT		product_special
			FROM		product_specials_global_2
			WHERE		1
				AND		store_code = '{$store_code}'
				AND		product_id = '{$product_id}'
		";
	    
		$query = $this->db->query($sql);

		if ($query->num_rows) {
			return $query->row['product_special'];		
		} else {
			return FALSE;
		}
		
	}
	
	
	public function getTotalProductsByTaxClassId ($store_code, $tax_class_id) {
	    
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store_product WHERE store_code = '{$store_code}' AND tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
		
	}	
	
	
	public function getUnjunctionedProductIDs ($store_code) {
	    
	    $sql = "
			SELECT DISTINCT P.product_id

			FROM 		
						product as P
							
						INNER JOIN productset_product as PP
							ON (P.product_id = PP.product_id)

    						INNER JOIN store_productsets as SP
    							ON (PP.productset_id = SP.productset_id)
    							
    							INNER JOIN store as S
    							ON (SP.store_id = S.store_id)
							
						LEFT JOIN store_product as J
							ON (P.product_id = J.product_id AND J.store_code = '{$store_code}')

			WHERE 		1
				AND		S.code = '{$store_code}'
				AND		J.id IS NULL

	    ";	    
		$result = $this->db->query($sql);
		
		foreach ($result->rows as $row) {
		    $final_result[] = $row['product_id'];
		}
			
		return (array) $final_result;
	    
	}	
		
	
	public function createUnjunctionedProductRecords ($store_code) {
	    
	    if (!$store_code) return;
	    
	    $product_ids = $this->getUnjunctionedProductIDs($store_code);
       $store_result = $this->db->get_multiple('store', "code = '{$store_code}'");
       $creator_user_id = $store_result[0]['user_id'];

	    $add_data['creator_user_id'] = $creator_user_id;
	    $add_data['store_code'] = $store_code;
	    $add_data['stock_status_id'] = $this->config->get('config_stock_status_id');
	    $add_data['quantity'] = '999';
	    $data['created_datetime'] = date(ISO_DATETIME_FORMAT);
	    $product_ids = array_unique($product_ids,SORT_NUMERIC); // Fixes: Duplicate products on store creation	    
	    foreach ($product_ids as $product_id) {
	        $add_data['product_id'] = $product_id; 
	        $this->db->add('store_product', $add_data);
	        
	    }
	    
	}	
	
	
}

?>
